function verify_register_fields() {
    if($("#name-input").val() == ""){
        $("#name-input").addClass("is-invalid");
        return false;
    } else {
        $("#name-input").removeClass("is-invalid");
    }
    if($("#email-input").val() == ""){
        $("#email-input").addClass("is-invalid");
        return false;
    } else {
        $("#email-input").removeClass("is-invalid");
    }
    if($("#pwd-input").val() == ""){
        $("#pwd-input").addClass("is-invalid");
        return false;
    } else {
        $("#pwd-input").removeClass("is-invalid");
    }
    if($("#pwd2-input").val() == ""){
        $("#pwd2-input").addClass("is-invalid");
        return false;
    } else {
        $("#pwd2-input").removeClass("is-invalid");
    }
    if($("#pwd-input").val().length < 8){
        $("#pwd-input").addClass("is-invalid");
        return false;
    } else {
        $("#pwd-input").removeClass("is-invalid");
    }
    if($("#pwd2-input").val().length < 8){
        $("#pwd2-input").addClass("is-invalid");
        return false;
    } else {
        $("#pwd2-input").removeClass("is-invalid");
    }
    if(check_both_pwd_equal() == false)
        return false;
    return true;
}

function verify_login_fields() {
    if($("#email-input").val() == "" || !validate_mail($("#email-input").val())){
        $("#email-input").addClass("is-invalid");
        return false;
    } else {
        $("#email-input").removeClass("is-invalid");
    }
    if($("#pwd-input").val() == ""){
        $("#pwd-input").addClass("is-invalid");
        return false;
    } else {
        $("#pwd-input").removeClass("is-invalid");
    }
    return true;
}

// first verification of passwords
function check_both_pwd_equal(){
    setTimeout(function(){
        if($("#pwd-input").val() != $("#pwd2-input").val()){
            $("#pwd2-input").addClass("is-invalid");
            return true;
        } else {
            $("#pwd2-input").removeClass("is-invalid");
            return false;
        }
    }, 500);
}   

// verify on text changed
$("#pwd-input").keypress(function(e){
    check_both_pwd_equal();
});

// verify on text changed
$("#pwd2-input").keypress(function(e){
    check_both_pwd_equal();
});

// handles the login form
$("#login-form").submit(function(e) {
    e.preventDefault();
    if(verify_login_fields()) {
        $.ajax({
            type: 'POST',
            url: 'logic.php',
            data: $(this).serialize(),
            success: function(e) {
                let ret = JSON.parse(e);
                if(ret.status == 'success') {
                    window.location = '../appointment';
                    //console.log(e);
                } else {
                    throwError('An error has occurred', ret.reason);
                }
            },
            error: function(data) {
                throwError('An error has occurred:<br>', data + '<br>Please contact the administrator.');
            }
        });
    }
});

// handles the register form
$("#register-form").submit(function(e) {
    e.preventDefault();
    if(verify_register_fields()) {
        $.ajax({
            type: 'POST',
            url: 'logic.php',
            data: $(this).serialize(),
            success: function(e) {
                let ret = JSON.parse(e);
                if(ret.status == 'success') {
                    window.location = '../appointment';
                } else {
                    throwError('An error has occurred', ret.reason);
                }
            },
            error: function(data) {
                throwError('An error has occurred:<br>', data + '<br>Please contact the administrator.');
            }
        });
    }
});

function validate_mail(mail){
    var r = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;
    return r.test(String(mail).toLowerCase());
}

function throwError(error_title, error_body) {
    $("#modalErrorTitle").html(error_title);
    $("#modalErrorBody").html(error_body);
    $("#modalError").modal('show');
}

$("#modalError").on("shown.bs.modal", function() {
    $("#error-ok-button").trigger("focus");
});