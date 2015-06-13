<?php

namespace HazzardWeb\Http\Controllers\Git;

use Illuminate\Http\Request;

use HazzardWeb\Http\Requests;
use HazzardWeb\Http\Controllers\Controller;

class AuthController extends Controller
{
	public function login()
	{
		return view('git.login');
	}
}
