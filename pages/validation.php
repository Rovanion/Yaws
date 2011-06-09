<?php
require_once '../lib/libAuth.php';
require_once '../lib/libJoy.php';

makeHead('Joypeak - Account validation');

echo '<body'; 
if($loggedIn) 
  echo ' class="loggedIn"'; 
echo'>';
?>
<table>
<tr>
<td id="header">
<div id="head">
<h1>Joypeak</h1>
<div class="bottom"></div>
</div>
</td>
</tr>
<tr>
<td id="content">

<div id="main">
<div id="content2">
<?php

$email = mysql_real_escape_string($_GET["email"]);
$token = mysql_real_escape_string($_GET["token"]);
$sql = "SELECT * FROM `verification` 
        WHERE `email` LIKE '{$email}' AND `code` LIKE '{$token}'";
$rows = mysql_query($sql);
if(mysql_num_rows($rows) == 1){
  ?>
  <h2>Ange lösenord för ditt konto</h2>
  <form>
        <label for="pass1">Lösenord</label><br />
        <input type="password" name="pass1" title="Lösenord1"><br />
        <label for="pass2">Upprepa lösenord</label><br />
        <input type="password" name="pass2" title="Lösenord2"><br />
        <div class="button" id="VerifyButton">Skapa konto och logga in</div>
  </form>
  <?php
}
else 
  echo 'An error occured while verifying your account';

?>
</div>
</div>
<div id="bottom">
</div>
</td>
</tr>
<tr>
<td>
<div id="footer">
<div class="foot"><h6>
  Kontakt <br />
  Telefon: 0910-32883<br />
  Fax: 0910-32844<br />
  Adress: Spångatan 5<br />
  Herpa Derpa Merpa
</h6>
</div>
<div class="spacer"></div>
<div class="foot"><h6>
  Design <br />
  Rovanin Luckey
</h6>
</div>
<div class="spacer"></div>
<div class="foot"><h6>
  Joypeak <br />
  Den 5e Juni <br />
  Klockan 09:00
</h6>
</div>
<div class="spacer"></div>
<div class="foot">
<?php
if(!$loggedIn){
?>
<h6 class="link admin">Administrera</h6>
<div class="loginBox"><form action="../pages/index.php" method="post">
        <label for="email">Epostadress</label><br />
        <input class="login" type="input" name="email" title="Epostadress"><br />
        <label for="password">Lösenord</label><br />
        <input class="login" type="password" name="password" title="Lösenord"><br />
        <input class="login" type="submit" value="Login">
</form></div>
<?php
}
else echo '<a href="../pages/logout.php">Logga ut</a>'
?>
</div>
</div>
<td>
</tr>
</table>
</body>
