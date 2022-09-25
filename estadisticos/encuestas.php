<?php
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

$ResPreguntas=mysqli_query($conn, "SELECT * FROM preguntas_encuesta ORDER BY Id ASC");

$cadena='<div class="c100 card">
            <h2>Resultados Encuesta</h2>';
while($RResP=mysqli_fetch_array($ResPreguntas))
{
    $cadena.='<div class="c100 card">
                <h2>'.$RResP["Pregunta"].'</h2>';
    if($RResP["Tipo"]==0)
    {
        $cadena.='<div class="c100">';
        $ResRespuestas=mysqli_query($conn, "SELECT * FROM respuestas_encuesta WHERE IdPregunta='".$RResP["Id"]."' ORDER BY Id ASC");
        while($RResR=mysqli_fetch_array($ResRespuestas))
        {
            $cadena.='<label class="l_form">'.$RResR["Respuesta"].'</label>';
        }
        $cadena.='</div>';
    }
    else
    {
        $cadena.='<div class="c50">';
        $ResRespuestas=mysqli_query($conn, "SELECT * FROM preguntas_encuesta_respuestas WHERE IdPregunta='".$RResP["Id"]."' ORDER BY Id ASC");
        $TR=mysqli_num_rows($ResRespuestas); $i=1;
        while($RResR=mysqli_fetch_array($ResRespuestas))
        {
            $cadena.='<label class="l_form"><strong>'.$RResR["Respuesta"].'</strong> - ';

            if($RResP["Tipo"]==1)
            {
                $ResResp=mysqli_query($conn, "SELECT Id FROM respuestas_encuesta WHERE Respuesta='".$RResR["Id"]."' AND IdPregunta='".$RResP["Id"]."'");
            }
            elseif($RResP["Tipo"]==2)
            {
                $ResResp=mysqli_query($conn, "SELECT Id FROM respuestas_encuesta WHERE Respuesta LIKE '%".$RResR["Id"]."|%' AND IdPregunta='".$RResP["Id"]."'");
            }
            
            $TotRR=mysqli_num_rows($ResResp);

            $datasets.='{
                label:"'.$RResR["Respuesta"].'",
                data:['.$TotRR.'],
                backgroundColor: [\'rgba(55,184,60, 0.2)\'],
                borderColor: [\'rgba(55,184,60, 1)\'],
                borderWidth: [2]
            }';
            if($i<($TR)){$datasets.=',';} $i++;

            $cadena.=$TotRR.'</label>';
        }
        $cadena.='</div>
                <div class="c50">
                    <canvas id="myChart_pregunta_'.$RResP["Id"].'"></canvas>
                </div>';

        $cadena.='<script>
                    var canvas = document.getElementById("myChart_pregunta_'.$RResP["Id"].'");
                    var res = canvas.getContext(\'2d\');

                    var data = {
                        labels: ["'.$RResP["Pregunta"].'"],
                        datasets: ['.$datasets.']
                    };

                    var options = {
                            rotation: -0.7 * Math.PI
                    };

                    var myPieChartRes = new Chart(res, {
                        type: \'bar\',
                        data: data,
                        options: options
                    });

                </script>';  
                
        $datasets='';
    }
$cadena.='  </div>';
}
$cadena.='</div>';

echo $cadena;

//bitacora
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo) VALUES ('".time()."', '".$_SESSION["Id"]."', '108')");