<?php
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

//0 por confimar | 1 confirmado | 2 cancelada

$anno=$_POST["anno"];

//enero
$RSEne=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM es_salud WHERE Fecha LIKE '".$anno."-01-%'"));
//febrero
$RSFeb=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM es_salud WHERE Fecha LIKE '".$anno."-02-%'"));
//marzo
$RSMar=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM es_salud WHERE Fecha LIKE '".$anno."-03-%'"));
//abril
$RSAbr=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM es_salud WHERE Fecha LIKE '".$anno."-04-%'"));
//Mayo
$RSMay=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM es_salud WHERE Fecha LIKE '".$anno."-05-%'"));
//junio
$RSJun=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM es_salud WHERE Fecha LIKE '".$anno."-06-%'"));
//julio
$RSJul=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM es_salud WHERE Fecha LIKE '".$anno."-07-%'"));
//agosto
$RSAgo=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM es_salud WHERE Fecha LIKE '".$anno."-08-%'"));
//septiembre
$RSSep=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM es_salud WHERE Fecha LIKE '".$anno."-09-%'"));
//octubre
$RSOct=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM es_salud WHERE Fecha LIKE '".$anno."-10-%'"));
//noviembre
$RSNov=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM es_salud WHERE Fecha LIKE '".$anno."-11-%'"));
//Diciembre
$RSDic=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM es_salud WHERE Fecha LIKE '".$anno."-12-%'"));
//total
$RST=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM es_salud WHERE Fecha LIKE '".$anno."-%-%'"));

$cadena='<div class="c100 card">
            <div class="c30 card">
                <h2>Estudios Socioeconomicos:</h2>
                <label class="l_form">Año: </label>
                <select name="annor" id="annor" onchange="e_socioeconomicos(this.value)">';
                for($j=2021; $j<=date("Y"); $j++)
                {
                    $cadena.='<option value="'.$j.'"';if($j==$anno){$cadena.=' selected';}$cadena.='>'.$j.'</option>';
                }
