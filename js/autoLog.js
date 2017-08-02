function stuff()
{
   $.ajax({
type: "POST",
url: "check.php",
data: {
      username:"mike_johnson",
      password:"password",
      submit:true,
            },
success: function(data) {
   if (data === "Yes") {
      window.location = "protected_page.php";
   }
   else {
      window.location = "index.php";
   }
}
//dataType: dataType
});
}
function post_to_url(path, params) {
   method = "post"; // Set method to post by default if not specified.

   // The rest of this code assumes you are not using a library.
   // It can be made less wordy if you use one.
   var form = document.createElement("form");
   form.setAttribute("method", method);
   form.setAttribute("action", path);

   for(var key in params) {
      if(params.hasOwnProperty(key)) {
         var hiddenField = document.createElement("input");
         hiddenField.setAttribute("type", "hidden");
         hiddenField.setAttribute("name", key);
         hiddenField.setAttribute("value", params[key]);

         form.appendChild(hiddenField);
      }
   }

   document.body.appendChild(form);
   form.submit();
}
//submit, username, password
//post_to_url('../autoLog.html', {username: 'Johnny Bravo'});
stuff();
