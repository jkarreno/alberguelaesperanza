<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$ResMun=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM municipios WHERE Id='".$_POST["municipio"]."' LIMIT 1"));

$cadena='<div class="c100 card">
            <h2>Editar municipio</h2>
            <form name="fedmunicipio" id="fedmunicipio">
                <div class="c100">
                    <label class="l_form">Estado:</label>
                    <select name="estado" id="estado">
                        <option value="0">Selecciona</option>';
    $ResEstados=mysqli_query($conn, "SELECT * FROM Estados ORDER BY Id ASC");
    while($RResEstados=mysqli_fetch_array($ResEstados))
    {
        $cadena.='      <option value="'.$RResEstados["Id"].'"';if($ResMun["Estado"]==$RResEstados["Id"]){$cadena.=' selected';}$cadena.='>'.utf8_encode($RResEstados["Estado"]).'</option>';
    }
                        
$cadena.='         </select>
                </div>
                <div class="c100">
                    <label class="l_form">Municipio:</label>
                    <input type="text" name="municipio" id="municipio" value="'.$ResMun["Municipio"].'">
                </div>
                
                <input type="hidden" name="idmunicipio" id="idmunicipio" value="'.$ResMun["Id"].'">
                <input type="hidden" name="hacer" id="hacer" value="editmunicipio">
				<input type="submit" name="boteduser" id="boteduser" value="Editar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#fedmunicipio").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fedmunicipio"));

	$.ajax({
		url: "configuracion/municipios/municipios.php",
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