<?php
/**
 * Delete a specific appointment from the database
 */
require("logic.php");
header("Content-Type: application/json");

if(isset($_POST["id"])) {
    $id = $_POST["id"];
    $ret = (object)[];
    try {
        // delete the appointment from the db
        $statement = $conn->prepare("DELETE FROM appointments WHERE id = :id");
        //$statement = $conn->prepare("UPDATE appointments SET status = 'Unfinished' WHERE id = :id");
        $statement->bindParam(":id", $id);
        $statement->execute();

        $ret->status = "success";
    } catch (Exception $e) {
        $ret->status = "error";
        $ret->data = (string) $e;
    }
    echo json_encode($ret, JSON_PRETTY_PRINT);
} else {
    header("location:../404");
}
?>