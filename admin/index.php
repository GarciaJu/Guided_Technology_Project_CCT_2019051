<?php
require("logic.php");
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

    <title>Salon Management</title>
    <script>
    </script>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-light nav-custom">
        <div class="container">
            <a class="navbar-brand" href="#">Beauty Style Management</a>
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
                    <a class="dropdown-item" href="../logout.php"><i class="fas fa-sign-out-alt mr-3"></i>Logout</a>
                  </div>
                </li>
              </ul>
            </div>
        </div>
    </nav>

    <!-- main container -->
    <div class="main-container container" id="firstpage">
        <h2>Select an action below</h2>
        <div class="my-3 btn-group-vertical">
          <a href="#" class="btn btn-secondary" id="search-by-id-adm"><i class="fas fa-search mr-3"></i>Search for an appointment by ID <span style="float:right"><i class="fas fa-chevron-right"></i></span></a>
          <a href="#" class="btn btn-secondary" id="calendar-adm"><i class="far fa-calendar-alt mr-3"></i>View all appointments in the calendar <span style="float:right"><i class="fas fa-chevron-right"></i></span></a>
          <a href="#" class="btn btn-secondary" id="management-adm"><i class="fas fa-cogs mr-3"></i>Service management <span style="float:right"><i class="fas fa-chevron-right"></i></span></a>
        </div>
    </div>

    <!-- search by id -->
    <div class="main-container container" id="sbid">
      <h2>1. Enter the appointment ID</h2>
      <div class="input-group my-3" id="id-ig">
        <div class="input-group-prepend">
          <span class="input-group-text" id="hashtag">#</span>
        </div>
        <input type="number" class="form-control" placeholder="appointment ID" id="sbid-id" aria-label="Appointment ID" aria-describedby="hashtag">
        <div class="input-group-append">
          <a href="#" class="btn btn-outline-secondary" id="sbid-search-btn">Search</a>
        </div>
      </div>

      <div id="sbid-results" class="mt-5">
        <div class="row">
          <div class="col-md-6">
            <h3>Client profile:</h3>
            <p>
              Name: <span id="sbid-client-name"></span><br>
              Phone: <span id="sbid-client-phone"></span><br>
              Email: <span id="sbid-client-email"></span>
            </p>
            <h3>Services:</h3>
            <ul id="sbid-services">
            </ul>
            <h3>Additional info:</h3>
            <p id="sbid-add-info">No additional information.</p>
            <h3>Responsible staff:</h3>
            <p id="sbid-staff"></p>
            <h3>Total price:</h3>
            <p>&euro; <span id="sbid-total-price"></span></p>
          </div>
          <div class="col-md-6">
            <h3>Status: <i id="sbid-status"></i></h3>
            <div class="btn-group-vertical">
              <a href="#" class="btn btn-secondary" style="font-size: 12pt;" id="sbid-in-service" onClick="setStatus('In Service')">Change to in service</a>
              <a href="#" class="btn btn-secondary" style="font-size: 12pt;" id="sbid-completed" onClick="setStatus('Completed')">Change to completed</a>
              <a href="#" class="btn btn-secondary" style="font-size: 12pt;" id="sbid-unfinished" onClick="setStatus('Unfinished')">Change to unfinished</a>
            </div>
            <h3 class="mt-3">Extra charges:</h3>
            <ul id="sbid-charges">
            </ul>
          </div>
        </div>
        
        <div class="row mt-5">
          <div class="col-md-3 mt-2">
            <a href="#" class="btn btn-secondary back-home-adm" style="width: 100%;text-align: center;">Go back to main page</a>
          </div>
          <div class="col-md-3 mt-2">
            <a href="#" class="btn btn-primary" style="width: 100%;" onClick="$('#modalCharge').modal('show');">Add extra charge</a>
          </div>
          <div class="col-md-3 mt-2">
            <a href="#" class="btn btn-primary" style="width: 100%;" onClick="loadESModal();">Add extra service</a>
          </div>
          <div class="col-md-3 mt-2">
            <a href="bill.php" class="btn btn-primary" style="width: 100%;" target="_blank" id="print-bill-btn">Print bill</a>
          </div>
        </div>
      </div>
    </div>

    <!-- calendar -->
    <div class="main-container container" id="calendar">
      <h2>Select a date to view/edit schedule</h2>
      <div id="calendar-obj" class="my-3"></div>
      <button class="btn btn-secondary back-home-adm mb-4" style="width: 100%; max-width: 200px; float:right;text-align: center;">Back</button>
    </div>

    <!-- date management -->
    <div class="main-container container" id="date-mgmt">
      <h2 id="appointment-list-title">Tuesday, December 31th 2019</h2>
      <table class="table table-striped my-3">
          <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Time</th>
                <th scope="col">Services</th>
                <th scope="col">Staff</th>
                <th scope="col">Client</th>
                <th scope="col">Status</th>
                <th scope="col">Unmark</th>
              </tr>
          </thead>
          <tbody id="tbody-appointment-list">
          </tbody>
      </table>
      <button class="btn btn-secondary mb-4" style="width: 100%; max-width: 200px; float:right;text-align: center;" id="back-calendar-details">Back</button>
    </div>

    <!-- service mgmt -->
    <div class="main-container container" id="smgmt">
      <h2>Add, edit, remove services</h2>
      <table class="table table-striped my-3">
          <thead>
              <tr>
                <th scope="col">Service name</th>
                <th scope="col">Category</th>
                <th scope="col">Price</th>
                <th scope="col">Required time</th>
                <th scope="col">Edit</th>
                <th scope="col">Delete</th>
              </tr>
          </thead>
          <tbody id="all-services-tbody">
          </tbody>
      </table>
      <button class="btn btn-primary ml-1" style="width: 100%;max-width: 200px;float:right;" id="add-new-service-btn">Add new service</button>
      <button class="btn btn-secondary back-home-adm" style="width: 100%; max-width: 200px; float:right;text-align: center;">Back</button>
    </div>

    <!-- new service -->
    <div class="main-container container" id="nservice">
      <h2>New service</h2>
      <form style="max-width: 600px;" id="new-service-form">
          <div class="form-group mt-5">
            <label for="new-sname">Service name</label>
            <input type="text" name="sname" id="new-sname" class="form-control" placeholder="Service name">
          </div>
          <div class="form-group">
            <label for="">Service category</label>
            <div class="dropdown mt-0 service-type-selector">
                <button class="btn btn-secondary dropdown-toggle" style="text-align:center; width: 100%; padding-top:5px; padding-bottom: 5px;" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Service type
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item">Hair</a>
                    <a class="dropdown-item">Hair removal</a>
                    <a class="dropdown-item">Nails</a>
                    <a class="dropdown-item">Face</a>
                </div>
            </div>
          </div>
          <div class="form-group">
            <label for="new-sprice">Service price</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" id="euro_sign">&euro;</span>
              </div>
              <input type="text" name="sprice" id="new-sprice" class="form-control" placeholder="Service price, in EUR">
            </div>
          </div>
          <div class="form-group">
            <label for="new-sreqtime">Estimated required time (hours)</label>
            <input type="text" name="sreqtime" id="new-sreqtime" class="form-control" placeholder="Required time, in hours">
          </div>

        </form>
        <button class="btn btn-primary mb-3 ml-1" type="button" style="float:right; width:100%; max-width: 200px;" id="new-service-save-btn">Save</button>
        <button class="btn btn-secondary mb-3 service-back-btn" type="button" style="float:right; width:100%; max-width: 200px; text-align: center;">Back</button>
    </div>

    <!-- edit service -->
    <div class="main-container container" id="editservice">
      <h2>Edit service</h2>
      <form style="max-width: 600px;" id="edit-service-form">
          <div class="form-group mt-5">
            <label for="sname">Service name</label>
            <input type="text" name="sname" id="sname" class="form-control" placeholder="Service name">
          </div>
          <div class="form-group">
            <label for="">Service category</label>
            <div class="dropdown mt-0 service-type-selector">
                <button class="btn btn-secondary dropdown-toggle" style="text-align:center; width: 100%; padding-top:5px; padding-bottom: 5px;" type="button" id="dropdownMenuButtonEdit" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Service type
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item">Hair</a>
                    <a class="dropdown-item">Hair removal</a>
                    <a class="dropdown-item">Nails</a>
                    <a class="dropdown-item">Face</a>
                </div>
            </div>
          </div>
          <div class="form-group">
            <label for="sprice">Service price</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" id="euro_sign">&euro;</span>
              </div>
              <input type="text" name="sprice" id="sprice" class="form-control" placeholder="Service price, in EUR">
            </div>
          </div>
          <div class="form-group">
            <label for="sreqtime">Estimated required time (hours)</label>
            <input type="text" name="sreqtime" id="sreqtime" class="form-control" placeholder="Required time, in hours">
          </div>

        </form>
        <button class="btn btn-primary mb-3 ml-1" type="button" style="float:right; width:100%; max-width: 200px;" id="edit-service-save-btn">Save</button>
        <button class="btn btn-secondary mb-3 service-back-btn" type="button" style="float:right; width:100%; max-width: 200px; text-align: center;">Back</button>
    </div>

    <!-- sure to delete modal -->
    <div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="modalDeleteTitle" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalDeleteTitle">Are you sure you want to delete?</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            Are you sure you want to delete the &quot;<span id="service-name-delete-modal"></span>&quot; service?<br>
            This action cannot be undone.
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="delete-service-btn-cancel">Cancel</button>
            <button type="button" class="btn btn-primary" id="delete-service-btn">Delete</button>
          </div>
        </div>
      </div>
    </div>

    <!-- sure to delete appointment modal -->
    <div class="modal fade" id="modalDeleteAP" tabindex="-1" role="dialog" aria-labelledby="modalDeleteAPTitle" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalDeleteAPTitle">Are you sure you want to delete?</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            Are you sure you want to delete the appointment #<span id="appointment-name-delete-modal"></span>?<br>
            This action cannot be undone.
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="delete-appointment-btn-cancel">Cancel</button>
            <button type="button" class="btn btn-primary" id="delete-appointment-btn">Delete</button>
          </div>
        </div>
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

    <!-- extra charge modal -->
    <div class="modal fade" id="modalCharge" tabindex="-1" role="dialog" aria-labelledby="modalChargeTitle" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalChargeTitle">Add extra charge</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <form id="form-charge">
              <div class="form-group">
                <label for="new-charge-reason">Charge reason</label>
                <textarea id="new-charge-reason" class="form-control" placeholder="Describe the reason for the extra charge"></textarea>
              </div>
              <div class="form-group">
                <label for="new-charge-price">Charge price</label><br>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="eur">&euro;</span>
                  </div>
                  <input type="number" class="form-control" placeholder="Charge, in EUR" id="new-charge-price" aria-label="Charge" aria-describedby="eur">
                </div>
              </div>
            </form>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="save-charge-btn">Save</button>
          </div>
        </div>
      </div>
    </div>

    <!-- extra service modal -->
    <div class="modal fade" id="modalES" tabindex="-1" role="dialog" aria-labelledby="modalESTitle" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalESTitle">Add extra service</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <table class="table table-striped my-2">
              <thead>
                  <tr>
                    <th scope="col">Add</th>
                    <th scope="col">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Time (hours)</th>
                  </tr>
              </thead>
              <tbody id="es-body">
              </tbody>
            </table>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="save-service-btn">Save</button>
          </div>
        </div>
      </div>
    </div>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="../assets/js/jquery-3.4.1.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/adm.js"></script>
  </body>
</html>