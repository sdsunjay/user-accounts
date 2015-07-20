$(document).on('click', '#submit', function() { // catch the form's submit event
   //if(regformhash(document.getElementById("username").value,document.getElementById("email").value,document.getElementById("password").value,document.getElementById("password1").value,document.getElementById("answer1").value,document.getElementById("answer2").value))
   if( $('#username').val().length > 2){
         if($('#username').val().length < 64) {
             if(validateEmail($('#email').val())){
               if ($('#password').val().length > 9 ){
                  if ($('#password').val().length < 72){
                     if ($('#password1').val().length > 9){
                        if ($('#password1').val().localeCompare($('#password').val()) == 0)
                           {
                              if ($('#answer1').val().length > 7){
                                 if ($('#answer1').val().length < 128){
                                    if (validateQuestion($('#question1').val())){
                                       $.ajax({
                                          method: "POST",
                                          url: "register.php",
                                          data: {
                                             action : "register",
                                             username: $("#username").val(),
                                             email: $("#email").val(),
                                             password: $("#password").val(),
                                             password1: $("#password1").val(),
                                             question1: $("#question1").val(),
                                             answer1: $("#answer1").val()
                                       } //send registration information to register.php
                                      // type: 'post',
                                      // async: true,
                                    })
                                      .done(function( result ) {
                                             if (result.toLowerCase() == "yes")
                                             {
                                                //alert("toLowerCase");
                                                window.location = "protected_page.php";
                                             }
                                             if (result.localeCompare("yes") == 0){
                                                //alert("localeCompare");
                                                window.location = "protected_page.php";
                                              }
                                             else{
                                                //bug here needs to be fixed
                                              //  alert(result);
                                                window.location = "protected_page.php";
                                                //window.location = "register.html";
                                             }
                                      })
                                      .fail(function() {
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
         } else {
            alert('Username must be between 3 and 64 characters.');
         }           
   } else {
      alert('Username must be between 3 and 64 characters.');
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
