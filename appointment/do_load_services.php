<?php
/**
 * Load all services in determined category from the DB
 */
require("logic.php");

if(isset($_GET['type'])) {
    $type = $_GET['type'];
    try {
        $statement = $conn->prepare("SELECT name, price FROM services WHERE type = :type");
        $statement->bindParam(":type", $type);
        $statement->execute();
        $services = $statement->fetchAll();

        $i = 0;
        foreach($services as $service) {
            echo '
                <tr>
                    <th scope="row"><input type="checkbox" aria-label="Select service" class="chkbox" id="check-'.$i.'" onClick="checkClick(\'check-'.$i.'\', '.$service["price"].', \''.$service["name"].'\')"></th>
                    <td>'.$service["name"].'</td>
                    <td>&euro; <span class="service-price">'.$service["price"].'</span></td>
                </tr>
            ';
            $i++;
        }

        echo '
            <tr style="background-color: rgb(10,10,10,0.05)">
                <th>Total</th>
                <td></td>
                <td><b>&euro;<span id="total-price">0</span></b></td>
            </tr>
        ';
    } catch(Exception $e) {
        echo "error";
    }
} else {
    header("location:../404");
}

?>