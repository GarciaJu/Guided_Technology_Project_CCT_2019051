<?php
/**
 * Load the rows for the selected day
 */
require("logic.php");

if(isset($_GET["day"])) {
    try {
        $day = new DateTime($_GET["day"]);
    
        // get the appointments of the day
        $statement = $conn->prepare("SELECT a.id, a.staff, a.time, b.name AS client, a.status
                                     FROM appointments a, costumers b
                                     WHERE a.costumer = b.id
                                     AND a.date = :date");
    
        $fmt_date = $day->format("Y-m-d");
        $statement->bindParam(":date", $fmt_date);
        $statement->execute();
    
        $rows = $statement->fetchAll();
    
        $i = 0;
        foreach($rows as $row) {
            // load the services for the appointment
            $statement = $conn->prepare("SELECT service FROM appointment_services WHERE appointment = :id");
            $statement->bindParam(":id", $row["id"]);
            $statement->execute();
            $rows2 = $statement->fetchAll();

            $str = "";
            foreach($rows2 as $subrow) {
                $str .= $subrow["service"].", ";
            }
            echo '
                <tr>
                    <td onClick="triggerSearch('.$row["id"].')" style="cursor:pointer">#'.$row["id"].'</td>
                    <td onClick="triggerSearch('.$row["id"].')" style="cursor:pointer">'.$row["time"].'</td>
                    <td onClick="triggerSearch('.$row["id"].')" style="cursor:pointer">'.substr($str,0,-2).'</td>
                    <td onClick="triggerSearch('.$row["id"].')" style="cursor:pointer">Staff '.$row["staff"].'</td>
                    <td onClick="triggerSearch('.$row["id"].')" style="cursor:pointer">'.$row["client"].'</td>
                    <td onClick="triggerSearch('.$row["id"].')" style="cursor:pointer">'.$row["status"].'</td>
                    <td><span class="sr-only">Delete</span><a href="#"><i class="fas fa-trash-alt fa-1x" onClick="deleteAppointment('.$row["id"].')"></i></a></td>
                </tr>';

            $i++;
        }

        // if there's no result, fill the table with a "no appointments" row
        if($i == 0) {
            echo '  <tr>
                        <td colspan="8" class="text-center">No appointments.</td>
                    </tr>';
        }
    } catch(Exception $e) {
        echo "error";
    }
}

?>