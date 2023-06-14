<?php
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

$ResPac=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, Nombre, Apellidos, Apellidos2 FROM pacientes WHERE Id='".$_POST["paciente"]."' LIMIT 1"));

$mensaje='';

//acciones
if(isset($_POST["hacer"]))
{
    //agregar paciente
	if($_POST["hacer"]=='addacompannante')
	{
        if($_POST["numacompannante"]=='')
        {
            mysqli_query($conn, "INSERT INTO acompannantes (IdPaciente, Nombre, Apellidos, Apellidos2, Parentesco, Curp, ClaveINE, Sexo, FechaNacimiento, LugarNacimiento, Talla, Peso, Domicilio, CP, Colonia, Estado, Municipio, TelefonoFijo, TelefonoCelular, TelefonoContacto, 
                                                        Religion, EdoCivil, Ocupacion, Escolaridad, NivelEscolaridad, Lengua, HablaEspanol, Observaciones)
							                    VALUES ('".$ResPac["Id"]."', '".strtoupper($_POST["nombre"])."', '".strtoupper($_POST["apellidos"])."', '".strtoupper($_POST["apellidos2"])."', '".strtoupper($_POST["parentesco"])."', '".strtoupper($_POST["curp"])."', '".strtoupper($_POST["claveine"])."', '".$_POST["sexo"]."', '".$_POST["fnacimiento"]."', 
                                                        '".$_POST["l_nacimiento"]."', '".$_POST["talla"]."', '".$_POST["peso"]."', 
                                                        '".strtoupper($_POST["domicilio"])."', '".$_POST["cp"]."', '".strtoupper($_POST["colonia"])."', '".$_POST["estado"]."', '".$_POST["municipio"]."', '".$_POST["telefono_fijo"]."', '".$_POST["telefono_celular"]."', 
                                                        '".$_POST["telefono_contacto"]."', '".$_POST["religion"]."', '".$_POST["edocivil"]."', '".$_POST["ocupacion"]."', '".$_POST["escolaridad"]."', '".$_POST["nivel_escolaridad"]."', 
                                                        '".$_POST["lengua"]."', '".$_POST["habla_espanol"]."', '".strtoupper($_POST["observaciones"])."')") or die(mysqli_error($conn));
        }
        else
        {
            mysqli_query($conn, "INSERT INTO acompannantes (Id, IdPaciente, Nombre, Apellidos, Apellidos2, Parentesco, Curp, CalveINE, Sexo, FechaNacimiento, LugarNacimiento, Talla, Peso, Domicilio, CP, Colonia, Estado, Municipio, TelefonoFijo, TelefonoCelular, TelefonoContacto, 
                                                        Religion, EdoCivil, Ocupacion, Escolaridad, NivelEscolaridad, Lengua, HablaEspanol, Observaciones)
									            VALUES ('".$_POST["numacompannante"]."', '".$ResPac["Id"]."', '".strtoupper($_POST["nombre"])."', '".strtoupper($_POST["apellidos"])."', '".strtoupper($_POST["apellidos2"])."', '".strtoupper($_POST["parentesco"])."', '".strtoupper($_POST["curp"])."', '".strtoupper($_POST["claveine"])."', '".$_POST["sexo"]."', '".$_POST["fnacimiento"]."', 
                                                        '".$_POST["l_nacimiento"]."', '".$_POST["talla"]."', '".$_POST["peso"]."', 
                                                        '".strtoupper($_POST["domicilio"])."', '".$_POST["cp"]."', '".strtoupper($_POST["colonia"])."', '".$_POST["estado"]."', '".$_POST["municipio"]."', '".$_POST["telefono_fijo"]."', '".$_POST["telefono_celular"]."', 
                                                        '".$_POST["telefono_contacto"]."', '".$_POST["religion"]."', '".$_POST["edocivil"]."', '".$_POST["ocupacion"]."', '".$_POST["escolaridad"]."', '".$_POST["nivel_escolaridad"]."', 
                                                        '".$_POST["lengua"]."', '".$_POST["habla_espanol"]."', '".strtoupper($_POST["observaciones"])."')") or die(mysqli_error($conn));
        }
        

		$mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se agrego el acompañante '.$_POST["nombre"].' del paciente '.$ResPac["Nombre"].' '.$ResPac["Apellidos"].' '.$ResPac["Apellidos2"].'</div>';

        //bitacora
        mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '39', '".json_encode($_POST)."')");
	}
    //editar comunidad
    if($_POST["hacer"]=='editacompannante')
    {
       mysqli_query($conn, "UPDATE acompannantes SET Nombre='".strtoupper($_POST["nombre"])."', 
                                                    Apellidos='".strtoupper($_POST["apellidos"])."',
                                                    Apellidos2='".strtoupper($_POST["apellidos2"])."',
                                                    Parentesco='".strtoupper($_POST["parentesco"])."', 
                                                    Curp='".strtoupper($_POST["curp"])."',
                                                    ClaveIne='".strtoupper($_POST["claveine"])."',
                                                    Sexo='".$_POST["sexo"]."', 
                                                    FechaNacimiento='".$_POST["fnacimiento"]."', 
                                                    LugarNacimiento='".$_POST["l_nacimiento"]."', 
                                                    Talla='".$_POST["talla"]."', 
                                                    Peso='".$_POST["peso"]."', 
                                                    Domicilio='".strtoupper($_POST["domicilio"])."', 
                                                    CP='".$_POST["cp"]."', 
                                                    Colonia='".strtoupper($_POST["colonia"])."', 
                                                    Estado='".$_POST["estado"]."', 
                                                    Municipio='".$_POST["municipio"]."', 
                                                    TelefonoFijo='".$_POST["telefono_fijo"]."', 
                                                    TelefonoCelular='".$_POST["telefono_celular"]."', 
                                                    TelefonoContacto='".$_POST["telefono_contacto"]."', 
                                                    Religion='".$_POST["religion"]."', 
                                                    EdoCivil='".$_POST["edocivil"]."', 
                                                    Ocupacion='".$_POST["ocupacion"]."', 
                                                    Escolaridad='".$_POST["escolaridad"]."', 
                                                    NivelEscolaridad='".$_POST["nivel_escolaridad"]."', 
                                                    Lengua='".$_POST["lengua"]."',
                                                    HablaEspanol='".$_POST["habla_espanol"]."', 
                                                    Observaciones='".strtoupper($_POST["observaciones"])."'
                                            WHERE Id='".$_POST["idacompannante"]."'") or die(mysqli_error($conn));


        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se actualizo el acompañante '.$_POST["nombre"].' del paciente '.$ResPac["Nombre"].' '.$ResPac["Apellidos"].'"</div>';

        //bitacora
        mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '40', '".json_encode($_POST)."')");
    }
    //foto_acompannante
    if($_POST["hacer"]=='adfoto')
    {
        //carga el archivo
        if($_FILES['facom']!='')
        {
            $nombre_archivo_r ='A_'.$_POST["idacompannante"].'_'.$_FILES['facom']['name']; 

            if (is_uploaded_file($_FILES['facom']['tmp_name']))
            { 
                if(copy($_FILES['facom']['tmp_name'], './fotos/'.$nombre_archivo_r))
                {
                    $copyfile=1;
                }
                else
                {
                    $copyfile=2;
                }
            }
            else
            {
                $copyfile=3;
            }
        }
        if($copyfile==1)
        {
            //revisa si hay logo anterior, y si existe lo borra
            $ResFileAnt=mysqli_fetch_array(mysqli_query($conn, "SELECT Foto FROM acompannantes WHERE Id='".$_POST["idacompannante"]."' LIMIT 1"));
            if($ResFileAnt["Foto"]!=NULL)
            {
                unlink('../fotos/'.$ResFileAnt["Foto"]);
            }
            //actualiza la base de datos con el nuevo logo
            mysqli_query($conn, "UPDATE acompannantes SET Foto='".$nombre_archivo_r."' WHERE Id='".$_POST["idacompannante"]."'");

            $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se actualizo la foto</div>';

            //bitacora
            mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '41', '".json_encode($_POST)."')");
            
        }
        else{
            $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-exclamation-triangle"></i> Ocurrio un error, no se pudo cargar el archivo, intente nuevamente</div>'; 
        }
    }
}

