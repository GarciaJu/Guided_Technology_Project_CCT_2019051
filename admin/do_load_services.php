<?php
/**
 * Load all services from the DB
 */
require("logic.php");

try {
    $statement = $conn->prepare("SELECT * FROM services");
    $statement->execute();
    $services = $statement->fetchAll();

    // flag
    $i = 0;
    foreach($services as $service) {
        echo '
            <tr>
                <td>'.$service["name"].'</td>
                <td>'.$service["type"].'</td>
                <td>&euro; '.$service["price"].'</td>
                <td>'.$service["time"].' hour</td>
                <td><span class="sr-only">Edit</span><a href="#" onClick="editClick(\''.$service["name"].'\')"><i class="fas fa-edit fa-1x"></i></a></td>
                <td><span class="sr-only">Delete</span><a href="#" onClick="deleteClick(\''.$service["name"].'\')"><i class="fas fa-trash-alt fa-1x"></i></a></td>
            </tr>
        ';
        $i = 1;
    }

    if($i != 1) {
        echo '
            <tr>
                <td colspan="6" class="text-center">No services available.</td>
            </tr>
        ';
    }
} catch(Exception $e) {
    echo "error";
}

?>