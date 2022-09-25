<?php
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

$mensaje='';

if(isset($_POST["hacer"]))
{
    $pa=explode('_', $_POST["recibio"]);

    if($_POST["hacer"]=='adrecibo')
    {
        if($_POST["recibo"]=='')
        {
            mysqli_query($conn, "INSERT INTO pagogastosmedicos (IdPaciente, PA, IdPA, CantidadFactura, CantidadEntregada, TipoAyuda, NumFactura, Provedor, Concepto, Observaciones, 
                                                            Autorizo, Fecha, Usuario)
                                                    VALUES ('".$_POST["paciente"]."', '".$pa[0]."', '".$pa[1]."', '".$_POST["cantidadtotal"]."', '".$_POST["cantidadentregada"]."', 
                                                            '".$_POST["tipo_ayuda"]."', '".strtoupper($_POST["numfactura"])."', '".strtoupper($_POST["provedor"])."', '".strtoupper($_POST["concepto"])."', '".strtoupper($_POST["observaciones"])."', 
                                                            '".$_POST["autorizo"]."', '".$_POST["fecha"]."', '".$_SESSION["Id"]."')") or die(mysqli_error($conn));
        }
        else
        {
            mysqli_query($conn, "INSERT INTO pagogastosmedicos (Id, IdPaciente, PA, IdPA, CantidadFactura, CantidadEntregada, TipoAyuda, NumFactura, Provedor, Concepto, Observaciones, 
                                                            Autorizo, Fecha, Usuario)
                                                    VALUES ('".$_POST["recibo"]."', '".$_POST["paciente"]."', '".$pa[0]."', '".$pa[1]."', '".$_POST["cantidadtotal"]."', '".$_POST["cantidadentregada"]."', 
                                                            '".$_POST["tipo_ayuda"]."', '".strtoupper($_POST["numfactura"])."', '".strtoupper($_POST["provedor"])."', '".strtoupper($_POST["concepto"])."', '".strtoupper($_POST["observaciones"])."', 
                                                            '".$_POST["autorizo"]."', '".$_POST["fecha"]."', '".$_SESSION["Id"]."')") or die(mysqli_error($conn));
        }

        

        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Recibo registrado</div>';

        //bitacora
        mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '36', '".json_encode($_POST)."')");

    }
    elseif($_POST["hacer"]=="editrecibo")
    {
        mysqli_query($conn, "UPDATE pagogastosmedicos SET PA='".$pa[0]."', 
                                                        IdPA='".$pa[1]."',
                                                        CantidadFactura='".$_POST["cantidadtotal"]."', 
                                                        CantidadEntregada='".$_POST["cantidadentregada"]."', 
                                                        TipoAyuda='".$_POST["tipo_ayuda"]."', 
                                                        NumFactura='".strtoupper($_POST["numfactura"])."', 
                                                        Provedor='".strtoupper($_POST["provedor"])."', 
                                                        Concepto='".strtoupper($_POST["concepto"])."', 
                                                        Observaciones='".strtoupper($_POST["observaciones"])."', 
                                                        Autorizo='".$_POST["autorizo"]."', 
                                                        Fecha='".$_POST["fecha"]."', 
                                                        Usuario='".$_SESSION["Id"]."'
                                                WHERE Id='".$_POST["idrecibo"]."'");

        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Recibo Actualizado</div>';

        //bitacora
        mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '37', '".json_encode($_POST)."')");
    }
}

$ResPac=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM pacientes WHERE Id='".$_POST["paciente"]."' LIMIT 1"));
$ResRes=mysqli_fetch_array(mysqli_query($conn, "SELECT Id FROM pagogastosmedicos ORDER BY Id DESC LIMIT 1"));

$recibo=$ResRes["Id"]+1;

$cadena.=$mensaje.'<div class="c100 card" id="f_recibo_med" name="f_recibo_med">
            <h2>Formato de Gastos Médicos y Reembolsos</h2>
            <form name="fgmedicos" id="fgmedicos">
                <div class="c10">
                    <label class="l_form">Num. Recibo</label>
                    <input type="number" name="recibo" id="recibo" placeholder="'.$recibo.'">
                </div>
                <div class="c10">
                    <label class="l_form">Num. Paciente</label>
                    <input type="number" name="paciente" id="paciente" value="'.$_POST["paciente"].'">
                </div>
                <div class="c25">
                    <label class="l_form">Nombre Paciente</label>
                    <input type="text" id="nombre" value="'.$ResPac["Nombre"].' '.$ResPac["Apellidos"].'">
                </div>
                <div class="c25">
                    <label class="l_form">Nombre de quien recibe</label>
                    <select name="recibio" id="recibio">
                        <option value="P_'.$ResPac["Id"].'">'.$ResPac["Nombre"].' '.$ResPac["Apellidos"].'</option>';
