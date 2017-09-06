<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');
//Tableau de bord d'admin
Route::group(['middleware'=>'auth'],function (){
    Route::post('/update-school-year/', 'WelcomeController@editYear');
    Route::get('/admin/', 'admin\adminController@index');
//routes pour les cycles
    Route::get('/admin/cycles/', 'admin\cycleController@index');
    Route::get('/admin/cycles/destroy/{id}', 'admin\cycleController@destroy');
    Route::post('/admin/cycles/store', 'admin\cycleController@store');
// Les niveaux
    Route::get('/admin/niveaux/', 'admin\niveauController@index');
    Route::get('/admin/niveaux/destroy/{id}', 'admin\niveauController@destroy');
    Route::post('/admin/niveaux/store', 'admin\niveauController@store');
//Les classes
    Route::get('/admin/classes/', 'admin\classeController@index');
    Route::get('/admin/classes/destroy/{id}', 'admin\classeController@destroy');
    Route::post('/admin/classes/update', 'admin\classeController@update');
    Route::post('/admin/classes/store', 'admin\classeController@store');
//Les scolarités
    Route::get('/admin/scolarites/', 'admin\scolariteController@index');
    Route::get('/admin/scolarite/destroy/{id}', 'admin\scolariteController@destroy');
    Route::post('/admin/scolarite/update', 'admin\scolariteController@update');
    Route::post('/admin/scolarite/store', 'admin\scolariteController@store');
//Matières
    Route::get('/admin/matieres/', 'admin\matiereController@index');
    Route::post('/admin/matieres/update/', 'admin\matiereController@update');
    Route::post('/admin/matieres/store', 'admin\matiereController@store');
//Programmes
    Route::get('/admin/programmes/', 'admin\programmeController@index');
    Route::get('/admin/programmes/selection-matieres/{id}', 'admin\programmeController@create');
    Route::post('/admin/programmes/store/', 'admin\programmeController@store');
    Route::get('/admin/programmes/destroy/{id}', 'admin\programmeController@destroy');

});


//Inscriptions
Route::get('/inscriptions/enregistrement/create/', 'inscriptions\enregistrementController@create');
Route::get('/inscriptions/enregistrement/edit/{id}', 'inscriptions\enregistrementController@edit');
Route::post('/inscriptions/enregistrement/store/', 'inscriptions\enregistrementController@store');
Route::post('/inscriptions/enregistrement/update/{id}', 'inscriptions\enregistrementController@update');
Route::get('/inscriptions/', 'inscriptions\inscriptionController@index');
Route::get('/inscriptions/new', 'inscriptions\inscriptionController@search');
Route::get('/inscriptions/find', 'inscriptions\inscriptionController@find');
Route::get('/inscriptions/create/{id}', 'inscriptions\inscriptionController@create');
Route::post('/inscriptions/store', 'inscriptions\inscriptionController@store');
Route::get('/inscriptions/payement/', 'inscriptions\payementController@index');
Route::get('/inscriptions/payement/search', 'inscriptions\payementController@search');
Route::get('/inscriptions/payement/create/{id}', 'inscriptions\payementController@create');
Route::post('/inscriptions/payement/store/{id}', 'inscriptions\payementController@store');
Route::get('/inscriptions/recu','inscriptions\inscriptionController@show');
Route::get('/inscriptions/print/{id}','inscriptions\inscriptionController@print_receipe');
Route::get('/inscriptions/payement/print/{id}','inscriptions\payementController@print_receipe');
Route::get('/inscriptions/payement/list/{id}','inscriptions\payementController@index_liste');
Route::get('/inscriptions/selection-listes','inscriptions\listesController@index');
Route::get('/inscriptions/listes/show/{id}','inscriptions\listesController@show');

//Les élèves
Route::get('/eleves/', 'eleves\eleveController@index');
Route::get('/eleves/discipline/', 'eleves\disciplineController@index');
Route::get('/eleves/discipline/search', 'eleves\disciplineController@search');
Route::get('/eleves/discipline/show/{id}', 'eleves\disciplineController@create');
Route::post('/eleves/discipline/update/', 'eleves\disciplineController@update');

Route::get('/eleves/notes/','eleves\noteController@index');
Route::get('/eleves/notes/matieres/{id}','eleves\noteController@matiere');
Route::post('/eleves/notes/create/{id}','eleves\noteController@create');
Route::post('/eleves/notes/store/','eleves\noteController@store');

Route::get('/eleves/bulletins/','eleves\bulletinController@index');
Route::get('/eleves/bulletins/search/','eleves\bulletinController@index2');
Route::get('/eleves/bulletins/find/','eleves\bulletinController@search');
Route::post('/eleves/bulletins/print-single-seq','eleves\bulletinController@print_single_seq');




Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
