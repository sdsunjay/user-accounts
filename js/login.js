$(document).on('click', '#submit', function() { // catch the form's submit event
    if (checkUsername(username)) {
        if (checkPassword(password, password)) {
            // Send data to server through ajax call
            // action is functionality we want to call and outputJSON is our data
            $.ajax({
                    type: 'POST',
                    url: 'check.php',
                    data: {
                        action: 'login',
                        username: $("#username").val(),
                        password: $("#password").val(),
                        submit: true
                    }, //send username and password and submit to check.php
                })
                .done(function(responseData) {
                    var parsed_data = JSON.parse(responseData);
                    //console.log(responseData); // works. outputs to console success
                    if (parsed_data.response === "yes") {
                        window.location = "protected_page.php";
                    } else if (parsed_data.response === "no") {
                        alert(parsed_data.msg);
                        document.getElementById("password").focus();
                    } else {
                        alert('An unknown error has occurred. Please try again.');
                    }
                  })
                  .fail(function(responseData) {
                     alert('A network error has occurred. Please try again.');
                  });
            // This callback function will trigger on unsuccessful action
        }
    }
    return false; // cancel original event to prevent form submitting
});
