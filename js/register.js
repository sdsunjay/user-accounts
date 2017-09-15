$(document).on('click', '#submit', function() { // catch the form's submit event
    if (regformhash(document.getElementById("name"), document.getElementById("username"),
	    document.getElementById("email"), document.getElementById("password"),
	    document.getElementById("password1"), document.getElementById("answer1"))) {
            if (validateQuestion($('#question1').val())) {
               $.ajax({
                        method: "POST",
                        url: "register.php",
                        type: 'POST',
                        data: {
                            action: "register",
                            name: $("#name").val(),
                            username: $("#username").val(),
                            email: $("#email").val(),
                            password: $("#password").val(),
                            password1: $("#password1").val(),
                            question1: $("#question1").val(),
                            answer1: $("#answer1").val()
                        } //send registration information to register.php
                    })
                    .done(function(responseData) {
                        var parsed_data = JSON.parse(responseData);
                        console.log(responseData); // works. outputs to console success
                        if (parsed_data.response === "yes") {
                            window.location = "protected_page.php";
                        } else if (parsed_data.response === "no") {
                            alert(parsed_data.msg);
                        }
                    })
                    .fail(function(responseData) {
                        alert('A network error has occurred. Please try again.');
                    });
            } else {
                alert('Error with Question 1.');
            }
    }
    return false; // cancel original event to prevent form submitting
});
