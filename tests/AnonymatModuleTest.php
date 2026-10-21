<?php
// Integration checks on the configured local database; all test writes roll back.
require __DIR__.'/../bootstrap/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$app['request']->setSession($app['session.store']);
\Auth::setUser(gesecole\User::orderBy('id')->first());
session(array('year'=>'2025-2026'));
$app['view']->share('errors', new Illuminate\Support\ViewErrorBag());
function checkAnon($condition, $message) { if (!$condition) throw new RuntimeException($message); }
function anonRequest($path, $values=array(), $method='GET') {
    global $app;
    $request=Illuminate\Http\Request::create($path, $method, $values);
    $request->setSession($app['session.store']);
    return $request;
}
function anonRender($view) { return $view->with('errors', new Illuminate\Support\ViewErrorBag())->render(); }
function anonPrivate($controller, $method, $args=array()) {
    $reflection=new ReflectionMethod($controller, $method); $reflection->setAccessible(true);
    return $reflection->invokeArgs($controller,$args);
}
checkAnon(Schema::hasTable('anonymats_examens') && Schema::hasColumn('note','id_anonymat'), 'Missing anonymat migration');
checkAnon(Schema::hasColumn('pv_generations','contenu_html'), 'Missing PV history migration');
$routes=$app['router']->getRoutes();
foreach(array('notes_anonymats','notes_anonymats_saisie','notes_anonymats_cc_tpe','notes_anonymats_generer','notes_anonymats_notes_store','notes_anonymats_pdf_fiches','notes_anonymats_pdf_listes','notes_anonymats_pdf_presences','notes_anonymats_modules','notes_anonymats_modules_pdf','notes_anonymats_modules_historique') as $name){
    $route=$routes->getByName($name); checkAnon($route!==null,'Missing route '.$name);
    $action=$route->getAction(); checkAnon(isset($action['action']), 'Missing action authorization');
}
$controller=new gesecole\Http\Controllers\eleves\anonymatController();
$home=anonRender($controller->index(anonRequest('/eleves/notes/anonymats')));
checkAnon(strpos($home,'Anonymats des examens')!==false && strpos($home,'id="page-wrapper"')!==false,'Application template');
echo "Anonymat routes, migrations and application layout: OK\n";
$context=DB::table('classe_matiere as cm')->join('session_exams as se','se.id_classe_matiere','=','cm.id')
    ->join('classe as c','c.id','=','cm.id_classe')
    ->where('cm.id_classe',366)->where('cm.id_cycle',2)->where('cm.id_nniveau',10)
    ->where('se.annee_academique',session('year'))->where('se.sn1','>',0)
    ->select('cm.*','se.id as session_id','se.id_tutelle','c.id_niveau')->first();
checkAnon($context!==null,'No configured test context');
$campus=DB::table('inscription')->where('id_classe',$context->id_classe)->where('id_cycle',$context->id_cycle)
    ->where('id_nniveau',$context->id_nniveau)->where('annee_scolaire',session('year'))->where('desinscrire',0)
    ->whereNotNull('id_campus')->pluck('id_campus');
if (!$campus) $campus=gesecole\Campus::orderBy('id')->first()->id;
session(array('campus_id'=>$campus));
$values=array('id_classe'=>$context->id_classe,'id_cycle'=>$context->id_cycle,'id_nniveau'=>$context->id_nniveau,
    'id_niveau'=>$context->id_niveau,'id_campus'=>$campus,'id_tutelle'=>$context->id_tutelle ?: 0,
    'semestre'=>'S1','type_examen'=>'fin_semestre_1','mode_generation'=>'matiere',
    'selection_matieres_active'=>1,'id_matieres'=>array($context->id_matiere),'id_matiere'=>$context->id_matiere);
