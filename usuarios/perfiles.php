<?php
//Inicio la sesion 
session_start();

include('../conexion.php');

$mensaje='';

if(isset($_POST["hacer"]))
{
    //revisa las acciones
    $acciones='||';
    $ResAcciones=mysqli_query($conn, "SELECT * FROM bitacora_accion ORDER BY Id ASC");
    while($RResA=mysqli_fetch_array($ResAcciones))
    {
        if($_POST["acc_".$RResA["Id"]]=='1'){$acciones.=$RResA["Id"].'|';}
    }
    $acciones.='|';

    if($_POST["hacer"]=='addperfil')
    {
        mysqli_query($conn, "INSERT INTO usuarios_perfiles (Nombre, Permisos)
                                                VALUES ('".$_POST["nombre"]."', '".$acciones."')");

        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se agrego el perfil '.$_POST["nombre"].'</div>';
    }
    if($_POST["hacer"]=='editperfil')
    {
        mysqli_query($conn, "UPDATE usuarios_perfiles SET Nombre='".$_POST["nombre"]."',
                                                            Permisos='".$acciones."'
                                                    WHERE Id='".$_POST["idperfil"]."'");

        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se actualizo el perfil '.$_POST["nombre"].'</div>';
    }
}

$cadena=$mensaje.'<table style="width:80%">
            <thead>
                <tr>
                    <td colspan="4" style="text-align: right">';if($_SESSION["perfil"]=='1'){$cadena.='| <a href="#" onclick="add_perfil()" class="liga">Nuevo Perfil</a> |';}$cadena.='</td>
                </tr>
                <tr>
                    <th colspan="4" align="center" class="textotitable">Perfiles</td>
                </tr>
                <tr>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">Nombre</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                </tr>
            </thead>
            <tbody>';

$ResPerfiles=mysqli_query($conn, "SELECT * FROM usuarios_perfiles ORDER BY Nombre ASC");
$bgcolor="#ffffff"; $J=1;
while($RResPer=mysqli_fetch_array($ResPerfiles))
{
    $cadena.='  <tr style="background: '.$bgcolor.'" id="row_'.$J.'">
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$J.'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$RResPer["Nombre"].'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        ';if($RResPer["Id"]!='1'){$cadena.='<a href="#" onclick="edit_perfil(\''.$RResPer["Id"].'\')"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>';}$cadena.='
                    </td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        ';if($RResPer["Id"]!='1'){$cadena.='<a href="#" onclick="delete_perfil(\''.$RResPer["Id"].'\')"><i class="fa fa-trash" aria-hidden="true"></i></a>';}$cadena.='
                    </td>
                </tr>';

    $J++;
    if($bgcolor=="#ffffff"){$bgcolor='#cccccc';}
    else if($bgcolor=="#cccccc"){$bgcolor="#ffffff";}
}

echo $cadena;

?>
<script>
function add_perfil(){
	$.ajax({
				type: 'POST',
				url : 'usuarios/add_perfil.php'
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}

function edit_perfil(idperfil){
	$.ajax({
				type: 'POST',
				url : 'usuarios/edit_perfil.php',
                data: 'idperfil=' + idperfil
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}

//mostrar mensaje despues de los cambios
setTimeout(function() { 
    $('#mesaje').fadeOut('fast'); 
}, 1000)
</script>