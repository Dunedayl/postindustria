<?php
    include "week_1_task_1.php";
    include "articles.php";
    include "printer.php";
    include "parser.php";

    //Array of element to output
    $ArrayOfNeeded = [];
    //Tag for filtering 
    $SearchTag = "Automotive Industry";
    //Separation of string by Article:
    $ArrayOfArticles = explode("Article:", $data);
    $ArrayOfArticles = array_filter($ArrayOfArticles);
    //Array of Objects
    $Objects = parseString($ArrayOfArticles);


    //Filter array of Objects
    foreach ($Objects as $key => $value) {
        if (in_array($SearchTag, $value->Tags))
        {
            array_push($ArrayOfNeeded, $value);
        }
    }

    printHTML($ArrayOfNeeded);

?>