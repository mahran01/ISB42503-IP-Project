<?php
    //Necessary Constant
    DEFINE ('DB_USER', 'root');
    DEFINE ('DB_PASSWORD', '');
    DEFINE ('DB_HOST', 'localhost');
    DEFINE ('DB_NAME', 'ip_project');

    // Make MySQL connection. (Object-oriented style)
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
    // Make MySQL connection. (Procedural style)
    $dbc = @mysqli_connect(DB_HOST, DB_USER) OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );
    // echo"<p>Successfully connected to MySQL</p>\n";

    // Select database.
    @mysqli_select_db($dbc, DB_NAME) OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );
    // echo"<p>Database name = ".DB_NAME."</p>\n";
    
?>