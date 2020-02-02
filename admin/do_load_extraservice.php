<?php
/**
 * Load the available extra services to determined appointment
 */
require("logic.php");

if(isset($_GET["id"])) {
    try {
        $id = $_GET["id"];
        // searchs for the appointment type (here, all the extra services will be of the same type)
        $statement = $conn->prepare("SELECT b.type 
                                     FROM appointment_services a, services b
                                     WHERE a.appointment = :id AND b.name = a.service");
        $statement->bindParam(":id", $id);
        $statement->execute();
        $type = $statement->fetch()["type"];
        
        // then, get from the database only the services that are  
        // the same type AND aren't already in the appointment
        
        $statement = $conn->prepare("SELECT * FROM services
                                     WHERE `name` NOT IN (SELECT `service` FROM appointment_services WHERE appointment = :id)
                                     AND type = :type");
        $statement->bindParam(":id", $id);
        $statement->bindParam(":type", $type);
        $statement->execute();
        $services = $statement->fetchAll();

        // flag
        $i = 0;
        foreach($services as $service) {
            echo '
                <tr>
                    <td><input type="checkbox" aria-label="Select service" class="chkbox" id="check-'.$i.'" onClick="checkESClick(\'check-'.$i.'\', \''.$service["name"].'\')"></td>
                    <td>'.$service["name"].'</td>
                    <td>&euro; '.$service["price"].'</td>
                    <td>'.$service["time"].' hour</td>
                </tr>
            ';
            $i = 1;
        }

        if($i != 1) {
            echo '
                <tr>
                    <td colspan="4" class="text-center">No extra services available.</td>
                </tr>
            ';
        }
    } catch(Exception $e) {
        echo "error ".$e;
    } 
}

?>