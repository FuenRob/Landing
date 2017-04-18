$(document).ready(function(){
	// Botón de enviar formulario, si es ajax...
	$('#form_main').submit(function(e){
		e.preventDefault();
		var request = $.ajax({
			url: "index.php",
			method: "POST",
			data: $(this).serialize(),
			dataType: "html"
		});

		request.done(function( msg ) {
			$('#form').hide();
			$( "#respuesta" ).html( msg );
		});

		request.fail(function( jqXHR, textStatus ) {
			alert( "Request failed: " + textStatus );
		});
	});
});


$(document).on('click', "#sendmail", function (e) {
	e.preventDefault();
	var request = $.ajax({
		url: $(this).attr('href'),
		method: "POST",
		data: {ajax: true},
		dataType: "html"
	});

	request.done(function( msg ) {
		$('#block_form').hide();
		$( "#respuesta" ).html( msg );
	});

	request.fail(function( jqXHR, textStatus ) {
		alert( "Request failed: " + textStatus );
	});
});
