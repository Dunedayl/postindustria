<?php
namespace Config\sqlconfig;

Class Config 
{
    static function getConfig(){
        return array(
            'host' => '127.0.0.1',
            'database' => 'week5',
            'user' => 'root',
            'password' => 'MyStrongPassword88'
        );
    }
}
