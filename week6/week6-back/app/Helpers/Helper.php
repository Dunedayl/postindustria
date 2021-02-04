<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class Helper
{
    public static function getRate($dbDate, $currency)
    {

        $date = date('Ymd', strtotime($dbDate));
        $response = Http::get("https://bank.gov.ua/NBUStatService/v1/statdirectory/exchange?valcode=$currency&date=$date");
        $xml = simplexml_load_string($response);
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);
        $rate = $array["currency"]["rate"];

        return $rate;
    }
}
