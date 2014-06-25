$(document).on('click', '#submit', function() { // catch the form's submit event
   if($('#username').val().length > 0 && $('#password').val().length > 0){
      // Send data to server through ajax call
      // action is functionality we want to call and outputJSON is our data
      $.ajax({
         url: "check.php",
         data: {
         action : 'login', 
         username: $("#username").val(),
         password: $("#password").val(),
         submit:true
         }, //send username and password and submit to check.php
         type: "POST",                   
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
         success: function (result) {

            if (result === "Yes")
            {
               window.location = "protected_page.php";
            }
            else if(result === "No")
            {
               // window.location = "http://google.com";
               window.location = "index.php";
            }
            else
            {
               window.location = "error.php";
            }
         },
         error: function (request,error) {
               // This callback function will trigger on unsuccessful action                
               alert('Network error has occurred please try again!');
            }
      });                   
   } else {
      alert('Please fill all nececery fields');
   }           
   return false; // cancel original event to prevent form submitting
});         
