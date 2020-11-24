<?php
    include ("../config/apiKey.php");
    include ("../src/formaters/dateFormater.php");
    include ("../src/formaters/parseData.php");
    include ("../src/api/weatherApi.php");
    include ("../src/model/cities.php");
    include ("../src/output/console.php");

    //Basic information
    $day = 'tomorrow';
    $time = ['8:00', '12:00', '16:00', '20:00'];

    //User input
    $console = new console;
    $cityName = $console->getCityName();
    $counryCode = $console->getCountryCode();

    //Request to API
    $weatherReques = new weatherApi($cityName, $counryCode, $apiKey);
    $requestUrl = $weatherReques->generateRequestUrlFiveDayForecast();
    $data = $weatherReques->makeRequest($requestUrl);

    //Getting timezon of City
    $dateFormater = new dateFormater();
    $timezoneSecconds = $data->city->timezone;
    $timezone = $dateFormater->getTimezone($timezoneSecconds);

    $parser = new parser($data);

    foreach ($time as $key => $value) {
        //Getting Time
        $weatherTime = $dateFormater->getTime($day, $value, $timezone);

        //Getting Weather
        $parser->parseTemperatureAndDate($weatherTime);
        $temperature = $parser->getTemperature(); 
        $date = $parser->getDate();
        //Getting Readable time
        $formatedTime = $dateFormater->convertTo($timezone, $date);

        $console->output($formatedTime, $temperature);
    }
?>