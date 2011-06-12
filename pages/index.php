<?php 
require_once '../lib/libAuth.php';
require_once '../lib/libJoy.php';
require_once '../lib/settings.php';

if($loggedIn && DefaultPage == 'NotSet'){
  header('Location:../pages/theAddAPagePage.php');
}

makeHead('Joypeak Live ');

echo '<body'; if($loggedIn) echo ' class="loggedIn"'; echo'>';
?>
<table>
<tr>
<td>
<div class="logo"></div>
</td>
</tr>
<tr>
<td id="content">
<div id="main">
<div id="content2">
<?php

include '../lib/content.php'; //Creates theb main content of the page

?>
</div>
</div>
<div id="nextToBottom"></div>
<div id="bottom">

</div>
</td>
</tr>
<tr>
<td>

<?php
if(!$loggedIn){
?>
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

<td>
</tr>
</table>
</body>
