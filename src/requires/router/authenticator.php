<?php
class Authenticator {

    static function Supplier()
    {
        if(isset($_COOKIE ['supplierId'])) 
        {
            return $_COOKIE['supplierId'];
        }
        else
        {
            Router->handleRequest('/login');
            exit();
        }
    }
    static function Agent()
    {
        if(isset($_COOKIE ['agentId'])) 
        {
            return $_COOKIE['agentId'];
        }
        else
        {
            Router->handleRequest('/login');
            exit();
        }
        
    }
}
?>