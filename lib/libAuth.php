<?php 
require_once '../lib/settings.php';

session_start();
$loggedIn = checkLogin(); //$loggedIn is true if the user is logged in, else false

function makeMySQLConnection(){
  //Connects to MySQL at localhost with username and password from ../lib/settings.php
  mysql_connect('localhost', DatabaseUserName, DatabasePassword) or
    die('MySQL connection failed. You must enter your password in ../lib/settings.php 
         accordingly to your setup. Either that or your database just broke.'.
	mysql_error());

  if(!mysql_select_db(Database)){    //The database defined in ../lib/settings.php is selected, else created.
    $sql = "CREATE DATABASE `".Database."`";
    mysql_query($sql) or
      die('Selecting the database '. Database. ' failed and subsequently creating a new database
           with that name also failed. Something in your setup is broken.'. 
	  mysql_error());
    
    $sql = "CREATE TABLE `".Database."`.`users` (
           `email` VARCHAR( 128 ) NOT NULL ,
           `password` VARCHAR( 32 ) NOT NULL ,
           `active` TINYINT( 1 ) NULL ,
            PRIMARY KEY ( `email` )
           ) ENGINE = MYISAM DEFAULT CHARSET=utf8 COMMENT = 
           'The table containing the site administrators and their encrypted passwords';";
    mysql_query($sql) or
      die('Selecting the database joypeak failed and subsequently creating a new table
           with the name <b>users</b> in the database joypeak. 
           Something in your setup is broken.'. mysql_error());

    $sql = "CREATE TABLE `".Database."`.`pages` (
           `index` TINYINT NOT NULL COMMENT 'A unique number, the order of links',
           `title` TEXT NOT NULL COMMENT 'The shown title',
           `JStitle` TEXT NOT NULL COMMENT 'The cleaned title for code',
            PRIMARY KEY ( `index` )
           ) ENGINE = MYISAM COMMENT = 'The table containing the pages of the website';";
    mysql_query($sql) or
      die('Selecting the database joypeak failed and subsequently creating a new table
           with the name <b>pages</b> in the database joypeak. 
           Something in your setup is broken.'. mysql_error());

    $sql = "CREATE TABLE  `".Database."`.`verification` (
           `email` VARCHAR( 64 ) NOT NULL COMMENT  'The email adress',
           `token` VARCHAR( 32 ) NOT NULL COMMENT  'The verification code'
           ) ENGINE = MYISAM COMMENT =  'Table used for verifying accounts';";
    mysql_query($sql) or
      die('Selecting the database joypeak failed and subsequently creating a new table
           with the name <b>verification</b> in the database joypeak. 
           Something in your setup is broken.'. mysql_error());
    
    mysql_select_db(Database) or
      die('Selecting the database '.Database.' after creating it failed in 
           ../lib/libAuth.php'. mysql_error());
  }
}

function checkLogin(){
  makeMySQLConnection();
  
  if (isset($_POST["email"]))
    login();
  
  if (isset($_SESSION["email"]) && isset($_SESSION["password"])){
    $sql = "SELECT * FROM users WHERE email='{$_SESSION['email']}' 
	    AND password='{$_SESSION['password']}'";
    $result = mysql_query($sql);
    $array = mysql_fetch_assoc($result);
    
    if($_SESSION["email"] == $array["email"] && $_SESSION["password"] == $array["password"]){
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

    $result = mysql_query($sql) or //Run query and continue if it succeeds, or print the error.
      print "Falken anfaller! Följande SQL-sats är felaktig:<br />". $sql.
	    '<br /><br /> Följande felmeddelande gavs:'. mysql_error();
		
    if (mysql_num_rows($result) == 1){
      $_SESSION["email"] = $_POST["email"];
      $_SESSION["password"] = $md5password;
    }
    else
      echo "<br />Du loggades inte in. Detta är en ful svart text.";
  }
}

?>