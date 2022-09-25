<?php
//Inicio la sesion 
session_start();

include('../conexion.php');

$ResNP=mysqli_fetch_array(mysqli_query($conn, "SELECT Id FROM pacientes ORDER BY Id DESC LIMIT 1"));

$cadena='<div class="c100 card">
            <h2>Nuevo paciente</h2>
            <form name="fadpaciente" id="fadpaciente">
                <div class="c30">
                    <label class="l_form">Num. Paciente: </label> 
                    <input type="number" name="numpaciente" id="numpaciente" placeholder="'.($ResNP["Id"]+1).'">
                </div>
                <div class="c30">
                    <label class="l_form">Fecha de Registro: </label>
                    <input type="date" name="fregistro" id="fregistro" value="'.date("Y-m-d").'" disabled>
                </div>
                <div class="c30">
                    <label class="l_form">Nivel Socioeconomico: </label> 
                    <select name="n_socio" id="n_socio"> 
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                </div>
                <div class="c30">
                    <label class="l_form">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" required>
                </div>
                <div class="c30">
                    <label class="l_form">Primer Apellido:</label>
                    <input type="text" name="apellidos" id="apellidos" required>
                </div>
                <div class="c30">
                    <label class="l_form">Segundo Apellido:</label>
                    <input type="text" name="apellidos2" id="apellidos2" required>
                </div>
                <div class="c20">
                    <label class="l_form">CURP: </label>
                    <input type="text" name="curp" id="curp" required>
                </div>
                <div class="c10">
                    <label class="l_form">Genero:</label>
                    <select name="sexo" id="sexo" required>
                        <option value="">Selecciona</option>
                        <option value="F">Femenino</option>
                        <option value="M">Masculino</option>
                        <option value="O">Otro</option>
                    </select>
                </div>
                <div class="c10">
                    <label class="l_form">Fecha de nacimiento:</label>
                    <input type="date" name="fnacimiento" id="fnacimiento" onchange="edadpaciente(this.value)" required>
                </div>
                <div class="c10" id="edad_paciente">
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
                    <input type="text" name="domicilio" id="domicilio">
                </div>
                <div class="c20"> 
                    <label class="l_form">C. P.:</label>
                    <input type="text" name="cp" id="cp">
                </div>
                <div class="c45">
                    <label class="l_form">Colonia:</label>
                    <input type="text" name="colonia" id="colonia">
                </div>
                <div class="c45">
                    <label class="l_form">Estado:</label>
                    <select name="estado" id="estado" onchange="mun_paciente(this.value)" required>
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
                    </select>
                </div>
                <div class="c45" id="pob_usuario">
                    <label class="l_form">Municipio:</label>
                    <select name="municipio" id="municipio">
                        <option value="0">Selecciona</option>
                    </select>
                </div>
                <div class="c45">
                    <label class="l_form">Telefono Fijo:</label>
                    <input type="text" name="telefono_fijo" id="telefono_fijo">
                </div>
                <div class="c45">
                    <label class="l_form">Telefono Celular:</label>
                    <input type="text" name="telefono_celular" id="telefono_celular">
                </div>
                <div class="c45">
                    <label class="l_form">Telefono Contacto:</label>
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
                    <select name="edocivil" id="edocivil">
                        <option>Seleccione</option>';
    $ResEdC=mysqli_query($conn, "SELECT * FROM edocivil ORDER BY EdoCivil ASC");
    while($RResEdC=mysqli_fetch_array($ResEdC))
    {
        $cadena.='      <option value="'.$RResEdC["Id"].'">'.$RResEdC["EdoCivil"].'</option>';
    }
    $cadena.='      </select>
                </div>
                <div class="c45">
                    <label class="l_form">Ocupación:</label>
                    <select name="ocupacion" id="ocupacion">
                        <option>Seleccione</option>';
    $ResOcu=mysqli_query($conn, "SELECT * FROM ocupaciones ORDER BY Ocupacion ASC");
    while($RResOcu=mysqli_fetch_array($ResOcu))
    {
        $cadena.='      <option value="'.$RResOcu["Id"].'">'.$RResOcu["Ocupacion"].'</option>';
    }
    $cadena.='      </select>
                </div>
                <div class="c20">
                    <label class="l_form">Escolaridad:</label>
                    <select name="escolaridad" id="escolaridad" required>
                        <option>Seleccione</option>';
    $ResEsc=mysqli_query($conn, "SELECT * FROM escolaridad ORDER BY Id ASC");
    while($RResEsc=mysqli_fetch_array($ResEsc))
    {
        $cadena.='      <option value="'.$RResEsc["Id"].'">'.$RResEsc["Escolaridad"].'</option>';
    }
    $cadena.='      </select>
                </div>
                <div class="c20">
                    <label class="l_form">&nbsp;</label>
                    <select name="nivel_escolaridad" id="nivel_escolaridad">
                        <option value="1">Terminada</option>
                        <option value="0">Trunca</option> 
                    </select>
                </div>
                <div class="c45">
                    <label class="l_form">Documentos Solicitados</label>
                    <label class="l_form">INE o CURP Paciente</label>
                    <ul class="tg-list">
                    <li class="tg-list-item">
                        <input class="tgl tgl-light" id="doc_ine" name="doc_ine" type="checkbox" value="1" />
                        <label class="tgl-btn" for="doc_ine"></label>
                    </li>
                    </ul>
                    <label class="l_form">Carnet hospital</label>
                    <ul class="tg-list">
                    <li class="tg-list-item">
                        <input class="tgl tgl-light" id="doc_carnet" name="doc_carnet" type="checkbox" value="1" />
                        <label class="tgl-btn" for="doc_carnet"></label>
                    </li>
                    </ul>
                    <label class="l_form">Resumen médico</label>
                    <ul class="tg-list">
                    <li class="tg-list-item">
                        <input class="tgl tgl-light" id="doc_resumen" name="doc_resumen" type="checkbox" value="1" />
                        <label class="tgl-btn" for="doc_resumen"></label>
                    </li>
                    </ul>
                    <label class="l_form">INE Acompañante</label>
                    <ul class="tg-list">
                    <li class="tg-list-item">
                        <input class="tgl tgl-light" id="doc_ine_aco" name="doc_ine_aco" type="checkbox" value="1" />
                        <label class="tgl-btn" for="doc_ine_aco"></label>
                    </li>
                    </ul>
                    <label class="l_form">Carta Responsiva</label>
                    <ul class="tg-list">
                    <li class="tg-list-item">
                        <input class="tgl tgl-light" id="doc_carta_resp" name="doc_carta_resp" type="checkbox" value="1" />
                        <label class="tgl-btn" for="doc_carta_resp"></label>
                    </li>
                    </ul>
                    <label class="l_form">Reglamento</label>
                    <ul class="tg-list">
                    <li class="tg-list-item">
                        <input class="tgl tgl-light" id="doc_reglamento" name="doc_reglamento" type="checkbox" value="1" />
                        <label class="tgl-btn" for="doc_reglamento"></label>
                    </li>
                    </ul>
                </div>
                <div class="c20">
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

                <div class="c45">
                    <label class="l_form">Correo Electrónico:</label>
                    <input type="text" name="correoe" id="correoe">
                </div>
                <div class="c45">
                    <label class="l_form">Clave INE:</label>
                    <input type="text" name="claveine" id="claveine">
                </div>

                <div class="c20">
                    <label class="l_form">Indígena:</label>
                    <ul class="tg-list">
                    <li class="tg-list-item">
                        <input class="tgl tgl-light" id="indigena" name="indigena" type="checkbox" value="1"/>
                        <label class="tgl-btn" for="indigena"></label>
                    </li>
                    </ul>
                </div>
                <div class="c20">
                    <label class="l_form">Discapacitado</label>
                    <ul class="tg-list">
                    <li class="tg-list-item">
                        <input class="tgl tgl-light" id="discapacitado" name="discapacitado" type="checkbox" value="1"/>
                        <label class="tgl-btn" for="discapacitado"></label>
                    </li>
                    </ul>
                </div>
                <div class="c45"></div>
                
                <div class="c40">
                <label class="l_form">Instituto:</label>
                    <select name="instituto1" id="instituto1">
                        <option value="0">Seleccione</option>';
    $ResIns=mysqli_query($conn, "SELECT * FROM institutos ORDER BY Instituto ASC");
    while($RResIns=mysqli_fetch_array($ResIns))
    {
        $cadena.='      <option value="'.$RResIns["Id"].'">'.$RResIns["Instituto"].'</option>';
    }
    $cadena.='      </select> 
                </div>
                <div class="c20"> 
                    <label class="l_form">Carnet:</label>
                    <input type="text" name="carnet1" id="carnet1">
                </div>
                <div class="c30">
                    <label class="l_form">Diagnostico:</label>
                    <select name="diagnostico1" id="diagnostico1">
                        <option value="0">Seleccione</option>';
    $ResDia1=mysqli_query($conn, "SELECT * FROM diagnosticos ORDER BY Diagnostico ASC");
    while($RResDia1=mysqli_fetch_array($ResDia1))
    {
        $cadena.='      <option value="'.$RResDia1["Id"].'">'.utf8_encode($RResDia1["Diagnostico"]).'</option>';
    }
    $cadena.='      </select>
                </div>
                <div class="c40">
                <label class="l_form">Instituto:</label>
                    <select name="instituto2" id="instituto2">
                    <option value="0">Seleccione</option>';
    $ResIns2=mysqli_query($conn, "SELECT * FROM institutos ORDER BY Instituto ASC");
    while($RResIns2=mysqli_fetch_array($ResIns2))
    {
        $cadena.='      <option value="'.$RResIns2["Id"].'">'.$RResIns2["Instituto"].'</option>';
    }
    $cadena.='      </select> 
                </div>
                <div class="c20"> 
                    <label class="l_form">Carnet:</label>
                    <input type="text" name="carnet2" id="carnet2">
                </div>
                <div class="c30">
                <label class="l_form">Diagnostico:</label>
                <select name="diagnostico2" id="diagnostico2">
                <option value="0">Seleccione</option>';
