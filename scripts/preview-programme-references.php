<?php
// Read-only preview. It contains programme metadata, never student marks or names.
require __DIR__.'/../bootstrap/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$extended = in_array('--extended', $argv, true);
$plan = $extended ? array('codes'=>gesecole\Services\ProgrammeReferenceBatch::plan('codes'),
    'categories'=>gesecole\Services\ProgrammeReferenceBatch::plan('categories')) : gesecole\Services\ProgrammeReferenceImport::databasePlan();
$path = storage_path('logs/programme_references_'.($extended ? 'extended_' : '').'20261022.json');
if (file_put_contents($path, json_encode($plan, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)) === false) {
    throw new RuntimeException('Impossible de sauvegarder le rapport de simulation.');
}
if ($extended) {
    foreach (array('codes','categories') as $mode) {
        echo strtoupper($mode).PHP_EOL;
        foreach ($plan[$mode]['summary'] as $status=>$count) echo '  '.$status.': '.$count.PHP_EOL;
        echo '  Profils sans programme : '.implode(', ', $plan[$mode]['profiles_without_programme']).PHP_EOL;
    }
} else {
    foreach ($plan['summary'] as $status=>$count) echo $status.': '.$count.PHP_EOL;
}
echo 'Rapport : '.$path.PHP_EOL;
