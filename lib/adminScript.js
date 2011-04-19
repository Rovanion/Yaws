$(document).ready(function(){
    //Create a NicEdit object for all list items
    var myNicEditor = new nicEditor();
    //And put nicEditors on all <textarea>'s
    nicEditors.allTextAreas();

    $('.contentListItem').each(function(){
	var name = $(this).attr('id');
	name = name.substring(0, (name.length - 15));
	var Nname = name + 'NicEditPanel';
	var Tname = name + 'Text';
	
	//Does nothing if the li has no textediting parts
	if($('#' + Nname).length != 0){
	    //Adds panels in NicEdit divs
	    myNicEditor.setPanel(Nname);
	    //Make the title and text editable with nicEdit
	    myNicEditor.addInstance(name); 
	    myNicEditor.addInstance(Tname);
	    //Then make them uneditable until activated with .edit click
	    $('#' + name).attr('contenteditable', 'false');
	    $('#' + Tname).attr('contenteditable', 'false');
	    $('#' + Nname).height(0);
	    $('#' + Nname).css('overflow', 'hidden');
        }
    });

    //The above was initial setup, the fallowing are event handlers

    //Shows yes and no buttons
    $('.delete').click(function(){  
	var name = $(this).attr('id');
	name = name.substring(0, (name.length - 12));
	name = '#' + name + 'YesNoContainer';
	
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
	var Tname = name + 'Text';
	var Lname = name + 'ContentListItem';
	var Dname = name + 'Div';
	var DBname = name + 'DeleteButton';
	var Nname = name + 'NicEditPanel';
	var Cname = name + 'SubmitCancelContainer';

	$(name).attr('contenteditable', 'true');
	$(Tname).attr('contenteditable', 'true');
	$(name).css('background', 'rgba(255, 255, 255, 0.4)');
	$(Tname).css('background', 'rgba(255, 255, 255, 0.4)');
	$(name).unbind('click');
	$(name).css('cursor', '');  //FIXME Find out the correct name of the cursor
	$(Dname).height('auto');

	open(Dname);
	$(DBname).fadeOut(fadeOutTime);	
	$(this).fadeOut(fadeOutTime);
	$(Nname).animate({ height: 26 }, {
    	    duration: openTime, easing: 'easeOutExpo'
    	})
	setTimeout(function(){$(Cname).fadeIn(fadeInTime);}, fadeOutTime);
    });

    //NewButton shows the form for adding additonal content to the page
    $('.newButton').click(function(){
	var name = $(this).attr('id');
	name = name.substring(0, (name.length - 9));
	var Iname = '#' + name + 'FormContainer';
	var Fname = '#' + name + 'Form';
	var Tname = '#' + name + 'Type';
	var Cname = '#' + name + 'NewContainer';r
	var type = $(Tname).val();
	var pagename = $('.table').val();

	$(Fname).append('<input type="hidden" name="type" value="' + 
			type + '" id="hiddenType"/>');

	if(type == 0 || type == 2 || type == 4){
	    if(type == 2)
		$('.titleContainer').hide();
	    else if(type == 4)
		$('.contentContainer').hide();
	    $(Cname).fadeOut(fadeOutTime);
	    setTimeout(function(){$(Iname).fadeIn(fadeInTime);}, fadeOutTime);
	}
	else if(type == 1){
	    $(Tname).before(
		'<li class="contentListItem">' +
                 '<div class="button" id="openButton">' +
	 	 '<h3>Öppna alla</h3></div>' +
                 '<div class="button" id="closeButton">' +
                 '<h3>Stäng alla</h3></div></li>');

	    $('#openButton').live('click', function(){
		$('.hidden').each(function(){
    		    var name = '#' + $(this).attr('id');
		    open(name, 'Large');
    		});
	    });
	    $('#closeButton').live('click', function(){
		close('.hidden');
	    });

	    $.post('../lib/operations.php', 
		   { submit: "Publicera", parent: name, 
		     table: pagename, type: 1 },
		   function(data){if(data != '1')document.write(data)});
	}
	else if(type == 3){
	    //Show upload and linking form
	}
	else if(type == 4){
	    $(Cname).fadeOut(fadeOutTime);
	    setTimeout(function(){$(Iname).fadeIn(fadeInTime);}, fadeOutTime);
	}
    });
    //Cancel buttons cancels an edit in progress, in newForms on ListItems
    $('.cancel').click(function(){
	var Cname = '#' + $(this).parent().attr('id');
	var name = endEditing(Cname);
	var Nname = name + 'NewContainer';

	$(Cname).fadeOut(fadeOutTime);

	setTimeout(function(){
	    $(Nname).fadeIn(fadeInTime);
	    $('.button').fadeIn(fadeInTime);
	    $('.type').fadeIn(fadeInTime);
	}, fadeOutTime);
    });
    //Buttons with the class submits a new entry or edited entry to the DB
    $('.submit').click(function(){
	var Cname = '#' + $(this).parent().attr('id');
	var name = endEditing(Cname);
	var Dname = name + 'Div';
	var Fname = name + 'Form';
	var formData = $(Fname).serializeArray();
	var textAreaData;
	
	originalHeight[Dname] = $(Dname).height();

	if(Cname[Cname.length - 13] == 'F')
	    textAreaData = $(Fname).find('.nicEdit-main').html();
	else{
	    textAreaData = $(name + 'Text').html();
	    formData.push({ name: 'title', value: $(name).html() });
	}
	formData.push({ name: 'content', value: textAreaData});
	
	$.post('../lib/operations.php', formData,
	      function(data){if(data != '1') alert(data);});

	$(Cname).fadeOut(fadeOutTime);
	formContainer.fadeOut(fadeOutTime);
	setTimeout(function(){
	    $('.button').fadeIn(fadeInTime);
	    $('.type').fadeIn(fadeInTime);
	}, fadeOutTime);
    });
});

/* Sumbit and cancel buttons both appear in <li>'s and <form>'s.
   The fallowing function determines which, does some context appropriate
   actions such as animation and then returns the correct basename*/
function endEditing(Cname){
    var name;
    if(Cname[Cname.length - 13] == 'F'){
	name  = Cname.substring(0, (Cname.length - 13));
	$('#hiddenType').remove();
	formContainer.fadeOut(fadeOutTime);
    }
    else{
	name  = Cname.substring(0, (Cname.length - 21));
	var Nname = name + 'NicEditPanel';
	var Tname = name + 'Text';
	
	$(name).attr('contenteditable', 'false');
	$(Tname).attr('contenteditable', 'false');
	$(name).css('background', 'rgba(255, 255, 255, 0)');
	$(Tname).css('background', 'rgba(255, 255, 255, 0)');
	$(name).css('cursor', 'pointer');
	bindOpenClose(name); //Hooks up event handler in ../lib/joyScript.js
	
	$(Nname).animate({ height: 0 }, {
    	    duration: closeTime, easing: 'easeOutExpo' });
    }
    return name;
}