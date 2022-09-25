<?php
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

//0 por confimar | 1 confirmado | 2 cancelada

$anno=$_POST["anno"];

//enero
$RPCEne=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Fecha LIKE '".$anno."-01-%' AND Estatus='0'"));
$RCOEne=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Fecha LIKE '".$anno."-01-%' AND Estatus='1'"));
$RCAEne=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Fecha LIKE '".$anno."-01-%' AND Estatus='2'"));
//febrero
$RPCFeb=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Fecha LIKE '".$anno."-02-%' AND Estatus='0'"));
$RCOFeb=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Fecha LIKE '".$anno."-02-%' AND Estatus='1'"));
$RCAFeb=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Fecha LIKE '".$anno."-02-%' AND Estatus='2'"));
//marzo
$RPCMar=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Fecha LIKE '".$anno."-03-%' AND Estatus='0'"));
$RCOMar=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Fecha LIKE '".$anno."-03-%' AND Estatus='1'"));
$RCAMar=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Fecha LIKE '".$anno."-03-%' AND Estatus='2'"));
//abril
$RPCAbr=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Fecha LIKE '".$anno."-04-%' AND Estatus='0'"));
$RCOAbr=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Fecha LIKE '".$anno."-04-%' AND Estatus='1'"));
$RCAAbr=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Fecha LIKE '".$anno."-04-%' AND Estatus='2'"));
//mayo
$RPCMay=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Fecha LIKE '".$anno."-05-%' AND Estatus='0'"));
$RCOMay=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Fecha LIKE '".$anno."-05-%' AND Estatus='1'"));
$RCAMay=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Fecha LIKE '".$anno."-05-%' AND Estatus='2'"));
//junio
$RPCJun=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Fecha LIKE '".$anno."-06-%' AND Estatus='0'"));
$RCOJun=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Fecha LIKE '".$anno."-06-%' AND Estatus='1'"));
$RCAJun=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Fecha LIKE '".$anno."-06-%' AND Estatus='2'"));
//julio
$RPCJul=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Fecha LIKE '".$anno."-07-%' AND Estatus='0'"));
$RCOJul=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Fecha LIKE '".$anno."-07-%' AND Estatus='1'"));
$RCAJul=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Fecha LIKE '".$anno."-07-%' AND Estatus='2'"));
//agosto
$RPCAgo=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Fecha LIKE '".$anno."-08-%' AND Estatus='0'"));
$RCOAgo=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Fecha LIKE '".$anno."-08-%' AND Estatus='1'"));
$RCAAgo=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Fecha LIKE '".$anno."-08-%' AND Estatus='2'"));
//septiembre
$RPCSep=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Fecha LIKE '".$anno."-09-%' AND Estatus='0'"));
$RCOSep=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Fecha LIKE '".$anno."-09-%' AND Estatus='1'"));
$RCASep=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Fecha LIKE '".$anno."-09-%' AND Estatus='2'"));
//cotubre
$RPCOct=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Fecha LIKE '".$anno."-10-%' AND Estatus='0'"));
$RCOOct=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Fecha LIKE '".$anno."-10-%' AND Estatus='1'"));
$RCAOct=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Fecha LIKE '".$anno."-10-%' AND Estatus='2'"));
//noviembre
$RPCNov=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Fecha LIKE '".$anno."-11-%' AND Estatus='0'"));
$RCONov=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Fecha LIKE '".$anno."-11-%' AND Estatus='1'"));
$RCANov=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Fecha LIKE '".$anno."-11-%' AND Estatus='2'"));
//diciembre
$RPCDic=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Fecha LIKE '".$anno."-12-%' AND Estatus='0'"));
$RCODic=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Fecha LIKE '".$anno."-12-%' AND Estatus='1'"));
$RCADic=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Fecha LIKE '".$anno."-12-%' AND Estatus='2'"));
//Totales
$RPCTot=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Estatus='0'"));
$RCOTot=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Estatus='1'"));
$RCATot=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE Estatus='2'"));

$cadena='<div class="c100 card">
            <div class="c30 card">
                <h2>Reservaciones</h2>
                <label class="l_form">Año: </label>
                <select name="annor" id="annor" onchange="e_reservaciones(this.value)">';
                for($j=2021; $j<=date("Y"); $j++)
                {
                    $cadena.='<option value="'.$j.'"';if($j==$anno){$cadena.=' selected';}$cadena.='>'.$j.'</option>';
                }
