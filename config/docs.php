<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Storage Driver
	|--------------------------------------------------------------------------
	|
	| Supported: "flat", "git"
	|
	*/

	'driver' => env('DOCS_DRIVER', 'git'),

	/*
	|--------------------------------------------------------------------------
	| Storage Path
	|--------------------------------------------------------------------------
	*/

	'storage_path' => base_path(env('DOCS_PATH', 'resources/docs')),

	/*
	|--------------------------------------------------------------------------
	| Manual Names
	|--------------------------------------------------------------------------
	*/

	'manual_names' => [
		'filepicker' 		  => 'Filepicker',
        'imagepicker'         => 'Image Picker',
        'imageselect'         => 'Image Select',
		'easylogin-pro' 	  => 'EasyLogin Pro',
		'ajax-comment-system' => 'Ajax Comment System',
	],

];
