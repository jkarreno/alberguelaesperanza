<?php
//Inicio la sesion 
session_start();

include('../conexion.php');

$ResAco=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM acompannantes WHERE Id='".$_POST["acompannante"]."' LIMIT 1"));
$ResPac=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, Nombre, Apellidos FROM pacientes WHERE Id='".$ResAco["IdPaciente"]."' LIMIT 1"));

$fecha_nac = new DateTime(date('Y/m/d',strtotime($ResAco["FechaNacimiento"]))); // Creo un objeto DateTime de la fecha ingresada
$fecha_hoy = new DateTime(date('Y/m/d',time())); // Creo un objeto DateTime de la fecha de hoy
$cedad = date_diff($fecha_hoy,$fecha_nac);
$edadaco=$cedad->format('%Y').' años '.$cedad->format('%m').' meses';

$cadena='<div class="c100 card">
            <h2>Editar acompañante para '.$ResPac["Nombre"].' '.$ResPac["Apellidos"].'</h2>
            <form name="fedacompannante" id="fedacompannante">
                <div class="c80"></div>
                <div class="c20">
                    <label class="l_form">Num. Acompañante: '.$ResAco["Id"].'</label> 
                </div>
                <div class="c20">
                    <label class="l_form">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" value="'.$ResAco["Nombre"].'" required>
                </div>
                <div class="c20">
                    <label class="l_form">Priemer Apellido:</label>
                    <input type="text" name="apellidos" id="apellidos" value="'.$ResAco["Apellidos"].'" required>
                </div>
                <div class="c20">
                    <label class="l_form">Segundo Apellido:</label>
                    <input type="text" name="apellidos2" id="apellidos2" value="'.$ResAco["Apellidos2"].'" required>
                </div>
                <div class="c20">
                    <label class="l_form">Parentesco:</label>
                    <input type="text" name="parentesco" id="parentesco" value="'.$ResAco["Parentesco"].'">
                </div>
                <div class="c25">
                    <label class="l_form">CURP:</label>
                    <input type="text" name="curp" id="curp" value="'.$ResAco["Curp"].'">
                </div>
                <div class="c10">
                    <label class="l_form">Sexo:</label>
                    <select name="sexo" id="sexo" required>
                        <option value="">Selecciona</option>
                        <option value="F"';if($ResAco["Sexo"]=='F'){$cadena.=' selected';}$cadena.='>Femenino</option>
                        <option value="M"';if($ResAco["Sexo"]=='M'){$cadena.=' selected';}$cadena.='>Masculino</option>
                    </select>
                </div>
                <div class="c10">
                    <label class="l_form">Fecha de nacimiento:</label>
                    <input type="date" name="fnacimiento" id="fnacimiento" value="'.$ResAco["FechaNacimiento"].'" onchange="edadacompanante(this.value)" required>
                </div>
                <div class="c10" id="edad_acompante">
                    <label class="l_form">Edad: </label>
                    <input type="text" name="edad" id="edad" value="'.$edadaco.'" disabled>
                </div>
                <div class="c10">
                    <label class="l_form">Talla:</label>
                    <input type="number" name="talla" id="talla" value="'.$ResAco["Talla"].'">
                </div>
                <div class="c10">
                    <label class="l_form">Peso:</label>
                    <input type="number" name="peso" id="peso" value="'.$ResAco["Peso"].'">
                </div>
                <div class="c20">
                    <label class="l_form">Lugar de Nacimiento:</label>
                    <select name="l_nacimiento" id="l_nacimiento" required>
                        <option value="0"'; if($ResAco["LugarNacimiento"]=='0'){$cadena.=' selected';}$cadena.='>Selecciona</option>
                        <option value="1"'; if($ResAco["LugarNacimiento"]=='1'){$cadena.=' selected';}$cadena.='>Ciudad de México</option>
                        <option value="2"'; if($ResAco["LugarNacimiento"]=='2'){$cadena.=' selected';}$cadena.='>Estado de México</option>
                        <option value="3"'; if($ResAco["LugarNacimiento"]=='3'){$cadena.=' selected';}$cadena.='>Aguascalientes</option>
                        <option value="4"'; if($ResAco["LugarNacimiento"]=='4'){$cadena.=' selected';}$cadena.='>Baja California</option>
                        <option value="5"'; if($ResAco["LugarNacimiento"]=='5'){$cadena.=' selected';}$cadena.='>Baja California Sur</option>
                        <option value="6"'; if($ResAco["LugarNacimiento"]=='6'){$cadena.=' selected';}$cadena.='>Campeche</option>
                        <option value="7"'; if($ResAco["LugarNacimiento"]=='7'){$cadena.=' selected';}$cadena.='>Coahuila de Zaragoza</option>
                        <option value="8"'; if($ResAco["LugarNacimiento"]=='8'){$cadena.=' selected';}$cadena.='>Colima</option>
                        <option value="9"'; if($ResAco["LugarNacimiento"]=='9'){$cadena.=' selected';}$cadena.='>Chiapas</option>
                        <option value="10"'; if($ResAco["LugarNacimiento"]=='10'){$cadena.=' selected';}$cadena.='>Chihuahua</option>
                        <option value="11"'; if($ResAco["LugarNacimiento"]=='11'){$cadena.=' selected';}$cadena.='>Durango</option>
                        <option value="12"'; if($ResAco["LugarNacimiento"]=='12'){$cadena.=' selected';}$cadena.='>Guanajuato</option>
                        <option value="13"'; if($ResAco["LugarNacimiento"]=='13'){$cadena.=' selected';}$cadena.='>Guerrero</option>
                        <option value="14"'; if($ResAco["LugarNacimiento"]=='14'){$cadena.=' selected';}$cadena.='>Hidalgo</option>
                        <option value="15"'; if($ResAco["LugarNacimiento"]=='15'){$cadena.=' selected';}$cadena.='>Jalisco</option>
                        <option value="16"'; if($ResAco["LugarNacimiento"]=='16'){$cadena.=' selected';}$cadena.='>Michoacán de Ocampo</option>
                        <option value="17"'; if($ResAco["LugarNacimiento"]=='17'){$cadena.=' selected';}$cadena.='>Morelos</option>
                        <option value="18"'; if($ResAco["LugarNacimiento"]=='18'){$cadena.=' selected';}$cadena.='>Nayarit</option>
                        <option value="19"'; if($ResAco["LugarNacimiento"]=='19'){$cadena.=' selected';}$cadena.='>Nuevo León</option>
                        <option value="20"'; if($ResAco["LugarNacimiento"]=='20'){$cadena.=' selected';}$cadena.='>Oaxaca</option>
                        <option value="21"'; if($ResAco["LugarNacimiento"]=='21'){$cadena.=' selected';}$cadena.='>Puebla</option>
                        <option value="22"'; if($ResAco["LugarNacimiento"]=='22'){$cadena.=' selected';}$cadena.='>Querétaro</option>
                        <option value="23"'; if($ResAco["LugarNacimiento"]=='23'){$cadena.=' selected';}$cadena.='>Quintana Roo</option>
                        <option value="24"'; if($ResAco["LugarNacimiento"]=='24'){$cadena.=' selected';}$cadena.='>San Luis Potosí</option>
                        <option value="25"'; if($ResAco["LugarNacimiento"]=='25'){$cadena.=' selected';}$cadena.='>Sinaloa</option>
                        <option value="26"'; if($ResAco["LugarNacimiento"]=='26'){$cadena.=' selected';}$cadena.='>Sonora</option>
                        <option value="27"'; if($ResAco["LugarNacimiento"]=='27'){$cadena.=' selected';}$cadena.='>Tabasco</option>
                        <option value="28"'; if($ResAco["LugarNacimiento"]=='28'){$cadena.=' selected';}$cadena.='>Tamaulipas</option>
                        <option value="29"'; if($ResAco["LugarNacimiento"]=='29'){$cadena.=' selected';}$cadena.='>Tlaxcala</option>
                        <option value="30"'; if($ResAco["LugarNacimiento"]=='30'){$cadena.=' selected';}$cadena.='>Veracruz</option>
                        <option value="31"'; if($ResAco["LugarNacimiento"]=='31'){$cadena.=' selected';}$cadena.='>Yucatán</option>
                        <option value="32"'; if($ResAco["LugarNacimiento"]=='32'){$cadena.=' selected';}$cadena.='>Zacatecas</option>
                        <option value="33"'; if($ResAco["LugarNacimiento"]=='33'){$cadena.=' selected';}$cadena.='>Extranjero</option>
                    </select>
                </div>
                <div class="c70"> 
                    <label class="l_form">Domicilio:</label>
                    <input type="text" name="domicilio" id="domicilio" value="'.$ResAco["Domicilio"].'">
                </div>
                <div class="c20"> 
                    <label class="l_form">C.P. :</label>
                    <input type="text" name="cp" id="cp" value="'.$ResAco["CP"].'">
                </div>
                <div class="c45">
                    <label class="l_form">Colonia:</label>
                    <input type="text" name="colonia" id="colonia" value="'.$ResAco["Colonia"].'">
                </div>
                <div class="c45">
                    <label class="l_form">Estado:</label>
                    <select name="estado" id="estado" onchange="pob_paciente(this.value)">
                    <option value="">Selecciona</option>
                    <option value="1"'; if($ResAco["Estado"]=='1'){$cadena.=' selected';}$cadena.='>Ciudad de México</option>
                    <option value="2"'; if($ResAco["Estado"]=='2'){$cadena.=' selected';}$cadena.='>Estado de México</option>
                    <option value="3"'; if($ResAco["Estado"]=='3'){$cadena.=' selected';}$cadena.='>Aguascalientes</option>
                    <option value="4"'; if($ResAco["Estado"]=='4'){$cadena.=' selected';}$cadena.='>Baja California</option>
                    <option value="5"'; if($ResAco["Estado"]=='5'){$cadena.=' selected';}$cadena.='>Baja California Sur</option>
                    <option value="6"'; if($ResAco["Estado"]=='6'){$cadena.=' selected';}$cadena.='>Campeche</option>
                    <option value="7"'; if($ResAco["Estado"]=='7'){$cadena.=' selected';}$cadena.='>Coahuila de Zaragoza</option>
                    <option value="8"'; if($ResAco["Estado"]=='8'){$cadena.=' selected';}$cadena.='>Colima</option>
                    <option value="9"'; if($ResAco["Estado"]=='9'){$cadena.=' selected';}$cadena.='>Chiapas</option>
                    <option value="10"'; if($ResAco["Estado"]=='10'){$cadena.=' selected';}$cadena.='>Chihuahua</option>
                    <option value="11"'; if($ResAco["Estado"]=='11'){$cadena.=' selected';}$cadena.='>Durango</option>
                    <option value="12"'; if($ResAco["Estado"]=='12'){$cadena.=' selected';}$cadena.='>Guanajuato</option>
                    <option value="13"'; if($ResAco["Estado"]=='13'){$cadena.=' selected';}$cadena.='>Guerrero</option>
                    <option value="14"'; if($ResAco["Estado"]=='14'){$cadena.=' selected';}$cadena.='>Hidalgo</option>
                    <option value="15"'; if($ResAco["Estado"]=='15'){$cadena.=' selected';}$cadena.='>Jalisco</option>
                    <option value="16"'; if($ResAco["Estado"]=='16'){$cadena.=' selected';}$cadena.='>Michoacán de Ocampo</option>
                    <option value="17"'; if($ResAco["Estado"]=='17'){$cadena.=' selected';}$cadena.='>Morelos</option>
                    <option value="18"'; if($ResAco["Estado"]=='18'){$cadena.=' selected';}$cadena.='>Nayarit</option>
                    <option value="19"'; if($ResAco["Estado"]=='19'){$cadena.=' selected';}$cadena.='>Nuevo León</option>
                    <option value="20"'; if($ResAco["Estado"]=='20'){$cadena.=' selected';}$cadena.='>Oaxaca</option>
                    <option value="21"'; if($ResAco["Estado"]=='21'){$cadena.=' selected';}$cadena.='>Puebla</option>
                    <option value="22"'; if($ResAco["Estado"]=='22'){$cadena.=' selected';}$cadena.='>Querétaro</option>
                    <option value="23"'; if($ResAco["Estado"]=='23'){$cadena.=' selected';}$cadena.='>Quintana Roo</option>
                    <option value="24"'; if($ResAco["Estado"]=='24'){$cadena.=' selected';}$cadena.='>San Luis Potosí</option>
                    <option value="25"'; if($ResAco["Estado"]=='25'){$cadena.=' selected';}$cadena.='>Sinaloa</option>
                    <option value="26"'; if($ResAco["Estado"]=='26'){$cadena.=' selected';}$cadena.='>Sonora</option>
                    <option value="27"'; if($ResAco["Estado"]=='27'){$cadena.=' selected';}$cadena.='>Tabasco</option>
                    <option value="28"'; if($ResAco["Estado"]=='28'){$cadena.=' selected';}$cadena.='>Tamaulipas</option>
                    <option value="29"'; if($ResAco["Estado"]=='29'){$cadena.=' selected';}$cadena.='>Tlaxcala</option>
                    <option value="30"'; if($ResAco["Estado"]=='30'){$cadena.=' selected';}$cadena.='>Veracruz</option>
                    <option value="31"'; if($ResAco["Estado"]=='31'){$cadena.=' selected';}$cadena.='>Yucatán</option>
                    <option value="32"'; if($ResAco["Estado"]=='32'){$cadena.=' selected';}$cadena.='>Zacatecas</option>
                    </select>
                </div>
                <div class="c45" id="pob_usuario">
                    <label class="l_form">Municipio:</label>
                    <select name="municipio" id="municipio">';
    $ResMun=mysqli_query($conn, "SELECT * FROM municipios WHERE Estado='".$ResAco["Estado"]."' ORDER BY Municipio ASC");
    while($RResMun=mysqli_fetch_array($ResMun))
    {
        $cadena.='     <option value="'.$RResMun["Id"].'"';if($RResMun["Id"]==$ResAco["Municipio"]){$cadena.=' selected';}$cadena.='>'.utf8_encode($RResMun["Municipio"]).'</option>';
    }               
    $cadena.='      </select>
                </div>
                <div class="c45">
                    <label class="l_form">Telefono fijo:</label>
                    <input type="text" name="telefono_fijo" id="telefono_fijo" value="'.$ResAco["TelefonoFijo"].'">
                </div>
                <div class="c45">
                    <label class="l_form">Telefono celular:</label>
                    <input type="text" name="telefono_celular" id="telefono_celular" value="'.$ResAco["TelefonoCelular"].'">
                </div>
                <div class="c45">
                    <label class="l_form">Telefono contacto:</label>
                    <input type="text" name="telefono_contacto" id="telefono_contacto" value="'.$ResAco["TelefonoContacto"].'">
                </div>
                <div class="c45">
                    <label class="l_form">Religión:</label>
                    <select name="religion" id="religion">';
    $ResRel=mysqli_query($conn, "SELECT * FROM religion ORDER BY Religion ASC");
    while($RResRel=mysqli_fetch_array($ResRel))
    {
        $cadena.='      <option value="'.$RResRel["Id"].'"';if($RResRel["Id"]==$ResAco["Religion"]){$cadena.=' selected';}$cadena.='>'.$RResRel["Religion"].'</option>';
    }
    $cadena.='      </select>
                </div>
                <div class="c45">
                    <label class="l_form">Edo. Civil:</label>
                    <select name="edocivil" id="edocivil">';
    $ResEdC=mysqli_query($conn, "SELECT * FROM edocivil ORDER BY EdoCivil ASC");
    while($RResEdC=mysqli_fetch_array($ResEdC))
    {
        $cadena.='      <option value="'.$RResEdC["Id"].'"';if($RResEdC["Id"]==$ResAco["EdoCivil"]){$cadena.=' selected';}$cadena.='>'.$RResEdC["EdoCivil"].'</option>';
    }
    $cadena.='      </select>
                </div>
                <div class="c45">
                    <label class="l_form">Ocupación:</label>
                    <select name="ocupacion" id="ocupacion">';
    $ResOcu=mysqli_query($conn, "SELECT * FROM ocupaciones ORDER BY Ocupacion ASC");
    while($RResOcu=mysqli_fetch_array($ResOcu))
    {
        $cadena.='      <option value="'.$RResOcu["Id"].'"';if($RResOcu["Id"]==$ResAco["Ocupacion"]){$cadena.=' selected';}$cadena.='>'.$RResOcu["Ocupacion"].'</option>';
    }
    $cadena.='      </select>
                </div>
                <div class="c20">
                    <label class="l_form">Escolaridad:</label>
                    <select name="escolaridad" id="escolaridad">
                        <option value="0"';if($ResAco["Escolaridad"]=='0'){$cadena.=' selected';}$cadena.='>Selecciona</option>
                        <option value="1"';if($ResAco["Escolaridad"]=='1'){$cadena.=' selected';}$cadena.='>Ninguna</option>
                        <option value="2"';if($ResAco["Escolaridad"]=='2'){$cadena.=' selected';}$cadena.='>Kinder</option> 
                        <option value="3"';if($ResAco["Escolaridad"]=='3'){$cadena.=' selected';}$cadena.='>Primaria</option> 
                        <option value="4"';if($ResAco["Escolaridad"]=='4'){$cadena.=' selected';}$cadena.='>Secundaria</option> 
                        <option value="5"';if($ResAco["Escolaridad"]=='5'){$cadena.=' selected';}$cadena.='>Preparatoria</option> 
                        <option value="6"';if($ResAco["Escolaridad"]=='6'){$cadena.=' selected';}$cadena.='>Licenciatura</option> 
                        <option value="7"';if($ResAco["Escolaridad"]=='7'){$cadena.=' selected';}$cadena.='>Maestria</option>
                        <option value="8"';if($ResAco["Escolaridad"]=='8'){$cadena.=' selected';}$cadena.='>Doctorado</option> 
                        <option value="9"';if($ResAco["Escolaridad"]=='9'){$cadena.=' selected';}$cadena.='>Carrera Técnica</option>
                    </select>
                </div>
                <div class="c20">
                <label class="l_form">&nbsp;</label>
                <select name="nivel_escolaridad" id="nivel_escolaridad">
                    <option value="1"';if($ResAco["NivelEscolaridad"]=='1'){$cadena.=' selected';}$cadena.='>Terminada</option>
                    <option value="0"';if($ResAco["NivelEscolaridad"]=='0'){$cadena.=' selected';}$cadena.='>Trunca</option> 
                </select>
            </div>
                <div class="c80">
                    <label class="l_form">Lengua:</label>
                    <select name="lengua" id="lengua">';
    $ResLen=mysqli_query($conn, "SELECT * FROM lenguas ORDER BY Lengua ASC");
    while($RResLen=mysqli_fetch_array($ResLen))
    {
        $cadena.='      <option value="'.$RResLen["Id"].'"';if($RResLen["Id"]==$ResAco["Lengua"]){$cadena.=' selected';}$cadena.='>'.$RResLen["Lengua"].'</option>';
    }
    $cadena.='      </select>
                </div>
                <div class="c20">
                    <label class="l_form">Habla Español:</label>
                    <ul class="tg-list">
                    <li class="tg-list-item">
                        <input class="tgl tgl-light" id="habla_espanol" name="habla_espanol" type="checkbox" value="1"';if($ResAco["HablaEspanol"]==1){$cadena.=' checked';}$cadena.='/>
                        <label class="tgl-btn" for="habla_espanol"></label>
                    </li>
                </ul>
                </div>
                <div class="c100">
                    <label class="l_form">Observaciones:</label>
                    <textarea name="observaciones" id="observaciones">'.$ResAco["Observaciones"].'</textarea>
                </div>
                
                <div class="c100">
                    <input type="hidden" name="hacer" id="hacer" value="editacompannante">
                    <input type="hidden" name="paciente" id="paciente" value="'.$ResPac["Id"].'">
                    <input type="hidden" name="idacompannante" id="idacompannante" value="'.$ResAco["Id"].'">
				    <input type="submit" name="botaduser" id="botaduser" value="Actualizar>>">
                </div>
			</form>
        </div>';
    
echo $cadena;
?>
<script>
function pob_paciente(estado)
{
    $.ajax({
				type: 'POST',
				url : 'pacientes/mun_pacientes.php',
                data: 'estado=' + estado
	}).done (function ( info ){
		$('#pob_usuario').html(info);
	});
}

function edadacompanante(fecha)
{
    $.ajax({
				type: 'POST',
				url : 'pacientes/edad_paciente.php',
                data: 'fecha=' + fecha
	}).done (function ( info ){
		$('#edad_acompante').html(info);
	});
}

$("#fedacompannante").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fedacompannante"));

	$.ajax({
		url: "pacientes/acompannantes.php",
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