$ResDia2=mysqli_query($conn, "SELECT * FROM diagnosticos ORDER BY Diagnostico ASC");
while($RResDia2=mysqli_fetch_array($ResDia2))
{
$cadena.='      <option value="'.$RResDia2["Id"].'">'.utf8_encode($RResDia2["Diagnostico"]).'</option>';
}
$cadena.='      </select>
                </div>
                <div class="c40">
                <label class="l_form">Instituto:</label>
                    <select name="instituto3" id="instituto3">
                    <option value="0">Seleccione</option>';
    $ResIns3=mysqli_query($conn, "SELECT * FROM institutos ORDER BY Instituto ASC");
    while($RResIns3=mysqli_fetch_array($ResIns3))
    {
        $cadena.='      <option value="'.$RResIns3["Id"].'">'.$RResIns3["Instituto"].'</option>';
    }
    $cadena.='      </select> 
                </div>
                <div class="c20"> 
                    <label class="l_form">Carnet:</label>
                    <input type="text" name="carnet3" id="carnet3">
                </div>
                <div class="c30">
                <label class="l_form">Diagnostico:</label>
                <select name="diagnostico3" id="diagnostico3">
                <option value="0">Seleccione</option>';
$ResDia3=mysqli_query($conn, "SELECT * FROM diagnosticos ORDER BY Diagnostico ASC");
while($RResDia3=mysqli_fetch_array($ResDia3))
{
$cadena.='      <option value="'.$RResDia3["Id"].'">'.utf8_encode($RResDia3["Diagnostico"]).'</option>';
}
$cadena.='      </select>
                </div>
                <div class="c45">
                    <label class="l_form">Vive con:</label>
                    <select name="vivecon" id="vivecon">';
