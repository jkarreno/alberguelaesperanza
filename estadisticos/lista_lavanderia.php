<?php
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

$cadena='<div class="c100 card">
            <div class="c40 card">
                <h2>Lavandería</h2>
                <table>
                    <thead>
                        <tr>
                            <th align="center" class="textotitable">#</th>
                            <th align="center" class="textotitable">Prenda</th>
                            <th align="center" class="textotitable">Cantidad</th>
                            <th align="center" class="textotitable">S</th>
                            <th align="center" class="textotitable">C</th>
                            <th align="center" class="textotitable">D</th>
                            <th align="center" class="textotitable">En Uso</th>
                        </tr>
                    </thead>
                    <tbody>';
                    $l=1; $bgcolor='#fff';
$ResLavanderia=mysqli_query($conn, "SELECT * FROM lavanderia ORDER BY Id ASC");
$CRL=mysqli_num_rows($ResLavanderia);
while($RResL=mysqli_fetch_array($ResLavanderia))
{
    $ResIL=mysqli_fetch_array(mysqli_query($conn, "SELECT Cantidad FROM lavanderia_inventario WHERE PA='I' AND IdPrenda='".$RResL["Id"]."' AND ES='E' ORDER BY FECHA DESC LIMIT 1"));
    $ResSIL=mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(Cantidad) AS total FROM lavanderia_inventario WHERE (PA='P' OR PA='A') AND ES='S' AND IdPrenda='".$RResL["Id"]."'"));
    $ResCIL=mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(Cantidad) AS total FROM lavanderia_inventario WHERE (PA='P' OR PA='A') AND ES='C' AND IdPrenda='".$RResL["Id"]."'"));
    $ResEIL=mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(Cantidad) AS total FROM lavanderia_inventario WHERE (PA='P' OR PA='A') AND ES='E' AND IdPrenda='".$RResL["Id"]."'"));

    //calcular en uso
    $ResB=mysqli_fetch_array(mysqli_query($conn, "SELECT Balance FROM lavanderia_inventario WHERE (PA='P' OR PA='A') AND IdPrenda='".$RResL["Id"]."' ORDER BY Id DESC LIMIT 1"));

    $enuso=$ResIL["Cantidad"]-$ResB["Balance"];

    $cadena.='          <tr style="background: '.$bgcolor.'" id="row_'.$l.'">
                            <td onmouseover="row_'.$l.'.style.background=\'#badad8\'" onmouseout="row_'.$l.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$l.'</td>
                            <td onmouseover="row_'.$l.'.style.background=\'#badad8\'" onmouseout="row_'.$l.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.permisos(107, '<a href="#" onclick="inventario_prenda_lavanderia(\''.$RResL["Id"].'\')">'.utf8_encode($RResL["Prenda"]).'</a>').'</td>
                            <td onmouseover="row_'.$l.'.style.background=\'#badad8\'" onmouseout="row_'.$l.'.style.background=\''.$bgcolor.'\'" align="right" class="texto" valign="middle">'.$ResIL["Cantidad"].'</td>
                            <td onmouseover="row_'.$l.'.style.background=\'#badad8\'" onmouseout="row_'.$l.'.style.background=\''.$bgcolor.'\'" align="right" class="texto" valign="middle">'.$ResSIL["total"].'</td>
                            <td onmouseover="row_'.$l.'.style.background=\'#badad8\'" onmouseout="row_'.$l.'.style.background=\''.$bgcolor.'\'" align="right" class="texto" valign="middle">'.$ResCIL["total"].'</td>
                            <td onmouseover="row_'.$l.'.style.background=\'#badad8\'" onmouseout="row_'.$l.'.style.background=\''.$bgcolor.'\'" align="right" class="texto" valign="middle">'.$ResEIL["total"].'</td>
                            <td onmouseover="row_'.$l.'.style.background=\'#badad8\'" onmouseout="row_'.$l.'.style.background=\''.$bgcolor.'\'" align="right" class="texto" valign="middle">'.$enuso.'</td>
                        </tr>';
    

    if($bgcolor=='#fff'){$bgcolor='#ccc';}
    elseif($bgcolor=='#ccc'){$bgcolor='#fff';}

    $TP=$TP+$ResIL["Cantidad"];

    $labels.='\''.utf8_encode($RResL["Prenda"]).'\'';if($l<$CRL){$labels.=',';}

    //entregas
    $RPE=mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(Cantidad) AS cantidad FROM lavanderia_inventario WHERE IdPrenda='".$RResL["Id"]."' AND ES='S'"));
    $entregas.='\''.$RPE["cantidad"].'\'';if($l<$CRL){$entregas.=',';}

    //devueltas
    $RPD=mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(Cantidad) AS cantidad FROM lavanderia_inventario WHERE IdPrenda='".$RResL["Id"]."' AND ES='E' AND PA!='I'"));
    $devueltas.='\''.$RPD["cantidad"].'\'';if($l<$CRL){$devueltas.=',';}

    $l++;
}
$cadena.='              <tr style="background: '.$bgcolor.'" id="row_'.$l.'">
                            <td onmouseover="row_'.$l.'.style.background=\'#badad8\'" onmouseout="row_'.$l.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle"></td>
                            <td onmouseover="row_'.$l.'.style.background=\'#badad8\'" onmouseout="row_'.$l.'.style.background=\''.$bgcolor.'\'" align="right" class="texto" valign="middle">Total Prendas: </td>
                            <td onmouseover="row_'.$l.'.style.background=\'#badad8\'" onmouseout="row_'.$l.'.style.background=\''.$bgcolor.'\'" align="right" class="texto" valign="middle">'.number_format($TP).'</td>
                    </tbody>
                </table>
            </div>
            <div class="c50 card">
                <canvas id="myChart"></canvas>
            </div>

            <div class="c100 card" id="mov_lav">
                
            </div>
        </div>';

