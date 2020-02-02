<?php
/**
 * Delete a specific service from the DB
 */
require("logic.php");
header("Content-Type: application/json");

if(isset($_POST["sname"])) {
    $ret = (object)[];

    try {
        // delete the service from the db
        $statement = $conn->prepare("DELETE FROM services WHERE name = :name");
        $statement->bindParam(":name", $_POST["sname"]);
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