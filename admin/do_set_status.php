<?php
/**
 * Change the status from determined appointment
 */
require("logic.php");
header("Content-Type: application/json");

$ret = (object)[];

if(isset($_POST["status"]) && isset($_POST["id"])) {
    $status = $_POST["status"];
    $id = $_POST["id"];

    try {
        $statement = $conn->prepare("UPDATE appointments SET `status` = :status WHERE `id` = :id");
        $statement->bindParam(":status", $status);
        $statement->bindParam(":id", $id);
        $statement->execute();

        $ret->status = "success";
    } catch(Exception $e)  {
        $ret->status = "error";
        $ret->data = (string) $e;
    } finally {
        echo json_encode($ret, JSON_PRETTY_PRINT);
    }
} else {
    header("location:../404");
}

?>