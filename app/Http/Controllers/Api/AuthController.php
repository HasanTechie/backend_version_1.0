<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    //

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required',
            'name' => 'required',
            'password' => 'required',
            'selecthotel' => 'required',
        ]);

        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->status = 0;
        $user->password = bcrypt($request->email);
        $user->hotel_id = $request->selecthotel;
        $user->save();
    }


    public function login()
    {

    }
}
