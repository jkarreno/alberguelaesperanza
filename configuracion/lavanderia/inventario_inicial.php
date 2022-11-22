<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');
include('../../funciones.php');

$ResPren=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM lavanderia WHERE Id='".$_POST["prenda"]."' LIMIT 1"));

$ResPrenA=mysqli_fetch_array(mysqli_query($conn, "SELECT Balance FROM lavanderia_inventario WHERE IdPrenda='".$_POST["prenda"]."' AND IdPA='-1' ORDER BY Id DESC LIMIT 1"));
$ResPrenP=mysqli_fetch_array(mysqli_query($conn, "SELECT Balance FROM lavanderia_inventario WHERE IdPrenda='".$_POST["prenda"]."' AND IdPA='-2' ORDER BY Id DESC LIMIT 1"));
$ResPrenU=mysqli_fetch_array(mysqli_query($conn, "SELECT Balance FROM lavanderia_inventario WHERE IdPrenda='".$_POST["prenda"]."' AND IdPA='0' ORDER BY Id DESC LIMIT 1"));

$cadena='<div class="c100 card">
            <h2>Inventario para '.$ResPren["Prenda"].'</h2>
            <form name="fedprendaii" id="fedprendaii">
                <div class="c30">
                    <label class="l_form">Alamacén:</label>
                    <select name="almacen" id="almacen">
						<option value="0">En uso</option>
						<option value="-1">Armario Lavanderia</option>
						<option value="-2">Armario Pasillo</option>
					</select>
                </div>
				<div class="c30">
                    <label class="l_form">Movimiento:</label>
                    <select name="movimiento" id="movimiento">
						<option value="I">Inventario Inicial</option>
						<option value="E">Entrada</option>
						<option value="S">Salida</option>
						<option value="T">Traslado</option>
					</select>
                </div>
				<div class="c30">
					<label class="l_form">Cantidad: </label>
					<input type="number" name="cant_prenda" id="cant_prenda" value="0">
				</div>
                
                <input type="hidden" name="hacer" id="hacer" value="ii_prenda">
                <input type="hidden" name="idprenda" id="idprenda" value="'.$ResPren["Id"].'">
				<input type="submit" name="botadinv" id="botadinv" value="Guardar>>">
			</form>
        </div>
		
		<div class="c100 card">
			<h2>Movimientos de inventario</h2>
			<div class="c25">
				<label class="l_form">Armario Lavandería: '.$ResPrenA["Balance"].'</label>
			</div>
			<div class="c25">
				<label class="l_form">Armario Pasillo: '.$ResPrenP["Balance"].'</label>
			</div>
			<div class="c25">
				<label class="l_form">En Uso: '.$ResPrenU["Balance"].'</label>
			</div>
			<div class="c25">
				<label class="l_form">Total prendas: '.($ResPrenU["Balance"]+$ResPrenA["Balance"]+$ResPrenP["Balance"]).'</label>
			</div>

			<div class="c100">
				<table>
            	<thead>
            	    <tr>
            	        <th align="center" class="textotitable">#</th>
            	        <th align="center" class="textotitable">Ubicación</th>
            	        <th align="center" class="textotitable">Reservación</th>
            	        <th align="center" class="textotitable">Paciente</th>
            	        <th align="center" class="textotitable">Fecha</th>
            	        <th align="center" class="textotitable">E/S</th>
            	        <th align="center" class="textotitable">Cantidad</th>
            	        <th align="center" class="textotitable">Balance</th>
            	    </tr>
            	</thead>
            	<tbody>';
$ResInvP=mysqli_query($conn, "SELECT * FROM lavanderia_inventario WHERE IdPrenda='".$_POST["prenda"]."' ORDER BY Id DESC");

