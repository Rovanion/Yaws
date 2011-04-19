<?php 
function makeHead($title){    //This is the function that makes the head of the page
  global $loggedIn;

  echo('<!DOCTYPE HTML>
	<html>
            <head>
	    <meta http-equiv="Content-Type" content="text/html" charset="utf-8">
	    <link href="../style/joySheet.css" rel="stylesheet" type="text/css">
            <link rel="icon" href="../img/favicon.png">
            <title>'. $title. '</title>
	    	
	    <script src="../lib/jquery.js" type="text/javascript" language="javascript"></script>
	    <script src="../lib/jquery.easing.js" type="text/javascript" language="javascript"></script>
            <script src="../lib/joyScript.js" type="text/javascript" language="javascript"></script>
            <!-- Remove jquery.lint.js on production setups -->
            <!-- <script src="../lib/jquery.lint.js" type="text/javascript" language="javascript"></script> -->');

  if($loggedIn){
    echo('<script src="../lib/nicEdit.js" type="text/javascript" language="javascript"></script> 
          <script src="../lib/adminScript.js" type="text/javascript" language="javascript"></script>');
  }
  echo '</head>';
}

function makeMenu(){    //Function that creates the main menu
  global $loggedIn;    //Defined in ../lib/libAuth.php

  $sql = "SELECT * FROM `pages` ORDER BY `index`";
  $posts = mysql_query($sql) or print("MySQLQuery bailed in ../lib/libJoy.php: ". mysql_error());

  echo '<div id="top">';
  while($post = mysql_fetch_assoc($posts)){
    if($post["index"] == 1){    //If it's the first menu item, give it the class left
      echo '<div class="menuItem left"><a href="index.php?page='. $post["JStitle"]. 
	'"><h4>'. $post["title"]. '</h4></a></div>';
    }
    else{
      echo '<div class="menuItem"><a href="index.php?page='. $post["JStitle"]. 
	  '"><h4>'. $post["title"]. '</h4></a></div>';
    }
  }
  if($loggedIn){
    echo '<div class="menuItem"><a href="../pages/theAddAPagePage.php"><h4>+</h4></a> + </div>';
  }
  echo  '<div class="menuItem right"><a href="../pages/nonExisting.php"><h4>Frivillig</h4></a></div>
 	 </div>';

  if(mysql_num_rows($posts) == 0){
    $sql = "SELECT * FROM `users`";
    $rows = mysql_query($sql);
    if(mysql_num_rows($rows) == 0){
      echo '<script type="text/javascript">window.location = "../pages/register.php"</script>';
      die();
    }
  }
}

//cleanForJavaScript is the function that is called to clean up titles and such before they, most likely,
//are put into a database to later be used for unique ID's of HTML elements. This is done so that the
//ID's once in place in the HTML can be used by JavaScript to manipulate the page.
function cleanForJavaScript($string){
  $string = str_replace(' ', '', $string);
  $string = str_replace('!', '', $string);
  $string = str_replace('/', '', $string);
  $string = str_replace(':', '', $string);
  $string = str_replace(';', '', $string);
  $string = str_replace('&', '', $string);
  $string = str_replace('(', '', $string);
  $string = str_replace(')', '', $string);
  $string = str_replace('.', '', $string);
  $string = str_replace(',', '', $string);
  $string = str_replace('-', '', $string);
  $string = str_replace('+', '', $string);
  $string = str_replace('#', '', $string);
  $string = str_replace('¤', '', $string);
  $string = str_replace('%', '', $string);
  $string = str_replace('=', '', $string);
  $string = str_replace('?', '', $string);
  $string = str_replace('´', '', $string);
  $string = str_replace('`', '', $string);
  $string = str_replace('\'', '', $string);
  $string = str_replace('*', '', $string);
  $string = str_replace('<', '', $string);
  $string = str_replace('>', '', $string);
  $string = str_replace('@', '', $string);
  $string = str_replace('£', '', $string);
  $string = str_replace('$', '', $string);
  $string = str_replace('€', '', $string);
  $string = str_replace('¥', '', $string);
  $string = str_replace('{', '', $string);
  $string = str_replace('[', '', $string);
  $string = str_replace(']', '', $string);
  $string = str_replace('}', '', $string);
  $string = str_replace('\\', '', $string);
  $string = str_replace('^', '', $string);
  $string = str_replace('±', '', $string);
  $string = str_replace('§', '', $string);
  $string = str_replace('½', '', $string);
  $string = str_replace('¶', '', $string);
  $string = str_replace('~', '', $string);
  
  return $string;
}

?>
