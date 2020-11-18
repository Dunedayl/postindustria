<?php
    include "requests.php";
    include "parseData.php";
    include "dateTime.php";

    //Basic information
    $apiKey = "568fb694b771f72ffd435bdd1a34d08d";
    $day = 'tomorrow';
    $time = ['8:00', '12:00', '16:00', '20:00'];

    //User input
    print_r("Please enter city name: \n");
    $cityName = readline();
    print_r("Please enter country code (2 letter): \n\n");
    $counryCode = readline();

    //Request to API
    $data = makeRequest($cityName, $counryCode, $apiKey);

    print_r("Weather in " . $cityName . " " . $counryCode . " for tomorrow:\n\n");

    //Getting timezon of City
    $timezoneSecconds = $data->city->timezone;
    $timezone = getTimezone($timezoneSecconds);

    foreach ($time as $key => $value) {
        //Gettin Time
        $weatherTime = getTime($day, $value, $timezone);
        //Getting Weather
        $weather = parseData($data,$weatherTime);

        print_r("Time: " . convertTo($timezone, $weather[0]) ."\n");
        print_r("Weather: " . $weather[1] . "\n\n");
    }

?>