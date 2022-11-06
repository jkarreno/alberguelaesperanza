<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');
include('../../funciones.php');

$ResP=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM preguntas_encuesta WHERE Id='".$_POST["pregunta"]."' LIMIT 1"));

$mensaje='';

$mensaje='';

//acciones
if(isset($_POST["hacer"]))
{
    //agregar respuesta
	if($_POST["hacer"]=='addrespuesta')
	{
		mysqli_query($conn, "INSERT INTO preguntas_encuesta_respuestas (IdPregunta, Respuesta)
									VALUES ('".$_POST["pregunta"]."', '".strtoupper($_POST["respuesta"])."')") or die(mysqli_error($conn));

		$mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se agrego la respuesta "'.$_POST["respuesta"].'" a la pregunta "'.$ResP["Pregunta"].'"</div>';

        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '93', '".json_encode($_POST)."')");
	}
    //editar respuesta
    if($_POST["hacer"]=='editrespuesta')
    {
        mysqli_query($conn, "UPDATE preguntas_encuesta_respuestas SET Respuesta'".strtoupper($_POST["respuesta"])."'
                                            WHERE Id='".$_POST["idrespuesta"]."'");

        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se actualizo la respuesta '.$_POST["respuesta"].'</div>';

        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '94', '".json_encode($_POST)."')");
    }
}

$cadena=$mensaje.'<table style="width:80%">
            <thead>
                <tr>
                    <td colspan="4" style="text-align: right">| '.permisos(93, '<a href="#" onclick="add_respuesta(\''.$_POST["pregunta"].'\')" class="liga">Nueva Respuestsa</a>').' |</td>
                </tr>
                <tr>
                    <th colspan="4" align="center" class="textotitable">Respuestas a la pregunta: '.$ResP["Pregunta"].'</td>
                </tr>
                <tr>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">Respuesta</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                </tr>
            </thead>
            <tbody>';
$bgcolor="#ffffff"; $J=1;
$ResPER=mysqli_query($conn, "SELECT * FROM preguntas_encuesta_respuestas WHERE IdPregunta='".$ResP["Id"]."' ORDER BY Id ASC");
while($RResPER=mysqli_fetch_array($ResPER))
{
    $cadena.='<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$J.'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$RResPER["Respuesta"].'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        '.permisos(94, '<a href="#" onclick="respuestas(\''.$RResPER["Id"].'\')"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>').' 
                    </td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        <a href="#" onclick="delete_edocivil(\''.$RResEC["Id"].'\')"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
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
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo) VALUES ('".time()."', '".$_SESSION["Id"]."', '92')");

?>
<script>
//funciones ajax
function add_respuesta(pregunta){
	$.ajax({
				type: 'POST',
				url : 'configuracion/encuesta/add_respuesta.php',
                data: 'pregunta=' + pregunta
	}).done (function ( info ){
		$('#contenido2').html(info);
	});

}

//mostrar mensaje despues de los cambios
setTimeout(function() { 
    $('#mesaje').fadeOut('fast'); 
}, 1000)
</script>