
function validateQuestion(n) {
    if (n === "1" || n === "2" || n === "3" || n === "4" || n === "5" || n === "6")
        return true;
    else {
        alert('Select a valid security question');
        document.getElementById("question1").focus();
        return false;
    }
}

function hasWhiteSpace(s) {
    return /\s/g.test(s);
}

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

function testPassword(str) {
    var re = /^.*(?=.{8,})(?=.*[a-zA-Z])(?=.*\d)(?=.*[!#$%&? "]).*$/;
    return re.test(str);
}

function checkEmail(email) {
    var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    if (!re.test(email.value)){
      alert("email is not valid");
      document.getElementById("email").focus();
      return false;
    }
    return true;

}

function checkUsername(username) {
    // Check the username
   console.log(username.value);
   re = /^\w+$/;
    if (!re.test(username.value)) {
        alert("Username must contain only letters, numbers and underscores. Please try again");
        document.getElementById("username").focus();
        return false;
    }

    // Check that the username is sufficiently long (min 3 chars)
    if (username.value.length < 3) {
        alert('Usernames must be at least 3 characters long.  Please try again');
        document.getElementById("username").focus();
        return false;
    }
    // Check that the username is not too long (max 64 chars)
    if (username.value.length > 64) {
        alert('Usernames must be 64 character or less.  Please try again');
        document.getElementById("username").focus();
        return false;
    }
    return true;
}

function checkPassword(password, conf) {

    // Check that the password IS the same as the confirmation password
    if (password.value !== conf.value) {
        alert("Error: Password and Password Confirmation do not match.");
        document.getElementById("password").focus();
        return false;
    }

    // Check that the password is sufficiently long (min 8 chars)
    if (password.value.length < 8) {
        alert('Passwords must be at least 8 characters long.  Please try again');
        //form.password.focus();
        document.getElementById("password").focus();
        return false;
    }

    // Check that the password is not too long (max 64 chars)
    if (password.value.length > 64) {
        alert('Passwords must be 64 character or less.  Please try again');
        //form.password.focus();
        document.getElementById("password").focus();
        return false;
    }

    // Check that the password contains atleast one number
    re = /[0-9]/;
    if (!re.test(password.value)) {
        alert("Error: password must contain at least one number (0-9)!");
        document.getElementById("password").focus();
        return false;
    }

    // Check that the password contains atleast one lowercase letter
    re = /[a-z]/;
    if (!re.test(password.value)) {
        alert("Error: password must contain at least one lowercase letter (a-z)!");
        document.getElementById("password").focus();
        return false;
    }

    // Check that the password contains atleast one uppercase letter
    re = /[A-Z]/;
    if (!re.test(password.value)) {
        alert("Error: password must contain at least one uppercase letter (A-Z)!");
        document.getElementById("password").focus();
        return false;
    }
    console.log('Password: true');
    return true;
}

function checkAnswer(answer1) {

    // Check the security answer
    re = /^\w+$/;
    if (!re.test(answer1.value)) {
        alert("Security Answer must contain only letters, numbers and underscores. Please try again");
        document.getElementById("answer1").focus();
        return false;
    }

    // check that the first answer is sufficiently long (min 5 chars)
    if (answer1.value.length < 5) {
        alert('Answers must be at least 5 characters long. Please try again');
        document.getelementbyid("answer1").focus();
        return false;
    }
    // check that the first answer is not too long (max 64 chars)
    if (answer1.value.length > 64) {
        alert('Answers must be 64 characters or fewer. Please try again');
        document.getelementbyid("answer1").focus();
        return false;
    }
    console.log('Answer: true');
    return true;
}

function checkName(name){

   var re = /^[a-zA-Z\s]*$/;
   if (!re.test(name.value)) {
      alert("Name must contain only letters and spaces. Please try again");
      document.getElementById("name").focus();
      return false;
   }
   return true;
}
function loginformhash(email, password){
      if(email.value === '' ||
          password.value === '') {
      alert('You must provide all the requested details. Please try again');
   } else if (checkUsername(username)) {
      if (checkPassword(password, password)) {
         return true;
      }
   }
   return false;
}

function regformhash(name, username, email, password, conf, answer1) {
   // Check each field has a value
   if (username.value === '' ||
       email.value === '' ||
          password.value === '' ||
             conf.value === '') {

      alert('You must provide all the requested details. Please try again');
   } else if (checkUsername(username)) {
      if (checkPassword(password, conf)) {
         if (checkAnswer(answer1)) {
            if (checkEmail(email)) {
               if (checkName(name)) {

                  // Check that the password is not the same as the username
                  if (password.value === username.value) {
                     alert("Error: Password must be different from Username!");
                     document.getElementById("password").focus();
                     return false;

                  }

                  // Finally submit the form.
                  //  form.submit();
                  return true;
               }
            }
         }
      }
   } else {
      alert('Please enter  a value for all required fields');
   }
   return false;
}
