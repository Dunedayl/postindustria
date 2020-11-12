<?php 
    class Article
    {
        public $Header, $Body, $ChangeMap, $Tags;

        //Class for element containing 
        function __construct($Header, $Body, $ChangeMap, $Tags)
        {
            $this->Header = $Header;
            $this->Body = $Body;
            $this->ChangeMap = $ChangeMap;
            $this->Tags = $Tags;
        }

    }
?>