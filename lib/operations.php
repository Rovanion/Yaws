<?php
require_once "../lib/libAuth.php";
require_once "../lib/libJoy.php";

print_r($_REQUEST);

if($loggedIn){
  if(isset($_REQUEST["table"]))
      $table = mysql_real_escape_string($_REQUEST["table"]); 
  else 
    $table = 'pages';

  //The fallowing creates a new entry on the page
  if($_REQUEST["submit"] == "Publicera"){
    $numberOfPosts = getNextIndex($table);
    $parent = mysql_real_escape_string($_REQUEST["parent"]);
    
    
    if($_REQUEST["type"] == 0){
      $title = mysql_real_escape_string($_REQUEST["title"]);
      $JStitle = cleanForJavaScript($title);
      $content = mysql_real_escape_string($_REQUEST["content"]);
      $type = mysql_real_escape_string($_REQUEST["type"]);
      
      //To make sure that no empty fields are added to the table
      if($title == '' || $content == '' || $JStitle == ''){
	die('Du måste placera minst ett alphanumerisk tecken i både titel och innehållsfältet');
      }
      
      $sql = "INSERT INTO `joypeak`.`{$table}` 
           (`index` ,`type` ,`title` ,`JStitle` ,`content` ,`parent`)
           VALUES ('{$numberOfPosts}', '{$type}', '{$title}', 
           '{$JStitle}', '{$content}', '{$parent}');";
    }
    else if($_REQUEST["type"] == 1){
       $sql = "INSERT INTO `joypeak`.`{$table}` 
           (`index` ,`type` ,`parent` ,`JStitle`)
           VALUES ('{$numberOfPosts}', '1', '{$parent}', '{$numberOfPosts}');";
    }
    else if($_REQUEST["type"] == 2){
      $content = mysql_real_escape_string($_REQUEST["content"]);
      $JStitle = cleanForJavaScript(substr($content, 0, 32));    
      $type = mysql_real_escape_string($_REQUEST["type"]);
      
      //To make sure that no empty fields are added to the table
      if($title == '' || $JStitle == ''){
	die('Du måste placera minst ett alphanumerisk tecken i både titel och innehållsfältet');
      }
      
      $sql = "INSERT INTO `joypeak`.`{$table}` 
           (`index` ,`type` ,`title` ,`JStitle` ,`content` ,`parent`)
           VALUES ('{$numberOfPosts}', '{$type}', '{$title}', 
           '{$JStitle}', '{$content}', '{$parent}');";
    }
    else if($_REQUEST["type"] == 3){
      //The stuff that enters an image into the database
    }
    else if($_REQUEST["type"] == 4){
      $title = mysql_real_escape_string($_REQUEST["title"]);
      $JStitle = cleanForJavaScript($title);
      $type = mysql_real_escape_string($_REQUEST["type"]);

      //To make sure that no empty fields are added to the table
      if($title == '' || $JStitle == ''){
	die('Du måste placera minst ett alphanumerisk tecken i titeln');
      }

      $sql = "INSERT INTO `joypeak`.`{$table}` 
           (`index` ,`type` ,`title` ,`JStitle` ,`parent`)
           VALUES ('{$numberOfPosts}', '{$type}', '{$title}', 
           '{$JStitle}', '{$parent}');";
    }
    $success = mysql_query($sql);
    if($success) echo '1';
    else reportError($sql);
  }

  //The fallowing updates an entry on a page
  else if($_REQUEST["submit"] == "Spara"){
    $table = mysql_real_escape_string($_REQUEST["table"]);
    $type = mysql_real_escape_string($_REQUEST["type"]);
    
    if($type == 1 || $type == 2){
      $title = mysql_real_escape_string($_REQUEST["title"]);
      $JStitle = cleanForJavaScript($title);
      $content = mysql_real_escape_string($_REQUEST["content"]);
      $index = mysql_real_escape_string($_REQUEST["index"]);

      //To make sure that no empty fields are added to the table
      if($title === '' || $content === '' || $JStitle === '')
	die('Du måste placera minst ett alphanumerisk tecken i både titel och innehållsfältet');
      $sql = "UPDATE `joypeak`.`{$table}` SET 
            `title` = '{$title}', `JStitle` = '{$JStitle}', `content` = '{$content}' 
            WHERE `{$table}`.`index` = {$index};";
    }  
    if($type == 4){
      $title = mysql_real_escape_string($_REQUEST["title"]);
      $JStitle = cleanForJavaScript($title);
      $index = mysql_real_escape_string($_REQUEST["index"]);

      //To make sure that no empty fields are added to the table
      if($title === '' || $JStitle === '')
	die('Du måste placera minst ett alphanumerisk tecken i titeln');
      $sql = "UPDATE `joypeak`.`{$table}` SET 
            `title` = '{$title}', `JStitle` = '{$JStitle}' 
            WHERE `{$table}`.`index` = {$index};";
    }

    if(mysql_query($sql)) echo 1;
    else reportError($sql);
  }
  //The fallowing creates a whole new page
  else if($_REQUEST["submit"] == "Skapa"){
    $numberOfPosts = getNextIndex($table);
    $title = mysql_real_escape_string($_REQUEST["title"]);
    $JStitle = cleanForJavaScript($title);
    
    if($title === '' || $JStitle === ''){//To make sure that no empty fields are added to the table
      die('Du måste placera minst ett alfanumerisk tecken i sidonamnet');
    }
    $sql = "INSERT INTO `joypeak`.`pages` (`index`, `title`, `JStitle`) 
            VALUES ('{$numberOfPosts}', '{$title}', '$JStitle');";
    $success = mysql_query($sql);
    if(!$success) {
      reportError($sql);
	die('Inserting the new page into the database failed');
    }
    $sql = "CREATE TABLE IF NOT EXISTS `". $JStitle. "` (
        `index` tinyint(4) NOT NULL COMMENT 'The order of posts',
        `type` tinyint(2) NOT NULL COMMENT 'The type of the content',
        `title` text CHARACTER SET utf8 COLLATE utf8_bin COMMENT 'The title of the post',
        `JStitle` text CHARACTER SET utf8 COLLATE utf8_bin COMMENT 'The cleaned title',
        `content` text CHARACTER SET utf8 COLLATE utf8_bin COMMENT 'The content of the post',
        `parent` text CHARACTER SET utf8 COLLATE utf8_bin COMMENT 'The parent of the object',
        PRIMARY KEY (`index`), UNIQUE KEY `JStitle` (`JStitle`(50))
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
    $success = mysql_query($sql);
    if($success) header("Location: ../pages/index.php?page=". $table);
    else reportError($sql);
  }

  //Deletes the requested entry
  else if($_REQUEST["submit"] == "Delete"){
    $JStitle = mysql_real_escape_string($_REQUEST["JStitle"]);
    $table = mysql_real_escape_string($_REQUEST["table"]);
    $sql = "DELETE FROM `joypeak`.`{$table}` WHERE `JStitle` = '{$JStitle}'";
    $success = mysql_query($sql);
    if(!$success) reportError($sql);
    else echo '1';
  }
  else if($_REQUEST["submit"] == "Register"){
    registerNewUser();
  }
  else if($_REQUEST["submit"] == "Register"){
   validateNewUser();
  }
}
else if($_REQUEST["submit"] == "Register"){
  $sql = "SELECT * FROM `users`";
  $result = mysql_query($sql);
  if(mysql_num_rows($result) == 0)
    registerNewUser();
}
else if($_REQUEST["submit"] == "Verification"){
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
       Click <a href="../pages/login.php">here</a> to go to the login page.</p>');
}

