<?php 
    class Curl
    {
        private $requestUri;
        
        public function __construct($requestUri)
        {
            $this->requestUri = $requestUri;
        }

        function makeCurlRequest()
        {
            // Request to the API
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, $this->requestUri);
            $response = curl_exec($ch);
            curl_close($ch);
            $data = json_decode($response);

            return $data;
        }
    }
    
?>