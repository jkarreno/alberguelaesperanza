<?php
date_default_timezone_set('America/Mexico_City');
//Inicio la sesion 
session_start();

include('../../conexion.php');
include('../../funciones.php');

$mensaje='';

//acciones
if(isset($_POST["hacer"]))
{
    //agregar lavanderia
	if($_POST["hacer"]=='addprenda')
	{
		mysqli_query($conn, "INSERT INTO lavanderia (Prenda)
									VALUES ('".strtoupper($_POST["prenda"])."')") or die(mysqli_error($conn));

		$mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se agrego la prenda '.$_POST["prenda"].'</div>';

        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '83', '".json_encode($_POST)."')");
	}
    //editar lavanderia
    if($_POST["hacer"]=='editprenda')
    {
        mysqli_query($conn, "UPDATE lavanderia SET prenda='".strtoupper($_POST["prenda"])."'
                                            WHERE Id='".$_POST["idprenda"]."'");

        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se actualizo la prenda '.$_POST["prenda"].'</div>';

        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '84', '".json_encode($_POST)."')");
    }
    //agregar inventario inicial
    if($_POST["hacer"]=='ii_prenda')
    {
        //inventario inicial
        if($_POST["movimiento"]=='I' )
        {
            mysqli_query($conn, "INSERT INTO lavanderia_inventario (Fecha, IdReservacion, IdPA, PA, ES, IdPrenda, Cantidad, Balance, Usuario)
                                                            VALUES ('".date("Y-m-d H:m:s")."', '0', '".$_POST["almacen"]."', 'I', 'E', '".$_POST["idprenda"]."', 
                                                                    '".$_POST["cant_prenda"]."', '".$_POST["cant_prenda"]."', '".$_SESSION["Id"]."')");
        }
        else if($_POST["movimiento"]=='E')
        {
            $ResCP=mysqli_fetch_array(mysqli_query($conn, "SELECT Balance FROM lavanderia_inventario WHERE IdPrenda='".$_POST["idprenda"]."' AND IdPA='".$_POST["almacen"]."' ORDER BY Id DESC LIMIT 1"));

            $balance=$ResCP["Balance"]+$_POST["cant_prenda"];

            mysqli_query($conn, "INSERT INTO lavanderia_inventario (Fecha, IdReservacion, IdPA, PA, ES, IdPrenda, Cantidad, Balance, Usuario)
                                                            VALUES ('".date("Y-m-d H:m:s")."', '0', '".$_POST["almacen"]."', 'E', 'E', '".$_POST["idprenda"]."', 
                                                                    '".$_POST["cant_prenda"]."', '".$balance."', '".$_SESSION["Id"]."')");
        }
        else if($_POST["movimiento"]=='S')
        {
            $ResCP=mysqli_fetch_array(mysqli_query($conn, "SELECT Balance FROM lavanderia_inventario WHERE IdPrenda='".$_POST["idprenda"]."' AND IdPA='".$_POST["almacen"]."' ORDER BY Id DESC LIMIT 1"));

            $balance=$ResCP["Balance"]-$_POST["cant_prenda"];

            mysqli_query($conn, "INSERT INTO lavanderia_inventario (Fecha, IdReservacion, IdPA, PA, ES, IdPrenda, Cantidad, Balance, Usuario)
                                                            VALUES ('".date("Y-m-d H:m:s")."', '0', '".$_POST["almacen"]."', 'S', 'S', '".$_POST["idprenda"]."', 
                                                                    '".$_POST["cant_prenda"]."', '".$balance."', '".$_SESSION["Id"]."')");
        }
        else if($_POST["movimiento"]=='T')
        {
            //almacen
            $ResCPA=mysqli_fetch_array(mysqli_query($conn, "SELECT Balance FROM lavanderia_inventario WHERE IdPrenda='".$_POST["idprenda"]."' AND IdPA='-1' ORDER BY Id DESC LIMIT 1"));

            $balance=$ResCPA["Balance"]-$_POST["cant_prenda"];

            mysqli_query($conn, "INSERT INTO lavanderia_inventario (Fecha, IdReservacion, IdPA, PA, ES, IdPrenda, Cantidad, Balance, Usuario)
                                                            VALUES ('".date("Y-m-d H:m:s")."', '0', '-1', 'T', 'S', '".$_POST["idprenda"]."', 
                                                                    '".$_POST["cant_prenda"]."', '".$balance."', '".$_SESSION["Id"]."')");

            //en uso
            $ResCPU=mysqli_fetch_array(mysqli_query($conn, "SELECT Balance FROM lavanderia_inventario WHERE IdPrenda='".$_POST["idprenda"]."' AND IdPA='0' ORDER BY Id DESC LIMIT 1"));

            $balance=$ResCPU["Balance"]+$_POST["cant_prenda"];

            mysqli_query($conn, "INSERT INTO lavanderia_inventario (Fecha, IdReservacion, IdPA, PA, ES, IdPrenda, Cantidad, Balance, Usuario)
                                                            VALUES ('".date("Y-m-d H:m:s")."', '0', '0', 'T', 'E', '".$_POST["idprenda"]."', 
                                                                    '".$_POST["cant_prenda"]."', '".$balance."', '".$_SESSION["Id"]."')");
        }

        $prenda=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM lavanderia WHERE Id='".$_POST["idprenda"]."' LIMIT 1"));
        
        //$mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se actualizo el inventario de la prenda '.$prenda["Prenda"].'</div>';
        $mensaje='<div class="mesaje" >'."INSERT INTO lavanderia_inventario (Fecha, IdReservacion, IdPA, PA, ES, IdPrenda, Cantidad, Balance, Usuario)
        VALUES ('".date("Y-m-d H:m:s")."', '0', '".$_POST["almacen"]."', 'I', 'E', '".$_POST["idprenda"]."', 
                '".$_POST["cant_prenda"]."', '".$_POST["cant_prenda"]."', '".$_SESSION["Id"]."')".'</div>';
        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '85', '".json_encode($_POST)."')");
    }
}

$cadena=$mensaje.'<table style="width:80%">
            <thead>
                <tr>
                    <td colspan="5" style="text-align: right">| '.permisos(83, '<a href="#" onclick="add_prenda()" class="liga">Nueva prenda</a>').' |</td>
                </tr>
                <tr>
                    <th colspan="5" align="center" class="textotitable">Lavanderia</td>
                </tr>
                <tr>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">Prenda</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                </tr>
            </thead>
            <tbody>';
$bgcolor="#ffffff"; $J=1;
$ResLav=mysqli_query($conn, "SELECT * FROM lavanderia ORDER BY Prenda ASC");
while($RResLav=mysqli_fetch_array($ResLav))
{
    $cadena.='<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$J.'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.utf8_encode($RResLav["Prenda"]).'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						'.permisos(84, '<a href="#" onclick="edit_prenda(\''.$RResLav["Id"].'\')"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>').' 
					</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						'.permisos(85, '<a href="#" onclick="inv_prenda(\''.$RResLav["Id"].'\')"><i class="fas fa-boxes" aria-hidden="true"></i></a>').' 
					</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						<a href="#" onclick="delete_prenda(\''.$RResLav["Id"].'\')"><i class="fa fa-trash" aria-hidden="true"></i></a>
                    </td>
				</tr>';
    
    $J++;
    if($bgcolor=="#ffffff"){$bgcolor='#cccccc';}
    else if($bgcolor=="#cccccc"){$bgcolor="#ffffff";}
}

$cadena.='  </tbody>
        </table>';

echo $cadena;

//bitacora
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo) VALUES ('".time()."', '".$_SESSION["Id"]."', '82')");

?>
<script>
//funciones ajax
function add_prenda(){
	$.ajax({
				type: 'POST',
				url : 'configuracion/lavanderia/add_prenda.php'
	}).done (function ( info ){
		$('#contenido2').html(info);
	});

}

function edit_prenda(prenda){
    $.ajax({
				type: 'POST',
				url : 'configuracion/lavanderia/edit_prenda.php',
                data: 'prenda=' + prenda
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

function delete_prenda(prenda){
    $.ajax({
				type: 'POST',
				url : 'configuracion/lavanderia/del_prenda.php',
                data: 'prenda=' + prenda
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

function inv_prenda(prenda){
    $.ajax({
				type: 'POST',
				url : 'configuracion/lavanderia/inventario_inicial.php',
                data: 'prenda=' + prenda
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

//mostrar mensaje despues de los cambios
setTimeout(function() { 
    $('#mesaje').fadeOut('fast'); 
}, 1000)
</script>