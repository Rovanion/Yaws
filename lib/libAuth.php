<?php 
session_start();
$loggedIn = checkLogin();

function makeMySQLConnection(){
  mysql_connect('localhost', 'root', 'underbar') or
    die('MySQL connection failed. You must correctly edit the makeMySQLConnection() function in 
         ../lib/libAuth.php accordingly to your setup. Either that or your database just broke.');
  mysql_select_db('joypeak') or 
    die('Selecting the table joypeak in your database failed. You must correctly edit the 
         makeMySQLConnection() function in ../lib/libAuth.php accordingly to your setup, 
         or create the missing database. Either that or your database just broke.');
}

function checkLogin(){
  makeMySQLConnection();
  
  if (isset($_POST["email"]))
    login();
  
  if (isset($_SESSION["email"]) && isset($_SESSION["password"])){
    $sql = "SELECT * FROM users WHERE email='{$_SESSION["email"]}' 
	    AND password='{$_SESSION["password"]}'";
    $result = mysql_query($sql);
    $array = mysql_fetch_assoc($result);
    
    if($_SESSION["email"] == $array["username"] && $_SESSION["password"] == $array["password"]){
      return true;
    }
  }
  else return false;
}

function login(){
  global $loggedIn;
  if($loggedIn)
    return;
  
  if(isset($_POST["email"]) && isset($_POST["password"])){
    $password = mysql_real_escape_string($_POST["password"]);
    $user = mysql_real_escape_string($_POST["email"]);
    $md5password = md5($password);
    $sql = "SELECT * FROM users WHERE email='{$user}' 
		AND password='{$md5password}'";
    $result = mysql_query($sql);
    
    if ($result == false)		// Checks for SQL issues
      print "Falken anfaller! Följande SQL-sats är felaktig:<br />". $sql.
	'<br /><br /> Den gör följande error:'. mysql_error();
    
    $count = mysql_num_rows($result);
		
    if ($count == 1){
      $_SESSION["email"] = $_POST["email"];
      $_SESSION["password"] = $md5password;
    }
    else
      echo "<br />Du loggades inte in. Detta är en ful svart text.";
  }
}

?>