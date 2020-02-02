<?php
/**
 * Remove specific charge related to determined appointment
 */
require("logic.php");
header("Content-Type: application/json");

if(isset($_POST["id"])) {
    $id = $_POST["id"];

    $ret = (object)[];

    try {
        // update the database with the charge
        $statement = $conn->prepare("DELETE FROM appointment_charges WHERE id = :id");
        $statement->bindParam(":id", $id);
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