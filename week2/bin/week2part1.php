<?php

    include ("../config/apiKey.php");
    include ("../src/formaters/dateFormater.php");
    include ("../src/formaters/parseData.php");
    include ("../src/api/weatherApi.php");
    include ("../src/model/cities.php");
    include ("../src/output/csvWriter.php");

    date_default_timezone_set('Europe/Zaporozhye');
    
    $fileName = 'forecastHistory.csv';
    $day = 'tomorrow';
    $time = '22:00';

    // Array of cities with country Id
    $cities = array(
        array("cityName" => "Kyiv",         "countryCode" =>  "UA"),
        array("cityName" => "London",       "countryCode" =>  "GB"),
        array("cityName" => "Berlin",       "countryCode" =>  "DE"),
        array("cityName" => "New York",     "countryCode" =>  "US"),
        array("cityName" => "Los Angeles",  "countryCode" =>  "US")
    );

    $dateFormater = new dateFormater();

    //Difference between Kyiv and UTC in seconds
    $timezoneKyiv = new DateTimeZone("Europe/Zaporozhye");
    $NowKyiv = new DateTime("now", $timezoneKyiv);
    $timeZone = $NowKyiv->getOffset();
    //Get Kyiv timezon in format +/- X GMT
    $timeZone = $dateFormater->getTimezone($timeZone);

    //Unix timespampt of Tommorow Kyiv 22:00 in UTC
    $dateTommorowUnix = $dateFormater->getTime($day,$time, $timeZone);

    //Date closest to $time 
    $closestDate;

    $arrayOfObjects = [];

    foreach ($cities as $key => $value) {

        //Request for weather
        $weatherReques = new weatherApi($value["cityName"],$value["countryCode"], $apiKey);
        $requestUrl = $weatherReques->generateRequestUrlFiveDayForecast();
        $data = $weatherReques->makeRequest($requestUrl);

        //Parse response for temperature
        $parser = new parser($data);
        $parser->parseTemperatureAndDate($dateTommorowUnix);

        //Get closset Date APi can give
        $closestDate = $parser->getDate();
        $temperature = $parser->getTemperature();

        //Add city to array of Objects
        $city = new City($value["cityName"], $temperature);
        
        array_push($arrayOfObjects, $city);
    }

    //Get UTC timezon
    $timezomeUtc = $dateFormater->getTimezone(0);
    //Get clossest date in readable format
    $timeKyivReadeable = $dateFormater->convertTo($timezomeUtc, $closestDate);

    //Array for CSV add Date
    $writeToCsv = [["Date"],[$timeKyivReadeable]];

    // Add cities to array for CSV
    foreach ($arrayOfObjects as $key => $value) {
        array_push($writeToCsv[0], $value->cityName);
        array_push($writeToCsv[1], $value->temperature);
    }

    // Output to CSV
    $csvWriter = new csvWriter($writeToCsv, $fileName);
    $csvWriter->printToCsv();
