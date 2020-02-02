<?php
/**
 * Add new service in the DB
 */
require("logic.php");
header("Content-Type: application/json");

if(isset($_POST["sname"]) && isset($_POST["sprice"]) && isset($_POST["sreqtime"]) && isset($_POST["stype"])) {
    $ret = (object)[];

    try {
        // insert the new service in the db
        $statement = $conn->prepare("INSERT INTO services (name, type, price, time) VALUES (:name, :type, :price, :time)");
        $statement->bindParam(":name", $_POST["sname"]);
        $statement->bindParam(":type", $_POST["stype"]);
        $statement->bindParam(":price", $_POST["sprice"]);
        $statement->bindParam(":time", $_POST["sreqtime"]);
        $statement->execute();

        $ret->status = "success";
    } catch(Exception $e) {
        $ret->status = "error";
        $ret->reason = (string) $e;
    }

    echo json_encode($ret, JSON_PRETTY_PRINT);
} else {
    header("location:../404");
}
?>