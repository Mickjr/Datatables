<?php

    define('DB_NAME', 'testdb');
    define('DB_PASS', 'jesus777');
    define('DB_USER', 'testdb');
    define('DB_HOST', 'localhost');

    $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if($db->connect_errno > 0){
        die('Unable to connect to database [' . $db->connect_error . ']');
    }