function reportError($sql){
  echo '<p>Följande indata gavs: <br />';
  print_r($_REQUEST);
  echo "<br /><br />A tiny tiny error accured when trying to add the new entry: <br /><br /><b>". 
    mysql_error(). ': </b><br /><br />'. $sql;
  die();
}

function getNextIndex($table){
  $sql = "SELECT MAX(`index`) FROM `{$table}`";
  $posts = mysql_query($sql);
  if (!$posts) 
    reportError($sql);
  $row = mysql_fetch_row($posts);
  $numberOfPosts = $row[0] + 1;
  return $numberOfPosts;
}

function registerNewUser(){
  $email = mysql_real_escape_string($_REQUEST["email"]);
  $validated = filter_var($email, FILTER_VALIDATE_EMAIL);
  if($validated){ //Check if the email is correct                       
    $token = createToken(32);
    $sql = "INSERT INTO `verification` (`email` ,`token`) VALUES ('{$email}', '{$token}');";
    if(!mysql_query($sql))
      reportError($sql);
    
    //FIXME The message and domain name should be variable.
    $message = "Var hälsad! \n \n Åt dig har det skapats ett administrativt konto på rovanion.dyndns.org \n".
      "Var god besök följande adress för att aktivera kontot: \n ".
      "http://rovanion.dyndns.org/joypeak/pages/verification.php?email=". $email . '&token='. $token;
    if(!mail($email, 'Verification of administrational account', $message))
      echo 'Sending the verification email failed';
    else
      echo '1';
  }
}

function validateNewUser(){
  $email = mysql_real_escape_string($_GET["email"]);
  $token = mysql_real_escape_string($_GET["token"]);
  $password = mysql_real_escape_string($_GET["password"]);
  $md5password = md5($password);
  $sql = "SELECT * FROM  `verification` WHERE  `email` 
          LIKE  '{$email}' AND  `token` LIKE  '{$token}'";
  $result = mysql_query($sql);
  
  if(mysql_num_rows($result) == 1){
    $sql = "INSERT INTO `users` (`email`, `password`, `active`) 
          VALUES ('{$email}', '{$md5password}', '1');";
    if(!mysql_query($sql))
      reportError($sql);
    $sql = "DELETE FROM `verification` WHERE `email` = '{$email}'";
    if(!mysql_query($sql))
      reportError($sql);
    else{
      $_REQUEST["email"] = $email;
      $_REQUEST["password"] = $password;
      checkLogin();
      echo '1';
    }
  }
  else echo 'Your verification values are incorrect: ' + $email + $token;
}

function createToken($length) {
  $allowedChars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ012345689';
  $bytes = mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);
  $token = '';
  foreach(str_split($bytes) as $byte) {
    $token .= $allowedChars[ord($byte) % strlen($allowedChars)];
  }
  return $token;
}
 ?>