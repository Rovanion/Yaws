<?php
require_once '../lib/libJoy.php';
require_once '../lib/libAuth.php';

if(!isset($_POST["submit"])){
  if(isset($_GET["page"]))
    $table = mysql_real_escape_string($_GET["page"]);    //If there is a page variable, use it
  else 
    $table = "Program";        //Otherwise go to the main page
  $parentToMatch = 'Page';
  
  echo '<ul>';
  createContent('');
  echo '</ul>';

  if($loggedIn){
    echo '<br /><br /><br />';
    makeAddButton('Page', $table);
  }
}

//Function called to print out the content from an MySQL table
//$table with the parent $parentToMatch.
function createContent($secondClass){
  global $parentToMatch;
  global $table;
  $sql = "SELECT * FROM `{$table}` WHERE parent='{$parentToMatch}' ORDER BY `index`";
  $result = mysql_query($sql);

  if($result == false || mysql_num_rows($result) == 0){ 
    return;
  }
  else{
    while($post = mysql_fetch_assoc($result)){
      global $loggedIn;
      $id = $post["JStitle"];
      
      echo '<li class="contentHolder" id="'. $id. 'ContentHolder">';
      if($post["type"] == 0){
	echo'<h2 class="X" id="'. $id. '">'. $post["title"]. '</h2>';
	if($loggedIn)
	  makeAdminInterface($post, 0);
	echo '<div class="hidden '. $secondClass. '" id="'. $id. 'Div">'.
	  '<p id="'. $id. 'Text">'. $post["content"]. '</p></div>';
      }
      else if($post["type"] == 1){
	echo '<div class="openCloseContainer">
          <div class="button open" id="'. $id. 'OpenButton"><h3>Öppna alla</h3></div>
          <div class="button close" id="'. $id. 'CloseButton"><h3>Stäng alla</h3></div></div>';
	if($loggedIn)
	  makeAdminInterface($post, 1);
      }
      else if($post["type"] == 2){
	echo '<h3 id="'. $id. '">'. $post["title"]. '</h3>';
	if($loggedIn)
	  makeAdminInterface($post, 2);
	echo '<p id="'. $id .'Text">'. $post["content"]. '</p>';
      }
      else if($post["type"] == 4){
	echo '<h2 class="X kategoriHeader" id="'. $id. '">'. $post["title"]. '</h2>';
	if($loggedIn)
	  makeAdminInterface($post, 4);
	echo '<div class="hidden kategori '. $secondClass. '"  id="'. $id. 'Div">';
	$parentToMatch = $post["JStitle"];
	createContent('child');    //Calls itself to see if there are any children.
	if($loggedIn)
	  makeAddButton($id);
	echo '</div>';
      }
      echo '</li>';
    } 
  }
}

//Function for creating the administrational interface, called from createContent()
function makeAdminInterface($post, $type){
  global $table;
  $id = $post["JStitle"];
  
  if($type == 0 || $type == 2){
    echo '<div class="button edit" id="'. $id. 'EditButton"><h3>Edit</h3></div>'.
      '<div class="deleteContainer"><div class="button delete" id="'. $id. 'DeleteButton">'.
      '<h3>Delete</h3></div></div>';
    
    echo '<div class="yesNoContainer" id="'. $id. 'yesNoContainer"><h5>Är du säker?</h5>
          <div class="button yes" id="'. $id. 'yesButton"><h3>Ja</h3></div>
          <div class="button no" id="' . $id. 'noButton"><h3>Nej</h3></div></div>';
    
    echo '<div class="formContainer" id="'. $id. 'FormContainer">
            <form id="'. $id. 'Form" method=post action="../lib/operations.php">
                <label for=title> Titel: </label> 
       	        <input type="text" name="title" value="'. $post["title"]. '">
       		<label for=content> Brödtext: </label> 
	        <textarea name=content>'. $post["content"]. '</textarea>
                <input type="hidden" name="index" value="'. $post["index"]. '" />
                <input type="hidden" name="table" value="'. $table. '" />
                <input type="hidden" name="submit" value="Spara">
    	    </form>
            <div class="button submit" id="'. $id. 'SubmitButton"><h3>Spara</h3></div>
            <div class="button cancelButton"><h3>Avbryt</h3></div>
        </div>';
  }
  else if($type == 1){
    echo '<div class="deleteContainer"><div class="button delete" id="'. $id. 'DeleteButton">'.
      '<h3>Delete</h3></div></div>'.
      '<div class="yesNoContainer" id="'. $id. 'yesNoContainer"><h5>Är du säker?</h5>'.
      '<div class="button yes" id="'. $id. 'yesButton"><h3>Ja</h3></div>'.
      '<div class="button no" id="' . $id. 'noButton"><h3>Nej</h3></div></div>';
  }
  else if($type == 4){
    echo '<div class="button edit" id="'. $id. 'EditButton"><h3>Edit</h3></div>'.
       '<div class="deleteContainer"><div class="button delete" id="'. $id. 'DeleteButton">'.
       '<h3>Delete</h3></div></div>';
  
    echo '<div class="yesNoContainer" id="'. $id. 'yesNoContainer"><h5>Är du säker?</h5>'.
       '<div class="button yes" id="'. $id. 'yesButton"><h3>Ja</h3></div>'.
       '<div class="button no" id="' . $id. 'noButton"><h3>Nej</h3></div></div>';
    
    echo '<div class="formContainer" id="'. $id. 'formContainer">
        	<form method=post action="../lib/operations.php">
		    <label for=title> Titel: </label> 
	      	    <input type="text" name="title" value="'. $post["title"]. '">
                    <input type="hidden" name="table" value="'. $table. '" />
                    <input type="hidden" name="index" value="'. $post["index"]. '" />
                    <input type="hidden" name="submit" value="Spara">
		</form>
                <div class="button submit" id="'. $id. 'SubmitButton"><h3>Spara</h3></div>
                <div class="button cancelButton"><h3>Avbryt</h3></div>
            </div>';
  }  
}

function makeAddButton($id){
  global $table;
  echo '<select name="type" class="type" id="'. $id. 'Type">
            <option value="0">Titel med gömd paragraf</option>
            <option value="1">Öppna och Stäng alla knappar</option>
            <option value="2">Titel med paragraf</option>
            <option value="3">Bild med undertext</option>
            <option value="4">Huvudkategori</option>
        </select>
        <div class="button newButton" id="'. $id. 'NewButton"><h3>Lägg till
        </h3></div>
        <div class="formContainer new" id="'. $id. 'InputContainer">
            <form method=post action="../lib/operations.php" class="newForm" id="'. $id. 'NewForm">
                <input class="table" type="hidden" name="table" value="'. $table. '" />
                <input type="hidden" name="parent" value="'. $id .'" />
            	<label for=titel> Titel: </label> 
       		<input type=text name=title>
		<div class="textarean">
                    <label for=content> Brödtext: </label> 
		    <textarea name=content></textarea>
                </div>
		<input type="hidden" name="submit" value="Publicera">
	    </form>
        <div class="button submit" id="'. $id. 'SubmitButton><h3>Skapa</h3></div>
        <div class="button cancelButton"><h3>Avbryt</h3></div>
        </div>';
}

?>