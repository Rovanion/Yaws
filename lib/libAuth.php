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
  return true;
}


?>