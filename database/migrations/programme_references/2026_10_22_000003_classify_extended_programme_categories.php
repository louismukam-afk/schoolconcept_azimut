<?php
use Illuminate\Database\Migrations\Migration;
use gesecole\Services\ProgrammeReferenceBatch;

class ClassifyExtendedProgrammeCategories extends Migration
{
    public function up() { ProgrammeReferenceBatch::apply('programme_reference_types_v2', 'categories'); }
    public function down() { ProgrammeReferenceBatch::revert('programme_reference_types_v2', 'categories'); }
}
