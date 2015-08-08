<?php

namespace Hazzard\Web\Http\Controllers\Git;

use Illuminate\Http\Request;

use Hazzard\Web\Http\Requests;
use Hazzard\Web\Http\Controllers\Controller;

class AuthController extends Controller
{
	public function login()
	{
		return view('git.login');
	}
}
