<?php
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

$mensaje='';

$cadena='<div class="c100">
            <div class="menucard">
                <ul>
                    '.permisos(52, '<li><a href="#" onclick="municipios(\'%\', \'0\')"><i class="fas fa-globe-americas"></i> Municipios</a></li>').'
                    '.permisos(55, '<li><a href="#" onclick="institutos()"><i class="fas fa-hospital"></i> Institutos</a></li>').'
                    '.permisos(58, '<li><a href="#" onclick="religion()"><i class="fas fa-cross"></i> Religion</a></li>').'	
                    '.permisos(61, '<li><a href="#" onclick="ocupacion()"><i class="fas fa-user-tie"></i> Ocupación</a></li>').'
                    '.permisos(64, '<li><a href="#" onclick="edocivil()"><i class="fas fa-user-tag"></i> Edo Civil</a></li>').'
                    '.permisos(67, '<li><a href="#" onclick="escolaridad()"><i class="fas fa-user-graduate"></i> Escolaridad</a></li>').'
                    '.permisos(70, '<li><a href="#" onclick="lenguas()"><i class="fas fa-language"></i> Lengua</a></li>').'
                    '.permisos(73, '<li><a href="#" onclick="vivecon()"><i class="fas fa-home"></i> Vive con</a></li>').'
                    '.permisos(76, '<li><a href="#" onclick="habitaciones()"><i class="fas fa-bed"></i> habitaciones</a></li>').'
                    '.permisos(82, '<li><a href="#" onclick="lavanderia()"><i class="fas fa-tshirt"></i> Lavandería</a></li>').'
                    '.permisos(86, '<li><a href="#" onclick="diagnosticos()"><i class="fas fa-head-side-cough"></i> Diagnosticos</a></li>').'
                    '.permisos(89, '<li><a href="#" onclick="encuesta_satisfaccion()"><i class="fas fa-question-circle"></i> Encuesta Satisfacción</a></li>').'
                    '.permisos(95, '<li><a href="#" onclick="material_apoyo()"><i class="fas fa-crutch"></i> Asistencia</a></li>').'
                    '.permisos(113, '<li><a href="#" onclick="almacenes()"><i class="fas fa-warehouse"></i> Almacenes</a></li>').'
                    '.permisos(116, '<li><a href="#" onclick="presentaciones()"><i class="fas fa-box-open"></i> Presentaciones</a></li>').'
                    '.permisos(129, '<li><a href="#" onclick="cat_almacen()"><i class="fas fa-boxes"></i> Categorias</a></li>').'
                </ul>
            </div>
            <div id="contenido2" class="contenido2">
                
            </div>
        </div>';

echo $cadena;

//bitacora
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo) VALUES ('".time()."', '".$_SESSION["Id"]."', '51')");

?>
<script>
//funciones ajax
function municipios(estado, num){
	$.ajax({
				type: 'POST',
				url : 'configuracion/municipios/municipios.php',
				data: 'estado=' + estado + '&num=' + num
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

function institutos(){
	$.ajax({
				type: 'POST',
				url : 'configuracion/institutos/institutos.php'
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

function religion(){
	$.ajax({
				type: 'POST',
				url : 'configuracion/religion/religion.php'
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

function ocupacion(){
	$.ajax({
				type: 'POST',
				url : 'configuracion/ocupaciones/ocupaciones.php'
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

function edocivil(){
	$.ajax({
				type: 'POST',
				url : 'configuracion/edocivil/edocivil.php'
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

function lenguas(){
	$.ajax({
				type: 'POST',
				url : 'configuracion/lengua/lenguas.php'
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

function vivecon(){
	$.ajax({
				type: 'POST',
				url : 'configuracion/vivecon/vivecon.php'
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

function habitaciones(){
	$.ajax({
				type: 'POST',
				url : 'configuracion/habitaciones/habitaciones.php'
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

function escolaridad(){
	$.ajax({
				type: 'POST',
				url : 'configuracion/escolaridad/escolaridad.php'
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

function lavanderia(){
	$.ajax({
				type: 'POST',
				url : 'configuracion/lavanderia/lavanderia.php'
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

function diagnosticos(){
	$.ajax({
				type: 'POST',
				url : 'configuracion/diagnosticos/diagnosticos.php'
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

function encuesta_satisfaccion(){
	$.ajax({
				type: 'POST',
				url : 'configuracion/encuesta/encuesta.php'
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

function material_apoyo(){
	$.ajax({
				type: 'POST',
				url : 'configuracion/material_apoyo/material_apoyo.php'
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

function almacenes(){
	$.ajax({
				type: 'POST',
				url : 'configuracion/almacenes/almacenes.php'
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

function presentaciones(){
	$.ajax({
				type: 'POST',
				url : 'configuracion/presentaciones/presentaciones.php'
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

function cat_almacen(){
	$.ajax({
				type: 'POST',
				url : 'configuracion/almacenes/cat_almacenes.php'
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}
</script>