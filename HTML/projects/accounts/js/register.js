$(document).on('click', '#submit', function() { // catch the form's submit event
//if(regformhash(document.getElementById("username").value,document.getElementById("email").value,document.getElementById("password").value,document.getElementById("password1").value,document.getElementById("answer1").value,document.getElementById("answer2").value))
               if($('#username').val().length > 0){
                  if($('#email').val().length > 0){
                     if ($('#password').val().length > 6){
                        if ($('#password1').val().length > 6){
                           if ($('#answer1').val().length > 3){
                              if ($('#answer2').val().length > 3){
                                 $.ajax({url: 'register.php',data: {action : 'register',username: $("#username").val(),email: $("#email").val(),password: $("#password").val(),password1: $("#password1").val(),question1: $("#question1").val(),answer1: $("#answer1").val(),question2: $("#question2").val(),answer2: $("#answer2").val()
                                    }, //send username and password and submit to check.php
                                    type: 'post',
                                    async: true,
                                    success: function (result) {
                                       if (result === "Yes")
                                       {
                                          window.location = "protected_page.php";
                                       }
                                       else if(result === "No"){
                                          window.location = "index.php";
                                       }
                                       else
                                       {
                                          
                                          window.location = "index.php";
                                       }
                                    },
                                    error: function (request,error) {
                                    alert('Network error has occurred please try again!');
                                    }
                                 });                   
                                 } else {
                                 alert('Answer2 must be greater than 3 characters');
                              }           
                              } else {
                              alert('Please fill all nececery fields');
                           }           
                           } else {
                           alert('Please fill all nececery fields');
                        }           
                        } else {
                        alert('Please fill all nececery fields');
                     }           
                     } else {
                     alert('Please fill all nececery fields');
                  }           
                  } else {
                  alert('Please fill all nececery fields');
               }           
               return false; // cancel original event to prevent form submitting
            });     
