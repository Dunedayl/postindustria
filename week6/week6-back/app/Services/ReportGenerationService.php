<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Models\UserAction;
use Illuminate\Support\Facades\Http;


//NOTE ALL MONEY STORED IN CENTS
class ReportGenerationService
{

    public function makeReport($user, $from, $to)
    {
        $this->user = $user;

        $database = UserAction::where('date', "<=", $to)
            ->where('date', ">=", $from)
            ->where("user_id", $user->id)
            ->get();

        $userTax = $user->tax_rate;

        $incomeUah = 0;
        $incomeAnotherCurrencies = [];
        $incomeForceExchange = 0;
        $incomeRateDifference = 0;

        foreach ($database as $value) {

            if ($value->action == "Income" and $value->currency == "UAH") {
                $incomeUah += $value->sum;
            }

            if ($value->action == "Income" and $value->currency != "UAH") {

                if (!array_key_exists($value->currency, $incomeAnotherCurrencies)) {
                    $incomeAnotherCurrencies[$value->currency] = 0;
                }

                $incomeAnotherCurrencies[$value->currency] += $value->sum * Helper::getRate($value->date, $value->currency);
            }

            if ($value->action == "Rate difference income") {
                $incomeRateDifference += $value->sum;
            }

            if ($value->action == "Force exchange UAH income") {
                $incomeForceExchange += $value->sum;
            }
        }

        $totalIncome = $incomeUah + $incomeRateDifference + $incomeForceExchange;

        foreach ($incomeAnotherCurrencies as &$value) {
            $totalIncome += $value;
            $value = number_format($value / 100, 2);
        }

        $tax = $totalIncome * ($userTax / 100);

        return [
            'incomeUah' => number_format($incomeUah / 100, 2),
            "incomeForceExchange" => number_format($incomeForceExchange / 100, 2),
            "incomeRateDifference" => number_format($incomeRateDifference / 100, 2),
            "totalIncome" => number_format($totalIncome / 100, 2),
            "tax" =>number_format($tax / 100, 2),
            'incomeAnotherCurrencies' => $incomeAnotherCurrencies,
        ];
    }
}
