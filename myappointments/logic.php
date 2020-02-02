<?php
/**
 * Profile logic component
 */
session_start();
// if user is not logged in, should redirect to login page
if(!isset($_SESSION['salon-session'])) {
    header('location:../login');
    return;    
}
require('../db/conn.php');

$conn = connect_db();
$user = get_user_info();

function get_user_info() {
    $sess_dec = explode(":", base64_decode($_SESSION['salon-session']));
    $id = $sess_dec[0];
    $pwd = $sess_dec[1];

    global $conn;

    $statement = $conn->prepare("SELECT `name`, `admin` FROM costumers WHERE id = :id AND pwd = :pwd");
    $statement->bindParam(":id", $id);
    $statement->bindParam(":pwd", $pwd);
    $statement->execute();
    $user = (object)[];

    $row = $statement->fetch();
    $user->id = $id;
    $user->name = $row["name"];
    $user->admin = $row["admin"];

    return $user;
}

// searchs in the DB all the appointments the user have made
function get_my_appointments() {
    global $conn;
    global $user;

    $statement = $conn->prepare("SELECT a.id, a.costumer, a.date, a.time, a.status, a.price AS totalPrice,
                                        b.appointment, b.service, 
                                        c.price
                                FROM appointments a, appointment_services b, services c
                                WHERE a.costumer = ? AND b.appointment = a.id AND c.name = b.service
                                ORDER BY a.id DESC");
    $statement->execute([$user->id]);

    $rows = $statement->fetchAll();

    $appointments = [];

    foreach($rows as $row) {
        $actual = intval($row["id"]);

        $appointments[$actual] = [
            "id" => $row["id"],
            "costumer" => $row["costumer"],
            "date" => $row["date"],
            "time" => $row["time"],
            "status" => $row["status"],
            "service" => $row["service"] . ", " . (isset($appointments[$actual]["service"]) ? $appointments[$actual]["service"] : ""),
            "price" => intval($row["price"]) + (isset($appointments[$actual]["price"]) ? intval($appointments[$actual]["price"]) : 0),
            "totalPrice" => intval($row["totalPrice"])
        ];
    }
    
    foreach($appointments as $appointment) {
        echo '
        <tr>
            <th scope="row">#'.$appointment["id"].'</th>
            <td>'.substr($appointment["service"],0,-2).'</td>
            <td>&euro; '. ($appointment["status"] == "Booked" ? $appointment["price"] : $appointment["totalPrice"]).'</td>
            <td>'.$appointment["date"].' - '.$appointment["time"].'</td>
            <td>'.$appointment["status"].'</td>
        </tr>
        ';
    }
}

?>