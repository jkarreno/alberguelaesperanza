<?php
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

$ResPac=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, Nombre, Apellidos, FacturarA FROM pacientes WHERE Id='".$_POST["paciente"]."' LIMIT 1"));

$cadena='<div class="c100 card">
            <h2>Facturación del paciente '.$ResPac["Nombre"].' '.$ResPac["Apellidos"].'</h2>
            <form name="ffacpaciente" id="ffacpaciente">
                <div class="c80"></div>
                <div class="c100">
                    <label class="l_form">Facturar a: </label> 
                    <select name="facturara" id="facturara" onchange="toggle(this)">
                        <option value="0"';if($ResPac["FacturarA"]==0){$cadena.=' selected';}$cadena.='>Publico General</option>
                        <option value="1"';if($ResPac["FacturarA"]==1){$cadena.=' selected';}$cadena.='>Personalizado</option>
                    </select>
                </div>
                <div class="c100 maselementos">
                    <div class="c70">
                        <label class="l_form">Nombre o Razón Social</label>
                        <input type="text" name="nombre" id="nombre">
                    </div>
                    <div class="c20">
                        <label class="l_form">RFC:</label>
                        <input type="text" name="rfc" id="rfc">
                    </div>
                    <div class="c100">
                        <label class="l_form"}>Dirección</label>
                        <input type="text" name="direccion" id="direccion">
                    </div>
                    <div class="c45">
                        <label class="l_form"}>Colonia</label>
                        <input type="text" name="colonia" id="colonia">
                    </div>
                    <div class="c45">
                        <label class="l_form"}>Municipio</label>
                        <input type="text" name="municipio" id="municipio">
                    </div>
                    <div class="c45">
                        <label class="l_form"}>Codigo Postal</label>
                        <input type="text" name="cp" id="cp">
                    </div>
                    <div class="c45">
                        <label class="l_form"}>Estado</label>
                        <input type="text" name="estado" id="estado">
                    </div>
                    <div class="c45">
                        <label class="l_form"}>Telefono</label>
                        <input type="text" name="telefono" id="telefono">
                    </div>
                    <div class="c45">
                        <label class="l_form"}>Correo Electrónico</label>
                        <input type="text" name="email" id="email">
                    </div>
                </div>
                <div class="c100">
                    <input type="hidden" name="idpaciente" id="idpaciente" value="'.$ResPac["Id"].'">
                    <input type="hidden" name="hacer" id="hacer" value="addfactpaciente">
                    <input type="submit" name="botadfacturacion" id="botadfacturacion" value="Registrar>>">
                </div>
            </form>
        </div>';

echo $cadena;

?>
<script>
function toggle(o) {
var el=document.querySelector(".masElementos");
if (o.value==1) el.style.display="flex";
if (o.value!=1) el.style.display="none";
}

$("#ffacpaciente").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("ffacpaciente"));

	$.ajax({
		url: "pacientes/pacientes.php",
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