<?php
//Inicio la sesion 
session_start();

include('../conexion.php');

$ResPac=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM pacientes WHERE Id='".$_POST["paciente"]."' LIMIT 1"));

$fecha_nac = new DateTime(date('Y/m/d',strtotime($ResPac["FechaNacimiento"]))); // Creo un objeto DateTime de la fecha ingresada
$fecha_hoy = new DateTime(date('Y/m/d',time())); // Creo un objeto DateTime de la fecha de hoy
$cedad = date_diff($fecha_hoy,$fecha_nac);
$edadaco=$cedad->format('%Y').' años '.$cedad->format('%m').' meses';

$cadena='<div class="c100 card">
            <h2>Editar paciente</h2>
            <form name="fedpaciente" id="fedpaciente">
                <div class="c20">
                    <label class="l_form">Num. Paciente: '.$ResPac["Id"].'</label> 
                </div>
                <div class="c60"></div>
                <div class="c20">
                    <label class="l_form">Nivel Socioeconomico: </label> 
                    <select name="n_socio" id="n_socio"> 
                        <option value="0"';if($ResPac["NivelSocioeconomico"]==0){$cadena.=' selected';}$cadena.='>0</option>
                        <option value="1"';if($ResPac["NivelSocioeconomico"]==1){$cadena.=' selected';}$cadena.='>1</option>
                        <option value="2"';if($ResPac["NivelSocioeconomico"]==2){$cadena.=' selected';}$cadena.='>2</option>
                        <option value="3"';if($ResPac["NivelSocioeconomico"]==3){$cadena.=' selected';}$cadena.='>3</option>
                    </select>
                </div>
                <div class="c30">
                    <label class="l_form">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" value="'.$ResPac["Nombre"].'" required>
                </div>
                <div class="c30">
                    <label class="l_form">Primer Apellido:</label>
                    <input type="text" name="apellidos" id="apellidos" value="'.$ResPac["Apellidos"].'" required>
                </div>
                <div class="c30">
                    <label class="l_form">Segundo Apellido:</label>
                    <input type="text" name="apellidos2" id="apellidos2" value="'.$ResPac["Apellidos2"].'" required>
                </div>
                <div class="c20">
                    <label class="l_form">CURP: </label>
                    <input type="text" name="curp" id="curp" value="'.$ResPac["Curp"].'" required>
                </div>
                <div class="c10">
                    <label class="l_form">Genero:</label>
                    <select name="sexo" id="sexo" required>
                        <option value="">Selecciona</option>
                        <option value="F"';if($ResPac["Sexo"]=='F'){$cadena.=' selected';}$cadena.='>Femenino</option>
                        <option value="M"';if($ResPac["Sexo"]=='M'){$cadena.=' selected';}$cadena.='>Masculino</option>
                    </select>
                </div>
                <div class="c10">
                    <label class="l_form">Fecha de nacimiento:</label>
                    <input type="date" name="fnacimiento" id="fnacimiento" value="'.$ResPac["FechaNacimiento"].'" onchange="edadpaciente(this.value)" required>
                </div>
                <div class="c10" id="edad_paciente">
                    <label class="l_form">Edad: </label>
                    <input type="text" name="edad" id="edad" value="'.$edadaco.'" disabled>
                </div>
                <div class="c10">
                    <label class="l_form">Talla:</label>
                    <input type="number" name="talla" id="talla" value="'.$ResPac["Talla"].'">
                </div>
                <div class="c10">
                    <label class="l_form">Peso:</label>
                    <input type="number" name="peso" id="peso" value="';if($ResPac["Peso"]==NULL OR $ResPac["Peso"]==''){$cadena.='0';}else{$cadena.=$ResPac["Peso"];}$cadena.='">
                </div>
                <div class="c20">
                    <label class="l_form">Lugar de Nacimiento:</label>
                    <select name="l_nacimiento" id="l_nacimiento" required>
                        <option value="0"'; if($ResPac["LugarNacimiento"]=='0'){$cadena.=' selected';}$cadena.='>Selecciona</option>
                        <option value="1"'; if($ResPac["LugarNacimiento"]=='1'){$cadena.=' selected';}$cadena.='>Ciudad de México</option>
                        <option value="2"'; if($ResPac["LugarNacimiento"]=='2'){$cadena.=' selected';}$cadena.='>Estado de México</option>
                        <option value="3"'; if($ResPac["LugarNacimiento"]=='3'){$cadena.=' selected';}$cadena.='>Aguascalientes</option>
                        <option value="4"'; if($ResPac["LugarNacimiento"]=='4'){$cadena.=' selected';}$cadena.='>Baja California</option>
                        <option value="5"'; if($ResPac["LugarNacimiento"]=='5'){$cadena.=' selected';}$cadena.='>Baja California Sur</option>
                        <option value="6"'; if($ResPac["LugarNacimiento"]=='6'){$cadena.=' selected';}$cadena.='>Campeche</option>
                        <option value="7"'; if($ResPac["LugarNacimiento"]=='7'){$cadena.=' selected';}$cadena.='>Coahuila de Zaragoza</option>
                        <option value="8"'; if($ResPac["LugarNacimiento"]=='8'){$cadena.=' selected';}$cadena.='>Colima</option>
                        <option value="9"'; if($ResPac["LugarNacimiento"]=='9'){$cadena.=' selected';}$cadena.='>Chiapas</option>
                        <option value="10"'; if($ResPac["LugarNacimiento"]=='10'){$cadena.=' selected';}$cadena.='>Chihuahua</option>
                        <option value="11"'; if($ResPac["LugarNacimiento"]=='11'){$cadena.=' selected';}$cadena.='>Durango</option>
                        <option value="12"'; if($ResPac["LugarNacimiento"]=='12'){$cadena.=' selected';}$cadena.='>Guanajuato</option>
                        <option value="13"'; if($ResPac["LugarNacimiento"]=='13'){$cadena.=' selected';}$cadena.='>Guerrero</option>
                        <option value="14"'; if($ResPac["LugarNacimiento"]=='14'){$cadena.=' selected';}$cadena.='>Hidalgo</option>
                        <option value="15"'; if($ResPac["LugarNacimiento"]=='15'){$cadena.=' selected';}$cadena.='>Jalisco</option>
                        <option value="16"'; if($ResPac["LugarNacimiento"]=='16'){$cadena.=' selected';}$cadena.='>Michoacán de Ocampo</option>
                        <option value="17"'; if($ResPac["LugarNacimiento"]=='17'){$cadena.=' selected';}$cadena.='>Morelos</option>
                        <option value="18"'; if($ResPac["LugarNacimiento"]=='18'){$cadena.=' selected';}$cadena.='>Nayarit</option>
                        <option value="19"'; if($ResPac["LugarNacimiento"]=='19'){$cadena.=' selected';}$cadena.='>Nuevo León</option>
                        <option value="20"'; if($ResPac["LugarNacimiento"]=='20'){$cadena.=' selected';}$cadena.='>Oaxaca</option>
                        <option value="21"'; if($ResPac["LugarNacimiento"]=='21'){$cadena.=' selected';}$cadena.='>Puebla</option>
                        <option value="22"'; if($ResPac["LugarNacimiento"]=='22'){$cadena.=' selected';}$cadena.='>Querétaro</option>
                        <option value="23"'; if($ResPac["LugarNacimiento"]=='23'){$cadena.=' selected';}$cadena.='>Quintana Roo</option>
                        <option value="24"'; if($ResPac["LugarNacimiento"]=='24'){$cadena.=' selected';}$cadena.='>San Luis Potosí</option>
                        <option value="25"'; if($ResPac["LugarNacimiento"]=='25'){$cadena.=' selected';}$cadena.='>Sinaloa</option>
                        <option value="26"'; if($ResPac["LugarNacimiento"]=='26'){$cadena.=' selected';}$cadena.='>Sonora</option>
                        <option value="27"'; if($ResPac["LugarNacimiento"]=='27'){$cadena.=' selected';}$cadena.='>Tabasco</option>
                        <option value="28"'; if($ResPac["LugarNacimiento"]=='28'){$cadena.=' selected';}$cadena.='>Tamaulipas</option>
                        <option value="29"'; if($ResPac["LugarNacimiento"]=='29'){$cadena.=' selected';}$cadena.='>Tlaxcala</option>
                        <option value="30"'; if($ResPac["LugarNacimiento"]=='30'){$cadena.=' selected';}$cadena.='>Veracruz</option>
                        <option value="31"'; if($ResPac["LugarNacimiento"]=='31'){$cadena.=' selected';}$cadena.='>Yucatán</option>
                        <option value="32"'; if($ResPac["LugarNacimiento"]=='32'){$cadena.=' selected';}$cadena.='>Zacatecas</option>
                        <option value="32"'; if($ResPac["LugarNacimiento"]=='33'){$cadena.=' selected';}$cadena.='>Extranjero</option>
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
                    <select name="estado" id="estado" onchange="mun_paciente(this.value)" required>
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
                    <label class="l_form">Telefono Fijo:</label>
                    <input type="text" name="telefono_fijo" id="telefono_fijo" value="'.$ResPac["TelefonoFijo"].'">
                </div>
                <div class="c45">
                    <label class="l_form">Telefono Celular:</label>
                    <input type="text" name="telefono_celular" id="telefono_celular" value="'.$ResPac["TelefonoCelular"].'">
                </div>
                <div class="c45">
                    <label class="l_form">Telefono Contacto:</label>
                    <input type="text" name="telefono_contacto" id="telefono_contacto" value="'.$ResPac["TelefonoContacto"].'">
                </div>
                <div class="c45">
                    <label class="l_form">Religión:</label>
                    <select name="religion" id="religion">';
    $ResRel=mysqli_query($conn, "SELECT * FROM religion ORDER BY Religion ASC");
    while($RResRel=mysqli_fetch_array($ResRel))
    {
        $cadena.='      <option value="'.$RResRel["Id"].'"';if($RResRel["Id"]==$ResPac["Religion"]){$cadena.=' selected';}$cadena.='>'.$RResRel["Religion"].'</option>';
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
        $cadena.='      <option value="'.$RResEdC["Id"].'"';if($RResEdC["Id"]==$ResPac["EdoCivil"]){$cadena.=' selected';}$cadena.='>'.$RResEdC["EdoCivil"].'</option>';
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
        $cadena.='      <option value="'.$RResOcu["Id"].'"';if($RResOcu["Id"]==$ResPac["Ocupacion"]){$cadena.=' selected';}$cadena.='>'.$RResOcu["Ocupacion"].'</option>';
    }
    $cadena.='      </select>
                </div>
                <div class="c20">
                    <label class="l_form">Escolaridad:</label>
                    <select name="escolaridad" id="escolaridad" required>
                        <option value="0">Seleccione</option>';
                    $ResEsc=mysqli_query($conn, "SELECT * FROM escolaridad ORDER BY Id ASC");
                    while($RResEsc=mysqli_fetch_array($ResEsc))
                    {
                        $cadena.='      <option value="'.$RResEsc["Id"].'"';if($ResPac["Escolaridad"]==$RResEsc["Id"]){$cadena.=' selected';}$cadena.='>'.$RResEsc["Escolaridad"].'</option>';
                    }
                    $cadena.='      </select>
                </div>
                <div class="c20">
                    <label class="l_form">&nbsp;</label>
                    <select name="nivel_escolaridad" id="nivel_escolaridad">
                        <option value="1"';if($ResPac["NivelEscolaridad"]=='1'){$cadena.=' selected';}$cadena.='>Terminada</option>
                        <option value="0"';if($ResPac["NivelEscolaridad"]=='0'){$cadena.=' selected';}$cadena.='>Trunca</option> 
                    </select>
                </div>';
    $ResDocs=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM documentos WHERE IdPaciente='".$ResPac["Id"]."' LIMIT 1"));
    $cadena.='  <div class="c45">
                    <label class="l_form">Documentos Solicitados</label>
                    <label class="l_form">INE o CURP Paciente</label>
                    <ul class="tg-list">
                    <li class="tg-list-item">
                        <input class="tgl tgl-light" id="doc_ine" name="doc_ine" type="checkbox" value="1"';if($ResDocs["IneP"]==1){$cadena.=' checked';}$cadena.=' />
                        <label class="tgl-btn" for="doc_ine"></label>
                    </li>
                    </ul>
                    <label class="l_form">Carnet hospital</label>
                    <ul class="tg-list">
                    <li class="tg-list-item">
                        <input class="tgl tgl-light" id="doc_carnet" name="doc_carnet" type="checkbox" value="1"';if($ResDocs["CarnetHospital"]==1){$cadena.=' checked';}$cadena.=' />
                        <label class="tgl-btn" for="doc_carnet"></label>
                    </li>
                    </ul>
                    <label class="l_form">Resumen médico</label>
                    <ul class="tg-list">
                    <li class="tg-list-item">
                        <input class="tgl tgl-light" id="doc_resumen" name="doc_resumen" type="checkbox" value="1"';if($ResDocs["ResumenMedico"]==1){$cadena.=' checked';}$cadena.=' />
                        <label class="tgl-btn" for="doc_resumen"></label>
                    </li>
                    </ul>
                    <label class="l_form">INE Acompañante</label>
                    <ul class="tg-list">
                    <li class="tg-list-item">
                        <input class="tgl tgl-light" id="doc_ine_aco" name="doc_ine_aco" type="checkbox" value="1"';if($ResDocs["IneA"]==1){$cadena.=' checked';}$cadena.=' />
                        <label class="tgl-btn" for="doc_ine_aco"></label>
                    </li>
                    </ul>
                    <label class="l_form">Carta Responsiva</label>
                    <ul class="tg-list">
                    <li class="tg-list-item">
                        <input class="tgl tgl-light" id="doc_carta_resp" name="doc_carta_resp" type="checkbox" value="1"';if($ResDocs["CartaResponsiva"]==1){$cadena.=' checked';}$cadena.=' />
                        <label class="tgl-btn" for="doc_carta_resp"></label>
                    </li>
                    </ul>
                    <label class="l_form">Reglamento</label>
                    <ul class="tg-list">
                    <li class="tg-list-item">
                        <input class="tgl tgl-light" id="doc_reglamento" name="doc_reglamento" type="checkbox" value="1"';if($ResDocs["Reglamento"]==1){$cadena.=' checked';}$cadena.=' />
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
        $cadena.='      <option value="'.$RResLen["Id"].'"';if($RResLen["Id"]==$ResPac["Lengua"]){$cadena.=' selected';}$cadena.='>'.$RResLen["Lengua"].'</option>';
    }
    $cadena.='      </select>
                </div>
                <div class="c10">
                    <label class="l_form">Habla Español:</label>
                    <ul class="tg-list">
                    <li class="tg-list-item">
                        <input class="tgl tgl-light" id="habla_espanol" name="habla_espanol" type="checkbox" value="1"';if($ResPac["HablaEspanol"]==1){$cadena.=' checked';}$cadena.='/>
                        <label class="tgl-btn" for="habla_espanol"></label>
                    </li>
                </ul>
                </div>

                <div class="c45">
                    <label class="l_form">Correo Electrónico:</label>
                    <input type="text" name="correoe" id="correoe" value="'.$ResPac["CorreoE"].'">
                </div>
                <div class="c45">
                    <label class="l_form">Clave INE:</label>
                    <input type="text" name="claveine" id="claveine" value="'.$ResPac["ClaveINE"].'">
                </div>

                <div class="c20">
                    <label class="l_form">Indígena:</label>
                    <ul class="tg-list">
                    <li class="tg-list-item">
                        <input class="tgl tgl-light" id="indigena" name="indigena" type="checkbox" value="1"';if($ResPac["Indigena"]==1){$cadena.=' checked';}$cadena.='/>
                        <label class="tgl-btn" for="indigena"></label>
                    </li>
                    </ul>
                </div>
                <div class="c20">
                    <label class="l_form">Discapacitado</label>
                    <ul class="tg-list">
                    <li class="tg-list-item">
                        <input class="tgl tgl-light" id="discapacitado" name="discapacitado" type="checkbox" value="1"';if($ResPac["Discapacitado"]==1){$cadena.=' checked';}$cadena.='/>
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
        $cadena.='      <option value="'.$RResIns["Id"].'"';if($RResIns["Id"]==$ResPac["Instituto1"]){$cadena.=' selected';}$cadena.='>'.$RResIns["Instituto"].'</option>';
    }
    $cadena.='      </select> 
                </div>
                <div class="c20"> 
                    <label class="l_form">Carnet:</label>
                    <input type="text" name="carnet1" id="carnet1" value="'.$ResPac["Carnet1"].'">
                </div>
                <div class="c30">
                <label class="l_form">Diagnostico:</label>
                <select name="diagnostico1" id="diagnostico1">
                <option value="0">Seleccione</option>';
