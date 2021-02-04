<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Models\UserAction;
use App\Models\UserCurrency;
use App\Models\UserCurrencyHistory;
use Illuminate\Support\Facades\Http;


//NOTE ALL MONEY STORED IN CENTS
class UserActionStoreService
{
    private $user;
    private $request;
    private $return;

    public function store($user, $request)
    {
        $this->user = $user;
        $this->request = $request;
        $this->return = [];

        if ($request->currency == "UAH") {
            $this->storeUah();
        } else {
            $this->storeCurrency();
        }

        return $this->return;
    }

    private function storeUah(){
        $info = "Income {$this->request->sum} {$this->request->currency}";
        $this->return[] = $this->writeToUserAction('Income', $this->request->sum,  $this->request->currency, $info);
    }

    private function storeCurrency(){

        $userCurrencies = UserCurrency::where("user_id", $this->user->id)
            ->where("currency", $this->request->currency)
            ->firstOrFail();

        $sum = $this->request->sum;

        if ($userCurrencies->force_exchange == 1) {

            $forceExchange = new ForceExchangeService;
            $forceExchangeData = $forceExchange->forceExchage($this->user, $this->request, $this->return);
            $this->return = $forceExchangeData["data"];
            $sum -= $forceExchangeData["ForceExchageAmount"];
        }

        $newUserCurrencyHistory = new UserCurrencyHistory();
        $newUserCurrencyHistory->user_id = $this->user->id;
        $newUserCurrencyHistory->currency = $this->request->currency;
        $newUserCurrencyHistory->date = $this->request->date;
        $newUserCurrencyHistory->spended = 0;
        $newUserCurrencyHistory->spended_amount = 0;
        $newUserCurrencyHistory->income = $sum * 100;
        $newUserCurrencyHistory->save();

        $info = "Income {$sum} {$this->request->currency}";
        $this->return[] = $this->writeToUserAction('Income', $sum,  $this->request->currency, $info);
    }

    private function writeToUserAction($actionType, $sum, $currency, $info){
        $action = new UserAction();
        $action->user_id = $this->user->id;
        $action->action = $actionType;
        $action->sum = $sum * 100;
        $action->currency = $currency;
        $action->date = $this->request->date;
        $action->info = $info;
        $action->save();

        return $action;
    }
}

