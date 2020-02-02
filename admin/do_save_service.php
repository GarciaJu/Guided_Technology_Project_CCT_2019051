<?php
/**
 * Save an specific service in the DB
 */
require("logic.php");
header("Content-Type: application/json");

if(isset($_POST["oldname"]) && isset($_POST["sname"]) && isset($_POST["sprice"]) && isset($_POST["sreqtime"]) && isset($_POST["stype"])) {
    $ret = (object)[];

    try {
        // update the changes were made
        $statement = $conn->prepare("UPDATE services SET name = :name, type = :type, price = :price, time = :time WHERE name = :oldname");
        $statement->bindParam(":name", $_POST["sname"]);
        $statement->bindParam(":type", $_POST["stype"]);
        $statement->bindParam(":price", $_POST["sprice"]);
        $statement->bindParam(":time", $_POST["sreqtime"]);
        $statement->bindParam(":oldname", $_POST["oldname"]);
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