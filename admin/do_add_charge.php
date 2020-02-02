<?php
/**
 * Creates new charge related to determined appointment
 */
require("logic.php");
header("Content-Type: application/json");

if(isset($_POST["id"]) && isset($_POST["charge"]) && isset($_POST["reason"])) {
    $id = $_POST["id"];
    $charge = $_POST["charge"];
    $reason = $_POST["reason"];

    $ret = (object)[];

    try {
        // update the database with the charge
        $statement = $conn->prepare("INSERT INTO appointment_charges (appointment, charge, reason) VALUES (:id, :charge, :reason)");
        $statement->bindParam(":id", $id);
        $statement->bindParam(":charge", $charge);
        $statement->bindParam(":reason", $reason);
        $statement->execute();

        $ret->status = "success";
    } catch(Exception $e) {
        $ret->status = "error";
        $ret->data = (string) $e;
    } finally {
        echo json_encode($ret, JSON_PRETTY_PRINT);
    }
} else {
    header("location: ../404");
}

?>