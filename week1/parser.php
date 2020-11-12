<?php

    //Function for string parsing
    function parseString($ArrayOfArticles)
    {
        $Objects = [];
        // Regulars for elements
        $HeaderReg = '/Header:\s*([^\n\r]*)/m';
        $BodyReg = '/Body:\s*([^\n\r]*)/m';
        $TagsReg = '/Tags:\s*([^\n\r]*)/m';

        //Regular for Changes (require aditional formating)
        $ChangeMapReg = '/ChangeMap:\s*([^\r]*[ ]{8}.*)/m';
        
        //Using RegeX to filter string
        foreach ($ArrayOfArticles  as $key => $value) {

            preg_match($ChangeMapReg, $value, $ChangeMatches);
            $ChangeMatches[1] = trim($ChangeMatches[1]);

            $ChangeMatches = array_filter (explode ("\n",$ChangeMatches[1]));

            preg_match($BodyReg, $value, $BodyMatches);
            $BodyMatches = $BodyMatches[1];

            preg_match($HeaderReg, $value, $HeaderMatches);
            $HeaderMatches = $HeaderMatches[1];

            preg_match($TagsReg, $value, $TagsMatches);
            $TagsMatches = $TagsMatches[1];

            //Creating array of tags
            $TagsMatches =  explode(",", $TagsMatches);
            
            //Create new body with replacemants 
            $ChangedBody =  change($BodyMatches, $ChangeMatches);

            $article = new Article($HeaderMatches, $BodyMatches, $ChangeMatches, $TagsMatches, $ChangedBody);
            array_push($Objects, $article);
        }
        return $Objects;
    }

    //Replace text in body with elements from ChangeMap
    function change($Body, $ChangeMap)
    {
        $Return = $Body;

        foreach ($ChangeMap as $value) {
            $value = trim($value);
            $value = explode(":", $value);
            $Return = str_replace($value[0], $value[1], $Return);
        }

        return $Return;
    }
?>