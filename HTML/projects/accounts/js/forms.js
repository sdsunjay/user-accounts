function formhash(form, password) {
    // Create a new element input, this will be our hashed password field. 
    var p = document.createElement("input");
 
    // Add the new element to our form. 
    form.appendChild(p);
    p.name = "p";
    p.type = "hidden";
    p.value = hex_sha512(password.value);
 
    // Make sure the plaintext password doesn't get sent. 
    password.value = "";
 
    // Finally submit the form. 
    form.submit();
}
 
function regformhash(username, email, password, conf,answer1,answer2) {
     // Check each field has a value
    if (username.value == ''         || 
          email.value == ''     || 
          password.value == ''  || 
          conf.value == '') {
 
        alert('You must provide all the requested details. Please try again');
        return false;
    }
 
    // Check the username
 
    re = /^\w+$/; 
    if(!re.test(username.value)) { 
        alert("Username must contain only letters, numbers and underscores. Please try again"); 
        //form.username.focus();
        document.getElementById("username").focus();
        return false; 
}

    // Check that the username is sufficiently long (min 6 chars)
    if (username.length < 3) {
        alert('Usernames must be at least 3 characters long.  Please try again');
        document.getElementById("username").focus();
        //form.username.focus();
        return false;
    }



    // Check that the password is sufficiently long (min 6 chars)
    // The check is duplicated below, but this is included to give more
    // specific guidance to the user
    if (password.length < 6) {
        alert('Passwords must be at least 6 characters long.  Please try again');
        //form.password.focus();
        document.getElementById("password").focus();
        return false;
    }
 
    // At least one number, one lowercase and one uppercase letter 
    // At least six characters 
 
  /*  var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/; 
    if (!re.test(password.value)) {
        alert('Passwords must contain at least one number, one lowercase and one uppercase letter.  Please try again');
        document.getElementById("password").focus();
        return false;
    }
*/ 
    // Check password and confirmation are the same
    if (password.value != conf.value) {
        alert('Your password and confirmation do not match. Please try again');
        document.getElementById("password").focus();
        //form.password.focus();
        return false;
    }
 
    // check that the first answer is sufficiently long (min 3 chars)
    // the check is duplicated below, but this is included to give more
    // specific guidance to the user
    if (answer1.length < 3) {
        alert('answers must be at least 3 characters long.  please try again');
        //form.password.focus();
        document.getelementbyid("answer1").focus();
        return false;
    }
    // check that the second answer is sufficiently long (min 3 chars)
    // the check is duplicated below, but this is included to give more
    // specific guidance to the user
    if (answer2.length < 3) {
        alert('answers must be at least 3 characters long.  please try again');
        //form.password.focus();
        document.getelementbyid("answer2").focus();
        return false;
    }
    // Create a new element input, this will be our hashed password field. 
    //var p = document.createElement("input");
 
    // Add the new element to our form. 
    //form.appendChild(p);
    //p.name = "p";
    //p.type = "hidden";
   // p.value = hex_sha512(password.value);
 // Make sure the plaintext password doesn't get sent. 
    //password.value = "";
    //conf.value = "";
 
    // Finally submit the form. 
  //  form.submit();
    return true;
}
