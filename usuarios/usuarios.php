<?php
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

$mensaje='';

//acciones
if(isset($_POST["hacer"]))
{
	//agregar usuario
	if($_POST["hacer"]=='addusuario')
	{
		mysqli_query($conn, "INSERT INTO usuarios (Usuario, Contrasenna, Nombre, Perfil)
											VALUES ('".$_POST["nusuario"]."', '".md5($_POST["contrasena"])."', '".strtoupper($_POST["nombre"])."', '".$_POST["perfil"]."')");

		$mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se agrego el usuario '.$_POST["nusuario"].'</div>';

		//bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '48', '".json_encode($_POST)."')");
	}

	//editar usuario
	if($_POST["hacer"]=='editusuario')
	{
		$sql="UPDATE usuarios SET Usuario='".$_POST["nusuario"]."', ";
		if($_POST["contrasena"]!=''){$sql.="Contrasenna='".md5($_POST["contrasena"])."', ";}
		$sql.="						Nombre='".strtoupper($_POST["nombre"])."', 
									Perfil='".$_POST["perfil"]."'
							WHERE Id='".$_POST["idusuario"]."'";

		mysqli_query($conn, $sql);

		$mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se actualizo el usuario '.$_POST["nusuario"].'</div>';

		//bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '49', '".json_encode($_POST)."')");
	}

	//eliminar usuario
	if($_POST["hacer"]=='borrausuario')
	{
		mysqli_query($conn, "DELETE FROM usuarios WHERE Id='".$_POST["usuario"]."'");

		$mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se elimino el usuario</div>';

		//bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '50', '".json_encode($_POST)."')");
	}
}

$cadena=$mensaje.'<table style="width:80%">
            <thead>
                <tr>
                    <td colspan="5" style="text-align: right">';if($_SESSION["perfil"]==1){$cadena.='| <a href="#" onclick="perfiles()" class="liga">Perfiles</a> | '.permisos(47, '<a href="#" onclick="add_usuario()" class="liga">Nuevo Usuario</a>').' |';}$cadena.='</td>
                </tr>
                <tr>
                    <th colspan="5" align="center" class="textotitable">Usuarios</td>
                </tr>
                <tr>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">Nombre</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                </tr>
            </thead>
            <tbody>';
$bgcolor="#ffffff"; $J=1;
if($_SESSION["perfil"]=='1')
{
	$ResUsuarios=mysqli_query($conn, "SELECT Id, Nombre, Perfil FROM usuarios ORDER BY Nombre ASC");
}
else
{
	$ResUsuarios=mysqli_query($conn, "SELECT Id, Nombre, Perfil FROM usuarios WHERE Id='".$_SESSION["Id"]."' ORDER BY Nombre ASC");
}

while($RResUser=mysqli_fetch_array($ResUsuarios))
{
	$ResP=mysqli_fetch_array(mysqli_query($conn, "SELECT Nombre FROM usuarios_perfiles WHERE Id='".$RResUser["Perfil"]."' LIMIT 1"));

    $cadena.='	<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$J.'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$ResP["Nombre"].'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$RResUser["Nombre"].'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						'.permisos(49, '<a href="#" onclick="edit_usuario(\''.$RResUser["Id"].'\')"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>').' 
					</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						';if($_SESSION["perfil"]==1){$cadena.=permisos(50, '<a href="#" onclick="delete_usuario(\''.$RResUser["Id"].'\')"><i class="fa fa-trash" aria-hidden="true"></i></a>');}
	$cadena.='		</td>
				</tr>';
		$J++;
		if($bgcolor=="#ffffff"){$bgcolor='#cccccc';}
		else if($bgcolor=="#cccccc"){$bgcolor="#ffffff";}
}
$cadena.='  </tbody>
        </table>';

echo $cadena;

//bitacora
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '47', '".json_encode($_POST)."')");

?>
<script>
function add_usuario(){
	$.ajax({
				type: 'POST',
				url : 'usuarios/add_usuario.php'
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}
function edit_usuario(usuario){
	$.ajax({
				type: 'POST',
				url : 'usuarios/edit_usuario.php',
				data: 'usuario=' + usuario
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}
function delete_usuario(usuario){
	$.ajax({
				type: 'POST',
				url : 'usuarios/del_usuario.php',
				data: 'usuario=' + usuario
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}

function perfiles(){
	$.ajax({
				type: 'POST',
				url : 'usuarios/perfiles.php'
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}


//mostrar mensaje despues de los cambios
setTimeout(function() { 
    $('#mesaje').fadeOut('fast'); 
}, 1000)
</script>