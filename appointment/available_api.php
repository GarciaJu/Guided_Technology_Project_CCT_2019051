<?php
/**
 * This file returns a JSON array object compatible with FullCalendar,
 * in order to show all available spots
 * */
// Model: 
// {
//     title: "Closed",
//     startTime: "10:00",
//     endTime: "20:00",
//     daysOfWeek: [
//         0,1
//     ],
//     color: "#ccc"
// },
// {
//     title: "Full",
//     start: "2019-12-31T14:00",
//     end: "2019-12-31T16:00",
//     color: "#da2d2d"
// },
// {
//     title: "Available",
//     startTime: "10:00",
//     endTime: "11:00",
//     daysOfWeek: [
//         2,3,4,5,6
//     ]
// },
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

// load the events from the database
$statement = $conn->prepare("SELECT `date`, `time`, `staff` FROM appointments WHERE `date` >= CURRENT_DATE()");
$statement->execute();

$rows = $statement->fetchAll();

// stores all unavailable hours from today to next week
$unavailableHours = [];
$unavailableDates = [];

// counter
$hourCounter = [];
$dateCounter = [];
// if there are 5 bookings at the same time => time unavailable
// if there are 20 bookings at the same day => whole day unavailable
foreach($rows as $row) {
    $dateTime = $row["date"]." ".$row["time"];
    $date = $row["date"];

    isset($hourCounter[$dateTime]) ? $hourCounter[$dateTime]++ : $hourCounter[$dateTime] = 1;
    isset($dateCounter[$date]) ? $dateCounter[$date]++ : $dateCounter[$date] = 1;

    if($hourCounter[$dateTime] >= 5) {
        $unavailableHours[$dateTime] = 1;
    }
    if($dateCounter[$date] >= 20) {
        $unavailableDates[$date] = 1;
    }
}

$now = new DateTime();
$now->setTime(10,0);

// maximum results: 7 days from now (except weekends)
for($i = 0; $i < 70; $i++) {
    $formatedNow = $now->format("Y-m-d");
    $hformatedNow = $now->format("Y-m-d H:i:s");
    $dayOfWeek = $now->format("l");

    if(isset($unavailableDates[$formatedNow])) {
        // eliminate the whole day

        $times = new DateTime("10:00");
        $times2 = new DateTime("11:00");

        for($j = 0; $j < 10; $j++) {
            $str = '{
                "title": "Unavailable",
                "start": "'.$formatedNow.'T'.$times->format("H:i").'",
                "end": "'.$formatedNow.'T'.$times2->format("H:i").'",
                "color": "#da2d2d"
            }
            ';

            $str = substr($str,0,-1);

            array_push($calendar, json_decode($str));

            $times->modify("+1 hour");
            $times2->modify("+1 hour");
        }
        $now->modify("+1 day");
        $now->setTime(10,0);
    } else {
        if(isset($unavailableHours[$hformatedNow])) {
            $times = $now->modify("+1 hour");
            $times = $times->format("Y-m-d H:i");
            $str = '{
                "title": "Unavailable",
                "start": "'.str_replace(" ", "T", $hformatedNow).'",
                "end": "'.str_replace(" ", "T", $times).'",
                "color": "#da2d2d"
            }
            ';

            array_push($calendar, json_decode($str));
        } else {
            if($dayOfWeek == "Sunday") {
                $times = $now->modify("+2 days");
                continue;
            } else if($dayOfWeek == "Monday") {
                $times = $now->modify("+1 day");
                continue;
            }
            $times = $now->modify("+1 hour");
            $times = $times->format("Y-m-d H:i");
            $str = '{
                "title": "Available",
                "start": "'.str_replace(" ", "T", $hformatedNow).'",
                "end": "'.str_replace(" ", "T", $times).'"
            }
            ';

            array_push($calendar, json_decode($str));
        }

        if($now->format("H:i") == "20:00") {
            if($dayOfWeek == "Saturday") {
                $now->modify("+3 days");
            } else {
                $now->modify("+1 day");
            }
            $now->setTime(10,0);
        }
    }
}

echo json_encode($calendar, JSON_PRETTY_PRINT);

?>