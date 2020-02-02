<?php
/**
 * Search for a specific service in the DB
 */
require("logic.php");
header("Content-Type: application/json");

$res = (object)[];

if(isset($_GET["id"])) {
    try {
        $data = (object)[];
        $id = $_GET["id"];

        // search the DB for the appointment/costumer data
        $statement = $conn->prepare("SELECT a.id, a.costumer, a.date, a.time, a.status, a.additional_info, a.price, a.staff,
                                            b.name, b.email, b.phone
                                     FROM appointments a, costumers b
                                     WHERE a.id = :id
                                     AND b.id = a.costumer");
        $statement->bindParam(":id", $id);
        $statement->execute();

        $row = $statement->fetch();

        if($row) {
            $costumer = (object)[];
            $costumer->name = $row["name"];
            $costumer->email = $row["email"];
            $costumer->phone = $row["phone"];

            $data->id = $row["id"];
            $data->status = $row["status"];
            $data->staff = $row["staff"];
            $data->price = $row["price"];
            $data->additional = $row["additional_info"];
            $data->costumer = $costumer;

            // search for the services
            $statement = $conn->prepare("SELECT a.service,
                                                b.price
                                         FROM appointment_services a, services b
                                         WHERE a.appointment = :id AND b.name = a.service");
            $statement->bindParam(":id", $id);
            $statement->execute();
            $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
            $data->services = $rows;

            // search for the charges
            $statement = $conn->prepare("SELECT id, charge, reason FROM appointment_charges WHERE appointment = :id");
            $statement->bindParam(":id", $id);
            $statement->execute();
            $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
            $data->charges = $rows;

            $res->status = "success";
            $res->data = $data;
        } else {
            $res->status = "error";
            $res->data = "ID not found";
        }
    } catch(Exception $e) {
        $res->status = "error";
        $res->data = (string) $e;
    } finally {
        echo json_encode($res, JSON_PRETTY_PRINT);
    }
} else {
    $res->status = "error";
    $res->data = "No ID provided";
    echo json_encode($res, JSON_PRETTY_PRINT);
}

?>