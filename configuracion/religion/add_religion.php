<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$cadena='<div class="c100 card">
            <h2>Nueva religion</h2>
            <form name="fadreligion" id="fadreligion">
                <div class="c100">
                    <label class="l_form">Nombre religion:</label>
                    <input type="text" name="religion" id="religion">
                </div>
                
                <input type="hidden" name="hacer" id="hacer" value="addreligion">
				<input type="submit" name="botadreligion" id="botadreligion" value="Agregar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#fadreligion").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fadreligion"));

	$.ajax({
		url: "configuracion/religion/religion.php",
		type: "POST",
		dataType: "HTML",
		data: formData,
		cache: false,
		contentType: false,
		processData: false
	}).done(function(echo){
		$("#contenido2").html(echo);
	});
});
</script>