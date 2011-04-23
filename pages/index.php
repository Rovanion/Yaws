<?php 
require_once '../lib/libAuth.php';
require_once '../lib/libJoy.php';
require_once '../lib/settings.php';

if($loggedIn && DefaultPage == 'NotSet'){
  header('Location:../pages/theAddAPagePage.php');
}

makeHead('YAWS - Den där filmen som baklänges handlar om en haj 
som spyr upp människor tills dom öppnar en strand ');

echo '<body'; if($loggedIn) echo ' class="loggedIn"'; echo'>';
?>
<table>
<tr>
<td id="header">
<div id="head">
<h1>Yet Another WebSite</h1>
<div class="bottom"></div>
</div>
</td>
</tr>
<tr>
<td id="content">
<?php 
makeMenu();    //Located in ../lib/libJoy.php
?>
<div id="main">
<div id="content2">
<?php

include '../lib/content.php'; //Creates theb main content of the page

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
  Telefon: 0910<br />
  Fax: 0910-32822<br />
  Adress: Spång. 5<br />
  Herpa Derpa
</h6>
</div>
<div class="spacer"></div>
<div class="foot"><h6>
  Design <br />
  Rovanion Luckey
</h6>
</div>
<div class="spacer"></div>
<div class="foot"><h6>
  Annan <br />
  intressant <br />
  information
</h6>
</div>
<div class="spacer"></div>
<div class="foot">
<?php
if(!$loggedIn){
?>
<h6 class="link admin">Administrera</h6>
<div class="loginBox"><form action="../pages/index.php" method="post">
        <label for="Anv"><h6>Epostadress</h6></label><br />
        <input class="login" type="input" name="email" title="Epostadress"><br />
	<label for="Pass1"><h6>Lösenord</h6></label><br />
	<input class="login" type="password" name="password" title="Lösenord"><br />
	<input class="login" type="submit" value="Login">
</form></div>
<?php
}
else{
  echo '<a href="../pages/register.php"><h6>Lägg till Admin</h6></a>';
  echo '<a href="../pages/logout.php"><h6>Logga ut</h6></a>';
}
?>
</div>
</div>
<td>
</tr>
</table>
</body>