<?php
require_once '../lib/libAuth.php';
require_once '../lib/libJoy.php';

makeHead('Joypeak - Skapa nya konton');

echo '<body'; if($loggedIn) echo ' class="loggedIn"'; echo'>';
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
<?php
							//makeMenu();    //Creates the main menu
?>
<div id="main">
<div id="content2">

<form action="../lib/operations.php" method="post">
  <label for="email"><h3>Epostadress</h3></label><br />
  <input type="input" id="email" name="email" title="email"><br />
  <input type="hidden" name="submit" value="Register">
  <input type="submit" value="Register"></input>
</form><br />

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
        <label for="Anv">Epostadress</label><br />
        <input class="login" type="input" name="email" title="Epostadress"><br />
        <label for="Pass1">Lösenord</label><br />
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
