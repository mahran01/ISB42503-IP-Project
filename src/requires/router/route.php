<?php
require "router.php";

const ROUTER = new Router();

class Route {
    
    static function add($url, $handler)
    {
        return ROUTER->addRoute($url, $handler.'.php');
    }
    static function header()
    {
        Router::header();
    }
    static function footer()
    {
        Router::footer();
    }
    static function MYSQL()
    {
        require_once '../../mysql/mysqli.php';
        return $mysqli;
    }
    static function MYSQL_PROCEDURAL()
    {
        require_once '../../mysql/mysqli.php';
        return $dbc;
    }
    static function MYSQL_BOTH()
    {
        require_once '../../mysql/mysqli.php';
        return [$mysqli, $dbc];
    }
}
//Some path Path specified is relative to generalFuntion.php
$root = "../../../";
$public = $root."public/";
$src = $root."src/";
$requires = $src."requires/";
$mysql = $src."mysql/";
$module1 = $src."module1/";
$module2 = $src."module2/";
$module3 = $src."module3/";
$module4 = $src."module4/";

// require_once $mysql.'mysqli.php';
require_once $requires.'field.php';
require_once $requires.'generalFunction.php';

//Specify all route here
// Route::add('/home', $public.'home.php');
// Route::add('/about', $public.'about.php');
// Route::add('/contact', $public.'contact.php');

//Home
Route::add('/supplierHome', $module1.'supplier');
Route::add('/agentHome', $module1.'agent');

//Module 1
Route::add('/login', $module1.'login_li');
Route::add('/logout', $module1.'logout_li');
Route::add('/register', $module1.'register_li');

//Module 2
Route::add('/restockItem', $module2.'restockItem');

//Module 3
Route::add('/approveOrder', $module3.'approveOrder');
Route::add('/createOrder', $module3.'createOrder');

//Module 4
Route::add('/agentPerformance', $module4.'agentPerformance');
Route::add('/salesPerformance', $module4.'salesPerformance');
Route::add('/recordOrder', $module4.'recordOrder');
?>