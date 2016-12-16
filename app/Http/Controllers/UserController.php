<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use Validator;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getUser()
    {
        $id = Auth::user()->id;
        
        return User::findOrFail($id);
    }

    public function postUser(Request $request, $id)
    {
    	$validator = Validator::make($request->all(), [
    		'password' => 'required|confirmed'
    	])->validate();

    	$user = User::FindOrFail($id);
        $user->password = bcrypt($request->password);
        $user->update();
    }
}
