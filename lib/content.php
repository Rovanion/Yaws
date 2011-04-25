<?php
require_once '../lib/settings.php';
require_once '../lib/libJoy.php';
require_once '../lib/libAuth.php';

/* Unless any arguments are sent with POST to this file it creates
   the whole content part of a page.*/

if(isset($_GET["page"]))    //If there is a page variable, use it
  $table = mysql_real_escape_string($_GET["page"]);
else
  $table = DefaultPage;        //Otherwise go to the main page
$parentToMatch = 'Page';

if(!isset($_POST['onePart'])){
  echo '<ul id="PageUnsortedList">';
  createContent('', 'wholePage');
  echo '</ul>';

  if($loggedIn){
    makeAddButton('Page', $table);
  }
}
else{
  createContent('', 'singleItem');
}


//Function called to print out the content from an MySQL table
//$table with the parent $parentToMatch.
function createContent($secondClass, $what){
  global $parentToMatch;
  global $table;

  if($what == 'wholePage')
    $sql = "SELECT * FROM `{$table}` WHERE parent='{$parentToMatch}' ORDER BY `index`";
  else if($what == 'singleItem'){
    $index = mysql_escape_string($_POST["index"]);
    $sql = "SELECT * FROM `{$table}` WHERE `index` ={$index}";
  }
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
	if($loggedIn)
	  makeAdminInterface($post, 0);
	echo'<h3 class="X" id="'. $id. '">'. $post["title"]. '</h3>';
	echo '<div class="hidden '. $secondClass. '" id="'. $id. 'Div">'.
	  '<p id="'. $id. 'Text">'. $post["content"]. '</p></div>';
      }
      else if($post["type"] == 1){
	if($loggedIn)
	  makeAdminInterface($post, 1);
	echo '<div class="openCloseContainer">
          <div class="button open" id="'. $id. 'OpenButton"><h4>Öppna alla</h4></div>
          <div class="button close" id="'. $id. 'CloseButton"><h4>Stäng alla</h4></div></div>';
      }
      else if($post["type"] == 2){
	if($loggedIn)
	  makeAdminInterface($post, 2);
	echo '<div id="'. $id .'Text">'. $post["content"]. '</div>';
      }
      else if($post["type"] == 4){
	if($loggedIn)
	  makeAdminInterface($post, 4);
	echo '<h3 class="X kategoriHeader" id="'. $id. '">'. $post["title"]. '</h3>';
	echo '<div class="hidden kategori '. $secondClass. '"  id="'. $id. 'Div">';
	$parentToMatch = $post["JStitle"];

	echo '<ul id="'. $id. 'UnsortedList">';
	createContent('child', 'wholePage');    //Calls itself to see if there are any children.
	echo '</ul>';

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
      echo '  <div class="button edit" id="'. $id. 'EditButton"><h4>Redigera</h4></div>';
    echo '    <div class="button delete" id="'. $id. 'DeleteButton"><h4>Ta bort</h4></div>
          </div>
          <div class="yesNoContainer" id="'. $id. 'YesNoContainer"><h5>Är du säker?</h5>
              <div class="button yes" id="'. $id. 'YesButton"><h4>Ja</h4></div>
              <div class="button no" id="' . $id. 'NoButton"><h4>Nej</h4></div>
          </div>';
    if($type != 1){
      echo '<div class="submitCancelContainer" id="'. $id. 'SubmitCancelContainer">
                <div class="button submit"><h4>Spara</h4></div>
                <div class="button cancel"><h4>Avbryt</h4></div>
            </div>
            <form id="'. $id. 'Form">
                <input type="hidden" name="index" value="'. $post["index"]. '" />
                <input type="hidden" name="table" value="'. $table. '" />
                <input type="hidden" name="submit" value="Spara" />
                <input type="hidden" name="type" value="'. $type. '" id="hiddenType" />
    	    </form>';
    }
  }
  else if($type == 3) echo 'FIX ME'; //FIXME Admin interface for slideshows
    
}

function makeAddButton($id){
  global $table;
  echo '<div class="newContainer" id="'. $id. 'NewContainer">
          <select name="type" class="type" id="'. $id. 'Type">
            <option value="0">Titel med gömd paragraf</option>
            <option value="1">Öppna/stäng alla knappar</option>
            <option value="2">Paragraf</option>
            <option value="3">Bildspel (Inte implementerat)</option>
            <option value="4">Huvudkategori</option>
          </select>
          <div class="button newButton" id="'. $id. 'NewButton"><h4>Lägg till
        </h4></div></div>
        <div class="formContainer new" id="'. $id. 'FormContainer">
            <form id="'. $id. 'Form">
                <div class="titleContainer">
            	    <label for=title><h6>Titel</h6></label><br /> 
       		    <input type="text" name="title">
                </div><div class="contentContainer">
                    <label for="content"><h6>Brödtext</h6></label><br /> 
		    <textarea name="content" id="'. $id. 'TextArea"></textarea>
                </div>
		<input type="hidden" name="submit" value="Publicera" />
                <input type="hidden" name="table" class="table" value="'. $table. '" />
                <input type="hidden" name="parent" value="'. $id .'" />
	    </form>
        <div class="button submit" id="'. $id. 'SubmitButton"><h4>Skapa</h4></div>
        <div class="button cancel"><h4>Avbryt</h4></div>
        </div>';
}

?>