<?php
// Read-only preview. It contains programme metadata, never student marks or names.
require __DIR__.'/../bootstrap/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$plan = gesecole\Services\ProgrammeReferenceImport::databasePlan();
$path = storage_path('logs/programme_references_20261022.json');
if (file_put_contents($path, json_encode($plan, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)) === false) {
    throw new RuntimeException('Impossible de sauvegarder le rapport de simulation.');
}
foreach ($plan['summary'] as $status=>$count) echo $status.': '.$count.PHP_EOL;
echo 'Rapport : '.$path.PHP_EOL;
