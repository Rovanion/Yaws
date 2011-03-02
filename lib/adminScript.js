$(document).ready(function(){
    nicEditors.allTextAreas();
    
    $('.delete').click(function(){
	var name = $(this).attr('id');
	name = name.substring(0, (name.length - 12));
	name = '#' + name + 'yesNoContainer';

	$(this).fadeOut(fadeOutTime);
	setTimeout(function(){$(name).fadeIn(fadeInTime);}, fadeOutTime);
    });
    $('.no').click(function(){
	$('.yesNoContainer').fadeOut(fadeOutTime);
	setTimeout(function(){$('.delete').fadeIn(500);}, fadeOutTime);
    });
    $('.yes').click(function(){
	var name = $(this).attr('id');
	name = name.substring(0, (name.length - 9));
	var Iname = '[id^="' + name + '"]';
	var pagename = $('.table').val();

	$(Iname).fadeOut(fadeOutTime);
	setTimeout(function(){$(Iname).remove();}, fadeOutTime);
	$.post('../lib/operations.php', 
	       { submit: "Delete", JStitle: name, table: pagename},
	       function(data){if(data != '1') document.write(data);});
    });

    $('.edit').click(function(){
	var name = $(this).attr('id');
	name = '#' + name.substring(0, (name.length - 10));
	var Tname = name + 'Text';
	var Dname = name + 'DeleteButton';
	var Fname = name + 'FormContainer';
	$(this).fadeOut(fadeOutTime); 
	$(name).fadeOut(fadeOutTime);
	$(Tname).fadeOut(fadeOutTime);
	$(Dname).fadeOut(fadeOutTime);
	setTimeout(function(){$(Fname).fadeIn(fadeInTime);}, fadeOutTime);
    });
    $('.newButton').click(function(){
	var name = $(this).attr('id');
	name = name.substring(0, (name.length - 9));
	var Iname = '#' + name + 'InputContainer';
	var Nname = '#' + name + 'NewForm';
	var Tname = '#' + name + 'Type';
	var type = $(Tname).val();
	var pagename = $('.table').val();

	$(Nname).append('<input type="hidden" name="type" value="' + type + '" />');

	if(type == 0 || type == 2){
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
	    //$('#openButton').live(click, );
	    //$('#closeButton').live(click, );

	    $.post('../lib/operations.php', 
		   { submit: "Publicera", parent: name, table: pagename, type: 1 },
		   function(data){if(data != '1')document.write(data)});
	}
	else if(type == 3){
	    //Show upload and linking form
	}
	else if(type == 4){
	    $('.textarean').hide();
	    $(this).fadeOut(fadeOutTime);
	    $(Tname).fadeOut(fadeOutTime);
	    setTimeout(function(){$(Iname).fadeIn(fadeInTime);}, fadeOutTime);
	}
    });
    $('.cancelButton').click(function(){
	$('.formContainer').fadeOut(fadeOutTime);
	$('#hiddenType').remove();
	setTimeout(function(){
	    $('.button').fadeIn(fadeInTime);
	    $('.type').fadeIn(fadeInTime);
	    $('.X').fadeIn(fadeInTime);
	    $('.hidden').fadeIn(fadeInTime);
	}, fadeOutTime);
    });
    $('.submit').click(function(){
	var name = $(this).attr('id');
	name = name.substring(0, (name.length - 12));
	var Fname = name + 'Form';
	var formData = $(Fname).serialize();
	
	$('.FormContainer').fadeOut(fadeOutTime);
	setTimeout(function(){
	    $('.button').fadeIn(fadeInTime);
	    $('.type').fadeIn(fadeInTime);
	    $('.X').fadeIn(fadeInTime);
	    $('.hidden').fadeIn(fadeInTime);
	}, fadeOutTime);
    	
	$.get('../lib/operations.php?' + formData, 
	      { parent: name, submit: 'Spara'},
	       function(data){if(data != '1')document.write(data)});
    });
});
