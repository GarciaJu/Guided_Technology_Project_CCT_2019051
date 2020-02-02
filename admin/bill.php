<?php
/**
 * Shows the bill to the client
 */
require("logic.php");

if(isset($_GET["id"])) {
  $id = $_GET["id"];
  try {
    // get client data
    $statement = $conn->prepare("SELECT a.name AS costumer, a.phone, b.price
                                FROM costumers a, appointments b
                                WHERE b.id = :id
                                AND a.id = b.costumer");
    $statement->bindParam(":id", $id);
    $statement->execute();

    $order = $statement->fetch(PDO::FETCH_ASSOC);
    $finalPrice = intval($order["price"]);

    // get services
    $statement = $conn->prepare("SELECT a.service, b.price
                                FROM appointment_services a, services b
                                WHERE a.appointment = :id AND b.name = a.service");
    $statement->bindParam(":id", $id);
    $statement->execute();
    $services = $statement->fetchAll(PDO::FETCH_ASSOC);

    // get charges
    $statement = $conn->prepare("SELECT SUM(charge) AS charge
                                FROM appointment_charges
                                WHERE appointment = :id");
    $statement->bindParam(":id", $id);
    $statement->execute();
    $charges = $statement->fetch();
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Raleway:400,700&display=swap" rel="stylesheet">
    <link href="../assets/font-aweasome/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/main.css">

    <title>Salon — Bill</title>
    <style>
        .container {
            padding: 30px 20px 15px 20px;
            border: 2px solid #000;
        }
    </style>
  </head>
  <body>
     <!-- Invoice -->    
    <div class="container mt-5">
        <h2 class="text-center">Beauty Style - Bill</h2>
        <p style="font-size: 14pt;margin-top:50px">
        Order ID: <?php echo $id; ?><br>
        CUSTOMER: <?php echo $order["costumer"]; ?><br>
        Mob No: <?php echo $order["phone"]; ?><br>
        <table>
        <span style="font-size:14pt;">Services required:</span>
        <?php
          foreach($services as $service) {
            echo '<tr>
                    <td>'.$service["service"].'</td>
                    <td>&euro;'.$service["price"].'</td>
                  </tr>';
          }
          if(isset($charges) && $charges["charge"] != 0) {
            echo '<tr>
                    <td><b>Extra charge</b></td>
                    <td><b>&euro;'.$charges["charge"].'</b></td>
                  </tr>';
            $finalPrice += intval($charges["charge"]);
          }
        ?>
          <tr>
          <td><b>TOTAL DUE&nbsp;&nbsp;</b></td>
          <td><b>&euro;<?php echo $finalPrice; ?></b></td>
          </tr>
        </table>
        <br><br>
        <b>Payment due on collection.</b>
        </p>
    </div>
    
    <div class="text-center mt-5 d-print-none">
        <a href="#" class="btn btn-secondary" onclick="window.print()">Print</a>
    </div>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="../assets/js/jquery-3.4.1.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/adm.js"></script>
  </body>
</html>

<?php
  } catch(Exception $e) {
    echo "An error has occurred: ".$e;
  }
} else {
  echo "Please provide an ID.";
}
?>