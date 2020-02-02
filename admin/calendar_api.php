<?php
/**
 * This file returns a JSON array object compatible with FullCalendar,
 * in order to show the calendar fot the week
 */
require("logic.php");
header("Content-Type: application/json");

$calendar = [];

// creates and adds the sundays exception
$sundays = json_decode('{
    "title": "Closed",
    "startTime": "10:00",
    "endTime": "20:00",
    "daysOfWeek": [
        0,1
    ],
    "color": "#ccc"
}');
array_push($calendar, $sundays);

// get the appointments from the database
$statement = $conn->prepare("SELECT b.name, a.date, a.time
                             FROM appointments a, costumers b
                             WHERE a.date >= CURRENT_DATE()
                             AND a.costumer = b.id");

$statement->execute();
$rows = $statement->fetchAll();

// if any appointment, add it to the calendar
foreach($rows as $row) {
    $endt = new DateTime($row["date"].'T'.$row["time"]);
    $endt->modify("+1 hour");

    $endtime = $endt->format("H:i");
    $event = '{
        "title": "'.$row["name"].'",
        "start": "'.$row["date"].'T'.$row["time"].'",
        "end": "'.$row["date"].'T'.$endtime.'"
    }
    ';

    array_push($calendar, json_decode($event));
}

echo json_encode($calendar, JSON_PRETTY_PRINT);

?>