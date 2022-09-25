<?php
//Inicio la sesion 
session_start();

include('../conexion.php');


$ResNom=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, IdPaciente, Nombre FROM acompannantes WHERE Id='".$_POST["id"]."' LIMIT 1"));


$cadena='<div class="c100 card">
            <h2>Foto '.$ResNom["Nombre"].'</h2>
            <form name="ffoto" id="ffoto" enctype="multipart/form-data">
                <label class="l_form"><i class="fas fa-exclamation-triangle" style="color: #c20005"></i> Los archivos deberan ser imagenes de tipo .png o .jpg y deben tener un peso menor a 2MB</label>
                <label for="custom-file-upload" class="filupp">
                        <span class="filupp-file-name js-value">Buscar Imagen</span>
                        <input type="file" name="facom" accept=".jpg,.png" id="custom-file-upload"/>
                    </label>
                    <input type="hidden" name="hacer" id="hacer" value="adfoto">
                    <input type="hidden" name="idacompannante" id="idacompannante" value="'.$ResNom["Id"].'"> 
                    <input type="hidden" name="paciente" id="paciente" value="'.$ResNom["IdPaciente"].'"> 
                    <input type="submit" name="botadfoto" id="botadfoto" value="Cargar >>">
            </form>
        </div>';

echo $cadena;

?>
<script>
$("#ffoto").on("submit", function(e){
	e.preventDefault();
    
	var formData = new FormData(document.getElementById("ffoto"));
    document.getElementById("contenido").innerHTML="";

	$.ajax({
		url: "pacientes/acompannantes.php",
		type: "POST",
		dataType: "HTML",
		data: formData,
		cache: false,
		contentType: false,
		processData: false
	}).done(function(echo){
		$("#contenido").html(echo);
	});
});

//funciones input logo
$(document).on('change','input[type="file"]',function(){
	// this.files[0].size recupera el tamaño del archivo
	// alert(this.files[0].size);
	
	var fileName = this.files[0].name;
	var fileSize = this.files[0].size;

	if(fileSize > 2000000){
		alert('El archivo no debe superar los 2MB');
		this.value = '';
		this.files[0].name = '';
	}else{
		// recuperamos la extensión del archivo
		var ext = fileName.split('.').pop();
		
		// Convertimos en minúscula porque 
		// la extensión del archivo puede estar en mayúscula
		ext = ext.toLowerCase();
    
		// console.log(ext);
		switch (ext) {
			case 'jpg':
			case 'jpeg':
			case 'png': break;
			default:
				alert('El archivo no tiene la extensión adecuada');
				this.value = ''; // reset del valor
				this.files[0].name = '';
		}
	}

});

$(document).ready(function() {

// get the name of uploaded file
  $('input[type="file"]').change(function(){
      var value = $("input[type='file']").val();
      $('.js-value').text(value);
  });

});
</script>