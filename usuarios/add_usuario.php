<?php
//Inicio la sesion 
session_start();

include('../conexion.php');

$cadena='<div class="c100 card">
            <h2>Nuevo usuario</h2>
            <form name="fadusuario" id="fadusuario">
                <label class="l_form">Nombre:</label>
                <input type="text" name="nombre" id="nombre">
                <label class="l_form">Nombre de usuario:</label>
                <input type="text" name="nusuario" id="nusuario">
                <label class="l_form">Contraseña:</label>
                <input type="text" name="contrasena" id="contrasena">
                <label class="l_form">Perfil:</label>
                <select name="perfil" id="perfil">
                    <option value="">Selecciona</option>';
$ResPerfiles=mysqli_query($conn, "SELECT * FROM usuarios_perfiles ORDER BY Nombre ASC");
while($RResP=mysqli_fetch_array($ResPerfiles))
{
    $cadena.='      <option value="'.$RResP["Id"].'"';if($RResP["Id"]==1){$cadena.=' selected';}$cadena.='>'.$RResP["Nombre"].'</option>';
}
$cadena.='      </select>
                <input type="hidden" name="hacer" id="hacer" value="addusuario">
				<input type="submit" name="botaduser" id="botaduser" value="Agregar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#fadusuario").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fadusuario"));

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