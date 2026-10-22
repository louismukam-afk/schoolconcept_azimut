<?php
use Illuminate\Database\Migrations\Migration;
use gesecole\Services\ProgrammeReferenceBatch;

class ImportExtendedProgrammeCodes extends Migration
{
    public function up() { ProgrammeReferenceBatch::apply('programme_reference_codes_v2', 'codes'); }
    public function down() { ProgrammeReferenceBatch::revert('programme_reference_codes_v2', 'codes'); }
}