$cadena=$mensaje.'<table style="width:80%">
            <thead>
                <tr>
                    <td colspan="6" style="text-align: left">Paciente: <a href="#" onclick="paciente(\''.$_POST["paciente"].'\')" class="liga">'.$ResPac["Id"].' - '.$ResPac["Nombre"].' '.$ResPac["Apellidos"].'</a></td>
                    <td colspan="3" style="text-align: right">| '.permisos(39, '<a href="#" onclick="add_acompannante(\''.$_POST["paciente"].'\')" class="liga">Nuevo acompañante</a>').' |</td>
                </tr>
                <tr>
                    <th colspan="9" align="center" class="textotitable">Acompañantes</td>
                </tr>
                <tr>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">Nombre</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                </tr>
            </thead>
            <tbody>';
$J=1; $bgcolor="#ffffff";
$ResAco=mysqli_query($conn, "SELECT Id, Nombre, Apellidos, Apellidos2, Sexo, Foto FROM acompannantes WHERE IdPaciente='".$_POST["paciente"]."' ORDER BY Nombre ASC ");
while($RResAco=mysqli_fetch_array($ResAco))
{
    $ResAmo=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM amonestaciones WHERE Tipo='A' AND IdPA='".$RResAco["Id"]."'"));
    $ResRec=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reconocimientos WHERE Tipo='A' AND IdPA='".$RResAco["Id"]."'"));

    $cadena.='	<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$RResAco["Id"].'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">';
                    if($RResAco["Foto"]==NULL)
                    {
                        if($RResAco["Sexo"]=='F'){$cadena.='<img src="pacientes/fotos/pacientem.jpg" border="0" class="imgpac">';}
                        if($RResAco["Sexo"]=='M' OR $RResAco["Sexo"]==NULL){$cadena.='<img src="pacientes/fotos/pacienteh.jpg" border="0" class="imgpac">';}
                    }
                    else
                    {
                        $cadena.='<img src="pacientes/fotos/'.$RResAco["Foto"].'" border="0" class="imgpac">';
                    }
    $cadena.='      </td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$RResAco["Nombre"].' '.$RResAco["Apellidos"].' '.$RResAco["Apellidos2"].'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        '.permisos(41, '<a href="#" onclick="foto_acompannante(\''.$RResAco["Id"].'\')"><i class="fas fa-portrait"></i></a>').'
                    </td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        '.permisos(42, '<a href="pacientes/cred_acompannante.php?idacompannante='.$RResAco["Id"].'" target="_blank"><i class="fas fa-id-card"></i></a>').'
                    </td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        '.permisos(40, '<a href="#" onclick="edit_acompannante(\''.$RResAco["Id"].'\')"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>').'
                    </td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						<a href="#" '.permisos(43, 'onclick="reconocimiento_acompannante(\''.$RResAco["Id"].'\')"').'><i class="fas fa-crown"';if($ResRec>0){$cadena.=' style="color:#7ac70c;"';}$cadena.='></i></a>
                    </td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						<a href="#" '.permisos(45, 'onclick="amonesta_acompannante(\''.$RResAco["Id"].'\')"').'><i class="fas fa-user-alt-slash"';if($ResAmo>0){$cadena.=' style="color:#c20005;"';}$cadena.='></i></a>
                    </td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        <a href="#" onclick="delete_acompannante(\''.$RResAco["Id"].'\')"><i class="fa fa-trash" aria-hidden="true"></i></a>
                    </td>
                </tr>';
        $J++;
        if($bgcolor=="#ffffff"){$bgcolor='#cccccc';}
        else if($bgcolor=="#cccccc"){$bgcolor="#ffffff";}
}
$cadena.='  </tbody>
        </table>';

