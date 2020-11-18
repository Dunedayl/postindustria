<?php
    include "requests.php";
    include "parseData.php";
    include "dateTime.php";
    include "console.php";

    //Basic information
    $apiKey = "568fb694b771f72ffd435bdd1a34d08d";
    $day = 'tomorrow';
    $time = ['8:00', '12:00', '16:00', '20:00'];

    //User input
    $input = userInput();
    $cityName = $input[0];
    $counryCode = $input[1];

    //Request to API
    $data = makeRequest($cityName, $counryCode, $apiKey);

    //Getting timezon of City
    $timezoneSecconds = $data->city->timezone;
    $timezone = getTimezone($timezoneSecconds);

    foreach ($time as $key => $value) {
        //Getting Time
        $weatherTime = getTime($day, $value, $timezone);
        //Getting Weather
        $weather = parseData($data,$weatherTime);
        //Getting Readable time
        $formatedTime = convertTo($timezone, $weather[0]);

        output($cityName, $counryCode, $formatedTime, $weather[1]);
    }
?>