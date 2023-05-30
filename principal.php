<?php 
date_default_timezone_set('America/Mexico_City');
//Inicio la sesion 
//ini_set("session.cookie_lifetime","7200");
//ini_set("session.gc_maxlifetime","7200");
session_start();
//COMPRUEBA QUE EL USUARIO ESTA AUTENTIFICADO 
if ($_SESSION["autentificado"] != "SI") { 
    //si no existe, envio a la p?gina de autentificacion 
    header("Location: index.php"); 
    //ademas salgo de este script 
    exit(); 
} 



include ("conexion.php");



include ("funciones.php");
?>
<html lang="es-mx">
<head>
	<meta charset="UTF-8" />
	<title>Administración</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
		
	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
	
	<link rel="stylesheet" href="estilos/estilos_principal.css">
	<link rel="stylesheet" href="estilos/estilos.css">
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<!--<script src="https://kit.fontawesome.com/2df1cf6d50.js" crossorigin="anonymous"></script>-->
	<!--<script src="https://kit.fontawesome.com/a5e678cc82.js" crossorigin="anonymous"></script>-->
	<link href="fontawesome64/css/fontawesome.css" rel="stylesheet">
  	<link href="fontawesome64/css/brands.css" rel="stylesheet">
  	<link href="fontawesome64/css/solid.css" rel="stylesheet">
	

	<script language="JavaScript" type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
	
	<script src="js/codigo.js"></script>


</head>
<body onload="<?php echo permisos(2, 'reservaciones(\''.date("Y-m-d").'\', \'0\', \'0\')');?>; ini()" onkeypress="parar()" onclick="parar()">

	<input type="checkbox" id="check">
	<header>
		<div class="menu_bar"><label for="check" id="chk_btn"><i class="fas fa-bars"></i></label></div>
		<div class="logo_img">Bienvenido <?php echo $_SESSION["nombre"];?>&nbsp;<img src="images/logo.png"></div>
	</header>

	<div class="menu_principal">
		<div><a href="principal.php"><i class="fas fa-globe"></i></a></div>
		<?php echo permisos(22, '<div><a href="#" onclick="pacientes()"><i class="fas fa-house-user"></i></a></div>');?>
		<?php echo permisos(47, '<div><a href="#" onclick="usuarios()"><i class="fas fa-users"></i></a></div>');?>
		<?php echo permisos(51, '<div><a href="#" onclick="configuracion()"><i class="fas fa-cogs"></i></a></div>');?>
		<?php echo permisos(99, '<div><a href="#" onclick="estadisticos()"><i class="fas fa-chart-bar"></i></a></div>');?>
		<?php echo permisos(109, '<div><a href="#" onclick="caja(\''.date ("Y-m-d").'\', \''.date ("Y-m-d").'\')"><i class="fas fa-cash-register"></i></a></div>');?>
		<?php echo permisos(119, '<div><a href="#" onclick="almacen()"><i class="fas fa-warehouse"></i></a></div>');?>
		<?php echo permisos(125, '<div><a href="#" onclick="bitacora()"><i class="fas fa-book"></i></a></div>');?>
		<div><i class="fas fa-sign-out-alt" onclick="location='logout.php'"></i></div>
	</div>

	<div class="contenido" id="contenido">
		
	</div>

	<!-- The Modal -->
    <div id="myModal" class="modal">

        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-body" id="modal-body">
    
            </div>
    
        </div>
    </div>
	
</body>
</html>
<script>
//definimos el modal
var modal = document.getElementById('myModal');

function limpiar(){
    document.getElementById("modal-body").innerHTML="";
}

function abrirmodal(){
	modal.style.display = "block";
}
function cerrarmodal(){
	modal.style.display = "none";
}
// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
	if (event.target == modal) {
		modal.style.display = "none";
	}
}

//funciones ajax
function pacientes(){
	$.ajax({
				type: 'POST',
				url : 'pacientes/pacientes.php'
	}).done (function ( info ){
		$('#contenido').html(info);
	});

}
function configuracion(){
	$.ajax({
				type: 'POST',
				url : 'configuracion/configuracion.php'
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}
function servicios(){
	$.ajax({
				type: 'POST',
				url : 'servicios/servicios.php'
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}
function usuarios(){
	$.ajax({
				type: 'POST',
				url : 'usuarios/usuarios.php'
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}	
function agenda(fecha){
	$.ajax({
				type: 'POST',
				url : 'agenda/agenda.php',
				data: 'fechacita=' + fecha
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}	
function estadisticos(){
	$.ajax({
				type: 'POST',
				url : 'estadisticos/estadisticos.php'
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}	
function reservaciones(fecha, habitacion, camas)
{
	$.ajax({
				type: 'POST',
				url : 'reservaciones/reservaciones.php',
				data: 'fecha_reservacion=' + fecha + '&habitacion=' + habitacion + '&camas=' + camas
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}
function caja(fechaini, fechafin, paciente){
	$.ajax({
				type: 'POST',
				url : 'caja/caja.php',
				data: 'fechaini=' + fechaini + '&fechafin=' + fechafin
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}
function almacen(){
	$.ajax({
				type: 'POST',
				url : 'almacen/almacen.php',
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}
function bitacora(){
	$.ajax({
				type: 'POST',
				url : 'bitacora/bitacora.php',
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}


//cerrar sesion
var bloqueo;
  function ini() {
    bloqueo = setTimeout('location="logout.php"', 3120000);
  }

  function parar() {
    clearTimeout(bloqueo);
    bloqueo = setTimeout('location="logout.php"', 3120000);
  }
</script>