<?php
//Inicio la sesion 
session_start();

include('../conexion.php');

$ResPac=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM pacientes WHERE Id='".$_POST["paciente"]."' LIMIT 1"));

$ResNA=mysqli_fetch_array(mysqli_query($conn, "SELECT Id FROM acompannantes ORDER BY Id DESC LIMIT 1"));

$cadena='<div class="c100 card">
            <h2>Nuevo acompañante para '.$ResPac["Nombre"].' '.$ResPac["Apellidos"].'</h2>
            <form name="fadacompannante" id="fadacompannante">
                <div class="c80"></div>
                <div class="c20">
                    <label class="l_form">Num. Acompañante: </label>
                    <input type="text" name="numacompannante" id="numacompannante" placeholder="'.($ResNA["Id"]+1).'">
                </div>
                <div class="c20">
                    <label class="l_form">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" required>
                </div>
                <div class="c20">
                    <label class="l_form">Primer Apellido:</label>
                    <input type="text" name="apellidos" id="apellidos" required>
                </div>
                <div class="c20">
                    <label class="l_form">Segundo Apellido:</label>
                    <input type="text" name="apellidos2" id="apellidos2" required>
                </div>
                <div class="c20">
                    <label class="l_form">Parentesco:</label>
                    <input type="text" name="parentesco" id="parentesco">
                </div>
                <div class="c25">
                    <label class="l_form">CURP:</label>
                    <input type="text" name="curp" id="curp">
                </div>
                <div class="c10">
                    <label class="l_form">Sexo:</label>
                    <select name="sexo" id="sexo" required>
                        <option value="">Selecciona</option>
                        <option value="F">Femenino</option>
                        <option value="M">Masculino</option>
                    </select>
                </div>
                <div class="c10">
                    <label class="l_form">Fecha de nacimiento:</label>
                    <input type="date" name="fnacimiento" id="fnacimiento" onchange="edadacompanante(this.value)" required>
                </div>
                <div class="c10" id="edad_acompante">
                    <label class="l_form">Edad: </label>
                    <input type="text" name="edad" id="edad" disabled>
                </div>
                <div class="c10">
                    <label class="l_form">Talla:</label>
                    <input type="number" name="talla" id="talla" value="0">
                </div>
                <div class="c10">
                    <label class="l_form">Peso:</label>
                    <input type="number" name="peso" id="peso" value="0">
                </div>
                <div class="c20">
                    <label class="l_form">Lugar de Nacimiento:</label>
                    <select name="l_nacimiento" id="l_nacimiento" required>
                        <option value="0">Selecciona</option>
                        <option value="1">Ciudad de México</option>
                        <option value="2">Estado de México</option>
                        <option value="3">Aguascalientes</option>
                        <option value="4">Baja California</option>
                        <option value="5">Baja California Sur</option>
                        <option value="6">Campeche</option>
                        <option value="7">Coahuila de Zaragoza</option>
                        <option value="8">Colima</option>
                        <option value="9">Chiapas</option>
                        <option value="10">Chihuahua</option>
                        <option value="11">Durango</option>
                        <option value="12">Guanajuato</option>
                        <option value="13">Guerrero</option>
                        <option value="14">Hidalgo</option>
                        <option value="15">Jalisco</option>
                        <option value="16">Michoacán de Ocampo</option>
                        <option value="17">Morelos</option>
                        <option value="18">Nayarit</option>
                        <option value="19">Nuevo León</option>
                        <option value="20">Oaxaca</option>
                        <option value="21">Puebla</option>
                        <option value="22">Querétaro</option>
                        <option value="23">Quintana Roo</option>
                        <option value="24">San Luis Potosí</option>
                        <option value="25">Sinaloa</option>
                        <option value="26">Sonora</option>
                        <option value="27">Tabasco</option>
                        <option value="28">Tamaulipas</option>
                        <option value="29">Tlaxcala</option>
                        <option value="30">Veracruz</option>
                        <option value="31">Yucatán</option>
                        <option value="32">Zacatecas</option>
                        <option value="33">Extranjero</option>
                    </select>
                </div>
                <div class="c70"> 
                    <label class="l_form">Domicilio:</label>
                    <input type="text" name="domicilio" id="domicilio" value="'.$ResPac["Domicilio"].'">
                </div>
                <div class="c20"> 
                    <label class="l_form">C.P.:</label>
                    <input type="text" name="cp" id="cp" value="'.$ResPac["CP"].'">
                </div>
                <div class="c45">
                    <label class="l_form">Colonia:</label>
                    <input type="text" name="colonia" id="colonia" value="'.$ResPac["Colonia"].'">
                </div>
                <div class="c45">
                    <label class="l_form">Estado:</label>
                    <select name="estado" id="estado" onchange="pob_paciente(this.value)">
                    <option value="0">Selecciona</option>
                    <option value="1"'; if($ResPac["Estado"]=='1'){$cadena.=' selected';}$cadena.='>Ciudad de México</option>
                    <option value="2"'; if($ResPac["Estado"]=='2'){$cadena.=' selected';}$cadena.='>Estado de México</option>
                    <option value="3"'; if($ResPac["Estado"]=='3'){$cadena.=' selected';}$cadena.='>Aguascalientes</option>
                    <option value="4"'; if($ResPac["Estado"]=='4'){$cadena.=' selected';}$cadena.='>Baja California</option>
                    <option value="5"'; if($ResPac["Estado"]=='5'){$cadena.=' selected';}$cadena.='>Baja California Sur</option>
                    <option value="6"'; if($ResPac["Estado"]=='6'){$cadena.=' selected';}$cadena.='>Campeche</option>
                    <option value="7"'; if($ResPac["Estado"]=='7'){$cadena.=' selected';}$cadena.='>Coahuila de Zaragoza</option>
                    <option value="8"'; if($ResPac["Estado"]=='8'){$cadena.=' selected';}$cadena.='>Colima</option>
                    <option value="9"'; if($ResPac["Estado"]=='9'){$cadena.=' selected';}$cadena.='>Chiapas</option>
                    <option value="10"'; if($ResPac["Estado"]=='10'){$cadena.=' selected';}$cadena.='>Chihuahua</option>
                    <option value="11"'; if($ResPac["Estado"]=='11'){$cadena.=' selected';}$cadena.='>Durango</option>
                    <option value="12"'; if($ResPac["Estado"]=='12'){$cadena.=' selected';}$cadena.='>Guanajuato</option>
                    <option value="13"'; if($ResPac["Estado"]=='13'){$cadena.=' selected';}$cadena.='>Guerrero</option>
                    <option value="14"'; if($ResPac["Estado"]=='14'){$cadena.=' selected';}$cadena.='>Hidalgo</option>
                    <option value="15"'; if($ResPac["Estado"]=='15'){$cadena.=' selected';}$cadena.='>Jalisco</option>
                    <option value="16"'; if($ResPac["Estado"]=='16'){$cadena.=' selected';}$cadena.='>Michoacán de Ocampo</option>
                    <option value="17"'; if($ResPac["Estado"]=='17'){$cadena.=' selected';}$cadena.='>Morelos</option>
                    <option value="18"'; if($ResPac["Estado"]=='18'){$cadena.=' selected';}$cadena.='>Nayarit</option>
                    <option value="19"'; if($ResPac["Estado"]=='19'){$cadena.=' selected';}$cadena.='>Nuevo León</option>
                    <option value="20"'; if($ResPac["Estado"]=='20'){$cadena.=' selected';}$cadena.='>Oaxaca</option>
                    <option value="21"'; if($ResPac["Estado"]=='21'){$cadena.=' selected';}$cadena.='>Puebla</option>
                    <option value="22"'; if($ResPac["Estado"]=='22'){$cadena.=' selected';}$cadena.='>Querétaro</option>
                    <option value="23"'; if($ResPac["Estado"]=='23'){$cadena.=' selected';}$cadena.='>Quintana Roo</option>
                    <option value="24"'; if($ResPac["Estado"]=='24'){$cadena.=' selected';}$cadena.='>San Luis Potosí</option>
                    <option value="25"'; if($ResPac["Estado"]=='25'){$cadena.=' selected';}$cadena.='>Sinaloa</option>
                    <option value="26"'; if($ResPac["Estado"]=='26'){$cadena.=' selected';}$cadena.='>Sonora</option>
                    <option value="27"'; if($ResPac["Estado"]=='27'){$cadena.=' selected';}$cadena.='>Tabasco</option>
                    <option value="28"'; if($ResPac["Estado"]=='28'){$cadena.=' selected';}$cadena.='>Tamaulipas</option>
                    <option value="29"'; if($ResPac["Estado"]=='29'){$cadena.=' selected';}$cadena.='>Tlaxcala</option>
                    <option value="30"'; if($ResPac["Estado"]=='30'){$cadena.=' selected';}$cadena.='>Veracruz</option>
                    <option value="31"'; if($ResPac["Estado"]=='31'){$cadena.=' selected';}$cadena.='>Yucatán</option>
                    <option value="32"'; if($ResPac["Estado"]=='32'){$cadena.=' selected';}$cadena.='>Zacatecas</option>
                    </select>
                </div>
                <div class="c45" id="pob_usuario">
                    <label class="l_form">Municipio:</label>
                    <select name="municipio" id="municipio">
                        <option value="0">Seleccione</option>';
    $ResMun=mysqli_query($conn, "SELECT * FROM municipios WHERE Estado='".$ResPac["Estado"]."' ORDER BY Municipio ASC");
    while($RResMun=mysqli_fetch_array($ResMun))
    {
        $cadena.='     <option value="'.$RResMun["Id"].'"';if($RResMun["Id"]==$ResPac["Municipio"]){$cadena.=' selected';}$cadena.='>'.utf8_encode($RResMun["Municipio"]).'</option>';
    }               
    $cadena.='      </select>
                </div>
                <div class="c45">
                    <label class="l_form">Telefono fijo:</label>
                    <input type="text" name="telefono_fijo" id="telefono_fijo" value="'.$ResPac["TelefonoFijo"].'">
                </div>
                <div class="c45">
                    <label class="l_form">Telefono celular:</label>
                    <input type="text" name="telefono_celular" id="telefono_celular">
                </div>
                <div class="c45">
                    <label class="l_form">Telefono contacto:</label>
                    <input type="text" name="telefono_contacto" id="telefono_contacto">
                </div>
                <div class="c45">
                    <label class="l_form">Religión:</label>
                    <select name="religion" id="religion">';
    $ResRel=mysqli_query($conn, "SELECT * FROM religion ORDER BY Religion ASC");
    while($RResRel=mysqli_fetch_array($ResRel))
    {
        $cadena.='      <option value="'.$RResRel["Id"].'">'.$RResRel["Religion"].'</option>';
    }
    $cadena.='      </select>
                </div>
                <div class="c45">
                    <label class="l_form">Edo. Civil:</label>
                    <select name="edocivil" id="edocivil">';
    $ResEdC=mysqli_query($conn, "SELECT * FROM edocivil ORDER BY EdoCivil ASC");
    while($RResEdC=mysqli_fetch_array($ResEdC))
    {
        $cadena.='      <option value="'.$RResEdC["Id"].'">'.$RResEdC["EdoCivil"].'</option>';
    }
    $cadena.='      </select>
                </div>
                <div class="c45">
                    <label class="l_form">Ocupación:</label>
                    <select name="ocupacion" id="ocupacion">';
    $ResOcu=mysqli_query($conn, "SELECT * FROM ocupaciones ORDER BY Ocupacion ASC");
    while($RResOcu=mysqli_fetch_array($ResOcu))
    {
        $cadena.='      <option value="'.$RResOcu["Id"].'">'.$RResOcu["Ocupacion"].'</option>';
    }
    $cadena.='      </select>
                </div>
                <div class="c20">
                    <label class="l_form">Escolaridad:</label>
                    <select name="escolaridad" id="escolaridad">
                        <option value="0">Selecciona</option>
                        <option value="1">Ninguna</option>
                        <option value="2">Kinder</option> 
                        <option value="3">Primaria</option> 
                        <option value="4">Secundaria</option> 
                        <option value="5">Preparatoria</option> 
                        <option value="6">Licenciatura</option> 
                        <option value="7">Maestria</option>
                        <option value="8">Doctorado</option> 
                        <option value="9">Carrera Técnica</option>
                    </select>
                </div>
                <div class="c20">
                <label class="l_form">&nbsp;</label>
                <select name="nivel_escolaridad" id="nivel_escolaridad">
                    <option value="1">Terminada</option>
                    <option value="0">Trunca</option> 
                </select>
            </div>
                <div class="c80">
                    <label class="l_form">Lengua:</label>
                    <select name="lengua" id="lengua">';
    $ResLen=mysqli_query($conn, "SELECT * FROM lenguas ORDER BY Lengua ASC");
    while($RResLen=mysqli_fetch_array($ResLen))
    {
        $cadena.='      <option value="'.$RResLen["Id"].'">'.$RResLen["Lengua"].'</option>';
    }
    $cadena.='      </select>
                </div>
                <div class="c20">
                    <label class="l_form">Habla Español:</label>
                    <ul class="tg-list">
                    <li class="tg-list-item">
                        <input class="tgl tgl-light" id="habla_espanol" name="habla_espanol" type="checkbox" value="1" checked/>
                        <label class="tgl-btn" for="habla_espanol"></label>
                    </li>
                </ul>
                </div>
                <div class="c100">
                    <label class="l_form">Observaciones:</label>
                    <textarea name="observaciones" id="observaciones"></textarea>
                </div>
                
                <div class="c100">
                    <input type="hidden" name="hacer" id="hacer" value="addacompannante">
                    <input type="hidden" name="paciente" id="paciente" value="'.$ResPac["Id"].'">
				    <input type="submit" name="botaduser" id="botaduser" value="Agregar>>">
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

$("#fadacompannante").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fadacompannante"));

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