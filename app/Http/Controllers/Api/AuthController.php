<?php

namespace App\Http\Controllers\Api;

use App\User;

/*use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Hash;*/

use Illuminate\Support\Facades\DB;
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

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->status = 1;
        $user->password = bcrypt($request->password);
        $user->hotel_id = $request->hotel_id;
        $user->save();

        $accessToken = $user->createToken('authToken')->accessToken;

        return response(['user' => $user, 'access_token' => $accessToken]);

    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        /*$user = User::where('email', $request->email)->first();

        if (!$user) {
            return response(['status' => 'error', 'message' => 'User not found']);
        }*/

        if (!auth()->attempt($loginData)) {
            return response(['message' => 'Invalid credentials']);
        }

        $hotelsBasic = DB::table('hotels_hrs')->select('name','city', 'country_code')->where('id', '=', auth()->user()->hotel_id)->get();

        $accessToken = auth()->user()->createToken('authToken')->accessToken;
        return response([
            'user' => auth()->user(),
            'hotel_name' => ((count($hotelsBasic) > 0) ? $hotelsBasic[0]->name : null),
            'city' => ((count($hotelsBasic) > 0) ? $hotelsBasic[0]->city : null),
            'country_code' => ((count($hotelsBasic) > 0) ? $hotelsBasic[0]->country_code : null),
            'access_token' => $accessToken
        ]);

        /*if (Hash::check($request->password, $user->password)) {
            $http = new GuzzleClient;
            $response = $http->post(url('oauth/token'), [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => env('OAUTH_GRANT_SECRET_ID'),
                    'client_secret' => env('OAUTH_GRANT_SECRET_KEY'),
                    'username' => $request->email,
                    'password' => $request->password,
                    'scope' => '',
                ],
            ]);
            return response(['auth' => json_decode((string)$response->getBody(), true)]);
        }*/
    }

    /*
    public function register(Request $request)
    {
                $validatedData['password'] = bcrypt($request->password);
                $validatedData['status'] = 0;
                $validatedData['hotel_id'] = $request->hotel_id;

                return $validatedData;

                $user = User::create($validatedData);



                $http = new GuzzleClient;
                $response = $http->post(url('oauth/token'), [
                    'form_params' => [
                        'grant_type' => 'password',
                        'client_id' => env('OAUTH_GRANT_SECRET_ID'),
                        'client_secret' => env('OAUTH_GRANT_SECRET_KEY'),
                        'username' => $request->email,
                        'password' => $request->password,
                        'scope' => '',
                    ],
                ]);
                return response(['auth' => json_decode((string)$response->getBody(), true)]);
    }
    */

    /*public function refreshToken()
    {
        $http = new GuzzleClient;
        $response = $http->post(url('oauth/token'), [
            'form_params' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => request('refresh_token'),
                'client_id' => env('OAUTH_GRANT_SECRET_ID'),
                'client_secret' => env('OAUTH_GRANT_SECRET_KEY'),
                'scope' => '',
            ],
        ]);
        return json_decode((string)$response->getBody(), true);
    }*/
}
