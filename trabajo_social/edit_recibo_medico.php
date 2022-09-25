<?php
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

$ResR=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM pagogastosmedicos WHERE Id='".$_POST["recibo"]."' LIMIT 1"));
$ResP=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, Nombre, Apellidos FROM pacientes WHERE Id='".$ResR["IdPaciente"]."' LIMIT 1"));

$cadena='<h2>Formato de Gastos Médicos y Reembolsos</h2>
        <form name="feditgmedicos" id="feditgmedicos">
            <div class="c10">
                <label class="l_form">Num. Recibo</label>
                <label class="l_form">'.$ResR["Id"].'</label>
            </div>
            <div class="c10">
                <label class="l_form">Num. Paciente</label>
                <input type="number" name="paciente" id="paciente" value="'.$ResP["Id"].'" readonly>
            </div>
            <div class="c25">
                <label class="l_form">Nombre Paciente</label>
                <input type="text" id="nombre" value="'.$ResP["Nombre"].' '.$ResP["Apellidos"].'" readonly>
            </div>
            <div class="c25">
                <label class="l_form">Nombre de quien recibe</label>
                <select name="recibio" id="recibio">
                    <option value="P_'.$ResP["Id"].'"';if($ResR["PA"]=='P' AND $ResR["IdPA"]==$ResP["Id"]){$cadena.=' selected';}$cadena.='>'.$ResP["Nombre"].' '.$ResP["Apellidos"].'</option>';
            $ResAco=mysqli_query($conn, "SELECT Id, Nombre FROM acompannantes WHERE IdPaciente='".$ResP["Id"]."' ORDER BY Id ASC");
            while($RResAco=mysqli_fetch_array($ResAco))
            {
            $cadena.='          <option value="A_'.$RResAco["Id"].'"';if($ResR["PA"]=='A' AND $ResR["IdPA"]==$RResAco["Id"]){$cadena.=' selected';}$cadena.='>'.$RResAco["Nombre"].'</option>';
            }
            $cadena.='          </select>
            </div>
            <div class="c20">
                <label class="l_form">Fecha</label>
                <input type="date" name="fecha" id="fecha" value="'.$ResR["Fecha"].'">
            </div>

            <div class="c20">
                <label class="l_form">Cantidad Total Facturada</label>
                <input type="number" name="cantidadtotal" id="cantidadtotal" value="'.$ResR["CantidadFactura"].'" onkeyup="calculoc()">
            </div>
            <div class="c20">
                <label class="l_form">Tipo de ayuda (%)</label>
                <input type="number" name="tipo_ayuda" id="tipo_ayuda" value="'.$ResR["TipoAyuda"].'" onkeyup="calculoc()">
            </div>
            <div class="c20">
                <label class="l_form">Cantidad Entregada</label>
                <input type="number" name="cantidadentregada" id="cantidadentregada" value="'.$ResR["CantidadEntregada"].'">
            </div>

            <div class="c50">
                <label class="l_form">Concepto</label>
                <input type="text" name="concepto" id="concepto" value="'.$ResR["Concepto"].'">
            </div>
            <div class="c30">
                <label class="l_form">Provedor</label>
                <input type="text" name="provedor" id="provedor" value="'.$ResR["Provedor"].'">
            </div>
            <div class="c10">
                <label class="l_form">Num. Factura</label>
                <input type="text" name="numfactura" id="numfactura" value="'.$ResR["NumFactura"].'">
            </div>

            <div class="c90">
                <label class="l_form">Observaciones</label>
                <input type="text" name="observaciones" id="observaciones" value="'.$ResR["Observaciones"].'">
            </div>
            <div class="c20">
                <label class="l_form">Autorizo</label>
                <select name="autorizo" id="autorizo">
                    <option value="0">Seleccione</option>';
$ResUsuarios=mysqli_query($conn, "SELECT Id, Nombre FROM usuarios ORDER BY Nombre ASC");
while($RResU=mysqli_fetch_array($ResUsuarios))
{
    $cadena.='      <option value="'.$RResU["Id"].'"';if($ResR["Autorizo"]==$RResU["Id"]){$cadena.=' selected';}$cadena.='>'.$RResU["Nombre"].'</option>';
}
$cadena.='     </select>
            </div>
            <div class="c20">
                <input type="hidden" name="hacer" id="hacer" value="editrecibo">
                <input type="hidden" name="idrecibo" id="idrecibo" value="'.$ResR["Id"].'">
                <input type="submit" name="botadrecgasmed" id="botrecgasmed" value="Guardar>>">
            </div>
        </form>';

echo $cadena;
?>
<script>
$("#feditgmedicos").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("feditgmedicos"));

	$.ajax({
		url: "trabajo_social/recibo_medico.php",
		type: "POST",
		dataType: "HTML",
		data: formData,
		cache: false,
		contentType: false,
		processData: false
	}).done(function(echo){
		$("#modal-body").html(echo);
	});
});
</script>