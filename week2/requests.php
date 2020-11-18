<?php 
    function makeRequest($cityName, $countryCode, $apiKey)
    {
        //Request URL
        $requestUri = "http://api.openweathermap.org/data/2.5/forecast?q=" . $cityName . "," . $countryCode . "&lang=en&units=metric&appid=" . $apiKey;

        // Request to the API
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $requestUri);
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response);

        return $data;
    }
?>