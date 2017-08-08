$(document).on('click', '#submit', function() { // catch the form's submit event
    if ($('#username').val().length > 0) {
        if (checkUsername(username)) {
            // Send data to server through ajax call
            // action is functionality we want to call and outputJSON is our data
            $.ajax({
                url: 'forgot_password.php',
                data: {
                    action: 'checkUsername',
                    username: $("#username").val(),
                    submit: true
                }, //send username and password and submit to forgot_password.php
                type: 'post',
                async: true,
                beforeSend: function() {
                    //Not sure what to do here..
                    // This callback function will trigger before data is sent
                    // $.mobile.showPageLoadingMsg(true); // This will show ajax spinner
                },
                complete: function() {
                    //Not sure what to do here..
                    // This callback function will trigger on data sent/received complete
                    //$.mobile.hidePageLoadingMsg(); // This will hide ajax spinner
                },
                success: function(result) {

                    var data = JSON.parse(result);
                    //console.log(responseData); // works. outputs to console success
                    if (data.response == "yes") {
                        $('#Sign-In').hide();
                        $('#Question').show();
                        //alert(data.question);
                        //window.location = "protected_page.php";
                    } else if (data.response === "no") {
                        alert(data.msg);
                        //alert('Username does not exist');
                    } else {
                        window.location = "error.php";
                    }
                },
                error: function(request, error) {
                    // This callback function will trigger on unsuccessful action
                    alert('Network error has occurred please try again!');
                }
            });
        }
    } else if ($('#answer').val().length > 0) {
        // Send data to server through ajax call
        // action is functionality we want to call and outputJSON is our data
        $.ajax({
                type: 'POST',
                url: 'forgot_password.php',
                data: {
                    action: 'checkAnswer',
                    answer: $("#answer").val(),
                    submit: true
                }, //send secret answer and submit to checkAnswer.php
            })
            .done(function(responseData) {
                var parsed_data = JSON.parse(responseData);
                //console.log(responseData); // works. outputs to console success
                if (parsed_data.response == "yes") {
                    window.location = "reset_password.php";
                } else {
                    alert(parsed_data.response);
                }
            });
    }
    return false; // cancel original event to prevent form submitting
});
