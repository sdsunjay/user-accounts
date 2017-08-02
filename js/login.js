$(document).on('click', '#submit', function() { // catch the form's submit event
   if($('#username').val().length > 0 && $('#password').val().length > 0){
      // Send data to server through ajax call
      // action is functionality we want to call and outputJSON is our data
      $.ajax({
         url: "https://www.sunjaydhama.com/projects/accounts/check.php",
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
               //window.location = "protected_page.php";
               window.location = "https://www.sunjaydhama.com/projects/accounts/protected_page.php";
            }
            else if(result === "No")
            {
               // window.location = "https://google.com";
               window.location = "https://www.sunjaydhama.com/projects/accounts/index.html";
            }
            else
            {
               alert(result);
               //alert("Unknown Fail");
               //window.location = "error.php";
            }
         },
               // This callback function will trigger on unsuccessful action                
         error: function(jqXHR, exception) {
          if (jqXHR.status === 0) {
             alert('Not connect.\n Verify Network.');
          } else if (jqXHR.status == 404) {
             alert('Requested page not found. [404]');
          } else if (jqXHR.status == 500) {
             alert('Internal Server Error [500].');
          } else if (exception === 'parsererror') {
             alert('Requested JSON parse failed.');
          } else if (exception === 'timeout') {
             alert('Time out error.');
          } else if (exception === 'abort') {
             alert('Ajax request aborted.');
          } else {
             alert('Uncaught Error.\n' + jqXHR.responseText);
          }
       }
      });                   
   } else {
      alert('Please fill all nececery fields');
   }           
   return false; // cancel original event to prevent form submitting
});         
