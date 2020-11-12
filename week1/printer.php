<?php
    include "parser.php";

    //Print function
    function printHTML($ObjectsToPrint)
    {
        $Headers = headerGenerator($ObjectsToPrint);

        $Articles = articleGenerator($ObjectsToPrint);

        $Tags = tagGenerator($ObjectsToPrint);

        require "out.php";
    }


    //Generetion of Article
    function articleGenerator($Objects)
    {
        $Headers = [];
        $Body = [];

        foreach ($Objects as $value) {
            array_push($Headers, $value->Header);
            array_push($Body, change($value->Body, $value->ChangeMap));
        }

        $Return = array_combine($Headers, $Body);

        return $Return;
    }

    //Generetion of Tag
    function tagGenerator($ObjectsToPrint)
    {
        $FinalTagsArray = [];

        foreach ($ObjectsToPrint as $key => $value) {

            foreach ($value->Tags as $key => $tagValue) {

                if (!in_array($tagValue, $FinalTagsArray)){
                    array_push($FinalTagsArray, $tagValue);
                }
            }        
        }
        $Return = implode (", ", $FinalTagsArray);

        return $Return;
    }

    //Generation of Headers for Table_of_content
    function headerGenerator($Objects)
    {

        $Return = [];
        foreach ($Objects as $value) {
            array_push($Return, $value->Header);
        }
        return $Return;
    }

?>