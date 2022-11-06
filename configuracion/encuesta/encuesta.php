<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');
include('../../funciones.php');

$mensaje='';

//acciones
if(isset($_POST["hacer"]))
{
    //agregar edocivil
	if($_POST["hacer"]=='addpregunta')
	{
		mysqli_query($conn, "INSERT INTO preguntas_encuesta (Pregunta, Tipo)
									VALUES ('".strtoupper($_POST["pregunta"])."', '".$_POST["tipo"]."')") or die(mysqli_error($conn));

		$mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se agrego la pregunta '.$_POST["pregunta"].'</div>';

        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '90', '".json_encode($_POST)."')");
	}
    //editar edocivil
    if($_POST["hacer"]=='editpregunta')
    {
        mysqli_query($conn, "UPDATE preguntas_encuesta SET Pregunta='".strtoupper($_POST["pregunta"])."',
                                                            Tipo='".$_POST["tipo"]."' 
                                            WHERE Id='".$_POST["idpregunta"]."'");

        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se actualizo la pregunta '.$_POST["pregunta"].'</div>';

        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '91', '".json_encode($_POST)."')");
    }
}

$cadena=$mensaje.'<table style="width:80%">
            <thead>
                <tr>
                    <td colspan="4" style="text-align: right">| '.permisos(90, '<a href="#" onclick="add_pregunta()" class="liga">Nueva Pregunta</a>').' |</td>
                </tr>
                <tr>
                    <th colspan="4" align="center" class="textotitable">Encuesta de Satisfacción</td>
                </tr>
                <tr>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">Pregunta</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                </tr>
            </thead>
            <tbody>';
$bgcolor="#ffffff"; $J=1;
$ResPE=mysqli_query($conn, "SELECT * FROM preguntas_encuesta ORDER BY Id ASC");
while($RResPE=mysqli_fetch_array($ResPE))
{
    $cadena.='<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$J.'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$RResPE["Pregunta"].'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						'.permisos(92, '<a href="#" onclick="respuestas(\''.$RResPE["Id"].'\')"><i class="fas fa-poll-h"></i></a>').'
					</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        '.permisos(91, '<a href="#" onclick="edit_pregunta(\''.$RResPE["Id"].'\')"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>').' 
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
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo) VALUES ('".time()."', '".$_SESSION["Id"]."', '89')");

?>
?>
<script>
//funciones ajax
function add_pregunta(){
	$.ajax({
				type: 'POST',
				url : 'configuracion/encuesta/add_pregunta.php'
	}).done (function ( info ){
		$('#contenido2').html(info);
	});

}

function respuestas(pregunta){
    $.ajax({
				type: 'POST',
				url : 'configuracion/encuesta/respuestas.php',
                data: 'pregunta=' + pregunta
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

function edit_pregunta(pregunta){
    $.ajax({
				type: 'POST',
				url : 'configuracion/encuesta/edit_pregunta.php',
                data: 'pregunta=' + pregunta
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}