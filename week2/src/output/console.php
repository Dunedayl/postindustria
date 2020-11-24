<?php 
    class console
    {
        private $cityName, $counryCode;

        function __construct()
        {
            $this->userInput();
        }

        function userInput()
        {
            print_r("Please enter city name: \n");
            $this->cityName = readline();
            print_r("Please enter country code (2 letter): \n\n");
            $this->counryCode = readline();
        }

        function output($formatedTime, $weather)
        {
            print_r("Time: " . $formatedTime . "\n");
            print_r("Weather: " . $weather . "\n\n");
        }

        function getCityName()
        {
            return $this->cityName;
        }

        function getCountryCode()
        {
            return $this->counryCode;
        }
    }
?>