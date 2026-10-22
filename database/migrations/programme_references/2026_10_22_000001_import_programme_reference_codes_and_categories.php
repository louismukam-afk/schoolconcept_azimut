<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use gesecole\Services\ProgrammeReferenceImport;

// Run this directory explicitly to avoid executing unrelated legacy migrations.
class ImportProgrammeReferenceCodesAndCategories extends Migration
{
    private $backupTable = 'programme_reference_backup_20261022';

    public function up()
    {
        foreach (array('classe_matiere', 'matiere', 'classe', 'cycle', 'n_niveaus', 'codages') as $table) {
            if (!Schema::hasTable($table)) throw new RuntimeException('Table requise absente : '.$table);
        }
        if (!Schema::hasTable($this->backupTable)) {
            Schema::create($this->backupTable, function (Blueprint $table) {
                $table->integer('id_classe_matiere')->primary();
                $table->text('old_code')->nullable();
                $table->integer('old_type')->nullable();
                $table->text('new_code')->nullable();
                $table->integer('new_type')->nullable();
                $table->string('profile', 32);
            });
        }
        DB::transaction(function () {
            $plan = ProgrammeReferenceImport::databasePlan();
            $count = 0;
            foreach ($plan['rows'] as $entry) {
                if ($entry['status'] !== 'update') continue;
                $current = DB::table('classe_matiere')->where('id', $entry['id'])->lockForUpdate()->first();
                if (!$current || !$this->same($current->id_code, $entry['old_code']) || !$this->same($current->type, $entry['old_type'])) {
                    throw new RuntimeException('Programme modifié pendant la migration : '.$entry['id']);
                }
                DB::table($this->backupTable)->insert(array('id_classe_matiere'=>$entry['id'],
                    'old_code'=>$entry['old_code'], 'old_type'=>$entry['old_type'],
                    'new_code'=>$entry['new_code'], 'new_type'=>$entry['new_type'], 'profile'=>$entry['profile']));
                DB::table('classe_matiere')->where('id', $entry['id'])->update(array(
                    'id_code'=>$entry['new_code'], 'type'=>$entry['new_type']));
                $count++;
            }
            echo 'Programmes : '.$count.' codes/catégories mis à jour ; les autres cas sont conservés.'.PHP_EOL;
        });
    }

    public function down()
    {
        if (!Schema::hasTable($this->backupTable)) return;
        DB::transaction(function () {
            $backups = DB::table($this->backupTable)->orderBy('id_classe_matiere')->get();
            foreach ($backups as $backup) {
                $current = DB::table('classe_matiere')->where('id', $backup->id_classe_matiere)->lockForUpdate()->first();
                if (!$current || !$this->same($current->id_code, $backup->new_code) || !$this->same($current->type, $backup->new_type)) {
                    throw new RuntimeException('Annulation arrêtée : le programme '.$backup->id_classe_matiere.' a été modifié depuis cet import.');
                }
            }
            foreach ($backups as $backup) {
                DB::table('classe_matiere')->where('id', $backup->id_classe_matiere)->update(array(
                    'id_code'=>$backup->old_code, 'type'=>$backup->old_type));
            }
        });
        Schema::drop($this->backupTable);
    }

    private function same($first, $second)
    {
        if ($first === null || $second === null) return $first === $second;
        return (string) $first === (string) $second;
    }
}
