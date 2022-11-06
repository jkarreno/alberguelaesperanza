<?php
//Inicio la sesion 
session_start();

include('../conexion.php');

$ResPreguntas=mysqli_query($conn, "SELECT * FROM preguntas_encuesta ORDER BY Id ASC");

if(isset($_POST["hacer"]))
{
    if($_POST["hacer"]=="guardaencuesta")
    {
        while($RResP=mysqli_fetch_array($ResPreguntas))
        {
            if($RResP["Tipo"]==0 OR $RResP["Tipo"]==1)
            {
                mysqli_query($conn, "INSERT INTO respuestas_encuesta (IdReservacion, IdPregunta, Respuesta)
                                                            VALUES ('".$_POST["reservacion"]."', '".$RResP["Id"]."', '".strtoupper($_POST["preg_".$RResP["Id"]])."')");
            }
            elseif($RResP["Tipo"]==2)
            {
                $ResRespuestas=mysqli_query($conn, "SELECT * FROM preguntas_encuesta_respuestas WHERE IdPregunta='".$RResP["Id"]."' ORDER BY Id ASC");
                $tr=mysqli_num_rows($ResRespuestas); $a=1;
                while($RResR=mysqli_fetch_array($ResRespuestas))
                {
                    if($_POST["preg_".$RResP["Id"]."_".$RResR["Id"]]!='' AND $_POST["preg_".$RResP["Id"]."_".$RResR["Id"]]!=NULL)
                    {
                        $resp.=$_POST["preg_".$RResP["Id"]."_".$RResR["Id"]].'|';
                    }
                }

                mysqli_query($conn, "INSERT INTO respuestas_encuesta (IdReservacion, IdPregunta, Respuesta)
                                                            VALUES ('".$_POST["reservacion"]."', '".$RResP["Id"]."', '".strtoupper($resp)."')");
                
                $resp='';
            }
        }

        //bitacora
        mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '15', '".json_encode($_POST)."')");
        
    }
}

$cadena='<div class="c100 card" style="border:0; box-shadow: none;">
            <h2>Encuesta de Satisfacción</h2>';
if(!isset($_POST["hacer"]))
{
    $Respuestas=mysqli_query($conn, "SELECT Id FROM respuestas_encuesta WHERE IdReservacion='".$_POST["reservacion"]."' ORDER BY IdPregunta ASC");

    if(mysqli_num_rows($Respuestas)==0)
    {
        $cadena.='<form name="frespencuesta" id="frespencuesta">';
        while($RResP=mysqli_fetch_array($ResPreguntas))
        {
            $cadena.='<div class="c100" style="border-bottom: 1px solid #ccc; margin-bottom: 10px;" >
                        <label class="l_form">'.$RResP["Pregunta"].'</label>';
            if($RResP["Tipo"]==0)
            {
                $cadena.='<textarea name="preg_'.$RResP["Id"].'" id="preg_'.$RResP["Id"].'"></textarea>';
            }
            elseif($RResP["Tipo"]==1)
            {
                $ResRespuestas=mysqli_query($conn, "SELECT * FROM preguntas_encuesta_respuestas WHERE IdPregunta='".$RResP["Id"]."' ORDER BY Id ASC");
                while($RResR=mysqli_fetch_array($ResRespuestas))
                {
                    $cadena.='<input type="radio" name="preg_'.$RResP["Id"].'" id="preg_'.$RResP["Id"].'" value="'.$RResR["Id"].'"> '.$RResR["Respuesta"].' ';
                }
            }
            elseif($RResP["Tipo"]==2)
            {
                $ResRespuestas=mysqli_query($conn, "SELECT * FROM preguntas_encuesta_respuestas WHERE IdPregunta='".$RResP["Id"]."' ORDER BY Id ASC");
                while($RResR=mysqli_fetch_array($ResRespuestas))
                {
                    $cadena.='<input type="checkbox" name="preg_'.$RResP["Id"].'_'.$RResR["Id"].'" id="preg_'.$RResP["Id"].'_'.$RResR["Id"].'" value="'.$RResR["Id"].'"> '.$RResR["Respuesta"].' ';
                }
            }
            $cadena.='</div>';
        }
        $cadena.='  <div class="c100">
                        <input type="hidden" name="hacer" id="hacer" value="guardaencuesta">
                        <input type="hidden" name="reservacion" id="reservacion" value="'.$_POST["reservacion"].'">
                        <input type="submit" name="botencuesta" id="botencuesta" value="Guardar>>">
                    </div>
                    </form>';
    }
    else
    {
        while($RResP=mysqli_fetch_array($ResPreguntas))
        {
            $Respuesta=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM respuestas_encuesta WHERE IdReservacion='".$_POST["reservacion"]."' AND IdPregunta='".$RResP["Id"]."' LIMIT 1"));

            $cadena.='<div class="c100" style="border-bottom: 1px solid #ccc; margin-bottom: 10px;" >
                        <label class="l_form"><strong>'.$RResP["Pregunta"].'</strong></label>';
            if($RResP["Tipo"]==0)
            {
                $cadena.='<label class="l_form">'.$Respuesta["Respuesta"].'</label>';
            }
            elseif($RResP["Tipo"]==1)
            {
                $Res=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM preguntas_encuesta_respuestas WHERE IdPregunta='".$RResP["Id"]."' AND Id='".$Respuesta["Respuesta"]."'"));
                $cadena.='<label class="l_form">'.$Res["Respuesta"].'</label>';
            }
            elseif($RResP["Tipo"]==2)
            {
                $array=explode('|', $Respuesta["Respuesta"]);
                $res=count($array);
                for($k=0; $k<($res-1); $k++)
                {
                    $Res=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM preguntas_encuesta_respuestas WHERE IdPregunta='".$RResP["Id"]."' AND Id='".$array[$k]."'"));
                    $respuesta.=$Res["Respuesta"];
                    //$respuesta.=$array[$k];

                    if($k<($res-2)){$respuesta.=', ';}
                }
                $cadena.='<label class="l_form">'.$respuesta.'</label>';

                //unset($array);
                $respuesta='';
            }
            $cadena.='</div>';
        }
    }
}
    
$cadena.='</div>';

echo $cadena;

?>
<script>
$("#frespencuesta").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("frespencuesta"));

	$.ajax({
		url: "reservaciones/encuesta.php",
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
