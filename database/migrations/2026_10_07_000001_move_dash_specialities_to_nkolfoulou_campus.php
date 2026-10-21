<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MoveDashSpecialitiesToNkolfoulouCampus extends Migration
{
    public function up()
    {
        $this->assertRequiredSchema();

        DB::transaction(function () {
            $campus = $this->nkolfoulouCampus();
            $classeIds = $this->dashSpecialiteIds();
            if (count($classeIds) === 0) {
                throw new RuntimeException('No specialties beginning with "-" were found in the current database.');
            }

            $this->assertNoAmbiguousUnlinkedRecords($classeIds);

            $this->moveCampusSpecialites($campus->id, $classeIds);
            $this->updateCampusByClasses('inscription', $campus->id, $classeIds);
            $this->updateCampusByClasses('classe_matiere', $campus->id, $classeIds);

            $inscriptionIds = $this->inscriptionIdsForClasses($classeIds);
            $payementIds = $this->updatePayements($campus->id, $classeIds, $inscriptionIds);
            $this->updateMouvementsFinanciers($campus->id, $inscriptionIds, $payementIds);
            $this->updateNotes($campus->id, $classeIds);
        });
    }

    public function down()
    {
        throw new RuntimeException('This data migration cannot be safely reversed because it does not retain prior campus assignments.');
    }

    private function assertRequiredSchema()
    {
        $requiredColumns = array(
            'campus' => array('id'),
            'classe' => array('id'),
            'inscription' => array('id', 'id_eleve', 'annee_scolaire', 'id_classe', 'id_campus'),
            'payement' => array('id', 'id_eleve', 'annee_scolaire', 'id_inscription', 'id_campus'),
            'note' => array('id', 'id_eleve', 'annee_scolaire', 'id_classe', 'id_classe_matiere', 'id_campus'),
        );

        foreach ($requiredColumns as $table => $columns) {
            if (!Schema::hasTable($table)) {
                throw new RuntimeException("Required table {$table} does not exist.");
            }

            foreach ($columns as $column) {
                if (!Schema::hasColumn($table, $column)) {
                    throw new RuntimeException("Required column {$table}.{$column} does not exist.");
                }
            }
        }

        if (!Schema::hasTable('campus_specialite')) {
            throw new RuntimeException('Required table campus_specialite does not exist.');
        }

        foreach (array('id_campus', 'id_classe') as $column) {
            if (!Schema::hasColumn('campus_specialite', $column)) {
                throw new RuntimeException("Required column campus_specialite.{$column} does not exist.");
            }
        }

        if (Schema::hasTable('mouvements_financiers')) {
            foreach (array('id_campus', 'id_inscription', 'id_payement') as $column) {
                if (!Schema::hasColumn('mouvements_financiers', $column)) {
                    throw new RuntimeException("Required column mouvements_financiers.{$column} does not exist.");
                }
            }
        }
    }

    private function nkolfoulouCampus()
    {
        if (Schema::hasColumn('campus', 'localisation')) {
            $locatedCampuses = DB::table('campus')
                ->whereRaw("LOWER(TRIM(COALESCE(localisation, ''))) = ?", array('nkolfoulou campus b'))
                ->orderBy('id')
                ->get();

            if (count($locatedCampuses) === 1) {
                return $locatedCampuses[0];
            }
            if (count($locatedCampuses) > 1) {
                throw new RuntimeException('More than one campus has the exact location "Nkolfoulou Campus B"; refusing to guess the target.');
            }
        }

        throw new RuntimeException('The campus with the exact location "Nkolfoulou Campus B" could not be identified.');
    }

    private function dashSpecialiteIds()
    {
        $conditions = array();
        foreach (array('code', 'nom', 'nom_anglais') as $column) {
            if (Schema::hasColumn('classe', $column)) {
                $conditions[] = "COALESCE({$column}, '') LIKE '-%'";
            }
        }

        if (count($conditions) === 0) {
            throw new RuntimeException('No specialty code or name column was found in the classe table.');
        }

        return DB::table('classe')
            ->whereRaw('(' . implode(' OR ', $conditions) . ')')
            ->lists('id');
    }

    private function moveCampusSpecialites($idCampus, $classeIds)
    {
        DB::table('campus_specialite')
            ->whereIn('id_classe', $classeIds)
            ->delete();

        foreach ($classeIds as $idClasse) {
            $this->insertPivot('campus_specialite', array(
                'id_campus' => $idCampus,
                'id_classe' => $idClasse,
            ));
        }
    }

    private function insertPivot($table, array $data)
    {
        $exists = DB::table($table);
        foreach ($data as $column => $value) {
            $exists->where($column, '=', $value);
        }

        if ($exists->first()) {
            return;
        }

        if (Schema::hasColumn($table, 'created_at')) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        if (Schema::hasColumn($table, 'updated_at')) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        DB::table($table)->insert($data);
    }

    private function updateCampusByClasses($table, $idCampus, $classeIds)
    {
        if (!Schema::hasTable($table)
            || !Schema::hasColumn($table, 'id_campus')
            || !Schema::hasColumn($table, 'id_classe')) {
            return;
        }

        DB::table($table)
            ->whereIn('id_classe', $classeIds)
            ->update(array('id_campus' => $idCampus));
    }

    private function inscriptionIdsForClasses($classeIds)
    {
        return DB::table('inscription')
            ->whereIn('id_classe', $classeIds)
            ->lists('id');
    }

    private function updatePayements($idCampus, $classeIds, $inscriptionIds)
    {
        $payementIds = array();
        if (Schema::hasColumn('payement', 'id_inscription') && count($inscriptionIds) > 0) {
            $payementIds = DB::table('payement')
                ->whereIn('id_inscription', $inscriptionIds)
                ->lists('id');

            DB::table('payement')
                ->whereIn('id_inscription', $inscriptionIds)
                ->update(array('id_campus' => $idCampus));
        }

        $unlinkedPayementIds = $this->updateUnlinkedStudentYearRows('payement', $idCampus, $classeIds);
        return array_values(array_unique(array_merge($payementIds, $unlinkedPayementIds)));
    }

    private function updateMouvementsFinanciers($idCampus, $inscriptionIds, $payementIds)
    {
        if (!Schema::hasTable('mouvements_financiers')
            || !Schema::hasColumn('mouvements_financiers', 'id_campus')) {
            return;
        }

        if (Schema::hasColumn('mouvements_financiers', 'id_inscription') && count($inscriptionIds) > 0) {
            DB::table('mouvements_financiers')
                ->whereIn('id_inscription', $inscriptionIds)
                ->update(array('id_campus' => $idCampus));
        }

        if (Schema::hasColumn('mouvements_financiers', 'id_payement') && count($payementIds) > 0) {
            DB::table('mouvements_financiers')
                ->whereIn('id_payement', $payementIds)
                ->update(array('id_campus' => $idCampus));
        }
    }

    private function updateNotes($idCampus, $classeIds)
    {
        $this->updateCampusByClasses('note', $idCampus, $classeIds);

        if (Schema::hasTable('classe_matiere')
            && Schema::hasColumn('note', 'id_classe_matiere')
            && Schema::hasColumn('classe_matiere', 'id_classe')) {
            $classeMatiereIds = DB::table('classe_matiere')
                ->whereIn('id_classe', $classeIds)
                ->lists('id');

            if (count($classeMatiereIds) > 0) {
                DB::table('note')
                    ->whereIn('id_classe_matiere', $classeMatiereIds)
                    ->update(array('id_campus' => $idCampus));
            }
        }

        $this->updateUnlinkedStudentYearRows('note', $idCampus, $classeIds);
    }

    private function updateUnlinkedStudentYearRows($table, $idCampus, $classeIds)
    {
        $updatedIds = array();

        if (!Schema::hasColumn($table, 'id_eleve')
            || !Schema::hasColumn($table, 'annee_scolaire')) {
            return $updatedIds;
        }

        $updatedIds = $this->unlinkedStudentYearQuery($table, $classeIds, false)->lists('id');
        $this->unlinkedStudentYearQuery($table, $classeIds, false)
            ->update(array('id_campus' => $idCampus));

        return $updatedIds;
    }

    private function assertNoAmbiguousUnlinkedRecords($classeIds)
    {
        foreach (array('payement', 'note') as $table) {
            if (!Schema::hasColumn($table, 'id_eleve')
                || !Schema::hasColumn($table, 'annee_scolaire')) {
                continue;
            }

            if ($this->unlinkedStudentYearQuery($table, $classeIds, true)->first()) {
                throw new RuntimeException(
                    "Cannot safely assign unlinked {$table} records: at least one student has another specialty enrollment in the same school year."
                );
            }
        }
    }

    private function unlinkedStudentYearQuery($table, $classeIds, $ambiguous)
    {
        $query = DB::table($table);

        $relationshipColumns = array('id_inscription');
        if ($table === 'note') {
            $relationshipColumns = array_merge($relationshipColumns, array('id_classe', 'id_classe_matiere'));
        }

        foreach ($relationshipColumns as $column) {
            if (Schema::hasColumn($table, $column)) {
                $query->where(function ($query) use ($column) {
                    $query->whereNull($column)
                        ->orWhere($column, '=', 0);
                });
            }
        }

        $placeholders = implode(', ', array_fill(0, count($classeIds), '?'));
        $condition = "EXISTS (SELECT 1 FROM inscription AS target_inscription " .
            "WHERE target_inscription.id_eleve = {$table}.id_eleve " .
            "AND target_inscription.annee_scolaire = {$table}.annee_scolaire " .
            "AND target_inscription.id_classe IN ({$placeholders}))";
        $bindings = $classeIds;

        if ($ambiguous) {
            $condition .= " AND EXISTS (SELECT 1 FROM inscription AS other_inscription " .
                "WHERE other_inscription.id_eleve = {$table}.id_eleve " .
                "AND other_inscription.annee_scolaire = {$table}.annee_scolaire " .
                "AND (other_inscription.id_classe IS NULL OR other_inscription.id_classe NOT IN ({$placeholders})))";
        } else {
            $condition .= " AND NOT EXISTS (SELECT 1 FROM inscription AS other_inscription " .
                "WHERE other_inscription.id_eleve = {$table}.id_eleve " .
                "AND other_inscription.annee_scolaire = {$table}.annee_scolaire " .
                "AND (other_inscription.id_classe IS NULL OR other_inscription.id_classe NOT IN ({$placeholders})))";
        }
        $bindings = array_merge($bindings, $classeIds);

        $query->whereRaw($condition, $bindings);
        return $query;
    }
}
