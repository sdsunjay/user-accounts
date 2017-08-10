
<?php
include_once("../../config/db_config.php");

function create_login_attempts($mysqli){

   // sql to create table
   $sql = "CREATE TABLE IF NOT EXISTS login_attempts ( 
      id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
      user_id INTEGER NOT NULL,
      FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
      created_at TIMESTAMP DEFAULT '0000-00-00 00:00:00', 
      updated_at TIMESTAMP DEFAULT now() ON UPDATE now()
      );"; 

   if (mysqli_query($mysqli, $sql)) {
      echo "Table user_roles created successfully<br>";
   } else {
      echo "Error creating table: " . mysqli_error($mysqli);
   }

}

function insert_roles($mysqli){

   $roles = [
      1 => "Admin",
      2 => "Tester",
      3 => "User",
   ];
   $query = "INSERT into roles (role) VALUES (?)";
   $query = $mysqli->prepare($query);
   foreach($roles as $value) {
      $query->bind_param('s', $value);
      // Execute the prepared query.
      if($query->execute()){
         echo "Role inserted successfully<br>";
      }
   }
   mysqli_stmt_close($query);
}

function create_user_roles($mysqli){

   // sql to create table
   $sql = "CREATE TABLE IF NOT EXISTS user_roles ( 
      id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
      role_id INTEGER NOT NULL,
      user_id INTEGER NOT NULL,
      FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
      FOREIGN KEY (role_id) REFERENCES roles(id) ON UPDATE CASCADE ON DELETE CASCADE,
      created_at TIMESTAMP DEFAULT '0000-00-00 00:00:00', 
      updated_at TIMESTAMP DEFAULT now() ON UPDATE now()
      );"; 

   if (mysqli_query($mysqli, $sql)) {
      echo "Table user_roles created successfully<br>";
   } else {
      echo "Error creating table: " . mysqli_error($mysqli);
   }

}

function create_roles($mysqli){

   // sql to create table
   $sql = "CREATE TABLE IF NOT EXISTS roles ( 
      id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
      role VARCHAR(255) NOT NULL,
      description VARCHAR(255),
      created_at TIMESTAMP DEFAULT '0000-00-00 00:00:00', 
      updated_at TIMESTAMP DEFAULT now() ON UPDATE now()
      );"; 

   if (mysqli_query($mysqli, $sql)) {
      echo "Table roles created successfully <br>";
   } else {
      echo "Error creating table: " . mysqli_error($mysqli);
   }

}

function insert_questions($mysqli){

   $questions = [
      1 => "What is your favorite actor's name?",
      2 => "What is the last name of the teacher who gave you your first failing grade?",
      3 => "If you could have any super power, what would it be?",
      4 => "What was the name of your favorite teacher in school?",
      5 => "In what city is your vacation home located?",
      6 => "What was the nickname of your grandfather or grandmother?"
   ];
   $query = "INSERT into questions (question) VALUES (?)";
   $query = $mysqli->prepare($query);
   foreach($questions as $value) {
      $query->bind_param('s', $value);
      // Execute the prepared query.
      if($query->execute()){
         echo "Question inserted successfully<br>";
      }
   }
   mysqli_stmt_close($query);
}

function create_user_answers($mysqli){

   // sql to create table
   $sql = "CREATE TABLE IF NOT EXISTS user_answers ( 
      id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
      question_id INTEGER NOT NULL,
      user_id INTEGER NOT NULL,
      answer VARCHAR(255) NOT NULL,
      FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
      FOREIGN KEY (question_id) REFERENCES questions(id) ON UPDATE CASCADE ON DELETE CASCADE,
      created_at TIMESTAMP DEFAULT '0000-00-00 00:00:00', 
      updated_at TIMESTAMP DEFAULT now() ON UPDATE now()
      );"; 

   if (mysqli_query($mysqli, $sql)) {
      echo "Table user_answers created successfully<br>";
   } else {
      echo "Error creating table: " . mysqli_error($mysqli);
   }

}

function create_questions($mysqli){

   // sql to create table
   $sql = "CREATE TABLE IF NOT EXISTS questions ( 
      id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
      question VARCHAR(255) NOT NULL,
      created_at TIMESTAMP DEFAULT '0000-00-00 00:00:00', 
      updated_at TIMESTAMP DEFAULT now() ON UPDATE now()
      );"; 

   if (mysqli_query($mysqli, $sql)) {
      echo "Table questions created successfully <br>";
   } else {
      echo "Error creating table: " . mysqli_error($mysqli);
   }

}

function create_users($mysqli){
   // sql to create table
   $sql = "CREATE TABLE IF NOT EXISTS users ( 
      id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
      name VARCHAR(255),
username VARCHAR(255) NOT NULL,
email VARCHAR(255),
password VARCHAR(255) NOT NULL,
created_at TIMESTAMP DEFAULT '0000-00-00 00:00:00', 
updated_at TIMESTAMP DEFAULT now() ON UPDATE now()
);"; 

   if (mysqli_query($mysqli, $sql)) {
      echo "Table users created successfully <br>";
   } else {
      echo "Error creating table: " . mysqli_error($mysqli);
   }

}
$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
// check connection 
if (mysqli_connect_errno()) 
{
   $feedback = "Connect failed";
   $_SESSION['Error'] = $feedback;
}
# create_users($mysqli);
#create_questions($mysqli);
#create_user_answers($mysqli);
#insert_questions($mysqli);
create_roles($mysqli);
create_user_roles($mysqli);
insert_roles($mysqli);
mysqli_close($mysqli);
?>



