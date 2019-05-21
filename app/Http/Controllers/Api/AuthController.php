<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    //

    public function register(Request $request){
        $validatedData = $request->validate([
            'email' => 'required',
            'name' => 'required',
            'password' => 'required',
            'selecthotel' => 'required',
        ]);
    }

    public function login(){

    }
}
