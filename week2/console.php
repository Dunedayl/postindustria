<?php 
    function userInput()
    {
        print_r("Please enter city name: \n");
        $cityName = readline();
        print_r("Please enter country code (2 letter): \n\n");
        $counryCode = readline();

        return ([$cityName, $counryCode]);
    }

    function output($cityName, $counryCode, $formatedTime, $weather)
    {
        print_r("Weather in " . $cityName . " " . $counryCode . " for tomorrow:\n\n");
        print_r("Time: " . $formatedTime . "\n");
        print_r("Weather: " . $weather . "\n\n");
    }
?>