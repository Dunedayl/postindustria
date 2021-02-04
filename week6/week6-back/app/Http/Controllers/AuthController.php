<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use App\Models\UserCurrency;
use App\Models\UsersCurrency;
use App\Models\UsersData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            if (Auth::attempt($request->only('email', 'password'))) {
                $user = Auth::user();

                /** @var User $user */
                $token = $user->createToken('app')->accessToken;
                return response([
                    'message' => 'success',
                    'token' => $token,
                    'user' => $user
                ]);
            }
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage()
            ], 400);
        }


        return response([
            'message' => 'Invalid username/password'
        ], 401);
    }

    public function register(StoreUserRequest $request)
    {

        try {

            $user = User::create([
                'firstname' => $request->input('firstname'),
                'lastname' => $request->input('lastname'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);

            $userCurrencies = UserCurrency::create([
                "user_id" => $user->id,
                "force_exchange" => 1,
                "force_exchange_amount" => 30,
                "currency" => "USD"
            ]);


            return $user;
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function index()
    {
        if (Auth::user()) {
            return 1;
        } else {
            return 0;
        }
    }
}
