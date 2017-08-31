$(document).on('click', '#submit', function() { // catch the form's submit event
    if (regformhash(document.getElementById("name"), document.getElementById("username"),
	    document.getElementById("email"), document.getElementById("password"),
	    document.getElementById("password1"), document.getElementById("answer1"))) {
            alert('inside regformhash'); 
            if (validateQuestion($('#question1').val())) {
               alert('inside question'); 
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
                        } else {
                            alert(parsed_data.response);
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

//check if email address is valid
/*
   function validateEmail(email) {
   var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;  return re.test(email);
   }*/
