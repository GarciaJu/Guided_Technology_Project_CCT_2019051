<?php

/**
 * Connects to the database
 * returns an PDO object, connected
 */
function connect_db() {
    $server = "localhost";
    $user = "root";
    $pwd_db = "";
    $db = "salon";

    $conn = new PDO("mysql:host=$server;dbname=$db",$user,$pwd_db);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conn;
}

?>