$(document).ready(function(){

    originalHeight = new Object();

    fadeInTime = 400;
    fadeOutTime = 100;
    
    $('.formContainer').hide();
    $('.yesNoContainer').hide();
    $('.loginBox').hide();

    $('.hidden').each(function(){
	originalHeight['#' + $(this).attr('id')] = $(this).height();
    });
    $('.kategori').each(function(){
	originalHeight['#' + $(this).attr('id') + 'Large'] = $(this).height();
    });
    $('.child').not($('.kategori')).height(0);
    $($('.kategori').get().reverse()).each(function(){
	originalHeight['#' + $(this).attr('id')] = $(this).height();
	$(this).height(0);
    });
    $('.hidden').height(0);
    
    $('.X').click(function() {
	var name = $(this).attr('id');
    	name = '#' + name + "Div";
	
	$(this).parent().height('auto');
    	if($(name).height() == 0){
    	    open(name);
	}
    	else{
	    close(name);
	}
    });
    
   $('.kategoriHeader').click(function(){
       if($(this).height() != 0){
	   var name = '#' + $(this).attr('id') + 'Div';
	   setTimeout(function(){ $(name).children('.hidden').height(0);}, 400); }
   });

    $('.close').click(function(){
    	close('.hidden');
    }) 
    $('.open').click(function(){
    	$('.hidden').each(function(){
    	    var name = '#' + $(this).attr('id');
	    open(name, 'Large');
    	});
    })

    $('.admin').click(function(){
	$(this).fadeOut(fadeOutTime);
	setTimeout(function(){$('.loginBox').fadeIn(fadeInTime);}, fadeOutTime);
	$('#Anv').focus();
    });
});

function open(object, addon){
    if(addon == null){
	$(object).animate(
    	    { height: originalHeight[object] }, {
    		duration: 600,
    		easing: 'easeOutExpo'
    	    })
    }
    else{
	if(originalHeight[object + addon] == null){
	    $(object).animate(
    		{ height: originalHeight[object] }, {
    		    duration: 600,
    		    easing: 'easeOutExpo'
    		})
	}
	else{
	    $(object).animate(
    		{ height: originalHeight[object + addon] }, {
    		    duration: 600,
    		    easing: 'easeOutExpo'
    		})
	}
    }
}
function close(object){
    $(object).animate(
	{ height: 0 }, {
	    duration: 400,
	    easing: 'easeOutExpo'
	})
}