<?php
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');



$ResPaciente=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM pacientes WHERE Id='".$_POST["paciente"]."' LIMIT 1"));
//salud
$ResSalud=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM es_salud WHERE IdPaciente='".$_POST["paciente"]."' LIMIT 1"));
//situacion economica/ingreso 
$ResSEI=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM es_situacioneconomica_ingreso WHERE IdPaciente='".$_POST["paciente"]."' LIMIT 1"));
//situacion economica/egreso 
$ResSEE=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM es_situacioneconomica_egreso WHERE IdPaciente='".$_POST["paciente"]."' LIMIT 1"));
//descripcion de la vivienda
$ResVi=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM es_vivienda WHERE IdPaciente='".$_POST["paciente"]."' LIMIT 1"));
//diagnostico social
$ResDS=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM es_diagnosticosocial WHERE IdPaciente='".$_POST["paciente"]."' LIMIT 1"));
//acompañantes
$ResAco=mysqli_query($conn, "SELECT Id, Nombre, Apellidos FROM acompannantes WHERE IdPaciente='".$_POST["paciente"]."' ORDER BY Nombre ASC, Apellidos ASC");

//edad
$fecha_nac = new DateTime(date('Y/m/d',strtotime($ResPaciente["FechaNacimiento"]))); // Creo un objeto DateTime de la fecha ingresada
$fecha_hoy = new DateTime(date('Y/m/d',time())); // Creo un objeto DateTime de la fecha de hoy
$cedad = date_diff($fecha_hoy,$fecha_nac);
$edadpac=$cedad->format('%Y').' años '.$cedad->format('%m').' meses';


