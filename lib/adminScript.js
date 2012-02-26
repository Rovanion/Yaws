$(document).ready(function(){
    originalData = new Object();

    //Create a NicEdit object for all list items
    myNicEditor = new nicEditor();
    //And put nicEditors on all <textarea>'s
    nicEditors.allTextAreas();

    $('.nicEdit-main').width('99%');
    $('.nicEdit-main').parent().width('99%');
    $('.nicEdit-main').parent().prev().width('99%');

    $('.contentListItem').each(function(){
	var name = $(this).attr('id');
	name = name.substring(0, (name.length - 15));
	var NEPname = name + 'NicEditPanel';
	var NEP = $('#' + NEPname);
	var Tname = name + 'Text';
	
	//Does nothing if the <li> has no textediting parts
	if(NEP.length != 0){
	    addNicEdit(name);
        }
    });

    //The above was initial setup, the fallowing are event handlers

    //Shows yes and no buttons
    $('#PageUnsortedList').delegate('.delete', 'click', function(){  
	var name = $(this).attr('id');
	name = name.substring(0, (name.length - 12));
	name = '#' + name + 'YesNoContainer';
	
	$(this).fadeOut(fadeOutTime);
	setTimeout(function(){$(name).fadeIn(fadeInTime);}, fadeOutTime + 10);
    });
    $('#PageUnsortedList').delegate('.no', 'click', function(){ //Shows the delete button again
	var name = $(this).attr('id');
	name = '#' + name.substring(0, (name.length - 8));
	var Cname = name + 'YesNoContainer';
	var Dname = name + 'DeleteButton'; 

	$(Cname).fadeOut(fadeOutTime);
	setTimeout(function(){$(Dname).fadeIn(fadeInTime);}, fadeOutTime + 10);
    });
    $('#PageUnsortedList').delegate('.yes', 'click', function(){ //Carries out a delete order
	var name = $(this).attr('id');
	name = name.substring(0, (name.length - 9));
	var Iname = '[id^="' + name + '"]';
	var pagename = $('.table').val();

	$(Iname).animate({ height: 0 }, {
    	    duration: closeTime, easing: 'easeOutExpo' });
	setTimeout(function(){$(Iname).remove();}, fadeOutTime + 10);

	$.post('../lib/operations.php', 
	       { submit: "Delete", JStitle: name, table: pagename},
	       function(data){if(data != '1'){ (data); };
        });
    });

    //Brings forth forms for editing existing content
    $('#PageUnsortedList').delegate('.edit', 'click', function(){
	var name = $(this).attr('id');
	name = '#' + name.substring(0, (name.length - 10));
	var Tname = name + 'Text';
	var Dname = name + 'Div';
	var DECname = name + 'DeleteEditContainer';
	var NEPname = name + 'NicEditPanel';
	var SCCname = name + 'SubmitCancelContainer';

	originalData[name] = $(name).html();
	originalData[Tname] = $(Tname).html();

	$(name).attr('contenteditable', 'true');
	$(Tname).attr('contenteditable', 'true');
	$(name).css('background', 'rgba(255, 255, 255, 0.4)');
	$(Tname).css('background', 'rgba(255, 255, 255, 0.4)');
	$(name).unbind('click');
	$(name).css('cursor', 'text');

	open(Dname);
	$(DECname).fadeOut(fadeOutTime);	
	$(NEPname).animate({ height: 26 }, {
    	    duration: openTime, easing: 'easeOutExpo'
    	})
	setTimeout(function(){
	    $(SCCname).fadeIn(fadeInTime);
	}, fadeOutTime + 10);
	setTimeout(function(){
	    $(Dname).height('auto');
	}, openTime + 10);

    });

    //NewButton shows the form for adding additonal content to the page
    $('#content2').delegate('.newButton', 'click', function(){
	var name = $(this).attr('id');
	name = name.substring(0, (name.length - 9));
	var FCname = '#' + name + 'FormContainer';
	var Fname = '#' + name + 'Form';
	var Tname = '#' + name + 'Type';
	var NCname = '#' + name + 'NewContainer';
	var type = $(Tname).val();
	var pagename = $('.table').val();

	$(Fname).append('<input type="hidden" name="type" value="' + type + '" />');

	if(type == 0 || type == 2 || type == 4){
	    if(type == 2)
		$('.titleContainer').hide();
	    else if(type == 4)
		$('.contentContainer').hide();
	    $(NCname).fadeOut(fadeOutTime);
	    setTimeout(function(){$(FCname).fadeIn(fadeInTime);}, fadeOutTime);
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
	    $(NCname).fadeOut(fadeOutTime);
	    setTimeout(function(){$(FCname).fadeIn(fadeInTime);}, fadeOutTime);
	}
    });

    //Cancel buttons cancels an edit in progress, in newForms on ListItems
    $('#content2').delegate('.cancel', 'click', function(){
	var Cname = '#' + $(this).parent().attr('id');
	var name = endEditing(Cname);
	var Tname = name + 'Text';
	
	$(name).html(originalData[name]);
	$(Tname).html(originalData[Tname]);
	
	$(Cname).fadeOut(fadeOutTime);
	
	setTimeout(function(){
	    $('.titleContainer').show();
	    $('.contentContainer').show();
	}, fadeOutTime + 10);
    });
    
    //Buttons with the class submit submits a new or edited entry to the DB
    $('#content2').delegate('.submit', 'click', function(){
	var Cname = '#' + $(this).parent().attr('id');
	var name = endEditing(Cname);
	var Dname = name + 'Div';
	var Fname = name + 'Form';
	var ULname = name + 'UnsortedList';
	var formData = $(Fname).serializeArray();
	var type = getType(Cname);
	var textAreaData;
	console.debug(type);
	console.debug(Fname);
	if(type == 'Form'){
	    textAreaData = $(Fname).find('.nicEdit-main').html();
	    $(Fname).find('.nicEdit-main').html('<br>'); //Empties the field
	    formData.push({name: "title", value: $(Fname + ' [name|="title"]').val()});
	    $(Fname + ' [name|="title"]').val('');
	}
	else{
	    textAreaData = $(name + 'Text').html();
	    formData.push({ name: 'title', value: $(name).html() });
	    originalHeight[Dname] = $(Dname).height();
	}
	formData.push({ name: 'content', value: textAreaData});
	console.debug(formData);
	/* The fallowing part is tricky. It first sends the data to operations.php
	   which creates or updates the entry in question. Then, if the sender was
	   a newForm, fetches that one newly created entry and adds it to the page. */
	$.post('../lib/operations.php', formData,
	       function(data){
		   //Display error if result is long enough to be one
		   if(data.length > 4){ 
		       $(name).after('<div class="warning">' + data + '</div>');
		   }
		   //Else fetch the newly created entry
		   else if(type == 'Form'){ 
		       fetchSingleEntry(formData, data, Fname);
		   }
	       });
	
	$(Cname).fadeOut(fadeOutTime);
    });
});