$cadena.='      </select>
                <table>
                    <thead>
                        <tr>
                            <th align="center" class="textotitable">Mes</th>
                            <th align="center" class="textotitable">Estudios</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr style="background: #fff" id="row_01">
                        <td onmouseover="row_01.style.background=\'#badad8\'" onmouseout="row_01.style.background=\'#fff\'" align="left" class="texto" valign="middle">'.permisos(101, '<a href="#" onclick="e_soc_mes(\''.$anno.'\', \'01\')">').'Ene'.permisos(101, '</a>').'</td>
                        <td onmouseover="row_01.style.background=\'#badad8\'" onmouseout="row_01.style.background=\'#fff\'" align="center" class="texto" valign="middle">'.number_format($RSEne).'</td>
                    </tr>
                    <tr style="background: #ccc" id="row_02">
                        <td onmouseover="row_02.style.background=\'#badad8\'" onmouseout="row_02.style.background=\'#ccc\'" align="left" class="texto" valign="middle">'.permisos(101, '<a href="#" onclick="e_soc_mes(\''.$anno.'\', \'02\')">').'Feb'.permisos(101, '</a>').'</td>
                        <td onmouseover="row_02.style.background=\'#badad8\'" onmouseout="row_02.style.background=\'#ccc\'" align="center" class="texto" valign="middle">'.number_format($RSFeb).'</td>
                    </tr>
                    <tr style="background: #fff" id="row_03">
                        <td onmouseover="row_03.style.background=\'#badad8\'" onmouseout="row_03.style.background=\'#fff\'" align="left" class="texto" valign="middle">'.permisos(101, '<a href="#" onclick="e_soc_mes(\''.$anno.'\', \'03\')">').'Mar'.permisos(101, '</a>').'</td>
                        <td onmouseover="row_03.style.background=\'#badad8\'" onmouseout="row_03.style.background=\'#fff\'" align="center" class="texto" valign="middle">'.number_format($RSMar).'</td>
                    </tr>
                    <tr style="background: #ccc" id="row_04">
                        <td onmouseover="row_04.style.background=\'#badad8\'" onmouseout="row_04.style.background=\'#ccc\'" align="left" class="texto" valign="middle">'.permisos(101, '<a href="#" onclick="e_soc_mes(\''.$anno.'\', \'04\')">').'Abr'.permisos(101, '</a>').'</td>
                        <td onmouseover="row_04.style.background=\'#badad8\'" onmouseout="row_04.style.background=\'#ccc\'" align="center" class="texto" valign="middle">'.number_format($RSAbr).'</td>
                    </tr>
                    <tr style="background: #fff" id="row_05">
                        <td onmouseover="row_05.style.background=\'#badad8\'" onmouseout="row_05.style.background=\'#fff\'" align="left" class="texto" valign="middle">'.permisos(101, '<a href="#" onclick="e_soc_mes(\''.$anno.'\', \'05\')">').'May'.permisos(101, '</a>').'</td>
                        <td onmouseover="row_05.style.background=\'#badad8\'" onmouseout="row_05.style.background=\'#fff\'" align="center" class="texto" valign="middle">'.number_format($RSMay).'</td>
                    </tr>
                    <tr style="background: #ccc" id="row_06">
                        <td onmouseover="row_06.style.background=\'#badad8\'" onmouseout="row_06.style.background=\'#ccc\'" align="left" class="texto" valign="middle">'.permisos(101, '<a href="#" onclick="e_soc_mes(\''.$anno.'\', \'06\')">').'Jun'.permisos(101, '</a>').'</td>
                        <td onmouseover="row_06.style.background=\'#badad8\'" onmouseout="row_06.style.background=\'#ccc\'" align="center" class="texto" valign="middle">'.number_format($RSJun).'</td>
                    </tr>
                    <tr style="background: #fff" id="row_07">
                        <td onmouseover="row_07.style.background=\'#badad8\'" onmouseout="row_07.style.background=\'#fff\'" align="left" class="texto" valign="middle">'.permisos(101, '<a href="#" onclick="e_soc_mes(\''.$anno.'\', \'07\')">').'Jul'.permisos(101, '</a>').'</td>
                        <td onmouseover="row_07.style.background=\'#badad8\'" onmouseout="row_07.style.background=\'#fff\'" align="center" class="texto" valign="middle">'.number_format($RSJul).'</td>
                    </tr>
                    <tr style="background: #ccc" id="row_08">
                        <td onmouseover="row_08.style.background=\'#badad8\'" onmouseout="row_08.style.background=\'#ccc\'" align="left" class="texto" valign="middle">'.permisos(101, '<a href="#" onclick="e_soc_mes(\''.$anno.'\', \'08\')">').'Ago'.permisos(101, '</a>').'</td>
                        <td onmouseover="row_08.style.background=\'#badad8\'" onmouseout="row_08.style.background=\'#ccc\'" align="center" class="texto" valign="middle">'.number_format($RSAgo).'</td>
                    </tr>
                    <tr style="background: #fff" id="row_09">
                        <td onmouseover="row_09.style.background=\'#badad8\'" onmouseout="row_09.style.background=\'#fff\'" align="left" class="texto" valign="middle">'.permisos(101, '<a href="#" onclick="e_soc_mes(\''.$anno.'\', \'09\')">').'Sep'.permisos(101, '</a>').'</td>
                        <td onmouseover="row_09.style.background=\'#badad8\'" onmouseout="row_09.style.background=\'#fff\'" align="center" class="texto" valign="middle">'.number_format($RSSep).'</td>
                    </tr>
                    <tr style="background: #ccc" id="row_10">
                        <td onmouseover="row_10.style.background=\'#badad8\'" onmouseout="row_10.style.background=\'#ccc\'" align="left" class="texto" valign="middle">'.permisos(101, '<a href="#" onclick="e_soc_mes(\''.$anno.'\', \'10\')">').'Oct'.permisos(101, '</a>').'</td>
                        <td onmouseover="row_10.style.background=\'#badad8\'" onmouseout="row_10.style.background=\'#ccc\'" align="center" class="texto" valign="middle">'.number_format($RSOct).'</td>
                    </tr>
                    <tr style="background: #fff" id="row_11">
                        <td onmouseover="row_11.style.background=\'#badad8\'" onmouseout="row_11.style.background=\'#fff\'" align="left" class="texto" valign="middle">'.permisos(101, '<a href="#" onclick="e_soc_mes(\''.$anno.'\', \'11\')">').'Nov'.permisos(101, '</a>').'</td>
                        <td onmouseover="row_11.style.background=\'#badad8\'" onmouseout="row_11.style.background=\'#fff\'" align="center" class="texto" valign="middle">'.number_format($RSNov).'</td>
                    </tr>
                    <tr style="background: #ccc" id="row_12">
                        <td onmouseover="row_12.style.background=\'#badad8\'" onmouseout="row_12.style.background=\'#ccc\'" align="left" class="texto" valign="middle">'.permisos(101, '<a href="#" onclick="e_soc_mes(\''.$anno.'\', \'12\')">').'Dic'.permisos(101, '</a>').'</td>
                        <td onmouseover="row_12.style.background=\'#badad8\'" onmouseout="row_12.style.background=\'#ccc\'" align="center" class="texto" valign="middle">'.number_format($RSDic).'</td>
                    </tr>
                    <tr style="background: #fff" id="row_13">
                        <td onmouseover="row_13.style.background=\'#badad8\'" onmouseout="row_13.style.background=\'#fff\'" align="left" class="texto" valign="middle">'.permisos(101, '<a href="#" onclick="e_soc_mes(\''.$anno.'\', \'%\')">').'Total'.permisos(101, '</a').'></td>
                        <td onmouseover="row_13.style.background=\'#badad8\'" onmouseout="row_13.style.background=\'#fff\'" align="center" class="texto" valign="middle">'.number_format($RST).'</td>
                    </tr>
                    </tbody> 
                </table>
            </div>
            <div class="c60 card">
                <canvas id="myChart"></canvas>
            </div>
        </div>';

        $cadena.='<script>
        var ctx = document.getElementById(\'myChart\').getContext(\'2d\');
                var myChart = new Chart(ctx, {
                    type: \'bar\',
                    data: {
                        labels: [\'Ene\', \'Feb\', \'Mar\', \'Abr\', \'May\', \'Jun\', \'Jul\', \'Ago\', \'Sep\', \'Oct\', \'Nov\', \'Dic\'],
                        datasets: [{
                            label: \'Estudios\',
                            data: [\''.$RSEne.'\', \''.$RSFeb.'\', \''.$RSMar.'\', \''.$RSAbr.'\', \''.$RSMay.'\', \''.$RSJun.'\', \''.$RSJul.'\', \''.$RSAgo.'\', \''.$RSSep.'\', \''.$RSOct.'\', \''.$RSNov.'\', \''.$RSDic.'\'],
                            backgroundColor: [
                                \'rgba(55,184,60, 0.2)\',
                                \'rgba(55,184,60, 0.2)\',
                                \'rgba(55,184,60, 0.2)\',
                                \'rgba(55,184,60, 0.2)\',
                                \'rgba(55,184,60, 0.2)\',
                                \'rgba(55,184,60, 0.2)\',
                                \'rgba(55,184,60, 0.2)\',
                                \'rgba(55,184,60, 0.2)\',
                                \'rgba(55,184,60, 0.2)\',
                                \'rgba(55,184,60, 0.2)\',
                                \'rgba(55,184,60, 0.2)\',
                                \'rgba(55,184,60, 0.2)\'
                            ],
                            borderColor: [
                                \'rgba(55,184,60, 1)\',
                                \'rgba(55,184,60, 1)\',
                                \'rgba(55,184,60, 1)\',
                                \'rgba(55,184,60, 1)\',
                                \'rgba(55,184,60, 1)\',
                                \'rgba(55,184,60, 1)\',
                                \'rgba(55,184,60, 1)\',
                                \'rgba(55,184,60, 1)\',
                                \'rgba(55,184,60, 1)\',
                                \'rgba(55,184,60, 1)\',
                                \'rgba(55,184,60, 1)\',
                                \'rgba(55,184,60, 1)\'
                            ],
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

$cadena.='<div class="c100 card" id="cont_est_soc"></div>';

//bitacora
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '137', '".json_encode($_POST)."')");

echo $cadena;

?>
<script>
function e_soc_mes(anno, mes){
    $.ajax({
				type: 'POST',
				url : 'estadisticos/esocioeco_mes.php',
                data: 'anno=' + anno + '&mes=' + mes
	}).done (function ( info ){
		$('#cont_est_soc').html(info);
	});
}
</script>