$ResAco=mysqli_query($conn, "SELECT Id, Nombre FROM acompannantes WHERE IdPaciente='".$ResPac["Id"]."' ORDER BY Id ASC");
while($RResAco=mysqli_fetch_array($ResAco))
{
    $cadena.='          <option value="A_'.$RResAco["Id"].'">'.$RResAco["Nombre"].'</option>';
}
$cadena.='          </select>
                </div>
                <div class="c20">
                    <label class="l_form">Fecha</label>
                    <input type="date" name="fecha" id="fecha" value="'.date("Y-m-d").'">
                </div>
                
                <div class="c20">
                    <label class="l_form">Cantidad Total Facturada</label>
                    <input type="number" name="cantidadtotal" id="cantidadtotal" value="0" onkeyup="calculoc()">
                </div>
                <div class="c20">
                    <label class="l_form">Tipo de ayuda (%)</label>
                    <input type="number" name="tipo_ayuda" id="tipo_ayuda" onkeyup="calculoc()">
                </div>
                <div class="c20">
                    <label class="l_form">Cantidad Entregada</label>
                    <input type="number" name="cantidadentregada" id="cantidadentregada" value="0">
                </div>
                
                <div class="c50">
                    <label class="l_form">Concepto</label>
                    <input type="text" name="concepto" id="concepto">
                </div>
                <div class="c30">
                    <label class="l_form">Provedor</label>
                    <input type="text" name="provedor" id="provedor">
                </div>
                <div class="c10">
                    <label class="l_form">Num. Factura</label>
                    <input type="text" name="numfactura" id="numfactura">
                </div>

                <div class="c90">
                    <label class="l_form">Observaciones</label>
                    <input type="text" name="observaciones" id="observaciones">
                </div>
                <div class="c20">
                    <label class="l_form">Autorizo</label>
                    <select name="autorizo" id="autorizo">
                        <option value="0">Seleccione</option>';
$ResUsuarios=mysqli_query($conn, "SELECT Id, Nombre FROM usuarios ORDER BY Nombre ASC");
while($RResU=mysqli_fetch_array($ResUsuarios))
{
    $cadena.='          <option value="'.$RResU["Id"].'">'.$RResU["Nombre"].'</option>';
}
$cadena.='          </select>
                </div>
                <div class="c20">
                    <input type="hidden" name="hacer" id="hacer" value="adrecibo">
                    '.permisos(36, '<input type="submit" name="botadrecgasmed" id="botrecgasmed" value="Guardar>>">').'
                </div>
            </form>
        </div>
        <div class="c100">
            <table>
                <thead>
                    <tr>
                        <th colspan="11" align="center" class="textotitable">Historial</td>
                    </tr>
                    <tr>
                        <th align="center" class="textotitable">Recibo</th>
                        <th align="center" class="textotitable">Fecha</th>
                        <th align="center" class="textotitable">Provedor</th>
                        <th align="center" class="textotitable">Num. Factura</th>
                        <th align="center" class="textotitable">Concepto</th>
                        <th align="center" class="textotitable">Cantidad Facturada</th>
                        <th align="center" class="textotitable">Tipo de Ayuda</th>
                        <th align="center" class="textotitable">Cantidad Entregada</th>
                        <th align="center" class="textotitable">&nbsp;</th>
                        <th align="center" class="textotitable">&nbsp;</th>
                        <th align="center" class="textotitable">&nbsp;</th>
                    </tr>
                </thead>
            <tbody>';
$ResRecibos=mysqli_query($conn, "SELECT * FROM pagogastosmedicos WHERE IdPaciente='".$_POST["paciente"]."' ORDER BY Fecha DESC");
$bgcolor="#ffffff";
while($RResR=mysqli_fetch_array($ResRecibos))
{
    $cadena.='<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$RResR["Id"].'</td>
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.fecha($RResR["Fecha"]).'</td>
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$RResR["Provedor"].'</td>
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$RResR["NumFactura"].'</td>
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$RResR["Concepto"].'</td>
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="right" class="texto" valign="middle">$ '.number_format($RResR["CantidadFactura"],2).'</td>
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="right" class="texto" valign="middle">'.$RResR["TipoAyuda"].' %</td>
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="right" class="texto" valign="middle">$ '.number_format($RResR["CantidadEntregada"],2).'</td>
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                    <a href="caja/recibo_ayuda_medica.php?idrecibo='.$RResR["Id"].'" target="_blank"><i class="fas fa-print"></i></a>
                </td>
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                    '.permisos(37, '<a href="#f_recibo_med" onclick="edit_recibo_med(\''.$RResR["Id"].'\')"><i class="fas fa-edit"></i></a>').'
                </td>
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                    <a href="javascript:void(0)" onclick=""><i class="far fa-times-circle"></i></a>
                </td>
            </tr>';
    
    $totalentregado=$totalentregado+$RResR["CantidadEntregada"];

    if($bgcolor=="#ffffff"){$bgcolor='#cccccc';}
    else if($bgcolor=="#cccccc"){$bgcolor="#ffffff";}
}
$cadena.='  <tr style="background: '.$bgcolor.'" id="row_'.$J.'" >
                <td colspan="7" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="right" class="texto" valign="middle">Total: </td>
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="right" class="texto" valign="middle">$ '.number_format($totalentregado,2).'</td>
                <td colspan="3" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="Right" class="texto" valign="middle"></td>
            </tbody>
            </table>
        </div>';

echo $cadena;

//bitacora
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '35', '".json_encode($_POST)."')");

?>
<script>
$("#fgmedicos").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fgmedicos"));

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

function edit_recibo_med(recibo){
    $.ajax({
				type: 'POST',
				url : 'trabajo_social/edit_recibo_medico.php',
				data: 'recibo=' + recibo
	}).done (function ( info ){
		$('#f_recibo_med').html(info);
	});
}

//mostrar mensaje despues de los cambios
setTimeout(function() { 
    $('#mesaje').fadeOut('fast'); 
}, 1000)

//calcular canidad
function calculoc(){
    var cantidad=document.getElementById('cantidadtotal').value;
    var porcentaje=document.getElementById('tipo_ayuda').value;

    total_entregado = (Number(cantidad) * Number(porcentaje) / 100 );

    document.getElementById('cantidadentregada').value=total_entregado;
}
</script>