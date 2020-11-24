<?php

    class dateFormater
    {
        //Geting unix time for $day and $time in $timezone
        function getTime($day, $time, $timezone)
        {

            $date = new DateTime($day . " " . $time, $timezone);

            $dateToReturn = $date->getTimestamp();

            return $dateToReturn;
        }

        //Convert time to readable format. 
        function convertTo($timezone, $timestamp)
        {
            $formater = new DateTime();
            $formater->setTimezone($timezone);
            $formater->setTimestamp($timestamp);

            $timeReadeable =  $formater->format('Y/m/d H:i:s');

            return $timeReadeable;
        }

        //Geting Timezon in format +/- X GMT
        function getTimezone($timezoneSecconds)
        {
            $timezone = $timezoneSecconds / (60 * 60);

            if ($timezone >= 0) {
                $timezone = "+" . $timezone;
            }

            $timezone = timezone_open($timezone);

            return $timezone;
        }
    }
?>  