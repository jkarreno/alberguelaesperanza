<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');
include('../../funciones.php');

$mensaje='';

//acciones
if(isset($_POST["hacer"]))
{
    //agregar municipio
	if($_POST["hacer"]=='addmunicipio')
	{
		mysqli_query($conn, "INSERT INTO municipios (Estado, municipio)
									VALUES ('".$_POST["estado"]."', '".$_POST["municipio"]."')") or die(mysqli_error($conn));

		$mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se agrego el municipio '.$_POST["municipio"].'</div>';

		//bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '53', '".json_encode($_POST)."')");
	}
    //editar población
    if($_POST["hacer"]=='editmunicipio')
    {
        mysqli_query($conn, "UPDATE municipios SET Estado='".$_POST["estado"]."',
                                                    municipio='".$_POST["municipio"]."'
                                            WHERE Id='".$_POST["idmunicipio"]."'");

        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se actualizo el municipio '.$_POST["municipio"].'</div>';

		//bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '54', '".json_encode($_POST)."')");
    }
}

$cadena=$mensaje.'<table style="width:80%">
            <thead>
                <tr>
                    <td colspan="3" style="text-align: left">
                        <select name="estado" id="estado" onchange="municipios(this.value, \'0\')">
                            <option value="%">Todos</option>';
$ResEstados=mysqli_query($conn, "SELECT * FROM Estados ORDER BY Id ASC");
while($RResEst=mysqli_fetch_array($ResEstados))
{
    $cadena.='              <option value="'.$RResEst["Id"].'"';if($_POST["estado"]==$RResEst["Id"]){$cadena.=' selected';}$cadena.='>'.utf8_encode($RResEst["Estado"]).'</option>';
}
$cadena.='              </select>
                    </td>
                    <td colspan="2" style="text-align: right">| '.permisos(53, '<a href="#" onclick="add_municipio()" class="liga">Nuevo Municipio</a>').' |</td>
                </tr>
                <tr>
                    <th colspan="5" align="center" class="textotitable">Municipios</td>
                </tr>
                <tr>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">Municipio</th>
                    <th align="center" class="textotitable">Estado</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                </tr>
            </thead>
            <tbody>';
$bgcolor="#ffffff"; $J=1;
$Resmunicipios=mysqli_query($conn, "SELECT p.Id AS Id, p.municipio AS municipio, e.Estado AS Estado FROM municipios AS p 
                                    INNER JOIN Estados AS e ON p.estado=e.Id 
                                    WHERE e.Id LIKE '".$_POST["estado"]."'
                                    ORDER BY p.municipio ASC LIMIT ".$_POST["num"].", 30");
$regs=mysqli_num_rows(mysqli_query($conn, "SELECT p.Id FROM municipios AS p 
                                            INNER JOIN Estados AS e ON p.estado=e.Id 
                                            WHERE e.Id LIKE '".$_POST["estado"]."'"));

while($RResmunicipios=mysqli_fetch_array($Resmunicipios))
{
    $cadena.='<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$J.'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.utf8_encode($RResmunicipios["municipio"]).'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.utf8_encode($RResmunicipios["Estado"]).'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						'.permisos(54, '<a href="#" onclick="edit_municipio(\''.$RResmunicipios["Id"].'\')"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>').' 
					</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						<a href="#" onclick="delete_municipio(\''.$RResmunicipios["Id"].'\')"><i class="fa fa-trash" aria-hidden="true"></i></a>
                    </td>
				</tr>';
    
    $J++;
    if($bgcolor=="#ffffff"){$bgcolor='#cccccc';}
    else if($bgcolor=="#cccccc"){$bgcolor="#ffffff";}
}

$cadena.='	<tr>
					<td colspan="5" bgcolor="'.$bgcolor.'" align="center">| ';
	$J=0;
	for($T=1; $T<=ceil($regs/30); $T++)
	{
		if($buscar==NULL)
		{
			if($J!=$_POST["num"]){$cadena.='<a href="#" onclick="municipios(\''.$_POST["estado"].'\', \''.$J.'\')">'.$T.'</a> |	';}
			else{$cadena.=$T.' | ';}
		}
		else
		{
			if($J!=$_POST["num"]){$cadena.='<a href="#" onclick="municipios(\''.$_POST["estado"].'\', \''.$J.'\')">'.$T.'</a> |	';}
			else{$cadena.=$T.' | ';}
		}
		$J=$J+30;
	}
	$cadena.='		</td>
				</tr>
            </tbody>
        </table>';

echo $cadena;

//bitacora
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '52', '".json_encode($_POST)."')");

?>
<script>
//funciones ajax
function add_municipio(){
	$.ajax({
				type: 'POST',
				url : 'configuracion/municipios/add_municipio.php'
	}).done (function ( info ){
		$('#contenido2').html(info);
	});

}

function edit_municipio(municipio){
    $.ajax({
				type: 'POST',
				url : 'configuracion/municipios/edit_municipio.php',
                data: 'municipio=' + municipio
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

function delete_municipio(municipio){
    $.ajax({
				type: 'POST',
				url : 'configuracion/municipios/del_municipio.php',
                data: 'municipio=' + municipio
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

//mostrar mensaje despues de los cambios
setTimeout(function() { 
    $('#mesaje').fadeOut('fast'); 
}, 1000)
</script>