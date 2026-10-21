<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixReleveNoteGenerationsIdSequence extends Migration
{
    public function up()
    {
        if (DB::connection()->getDriverName() !== 'pgsql' || !Schema::hasTable('releve_note_generations')) {
            return;
        }

        DB::transaction(function () {
            // Bloquer les écritures pendant la remise en place de la numérotation.
            DB::statement('LOCK TABLE releve_note_generations IN ACCESS EXCLUSIVE MODE');
            $result = DB::selectOne("SELECT pg_get_serial_sequence('releve_note_generations', 'id') AS sequence_name");
            $sequence = $result->sequence_name;
            if (!$sequence) {
                DB::statement('CREATE SEQUENCE IF NOT EXISTS releve_note_generations_id_seq');
                DB::statement('ALTER SEQUENCE releve_note_generations_id_seq OWNED BY releve_note_generations.id');
                DB::statement("ALTER TABLE releve_note_generations ALTER COLUMN id SET DEFAULT nextval('releve_note_generations_id_seq'::regclass)");
                $sequence = 'releve_note_generations_id_seq';
            }

            // Le nom renvoyé par PostgreSQL est un identifiant SQL correctement cité.
            $state = DB::selectOne('SELECT last_value, is_called FROM '.$sequence);
            $max = DB::table('releve_note_generations')->max('id');
            $next = max((int) $max + 1, (int) $state->last_value + ($state->is_called ? 1 : 0));
            DB::selectOne('SELECT setval(CAST(? AS regclass), ?, false)', array($sequence, $next));
        });
    }

    public function down()
    {
        // Conserver la numérotation et les relevés déjà sauvegardés.
    }
}