$cadena.='      </select>
                <table>
                    <thead>
                        <tr>
                            <th align="center" class="textotitable">Mes</th>
                            <th align="center" class="textotitable">PC</th>
                            <th align="center" class="textotitable">CO</th>
                            <th align="center" class="textotitable">CA</th>
                            <th align="center" class="textotitable">T</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr style="background: #fff" id="row_01">
                        <td onmouseover="row_01.style.background=\'#badad8\'" onmouseout="row_01.style.background=\'#fff\'" align="left" class="texto" valign="middle">'.permisos(101, '<a href="#" onclick="e_res_mes(\''.$anno.'\', \'01\')">').'Ene'.permisos(101, '</a>').'</td>
                        <td onmouseover="row_01.style.background=\'#badad8\'" onmouseout="row_01.style.background=\'#fff\'" align="center" class="texto" valign="middle">'.number_format($RPCEne).'</td>
                        <td onmouseover="row_01.style.background=\'#badad8\'" onmouseout="row_01.style.background=\'#fff\'" align="center" class="texto" valign="middle">'.number_format($RCOEne).'</td>
                        <td onmouseover="row_01.style.background=\'#badad8\'" onmouseout="row_01.style.background=\'#fff\'" align="center" class="texto" valign="middle">'.number_format($RCAEne).'</td>
                        <td onmouseover="row_01.style.background=\'#badad8\'" onmouseout="row_01.style.background=\'#fff\'" align="center" class="texto" valign="middle">'.number_format(($RPCEne+$RCOEne+$RCAEne)).'</td>
                    </tr>
                    <tr style="background: #ccc" id="row_02">
                        <td onmouseover="row_02.style.background=\'#badad8\'" onmouseout="row_02.style.background=\'#ccc\'" align="left" class="texto" valign="middle">'.permisos(101, '<a href="#" onclick="e_res_mes(\''.$anno.'\', \'02\')">').'Feb'.permisos(101, '</a>').'</td>
                        <td onmouseover="row_02.style.background=\'#badad8\'" onmouseout="row_02.style.background=\'#ccc\'" align="center" class="texto" valign="middle">'.number_format($RPCFeb).'</td>
                        <td onmouseover="row_02.style.background=\'#badad8\'" onmouseout="row_02.style.background=\'#ccc\'" align="center" class="texto" valign="middle">'.number_format($RCOFeb).'</td>
                        <td onmouseover="row_02.style.background=\'#badad8\'" onmouseout="row_02.style.background=\'#ccc\'" align="center" class="texto" valign="middle">'.number_format($RCAFeb).'</td>
                        <td onmouseover="row_02.style.background=\'#badad8\'" onmouseout="row_02.style.background=\'#ccc\'" align="center" class="texto" valign="middle">'.number_format(($RPCFeb+$RCOFeb+$RCAFeb)).'</td>
                    </tr>
                    <tr style="background: #fff" id="row_03">
                        <td onmouseover="row_03.style.background=\'#badad8\'" onmouseout="row_03.style.background=\'#fff\'" align="left" class="texto" valign="middle">'.permisos(101, '<a href="#" onclick="e_res_mes(\''.$anno.'\', \'03\')">').'Mar'.permisos(101, '</a>').'</td>
                        <td onmouseover="row_03.style.background=\'#badad8\'" onmouseout="row_03.style.background=\'#fff\'" align="center" class="texto" valign="middle">'.number_format($RPCMar).'</td>
                        <td onmouseover="row_03.style.background=\'#badad8\'" onmouseout="row_03.style.background=\'#fff\'" align="center" class="texto" valign="middle">'.number_format($RCOMar).'</td>
                        <td onmouseover="row_03.style.background=\'#badad8\'" onmouseout="row_03.style.background=\'#fff\'" align="center" class="texto" valign="middle">'.number_format($RCAMar).'</td>
                        <td onmouseover="row_03.style.background=\'#badad8\'" onmouseout="row_03.style.background=\'#fff\'" align="center" class="texto" valign="middle">'.number_format(($RPCMar+$RCOMar+$RCAMar)).'</td>
                    </tr>
                    <tr style="background: #ccc" id="row_04">
                        <td onmouseover="row_04.style.background=\'#badad8\'" onmouseout="row_04.style.background=\'#ccc\'" align="left" class="texto" valign="middle">'.permisos(101, '<a href="#" onclick="e_res_mes(\''.$anno.'\', \'04\')">').'Abr'.permisos(101, '</a>').'</td>
                        <td onmouseover="row_04.style.background=\'#badad8\'" onmouseout="row_04.style.background=\'#ccc\'" align="center" class="texto" valign="middle">'.number_format($RPCAbr).'</td>
                        <td onmouseover="row_04.style.background=\'#badad8\'" onmouseout="row_04.style.background=\'#ccc\'" align="center" class="texto" valign="middle">'.number_format($RCOAbr).'</td>
                        <td onmouseover="row_04.style.background=\'#badad8\'" onmouseout="row_04.style.background=\'#ccc\'" align="center" class="texto" valign="middle">'.number_format($RCAAbr).'</td>
                        <td onmouseover="row_04.style.background=\'#badad8\'" onmouseout="row_04.style.background=\'#ccc\'" align="center" class="texto" valign="middle">'.number_format(($RPCAbr+$RCOAbr+$RCAAbr)).'</td>
                    </tr>
                    <tr style="background: #fff" id="row_05">
                        <td onmouseover="row_05.style.background=\'#badad8\'" onmouseout="row_05.style.background=\'#fff\'" align="left" class="texto" valign="middle">'.permisos(101, '<a href="#" onclick="e_res_mes(\''.$anno.'\', \'05\')">').'May'.permisos(101, '</a>').'</td>
                        <td onmouseover="row_05.style.background=\'#badad8\'" onmouseout="row_05.style.background=\'#fff\'" align="center" class="texto" valign="middle">'.number_format($RPCMay).'</td>
                        <td onmouseover="row_05.style.background=\'#badad8\'" onmouseout="row_05.style.background=\'#fff\'" align="center" class="texto" valign="middle">'.number_format($RCOMay).'</td>
                        <td onmouseover="row_05.style.background=\'#badad8\'" onmouseout="row_05.style.background=\'#fff\'" align="center" class="texto" valign="middle">'.number_format($RCAMay).'</td>
                        <td onmouseover="row_05.style.background=\'#badad8\'" onmouseout="row_05.style.background=\'#fff\'" align="center" class="texto" valign="middle">'.number_format(($RPCMay+$RCOMay+$RCAMay)).'</td>
                    </tr>
                    <tr style="background: #ccc" id="row_06">
                        <td onmouseover="row_06.style.background=\'#badad8\'" onmouseout="row_06.style.background=\'#ccc\'" align="left" class="texto" valign="middle">'.permisos(101, '<a href="#" onclick="e_res_mes(\''.$anno.'\', \'06\')">').'Jun'.permisos(101, '</a>').'</td>
                        <td onmouseover="row_06.style.background=\'#badad8\'" onmouseout="row_06.style.background=\'#ccc\'" align="center" class="texto" valign="middle">'.number_format($RPCJun).'</td>
                        <td onmouseover="row_06.style.background=\'#badad8\'" onmouseout="row_06.style.background=\'#ccc\'" align="center" class="texto" valign="middle">'.number_format($RCOJun).'</td>
                        <td onmouseover="row_06.style.background=\'#badad8\'" onmouseout="row_06.style.background=\'#ccc\'" align="center" class="texto" valign="middle">'.number_format($RCAJun).'</td>
                        <td onmouseover="row_06.style.background=\'#badad8\'" onmouseout="row_06.style.background=\'#ccc\'" align="center" class="texto" valign="middle">'.number_format(($RPCJun+$RCOJun+$RCAJun)).'</td>
                    </tr>
                    <tr style="background: #fff" id="row_07">
                        <td onmouseover="row_07.style.background=\'#badad8\'" onmouseout="row_07.style.background=\'#fff\'" align="left" class="texto" valign="middle">'.permisos(101, '<a href="#" onclick="e_res_mes(\''.$anno.'\', \'07\')">').'Jul'.permisos(101, '</a>').'</td>
                        <td onmouseover="row_07.style.background=\'#badad8\'" onmouseout="row_07.style.background=\'#fff\'" align="center" class="texto" valign="middle">'.number_format($RPCJul).'</td>
                        <td onmouseover="row_07.style.background=\'#badad8\'" onmouseout="row_07.style.background=\'#fff\'" align="center" class="texto" valign="middle">'.number_format($RCOJul).'</td>
                        <td onmouseover="row_07.style.background=\'#badad8\'" onmouseout="row_07.style.background=\'#fff\'" align="center" class="texto" valign="middle">'.number_format($RCAJul).'</td>
                        <td onmouseover="row_07.style.background=\'#badad8\'" onmouseout="row_07.style.background=\'#fff\'" align="center" class="texto" valign="middle">'.number_format(($RPCJul+$RCOJul+$RCAJul)).'</td>
                    </tr>
                    <tr style="background: #ccc" id="row_08">
                        <td onmouseover="row_08.style.background=\'#badad8\'" onmouseout="row_08.style.background=\'#ccc\'" align="left" class="texto" valign="middle">'.permisos(101, '<a href="#" onclick="e_res_mes(\''.$anno.'\', \'08\')">').'Ago'.permisos(101, '</a>').'</td>
                        <td onmouseover="row_08.style.background=\'#badad8\'" onmouseout="row_08.style.background=\'#ccc\'" align="center" class="texto" valign="middle">'.number_format($RPCAgo).'</td>
                        <td onmouseover="row_08.style.background=\'#badad8\'" onmouseout="row_08.style.background=\'#ccc\'" align="center" class="texto" valign="middle">'.number_format($RCOAgo).'</td>
                        <td onmouseover="row_08.style.background=\'#badad8\'" onmouseout="row_08.style.background=\'#ccc\'" align="center" class="texto" valign="middle">'.number_format($RCAAgo).'</td>
                        <td onmouseover="row_08.style.background=\'#badad8\'" onmouseout="row_08.style.background=\'#ccc\'" align="center" class="texto" valign="middle">'.number_format(($RPCAgo+$RCOAgo+$RCAAgo)).'</td>
                    </tr>
                    <tr style="background: #fff" id="row_09">
                        <td onmouseover="row_09.style.background=\'#badad8\'" onmouseout="row_09.style.background=\'#fff\'" align="left" class="texto" valign="middle">'.permisos(101, '<a href="#" onclick="e_res_mes(\''.$anno.'\', \'09\')">').'Sep'.permisos(101, '</a>').'</td>
                        <td onmouseover="row_09.style.background=\'#badad8\'" onmouseout="row_09.style.background=\'#fff\'" align="center" class="texto" valign="middle">'.number_format($RPCSep).'</td>
                        <td onmouseover="row_09.style.background=\'#badad8\'" onmouseout="row_09.style.background=\'#fff\'" align="center" class="texto" valign="middle">'.number_format($RCOSep).'</td>
                        <td onmouseover="row_09.style.background=\'#badad8\'" onmouseout="row_09.style.background=\'#fff\'" align="center" class="texto" valign="middle">'.number_format($RCASep).'</td>
                        <td onmouseover="row_09.style.background=\'#badad8\'" onmouseout="row_09.style.background=\'#fff\'" align="center" class="texto" valign="middle">'.number_format(($RPCSep+$RCOSep+$RCASep)).'</td>
                    </tr>
                    <tr style="background: #ccc" id="row_10">
                        <td onmouseover="row_10.style.background=\'#badad8\'" onmouseout="row_10.style.background=\'#ccc\'" align="left" class="texto" valign="middle">'.permisos(101, '<a href="#" onclick="e_res_mes(\''.$anno.'\', \'10\')">').'Oct'.permisos(101, '</a>').'</td>
                        <td onmouseover="row_10.style.background=\'#badad8\'" onmouseout="row_10.style.background=\'#ccc\'" align="center" class="texto" valign="middle">'.number_format($RPCOct).'</td>
                        <td onmouseover="row_10.style.background=\'#badad8\'" onmouseout="row_10.style.background=\'#ccc\'" align="center" class="texto" valign="middle">'.number_format($RCOOct).'</td>
                        <td onmouseover="row_10.style.background=\'#badad8\'" onmouseout="row_10.style.background=\'#ccc\'" align="center" class="texto" valign="middle">'.number_format($RCAOct).'</td>
                        <td onmouseover="row_10.style.background=\'#badad8\'" onmouseout="row_10.style.background=\'#ccc\'" align="center" class="texto" valign="middle">'.number_format(($RPCOct+$RCOOct+$RCAOct)).'</td>
                    </tr>
                    <tr style="background: #fff" id="row_11">
                        <td onmouseover="row_11.style.background=\'#badad8\'" onmouseout="row_11.style.background=\'#fff\'" align="left" class="texto" valign="middle">'.permisos(101, '<a href="#" onclick="e_res_mes(\''.$anno.'\', \'11\')">').'Nov'.permisos(101, '</a>').'</td>
                        <td onmouseover="row_11.style.background=\'#badad8\'" onmouseout="row_11.style.background=\'#fff\'" align="center" class="texto" valign="middle">'.number_format($RPCNov).'</td>
                        <td onmouseover="row_11.style.background=\'#badad8\'" onmouseout="row_11.style.background=\'#fff\'" align="center" class="texto" valign="middle">'.number_format($RCONov).'</td>
                        <td onmouseover="row_11.style.background=\'#badad8\'" onmouseout="row_11.style.background=\'#fff\'" align="center" class="texto" valign="middle">'.number_format($RCANov).'</td>
                        <td onmouseover="row_11.style.background=\'#badad8\'" onmouseout="row_11.style.background=\'#fff\'" align="center" class="texto" valign="middle">'.number_format(($RPCNov+$RCONov+$RCANov)).'</td>
                    </tr>
                    <tr style="background: #ccc" id="row_12">
                        <td onmouseover="row_12.style.background=\'#badad8\'" onmouseout="row_12.style.background=\'#ccc\'" align="left" class="texto" valign="middle">'.permisos(101, '<a href="#" onclick="e_res_mes(\''.$anno.'\', \'12\')">').'Dic'.permisos(101, '</a>').'</td>
                        <td onmouseover="row_12.style.background=\'#badad8\'" onmouseout="row_12.style.background=\'#ccc\'" align="center" class="texto" valign="middle">'.number_format($RPCDic).'</td>
                        <td onmouseover="row_12.style.background=\'#badad8\'" onmouseout="row_12.style.background=\'#ccc\'" align="center" class="texto" valign="middle">'.number_format($RCODic).'</td>
                        <td onmouseover="row_12.style.background=\'#badad8\'" onmouseout="row_12.style.background=\'#ccc\'" align="center" class="texto" valign="middle">'.number_format($RCADic).'</td>
                        <td onmouseover="row_12.style.background=\'#badad8\'" onmouseout="row_12.style.background=\'#ccc\'" align="center" class="texto" valign="middle">'.number_format(($RPCDic+$RCODic+$RCADic)).'</td>
                    </tr>
                    <tr style="background: #fff" id="row_13">
                        <td onmouseover="row_13.style.background=\'#badad8\'" onmouseout="row_13.style.background=\'#fff\'" align="left" class="texto" valign="middle">'.permisos(101, '<a href="#" onclick="e_res_mes(\''.$anno.'\', \'%\')">').'Total'.permisos(101, '</a').'></td>
                        <td onmouseover="row_13.style.background=\'#badad8\'" onmouseout="row_13.style.background=\'#fff\'" align="center" class="texto" valign="middle">'.number_format($RPCTot).'</td>
                        <td onmouseover="row_13.style.background=\'#badad8\'" onmouseout="row_13.style.background=\'#fff\'" align="center" class="texto" valign="middle">'.number_format($RCOTot).'</td>
                        <td onmouseover="row_13.style.background=\'#badad8\'" onmouseout="row_13.style.background=\'#fff\'" align="center" class="texto" valign="middle">'.number_format($RCATot).'</td>
                        <td onmouseover="row_13.style.background=\'#badad8\'" onmouseout="row_13.style.background=\'#fff\'" align="center" class="texto" valign="middle">'.number_format(($RPCTot+$RCOTot+$RCATot)).'</td>
                    </tr>
                    <tr style="background: #ccc" id="row14">
                        <td colspan="5" onmouseover="row_14.style.background=\'#badad8\'" onmouseout="row_14.style.background=\'#fff\'" align="center" class="texto" valign="middle">'.permisos(105, '<a href="#" onclick="rep_pers()">Reporte Personalizado</a>').'</td>
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
                    label: \'Estancias\',
                    data: [\''.$RCOEne.'\', \''.$RCOFeb.'\', \''.$RCOMar.'\', \''.$RCOAbr.'\', \''.$RCOMay.'\', \''.$RCOJun.'\', \''.$RCOJul.'\', \''.$RCOAgo.'\', \''.$RCOSep.'\', \''.$RCOOct.'\', \''.$RCONov.'\', \''.$RCODic.'\'],
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
                },
                {
                    label: \'Canceladas\',
                    data: [\''.$RCAEne.'\', \''.$RCAFeb.'\', \''.$RCAMar.'\', \''.$RCAAbr.'\', \''.$RCAMay.'\', \''.$RCAJun.'\', \''.$RCAJul.'\', \''.$RCAAgo.'\', \''.$RCASep.'\', \''.$RCAOct.'\', \''.$RCANov.'\', \''.$RCADic.'\'],
                    backgroundColor: [
                        \'rgba(255,0,0, 0.2)\',
                        \'rgba(255,0,0, 0.2)\',
                        \'rgba(255,0,0, 0.2)\',
                        \'rgba(255,0,0, 0.2)\',
                        \'rgba(255,0,0, 0.2)\',
                        \'rgba(255,0,0, 0.2)\',
                        \'rgba(255,0,0, 0.2)\',
                        \'rgba(255,0,0, 0.2)\',
                        \'rgba(255,0,0, 0.2)\',
                        \'rgba(255,0,0, 0.2)\',
                        \'rgba(255,0,0, 0.2)\',
                        \'rgba(255,0,0, 0.2)\'
                    ],
                    borderColor: [
                        \'rgba(255,0,0, 1)\'
                    ],
                    borderWidth: 2
                },
                {
                    label: \'Por Confirmar\',
                    data: [\''.$RPCEne.'\', \''.$RPCFeb.'\', \''.$RPCMar.'\', \''.$RPCAbr.'\', \''.$RPCMay.'\', \''.$RPCJun.'\', \''.$RPCJul.'\', \''.$RPCAgo.'\', \''.$RPCSep.'\', \''.$RPCOct.'\', \''.$RPCNov.'\', \''.$RPCDic.'\'],
                    backgroundColor: [
                        \'rgba(243,243,21, 0.2)\',
                        \'rgba(243,243,21, 0.2)\',
                        \'rgba(243,243,21, 0.2)\',
                        \'rgba(243,243,21, 0.2)\',
                        \'rgba(243,243,21, 0.2)\',
                        \'rgba(243,243,21, 0.2)\',
                        \'rgba(243,243,21, 0.2)\',
                        \'rgba(243,243,21, 0.2)\',
                        \'rgba(243,243,21, 0.2)\',
                        \'rgba(243,243,21, 0.2)\',
                        \'rgba(243,243,21, 0.2)\',
                        \'rgba(243,243,21, 0.2)\'
                    ],
                    borderColor: [
                        \'rgba(243,243,21, 1)\',
                        \'rgba(243,243,21, 1)\',
                        \'rgba(243,243,21, 1)\',
                        \'rgba(243,243,21, 1)\',
                        \'rgba(243,243,21, 1)\',
                        \'rgba(243,243,21, 1)\',
                        \'rgba(243,243,21, 1)\',
                        \'rgba(243,243,21, 1)\',
                        \'rgba(243,243,21, 1)\',
                        \'rgba(243,243,21, 1)\',
                        \'rgba(243,243,21, 1)\',
                        \'rgba(243,243,21, 1)\',
                        \'rgba(243,243,21, 1)\'
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

$cadena.='<div class="c100 card" id="cont_mes_res"></div>';

echo $cadena;

//bitacora
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '100', '".json_encode($_POST)."')");

?>
<script>
function e_reservaciones(anno){
    $.ajax({
				type: 'POST',
				url : 'estadisticos/reservaciones.php',
                data: 'anno=' + anno
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}
function e_res_mes(anno, mes){
    $.ajax({
				type: 'POST',
				url : 'estadisticos/reservaciones_mes.php',
                data: 'anno=' + anno + '&mes=' + mes
	}).done (function ( info ){
		$('#cont_mes_res').html(info);
	});
}
function rep_pers(){
    $.ajax({
				type: 'POST',
				url : 'estadisticos/reporte_personalizado.php'
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}
</script>