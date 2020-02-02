<?php
/**
 * Administration logic component
 */
session_start();
// if user is not logged in, should redirect to login page
if(!isset($_SESSION['salon-session'])) {
    header('location:../login');
    return;    
}
require("../db/conn.php");

$conn = connect_db();
$user = get_user_info();

// if the user is not an administrator, should redirect to main page
if(!$user->admin) {
    header('location:../appointment');
    return;
}

function get_user_info() {
    $sess_dec = explode(":", base64_decode($_SESSION['salon-session']));
    $id = $sess_dec[0];
    $pwd = $sess_dec[1];

    global $conn;

    $statement = $conn->prepare("SELECT `name`, `admin` FROM costumers WHERE id = :id AND pwd = :pwd");
    $statement->bindParam(":id", $id);
    $statement->bindParam(":pwd", $pwd);
    $statement->execute();
    $user = (object)[];

    $row = $statement->fetch();
    $user->id = $id;
    $user->name = $row["name"];
    $user->admin = $row["admin"];

    return $user;
}

?>