<?php namespace gesecole\Services;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProgrammeReferenceBatch
{
    public static function plan($mode)
    {
        self::field($mode);
        $catalogue = ProgrammeReferenceImport::catalogue('programme_references_extended_20261022.json');
        $catalogue['mode'] = $mode;
        return ProgrammeReferenceImport::databasePlan($catalogue);
    }

    public static function apply($tableName, $mode)
    {
        $field = self::field($mode);
        if (!Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->integer('id_classe_matiere')->primary();
                $table->text('old_code')->nullable(); $table->text('new_code')->nullable();
                $table->integer('old_type')->nullable(); $table->integer('new_type')->nullable();
                $table->string('profile', 32);
            });
        }
        return DB::transaction(function () use ($tableName, $mode, $field) {
            $plan = self::plan($mode); $count = 0;
            foreach ($plan['rows'] as $entry) {
                if ($entry['status'] !== 'update') continue;
                $current = DB::table('classe_matiere')->where('id', $entry['id'])->lockForUpdate()->first();
                if (!$current || !self::same($current->id_code, $entry['old_code']) || !self::same($current->type, $entry['old_type'])) {
                    throw new \RuntimeException('Programme modifié pendant cet import : '.$entry['id']);
                }
                DB::table($tableName)->insert(array('id_classe_matiere'=>$entry['id'], 'old_code'=>$entry['old_code'],
                    'new_code'=>$entry['new_code'], 'old_type'=>$entry['old_type'], 'new_type'=>$entry['new_type'], 'profile'=>$entry['profile']));
                $value = $mode === 'codes' ? $entry['new_code'] : $entry['new_type'];
                DB::table('classe_matiere')->where('id', $entry['id'])->update(array($field=>$value));
                $count++;
            }
            echo 'Import étendu '.$mode.' : '.$count.' lignes mises à jour.'.PHP_EOL;
            return $count;
        });
    }

    public static function revert($tableName, $mode)
    {
        $field = self::field($mode);
        if (!Schema::hasTable($tableName)) return;
        DB::transaction(function () use ($tableName, $mode, $field) {
            $backups = DB::table($tableName)->orderBy('id_classe_matiere')->get();
            foreach ($backups as $backup) {
                $current = DB::table('classe_matiere')->where('id', $backup->id_classe_matiere)->lockForUpdate()->first();
                $applied = $mode === 'codes' ? $backup->new_code : $backup->new_type;
                if (!$current || !self::same($current->$field, $applied)) {
                    throw new \RuntimeException('Annulation arrêtée : modification ultérieure du programme '.$backup->id_classe_matiere);
                }
            }
            foreach ($backups as $backup) {
                $old = $mode === 'codes' ? $backup->old_code : $backup->old_type;
                DB::table('classe_matiere')->where('id', $backup->id_classe_matiere)->update(array($field=>$old));
            }
        });
        Schema::drop($tableName);
    }

    private static function field($mode)
    {
        if (!in_array($mode, array('codes','categories'), true)) throw new \InvalidArgumentException('Mode de migration invalide.');
        return $mode === 'codes' ? 'id_code' : 'type';
    }

    private static function same($first, $second)
    {
        return $first === null || $second === null ? $first === $second : (string) $first === (string) $second;
    }
}