$ResDia1=mysqli_query($conn, "SELECT * FROM diagnosticos ORDER BY Diagnostico ASC");
while($RResDia1=mysqli_fetch_array($ResDia1))
{
$cadena.='      <option value="'.$RResDia1["Id"].'"';if($ResPac["Diagnostico1"]==$RResDia1["Id"]){$cadena.=' selected';}$cadena.='>'.utf8_encode($RResDia1["Diagnostico"]).'</option>';
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
        $cadena.='      <option value="'.$RResIns2["Id"].'"';if($RResIns2["Id"]==$ResPac["Instituto2"]){$cadena.=' selected';}$cadena.='>'.$RResIns2["Instituto"].'</option>';
    }
    $cadena.='      </select> 
                </div>
                <div class="c20"> 
                    <label class="l_form">Carnet:</label>
                    <input type="text" name="carnet2" id="carnet2" value="'.$ResPac["Carnet2"].'">
                </div>
                <div class="c30">
                <label class="l_form">Diagnostico:</label>
                <select name="diagnostico2" id="diagnostico2">
                <option value="0">Seleccione</option>';
$ResDia2=mysqli_query($conn, "SELECT * FROM diagnosticos ORDER BY Diagnostico ASC");
while($RResDia2=mysqli_fetch_array($ResDia2))
{
$cadena.='      <option value="'.$RResDia2["Id"].'"';if($ResPac["Diagnostico2"]==$RResDia2["Id"]){$cadena.=' selected';}$cadena.='>'.utf8_encode($RResDia2["Diagnostico"]).'</option>';
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
        $cadena.='      <option value="'.$RResIns3["Id"].'"';if($RResIns3["Id"]==$ResPac["Instituto3"]){$cadena.=' selected';}$cadena.='>'.$RResIns3["Instituto"].'</option>';
    }
    $cadena.='      </select> 
                </div>
                <div class="c20"> 
                    <label class="l_form">Carnet:</label>
                    <input type="text" name="carnet3" id="carnet3"  value="'.$ResPac["Carnet3"].'">
                </div>
                <div class="c30">
                <label class="l_form">Diagnostico:</label>
                <select name="diagnostico3" id="diagnostico3">
                <option value="0">Seleccione</option>';
