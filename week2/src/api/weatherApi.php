<?php
    include ("curl.php");

    class weatherApi
    {
        private $cityName, $countryCode, $apiKey;

        function __construct($cityName, $countryCode, $apiKey)
        {
            $this->apiKey = $apiKey;
            $this->cityName = $cityName;
            $this->countryCode = $countryCode;
        }

        function generateRequestUrlFiveDayForecast()
        {
            $requestUri = "http://api.openweathermap.org/data/2.5/forecast?q=" . $this->cityName . ","
                . $this->countryCode . "&lang=en&units=metric&appid=" . $this->apiKey;

            return $requestUri;
        }

        function makeRequest($requestUri)
        {
            $request = new Curl($requestUri);
            $data = $request->makeCurlRequest();
            return $data;
        }
    }
    
?>