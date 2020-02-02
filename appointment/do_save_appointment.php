<?php 
/**
 * Save the appointment in the database
 */
require("logic.php");
header("Content-Type: application/json");

if(isset($_POST["services"]) && isset($_POST["date"]) && isset($_POST["price"]) && isset($_POST["addInfo"])) {
    $resp = (object)[];
    try {
        $services = $_POST["services"];
        $datetime = new DateTime($_POST["date"]);
        $additionalInfo = $_POST["addInfo"];
        $price = $_POST["price"];

        $date = $datetime->format("Y-m-d");
        $time = $datetime->format("H:i");
        
        // check which staff is available in desired date
        // check the whole day, and choose the first staff not working at the
        // same time, that doesn't have the full day scheduled (4 hours maximum)
        $statement = $conn->prepare("SELECT staff, time FROM appointments WHERE date = :date");
        $statement->bindParam(":date", $date);
        $statement->execute();
        $row = $statement->fetchAll();

        if($row) {
            $staff_hours = [ 0, 4, 4, 4, 4, 4 ]; // number of hours each staff team has available

            // search for the scheduled appointments, and reduce the number of hours of the teams
            foreach($row as $scheduled) {
                $staff_hours[ intval($scheduled["staff"]) ]--;

                // now, remove the teams that are busy that time
                if(strtotime($scheduled["time"]) == strtotime($time)) {
                    $staff_hours[ intval($scheduled["staff"]) ] = 0;
                }
            }

            // selected staff
            $staff = 0;
            foreach($staff_hours as $item) {
                if($item == 0) {
                    $staff++;
                } else {
                    break;
                }
            }
        } else {
            $staff = 1;
        }

        // no staff available, throw error
        if($staff >= 6) {
            $resp->status = "error";
            $resp->reason = "This date/time is not available.";
        } else {
            // create the appointment
            $user = get_user_info();

            $statement = $conn->prepare("INSERT INTO appointments (costumer, date, time, status, additional_info, price, staff) VALUES (:costumer, :date, :time, 'Booked', :additional, :price, :staff)");
            $statement->bindParam(":costumer", $user->id);
            $statement->bindParam(":date", $date);
            $statement->bindParam(":time", $time);
            $statement->bindParam(":additional", $additionalInfo);
            $statement->bindParam(":price", $price);
            $statement->bindParam(":staff", $staff);
            $statement->execute();

            // save the appointment id
            $ap_id = $conn->lastInsertId();

            // insert all services
            foreach($services as $service) {
                $statement = $conn->prepare("INSERT INTO appointment_services (appointment, service) VALUES (:appointment, :service)");
                $statement->bindParam(":appointment", $ap_id);
                $statement->bindParam(":service", $service);
                $statement->execute();
            }

            // if everything is succesfully, returns a json with success
            $resp->status = "success";
            $resp->id = $ap_id;
        }
    } catch(Exception $e) {
        $resp->status = "error";
        $resp->reason = $e;
    } finally {
        echo json_encode($resp, JSON_PRETTY_PRINT);
    }
} else {
    header("location:../404");
}

?>