/* Sumbit and cancel buttons both appear in <li>'s and <form>'s.
   The fallowing function determines which, does some context appropriate
   actions such as animation and then returns the correct basenamef */
function endEditing(Cname){
    var name;
    var type = getType(Cname);
    if(type == 'Form'){
	name  = Cname.substring(0, (Cname.length - 13));
	var NCname = name + 'NewContainer';

	$('#hiddenType').remove();
	formContainer.fadeOut(fadeOutTime);
	
	setTimeout(function(){
	    $(NCname).fadeIn(fadeInTime);
	}, fadeOutTime + 2);
    }
    else if(type == 'ListItem'){
	name  = Cname.substring(0, (Cname.length - 21));
	var NEPname = name + 'NicEditPanel';
	var Tname = name + 'Text';
	var DECname = name + 'DeleteEditContainer';
	
	var title = $(name);
	var text = $(Tname);

	title.attr('contenteditable', 'false');
	text.attr('contenteditable', 'false');
	title.css('background', 'rgba(255, 255, 255, 0)');
	text.css('background', 'rgba(255, 255, 255, 0)');
	title.css('cursor', 'pointer');
	bindOpenClose(name); //Hooks up event handler in ../lib/joyScript.js
	
	$(NEPname).animate({ height: 0 }, {
    	    duration: closeTime, easing: 'easeOutExpo' 
	});

	setTimeout(function(){
	    $(DECname).fadeIn(fadeInTime);
	}, fadeOutTime + 10);
    }
    return name;
}
function getType(name){
    if(name[name.length - 13] == 'F')
	return 'Form';
    else if(name[name.length - 15] == 'C')
	return 'ListItem';
    else if(name[name.length - 21] == 'S')
	return 'ListItem';
}

function addNicEdit(name){
    //Clean up the input if it starts with a #
    if(name[0] == '#')
	name = name.substring(1, name.length);
    var NEPname = name + 'NicEditPanel';
    var Tname = name + 'Text';
        
    /* Adds a NicEditPanel and activates the areas of editing */
    myNicEditor.setPanel(NEPname);

    if($('#' + name).length != 0){
	myNicEditor.addInstance(name);
	$('#' + name).attr('contenteditable', 'false');
    }
    if($('#' + Tname).length != 0){
	myNicEditor.addInstance(Tname);
	$('#' + Tname).attr('contenteditable', 'false');
    }

    $('#' + NEPname).height(0).css('overflow', 'hidden');
}
function fetchSingleEntry(formData, data, Fname){
    var postData = { onePart: 'true', index: data, 
		     page: formData[3].value };
    
    $.post('../lib/content.php', postData,
	   function(data){
	       /* First attatch the data fetched from content.php. This
		  traversing may seem odd, but it's neccecery. */
	       $(Fname).parent().prev().prev().children().last().after(data);
				  
	       //Then do some operations on it
	       name = $(Fname).parent().prev().prev().children().last().attr('id');	
	       name = name.substring(0, (name.length - 15));
	       var YNCname = '#' + name + 'YesNoContainer';
	       var SCCname = '#' + name + 'SubmitCancelContainer';
	       var FCname = '#' + name + 'FormContainer';
	       var Dname = '#' + name + 'Div';
	       
	       if(formData["type"] == 4){
		   myNicEditor.setpanel(name + 'NicEditPanel');
		   myNicEditor.addInstance(name + 'Text');
		   myNicEditor.addInstance(name);
	       }
	       else
		   addNicEdit(name);
	       bindOpenClose('#' + name);
	       
	       $(FCname).hide();
	       $(YNCname).hide();
	       $(SCCname).hide();
	       originalHeight[Dname] = $(Dname).height();
	       $(Dname).height(0);
	   });
}