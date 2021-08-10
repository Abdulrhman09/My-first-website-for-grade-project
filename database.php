<?php 

    /** ربط قاعده البيانات**/
    
    $dbname   = "charity_db";
    $username = "root";
    $password = "";

    try {
        $pdo = new PDO("mysql:dbname=$dbname;host=localhost;charset=utf8",$username,$password,);
    }catch(Exception $ex){
        echo "Failed to connect : $ex";
        exit;
    }