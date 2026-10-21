<?php
// Loaded inside the authenticated application routes (auth + dc).
Route::group(array('middleware'=>'action', 'action'=>\gesecole\Action::ACTION_STUDENT_NOTE), function () {
    Route::get('/eleves/notes/anonymats', array('as'=>'notes_anonymats', 'uses'=>'eleves\anonymatController@index'));
    Route::get('/eleves/notes/anonymats/show', array('as'=>'notes_anonymats_show', 'uses'=>'eleves\anonymatController@show'));
    Route::get('/eleves/notes/anonymats/matieres', array('as'=>'notes_anonymats_matieres', 'uses'=>'eleves\anonymatController@matieresOptions'));
    Route::get('/eleves/notes/anonymats/print', array('as'=>'notes_anonymats_print', 'uses'=>'eleves\anonymatController@printListe'));
    Route::get('/eleves/notes/anonymats/fiche-matiere', array('as'=>'notes_anonymats_fiche_matiere', 'uses'=>'eleves\anonymatController@printFicheMatiere'));
    Route::get('/eleves/notes/anonymats/fiche-presence', array('as'=>'notes_anonymats_fiche_presence', 'uses'=>'eleves\anonymatController@printFichePresence'));
    Route::get('/eleves/notes/anonymats/fiche-etudiant', array('as'=>'notes_anonymats_fiche_etudiant', 'uses'=>'eleves\anonymatController@printFicheEtudiant'));
    Route::get('/eleves/notes/anonymats/pdf-fiches', array('as'=>'notes_anonymats_pdf_fiches', 'uses'=>'eleves\anonymatController@downloadFichesPdf'));
    Route::get('/eleves/notes/anonymats/pdf-listes', array('as'=>'notes_anonymats_pdf_listes', 'uses'=>'eleves\anonymatController@downloadListesPdf'));
    Route::get('/eleves/notes/anonymats/pdf-presences', array('as'=>'notes_anonymats_pdf_presences', 'uses'=>'eleves\anonymatController@downloadPresencesPdf'));
    Route::get('/eleves/notes/anonymats/modules', array('as'=>'notes_anonymats_modules', 'uses'=>'eleves\anonymatController@modules'));
    Route::get('/eleves/notes/anonymats/modules/pdf', array('as'=>'notes_anonymats_modules_pdf', 'uses'=>'eleves\anonymatController@downloadModulesPdf'));
    Route::get('/eleves/notes/anonymats/modules/historique', array('as'=>'notes_anonymats_modules_historique', 'uses'=>'eleves\anonymatController@modulesHistorique'));
    Route::get('/eleves/notes/anonymats/modules/historique/{id}', array('as'=>'notes_anonymats_modules_historique_show', 'uses'=>'eleves\anonymatController@visualiserModulePv'));
});
Route::group(array('middleware'=>'action', 'action'=>\gesecole\Action::ACTION_STUDENT_NOTE_CU), function () {
    Route::get('/eleves/notes/anonymats/saisie', array('as'=>'notes_anonymats_saisie', 'uses'=>'eleves\anonymatController@saisie'));
    Route::get('/eleves/notes/anonymats/cc-tpe', array('as'=>'notes_anonymats_cc_tpe', 'uses'=>'eleves\anonymatController@ccTpe'));
    Route::post('/eleves/notes/anonymats/generer', array('as'=>'notes_anonymats_generer', 'uses'=>'eleves\anonymatController@generer'));
    Route::post('/eleves/notes/anonymats/notes/store', array('as'=>'notes_anonymats_notes_store', 'uses'=>'eleves\anonymatController@storeNotes'));
    Route::post('/eleves/notes/anonymats/cc-tpe/store', array('as'=>'notes_anonymats_cc_tpe_store', 'uses'=>'eleves\anonymatController@storeCcTpe'));
});
