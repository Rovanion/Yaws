
<?php
require_once '../lib/settings.php';
require_once '../lib/libJoy.php';
require_once '../lib/libAuth.php';


/*This file creates the content of each page. 
  Concider merging it with ../lib/libJoy.php and ../pages/index.php*/

if(isset($_GET["page"]))    //If there is a page variable, use it
  $table = mysql_real_escape_string($_GET["page"]);
else
  $table = DefaultPage;        //Otherwise go to the main page
$parentToMatch = 'Page';

echo '<ul>';
createContent('');
echo '</ul>';

if($loggedIn){
  makeAddButton('Page', $table);
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
      
      echo '<li class="contentListItem" id="'. $id. 'ContentListItem">';
      if($post["type"] != 1)
	echo '<div id="'. $id. 'NicEditPanel"></div>';

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
	echo '<p id="'. $id .'Text">'. $post["content"]. '</p>';
	if($loggedIn)
	  makeAdminInterface($post, 2);
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

  if($type == 0 || $type == 1 || $type == 2 || $type == 4){ //If it's a text field or open/closeall
    echo '<div class="deleteEditContainer" id="'. $id. 'DeleteEditContainer">';
    if($type != 1) 
      echo '  <div class="button edit" id="'. $id. 'EditButton"><h3>Edit</h3></div>';
    echo '    <div class="button delete" id="'. $id. 'DeleteButton"><h3>Delete</h3></div>
          </div>
          <div class="yesNoContainer" id="'. $id. 'YesNoContainer"><h5>Är du säker?</h5>
              <div class="button yes" id="'. $id. 'YesButton"><h3>Ja</h3></div>
              <div class="button no" id="' . $id. 'NoButton"><h3>Nej</h3></div>
          </div>';
    if($type != 1){
      echo '<div class="submitCancelContainer" id="'. $id. 'SubmitCancelContainer">
                <div class="button submit" id="'. $id. 'SubmitButton"><h3>Spara</h3></div>
                <div class="button cancelButton"><h3>Avbryt</h3></div>
            </div>
            <form id="'. $id. 'Form">
                <input type="hidden" name="index" value="'. $post["index"]. '" />
                <input type="hidden" name="table" value="'. $table. '" />
                <input type="hidden" name="submit" value="Spara">
                <input type="hidden" name="type" value="'. $type. '">
    	    </form>';
    }
  }
  else if($type == 3) echo 'FIX ME'; //FIXME
    
}

function makeAddButton($id){
  global $table;
  echo '<div class="newContainer">
          <select name="type" class="type" id="'. $id. 'Type">
            <option value="0">Titel med gömd paragraf</option>
            <option value="1">Öppna och Stäng alla knappar</option>
            <option value="2">Paragraf</option>
            <option value="3">Bild med undertext</option>
            <option value="4">Huvudkategori</option>
          </select>
          <div class="button newButton" id="'. $id. 'NewButton"><h3>Lägg till
        </h3></div></div>
        <div class="formContainer new" id="'. $id. 'FormContainer">
            <form id="'. $id. 'Form">
                <div class="titleContainer">
            	    <label for=title><h6>Titel</h6></label><br /> 
       		    <input type="text" name="title">
                </div><div class="contentContainer">
                    <label for="content"><h6>Brödtext</h6></label><br /> 
		    <textarea name="content" id="'. $id. 'TextArea"></textarea>
                </div>
		<input type="hidden" name="submit" value="Publicera">
                <input type="hidden" name="table" class="table" value="'. $table. '" />
                <input type="hidden" name="parent" value="'. $id .'" />
	    </form>
        <div class="button submit" id="'. $id. 'SubmitButton"><h3>Skapa</h3></div>
        <div class="button cancelButton"><h3>Avbryt</h3></div>
        </div>';
}

?>