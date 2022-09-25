<?php
date_default_timezone_set('America/Mexico_City');
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');



if(isset($_POST["busbit"]))
{
    if($_POST["fechai"]!=''){$fechai=strtotime($_POST["fechai"].' 00:00:00');}else{$fechai=strtotime(date("Y-m-d".' 00:00:00')."- 365 days");}
    if($_POST["fechaf"]!=''){$fechaf=strtotime($_POST["fechaf"].' 23:59:59');}else{$fechaf=strtotime(date("Y-m-d".' 23:59:59'));}

    $ResBitacora=mysqli_query($conn, "SELECT * FROM bitacora WHERE FechaHora >= '".$fechai."' AND FechaHora <= '".$fechaf."' AND IdUser LIKE '".$_POST["usuario"]."' 
                                        AND Hizo LIKE '".$_POST["accion"]."' ORDER BY FechaHora DESC");
}
else
{
    $dias=date("Y-m-d",strtotime(date("Y-m-d")."- 31 days"));

    $fecha=strtotime($dias.' 23:59:59');

    $ResBitacora=mysqli_query($conn, "SELECT * FROM bitacora WHERE FechaHora > '".$fecha."' ORDER BY FechaHora DESC LIMIT 100");
}


$cadena='<div class="c100 card">
            <h2>Bitacora</h2>
            <table>
            <thead>
                <tr>
                    <td colspan="4">
                        <div class="c100 card" style="border: 0;box-shadow: none;margin: 0;padding: 0;">
                            <form name="fbusbit" id="fbusbit">
                                <div class="c20">
                                    <label class="l_form">Desde: </label>
                                    <input type="date" name="fechai" id="fechai"';if(isset($_POST["busbit"])){$cadena.=' value="'.$_POST["fechai"].'"';}$cadena.='>
                                </div>
                                <div class="c20">
                                    <label class="l_form">Hasta: </label>
                                    <input type="date" name="fechaf" id="fechaf"';if(isset($_POST["busbit"])){$cadena.=' value="'.$_POST["fechaf"].'"';}$cadena.='>
                                </div>
                                <div class="c20">
                                    <label class="l_form">Usuario: </label>
                                    <select name="usuario" id="usuario">
                                        <option value="%">Todos</option>';
$ResUsuarios=mysqli_query($conn, "SELECT Id, Nombre FROM usuarios ORDER BY Nombre ASC");
while($RResU=mysqli_fetch_array($ResUsuarios))
{
    $cadena.='                          <option value="'.$RResU["Id"].'"';if(isset($_POST["busbit"])){if($RResU["Id"]==$_POST["usuario"]){$cadena.=' selected';}}$cadena.='>'.$RResU["Nombre"].'</option>';
}
$cadena.='                          </select>
                                </div>
                                <div class="c20">
                                    <label class="l_form">Acción: </label>
                                    <select name="accion" id="accion">
                                        <option value="%">Todas</option>';
$ResAcciones=mysqli_query($conn, "SELECT * FROM bitacora_accion ORDER BY Descripcion ASC");
while($RResA=mysqli_fetch_array($ResAcciones))
{
    $cadena.='                          <option value="'.$RResA["Id"].'"';if(isset($_POST["busbit"])){if($RResA["Id"]==$_POST["accion"]){$cadena.=' selected';}}$cadena.='>'.utf8_encode($RResA["Descripcion"]).'</option>';
}
$cadena.='                          </select>
                                </div>
                                <div class="c10">
                                    <input type="hidden" name="busbit" id="busbit" value="1">
                                    <input type="submit" name="botbusbit" id="botbusbit" value="Buscar>>">
                                </div>
                            </form>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th align="center" class="textotitable">Fecha</th>
                    <th align="center" class="textotitable">Usuario</th>
                    <th align="center" class="textotitable">Acción</th>
                    <th align="center" class="textotitable">Datos</th>
                </tr>
            </thead>
            <tbody>';
$bgcolor="#FFFFFF"; $J=1;
while($RResBit=mysqli_fetch_array($ResBitacora))
{
    $epoch = $RResBit["FechaHora"];
    $ResU=mysqli_fetch_array(mysqli_query($conn, "SELECT Nombre FROM usuarios  WHERE Id='".$RResBit["IdUser"]."' LIMIT 1"));
    $ResA=mysqli_fetch_array(mysqli_query($conn, "SELECT Descripcion FROM bitacora_accion WHERE Id='".$RResBit["Hizo"]."' LIMIT 1"));

    if($RResBit["Datos"]==NULL OR $RResBit["Datos"]=='[]'){$a=0; $json='';}
    else{
        $array=json_decode($RResBit["Datos"], TRUE);

        $a=count($array);

        $json='<div class="dat-bit">';

        for($i=0;$i<$a;$i++) 
        {
            $json.='<strong>'.key($array).'</strong> = "'.current($array).'"<br />';
            next($array);
        }

        $json.='</div>';
    }

    

    $cadena.='  <tr style="background: '.$bgcolor.'" id="row_'.$J.'">
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.fecha_bit(date("Y-m-d H:i:s", substr($epoch, 0, 10))).'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$ResU["Nombre"].'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.utf8_encode($ResA["Descripcion"]).'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$json.'</td>
                </tr>';

    $J++;$json='';
    if($bgcolor=='#FFFFFF'){$bgcolor="#CCCCCC";}
    elseif($bgcolor=='#CCCCCC'){$bgcolor='#FFFFFF';}
}
$cadena.='  </tbody>
            </table>
        </div>';

echo $cadena;

//bitacora
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '125', '".json_encode($_POST)."')");

?>
<script>
$("#fbusbit").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fbusbit"));

	$.ajax({
		url: "bitacora/bitacora.php",
		type: "POST",
		dataType: "HTML",
		data: formData,
		cache: false,
		contentType: false,
		processData: false
	}).done(function(echo){
		$("#contenido").html(echo);
	});
});
</script>