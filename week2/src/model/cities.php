<?php
    class City 
    {
        public $cityName;
        public $temperature;

        public function __construct($cityName, $temperature)
        {
            $this->cityName = $cityName;
            $this->temperature = $temperature;
        } 
    }
?>