$cadena.='<div class="c100 card">
            <h2>Estudio Socioeconomico</h2>
            <form name="festsocio" id="festsocio">
                <div class="c80">
                    <label class="l_form"><strong>1. Datos Generales del Albergado</strong></label>
                </div>
                <div class="c20 c_der">
                    <a href="trabajo_social/estudio.php?paciente='.$ResPaciente["Id"].'" target="_blank"><i class="fas fa-print"></i></a>
                </div>
                <div class="c20">
                    <label class="l_form">Num. Paciente: </label> 
                    <input type="number" name="paciente" id="paciente" value="'.$ResPaciente["Id"].'" readonly>
                </div>
                <div class="c20">
                    <label class="l_form">Fecha de Ingreso: </label> 
                    <input type="text" name="f_ingreso" id="f_ingreso" value="'.fecha($ResPaciente["FechaRegistro"]).'" readonly>
                </div>
                <div class="c20">
                    <label class="l_form">Nivel Socioeconomico: </label> 
                    <select name="n_socio" id="n_socio"> 
                        <option value="0"';if($ResPaciente["NivelSolioeconomico"]==0){$cadena.=' selected';}$cadena.='>0</option>
                        <option value="1"';if($ResPaciente["NivelSolioeconomico"]==1){$cadena.=' selected';}$cadena.='>1</option>
                        <option value="2"';if($ResPaciente["NivelSolioeconomico"]==2){$cadena.=' selected';}$cadena.='>2</option>
                        <option value="3"';if($ResPaciente["NivelSolioeconomico"]==3){$cadena.=' selected';}$cadena.='>3</option>
                    </select>
                </div>
                <div class="c20">
                    <label class="l_form">Carnet: </label> 
                    <input type="text" name="carnet1" id="carnet1" value="'.$ResPaciente["Carnet1"].'">
                </div>
                <div class="c45">
                    <label class="l_form">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" value="'.$ResPaciente["Nombre"].'">
                </div>
                <div class="c45">
                    <label class="l_form">Apellidos:</label>
                    <input type="text" name="apellidos" id="apellidos" value="'.$ResPaciente["Apellidos"].'">
                </div>
                <div class="c20" id="edad_paciente">
                    <label class="l_form">Edad: </label>
                    <input type="text" name="edad" id="edad" value="'.$edadpac.'" disabled>
                </div>
                <div class="c20">
                    <label class="l_form">Fecha de nacimiento:</label>
                    <input type="date" name="fnacimiento" id="fnacimiento" value="'.$ResPaciente["FechaNacimiento"].'" onchange="edadpaciente(this.value)">
                </div>
                <div class="c20">
                    <label class="l_form">Edo. Civil:</label>
                    <select name="edocivil" id="edocivil">';
    $ResEdC=mysqli_query($conn, "SELECT * FROM edocivil ORDER BY EdoCivil ASC");
    while($RResEdC=mysqli_fetch_array($ResEdC))
    {
        $cadena.='      <option value="'.$RResEdC["Id"].'"';if($ResPaciente["EdoCivil"]==$RResEdC["Id"]){$cadena.=' selected';}$cadena.='>'.$RResEdC["EdoCivil"].'</option>';
    }
    $cadena.='      </select>
                </div>
                <div class="c10">
                    <label class="l_form">Escolaridad:</label>
                    <select name="escolaridad" id="escolaridad">';
    $ResEsc=mysqli_query($conn, "SELECT * FROM escolaridad ORDER BY Escolaridad ASC");
    while($RResEsc=mysqli_fetch_array($ResEsc))
    {
        $cadena.='      <option value="'.$RResEsc["Id"].'"';if($RResEsc["Id"]==$ResPaciente["Escolaridad"]){$cadena.=' selected';}$cadena.='>'.$RResEsc["Escolaridad"].'</option>';
    }
    $cadena.='      </select>
                </div>
                <div class="c10">
                    <label class="l_form">&nbsp;</label>
                    <select name="nivel_escolaridad" id="nivel_escolaridad">
                        <option value="1"';if($ResPaciente["NivelEscolaridad"]==1){$cadena.=' selected';}$cadena.='>Terminada</option>
                        <option value="0"';if($ResPaciente["NivelEscolaridad"]==0){$cadena.=' selected';}$cadena.='>Trunca</option> 
                    </select>
                </div>
                <div class="c70"> 
                    <label class="l_form">Domicilio:</label>
                    <input type="text" name="domicilio" id="domicilio" value="'.$ResPaciente["Domicilio"].'">
                </div>
                <div class="c20"> 
                    <label class="l_form">C.P.:</label>
                    <input type="text" name="cp" id="cp" value="'.$ResPaciente["CP"].'">
                </div>
                <div class="c45">
                    <label class="l_form">Colonia:</label>
                    <input type="text" name="colonia" id="colonia" value="'.$ResPaciente["Colonia"].'">
                </div>
                <div class="c45">
                    <label class="l_form">Estado:</label>
                    <select name="estado" id="estado" onchange="mun_paciente(this.value)">
                        <option value="0">Selecciona</option>
                        <option value="1"'; if($ResPaciente["Estado"]=='1'){$cadena.=' selected';}$cadena.='>Ciudad de México</option>
                        <option value="2"'; if($ResPaciente["Estado"]=='2'){$cadena.=' selected';}$cadena.='>Estado de México</option>
                        <option value="3"'; if($ResPaciente["Estado"]=='3'){$cadena.=' selected';}$cadena.='>Aguascalientes</option>
                        <option value="4"'; if($ResPaciente["Estado"]=='4'){$cadena.=' selected';}$cadena.='>Baja California</option>
                        <option value="5"'; if($ResPaciente["Estado"]=='5'){$cadena.=' selected';}$cadena.='>Baja California Sur</option>
                        <option value="6"'; if($ResPaciente["Estado"]=='6'){$cadena.=' selected';}$cadena.='>Campeche</option>
                        <option value="7"'; if($ResPaciente["Estado"]=='7'){$cadena.=' selected';}$cadena.='>Coahuila de Zaragoza</option>
                        <option value="8"'; if($ResPaciente["Estado"]=='8'){$cadena.=' selected';}$cadena.='>Colima</option>
                        <option value="9"'; if($ResPaciente["Estado"]=='9'){$cadena.=' selected';}$cadena.='>Chiapas</option>
                        <option value="10"'; if($ResPaciente["Estado"]=='10'){$cadena.=' selected';}$cadena.='>Chihuahua</option>
                        <option value="11"'; if($ResPaciente["Estado"]=='11'){$cadena.=' selected';}$cadena.='>Durango</option>
                        <option value="12"'; if($ResPaciente["Estado"]=='12'){$cadena.=' selected';}$cadena.='>Guanajuato</option>
                        <option value="13"'; if($ResPaciente["Estado"]=='13'){$cadena.=' selected';}$cadena.='>Guerrero</option>
                        <option value="14"'; if($ResPaciente["Estado"]=='14'){$cadena.=' selected';}$cadena.='>Hidalgo</option>
                        <option value="15"'; if($ResPaciente["Estado"]=='15'){$cadena.=' selected';}$cadena.='>Jalisco</option>
                        <option value="16"'; if($ResPaciente["Estado"]=='16'){$cadena.=' selected';}$cadena.='>Michoacán de Ocampo</option>
                        <option value="17"'; if($ResPaciente["Estado"]=='17'){$cadena.=' selected';}$cadena.='>Morelos</option>
                        <option value="18"'; if($ResPaciente["Estado"]=='18'){$cadena.=' selected';}$cadena.='>Nayarit</option>
                        <option value="19"'; if($ResPaciente["Estado"]=='19'){$cadena.=' selected';}$cadena.='>Nuevo León</option>
                        <option value="20"'; if($ResPaciente["Estado"]=='20'){$cadena.=' selected';}$cadena.='>Oaxaca</option>
                        <option value="21"'; if($ResPaciente["Estado"]=='21'){$cadena.=' selected';}$cadena.='>Puebla</option>
                        <option value="22"'; if($ResPaciente["Estado"]=='22'){$cadena.=' selected';}$cadena.='>Querétaro</option>
                        <option value="23"'; if($ResPaciente["Estado"]=='23'){$cadena.=' selected';}$cadena.='>Quintana Roo</option>
                        <option value="24"'; if($ResPaciente["Estado"]=='24'){$cadena.=' selected';}$cadena.='>San Luis Potosí</option>
                        <option value="25"'; if($ResPaciente["Estado"]=='25'){$cadena.=' selected';}$cadena.='>Sinaloa</option>
                        <option value="26"'; if($ResPaciente["Estado"]=='26'){$cadena.=' selected';}$cadena.='>Sonora</option>
                        <option value="27"'; if($ResPaciente["Estado"]=='27'){$cadena.=' selected';}$cadena.='>Tabasco</option>
                        <option value="28"'; if($ResPaciente["Estado"]=='28'){$cadena.=' selected';}$cadena.='>Tamaulipas</option>
                        <option value="29"'; if($ResPaciente["Estado"]=='29'){$cadena.=' selected';}$cadena.='>Tlaxcala</option>
                        <option value="30"'; if($ResPaciente["Estado"]=='30'){$cadena.=' selected';}$cadena.='>Veracruz</option>
                        <option value="31"'; if($ResPaciente["Estado"]=='31'){$cadena.=' selected';}$cadena.='>Yucatán</option>
                        <option value="32"'; if($ResPaciente["Estado"]=='32'){$cadena.=' selected';}$cadena.='>Zacatecas</option>
                    </select>
                </div>
                <div class="c45" id="pob_usuario">
                    <label class="l_form">Municipio:</label>
                    <select name="municipio" id="municipio">
                        <option value="0">Seleccione</option>';
    $ResMun=mysqli_query($conn, "SELECT * FROM municipios WHERE Estado='".$ResPaciente["Estado"]."' ORDER BY Municipio ASC");
     while($RResMun=mysqli_fetch_array($ResMun))
     {
         $cadena.='     <option value="'.$RResMun["Id"].'"';if($RResMun["Id"]==$ResPaciente["Municipio"]){$cadena.=' selected';}$cadena.='>'.utf8_encode($RResMun["Municipio"]).'</option>';
     }               
    $cadena.='      </select>
                </div>
                <div class="c45">
                    <label class="l_form">Telefono Fijo:</label>
                    <input type="text" name="telefono_fijo" id="telefono_fijo" value="'.$ResPaciente["TelefonoFijo"].'">
                </div>
                <div class="c45">
                    <label class="l_form">Telefono Celular:</label>
                    <input type="text" name="telefono_celular" id="telefono_celular" value="'.$ResPaciente["TelefonoCelular"].'">
                </div>
                <div class="c45">
                    <label class="l_form">Telefono Contacto:</label>
                    <input type="text" name="telefono_contacto" id="telefono_contacto" value="'.$ResPaciente["TelefonoContacto"].'">
                </div>

                <div class="c100">
                    <label class="l_form"><strong>2. Salud</strong></label>
                </div>
                <div class="c100">
                    <label class="l_form">Instituto:</label>
                    <select name="instituto1" id="instituto1">
                        <option value="0">Seleccione</option>';
    $ResIns=mysqli_query($conn, "SELECT * FROM institutos ORDER BY Instituto ASC");
    while($RResIns=mysqli_fetch_array($ResIns))
    {
        $cadena.='      <option value="'.$RResIns["Id"].'"';if($RResIns["Id"]==$ResPaciente["Instituto1"]){$cadena.=' selected';}$cadena.='>'.$RResIns["Instituto"].'</option>';
    }
    $cadena.='      </select> 
                </div>
                <div class="c100">
                <label class="l_form">Diagnostico:</label>
                    <input type="text" name="diagnostico1" id="diagnostico1" value="'.$ResPaciente["Diagnostico1"].'">
                </div>
                <div class="c100">
                    <label class="l_form">Antecedentes de la enfermedad:</label>
                    <textarea name="antecedentes_enfermedad" id="antecedentes_enfermrdad">'.$ResSalud["AntecedentesEnfermedad"].'</textarea>
                </div>
                <div class="c100">
                    <label class="l_form">Tratamiento:</label>
                    <textarea name="tratamiento_enfermedad" id="tratamiento_enfermedad">'.$ResSalud["Tratamiento"].'</textarea>
                </div>
                <div class="c20">
                    <label class="l_form">Frecuencia con la que acude al hospital</label>
                    <input type="text" name="frecuencia_hospital" id="frecuencia_hospital" value="'.$ResSalud["FrecuenciaHospital"].'">
                </div>
                <div class="c20">
                    <label class="l_form">Servicio médico con el que cuenta</label>
                    <select name="sm_cuenta" id="sm_cuenta">
                        <option value="0">Seleccione</option>
                        <option value="1"';if($ResSalud["ServicioMedico"]==1){$cadena.=' selected';}$cadena.='>IMSS</option>
                        <option value="2"';if($ResSalud["ServicioMedico"]==2){$cadena.=' selected';}$cadena.='>ISSSTE</option>
                        <option value="3"';if($ResSalud["ServicioMedico"]==3){$cadena.=' selected';}$cadena.='>Centro de Salud</option>
                        <option value="4"';if($ResSalud["ServicioMedico"]==4){$cadena.=' selected';}$cadena.='>Seguro Popular</option>
                        <option value="5"';if($ResSalud["ServicioMedico"]==4){$cadena.=' selected';}$cadena.='>Ninguno</option>
                    </select>
                </div>
                <!--<div class="c50">
                    <label class="l_form">Documentos Solicitados</label>
                    <input type="checkbox" name="doc_ine" id="doc_ine" value="1"';if($ResSalud["IneP"]==1){$cadena.=' checked';}$cadena.='> INE Paciente <input type="checkbox" name="doc_carnet" id="doc_carnet" value="1"';if($ResSalud["CarnetHospital"]==1){$cadena.=' checked';}$cadena.='> Carnet hospital <input type="checkbox" name="doc_resumen" id="doc_resumen" value="1"';if($ResSalud["ResumenMedico"]==1){$cadena.=' checked';}$cadena.='> Resumen médico <input type="checkbox" name="doc_ine_aco" id="doc_ine_aco" value="1"';if($ResSalud["IneA"]==1){$cadena.=' checked';}$cadena.='> INE Acompañante
                </div>-->

                <div class="c100">
                    <label class="l_form"><strong>3. Estructura y Dinámica familiar</strong></label>
                </div>
                <div class="c20">
                    <label class="l_form">Nombre: </label>
                    <input type="text" name="nombre_fam" id="nombre_fam">
                </div>
                <div class="c20">
                    <label class="l_form">Parentesco: </label>
                    <input type="text" name="parentesco_fam" id="parentesco_fam">
                </div>
                <div class="c20">
                    <label class="l_form">Edad: </label>
                    <input type="text" name="edad_fam" id="edad_fam">
                </div>
                <div class="c20">
                    <label class="l_form">Ocupación: </label>
                    <input type="text" name="ocupacion_fam" id="ocupacion_fam">
                </div>
                <div class="c10">
                    <input type="button" name="botadfam" id="botadfam" value="+" onclick="adestfam()">
                </div>
                <div class="c100" id="estructura_familiar" style="display: flex; flex-wrap: wrap;">';
                $ResES=mysqli_query($conn, "SELECT * FROM es_estructurafamiliar WHERE IdPaciente='".$_POST["paciente"]."' ORDER BY Id ASC");

                $cadena.='<table>
                            <tbody>';
                
                $J=1; $bgcolor="#ffffff";
                while($RResEs=mysqli_fetch_array($ResES))
                {
                    $cadena.='  <tr style="background: '.$bgcolor.'" id="row_'.$J.'">
                                    <td width="20%" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$RResEs["Nombre"].'</td>
                                    <td width="20%" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$RResEs["Parentesco"].'</td>
                                    <td width="20%" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$RResEs["Edad"].'</td>
                                    <td width="20%" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$RResEs["Ocupacion"].'</td>
                                    <td width="20%" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                                        <a href="javascript:void(0)" onclick="del_familiar(\''.$RResEs["Id"].'\')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>';
                
                    $J++;
                    if($bgcolor=="#ffffff"){$bgcolor='#cccccc';}
                    else if($bgcolor=="#cccccc"){$bgcolor="#ffffff";}
                }
                                
                $cadena.='  </tbody>
                        </table>';
    $cadena.='  </div>

                <div class="c100">
                    <label class="l_form"><strong>4. Situación económica</strong></label>
                    <label class="l_form">Ingreso familiar mensual (¿Quien aporta al gasto familiar en casa?)</label>
                </div>
                <div class="c20">
                    <label class="l_form">Esposo</label>
                    $ <input type="number" name="ingreso_esposo" id="ingreso_esposo" style="width: calc(100% - 15px); margin-bottom: 20px;" value="';if($ResSEI["Esposo"]>0){$cadena.=$ResSEI["Esposo"];}else{$cadena.='0';}$cadena.='" onKeyUp="calculoi();">
                    <input type="text" name="ocupacion_esposo" id="ocupacion_esposo" placeholder="Ocupación" value="'.$ResSEI["OcupacionE"].'">
                </div>
                <div class="c20">
                    <label class="l_form">Padre/Madre</label>
                    $ <input type="number" name="ingreso_padremadre" id="ingreso_padremadre" style="width: calc(100% - 15px); margin-bottom: 20px;" value="';if($ResSEI["PadreMadre"]>0){$cadena.=$ResSEI["PadreMadre"];}else{$cadena.='0';}$cadena.='" onKeyUp="calculoi();">
                    <input type="text" name="ocupacion_padremadre" id="ocupacion_padremadre" placeholder="Ocupación" value="'.$ResSEI["OcupacionPM"].'">
                </div>
                <div class="c20">
                    <label class="l_form">Hijo(s)</label>
                    $ <input type="number" name="ingreso_hijos" id="ingreso_hijos" style="width: calc(100% - 15px); margin-bottom: 20px;" value="';if($ResSEI["Hijos"]>0){$cadena.=$ResSEI["Hijos"];}else{$cadena.='0';}$cadena.='" onKeyUp="calculoi();">
                    <input type="text" name="ocupacion_hijos" id="ocupacion_hijos" placeholder="Ocupación" value="'.$ResSEI["OcupacionH"].'">
                </div>
                <div class="c20" style="color: #c20005">
                    <label class="l_form" style="color:#c20005">Otros</label>
                    $ <input type="number" name="ingreso_otros" id="ingreso_otros" style="width: calc(100% - 15px); margin-bottom: 20px; color: #c20005;" value="';if($ResSEI["Otros"]>0){$cadena.=$ResSEI["Otros"];}else{$cadena.='0';}$cadena.='" onKeyUp="calculoi();">
                    <input type="text" name="ocupacion_otros" id="ocupacion_otros" style="color: #c20005; "placeholder="Ocupación" value="'.$ResSEI["OcupacionO"].'">
                </div>
                <div class="c100">
                    <label class="l_form">Egreso familiar mensual (¿Como es distribuido el ingreso familiar?)</label>
                </div>
                <div class="c20">
                    <label class="l_form">Alimentación</label>
                    $ <input type="number" name="egreso_alimentacion" id="egreso_alimentacion" style="width: calc(100% - 15px); margin-bottom: 20px;" value="';if($ResSEE["Alimentacion"]>0){$cadena.=$ResSEE["Alimentacion"];}else{$cadena.='0';}$cadena.='" onkeyup="calculoe()">
                </div>
                <div class="c20">
                    <label class="l_form">Transporte</label>
                    $ <input type="number" name="egreso_transporte" id="egreso_transporte" style="width: calc(100% - 15px); margin-bottom: 20px;" value="';if($ResSEE["Transporte"]>0){$cadena.=$ResSEE["Transporte"];}else{$cadena.='0';}$cadena.='" onkeyup="calculoe()">
                </div>
                <div class="c20">
                    <label class="l_form">Renta o predial</label>
                    $ <input type="number" name="egreso_renta" id="egreso_renta" style="width: calc(100% - 15px); margin-bottom: 20px;" value="';if($ResSEE["Renta"]>0){$cadena.=$ResSEE["Renta"];}else{$cadena.='0';}$cadena.='" onkeyup="calculoe()">
                </div>
                <div class="c20">
                    <label class="l_form">Gas</label>
                    $ <input type="number" name="egreso_gas" id="egreso_gas" style="width: calc(100% - 15px); margin-bottom: 20px;" value="';if($ResSEE["Gas"]>0){$cadena.=$ResSEE["Gas"];}else{$cadena.='0';}$cadena.='" onkeyup="calculoe()">
                </div>
                <div class="c20">
                    <label class="l_form">Teléfono</label>
                    $ <input type="number" name="egreso_telefono" id="egreso_telefono" style="width: calc(100% - 15px); margin-bottom: 20px;" value="';if($ResSEE["Telefono"]>0){$cadena.=$ResSEE["Telefono"];}else{$cadena.='0';}$cadena.='" onkeyup="calculoe()">
                </div>
                <div class="c20">
                    <label class="l_form">Agua</label>
                    $ <input type="number" name="egreso_agua" id="egreso_agua" style="width: calc(100% - 15px); margin-bottom: 20px;" value="';if($ResSEE["Agua"]>0){$cadena.=$ResSEE["Agua"];}else{$cadena.='0';}$cadena.='" onkeyup="calculoe()">
                </div>
                <div class="c20">
                    <label class="l_form">Servicio médico</label>
                    $ <input type="number" name="egreso_medico" id="egreso_medico" style="width: calc(100% - 15px); margin-bottom: 20px;" value="';if($ResSEE["ServicioMedico"]>0){$cadena.=$ResSEE["ServicioMedico"];}else{$cadena.='0';}$cadena.='" onkeyup="calculoe()">
                </div>
                <div class="c20">
                    <label class="l_form">Luz</label>
                    $ <input type="number" name="egreso_luz" id="egreso_luz" style="width: calc(100% - 15px); margin-bottom: 20px;" value="';if($ResSEE["Luz"]>0){$cadena.=$ResSEE["Luz"];}else{$cadena.='0';}$cadena.='" onkeyup="calculoe()">
                </div>
                <div class="c20">
                    <label class="l_form">Medicamentos</label>
                    $ <input type="number" name="egreso_medicamentos" id="egreso_medicamentos" style="width: calc(100% - 15px); margin-bottom: 20px;" value="';if($ResSEE["Medicamentos"]>0){$cadena.=$ResSEE["Medicamentos"];}else{$cadena.='0';}$cadena.='" onkeyup="calculoe()">
                </div>
                <div class="c20">
                    <label class="l_form">Otros</label>
                    $ <input type="number" name="egreso_otros" id="egreso_otros" style="width: calc(100% - 15px); margin-bottom: 20px;" value="';if($ResSEE["Otros"]>0){$cadena.=$ResSEE["Otros"];}else{$cadena.='0';}$cadena.='" onkeyup="calculoe()">
                </div>
                <div class="c20">
                    <label class="l_form">Total Ingresos</label>
                    $ <input type="number" name="total_ingresos" id="total_ingresos" style="width: calc(100% - 15px); margin-bottom: 20px;" value="'.($ResSEI["Esposo"]+$ResSEI["PadreMadre"]+$ResSEI["Hijos"]+$ResSEI["Otros"]).'">
                </div>
                <div class="c20">
                    <label class="l_form">Total Egresos</label>
                    $ <input type="number" name="total_egresos" id="total_egresos" style="width: calc(100% - 15px); margin-bottom: 20px;" value="'.($ResSEE["Alimentacion"]+$ResSEE["Transporte"]+$ResSEE["Renta"]+$ResSEE["Gas"]+$ResSEE["Telefono"]+$ResSEE["Agua"]+$ResSEE["ServicioMedico"]+$ResSEE["Luz"]+$ResSEE["Medicamentos"]+$ResSEE["Otros"]).'">
                </div>

                <div class="c100">
                    <label class="l_form"><strong>5. Descripción de la vivienda</strong></label>
                </div>
                <div class="c20">
                    <label class="l_form">Tipo de Vivienda</label>
                    <input type="radio" name="tipo_vivienda" id="tv_casasola" value="1"';if($ResVi["TipoVivienda"]==1){$cadena.=' checked';}$cadena.='> Casa Sola <br />
                    <input type="radio" name="tipo_vivienda" id="tv_departamento" value="2"';if($ResVi["TipoVivienda"]==2){$cadena.=' checked';}$cadena.='> Departamento <br />
                    <input type="radio" name="tipo_vivienda" id="tv_vecindad" value="3"';if($ResVi["TipoVivienda"]==3){$cadena.=' checked';}$cadena.='> Vecindad <br />
                    <input type="radio" name="tipo_vivienda" id="tv_otra" value="4"';if($ResVi["TipoVivienda"]==4){$cadena.=' checked';}$cadena.='> Otra <br />
                    <input type="text" name="tipo_vivienda_otro" id="tv_otra_esp" placeholder="Especifique" value="'.$ResVi["OtroTV"].'">
                </div>
                <div class="c20">
                    <label class="l_form">Su vivienda es</label>
                    <input type="radio" name="vivienda" id="vi_propia" value="1"';if($ResVi["Vivienda"]==1){$cadena.=' checked';}$cadena.='> Propia <br />
                    <input type="radio" name="vivienda" id="vi_pagando" value="2"';if($ResVi["Vivienda"]==2){$cadena.=' checked';}$cadena.='> Se esta pagando <br />
                    <input type="radio" name="vivienda" id="vi_rentrada" value="3"';if($ResVi["Vivienda"]==3){$cadena.=' checked';}$cadena.='> Rentada <br />
                    <input type="radio" name="vivienda" id="vi_prestada" value="4"';if($ResVi["Vivienda"]==4){$cadena.=' checked';}$cadena.='> Prestada <br />
                    <input type="radio" name="vivienda" id="vi_otra" value="5"';if($ResVi["Vivienda"]==5){$cadena.=' checked';}$cadena.='> Otra<br />
                    <input type="text" name="vivienda_otro" id="vi_otra_esp" placeholder="Especifique" value="'.$ResVi["OtroV"].'">
                </div>
                <div class="c20">
                    <label class="l_form">De que material es la mayor parte del piso</label>
                    <input type="radio" name="material" id="mpiso_tierra" value="1"';if($ResVi["Material"]==1){$cadena.=' checked';}$cadena.='> Tierra <br />
                    <input type="radio" name="material" id="mpiso_cemento" value="2"';if($ResVi["Material"]==2){$cadena.=' checked';}$cadena.='> Cemento firme <br />
                    <input type="radio" name="material" id="mpiso_loseta" value="3"';if($ResVi["Material"]==3){$cadena.=' checked';}$cadena.='> Loseta <br />
                    <input type="radio" name="material" id="mpiso_mosaico" value="4"';if($ResVi["Material"]==4){$cadena.=' checked';}$cadena.='> Mosaico <br />
                    <input type="radio" name="material" id="mpiso_otra" value="5"';if($ResVi["Material"]==5){$cadena.=' checked';}$cadena.='> Otra<br />
                    <input type="text" name="material_otro" id="mpiso_otra_esp" placeholder="Especifique" value="'.$ResVi["OtroM"].'">
                </div>
                <div class="c20">
                    <label class="l_form">De que material son las paredes de su vivienda</label>
                    <input type="radio" name="paredes" id="mpared_tabique" value="1"';if($ResVi["Paredes"]==1){$cadena.=' checked';}$cadena.='> Tabique <br />
                    <input type="radio" name="paredes" id="mpared_adobe" value="2"';if($ResVi["Paredes"]==2){$cadena.=' checked';}$cadena.='> Adobe <br />
                    <input type="radio" name="paredes" id="mpared_madera" value="3"';if($ResVi["Paredes"]==3){$cadena.=' checked';}$cadena.='> Madera <br />
                    <input type="radio" name="paredes" id="mpared_lamina" value="4"';if($ResVi["Paredes"]==4){$cadena.=' checked';}$cadena.='> Lamina <br />
                    <input type="radio" name="paredes" id="mpared_otra" value="5"';if($ResVi["Paredes"]==5){$cadena.=' checked';}$cadena.='> Otra<br />
                    <input type="text" name="paredes_otro" id="mpared_otra_esp" placeholder="Especifique" value="'.$ResVi["OtroP"].'">
                </div>
                <div class="c10"></div>
                <div class="c20">
                    <label class="l_form">De que material es el techo de su vivienda</label>
                    <input type="radio" name="techo" id="mtecho_concreto" value="1"';if($ResVi["Techo"]==1){$cadena.=' checked';}$cadena.='> Concreto <br />
                    <input type="radio" name="techo" id="mtecho_laminag" value="2"';if($ResVi["Techo"]==2){$cadena.=' checked';}$cadena.='> Lamina galvanizada <br />
                    <input type="radio" name="techo" id="mtecho_laminac" value="3"';if($ResVi["Techo"]==3){$cadena.=' checked';}$cadena.='> Lamina de cartón <br />
                    <input type="radio" name="techo" id="mtecho_laminaa" value="4"';if($ResVi["Techo"]==4){$cadena.=' checked';}$cadena.='> Lamina de asbesto <br />
                    <input type="radio" name="techo" id="mtecho_otra" value="5"';if($ResVi["Techo"]==5){$cadena.=' checked';}$cadena.='> Otra<br />
                    <input type="text" name="techo_otro" id="mtecho_otra_esp" placeholder="Especifique" value="'.$ResVi["OtroT"].'">
                </div>
                <div class="c20">
                    <label class="l_form">Número de habitaciones</label>
                    <input type="number" name="comedor" id="comedor" value="';if($ResVi["Comedor"]!=0){$cadena.=$ResVi["Comedor"];}else{$cadena.='1';}$cadena.='" style="width:50px; margin-bottom: 10px"> Comedor <br />
                    <input type="number" name="cocina" id="cocina" value="';if($ResVi["Cocina"]!=0){$cadena.=$ResVi["Cocina"];}else{$cadena.='1';}$cadena.='" style="width:50px; margin-bottom: 10px"> Cocina <br />
                    <input type="number" name="bano" id="bano" value="';if($ResVi["Bannos"]!=0){$cadena.=$ResVi["Bannos"];}else{$cadena.='1';}$cadena.='" style="width:50px; margin-bottom: 10px"> Baño <br />
                    <input type="number" name="recamaras" id="recamaras" value="';if($ResVi["Recamaras"]!=0){$cadena.=$ResVi["Recamaras"];}else{$cadena.='1';}$cadena.='" style="width:50px; margin-bottom: 10px"> Recamaras
                </div>
                <div class="c50"></div>

                <div class="c100">
                    <label class="l_form"><strong>6. Diagnostico Social</strong></label>
                </div>
                <div class="c100">
                    <label class="l_form">Observaciones</label>
                    <textarea name="observaciones" id="observaciones">'.$ResDS["Diagnostico"].'</textarea>
                </div>
                <div class="c100">
                    <label class="l_form"><strong>7. Acompañante</strong></label>
                </div>
                <div class="c100">´
                    <select name="acompannante" id="acompannante">';
                while($RResAco=mysqli_fetch_array($ResAco))
                {
                    $cadena.='<option value="'.$RResAco["Id"].'">'.$RResAco["Nombre"].' '.$RResAco["Apellidos"].'</option>';
                }
