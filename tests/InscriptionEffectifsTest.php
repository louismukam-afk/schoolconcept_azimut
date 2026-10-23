<?php
require __DIR__.'/../bootstrap/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$app['request']->setSession($app['session.store']);
Auth::setUser(gesecole\User::orderBy('id')->firstOrFail());
view()->share('errors', new Illuminate\Support\ViewErrorBag());
function effectifsCheck($ok, $message) { if (!$ok) throw new RuntimeException($message); }
$campus = gesecole\Campus::orderBy('id')->firstOrFail();
session(array('campus_id'=>$campus->id, 'year'=>'2025-2026'));
$controller = new gesecole\Http\Controllers\inscriptions\inscriptionController();
$request = Illuminate\Http\Request::create('/inscriptions/effectifs', 'GET');
$view = $controller->effectifs($request);
$data = $view->getData();
$expected = DB::table('inscription')->where('id_campus', $campus->id)
    ->whereIn('annee_scolaire', array('2025-2026','2025 - 2026'))
    ->where(function($q){$q->whereNull('desinscrire')->orWhere('desinscrire',0);})->count();
effectifsCheck($data['total']===$expected, 'Active campus/year total matches registration count');
$sum = 0;
foreach ($data['effectifsParCycle'] as $group) {
    $subtotal = 0; foreach ($group['rows'] as $row) $subtotal += $row->effectif;
    effectifsCheck($subtotal===$group['total'], 'Cycle subtotal matches its rows'); $sum += $subtotal;
}
effectifsCheck($sum===$data['total'], 'Cycle subtotals match grand total');
$html = html_entity_decode($view->render(), ENT_QUOTES, 'UTF-8');
effectifsCheck(strpos($html,'Effectifs des inscriptions')!==false && strpos($html,'window.print()')!==false,'View and print action render');
effectifsCheck(strpos($controller->index()->render(),'/inscriptions/effectifs')!==false,'Home button renders');
DB::beginTransaction();
try {
    $sample = (array)DB::table('inscription')->where('id_campus',$campus->id)->first();
    effectifsCheck(count($sample)>0,'Registration fixture available');
    unset($sample['id']); $sample['annee_scolaire']='9898-9899'; $sample['desinscrire']=0;
    DB::table('inscription')->insert($sample);
    $sample['desinscrire']=0;
    DB::table('inscription')->insert($sample);
    $sample['desinscrire']=1; DB::table('inscription')->insert($sample);
    $other = gesecole\Campus::where('id','<>',$campus->id)->first();
    if ($other) { $sample['desinscrire']=0; $sample['id_campus']=$other->id; DB::table('inscription')->insert($sample); }
    $sample['id_campus']=null; DB::table('inscription')->insert($sample);
    $result = $controller->effectifs(Illuminate\Http\Request::create('/inscriptions/effectifs','GET',array('annee_scolaire'=>'9898 - 9899')))->getData();
    effectifsCheck($result['total']===2,'Year variants included; withdrawn students and other/unassigned campuses excluded');
    $empty = $controller->effectifs(Illuminate\Http\Request::create('/inscriptions/effectifs','GET',array('annee_scolaire'=>'9897-9898')));
    effectifsCheck($empty->getData()['total']===0 && strpos($empty->render(),'Aucun effectif')!==false,'Empty year renders clearly');
} finally { DB::rollBack(); }
echo 'Registration headcounts: campus isolation, year variants, withdrawals, grouping, totals, rendering and empty state: OK ('.$expected.' registrations).' . PHP_EOL;
