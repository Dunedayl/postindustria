<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Http\Resources\UserActionResource;
use App\Models\UserAction;
use App\Models\UserCurrencyHistory;
use Illuminate\Support\Facades\Http;


//NOTE ALL MONEY STORED IN CENTS
class UserActionExchangeService
{
    private $user;
    private $request;
    private $return;

    public function exchange($user, $request)
    {
        $this->user = $user;
        $this->request = $request;
        $this->return = [];


        $database = UserCurrencyHistory::where('spended', 0)
        ->where('user_id', $this->user->id)
            ->where('currency', $this->request->currency)
            ->where('date', '<=', $this->request->date)
            ->orderBy('date', 'asc')
            ->get();

        $rateDifference = 0;
        $rateAtTheDayOfExchange = Helper::getRate($request->date, $request->currency);
        $sumUahNow = $request->sum * $rateAtTheDayOfExchange * 100;

        $sumUsd = $request->sum * 100;
        $sumUahBefore = 0;
        $money = 0;

        foreach ($database as $value) {
            $money += $value->income - $value->spended_amount;
        }

        if ($money < $sumUsd) {
            $money = number_format ($money / 100, 2);
            header('Content-Type: application/json');
            return ["error" => "Not enough money your balance is $money $request->currency"];
        }

        $days = [];
        $rayts = [];

        foreach ($database as $value) {

            $avalible = $value->income - $value->spended_amount;
            $rate = Helper::getRate($value->date, $value->currency);

            $days[] = $value->date;
            $rayts[] = $rate;

            $sumUsd -= $avalible;

            if ($sumUsd <= 0) {

                $sumUahBefore += ($avalible + $sumUsd) * $rate;

                if ($sumUsd == 0) {
                    $value->spended_amount = $value->income;
                    $value->spended = 1;
                    $value->save();
                } else {
                    $value->spended_amount = $value->spended_amount + $avalible + $sumUsd;
                    $value->save();

                }

                $rateDifference = $sumUahNow - $sumUahBefore;
                $days = implode(", ", $days);
                $rayts = implode(", ", $rayts);


                if ($rateDifference >= 1) {
                    $info = " Income $rateDifference {$this->user->default_currency} from rate difference $rayts at the dates of reciving $days and rate at the day of exhange $request->date with rate $rateAtTheDayOfExchange";
                    $this->return[] = $this->writeToUserAction("Rate difference income", $rateDifference, $this->user->default_currency, $info);
                    break;
                }

                if ($sumUsd <= 0){
                    break;
                }

            } else {
                $sumUahBefore += $avalible * $rate;
                $value->spended_amount = $value->income;
                $value->spended = 1;
                $value->save();
            }
        }

        $formatedUahNow = number_format($sumUahNow / 100, 2);
        $info = "Income {$formatedUahNow} {$this->user->default_currency} from exchange $request->sum $request->currency with rate $rateAtTheDayOfExchange";
        $this->return[] = $this->writeToUserAction("Exchange income", $sumUahNow, $this->user->default_currency, $info);

        return $this->return;
    }

    private function writeToUserAction($actionType, $sum, $currency, $info)
    {
        $action = new UserAction();
        $action->user_id = $this->user->id;
        $action->action = $actionType;
        $action->sum = $sum;
        $action->currency = $currency;
        $action->date = $this->request->date;
        $action->info = $info;
        $action->save();

        return $action;
    }
}
