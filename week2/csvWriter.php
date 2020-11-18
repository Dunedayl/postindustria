<?php 
    function printToCsv($writeToCsv, $fileName)
    {
        // If CSV exist
        if (file_exists($fileName))
        {
            $csvData = [];

            //Write CSV to Array
            if (($h = fopen("{$fileName}", "r")) !== FALSE) {

                while (($data = fgetcsv($h, 1000, ",")) !== FALSE) {
                    $csvData[] = $data;
                }
                fclose($h);
            }

            // Check repeateable values in CSV 
            $replace = checkDataInCsv($csvData, $writeToCsv);
            // If return is not Null Date already exists in array
            if (!$replace == null){

                // Replace data in CSV file
                createCsv($replace, $fileName, 'w');

            } else {
                // Add lines to CSV File
                createCsv($writeToCsv[1], $fileName, 'a');
            }

        //CSV don't exist
        } else {
            //Create new CSV file
            createCsv($writeToCsv, $fileName, 'w');
        }
    }

    // Create or Edit CSV file
    function createCsv($writeToCsv, $fileName, $accesWay)
    {
        $fp = fopen($fileName, $accesWay);

        // Edit File
        if ($accesWay == "a"){

            fputcsv($fp, $writeToCsv);

        // Create new File
        } elseif ($accesWay == "w"){

            foreach ($writeToCsv as $value) {
                fputcsv($fp, $value);
            }
        }

        fclose($fp); 
    }

    /*Check for Date in CSV file
    If date exists, replace line with this date with new info
    and create new csv file.
    */

    function checkDataInCsv($csvData, $writeToCsv)
    {
        $j = 0;
        for ($i = 1; $i < count($csvData); $i++) {

            if ($csvData[$i][0] == $writeToCsv[1][0]) {

                $csvData[$i] = $writeToCsv[1];
                $j++;
            }
        }

        if ($j == 0){
            return null;
        } else {
            return $csvData;
        }
    }