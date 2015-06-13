<?php

$domain = app()->environment('local') ? 'hazzardweb.app' : 'hazzardweb.com';

// Docs
Route::group(['domain' => "docs.{$domain}", 'as' => 'docs.'], function() {
	Route::get('/', [
		'as'   => 'index',
		'uses' => 'DocsController@index'
	]);

	Route::get('{manual}/{version?}/{page?}', [
		'as'   => 'show',
		'uses' => 'DocsController@show'
	]);
});

// Git
Route::group(['domain' => "git.{$domain}", 'as' => 'git.', 'namespace' => 'Git'], function() {
	Route::get('/', [
		'as' => 'index',
		'uses' => 'MainController@index',
		//'middleware' => 'auth'
	]);

	Route::get('/auth/login', [
		'as' => 'login',
		'uses' => 'AuthController@login',
		'middleware' => 'guest'
	]);
});

// Blog
Route::group(['domain' => "blog.{$domain}", 'as' => 'blog.'], function() {
	Route::get('/', [
		'as'   => 'index',
		'uses' => 'BlogController@index'
	]);
});


Route::get('/', function() {
	return view('welcome');
});