$beforeAnon=gesecole\AnonymatExamen::count(); $beforeNotes=gesecole\note::count();
$pdfMode=getenv('ANONYMATS_PDF_MODE'); putenv('ANONYMATS_PDF_MODE=browser');
DB::beginTransaction();
try {
    // Ensure the imported modules/PV branch is tested even when this class has no groups yet.
    if (!DB::table('groupe_matieres')->where('id_classe_matiere',$context->id)->exists()) {
        $parent=DB::table('classe_matiere')->where('id_classe',$context->id_classe)
            ->where('id_cycle',$context->id_cycle)->where('id_nniveau',$context->id_nniveau)
            ->where('id_matiere','<>',$context->id_matiere)->pluck('id_matiere');
        DB::table('groupe_matieres')->insert(array('id'=>(int)DB::table('groupe_matieres')->max('id')+1,
            'id_classe_matiere'=>$context->id,'id_matiere_mere'=>$parent,'id_classe'=>$context->id_classe,
            'id_nniveau'=>$context->id_nniveau,'semestre'=>$context->semestre));
    }
    $response=$controller->generer(anonRequest('/eleves/notes/anonymats/generer',$values,'POST'));
    checkAnon(strpos($response->getTargetUrl(),'/anonymats/show?')!==false,'Generation failed');
    $count=gesecole\AnonymatExamen::count(); checkAnon($count>$beforeAnon,'No generated anonymats');
    $controller->generer(anonRequest('/eleves/notes/anonymats/generer',$values,'POST'));
    checkAnon(gesecole\AnonymatExamen::count()===$count,'Generation must be idempotent');
    $view=$controller->saisie(anonRequest('/eleves/notes/anonymats/saisie',$values));
    $data=$view->getData(); checkAnon(count($data['rows'])>0,'No anonymous entry rows');
    $anonymousHtml=anonRender($view);
    checkAnon(strpos($anonymousHtml,'Numero d')!==false,'Anonymous entry form');
    $anonymat=gesecole\AnonymatExamen::findOrFail($data['rows'][0]->id);
    $selection=anonPrivate($controller,'selection',array(anonRequest('/',$values)));
    $cm=gesecole\classeMatiere::findOrFail($context->id);
    $se=anonPrivate($controller,'sessionExamForSelection',array($selection,$cm,$context->id_matiere));
    $weight=anonPrivate($controller,'sessionPercentage',array($se,3));
    $store=$values; $store['notes']=array($anonymat->id=>'12,5');
    $controller->storeNotes(anonRequest('/eleves/notes/anonymats/notes/store',$store,'POST'));
    $mark=gesecole\note::where('id_anonymat',$anonymat->id)->where('sequence',3)->where('id_session',$se->id)->first();
    checkAnon($mark && abs($mark->note-12.5*$weight/20)<0.0001,'Wrong stored weighting/session');
    $again=$controller->saisie(anonRequest('/',$values))->getData();
    checkAnon(abs($again['rows'][0]->note_saisie-12.5)<0.0001,'Mark round trip /20');
    $store['notes']=array($anonymat->id=>'21'); $rejected=false;
    try {$controller->storeNotes(anonRequest('/',$store,'POST'));}catch(Symfony\Component\HttpKernel\Exception\HttpException $e){$rejected=$e->getStatusCode()===422;}
    checkAnon($rejected,'Out of range note accepted');
    $lockId=(int)DB::table('verrou_note')->max('id')+1;
    DB::table('verrou_note')->insert(array('id'=>$lockId,'sequence'=>3,'annee_scolaire'=>session('year'),
        'id_campus'=>$campus,'target'=>'note_sequence','is_active'=>true));
    $store['notes']=array($anonymat->id=>'13'); $locked=false;
    try {$controller->storeNotes(anonRequest('/',$store,'POST'));}catch(Symfony\Component\HttpKernel\Exception\HttpException $e){$locked=$e->getStatusCode()===403;}
    checkAnon($locked,'Active note lock was ignored');
    DB::table('verrou_note')->where('id',$lockId)->delete();
    $resit=$values; $resit['type_examen']='rattrapage_1';
    $controller->generer(anonRequest('/',$resit,'POST'));
    $resitData=$controller->saisie(anonRequest('/',$resit))->getData();
    $resit['notes']=array($resitData['rows'][0]->id=>'14');
    $controller->storeNotes(anonRequest('/',$resit,'POST'));
    $resitMark=gesecole\note::where('id_anonymat',$resitData['rows'][0]->id)->where('sequence',5)->first();
    $resitWeight=anonPrivate($controller,'sessionPercentage',array($se,5));
    checkAnon($resitMark && abs($resitMark->note-14*$resitWeight/20)<0.0001,'Resit weighting');
    $cc=$values; $cc['note_type']='cc';
    $ccData=$controller->ccTpe(anonRequest('/',$cc))->getData();
    if(count($ccData['rows'])) {
        $cc['notes']=array($ccData['rows'][0]->id=>'11');
        $controller->storeCcTpe(anonRequest('/',$cc,'POST'));
        checkAnon(gesecole\note::where('id_eleve',$ccData['rows'][0]->id)->where('id_session',$se->id)->where('sequence',1)->exists(),'CC entry');
    }
    $tpe=$values; $tpe['note_type']='tpe';
    $tpeData=$controller->ccTpe(anonRequest('/',$tpe))->getData();
    if(count($tpeData['rows'])) {
        $tpe['notes']=array($tpeData['rows'][0]->id=>'15');
        $controller->storeCcTpe(anonRequest('/',$tpe,'POST'));
        checkAnon(gesecole\note::where('id_eleve',$tpeData['rows'][0]->id)->where('id_session',$se->id)->where('sequence',7)->exists(),'TPE entry');
    }
    $otherSession=(object)array('id'=>9999999,'id_session'=>$se->id+100,'id_classe_matiere'=>$cm->id,'note'=>100);
    $selected=anonPrivate($controller,'moduleNote',array(array($mark,$otherSession),$se,$cm->id,false));
    checkAnon($selected->id===$mark->id,'Module mixed sessions');
    $otherCm=clone $mark; $otherCm->id_classe_matiere=$cm->id+100;
    checkAnon(anonPrivate($controller,'moduleNote',array(array($otherCm),$se,$cm->id,false))===null,'Module mixed programmes');
    foreach(array('show','printListe','printFicheMatiere','printFichePresence','modules','modulesHistorique') as $method) {
        checkAnon(strlen(anonRender($controller->$method(anonRequest('/',$values))))>100,'Failed view '.$method);
    }
    foreach(array('downloadFichesPdf','downloadListesPdf','downloadPresencesPdf') as $method) {
        $export=$controller->$method(anonRequest('/',$values));
        checkAnon($export->getStatusCode()===200 && strpos($export->getContent(),'window.print()')!==false,'Browser PDF export '.$method);
        checkAnon(strpos($export->getContent(),'INSTITUT SUPERIEUR AZIMUT')!==false,'Azimut export header');
        if(getenv('ANONYMATS_TEST_PREVIEW') && $method==='downloadFichesPdf') file_put_contents(__DIR__.'/../.codex_tmp/anonymats_report_preview.html',$export->getContent());
    }
    $moduleView=$controller->modules(anonRequest('/',$values)); $moduleData=$moduleView->getData();
    checkAnon(count($moduleData['modules'])>0,'Module calculation fixture missing');
    if (count($moduleData['modules'])) {
        $export=$controller->downloadModulesPdf(anonRequest('/',$values));
        checkAnon($export->getStatusCode()===200,'Modules PDF');
        $pv=gesecole\PvGeneration::where('type_pv','modules_anonymats')->orderBy('id','desc')->first();
        checkAnon($pv && strlen($pv->contenu_html)>100,'Saved module PV snapshot');
        checkAnon($controller->visualiserModulePv($pv->id)->getStatusCode()===200,'Saved module PV display');
        if(getenv('ANONYMATS_TEST_PREVIEW')) file_put_contents(__DIR__.'/../.codex_tmp/anonymats_module_preview.html',$export->getContent());
        echo "Modules calculation, PDF export and saved PV history: OK\n";
    }
    $exam=$values; $exam['mode_generation']='examen'; unset($exam['selection_matieres_active'],$exam['id_matieres']);
    $controller->generer(anonRequest('/',$exam,'POST'));
    $examData=$controller->saisie(anonRequest('/',$exam))->getData();
    checkAnon(count($examData['rows'])>0 && (int)$examData['rows'][0]->id_matiere===0,'Exam-wide anonymats');
    $allSheets=$controller->printFicheMatiere(anonRequest('/',$exam))->getData();
    checkAnon(count($allSheets['rows'])>0,'Exam-wide subject sheets');
    echo "Generation, idempotence, anonymous /20 entry, resits, CC, views and PDF fallback: OK\n";
} finally {
    DB::rollBack();
    putenv($pdfMode===false?'ANONYMATS_PDF_MODE':'ANONYMATS_PDF_MODE='.$pdfMode);
}
checkAnon(gesecole\AnonymatExamen::count()===$beforeAnon && gesecole\note::count()===$beforeNotes,'Test data not rolled back');
echo "All test writes rolled back; existing notes preserved.\n";
