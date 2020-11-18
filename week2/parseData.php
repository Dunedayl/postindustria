<?php 

    // Parse data for Temperature and Date
    function parseData($data, $weatherTime)
    {
        // Adding all received time to array
        $arrayOfTime = [];
        $arrayOfTemp = [];

        $formater = new DateTime();

        foreach ($data->list as $key => $value) {
            array_push($arrayOfTime, $value->dt);
            array_push($arrayOfTemp, $value->main->temp);
        }

        $closest = getClosest($weatherTime, $arrayOfTime);
        $formater->setTimestamp($closest[0]);
        $closest[1] = $arrayOfTemp[$closest[1]];

        // return Array [Date] [Temperature]
        return $closest;
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
?>