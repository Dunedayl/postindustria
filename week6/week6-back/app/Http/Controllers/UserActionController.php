<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\ExchangeUserActionRequest;
use App\Http\Requests\StoreUserActionRequest;
use App\Http\Resources\UserActionResource;
use App\Models\UserAction;
use App\Services\ForceExchangeService;
use App\Services\ReportGenerationService;
use App\Services\UserActionExchangeService;
use App\Services\UserActionStoreService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;


class UserActionController extends Controller
{
    public function apiRate($dbDate, $currency)
    {
        return Helper::getRate($dbDate, $currency);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //Generete report of user income from/to
    public function makeReport($from, $to, ReportGenerationService $reportGenerationService)
    {
        $report = $reportGenerationService->makeReport(Auth::user(), $from, $to);

        return $report;
    }

    public function getAllActions()
    {
        $userId = Auth::user()->id;

        $database = UserAction::where("user_id", $userId)
            ->orderBy('date', 'asc')
            ->get();

        return UserActionResource::collection($database);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(StoreUserActionRequest $request, UserActionStoreService $userActionStoreService)
    {
        $userAction = $userActionStoreService->store(Auth::user(), $request);
        return UserActionResource::collection($userAction);
    }

    // Perfom force exchange for past incomes
    public function forceExchage(StoreUserActionRequest $request, ForceExchangeService $forceExchangeService)
    {
        $userAction = $forceExchangeService->forceExchage(Auth::user(), $request, true);

        if (array_key_exists('error', $userAction)) {
            return response()->json(["error" => $userAction["error"]], 409);
        } else {
            return UserActionResource::collection($userAction["data"]);
        }

    }

    //Exchange USD in UAH
    public function exchange(ExchangeUserActionRequest $request, UserActionExchangeService $userActionExchangeService)
    {
        $userAction = $userActionExchangeService->exchange(Auth::user(), $request);

        if (array_key_exists('error', $userAction)) {
            return response()->json($userAction, 409);
        } {
            return  UserActionResource::collection($userAction);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
