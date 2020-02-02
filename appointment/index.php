<?php
require('logic.php');

$user = get_user_info();
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

    <link href="../assets/fullcalendar/core/main.css" rel="stylesheet" />
    <link href="../assets/fullcalendar/daygrid/main.css" rel="stylesheet" />
    <link href="../assets/fullcalendar/timegrid/main.css" rel="stylesheet" />

    <script src="../assets/fullcalendar/core/main.js"></script>
    <script src="../assets/fullcalendar/daygrid/main.js"></script>
    <script src="../assets/fullcalendar/timegrid/main.js"></script>
    <script src="../assets/fullcalendar/interaction/main.js"></script>

    <title>Salon</title>
    <script>
    </script>
  </head>
  <body>

    <nav class="navbar navbar-expand-lg navbar-light nav-custom">
        <div class="container">
            <a class="navbar-brand" href="#">Beauty Style</a>
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

    <!-- step 1: select the date the user would like to book-->
    <div class="main-container container" id="step1">
      <h2>1. Select a date for your service</h2>
      <div id="calendar-obj"></div>
    </div>

    <!-- step 2: select which service the user would like-->
    <div class="main-container container" id="step2">
        <h2>2. Select the service type</h2>
        <div class="dropdown" id="service-type-selector">
            <button class="btn btn-primary dropdown-toggle" style="width: 100%;" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Service type
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="#">Hair</a>
                <a class="dropdown-item" href="#">Hair removal</a>
                <a class="dropdown-item" href="#">Nails</a>
                <a class="dropdown-item" href="#">Face</a>
            </div>
        </div>
        <br>
        <div class="other-parameters"> <!-- load dynamically by the selected option -->
            <h2>3. Select the desired services</h2>
            <table class="table my-3">
                <thead>
                    <tr>
                    <th scope="col"></th>
                    <th scope="col">Service name</th>
                    <th scope="col">Service price</th>
                    </tr>
                </thead>
                <tbody id="select-services-tbody">
                </tbody>
            </table>
            <br>
            <h2>3. Additional info</h2>
            <br>
            <textarea class="form-control" id="addInfoTA" placeholder="Add any extra informations such as any type of allergy, etc." rows="3"></textarea>
            <br>
            <button class="btn btn-primary right-buttons next-step">Next</button>
            <button class="btn btn-secondary text-center right-buttons" onClick="$('.navbar-brand').click()">Cancel</button>
            <br><br>
        </div>
    </div>

    <!-- step 3: appointment successful -->
    <div class="main-container container" id="step3">
      <div style="max-width: 200px;margin-left:auto;margin-right:auto;left:0;right:0;margin-bottom: 50px;">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 97 97"><defs><style>.a,.c{fill:#fff;}.b{fill:#ff935c;}.c{stroke:#ff935c;stroke-miterlimit:10;stroke-width:3px;}</style></defs><title>check</title><circle class="a" cx="48.5" cy="48.5" r="47"/><path class="b" d="M49.5,5A45.5,45.5,0,1,1,4,50.5,45.55,45.55,0,0,1,49.5,5m0-3A48.5,48.5,0,1,0,98,50.5,48.49,48.49,0,0,0,49.5,2Z" transform="translate(-1 -2)"/><polyline class="c" points="17.5 47.5 41.5 73.5 77.5 27.5"/></svg>
      </div>
      <h2>Done!</h2>
      <p style="font-size: 16pt;">
        You have <span id="r-ap-type"></span> appointment on <span id="r-ap-time"></span><br>
        Appointment code: #<span id="r-ap-id"></span><br>
        <br>
        Thank you!
      </p>
      <a href="../myappointments/" class="btn btn-primary">Go to my appointments</a>
    </div>

    <!-- error modal: if there is any error -->
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
            <button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button>
          </div>
        </div>
      </div>
    </div>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="../assets/js/jquery-3.4.1.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/appointment.js"></script>
  </body>
</html>