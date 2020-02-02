<?php
// if user is already logged-in, should only return to main page
session_start();
if(isset($_SESSION['salon-session'])) {
    header("location:../appointment");
    return;    
}
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
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/login.css">

    <title>Salon — Register</title>
    <script>
    </script>
  </head>
  <body>

    <!-- main container -->
    <div class="main-container container">
        <div class="smaller-container">
          <h2>Register</h2>
          <form class="mt-5" id="register-form" method="POST">
            <p style="margin-bottom: 20px;color: rgb(100, 100, 100);">We'll never share your information with anyone else.</p>
            <div class="form-group">  
              <label for="name-input">Name</label>
              <input type="text" class="form-control" id="name-input" name="name" placeholder="Full name" required autofocus>
            </div>
            <div class="form-group">
              <label for="email-input">E-mail</label>
              <input type="email" class="form-control" id="email-input" name="email" placeholder="E-mail address" required>
            </div>
            <div class="form-group">
              <label for="tel-input">Phone number</label>
              <input type="tel" class="form-control" id="tel-input" name="phone" placeholder="Phone number" required>
            </div>
            <div class="form-group">
              <label for="pwd-input">Password</label>
              <input type="password" class="form-control" id="pwd-input" name="pwd" placeholder="Password" required aria-describedby="pwdHelp">
              <small id="pwdHelp" class="form-text text-muted">Passwords must contain at least 8 characters</small>
            </div>
            <div class="form-group">
              <label for="pwd2-input">Repeat password</label>
              <input type="password" class="form-control" id="pwd2-input" name="pwd_confirm" placeholder="Repeat password" required aria-describedby="pwd2Help">
              <small id="pwd2Help" class="form-text text-muted">Retype your password to confirm it's allright</small>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
          <button type="button" class="btn btn-secondary text-center" onclick="window.history.back(1)">Back</button>
        </div>
    </div>

    <!-- error modal -->
    <div class="modal fade" id="modalError" tabindex="-1" role="dialog" aria-labelledby="modalErrorTitle" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalErrorTitle"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body" id="modalErrorBody">
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="error-ok-button">OK</button>
          </div>
        </div>
      </div>
    </div>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="../assets/js/jquery-3.4.1.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/login.js"></script>
  </body>
</html>