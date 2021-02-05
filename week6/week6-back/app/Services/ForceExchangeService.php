<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Models\UserAction;
use App\Models\UserCurrency;
use App\Models\UserCurrencyHistory;
use Exception;

//NOTE ALL MONEY STORED IN CENTS
class ForceExchangeService
{
    private $user;
    private $request;
    private $return;

    public function forceExchage($user, $request, $standalone=false)
    {
        $this->user =  $user;
        $this->request = $request;

        $userCurrencies = UserCurrency::where("user_id", $this->user->id)
            ->where("currency", $this->request->currency)
            ->firstOrFail();

        $sum = $this->request->sum;
        $forceAmountPersent = $userCurrencies->force_exchange_amount;

        $forceAmount = $sum * ($forceAmountPersent / 100);
        $sum -= $forceAmount;

        // If force exchange perfome on past transaction
        // we need to correnct income sum of the past transaction
        if ($standalone) {
            // Corecting the sum of the past transaction
            $action = UserAction::where("user_id", $this->user->id)
                ->where("date", $this->request->date)
                ->where("sum", $this->request->sum * 100)
                ->first();

            if ($action == null) {
                return  ["error" => "Error transaction is not found"];
            }
            $income = $sum * 100;
            $action->sum = $income;
            $action->info = "Income {$income} {$this->request->currency}";
            $action->save();


            //Corecting the sum in the history of income in that currency
            $currencyHistory = UserCurrencyHistory::where("user_id", $this->user->id)
                ->where("date", $this->request->date)
                ->where("income", $this->request->sum * 100)
                ->where("spended_amount", 0)
                ->where("currency", $this->request->currency)
                ->first();
            $currencyHistory->income = $income;
            $currencyHistory->save();
        }

        $info = "Force exchange $forceAmount {$this->request->currency} it's $forceAmountPersent % from {$this->request->sum} {$this->request->currency}";
        $this->return[] = $this->writeToUserAction('Force exchange', $forceAmount, $this->request->currency, $info);

        $rate = Helper::getRate($this->request->exchangeDate, $this->request->currency);

        $incomeFromFroceExchange = $forceAmount * $rate;
        $incomeFromFroceExchangeFormated = number_format ($incomeFromFroceExchange, 2);
        $info = "Income  $incomeFromFroceExchangeFormated {$user->default_currency} from force exchange $forceAmount  {$this->request->currency} ($forceAmountPersent % from {$this->request->sum} {$this->request->currency}) with rate $rate";
        $this->return[] = $this->writeToUserAction('Force exchange UAH income', $incomeFromFroceExchange, $this->user->default_currency, $info);
        return ['data' => $this->return, 'ForceExchageAmount' => $forceAmount];
    }


    private function writeToUserAction($actionType, $sum, $currency, $info)
    {
        $action = new UserAction();
        $action->user_id = $this->user->id;
        $action->action = $actionType;
        $action->sum = $sum * 100;
        $action->currency = $currency;
        $action->date = $this->request->exchangeDate;
        $action->info = $info;
        $action->save();

        return $action;
    }
}
