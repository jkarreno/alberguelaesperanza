<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');
include('../../funciones.php');

$mensaje='';

//acciones
if(isset($_POST["hacer"]))
{
    //agregar lengua
	if($_POST["hacer"]=='addlengua')
	{
		mysqli_query($conn, "INSERT INTO lenguas (Lengua)
									VALUES ('".$_POST["lengua"]."')") or die(mysqli_error($conn));

		$mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se agrego la lengua '.$_POST["lengua"].'</div>';

        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '71', '".json_encode($_POST)."')");
	}
    //editar lengua
    if($_POST["hacer"]=='editlengua')
    {
        mysqli_query($conn, "UPDATE lenguas SET Lengua='".$_POST["lengua"]."'
                                            WHERE Id='".$_POST["idlengua"]."'");

        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se actualizo la lengua '.$_POST["lengua"].'</div>';

        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '72', '".json_encode($_POST)."')");
    }
}

$cadena=$mensaje.'<table style="width:80%">
            <thead>
                <tr>
                    <td colspan="4" style="text-align: right">| '.permisos(71, '<a href="#" onclick="add_lengua()" class="liga">Nueva lengua</a>').' |</td>
                </tr>
                <tr>
                    <th colspan="4" align="center" class="textotitable">Lenguas</td>
                </tr>
                <tr>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">Lengua</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                </tr>
            </thead>
            <tbody>';
$bgcolor="#ffffff"; $J=1;
$ResLenguas=mysqli_query($conn, "SELECT * FROM lenguas ORDER BY Lengua ASC");
while($RResLen=mysqli_fetch_array($ResLenguas))
{
    $cadena.='<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$J.'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$RResLen["Lengua"].'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						'.permisos(72, '<a href="#" onclick="edit_lengua(\''.$RResLen["Id"].'\')"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>').' 
					</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						<a href="#" onclick="delete_lengua(\''.$RResLen["Id"].'\')"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo) VALUES ('".time()."', '".$_SESSION["Id"]."', '70')");

?>
<script>
//funciones ajax
function add_lengua(){
	$.ajax({
				type: 'POST',
				url : 'configuracion/lengua/add_lengua.php'
	}).done (function ( info ){
		$('#contenido2').html(info);
	});

}

function edit_lengua(lengua){
    $.ajax({
				type: 'POST',
				url : 'configuracion/lengua/edit_lengua.php',
                data: 'lengua=' + lengua
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

function delete_lengua(lengua){
    $.ajax({
				type: 'POST',
				url : 'configuracion/lengua/del_lengua.php',
                data: 'poblacion=' + poblacion
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}