<?php


// Default Route
Route::set('default', '(<controller>(/<action>(/<id>)))')
	->defaults(array(
		'directory'		=> 'default',
		'controller'	=> 'home',
		'action'		=> 'index',
	));

	
// Admin Route
Route::set('admin', 'admin(/<controller>(/<action>(/<id>)))')
	->defaults(array(
		'directory'		=> 'backend',
		'controller'	=> 'dashboard',
		'action'		=> 'index',
	));

// Ajax Route
Route::set('ajax', 'ajax(/<controller>(/<action>(/<id>)))')
	->defaults(array(
		'directory'		=> 'ajax',
		'controller'	=> 'default',
		'action'		=> 'index',
	));

