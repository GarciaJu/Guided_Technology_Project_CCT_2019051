<?php
/**
 * Save the new extra services to determined appointment
 */
require("logic.php");
header("Content-Type: application/json");

if(isset($_POST["id"]) && isset($_POST["services"])) {
    $res = (object)[];

    try {
        $id = $_POST["id"];
        $services = $_POST["services"];
        
        // get the new price to add into the appointments row
        $qMarks = str_repeat('?,', count($services) - 1) . '?';
        $statement = $conn->prepare("UPDATE appointments SET price = price + (SELECT SUM(price) AS sum FROM services WHERE `name` IN ($qMarks)) WHERE id = ?");
        $temp_services = $services;
        array_push($temp_services, $id);
        $statement->execute($temp_services);
        //$res->data = $statement->fetchAll(PDO::FETCH_ASSOC);

        // add the new service to the appointment
        foreach($services as $service) {
            $statement = $conn->prepare("INSERT INTO appointment_services (appointment, service) VALUES (:appointment, :service)");
            $statement->bindParam(":appointment", $id);
            $statement->bindParam(":service", $service);
            $statement->execute();
        }

        $res->status = "success";
    } catch(Exception $e) {
        $res->status = "error";
        $res->data = (string) $e;
    } finally {
        echo json_encode($res, JSON_PRETTY_PRINT);
    }
} else {
    header("location: ../404");
}

?>