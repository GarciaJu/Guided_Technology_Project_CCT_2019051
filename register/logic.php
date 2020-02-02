<?php
/**
 * Register logic component
 */
$response = (object)[]; // empty response object

session_start();
// if user is already logged-in, should only return to main page
if(isset($_SESSION['salon-session'])) {
    $response->status = "success";
    echo json_encode($response);
    return;    
}

require('../db/conn.php');


// if all required fields are send by post method, verify them
if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['pwd']) && isset($_POST['pwd_confirm'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $pwd = $_POST['pwd'];
    $pwd_confirm = $_POST['pwd_confirm'];

    $conn = connect_db();

    if($pwd != $pwd_confirm) {
        $response->status = "fail";
        $response->reason = "Passwords do not match";
        echo json_encode($response);
        return;
    }

    // encodes the password
    $pwd = password_hash($pwd, PASSWORD_BCRYPT);

    // adds the user in db and returns the status
    try {
        $statement = $conn->prepare("INSERT INTO costumers (name, pwd, email,phone) VALUES (:name, :pwd, :email, :phone)");
        $statement->bindParam(":name", $name);
        $statement->bindParam(":pwd", $pwd);
        $statement->bindParam(":email", $email);
        $statement->bindParam(":phone", $phone);
        $statement->execute();

        // create new session (user should be logged in after registration)
        // stores the user id and pwd hash
        $_SESSION['salon-session'] = base64_encode($conn->lastInsertId() . ":" . $pwd);

        $response->status = "success";
    } catch(Exception $e) {
        $response->status = "fail";
        $response->reason = $e;
    } finally {
        echo json_encode($response);
    }
} else {
    // if don't receive the parameters, user should not be able to see this page (go to 404 error page)
    header("location:../404");
}

?>