$l=1; $bgcolor='#fff'; $balance=0;
while($RResIP=mysqli_fetch_array($ResInvP))
{

	if($RResIP["IdReservacion"]==0 AND ($RResIP["IdPA"]==0 OR $RResIP["IdPA"]<=-1) AND $RResIP["PA"]=='I')
	{
		$RResIP["IdReservacion"]='---';
		$paciente='CAPTURA DE INVENTARIO';
	}
	else if($RResIP["IdReservacion"]==0 AND ($RResIP["IdPA"]==0 OR $RResIP["IdPA"]<=-1) AND $RResIP["PA"]=='E')
	{
		$RResIP["IdReservacion"]='---';
		$paciente='INGRESO A INVENTARIO';
	}
	else if($RResIP["IdReservacion"]==0 AND ($RResIP["IdPA"]==0 OR $RResIP["IdPA"]<=-1) AND $RResIP["PA"]=='S')
	{
		$RResIP["IdReservacion"]='---';
		$paciente='SALIDA DE INVENTARIO';
	}
	else if($RResIP["IdReservacion"]==0 AND ($RResIP["IdPA"]==0 OR $RResIP["IdPA"]<=-1) AND $RResIP["PA"]=='T')
	{
		$RResIP["IdReservacion"]='---';
		$paciente='TRASLADO DE INVENTARIO DE ALMACEN';
	}
	else
	{
		$ResRes=mysqli_fetch_array(mysqli_query($conn, "SELECT p.Nombre, p.Apellidos FROM reservacion AS r
														INNER JOIN pacientes AS p on r.IdPaciente=p.Id
														WHERE r.Id='".$RResIP["IdReservacion"]."'"));

		$paciente=$ResRes["Nombre"].' '.$ResRes["Apellido"];
	}

	switch($RResIP["ES"])
	{
		case 'I': $mov='Inventario'; $s='<i class="fas fa-plus" style="font-size: 16px;"></i>'; $estilo='#003366'; break;
		case 'E': $mov='Entrada'; $s='<i class="fas fa-plus" style="font-size: 16px;"></i>'; $estilo='#26b719'; break;
		case 'S': $mov='Salida'; $s='<i class="fas fa-minus" style="font-size: 16px;"></i>'; $estilo='#ff0000'; break;
		case 'C': $mov='Cambio'; $s='<i class="fas fa-exchange-alt" style="font-size: 16px;"></i>'; $estilo='#003366'; break;
		case 'T': $mov='Traslado'; $s='<i class="fas fa-exchange-alt" style="font-size: 16px;"></i>'; $estilo='#003366'; break;
	}

	if($RResIP["IdPA"]=='-1')
	{
		$almacen='Armario Lavandería'; 
	}
	elseif($RResIP["IdPA"]=='-2')
	{
		$almacen='Armario Pasillo'; 
	}
	else
	{
		$almacen='En uso';
	}

	$cadena.='<tr style="background: '.$bgcolor.'" id="i_row_'.$l.'">
				<td onmouseover="i_row_'.$l.'.style.background=\'#badad8\'" onmouseout="i_row_'.$l.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$l.'</td>
				<td onmouseover="i_row_'.$l.'.style.background=\'#badad8\'" onmouseout="i_row_'.$l.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$almacen.'</td>
				<td onmouseover="i_row_'.$l.'.style.background=\'#badad8\'" onmouseout="i_row_'.$l.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$RResIP["IdReservacion"].'</td>
				<td onmouseover="i_row_'.$l.'.style.background=\'#badad8\'" onmouseout="i_row_'.$l.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$paciente.'</td>
				<td onmouseover="i_row_'.$l.'.style.background=\'#badad8\'" onmouseout="i_row_'.$l.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.fecha($RResIP["Fecha"]).'</td>
				<td onmouseover="i_row_'.$l.'.style.background=\'#badad8\'" onmouseout="i_row_'.$l.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$mov.'</td>
				<td onmouseover="i_row_'.$l.'.style.background=\'#badad8\'" onmouseout="i_row_'.$l.'.style.background=\''.$bgcolor.'\'" align="right" class="texto" valign="middle" style="color: '.$estilo.'">'.$s.number_format($RResIP["Cantidad"]).'</td>
				<td onmouseover="i_row_'.$l.'.style.background=\'#badad8\'" onmouseout="i_row_'.$l.'.style.background=\''.$bgcolor.'\'" align="right" class="texto" valign="middle">'.number_format($RResIP["Balance"]).'</td>
			</tr>';

	if($bgcolor=='#fff'){$bgcolor='#ccc';}
	elseif($bgcolor=='#ccc'){$bgcolor='#fff';}

	$l++;
}
$cadena.='	</div>
		</div>';
    
echo $cadena;

?>
<script>
$("#fedprendaii").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fedprendaii"));

	$.ajax({
		url: "configuracion/lavanderia/lavanderia.php",
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