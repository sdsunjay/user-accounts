$(document).on('click', '#submit', function() { // catch the form's submit event
   if(regformhash(document.getElementById("username").value,document.getElementById("email").value,document.getElementById("password").value,document.getElementById("password1").value,document.getElementById("answer1").value)){
      if(validateEmail($('#email').val())){
         if ($('#password').val().length > 7 ){
            if ($('#password').val().length < 72){
               if ($('#password1').val().length > 7){
                  if ($('#password1').val().localeCompare($('#password').val()) == 0){
                     if ($('#answer1').val().length > 7){
                        if ($('#answer1').val().length < 128){
                           if (validateQuestion($('#question1').val())){
                              $.ajax({
                                 method: "POST",
                                 url: "register.php",
                                 type: 'POST',
                                 data: {
                                    action : "register",
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
                                 //console.log(responseData); // works. outputs to console success
                                 if (parsed_data.response.localeCompare("yes") == 0){
                                    window.location = "protected_page.php";
                                 }
                                 else{
                                    alert(parsed_data.response);
                                 }
                              })
                              .fail(function(responseData) {
                                 alert('A network error has occurred. Please try again.');
                              });
                           }  else {
                              alert('Error with Question 1.');
                           }           
                        }  else {
                           alert('Answer1 must be less than 128 characters.');
                        }           
                     } else {
                        alert('Answer1 must be greater than 7 characters.');
                     }           
                  } else {
                     alert('Passwords do not match.');
                  }           
               }
               else {
                  alert('Password is of insufficient length.');
               }           
            } else {
               alert('Password must be less than 72 characters.');
            }           
         } else {
            alert('Password is of insufficient length.');
         }           
      } else {
         alert('Please fix email field.');
      }
   }
   return false; // cancel original event to prevent form submitting
});

//check if email address is valid
/*
   function validateEmail(email) { 
   var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;  return re.test(email);
   }*/
function validateEmail(email) {
   var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
   return re.test(email);
}
function validateQuestion(n){
   if(n === "1" || n === "2" || n === "3" || n === "4" || n === "5" || n === "6") 
      return true;
   else
      return false;
}
function hasWhiteSpace(s) {
   return /\s/g.test(s);
}
