<?php
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

//total pacientes
$TotPacientes=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM pacientes"));
//total acompañantes
$TotAcompannantes=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM acompannantes"));

//total reservaciones
$TotReservaciones=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion"));
//total reservaciones canceladas
$TotReservCanc=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Estatus=2"));
//total reservaciones estancias
$TotReservEst=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Estatus=1"));
//total reservaciones por confirmar
$TotReservConf=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Estatus=0"));

//total ingresos
$TotIngresos=mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(Monto) AS TotMonto FROM pagoreservacion"));

//total prendas lavanderias
$Totprendas=mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(Cantidad) AS Cantidad FROM lavanderia_inventario WHERE PA='I'"));
//total prendas Balance
//$TotPS=mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(Cantidad) AS cantidad FROM `lavanderia_inventario` WHERE PA!='I' AND ES='S'"));
$ResPrendas=mysqli_query($conn, "SELECT Id FROM lavanderia ORDER BY Id ASC");
while($RResP=mysqli_fetch_array($ResPrendas))
{
    $ResB=mysqli_fetch_array(mysqli_query($conn, "SELECT Balance FROM lavanderia_inventario WHERE IdPrenda='".$RResP["Id"]."' ORDER BY Id DESC LIMIT 1"));
    $Totprendas=mysqli_fetch_array(mysqli_query($conn, "SELECT Cantidad FROM lavanderia_inventario WHERE PA='I' AND IdPrenda='".$RResP["Id"]."' ORder BY Id DESC LIMIT 1"));
    $tprendas=$tprendas+$Totprendas["Cantidad"];
    $balance=$balance+$ResB["Balance"];
}


//total prendas en uso
$TPU=$tprendas-$balance;
//total prendas en almacen
$TPA=$tprendas-$TPU;

//encuestas contestadas
$TE=mysqli_num_rows(mysqli_query($conn, "SELECT IdReservacion FROM `respuestas_encuesta` GROUP BY IdReservacion"));

//total material apoyo
$TMA=mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(Cantidad) AS Cantidad FROM material_apoyo_inventario WHERE PA='I'"));
//material prestado
$TMAP=mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(Cantidad) AS Cantidad FROM material_apoyo_inventario WHERE PA!='I' AND ES='S'"));
//estudios socioeconomicos
$TES=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM es_salud"));


$cadena='<div class="c100 rep">
            <div class="c30" style="padding: 0 20px;">
                <div class="card_cl c100">
                    <h1>Personas</h1>
                </div>
                <div class="c100">
                    <p><strong>Pacientes: </strong>'.number_format($TotPacientes).'</p>
                    <p><strong>Acompañantes: </strong>'.number_format($TotAcompannantes).'</p>
                    <p style="text-align: right;"><a href="#" onclick="personas()">Ver mas ></a></p>
                </div>
            </div>

            <div class="c30" style="padding: 0 20px;">
                <div class="card_po c30">
                    <h1>Hospedajes</h1>
                </div>
                <div class="c100">
                    <p><strong>Hospedajes: </strong>'.number_format($TotReservEst).'</p>
                    <p><strong>Cancelados: </strong>'.number_format($TotReservCanc).'</p>
                    <p><strong>Confirmar: </strong>'.number_format($TotReservConf).'</p>
                    <p><strong>Total: </strong>'.number_format($TotReservaciones).'</p>
                    '.permisos(100, '<p style="text-align: right;"><a href="#" onclick="e_reservaciones(\''.date("Y").'\')">Ver mas ></a></p>').'
                </div>
            </div>

            <div class="c30" style="padding: 0 20px;">
                <div class="card_pc c100">
                    <h1>Ingresos</h1>
                </div>
                <div class="c100">
                    <p><strong>Total: </strong>$ '.number_format($TotIngresos["TotMonto"], 2).'</p>
                    <p style="text-align: right;"><a href="#">Ver mas ></a></p>
                </div>
            </div>

            <div class="c30" style="padding: 0 20px;">
                <div class="card_la c100">
                    <h1>Lavanderia</h1>
                </div>
                <div class="c100">
                    <p><strong>Prendas en uso: </strong>'.number_format($TPU).'</p>
                    <p><strong>Prendas en Almacen: </strong>'.number_format($TPA).'</p>
                    <p><strong>Prendas Totales: </strong>'.number_format($tprendas).'</p> 
                    '.permisos(106, '<p style="text-align: right;"><a href="#" onclick="e_lavanderia()">Ver mas ></a></p>').'
                </div>
            </div>

            <div class="c30" style="padding: 0 20px;">
                <div class="card_ma c100">
                    <h1>Material Apoyo</h1>
                </div>
                <div class="c100">
                    <p><strong>Piezas Material: </strong>'.number_format($TMA["Cantidad"]).'</p>
                    <p><strong>Material Prestado: </strong>'.number_format($TMAP["Cantidad"]).'</p>
                    <p style="text-align: right;"><a href="#" onclick="e_encuestas()">Ver mas ></a></p>
                </div>
            </div>

            <div class="c30" style="padding: 0 20px;">
                <div class="card_ens c100">
                    <h1>Satisfacción</h1>
                </div>
                <div class="c100">
                    <p><strong>Encuestas: </strong>'.number_format($TE).'</p>
                    <p style="text-align: right;">'.permisos(108, '<a href="#" onclick="e_encuestas()">Ver mas ></a>').'</p>
                </div>
            </div>

            <div class="c30" style="padding: 0 20px;">
                <div class="card_soc c100">
                    <h1>Socioeconomico</h1>
                </div>
                <div class="c100">
                    <p><strong>Estudios: </strong>'.number_format($TES).'</p>
                    <p style="text-align: right;">'.permisos(137, '<a href="#" onclick="e_socioeconomicos(\''.date("Y").'\')">Ver mas ></a>').'</p>
                </div>
            </div>
        </div>';

echo $cadena;

//bitacora
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo) VALUES ('".time()."', '".$_SESSION["Id"]."', '99')");

?>
<script>
function personas(){
	$.ajax({
				type: 'POST',
				url : 'estadisticos/personas.php'
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}
function e_reservaciones(anno){
    $.ajax({
				type: 'POST',
				url : 'estadisticos/reservaciones.php',
                data: 'anno=' + anno
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}
function e_lavanderia(){
    $.ajax({
				type: 'POST',
				url : 'estadisticos/lista_lavanderia.php'
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}
function e_encuestas(){
    $.ajax({
				type: 'POST',
				url : 'estadisticos/encuestas.php'
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}
function e_socioeconomicos(anno){
    $.ajax({
				type: 'POST',
				url : 'estadisticos/esocioe.php',
                data: 'anno=' + anno
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}

</script>