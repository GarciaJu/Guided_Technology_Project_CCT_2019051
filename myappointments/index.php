<?php
require('logic.php');
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

    <title>Salon</title>
    <script>
    </script>
  </head>
  <body>

    <nav class="navbar navbar-expand-lg navbar-light nav-custom">
        <div class="container">
            <a class="navbar-brand" href="../appointment/">Beauty Style</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
          
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav ml-auto">
                <div class="circle mt-2"></div>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo $user->name; ?>
                  </a>
                  <div class="dropdown-menu" aria-labelledby="navDropdown">
                    <a class="dropdown-item" href="../"><i class="fas fa-home mr-3"></i>Homepage</a>
                    <?php if($user->admin){ ?>
                    <a class="dropdown-item" href="../admin"><i class="fas fa-users-cog mr-3"></i>Administration</a>
                    <?php } ?>
                    <a class="dropdown-item" href="../myappointments/"><i class="fas fa-history mr-3"></i>My appointments</a>
                    <a class="dropdown-item" href="../logout.php"><i class="fas fa-sign-out-alt mr-3"></i>Logout</a>
                  </div>
                </li>
              </ul>
            </div>
        </div>
    </nav>

    <!-- main container -->
    <div class="main-container container">
        <h2>My appointments</h2>
        <table class="table table-striped my-3">
            <thead>
                <tr>
                <th scope="col">ID</th>
                <th scope="col">Service name</th>
                <th scope="col">Price</th>
                <th scope="col">Date</th>
                <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php get_my_appointments(); ?>
            </tbody>
        </table>
    </div>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="../assets/js/jquery-3.4.1.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/main.js"></script>
  </body>
</html>