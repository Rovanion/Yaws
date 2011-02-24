<?php
 include '../lib/libAuth.php';
 include '../lib/libJoy.php';

if($loggedIn){
  header('../pages/index.php');
}

 makeHead('Joypeak - Där allting är gratis förutom maten ');
?>
<table>
	<tr>
		<td id="header">
			<div id="head">
			<h1>Inloggning!</h1>
			</div>
		</td>
	</tr>
	<tr>
		<td id="content">
		<?php makeMenu()?>
			<div id="main">
				<div id="content2">
                                    <h1>Tänk noga, skriv rätt</h1>
					<form action="../pages/index.php" method="post">
						<label for="Anv">Användarnamn:</label><br />
						<input type="input" id="Anv" name="user" title="Användarnamn"><br /><br />
						<label for="Pass1">Lösenord:</label><br />
						<input type="password" id="Pass1" name="password" title="Lösenord"><br />
						<input type="submit" value="Login"></input>
					</form>
					<script>document.getElementById('Anv').focus();</script>
					<br />
                                </div>
			</div>
			<div id="bottom"></div>
		</td>
	</tr>
</table>
</body>