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

    <title>Salon — Login</title>
    <script>
    </script>
  </head>
  <body>

    <!-- main container -->
    <div class="main-container container">
        <div class="smaller-container">
          <h2>Login</h2>
		  <p>
			In order to acess our services, you must login.<br>
			Don't have an account yet? Create one with the button below!
		  </p>
          <form class="mt-5" id="login-form" method="POST">
              <div class="form-group">  
                <label for="email-input">E-mail</label>
                <input type="email" class="form-control" id="email-input" name="email" placeholder="E-mail address" required autofocus>
              </div>
              <div class="form-group">
                <label for="pwd-input">Password</label>
                <input type="password" class="form-control" id="pwd-input" name="pwd" placeholder="Password" required>
              </div>
            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
          <button type="button" onClick="window.location='../register'" class="btn btn-secondary text-center">Create an account</button>
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