<?php
/**
 * Load a specific service from the DB
 */
require("logic.php");
header("Content-Type: application/json");

if(isset($_GET['sname'])) {
    $ret = (object)[];
    try {
        // load the selected service data from the db
        $statement = $conn->prepare("SELECT * FROM services WHERE name = :sname");
        $statement->bindParam(":sname", $_GET["sname"]);
        $statement->execute();
        $service = $statement->fetch();

        if($service) {
            $ret->name = $service["name"];
            $ret->type = $service["type"];
            $ret->price = $service["price"];
            $ret->time = $service["time"];
        } else {
            $ret->status = "error";
            $ret->reason = "service-doesnt-exist";
        }
    } catch(Exception $e) {
        $ret->status = "error";
        $ret->reason = (string) $e;
    }
    echo json_encode($ret, JSON_PRETTY_PRINT);
} else {
    header("location:../404");
}

?>