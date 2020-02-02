/********************************************************************
 * 
 * Administration Functions
 * 
 * Navigation/Page State
 * 
 ********************************************************************/

// variables to simplify identification of each page
var pageStates = {
    HOME: 1,
    SEARCH_BY_ID: 2,
    MANAGEMENT: 3,
    EDIT_SERVICE: 4,
    NEW_SERVICE: 5,
    CALENDAR: 6,
}

// page state
var state = {
    calendarLoaded: false,
    selectedServiceType: "none",
    toEdit: "none",
    toDelete: "none",
    toDeleteAP: 0,
    sbID: 0,
    selectedES: [],
    selectedDate: new Date()
}

// shows error popup
function throwError(error_title, error_body) {
    $("#modalErrorTitle").html(error_title);
    $("#modalErrorBody").html(error_body);
    $("#modalError").modal('show');
}

// focus the "ok" button
$("#modalError").on("shown.bs.modal", function() {
    $("#error-ok-button").trigger("focus");
});

// change the subpages in the main "adm page"
function setPage(page_id) {
    if(page_id == pageStates.HOME) { // in the home, all pages must be invisible, except the first
        if($("#smgmt").css("display") != "none")
            changePage("#smgmt", "#firstpage");
        else if($("#nservice").css("display") != "none")
            changePage("#nservice", "#firstpage");
        else if($("#editservice").css("display") != "none")
            changePage("#editservice", "#firstpage")
        else if($("#sbid").css("display") != "none")
            changePage("#sbid", "#firstpage");
        else if($("#calendar").css("display") != "none")
            changePage("#calendar", "#firstpage");
        else if($("#date-mgmt").css("display") != "none")
            changePage("#date-mgmt", "#firstpage");

        $("input").each(function() {
            $(this).val("");
        });

        $("#sbid-results").hide();
    } else if(page_id == pageStates.SEARCH_BY_ID) {
        if($("#firstpage").css("display") != "none")
            changePage("#firstpage", "#sbid");
        else {
            changePage("#date-mgmt", "#sbid");
        }
    } else if(page_id == pageStates.MANAGEMENT) {
        // retrieves data from the DB, and then shows the page
        reloadServiceList();
        // change pages
        if($("#firstpage").css("display") != "none") { // first page showing up
            changePage("#firstpage", "#smgmt");    
        } else {
            if($("#nservice").css("display") != "none") { // new service showing up
                changePage("#nservice", "#smgmt");
            } else if($("#editservice").css("display") != "none") { // edit service showing up
                changePage("#editservice", "#smgmt");
            }
            $(".service-type-selector .dropdown-toggle").html("Service type");
        }
    } else if(page_id == pageStates.NEW_SERVICE) {
        changePage("#smgmt", "#nservice");
        setTimeout(function() {
            $("#new-sname").trigger("focus")
        }, 500);
    } else if(page_id == pageStates.EDIT_SERVICE) {
        loadService();
        changePage("#smgmt", "#editservice");
        setTimeout(function() {
            $("#sname").trigger("focus")
        }, 500);
    } else if(page_id == pageStates.CALENDAR) {
        if($("#date-mgmt").css("display") != "none") { // details showing up
            $("#date-mgmt").fadeOut("fast", function() {
                $("#calendar").fadeIn("fast", function() {
                    if(!state.calendarLoaded) {
                        fcalendar.render();
                        state.calendarLoaded = true;
                    }
                });
            });

            //changePage("#date-mgmt", "#calendar");
        } else {
            $("#firstpage").fadeOut("fast", function() {
                $("#calendar").fadeIn("fast", function() {
                    if(!state.calendarLoaded) {
                        fcalendar.render();
                        state.calendarLoaded = true;
                    }
                });
            })
        }
    }
}

// handle the button clicks
$("#search-by-id-adm").click(function() {
    setPage(pageStates.SEARCH_BY_ID);
    setTimeout(function() {
        $("#sbid-id").trigger("focus");
    }, 500);
});

$("#management-adm, .service-back-btn").click(function() {
    setPage(pageStates.MANAGEMENT);
});

$("#add-new-service-btn").click(function() {
    setPage(pageStates.NEW_SERVICE);
});

$("#calendar-adm, #back-calendar-details").click(function() {
    setPage(pageStates.CALENDAR);
});

$(".navbar-brand, .back-home-adm").click(function() {
    setPage(pageStates.HOME);
});

/********************************************************************
 * 
 * Calendar relative functions
 * 
 ********************************************************************/
