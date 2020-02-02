<?php
/**
 * Login logic component
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
if(isset($_POST['email']) && isset($_POST['pwd'])) {
    $email = $_POST['email'];
    $pwd = $_POST['pwd'];

    $conn = connect_db();

    // verify if the user exists in the db
    try {
        $statement = $conn->prepare("SELECT id, email, pwd FROM costumers WHERE email = :email");
        $statement->bindParam(':email', $email);
        $statement->execute();
        $row = $statement->fetch();

        if(password_verify($pwd, $row['pwd'])) {
            // if email and password correct, create new session
            // stores the user id and pwd hash
            $_SESSION['salon-session'] = base64_encode($row['id'] . ":" . $row['pwd']);
            $response->status = "success";
        } else {
            $response->status = "fail";
            $response->reason = "Email/password combination invalid or don't exist.";
        }
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