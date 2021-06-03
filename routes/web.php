<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Admin Capabilities...
Route::group(['middleware' => ['admin']], function(){
	
	// Logout
	Route::get('/admin/logout', function(){
	    return redirect('/');
	});
	Route::post('/admin/logout', 'Auth\LoginController@logout');

	// Dashboard
	Route::get('/admin', 'DashboardController@index');
	Route::get('/admin/ajax/graph-transaksi', 'DashboardController@graph_transaksi');
	Route::get('/admin/ajax/graph-kunjungan', 'DashboardController@graph_kunjungan');

	// Profil
	Route::get('/admin/profil', 'UserController@profile');
	Route::post('/admin/profil/update', 'UserController@update_profile');

	// User
	Route::get('/admin/user', 'UserController@index');
	Route::get('/admin/user/create', 'UserController@create');
	Route::post('/admin/user/store', 'UserController@store');
	Route::get('/admin/user/detail/{id}', 'UserController@detail');
	Route::get('/admin/user/export', 'UserController@export');
	Route::post('/admin/user/import', 'UserController@import');
	Route::get('/admin/user/edit/{id}', 'UserController@edit');
	Route::post('/admin/user/update', 'UserController@update');
	Route::post('/admin/user/delete', 'UserController@delete');

	// Email
	Route::get('/admin/email', 'EmailController@index');
	Route::get('/admin/email/create', 'EmailController@create');
	Route::post('/admin/email/store', 'EmailController@store');
	Route::post('/admin/email/import', 'EmailController@import');
	Route::get('/admin/email/detail/{id}', 'EmailController@detail');
	Route::post('/admin/email/delete', 'EmailController@delete');

	// Transaksi
	Route::get('/admin/transaksi', 'TransaksiController@index');
	Route::get('/admin/transaksi/create', 'TransaksiController@create');
	Route::post('/admin/transaksi/store', 'TransaksiController@store');
	Route::get('/admin/transaksi/export', 'TransaksiController@export');
	Route::post('/admin/transaksi/import', 'TransaksiController@import');
	Route::get('/admin/transaksi/edit/{id}', 'TransaksiController@edit');
	Route::post('/admin/transaksi/update', 'TransaksiController@update');
	Route::post('/admin/transaksi/delete', 'TransaksiController@delete');

	// Pengaturan
	Route::get('/admin/pengaturan', 'SettingController@index');
	Route::post('/admin/pengaturan/update', 'SettingController@update');

	// Report
	Route::get('/admin/report/analisa', 'ReportController@analisa');
	Route::get('/admin/ajax/graph-usia', 'ReportController@graph_usia');
	Route::get('/admin/ajax/graph-gender', 'ReportController@graph_gender');
	Route::get('/admin/ajax/graph-kedatangan', 'ReportController@graph_kedatangan');
	Route::get('/admin/report/best-customer', 'ReportController@best_customer');
	Route::get('/admin/report/middle-customer', 'ReportController@middle_customer');
	Route::get('/admin/report/low-customer', 'ReportController@low_customer');
	
	// Statistik
	Route::get('/admin/report/statistik/top-up', 'ReportController@statistik_top_up');
	Route::get('/admin/ajax/graph-top-up', 'ReportController@graph_top_up');
	Route::get('/admin/report/statistik/trx', 'ReportController@statistik_trx');
	Route::get('/admin/ajax/graph-trx', 'ReportController@graph_trx');
	Route::get('/admin/report/statistik/arpu', 'ReportController@statistik_arpu');
	Route::get('/admin/ajax/graph-arpu', 'ReportController@graph_arpu');
});

// Guest Capabilities...
Route::group(['middleware' => ['guest']], function(){

	// Home
	Route::get('/', function(){
		return redirect('/login');
	});

	// Login dan Recovery Password
	Route::get('/login', 'Auth\LoginController@showLoginForm');
	Route::post('/login', 'Auth\LoginController@login');
	Route::get('/recovery-password', 'Auth\LoginController@showRecoveryPasswordForm');
	Route::post('/recovery-password', 'Auth\LoginController@recoveryPassword');
});