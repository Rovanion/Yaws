<?php 
include '../lib/libAuth.php';
include '../lib/libJoy.php';

//if(!$loggedIn} header("Location:../pages/index.php"); //Om besökaren inte är inloggad, kasta ut honom!

makeHead('The Add A Page Page - Where pages are born');

?>

<table>
<tr>
<td id="header">
  <div id="head">
  <h1>A new page begins</h1>
  <div class="bottom"></div>
  </div>
  </td>
  </tr>
  <tr>
  <td id="content">
   <?php makeMenu();    // Creates the main menu ?>
  <div id="main">
  <div id="content2">
  <br />  <br />  <br />  <br />
  <form method="post" action="../lib/operations.php">
      <label for="titel"><h2><b> Titel: </b></h2> </label> <br />
      <input type="text" name="title"><br />
      <input type="submit" name="submit" value="Skapa">
  </form>	
  <br />  <br />  <br />  <br />
  </div>				
  </div>
  <div id="bottom">
<?php 
  if($loggedIn) echo '<a href="../pages/logout.php"> Logga ut </a>';
  else echo '<a href="../pages/login.php">Administrera</a>';
?>
</div>
</td>
</tr>
</table>
</body>