var calendar_settings = {
    plugins: [ 'dayGrid', 'timeGrid', 'interaction' ],
    defaultView: 'timeGridWeek',
    header: {
        left: 'dayGridMonth timeGridWeek',
        center: 'title'
    },
    allDaySlot: false,
    minTime: "10:00",
    maxTime: "20:00",
    height: "auto",
    slotDuration: "1:00",
    dateClick: (info) => calendarClick(info.date),
    eventClick: (info) => calendarClick(info.event.start),
    events: {
        url: 'calendar_api.php'
    },
    eventColor: "#ffbb99",
    eventTextColor: "#000"
}

var fcalendar;

$(document).ready(function() {
    fcalendar = new FullCalendar.Calendar(document.getElementById("calendar-obj"), calendar_settings);
});

// handle the clicks in the items from the calendar
function calendarClick(date) {
    if(date.getDay() > 1){
        state.selectedDate = date;
        loadDayData();
    }
}

// load the appointments in the specified day
function loadDayData() {
    $.ajax({
        type: "GET",
        url: "do_load_day.php?day=" + state.selectedDate.toJSON(),
        success: function(e) {
            if(e != "error"){
                $("#appointment-list-title").html(formatDate(state.selectedDate));
                $("#tbody-appointment-list").html(e);
                changePage("#calendar", "#date-mgmt");
            } else
                throwError("An error has occurred", "Error loading data for the day. Please try again.");
        }
    });
}

// get the appointment list title: DayofWeek, Day Month Year
function formatDate(date) {
    var month = ["January","February","March","April","May",
                 "June","July","August","September","October",
                 "November","December"];
    var week = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        
    return week[date.getDay()] + ", " + date.getDate() + " " + month[date.getMonth()] + " " + date.getFullYear(); 
}

// search when the user clicks on the appointment list (in the day)
function triggerSearch(id) {
    setPage(pageStates.SEARCH_BY_ID);
    $("#sbid-id").val(id);
    searchByID();
}

// handle the click in the delete appointment button
function deleteAppointment(id) {
    state.toDeleteAP = parseInt(id);
    $("#appointment-name-delete-modal").html(id);
    $("#modalDeleteAP").modal("show");
}

// handle the click in the "sure to delete" button
$("#delete-appointment-btn").click(function() {
    $("#modalDeleteAP").modal("hide");
    $.ajax({
        type: "POST",
        url: "do_delete_appointment.php",
        data: { "id": state.toDeleteAP },
        success: function(e) {
            if(e.status == "error") {
                throwError("An error has occurred", "Please contact the administrator.<br>" + e.data);
            } else {
                // resets the page
                loadDayData();
                // resets the calendar
                fcalendar.destroy();
                fcalendar = new FullCalendar.Calendar(document.getElementById("calendar-obj"), calendar_settings);
                state.calendarLoaded = false;
            }
        },
        error: function(e) {
            throwError("An error has occurred", "Please contact the administrator.<br>" + e);
        }
    })
});

/********************************************************************
 * 
 * Search by ID relative functions
 * 
 ********************************************************************/

// button clicks when id is entered
$("#sbid-search-btn").click(function() {
    searchByID();
});

// also search when press enter
$("#sbid-id").keypress(function(e) {
    if(e.which == 13)
        searchByID();
});

// change which buttons are shown
function statusButtonsToggler(status) {
    if(status == "In Service") {
        $("#sbid-in-service").hide();
        $("#sbid-unfinished").show();
        $("#sbid-completed").show();
    } else if(status == "Booked") {
        $("#sbid-in-service").show();
        $("#sbid-unfinished").hide();
        $("#sbid-completed").hide();
    } else {
        $("#sbid-in-service").hide();
        $("#sbid-unfinished").hide();
        $("#sbid-completed").hide();
    }
    $("#sbid-status").html(status);
}

// change the status in the database
function setStatus(status) {
    $.ajax({
        type: "POST",
        url: "do_set_status.php",
        data: { "id": state.sbID, "status": status },
        success: function(e) {
            if(e.status == "success") {
                statusButtonsToggler(status);
            } else {
                throwError("An error has occurred", "Please contact the administrator <br>" + e.data);
            }
        },
        error: function(e) {
            throwError("An error has occurred", "Please contact the administrator. <br>" + e);
        }
    })
}

// focus on the first input
$("#modalCharge").on("shown.bs.modal", function(e) {
    $("#new-charge-reason").trigger("focus");
});

