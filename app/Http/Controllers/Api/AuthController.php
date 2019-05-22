<?php

namespace App\Http\Controllers\Api;

use App\User;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Hash;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    //

    public function register(Request $request)
    {

        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'hotel_id' => 'required',
        ]);

        $user = User::firstOrNew(['email' => $request->email]);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->status = 0;
        $user->password = bcrypt($request->email);
        $user->hotel_id = $request->hotel_id;
        $user->save();

        $http = new GuzzleClient;

        $response = $http->post(url('oauth/token'), [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => '2',
                'client_secret' => '5HMCDi6OBKppKXWQqYwg5WHe8ahbqYYlKHtoP1y4',
                'username' => $request->email,
                'password' => $request->password,
                'scope' => '',
            ],
        ]);

        return response(['data' => json_decode((string)$response->getBody(), true)]);

    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response(['status' => 'error', 'message' => 'User not found']);
        }

        if (Hash::check($request->password, $user->password)) {

            $http = new GuzzleClient;

            $response = $http->post(url('oauth/token'), [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => '2',
                    'client_secret' => '5HMCDi6OBKppKXWQqYwg5WHe8ahbqYYlKHtoP1y4',
                    'username' => $request->email,
                    'password' => $request->password,
                    'scope' => '',
                ],
            ]);

            return response(['data' => json_decode((string)$response->getBody(), true)]);
        }

//        return 'check' . Hash::check($request->password, $user->password);

    }
}
