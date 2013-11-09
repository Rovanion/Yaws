<?php
require_once "../lib/libAuth.php";
requite_once "../lib/operationFunctions.php";

if($loggedIn){
  if(isset($_POST["table"])){
    $table = mysql_real_escape_string($_POST["table"]);

    if($_POST["submit"] == "Publicera")
      publishEntry($table);
    else if($_POST["submit"] == "Spara")
      updateEntry($table);
    else if($_POST["submit"] == "Delete")
      deleteEntry($table);
    else if($_POST["submit"] == "Register")
      registerNewUser();
    else if($_POST["submit"] == "Validate")
      validateNewUser();
  }
  else if($_POST["submit"] == "Skapa"){
    $title = mysql_real_escape_string($_POST["title"]);
    createPage($table, $title);
  }
}

// The fallwing two cases are only used to make the first account
else if($_POST["submit"] == "Register"){
  $sql = "SELECT * FROM `users`";
  $result = mysql_query($sql);
  if(mysql_num_rows($result) == 0)
    registerNewUser();
}
else if($_POST["submit"] == "Verification"){
  $sql = "SELECT * FROM `users`";
  $result = mysql_query($sql);
  if(mysql_num_rows($result) == 0)
    validateNewUser();
}
else{
  die('<p>You must be logged in to perform any operations. If you once were logged in,
       your session probably timed out meaning that you have to log in again before
       you try to perform any administrational operations.
       <br /> <br />
       Click <a href="../pages/login.php">here</a> to go to the login page.</p>
       <br /><br />
       '. var_dump($_POST));
}
?>