// add a charge to the database
$("#form-charge").submit(function() {
    if(validateChargeForm()) {
        $.ajax({
            type: "POST",
            url: "do_add_charge.php",
            data: { 
                "id": state.sbID, 
                "charge": $("#new-charge-price").val(),
                "reason": $("#new-charge-reason").val() 
            },
            success: function(e) {
                $("#modalCharge").modal("hide");
                if(e.status == "success") {
                    $("#sbid-id").val(state.sbID);
                    searchByID();
                } else {
                    throwError("An error has occurred", "Please contact the administrator. <br>" + e.data);
                }
            },
            error: function(e) {
                throwError("An error has occurred", "Please contact the administrator. <br>" + e);
            }
        });
    }
});

// delete the charge from the appointment
function removeCharge(id) {
    $.ajax({
        type: "POST",
        url: "do_remove_charge.php",
        data: { "id": id },
        success: function(e) {
            if(e.status == "success") {
                $("#sbid-id").val(state.sbID);
                searchByID();
            } else {
                throwError("An error has occurred", "Please contact the administrator. <br>" + e.data);
            }
        },
        error: function(e) {
            throwError("An error has occurred", "Please contact the administrator. <br>" + e);
        }
    });
}

// handle the save button click
$("#save-charge-btn").click(function() {
    $("#form-charge").submit();
});

// validate the "new charge" form
function validateChargeForm() {
    if($("#new-charge-reason").val() == "") {
        $("#new-charge-reason").addClass("is-invalid");
        return false;
    } else {
        $("#new-charge-reason").removeClass("is-invalid");
    }
    if($("#new-charge-price").val() == "") {
        $("#new-charge-price").addClass("is-invalid");
        return false;
    } else {
        $("#new-charge-price").removeClass("is-invalid");
    }
    return true;
}

// load and shows the extra service modal
function loadESModal() {
    //es-body
    $.ajax({
        type: "GET",
        url: "do_load_extraservice.php?id=" + state.sbID,
        success: function(e) {
            if(e != "error") {
                $("#es-body").html(e);
                $('#modalES').modal('show');
            } else {
                throwError("An error has occurred", "Please contact the administrator.");
            }
        }
    });   
}

// handle the extra service checkboxes
function checkESClick(idcheck, servicename) {
    idcheck = $("#" + idcheck);
    if(idcheck.prop("checked")) {
        state.selectedES.push(servicename);
    } else {
        state.selectedES.splice(state.selectedES.indexOf(servicename), 1);
    }
    console.log(state.selectedES);
}

// save the extra services
$("#save-service-btn").click(function() {
    if(state.selectedES.length > 0) {
        $.ajax({
            type: "POST",
            url: "do_save_extraservice.php",
            data: {
                "id": state.sbID,
                "services": state.selectedES
            },
            success: function(e) {
                if(e.status == "success") {
                    $("#sbid-id").val(state.sbID);
                    searchByID();
                    $("#modalES").modal("hide");
                    //console.log(e);
                } else {
                    throwError("An error has occurred", "Please contact the administrator.<br>" + e.data);
                }
            },
            error: function(e) {
                throwError("An error has occurred", "Please contact the administrator. <br>" + e);
            }
        });
    }
});

// clean the list of extra services when the modal fades off
$("#modalES").on("hidden.bs.modal", function(e) {
    state.selectedES = [];
});

// do the search and update the fields
function searchByID() {
    state.sbID = $("#sbid-id").val();
    let id = state.sbID;
    if(id != "") {
        $("#sbid-results").slideUp("fast", function() {
            $.ajax({
                type: "GET",
                url: "do_search_appointment.php?id=" + id,
                success: function(e) {
                    if(e.status == "success") {
                        $("#sbid-client-name").html(e.data.costumer.name);
                        $("#sbid-client-phone").html(e.data.costumer.phone);
                        $("#sbid-client-email").html(e.data.costumer.email);

                        $("#sbid-services").html("");
                        e.data.services.forEach(function(service) {
                            $("#sbid-services").append("<li>" + service.service + "</li>");
                        });

                        if(e.data.additional != "") {
                            $("#sbid-add-info").html(e.data.additional);
                        } else {
                            $("#sbid-add-info").html("No additional information.");
                        }

                        $("#sbid-staff").html("Staff " + e.data.staff);

                        let sum_charges = 0;
                        if(e.data.charges.length > 0) {
                            $("#sbid-charges").html("");
                            e.data.charges.forEach(function(charge) {
                                $("#sbid-charges").append("<li><i class='fas fa-times' style='color:#dc5353;cursor:pointer' onClick='removeCharge("+ charge.id +")'></i> &euro;" + charge.charge + " — " + charge.reason + "</li>");
                                sum_charges += parseInt(charge.charge);
                            });
                        } else {
                            $("#sbid-charges").html("No extra charges.");
                        }

                        $("#sbid-total-price").html(parseInt(e.data.price) + sum_charges);

                        // handles the buttons
                        statusButtonsToggler(e.data.status);
                        $("#print-bill-btn").attr("href", "bill.php?id=" + id);

                        $("#sbid-results").slideDown("fast");
                    } else {
                        throwError("An error has occurred", e.data);
                    }
                },
                error: function(e) {
                    throwError("An error has occurred", "Please contact the administrator. <br>" + e);
                }
            });
        });
    }
}


