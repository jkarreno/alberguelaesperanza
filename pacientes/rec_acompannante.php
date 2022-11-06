<?php
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

$ResAcom=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, Nombre, Apellidos FROM acompannantes WHERE Id='".$_POST["id"]."' LIMIT 1"));

$mensaje='';

//acciones
if(isset($_POST["hacer"]))
{
    if($_POST["hacer"]=='addrecaco')
    {
        mysqli_query($conn, "INSERT INTO reconocimientos (Tipo, IdPA, Reconocimiento, Fecha)
                                                VALUES ('A', '".$_POST["id"]."', '".strtoupper($_POST["reconocimiento"])."', '".date("Y-m-d")."')") or die(mysqli_error($conn));

        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se agrego reconocimiento para el acompannante '.$ResAcom["Nombre"].'</div>';

        //bitacora
        mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '44', '".json_encode($_POST)."')");
    }
}

$cadena=$mensaje.'<table style="width:80%">
            <thead>
                <tr>
                    <td colspan="9" style="text-align: right">| '.permisos(44, '<a href="#" onclick="add_rec_aco(\''.$ResAcom["Id"].'\')" class="liga">Nuevo reconocimiento</a>').' |</td>
                </tr>
                <tr>
                    <th colspan="9" align="center" class="textotitable">Reconocimientos del acompañante '.$ResAcom["Nombre"].' '.$ResAcom["Apellidos"].'</td>
                </tr>
                <tr>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">Fecha</th>
                    <th align="center" class="textotitable">Reconocimiento</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                </tr>
            </thead>
            <tbody>';
$ResRec=mysqli_query($conn, "SELECT * FROM reconocimientos WHERE Tipo='A' AND IdPA='".$ResAcom["Id"]."' ORDER BY Fecha ASC");
$J=1; $bgcolor="#ffffff";
while($RResRec=mysqli_fetch_array($ResRec))
{
    $cadena.='	<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$J.'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.fecha($RResRec["Fecha"]).'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$RResRec["Reconocimiento"].'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        <a href="#" onclick="edit_rec_pac(\''.$RResRec["Id"].'\')"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
                    </td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						<a href="#" onclick="del_rec_pac(\''.$RResRec["Id"].'\')"><i class="fa fa-trash" aria-hidden="true"></i></a>
                    </td>
				</tr>';
		$J++;
		if($bgcolor=="#ffffff"){$bgcolor='#cccccc';}
		else if($bgcolor=="#cccccc"){$bgcolor="#ffffff";}
}

echo $cadena;

//bitacora
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '43', '".json_encode($_POST)."')");
?>
<script>
function add_rec_aco(idacompannante){
	$.ajax({
				type: 'POST',
				url : 'pacientes/add_rec_aco.php',
                data: 'idacompannante=' + idacompannante
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}
</script>