echo $cadena;

//bitacora
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '38', '".json_encode($_POST)."')");

?>
<script>
function add_acompannante(paciente){
	$.ajax({
				type: 'POST',
				url : 'pacientes/add_acompannante.php',
                data: 'paciente=' + paciente
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}
function edit_acompannante(acompannante){
    $.ajax({
                type: 'POST',
                url : 'pacientes/edit_acompannante.php',
                data: 'acompannante=' + acompannante
    }).done (function ( info ){
		$('#contenido').html(info);
	});
}

function foto_acompannante(id)
{
    $.ajax({
				type: 'POST',
				url : 'pacientes/fotoac.php',
                data: 'id=' + id 
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}

function amonesta_acompannante(id)
{
    $.ajax({
				type: 'POST',
				url : 'pacientes/amo_acompannante.php',
                data: 'id=' + id 
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}
function reconocimiento_acompannante(id)
{
    $.ajax({
				type: 'POST',
				url : 'pacientes/rec_acompannante.php',
                data: 'id=' + id 
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}

function paciente(paciente)
{
    $.ajax({
				type: 'POST',
				url : 'pacientes/pacientes.php',
                data: 'buscaidpaciente=' + paciente + '&buscar=1'
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}

//mostrar mensaje despues de los cambios
setTimeout(function() { 
    $('#mesaje').fadeOut('fast'); 
}, 1000)
</script>