<?php
require "router.php";

const ROUTER = new Router();

class Route {
    
    static function add($url, $handler)
    {
        return ROUTER->addRoute($url, $handler.'.php');
    }
    //Path for header and footer is relative to public/index.php
    public static $__SRC = "../src/";
    public static $__INC = "../src/includes/";
    public static $__REQ = "../src/requires/";

    static function header($page_title = "Inventory Manager")
    {
        if (isset($_COOKIE['agentId']))
        {
            include self::$__INC."agent/header.php";
            return;
        }
        if (isset(($_COOKIE['supplierId'])))
        {
            include self::$__INC."supplier/header.php";
            return;
        }
        include self::$__INC."header.php";
    }
    static function footer()
    {
        // if (isset($_COOKIE['agentId']))
        // {
        //     include_once self::$__INC."agent/header.php";
        // }
        // if (isset(($_COOKIE['supplierId'])))
        // {
        //     include_once self::$__INC."supplier/header.php";
        // }
        include_once self::$__INC."footer.php";
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
//Some path - Path specified is relative to this
$root = "../../../";
$public = $root."public/";
$src = $root."src/";
$requires = $src."requires/";
$mysql = $src."mysql/";
$module1 = $src."module1/";
$module2 = $src."module2/";
$module3 = $src."module3/";
$module4 = $src."module4/";
$includes = $src."includes/";
$aIncludes = $includes."agent/";
$sIncludes = $includes."supplier/";

// require_once $mysql.'mysqli.php';
if(!(isset($isIndex) && $isIndex))
{
    require_once $requires.'field.php';
    require_once $requires.'generalFunction.php';
}

//Specify all route here
// Route::add('/home', $public.'home.php');
// Route::add('/about', $public.'about.php');
// Route::add('/contact', $public.'contact.php');

//Public (For Reloading)
//Refresh page
Route::add('/refresh', $includes.'refresh');

//Header
Route::add('/agentHeader', $aIncludes.'header');
Route::add('/supplierHeader', $sIncludes.'header');

//Home
Route::add('/home', $src.'home');
Route::add('/supplierHome', $module1.'supplier');
Route::add('/agentHome', $module1.'agent');

//Module 1
Route::add('/login', $module1.'login_li');
Route::add('/logout', $module1.'logout_li');
Route::add('/register', $module1.'register_li');
Route::add('/viewItemDetails', $module1.'view_item_details');
Route::add('/viewOrderDetails', $module1.'view_order_details');

//Module 2
Route::add('/restockItem', $module2.'restockItem');

//Module 3
Route::add('/approveOrder', $module3.'approveOrder');
Route::add('/createOrder', $module3.'createOrder');

//Module 4
Route::add('/agentPerformance', $module4.'agentPerformance');
Route::add('/salesPerformance', $module4.'salesPerformance');
Route::add('/recordOrder', $module4.'recordOrder');

require "authenticator.php";
?>