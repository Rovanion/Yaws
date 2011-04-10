$(document).ready(function(){
    /*Create a NicEdit object for all list items and
      put separate nicEditors on all textareas, ie new forms*/
    var myNicEditor = new nicEditor();
    nicEditors.allTextAreas();

    $('.submitCancelContainer').hide();
    $('.formContainer').hide();
    $('.yesNoContainer').hide();

    //Adds nicEdit panels and instances aka textfields to <li>
    $('.contentListItem').each(function(){
	var name = $(this).attr('id');
	name = name.substring(0, (name.length - 15));
	var Nname = name + 'NicEditPanel';
	var Tname = name + 'Text';

	//Adds panels in NicEdit divs
	myNicEditor.setPanel(Nname);
	//Make the title and text edible with nicEdit
	myNicEditor.addInstance(name); 
	myNicEditor.addInstance(Tname);
	//Then make them unedible until activated with .edit click
	$('#' + name).attr('contentEdible', false);
	$('#' + Tname).attr('contentEdible', false);
	$('#' + Nname).hide();
    });

    //The above was initial setup, the fallowing are events

    //Shows yes and no buttons
    $('.delete').click(function(){  
	var name = $(this).attr('id');
	name = name.substring(0, (name.length - 12));
	name = '#' + name + 'YesNoContainer';
	
	$( name).attr('contentEdible', false);
	$(this).fadeOut(fadeOutTime);
	setTimeout(function(){$(name).fadeIn(fadeInTime);}, fadeOutTime);
    });
    $('.no').click(function(){ //Shows the delete button again
	var name = $(this).attr('id');
	name = '#' + name.substring(0, (name.length - 8));
	var Cname = name + 'YesNoContainer';
	var Dname = name + 'DeleteButton'; 

	$(Cname).fadeOut(fadeOutTime);
	setTimeout(function(){$(Dname).fadeIn(fadeInTime);}, fadeOutTime);
    });
    $('.yes').click(function(){ //Carries out a delete order
	var name = $(this).attr('id');
	name = name.substring(0, (name.length - 9));
	var Iname = '[id^="' + name + '"]';
	var pagename = $('.table').val();

	$(Iname).fadeOut(fadeOutTime);
	setTimeout(function(){$(Iname).remove();}, fadeOutTime);
	$.post('../lib/operations.php', 
	       { submit: "Delete", JStitle: name, table: pagename},
	       function(data){if(data != '1'){ (data); };
        });
    });
    //Brings forth forms for editing existing content
    $('.edit').click(function(){
	var name = $(this).attr('id');
	name = '#' + name.substring(0, (name.length - 10));
	var Dname = name + 'DeleteButton';
	var Nname = name + 'NicEditPanel';
	var Cname = name + 'SubmitCancelContainer';
	var Tname = name + 'Div';

	open(Tname);
	$(Dname).fadeOut(fadeOutTime);	
	$(this).fadeOut(fadeOutTime);
	$(Nname).show();//FIXME: Animera öppnandet av Nname
	setTimeout(function(){$(Cname).fadeIn(fadeInTime);}, fadeOutTime);
    });
    //NewButton shows the form for adding additonal content to the page
    $('.newButton').click(function(){
	var name = $(this).attr('id');
	name = name.substring(0, (name.length - 9));
	var Iname = '#' + name + 'FormContainer';
	var Fname = '#' + name + 'Form';
	var Tname = '#' + name + 'Type';
	var type = $(Tname).val();
	var pagename = $('.table').val();

	$(Fname).append('<input type="hidden" name="type" value="' + type + '" />');

	if(type == 0 || type == 2 || type == 4){
	    if(type == 2)
		$('.titleContainer').hide();
	    else if(type == 4)
		$('.contentContainer').hide();
	    $(this).fadeOut(fadeOutTime);
	    $(Tname).fadeOut(fadeOutTime);
	    setTimeout(function(){$(Iname).fadeIn(fadeInTime);}, fadeOutTime);
	}
	else if(type == 1){
	    $(Tname).before(
		'<div class="button" id="openButton">' +
		'<h3>Öppna alla</h3></div>' +
                '<div class="button" id="closeButton">' +
                    '<h3>Stäng alla</h3></div><br /><br /><br />');
	    //$('#openButton').live(click, );  FIXME AT SOME POINT IN THE FUTURE
	    //$('#closeButton').live(click, );

	    $.post('../lib/operations.php', 
		   { submit: "Publicera", parent: name, table: pagename, type: 1 },
		   function(data){if(data != '1')document.write(data)});
	}
	else if(type == 3){
	    //Show upload and linking form
	}
	else if(type == 4){
	    $(this).fadeOut(fadeOutTime);
	    $(Tname).fadeOut(fadeOutTime);
	    setTimeout(function(){$(Iname).fadeIn(fadeInTime);}, fadeOutTime);
	}
    });
    //Cancelbuttons cancel an edit in progress
    $('.cancelButton').click(function(){
	var Cname = '#' + $(this).parent().attr('id');
	var name  = Cname.substring(0, (Cname.length - 21));
	var Nname = name + 'NicEditPanel';

	$(Cname).fadeOut(fadeOutTime);
	$(Nname).fadeOut(fadeOutTime);
	
	formContainer.fadeOut(fadeOutTime);
	$('#hiddenType').remove();
	setTimeout(function(){
	    $('.button').fadeIn(fadeInTime);
	    $('.type').fadeIn(fadeInTime);
	    $('.X').fadeIn(fadeInTime);
	    $('.hidden').fadeIn(fadeInTime);
	}, fadeOutTime);
	$('.contentTextArea').show();
	$('.titleContainer').show();
    });
    //Buttons with the class submit create new entries on a page
    $('.submit').click(function(){
	var name = $(this).attr('id');
	name = name.substring(0, (name.length - 12));
	var Fname = '#' + name + 'Form';
	var textAreaData = $(Fname).find('.nicEdit-main').html();
	var formData = $(Fname).serializeArray();
	
	formData.push({ name: 'content', value: textAreaData}); 
	formData.push({ name: 'parent', value: name});

	$.post('../lib/operations.php', formData,
	      function(data){if(data != '1') alert(data);});

	formContainer.fadeOut(fadeOutTime);
	setTimeout(function(){
	    $('.button').fadeIn(fadeInTime);
	    $('.type').fadeIn(fadeInTime);
	    $('.X').fadeIn(fadeInTime);
	    $('.hidden').fadeIn(fadeInTime);
	}, fadeOutTime);
    });
});
 