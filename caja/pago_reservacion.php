<?php
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

$mensaje='';

//acciones
if(isset($_POST["hacer"]))
{
    if($_POST["hacer"]=='addpagores')
    {
        if($_POST["numrecibo"]=='')
        {
            mysqli_query($conn, "INSERT INTO pagoreservacion (IdReservacion, Fecha, Pago, DetDescuento, Descuento, DetExtra, Extra, Monto, Estatus, Usuario) 
                                                        VALUES ('".$_POST["idreservacion"]."', '".date("Y-m-d")."', '".$_POST["pago"]."', '".strtoupper($_POST["det_descuento"])."',
                                                                '".$_POST["descuento"]."', '".$_POST["det_extra"]."', '".$_POST["extra"]."', '".$_POST["tpago"]."', '1', '".$_SESSION["Id"]."')") or die(mysqli_error($conn));
            
        }
        else
        {
            mysqli_query($conn, "INSERT INTO pagoreservacion (Id, IdReservacion, Fecha, Pago, DetDescuento, Descuento, DetExtra, Extra, Monto, Estatus, Usuario) 
                                                        VALUES ('".$_POST["numrecibo"]."', '".$_POST["idreservacion"]."', '".date("Y-m-d")."', '".$_POST["pago"]."', 
                                                                '".strtoupper($_POST["det_descuento"])."', '".$_POST["descuento"]."', '".$_POST["det_extra"]."', '".$_POST["extra"]."', 
                                                                '".$_POST["tpago"]."', '1', '".$_SESSION["Id"]."')") or die(mysqli_error($conn));
        }

        //busca pagos totales
        $totalpagado=mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(Pago) AS TotalPagado FROM pagoreservacion WHERE IdReservacion='".$_POST["idreservacion"]."' AND Estatus='1'"));

        if($totalpagado["TotalPagado"]>=$_POST["montototal"] OR $_POST["Descuento"]>0)
        {
            mysqli_query($conn, "UPDATE reservacion SET Pagada=3, Estatus=1 WHERE Id='".$_POST["idreservacion"]."'"); // totalmente pagada
            mysqli_query($conn, "UPDATE reservaciones SET Estatus=1 WHERE IdReservacion='".$_POST["idreservacion"]."'");
        }
        else
        {
            mysqli_query($conn, "UPDATE reservacion SET Pagada=1, Estatus=1 WHERE Id='".$_POST["idreservacion"]."'"); //pagada parcialmente
            mysqli_query($conn, "UPDATE reservaciones SET Estatus=1 WHERE IdReservacion='".$_POST["idreservacion"]."'");
        }

        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se registro el pago de la reservación '.$_POST["idreservacion"].'"</div>';

        //bitacora
        mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '9', '".json_encode($_POST)."')");
    }
}

$ResRes=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM reservacion WHERE Id='".$_POST["idreservacion"]."' LIMIT 1"));
$ResPac=mysqli_fetch_array(mysqli_query($conn, "SELECT Nombre, Apellidos, FechaNacimiento FROM pacientes WHERE Id='".$ResRes["IdPaciente"]."' LIMIT 1"));

if($ResRes["Dias"]>1)
{
    $dias=date("Y-m-d",strtotime($ResRes["Fecha"]."+ ".($ResRes["Dias"]-1)." days"));
}
elseif($ResRes["Dias"]==1)
{
    $dias=$ResRes["Fecha"];
}

//reservación pagada
$pagada=$ResRes["Pagada"];

//calculamos edad del paciente
//$paci=mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(Id) AS paciente FROM `reservaciones` WHERE IdReservacion='".$_POST["idreservacion"]."' AND Tipo='P' AND Cama>'0' GROUP BY IdPA"));
//if($paci["paciente"]>1)
//{
//    $edadp=obtener_edad_segun_fecha($ResPac["FechaNacimiento"]);
//    if($edadp<=12){$cp=15;}else{$cp=25;}
//}
//else
//{
//    $cp=0;
//}
$dpaci=mysqli_num_rows(mysqli_query($conn, "SELECT IdPA FROM `reservaciones` WHERE IdReservacion='".$_POST["idreservacion"]."' AND Tipo='P' AND Cama>='0' GROUP BY IdPA")); //cuantos dias esta en el albergue

if($dpaci>0)
{
    if($ResPac["FechaNacimiento"]==NULL OR $ResPac["FechaNacimiento"]=='')
    {
        $cp=25;
    }
    else
    {
        $edadp=obtener_edad_segun_fecha($ResPac["FechaNacimiento"]);
        if($edadp<=12){$cp=15;}else{$cp=25;}
    }
}
else
{
    $cp=0;
}

//calculamos acompañantes
$acomp=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(Id) AS acompanantes FROM `reservaciones` WHERE IdReservacion='".$_POST["idreservacion"]."' AND Tipo='A' AND Cama>'0' GROUP BY IdPA"));
if($acomp==0 OR $acomp==NULL){if($edadp<=12){$cp=15;}else{$cp=35;} $ca=0;}elseif($acomp>0){$ca=25*$acomp;}
if($dpaci==0 AND $acomp==1){$ca=25*$acomp;} //solo se hospeda acompañante

//calcula total paciente
$ctp=$cp*$ResRes["Dias"];
//calcula total acompañante
$cta=$ca*$ResRes["Dias"];
//calcula total a pagar
$CT=$ctp+$cta;

$ResPagado=mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(Monto) AS MontoPagado FROM pagoreservacion WHERE IdReservacion='".$_POST["idreservacion"]."' AND Estatus='1' "));

$ResRecibo=mysqli_fetch_array(mysqli_query($conn, "SELECT Id FROM pagoreservacion WHERE Estatus='1' ORDER BY Id DESC LIMIT 1"));

$cadena=$mensaje;
if($pagada<3)
{
    if(($CT-$ResPagado["MontoPagado"])>0)
    {
        $cadena.='<div class="c100 card">
                    <h2>Pago reservación '.$acomp.' - '.$cta.' - '.$ctp.' - '.$cp.' - '.$pagada.'</h2>
                    <form name="fpagores" id="fpagores">
                        <div class="c20">
                            <label class="l_form">Reservación: '.$ResRes["Id"].'</label>
                        </div>
                        <div class="c60"> 
                            <label class="l_form">Paciente: '.$ResRes["IdPaciente"].' - '.$ResPac["Nombre"].' '.$ResPac["Apellidos"].'</label>
                        </div>
                        <div class="c20">
                            <label class="l_form">Recibo: </label>
                            <input type="number" name="numrecibo" id="numrecibo" placeholder="'.($ResRecibo["Id"]+1).'">
                        </div>
                        <div class="c20"> 
                            <label class="l_form">Dias: '.$ResRes["Dias"].'</label> 
                        </div>
                        <div class="c40">
                            <label class="l_form">Del: '.fecha($ResRes["Fecha"]).'</label> 
                        </div>
                        <div class="c40">
                            <label class="l_form">Al: '.fecha($dias).'</label> 
                        </div>
                        <div class="c25">
                            <label class="l_form">Monto a Pagar: $ '.number_format($CT, 2).'</label>
                        </div>
                        <div class="c25">
                            <label class="l_form">Pagado: $ '.number_format($ResPagado["MontoPagado"], 2).'</label>
                        </div>
                        <div class="c25">
                            <label class="l_form">Por Pagar: $ '.number_format(($CT-$ResPagado["MontoPagado"]), 2).'</label>
                        </div>
                        <div class="c25">
                            <label class="l_form">Pago: </label>
                            <input type="number" name="pago" id="pago" value="'.($CT-$ResPagado["MontoPagado"]).'" onkeyup="calculo()"> 
                        </div>

                        <div class="c25"></div>
                        <div class="c50" style="padding-right: 10px">
                            <label class="l_form">Detalle: </label>
                            <input type="text" name="det_descuento" id="det_descuento">
                        </div>
                        <div class="c25">
                            <label class="l_form">Descuento: </label>
                            <input type="number" name="descuento" id="descuento" value="0" onkeyup="calculo()">
                        </div>

                        <div class="c25"></div>
                        <div class="c50" style="padding-right: 10px">
                            <label class="l_form">Cargo Extra: </label>
                            <input type="text" name="det_extra" id="det_extra">
                        </div>
                        <div class="c25">
                            <label class="l_form">Monto: </label>
                            <input type="number" name="extra" id="extra" value="0" onkeyup="calculo()">
                        </div>

                        <div class="c25"></div>
                        <div class="c50"></div>
                        <div class="c25">
                            <label class="l_form">Total a pagar: </label>
                            <input type="number" name="tpago" id="tpago" value="'.($CT-$ResPagado["MontoPagado"]).'">
                        </div>


                        <div class="c50"></div>
                        <div class="c25"></div>
                        <div class="c25">
                            <input type="hidden" name="hacer" id="hacer" value="addpagores">
                            <input type="hidden" name="montototal" id="montototal" value="'.$CT.'">
                            <input type="hidden" name="idreservacion" id="idreservacion" value="'.$_POST["idreservacion"].'">
                            <input type="submit" name="botpagar" id="botpagar" value="Pagar>>">
                        </div>
                    </form>
                </div>';
    }
}

$ResPagosReservacion=mysqli_query($conn, "SELECT * FROM pagoreservacion WHERE IdReservacion='".$_POST["idreservacion"]."'");
if(mysqli_num_rows($ResPagosReservacion)>0)
{
    $cadena.='<table style="width:80%">
                <thead>
                    <tr>
                        <th colspan="8" align="center" class="textotitable">Pagos reservación '.$_POST["idreservacion"].'</td>
                    </tr>
                    <tr>
                        <th align="center" class="textotitable">Cobrado por</th>
                        <th align="center" class="textotitable">Folio</th>
                        <th align="center" class="textotitable">Fecha</th>
                        <th align="center" class="textotitable">Pago</th>
                        <th align="center" class="textotitable">Descuento</th>
                        <th align="center" class="textotitable">Extra</th>
                        <th align="center" class="textotitable">Monto</th>
                        <th align="center" class="textotitable">&nbsp;</th>
                    </tr>
                </thead>';

    $J=1;
    while($RResPR=mysqli_fetch_array($ResPagosReservacion))
    {
        if($RResPR["Usuario"]==0){$cobradoby='---';}
        else
        {
            $ResUsuario=mysqli_fetch_array(mysqli_query($conn, "SELECT Nombre FROM usuarios WHERE Id='".$RResPR["Usuario"]."' LIMIT 1"));
            $cobradoby=$ResUsuario["Nombre"];
        }

        $cadena.='<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto">'.$cobradoby.'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto">'.$RResPR["Id"].'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto">'.fecha($RResPR["Fecha"]).'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto">$ '.number_format($RResPR["Pago"], 2).'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto">$ '.number_format($RResPR["Descuento"], 2).'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto">$ '.number_format($RResPR["Extra"], 2).'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto">$ '.number_format($RResPR["Monto"], 2).'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto">';
        if($RResPR["Estatus"]==1)
        {
            $cadena.=permisos(10, '<a href="caja/recibo_reservacion.php?idrecibo='.$RResPR["Id"].'" target="_blank"><i class="fas fa-print"></i></a>');
        }
        $cadena.='  </td>
                </tr>';
            $J++;
            if($bgcolor=="#ffffff"){$bgcolor='#cccccc';}
            else if($bgcolor=="#cccccc"){$bgcolor="#ffffff";}
            else{$bgcolor="#ffffff";}
    }
    $cadena.='</table>';
}



echo $cadena;

?>
<script>
$("#fpagores").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fpagores"));

	$.ajax({
		url: "caja/pago_reservacion.php",
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

function calculo()
{
    var total = document.getElementById("pago").value;
    var descuento = document.getElementById("descuento").value;
    var extra = document.getElementById("extra").value;
    
    var ttotal = ((Number(total) - Number(descuento)) + Number(extra))

    document.getElementById("tpago").value=ttotal;

}


//mostrar mensaje despues de los cambios
setTimeout(function() { 
    $('#mesaje').fadeOut('fast'); 
}, 1000)
</script>