/********************************************************************
 * 
 * Service management functions
 * 
 ********************************************************************/

// dropdowns logic
$(".service-type-selector .dropdown-item").click(function() {
    $(".service-type-selector .dropdown-toggle").html($(this).html());
    state.selectedServiceType = $(this).html();
});

// when the user clicks the "edit service" button
function editClick(sname) {
    state.toEdit = sname;
    setPage(pageStates.EDIT_SERVICE);
}

// when the user clicks the "delete service" button
function deleteClick(sname) {
    state.toDelete = sname;
    $("#service-name-delete-modal").html(sname);
    $("#modalDelete").modal('show');
}

// call the code to delete the specified service
$("#delete-service-btn").click(function() {
    $.ajax({
        type: "POST",
        url: "do_delete_service.php",
        data: { "sname": state.toDelete },
        success: function(e) {
            $("#modalDelete").modal('hide');
            if(e.status == "error") {
                // if error
                throwError("An error has occurred", "Please contact the administrator, and send the error: <br>" + e);
            } else {
                // reloads the list
                reloadServiceList();
            }
        },
        error: function(e) {
            throwError("An error has occurred", "Please contact the administrator, and send the error: <br>" + e);
        }
    });
});


// call the code to edit the specified service
$("#edit-service-save-btn").click(function() {
    $.ajax({
        type: "POST",
        url: "do_save_service.php",
        data: {
            "oldname": state.toEdit,
            "sname": $("#sname").val(), 
            "stype": state.selectedServiceType, 
            "sprice": $("#sprice").val(),
            "sreqtime": $("#sreqtime").val()
        },
        success: function(e) {
            if(e.status == "error") {
                // if error
                throwError("An error has occurred", "Please contact the administrator, and send the error: <br>" + e);
            } else {
                // changes pages
                setPage(pageStates.MANAGEMENT);
            }
        },
        error: function(e) {
            throwError("An error has occurred", "Please contact the administrator, and send the error: <br>" + e);
        }
    });
});

// call the code to save the new created service
$("#new-service-save-btn").click(function() {
    $.ajax({
        type: "POST",
        url: "do_new_service.php",
        data: { 
            "sname": $("#new-sname").val(), 
            "stype": state.selectedServiceType, 
            "sprice": $("#new-sprice").val(),
            "sreqtime": $("#new-sreqtime").val()
        },
        success: function(e) {
            if(e.status == "error") {
                // if error
                throwError("An error has occurred", "Please contact the administrator, and send the error: <br>" + e);
            } else {
                // changes pages
                setPage(pageStates.MANAGEMENT);
            }
        },
        error: function(e) {
            throwError("An error has occurred", "Please contact the administrator, and send the error: <br>" + e);
        }
    });
});

// load data about the specified (state.toEdit) service
function loadService() {
    $.ajax({
        type: "GET",
        url: "do_load_service.php?sname=" + state.toEdit,
        success: function(e) {
            if(e.status) {
                throwError("An error has occurred", "Please try again");
                setPage(pageStates.MANAGEMENT);
            } else {
                $("#sname").val(e.name);
                $(".service-type-selector .dropdown-toggle").html(e.type);
                state.selectedServiceType = e.type;
                $("#sprice").val(e.price);
                $("#sreqtime").val(e.time);
            }
        },
        error: function (e) {
            throwError("An error has occurred", "Please contact the administrator, and send the error: <br>" + e);
        }
    });
}

// reload the list of services (when edit/new)
function reloadServiceList() {
    $.ajax({
        type: "GET",
        url: "do_load_services.php",
        success: function(e) {
            if(e != "error") {
                $("#all-services-tbody").html(e);
            } else {
                throwError("An error has occurred", "Unable to load the contents. Please restart the page.");
            }
        },
        error: function (e) {
            throwError("An error has occurred", "Please contact the administrator, and send the error: <br>" + e);
        }
    });
}

// focus on the cancel buttons on the confirmation modal (when the user press enter, cancel is clicked)
$("#modalDelete").on("shown.bs.modal", function () {
    $("#delete-service-btn-cancel").trigger("focus");
});

$("#modalDeleteAP").on("shown.bs.modal", function () {
    $("#delete-appointment-btn-cancel").trigger("focus");
});