$ResDia3=mysqli_query($conn, "SELECT * FROM diagnosticos ORDER BY Diagnostico ASC");
while($RResDia3=mysqli_fetch_array($ResDia3))
{
$cadena.='      <option value="'.$RResDia3["Id"].'"';if($ResPac["Diagnostico3"]==$RResDia3["Id"]){$cadena.=' selected';}$cadena.='>'.utf8_encode($RResDia3["Diagnostico"]).'</option>';
}
$cadena.='      </select>
                </div>
                <div class="c45">
                    <label class="l_form">Vive con:</label>
                    <select name="vivecon" id="vivecon">';
$ResViv=mysqli_query($conn, "SELECT * FROM vivecon ORDER BY ViveCon ASC");
while($RResViv=mysqli_fetch_array($ResViv))
{
    $cadena.='          <option value="'.$RResViv["Id"].'"';if($RResViv["Id"]==$ResPac["ViveCon"]){$cadena.=' selected';}$cadena.='>'.$RResViv["ViveCon"].'</option>';
}
$cadena.='        </select>
                </div>
                <div class="c45">
                    <label class="l_form">Tiene apoyo gubernamenal:</label>
                    <select name="recibe_ayuda" id="recibe_ayuda">
                        <option value="0"';if($ResPac["RecibeAyuda"]=='0'){$cadena.=' selected';}$cadena.='>No</option>
                        <option value="1"';if($ResPac["RecibeAyuda"]=='1'){$cadena.=' selected';}$cadena.='>Si</option>
                    </select>
                </div>
                <div class="c100">
                    <label class="l_form">Quien recibio:</label>
                    <select name="recibio" id="recibio">';