$cadena.='<script>
        var ctx = document.getElementById(\'myChart\').getContext(\'2d\');
                var myChart = new Chart(ctx, {
                    type: \'bar\',
                    data: {
                        labels: ['.$labels.'],
                        datasets: [{
                            label: \'Entrega\',
                            data: ['.$entregas.'],
                            backgroundColor: [';
                            for($i=1;$i<=$CRL;$i++)
                            {
                                $cadena.='\'rgba(55,184,60, 0.2)\'';
                                if($i<$CRL){$cadena.=',';}
                            }
                            $cadena.='],
                            borderColor: [';
                            for($i=1;$i<=$CRL;$i++)
                            {
                                $cadena.='\'rgba(55,184,60, 1)\'';
                                if($i<$CRL){$cadena.=',';}
                            }
                            $cadena.='],
                            borderWidth: 2
                        },
                        {
                            label: \'Devolución\',
                            data: ['.$devueltas.'],
                            backgroundColor: [';
                            for($i=1;$i<=$CRL;$i++)
                            {
                                $cadena.='\'rgba(8,100,191, 0.2)\'';
                                if($i<$CRL){$cadena.=',';}
                            }
                            $cadena.='],
                            borderColor: [';
                            for($i=1;$i<=$CRL;$i++)
                            {
                                $cadena.='\'rgba(8,100,191, 1)\'';
                                if($i<$CRL){$cadena.=',';}
                            }
                            $cadena.='],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            
        </script>';

echo $cadena;

//bitacora
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo) VALUES ('".time()."', '".$_SESSION["Id"]."', '106')");

?>
<script>
function inventario_prenda_lavanderia(prenda){
    $.ajax({
				type: 'POST',
				url : 'estadisticos/inventario_prenda_lavanderia.php',
                data: 'prenda=' + prenda
	}).done (function ( info ){
		$('#mov_lav').html(info);
	});
}
</script>