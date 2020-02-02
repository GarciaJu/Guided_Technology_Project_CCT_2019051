var pageStates = {
    SEL_DATE: 1,
    SEL_TYPE: 2,
    SUCCESS: 3
}

var state = {
    totalPrice: 0,
    step: 1,
    selectedDate: new Date(),
    selectedType: "none",
    selectedServices: []
}

var fcalendar;

$(document).ready(function() {
    fcalendar = new FullCalendar.Calendar(document.getElementById("calendar-obj"), {
        timeZone: 'UTC',
        plugins: [ 'dayGrid', 'timeGrid', 'interaction' ],
        defaultView: 'timeGridWeek',
        header: {
            left: 'timeGridWeek',
            center: 'title'
        },
        allDaySlot: false,
        minTime: "10:00",
        maxTime: "20:00",
        height: "auto",
        slotDuration: "1:00",
        eventClick: (info) => calendarClick(info),
        events: {
            url: 'available_api.php'
        },
        eventColor: "#50d890",
        eventTextColor: "#000"
    });

    fcalendar.render();
});

// change subpages
function nextStep() {
    if(state.step == pageStates.SEL_DATE) {
        state.step = pageStates.SEL_TYPE;
        changePage("#step1", "#step2");
    } else if(state.step == pageStates.SEL_TYPE) {
        if(state.totalPrice > 0) { // if any service has been chosen, proceed
            state.step = pageStates.SUCCESS;
            saveAppointment();
        } else { // error, no service selected
            throwError("No service selected", "You must select at least one service before proceeding");
        }
    }
}

// when the user click an available date/time
function calendarClick(info) {
    let now = new Date();
    date = info.event.start;
    console.log(info);
    if(date < now) {
        throwError("Invalid date", "Your appointment cannot be before today (" + new Date() + ")");
    } else if(date.getDay() > 1 && info.event.title != "Unavailable") {
        state.selectedDate = date;
        nextStep();
    }
}

// shows error window with specified parameters
function throwError(title, body) {
    $("#modalErrorTitle").html(title);
    $("#modalErrorBody").html(body);
    $("#modalError").modal("show");
}

// dropdown menu handler
$("#service-type-selector .dropdown-item").click(function() {
    state.selectedType = $(this).html();
    $("#dropdownMenuButton").html(state.selectedType);
    loadServices(state.selectedType);
    $(".other-parameters").slideDown("fast");
});

// checkbox handler
function checkClick(id, value, name) {
    id = "#" + id;
    if($(id).prop("checked")) {
        $(id).parent().parent().css({
            backgroundColor: "#edabca",
            color: "#fff"
        });
        state.totalPrice += value;
        state.selectedServices.push(name);
        $("#total-price").html(state.totalPrice);
    } else {
        $(id).parent().parent().css({
            backgroundColor: "#fff",
            color: "#2e2e2e"
        });
        state.totalPrice -= value;
        state.selectedServices.splice(state.selectedServices.indexOf(name), 1);
        $("#total-price").html(state.totalPrice);
    }
}

$(".next-step").click(nextStep);

// homepage handler
$(".navbar-brand").click(function() {
    state.step = pageStates.SEL_DATE;
    state.totalPrice = 0;
    
    if($("#step2").css("display") != "none") {
        changePage("#step2", "#step1");
    } else if($("#step3").css("display") != "none"){
        changePage("#step3", "#step1");
    }

    // clean all the inputs
    $("input:checkbox").each(function() {
        $(this).prop("checked", false);
        $(this).parent().parent().css({
            backgroundColor: "#fff",
            color: "#2e2e2e"
        });
    });

    $("textarea").val("");
    $("#total-price").html("0");
});

// request services in the determined category
function loadServices(type) {
    $.ajax({
        type: 'GET',
        url: 'do_load_services.php?type=' + type,
        success: function(e) {
            if(e != "error") {
                state.totalPrice = 0;
                state.selectedServices = [];
                $("#select-services-tbody").html(e);
            } else {
                throwError("An error has occurred", "Please try again.");
            }
        },
        error: function(e) {
            throwError("An error has occurred", "Please contact the administrator and show the error: <br>" + e);
        }
    });
}

// write the appointment
function saveAppointment() {
    $.ajax({
        type: 'POST',
        url: 'do_save_appointment.php',
        data: {
            "services": state.selectedServices,
            "date": state.selectedDate.toJSON(),
            "price": state.totalPrice,
            "addInfo": $("#addInfoTA").val()
        },
        success: function(e) {
            if(e.status == "error") {
                throwError("An error has occurred", e.reason);
            } else {
                // change pages, success
                if(state.selectedType == "Haircuit" || state.selectedType == "Hair removal") {
                    state.selectedType = "an " + state.selectedType;
                } else {
                    state.selectedType = "a " + state.selectedType;
                }
                $("#r-ap-type").html(state.selectedType);
                $("#r-ap-time").html(state.selectedDate);
                $("#r-ap-id").html(e.id);
                changePage("#step2", "#step3");
            }
            console.log(e);
        },
        error: function(e) {
            throwError("An error has occurred", "Please contact the administrator and show the error: <br>" + e);
        }
    })
}