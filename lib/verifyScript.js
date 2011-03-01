$(document).ready(function(){
    $('.verify').click(function(){
	if($('#password1').val() == $('#password2').val()){
	    var formData = $('#PasswordForm').serialize();
	    
	    //$('#PasswordContainer').fadeOut(fadeOutTime);
	    $.get('../lib/operations.php?' + formData, {submit: 'Verification'},
		  function(data){
		      if(data != '1')
			  document.write(data);
		      else
			  window.location('../pages/index.php');
		  });
	}
	else
	    $('#PasswordForm').append('<h6>Lösenorden matchar ej</h6>')
    });
});