$ResRec=mysqli_query($conn, "SELECT Id, Nombre FROM usuarios ORDER BY Nombre ASC");
while($RResRec=mysqli_fetch_array($ResRec))
{
    $cadena.='          <option value="'.$RResRec["Id"].'"';if($RResRec["Id"]==$ResPac["Recibio"]){$cadena.=' selected';}$cadena.='>'.$RResRec["Nombre"].'</option>';
}
$cadena.='          </select>
                </div>
                <div class="c100">
                    <label class="l_form">Observaciones:</label>
                    <textarea name="observaciones" id="observaciones">'.$ResPac["Observaciones"].'</textarea>
                </div>
                <div class="c20"> 
                    <label class="l_form">Fallecido:</label>
                    <ul class="tg-list">
                        <li class="tg-list-item">
                            <input class="tgl tgl-light" id="fallecido" name="fallecido" type="checkbox" value="1"';if($ResPac["Fallecido"]==1){$cadena.=' checked';}$cadena.='/>
                            <label class="tgl-btn" for="fallecido"></label>
                        </li>
                    </ul>
                </div>
                <div class="c20"> 
                    <label class="l_form">Fecha Muerte:</label>
                    <input type="date" name="fmuerte" id="fmuerte" value="';if($ResPac["Fallecido"]==1){$cadena.=$ResPac["FechaMuerte"];}$cadena.='">
                </div>
                <div class="c60"></div>
                
                <input type="hidden" name="hacer" id="hacer" value="editpaciente">
                <input type="hidden" name="idpaciente" id="idpaciente" value="'.$ResPac["Id"].'">
                <input type="hidden" name="buscaidpaciente" id="buscaidpaciente" value="'.$ResPac["Id"].'"> 
                <input type="hidden" name="buscar" id="buscar" value="'.$ResPac["Id"].'"> 
				<input type="submit" name="botaduser" id="botaduser" value="Actualizar>>">
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

$("#fedpaciente").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fedpaciente"));

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