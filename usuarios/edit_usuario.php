<?php
//Inicio la sesion 
session_start();

include('../conexion.php');

$ResUser=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM usuarios WHERE Id='".$_POST["usuario"]."' LIMIT 1"));

$cadena='<div class="c100 card">
            <h2>Editar usuario</h2>
            <form name="feditusuario" id="feditusuario">
                <label class="l_form">Nombre:</label>
                <input type="text" name="nombre" id="nombre" value="'.$ResUser["Nombre"].'">
                <label class="l_form">Nombre de usuario:</label>
                <input type="text" name="nusuario" id="nusuario" value="'.$ResUser["Usuario"].'">
                <label class="l_form">Contraseña:</label>
                <input type="text" name="contrasena" id="contrasena">
                <label class="l_form">Perfil:</label>
                <select name="perfil" id="perfil">';
$ResPerfiles=mysqli_query($conn, "SELECT * FROM usuarios_perfiles ORDER BY Nombre ASC");
while($RResP=mysqli_fetch_array($ResPerfiles))
{
    $cadena.='      <option value="'.$RResP["Id"].'"';if($RResP["Id"]==$ResUser["Perfil"]){$cadena.=' selected';}$cadena.='>'.$RResP["Nombre"].'</option>';
}
$cadena.='      </select>
                <input type="hidden" name="hacer" id="hacer" value="editusuario">
                <input type="hidden" name="idusuario" id="idusuario" value="'.$ResUser["Id"].'">
				<input type="submit" name="botedituser" id="botedituser" value="Actualizar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#feditusuario").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("feditusuario"));

	$.ajax({
		url: "usuarios/usuarios.php",
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
</script>