$cadena.='          </select>
                </div>
                <div class="c100">
                    <input type="hidden" name="hacer" id="hacer" value="guardar_es">
                    <input type="hidden" name="buscaidpaciente" id="buscaidpaciente" value="'.$ResPaciente["Id"].'">
                    <input type="hidden" name="buscar" id="buscar" value="1">
                    <input type="submit" name="botestsocio" id="botestsocio" value="Guardar >>">
                </div>
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

function adestfam(parentesco, edad, ocupacion)
{
    var nombre=document.getElementById("nombre_fam").value; 
    var parentesco=document.getElementById("parentesco_fam").value;
    var edad=document.getElementById("edad_fam").value;
    var ocupacion=document.getElementById("ocupacion_fam").value;
    var idpaciente=document.getElementById("paciente").value;

    $.ajax({
				type: 'POST',
				url : 'trabajo_social/estructura_familiar.php',
                data: 'nombre=' + nombre + '&parentesco=' + parentesco + '&edad=' + edad + '&ocupacion=' + ocupacion + '&idpaciente=' + idpaciente
	}).done (function ( info ){
		$('#estructura_familiar').html(info);
	});

    document.getElementById("nombre_fam").value='';
    document.getElementById("parentesco_fam").value='';
    document.getElementById("edad_fam").value='';
    document.getElementById("ocupacion_fam").value='';
}

