<?php 

    class parser
    {
        private $data;
        private $dateOfWeather, $temperature;

        function __construct($data)
        {
            $this->data = $data;
        }

        // Parse data for Temperature at given Date
        function parseTemperatureAndDate($weatherTime)
        {
            // Adding all received time to array
            $arrayOfTime = [];
            $arrayOfTemp = [];

            foreach ($this->data->list as $key => $value) {
                array_push($arrayOfTime, $value->dt);
                array_push($arrayOfTemp, $value->main->temp);
            }
            //Finding closet avalible time
            $closest = $this->getClosest($weatherTime, $arrayOfTime);

            //Finding temperature of the given date and time
            $closest[1] = $arrayOfTemp[$closest[1]];

            $this->dateOfWeather = $closest[0];
            $this->temperature = $closest[1];
        }

        //Find closest timestamp avalible
        function getClosest($search, $arr)
        {
            $closest = null;
            $closestKey = null;
            foreach ($arr as $key => $item) {
                if ($closest === null || abs($search - $closest) > abs($item - $search)) {
                    $closest = $item;
                    $closestKey = $key;
                }
            }
            return array($closest, $closestKey);
        }

        function getDate()
        {
            return $this->dateOfWeather;
        }

        function getTemperature()
        {
            return $this->temperature;
        }
    }


?>