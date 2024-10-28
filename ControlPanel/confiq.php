<?php
    $dsn = "mysql:host=localhost;dbname=lb_medical_castle"; // Data Source Name
    $userDB = "root";
    $passDB = "";
    $options = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    );
    try {
        $conn = new PDO($dsn, $userDB, $passDB, $options);
        $conn ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e){
        echo 'Connecting Failed'. $e->getMessage();
    }
    date_default_timezone_set("Africa/Tripoli");
