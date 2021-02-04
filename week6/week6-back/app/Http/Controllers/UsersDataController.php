<?php

namespace App\Http\Controllers;

use App\Models\ActorData;
use App\Models\User;
use App\Models\UserCurrency;
use App\Models\UsersData;
use Exception;
use finfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $user = Auth::user();


        $userId = Auth::user()->id;

        $userData = UsersData::where("userId", $userId)->firstOrFail();
        $userCurrencies = UserCurrency::where("userId", $userId)->get();

        $currecyData = [];

        foreach ($userCurrencies as $value) {
            array_push($currecyData, [
                'currency' => $value->currency,
                'forceExchange' => $value->forceExchange,
                'forceExchangeAmount' => $value->forceExchangeAmount
            ]);
        }


        return response([
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'taxRate' => $userData->taxRate,
            'notificationPeriod' => $userData->notificationPeriod,
            'currecies' => $currecyData,
            'image' => $userData->image
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {


        $userId = Auth::user()->id;
        $user = User::find($userId);


        $userData = UsersData::where("userId", $userId)->firstOrFail();
        $userCurrencies = UserCurrency::where("userId", $userId)->get();

        if ($user) {
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            $user->save();
        }

        if ($userData) {
            try {
                $userData->taxRate = $request->taxRate;
                $userData->notificationPeriod = $request->notificationPeriod;
                $userData->image = $request->image;
                $userData->save();
            } catch (Exception $e) {
                return $e;
            }
        }

        if ($userCurrencies) {

            foreach ($userCurrencies as $key => $dbCurrency) {

                foreach ($request->currencies as $key => $requestCurrency) {

                    if ($dbCurrency->currency == $requestCurrency["currency"]) {
                        $dbCurrency->forceExchange = $requestCurrency["forceExchange"];
                        $dbCurrency->forceExchangeAmount = $requestCurrency["forceExchangeAmount"];
                        $dbCurrency->save();
                    }
                }
            }
        }

        return response([
            'message' => "OK",
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
