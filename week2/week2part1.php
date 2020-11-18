<?php
    include "requests.php";
    include "parseData.php";
    include "cities.php";
    include "csvWriter.php";
    include "dateTime.php";

    date_default_timezone_set('Europe/Zaporozhye');
    
    $apiKey = "568fb694b771f72ffd435bdd1a34d08d";
    $fileName = 'forecastHistory.csv';
    $day = 'tomorrow';
    $time = '22:00';

    //Difference between Kyiv and UTC in seconds
    $timezoneKyiv = new DateTimeZone("Europe/Zaporozhye");
    $NowKyiv = new DateTime("now", $timezoneKyiv);
    $timeZone = $NowKyiv->getOffset();
    //Get Kyiv timezon in format +/- X GMT
    $timeZone = getTimezone($timeZone);

    //Unix timespampt of Tommorow Kyiv 22:00 in UTC
    $dateTommorowUnix = getTime($day,$time, $timeZone);

    $closestDate;
    
    // Array of cities with country Id
    $cities = array(
        array("Kyiv", "UA"),
        array("London", "GB"),
        array("Berlin", "DE"),
        array("New York", "US"),
        array("Los Angeles", "US")
    );

    $arrayOfObjects = [];

    foreach ($cities as $key => $value) {
        //Request for wearher
        $data = makeRequest($value[0], $value[1], $apiKey);
        //Parse response for temperature
        $tommorowWeather = parseData($data, $dateTommorowUnix);
        //Get closset Date APi can give
        $closestDate = $tommorowWeather[0];
        //Add city to array of Objects
        $city = new City($value[0], $tommorowWeather[1]);
        array_push($arrayOfObjects, $city);
    }

    $timezomeUtc = getTimezone(0);

    //Get clossest date in readable format
    $timeKyivReadeable = convertTo($timezomeUtc, $closestDate);

    //Array for CSV add Date
    $writeToCsv = [[],[]];
    array_push($writeToCsv[0], "Date");
    array_push($writeToCsv[1], $timeKyivReadeable);

    // Add cities to array for CSV
    foreach ($arrayOfObjects as $key => $value) {
        array_push($writeToCsv[0], $value->cityName);
        array_push($writeToCsv[1], $value->temperature);
    }

    // Output to CSV
    printToCsv($writeToCsv, $fileName);
