function verifyPassword(password, password1)
{
 var matches = password.match(/\d+/g);
 if (matches != null) {
      if (password.length > 9 ){
         if (password.length < 72){
            if (password.localeCompare(password1) == 0)
            {
               return true;
            }
            else
            {
               message="Passwords do not match.";
               //passwords do not match
            }
         }
         else
         {
            message="Password must be 71 characters or less.";
            //password is too long
         }
      }
      else
      {
         message="Password must be 10 characters or greater.";
         //password is too short
      }
   }
   else
   {
      message="Password must contain at least one digit.";
   }
   return false;
}
var message;
$(document).on('click', '#submit', function() { // catch the form's submit event
 if(verifyPassword(($("#new_password").val()),$("#new_password1").val()))
   {
      $.ajax({
         url: "change_password.php",
         data: {
         action : 'change_password', 
         old_password: $("#old_password").val(),
         new_password: $("#new_password").val(),
         new_password1: $("#new_password1").val(),
         submit:true
         }, 
         type: "POST",                   
         beforeSend: function() {
         },
            complete: function() {
         },
         success: function (result) {

            if (result === "Yes")
            {
               alert("success");
               window.location='#';
            }
            else if(result === "No")
            {
               alert("No");
               window.location='#';
            }
            else
            {
               alert(result);
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
   }
   else
   {
      alert(message);
   }
});
