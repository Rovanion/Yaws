$(document).ready(function(){
    $('.verify').click(function(){
	if($('#password1').val() == $('#password2').val();){
	    $.post();
	}
	else
	    $('.verify').append('<h6>Lösenorden matchar ej</h6>')
    });

});