$ResViv=mysqli_query($conn, "SELECT * FROM vivecon ORDER BY ViveCon ASC");
while($RResViv=mysqli_fetch_array($ResViv))
{
    $cadena.='          <option value="'.$RResViv["Id"].'">'.$RResViv["ViveCon"].'</option>';
}
$cadena.='        </select>
                </div>
                <div class="c45">
                    <label class="l_form">Tiene apoyo gubernamenal:</label>
                    <select name="recibe_ayuda" id="recibe_ayuda">
                        <option value="0">No</option>
                        <option value="1">Si</option>
                    </select>
                </div>
                <div class="c100">
                    <label class="l_form">Quien recibio:</label>
                    <select name="recibio" id="recibio">';
$ResRec=mysqli_query($conn, "SELECT Id, Nombre FROM usuarios ORDER BY Nombre ASC");
while($RResRec=mysqli_fetch_array($ResRec))
{
    $cadena.='          <option value="'.$RResRec["Id"].'"';if($RResRec["Id"]==$_SESSION["Id"]){$cadena.=' selected';}$cadena.='>'.$RResRec["Nombre"].'</option>';
}
$cadena.='          </select>
                </div>
                <div class="c100">
                    <label class="l_form">Observaciones:</label>
                    <textarea name="observaciones" id="observaciones"></textarea>
                </div>
                
                <input type="hidden" name="hacer" id="hacer" value="addpaciente">
				<input type="submit" name="botaduser" id="botaduser" value="Agregar>>">
			</form>
        </div>';
    
echo $cadena;
?>
<script>
function mun_paciente(estado)
{
    $.ajax({
				type: 'POST',
				url : 'pacientes/mun_pacientes.php',
                data: 'estado=' + estado
	}).done (function ( info ){
		$('#pob_usuario').html(info);
	});
}

function edadpaciente(fecha)
{
    $.ajax({
				type: 'POST',
				url : 'pacientes/edad_paciente.php',
                data: 'fecha=' + fecha
	}).done (function ( info ){
		$('#edad_paciente').html(info);
	});
}

$("#fadpaciente").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fadpaciente"));

	$.ajax({
		url: "pacientes/pacientes.php",
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