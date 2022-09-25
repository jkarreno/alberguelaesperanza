<?php
//Inicio la sesion 
session_start();

include('../conexion.php');

$cadena='<div class="c100 card">
            <h2>Nuevo Perfil</h2>
            <form name="fadperfil" id="fadperfil">
                <label class="l_form">Nombre perfil:</label>
                <input type="text" name="nombre" id="nombre">
                <div class="c100 card" style="border: 0;box-shadow: none;margin: 0;padding: 0;">';
$ResCatego=mysqli_query($conn, "SELECT Categoria FROM bitacora_accion WHERE Categoria!='' GROUP BY Categoria ORDER BY Categoria ASC");
while($RResCatego=mysqli_fetch_array($ResCatego))
{
    $cadena.='      <label class="l_form" style="border-bottom: solid 1px">'.$RResCatego["Categoria"].'</label>
    ';

    $ResAcciones=mysqli_query($conn, "SELECT * FROM bitacora_accion WHERE Categoria='".$RResCatego["Categoria"]."' ORDER BY Id ASC");
    while($RResA=mysqli_fetch_array($ResAcciones))
    {
        $cadena.='  <div class="c20">
                        <label class="l_form">'.utf8_encode($RResA["Descripcion"]).'</label>
                        <ul class="tg-list">
                        <li class="tg-list-item">
                            <input class="tgl tgl-light" id="acc_'.$RResA["Id"].'" name="acc_'.$RResA["Id"].'" type="checkbox" value="1"/>
                            <label class="tgl-btn" for="acc_'.$RResA["Id"].'"></label>
                        </li>
                        </ul>
                    </div>';
    }
}
$cadena.='      </div>
                <input type="hidden" name="hacer" id="hacer" value="addperfil">
				<input type="submit" name="botaduser" id="botaduser" value="Agregar>>">
			</form>
        </div>';
    
echo $cadena;


?>
<script>
$("#fadperfil").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fadperfil"));

	$.ajax({
		url: "usuarios/perfiles.php",
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