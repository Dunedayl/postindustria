<?php 
    class Article
    {
        public $Header, $Body, $ChangeMap, $Tags, $ChangedBody;

        //Class for element containing 
        function __construct($Header, $Body, $ChangeMap, $Tags, $ChangedBody)
        {
            $this->Header = $Header;
            $this->Body = $Body;
            $this->ChangeMap = $ChangeMap;
            $this->Tags = $Tags;
            $this->ChangedBody = $ChangedBody;
        }

    }
?>