function del_familiar(familiar)
{
    $.ajax({
				type: 'POST',
				url : 'trabajo_social/estructura_familiar.php',
                data: 'familiar=' + familiar + '&hacer=borrar'
	}).done (function ( info ){
		$('#estructura_familiar').html(info);
	});
}

$("#festsocio").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("festsocio"));

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

//calcula ingreso
function calculoi(){
    var ingresoe=document.getElementById('ingreso_esposo').value;
    var ingresopm=document.getElementById('ingreso_padremadre').value;
    var ingresoh=document.getElementById('ingreso_hijos').value;
    var ingresoo=document.getElementById('ingreso_otros').value;

    ingreso = Number(ingresoe) + Number(ingresopm) + Number(ingresoh) + Number(ingresoo);

    document.getElementById('total_ingresos').value=ingreso;
}

//calcula egreso
function calculoe(){
    var alimentacion=document.getElementById('egreso_alimentacion').value;
    var transporte=document.getElementById('egreso_transporte').value;
    var renta=document.getElementById('egreso_renta').value;
    var gas=document.getElementById('egreso_gas').value;
    var telefono=document.getElementById('egreso_telefono').value;
    var agua=document.getElementById('egreso_agua').value;
    var medico=document.getElementById('egreso_medico').value;
    var luz=document.getElementById('egreso_luz').value;
    var medicamentos=document.getElementById('egreso_medicamentos').value;
    var otros=document.getElementById('egreso_otros').value;

    egreso = Number(alimentacion) + Number(transporte) + Number(renta) + Number(gas) + Number(telefono) + Number(agua) + Number(medico) + Number(luz) + Number(medicamentos) + Number(otros);

    document.getElementById('total_egresos').value=egreso;
}

//mostrar mensaje despues de los cambios
setTimeout(function() { 
    $('#mesaje').fadeOut('fast'); 
}, 1000)
</script>