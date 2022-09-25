<?php
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

require('../libs/fpdf/fpdf.php');

$ResPaciente=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM pacientes WHERE Id='".$_GET["paciente"]."' LIMIT 1"));
//salud
$ResSalud=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM es_salud WHERE IdPaciente='".$_GET["paciente"]."' LIMIT 1"));

//edad paciente
$fecha_nac = new DateTime(date('Y/m/d',strtotime($ResPaciente["FechaNacimiento"]))); // Creo un objeto DateTime de la fecha ingresada
$fecha_hoy = new DateTime(date('Y/m/d',time())); // Creo un objeto DateTime de la fecha de hoy
$cedad = date_diff($fecha_hoy,$fecha_nac);
$edadpac=$cedad->format('%Y').'/'.$cedad->format('%m');

//ingresos
$ResSI=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM es_situacioneconomica_ingreso WHERE IdPaciente='".$_GET["paciente"]."' LIMIT 1"));
$TI=$ResSI["Esposo"]+$ResSI["PadreMadre"]+$ResSI["Hijos"]+$ResSI["Otros"];
//egresos
$ResSE=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM es_situacioneconomica_egreso WHERE IdPaciente='".$_GET["paciente"]."' LIMIT 1"));
$TE=$ResSE["Alimentacion"]+$ResSE["Transporte"]+$ResSE["Renta"]+$ResSE["Gas"]+$ResSE["Telefono"]+$ResSE["Agua"]+$ResSE["ServicioMedico"]+$ResSE["Luz"]+$ResSE["Medicamentos"]+$ResSE["Otros"];
//descripcion de la vivienda
$ResViv=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM  es_vivienda  WHERE IdPaciente='".$_GET["paciente"]."' LIMIT 1"));
//diagnostico social
$ResDS=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM es_diagnosticosocial WHERE IdPaciente='".$_GET["paciente"]."' LIMIT 1"));
//acompañante
$ResAco=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM acompannantes WHERE Id='".$ResDS["IdAcompannante"]."' LIMIT 1"));

//edad acompañante
$fecha_nac_aco = new DateTime(date('Y/m/d',strtotime($ResAco["FechaNacimiento"]))); // Creo un objeto DateTime de la fecha ingresada
$fecha_hoy = new DateTime(date('Y/m/d',time())); // Creo un objeto DateTime de la fecha de hoy
$cedad_aco = date_diff($fecha_hoy,$fecha_nac_aco);
$edadaco=$cedad_aco->format('%Y').' / '.$cedad->format('%m');

//crear el nuevo archivo pdf
$pdf=new FPDF();

//desabilitar el corte automatico de pagina
$pdf->SetAutoPageBreak(false);

//Agregamos la primer pagina
$pdf->AddPage('P', 'Letter');

//marco del titulo
$pdf->SetFillColor(000,000,000);
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(4);
$pdf->SetX(4);
$pdf->Cell(208,20,'',1,0,'L', FALSE, 1);
$pdf->SetY(4);
$pdf->SetX(4);
$pdf->Cell(30,20,'',1,0,'L', FALSE, 1);
//logo 
$pdf->Image('../images/logo.png',8,10,20);
//titulo
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(6);
$pdf->SetX(34);
$pdf->Cell(178,4,'Voluntarias Vicentinas Albergue La Esperanza I.A.P.',0,0,'C',0);
$pdf->SetY(12);
$pdf->SetX(34);
$pdf->Cell(178,4,'Trabajo Social',0,0,'C',0);
$pdf->SetY(18);
$pdf->SetX(34);
$pdf->Cell(178,4,utf8_decode('Estudio Socioeconómico'),0,0,'C',0);
//objetivo
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(26);
$pdf->SetX(4);
$pdf->MultiCell(208,4,utf8_decode('OBJETIVO: Identificar datos generales, la situación de salud, el entorno económico social y familiar del albergado para brindarle el apoyo necesario y útil en su tratamiendo médic, coadyuvando a la recuperaión de su salud o al mantenimiento y control del mismo, dependiendo de su padecimiento.'),0,'J',0);
//1.datos generales
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(40);
$pdf->SetX(4);
$pdf->Cell(208,4,utf8_decode('1. Datos Generales del Albergado'),0,0,'L',0);
//
$pdf->SetY(44);
$pdf->SetX(4);
$pdf->Cell(208,60,'',1,0,'L', FALSE, 1);
//
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(46);
$pdf->SetX(6);
$pdf->Cell(8,4,'No. de Reg.: ',0,0,'L',0);
$pdf->SetX(28);
$pdf->SetFont('Arial','',10);
$pdf->Cell(25,4,$ResPaciente["Id"],'B',0,'L',0);
//
$pdf->SetX(60);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(51,4,'Fecha de Ingreso al albergue: ',0,0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(50,4,fecha($ResPaciente["FechaRegistro"]),'B',0,'L',0);
//
$pdf->SetY(52);
$pdf->SetX(6);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(14,4,'Carnet: ',0,0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(25,4,$ResPaciente["Carnet1"],'B',0,'L',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->Cell(58,4,utf8_decode('Nivel Socioeconómico asignado : '),0,0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(25,4,$ResPaciente["NivelSocioeconomico"],'B',0,'L',0);
//
$pdf->SetY(58);
$pdf->SetX(6);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(16,4,'Nombre: ',0,0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(120,4,utf8_decode($ResPaciente["Nombre"].' '.$ResPaciente["Apellidos"]),'B',0,'L',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->Cell(11,4,utf8_decode('Sexo: '),0,0,'L',0);
$pdf->SetFont('Arial','',10);
if($ResPaciente["Sexo"]=='M'){$sexo='Masculino';}else{$sexo='Femenino';}
$pdf->Cell(25,4,$sexo,'B',0,'L',0);
//
$pdf->SetY(64);
$pdf->SetX(6);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(11,4,'Edad: ',0,0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(35,4,utf8_decode($edadpac),'B',0,'L',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->Cell(38,4,utf8_decode('Fecha de Nacimiento: '),0,0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(50,4,fecha($ResPaciente["FechaNacimiento"]),'B',0,'L',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->Cell(23,4,utf8_decode('Estado Civil: '),0,0,'L',0);
$pdf->SetFont('Arial','',10);
$ResEdC=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM edocivil WHERE Id='".$ResPaciente["EdoCivil"]."' LIMIT 1"));
$pdf->Cell(40,4,$ResEdC["EdoCivil"],'B',0,'L',0);
//
$pdf->SetY(70);
$pdf->SetX(6);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(23,4,'Escolaridad: ',0,0,'L',0);
$pdf->SetFont('Arial','',10);
$ResEsc=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM escolaridad WHERE Id='".$ResPaciente["Escolaridad"]."' LIMIT 1"));
if($ResPaciente["NivelEscolaridad"]==1){$nesc='TERMINADA';}
if($ResPaciente["NivelEscolaridad"]==0){$nesc='TRUNCA';}
$pdf->Cell(60,4,utf8_decode($ResEsc["Escolaridad"].' - '.$nesc),'B',0,'L',0);
//
$pdf->SetY(76);
$pdf->SetX(6);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(19,4,'Domicilio: ',0,0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(155,4,utf8_decode($ResPaciente["Domicilio"]),'B',0,'L',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->Cell(10,4,utf8_decode('C.P.: '),0,0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(15,4,$ResPaciente["CP"],'B',0,'L',0);
//
$pdf->SetY(82);
$pdf->SetX(6);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(16,4,'Colonia: ',0,0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(110,4,utf8_decode($ResPaciente["Colonia"]),'B',0,'L',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->Cell(15,4,utf8_decode('Estado:'),0,0,'L',0);
$pdf->SetFont('Arial','',10);
if($ResPaciente["Estado"]=='1'){$estado='Ciudad de México';}
if($ResPaciente["Estado"]=='2'){$estado='Estado de México';}
if($ResPaciente["Estado"]=='3'){$estado='Aguascalientes';}
if($ResPaciente["Estado"]=='4'){$estado='Baja California';}
if($ResPaciente["Estado"]=='5'){$estado='Baja California Sur';}
if($ResPaciente["Estado"]=='6'){$estado='Campeche';}
if($ResPaciente["Estado"]=='7'){$estado='Coahuila de Zaragoza';}
if($ResPaciente["Estado"]=='8'){$estado='Colima';}
if($ResPaciente["Estado"]=='9'){$estado='Chiapas';}
if($ResPaciente["Estado"]=='10'){$estado='Chihuahua';}
if($ResPaciente["Estado"]=='11'){$estado='Durango';}
if($ResPaciente["Estado"]=='12'){$estado='Guanajuato';}
if($ResPaciente["Estado"]=='13'){$estado='Guerrero';}
if($ResPaciente["Estado"]=='14'){$estado='Hidalgo';}
if($ResPaciente["Estado"]=='15'){$estado='Jalisco';}
if($ResPaciente["Estado"]=='16'){$estado='Michoacán de Ocampo';}
if($ResPaciente["Estado"]=='17'){$estado='Morelos';}
if($ResPaciente["Estado"]=='18'){$estado='Nayarit';}
if($ResPaciente["Estado"]=='19'){$estado='Nuevo León';}
if($ResPaciente["Estado"]=='20'){$estado='Oaxaca';}
if($ResPaciente["Estado"]=='21'){$estado='Puebla';}
if($ResPaciente["Estado"]=='22'){$estado='Querétaro';}
if($ResPaciente["Estado"]=='23'){$estado='Quintana Roo';}
if($ResPaciente["Estado"]=='24'){$estado='San Luis Potosí';}
if($ResPaciente["Estado"]=='25'){$estado='Sinaloa';}
if($ResPaciente["Estado"]=='26'){$estado='Sonora';}
if($ResPaciente["Estado"]=='27'){$estado='Tabasco';}
if($ResPaciente["Estado"]=='28'){$estado='Tamaulipas';}
if($ResPaciente["Estado"]=='29'){$estado='Tlaxcala';}
if($ResPaciente["Estado"]=='30'){$estado='Veracruz';}
if($ResPaciente["Estado"]=='31'){$estado='Yucatán';}
if($ResPaciente["Estado"]=='32'){$estado='Zacatecas';}
$pdf->Cell(60,4,utf8_decode($estado),'B',0,'L',0);
//
$pdf->SetY(88);
$pdf->SetX(6);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(19,4,'Municipio: ',0,0,'L',0);
$pdf->SetFont('Arial','',10);
$ResMun=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM municipios WHERE Id='".$ResPaciente["Municipio"]."' LIMIT 1"));
$pdf->Cell(100,4,utf8_decode($ResMun["Municipio"]),'B',0,'L',0);
//
$pdf->SetY(94);
$pdf->SetX(6);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(25,4,'Telefono Fijo: ',0,0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(25,4,utf8_decode($ResPaciente["TelefonoFijo"]),'B',0,'L',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->Cell(30,4,'Telefono Celular: ',0,0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(25,4,utf8_decode($ResPaciente["TelefonoCelular"]),'B',0,'L',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->Cell(34,4,'Telefono Contacto: ',0,0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(25,4,utf8_decode($ResPaciente["TelefonoContacto"]),'B',0,'L',0);
//2.Salud
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(110);
$pdf->SetX(4);
$pdf->Cell(208,4,utf8_decode('2. Salud'),0,0,'L',0);
//
$pdf->SetY(114);
$pdf->SetX(4);
$pdf->Cell(208,120,'',1,0,'L', FALSE, 1);
//
$pdf->SetY(118);
$pdf->SetX(6);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(58,4,'Instituto u Hospital que lo refiere: ',0,0,'L',0);
$pdf->SetFont('Arial','',10);
$ResIns=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM institutos WHERE Id='".$ResPaciente["Instituto1"]."' LIMIT 1"));
$pdf->Cell(140,4,utf8_decode($ResIns["Instituto"]),'B',0,'L',0);
//
$pdf->SetY(124);
$pdf->SetX(6);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(23,4,'Diagnostico: ',0,0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(175,4,utf8_decode($ResPaciente["Diagnostico1"]),'B',0,'L',0);
//
$pdf->SetY(130);
$pdf->SetX(6);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(23,4,'Antecedentes de la Enfermedad:',0,0,'L',0);
$pdf->SetY(136);
$pdf->SetX(6);
$pdf->SetFont('Arial','',10);
$pdf->MultiCell(200,4,utf8_decode($ResSalud["AntecedentesEnfermedad"]),0,'J',0);
//
$pdf->SetY(160);
$pdf->SetX(6);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(23,4,'Tratamiento:',0,0,'L',0);
$pdf->SetY(166);
$pdf->SetX(6);
$pdf->SetFont('Arial','',10);
$pdf->MultiCell(200,4,utf8_decode($ResSalud["Tratamiento"]),0,'J',0);
//
$pdf->SetY(190);
$pdf->SetX(6);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(88,4,'Frecuencia con la que acude al instituto u hospital:',0,0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(110,4,utf8_decode($ResSalud["FrecuenciaHospital"]),'B',0,'L',0);
//
$pdf->SetY(196);
$pdf->SetX(6);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(88,4,utf8_decode('Servicio médico con el que cuenta el albergado:'),0,0,'L',0);
$pdf->SetY(202);
$pdf->SetX(6);
$pdf->SetFont('Arial','',10);
switch($ResSalud["ServicioMedico"])
{
    case 1: $imss='X'; break; 
    case 2: $issste='X'; break; 
    case 3: $centro='X'; break; 
    case 4: $segurop='X'; break; 
    case 5: $ninguno='X'; break;
}
$pdf->Cell(10,4,utf8_decode('IMSS'),0,0,'L',0);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(5,4,utf8_decode($imss),1,0,'C',0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(13,4,utf8_decode('ISSTE'),0,0,'L',0);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(5,4,utf8_decode($issste),1,0,'C',0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(30,4,utf8_decode('Centro de Salud'),0,0,'L',0);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(5,4,utf8_decode($centro),1,0,'C',0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(28,4,utf8_decode('Seguro popular'),0,0,'L',0);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(5,4,utf8_decode($segurop),1,0,'C',0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(18,4,utf8_decode('Ninguno'),0,0,'L',0);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(5,4,utf8_decode($ninguno),1,0,'C',0);
//
$pdf->SetY(208);
$pdf->SetX(6);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(88,4,utf8_decode('Documentos solicitados para expediente (copia):'),0,0,'L',0);
$pdf->SetY(214);
$pdf->SetX(6);
$pdf->SetFont('Arial','',10);
if($ResSalud["IneP"]==1){$inep='X';}else{$inep='';}
if($ResSalud["CarnetHospital"]==1){$carnet='X';}else{$carnet='';}
if($ResSalud["ResumenMedico"]==1){$resumen='X';}else{$resumen='';}
if($ResSalud["IneA"]==1){$inea='X';}else{$inea='';}
$pdf->Cell(23,4,utf8_decode('INE paciente'),0,0,'L',0);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(5,4,utf8_decode($inep),1,0,'C',0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(26,4,utf8_decode('Carnet hospital'),0,0,'L',0);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(5,4,utf8_decode($carnet),1,0,'C',0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(30,4,utf8_decode('Resumen médico'),0,0,'L',0);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(5,4,utf8_decode($resumen),1,0,'C',0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(30,4,utf8_decode('INE acompañante'),0,0,'L',0);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(5,4,utf8_decode($inea),1,0,'C',0);

//
$pdf->AddPage('P', 'Letter');
//3. Estructura Familiar
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(4);
$pdf->SetX(4);
$pdf->Cell(208,4,utf8_decode('3. Estructura Familiar'),0,0,'L',0);
//
$pdf->SetY(10);
$pdf->SetX(4);
$pdf->Cell(208,100,'',1,0,'L', FALSE, 1);
//
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(12);
$pdf->SetX(6);
$pdf->Cell(200,4,utf8_decode('¿Con quién vive el albergado?'),0,0,'L',0);
//
$pdf->SetY(18);
$pdf->SetX(6);
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(000,000,000);
$pdf->Cell(80,4,utf8_decode('Nombre'),0,0,'C',0);
$pdf->Cell(5,4,utf8_decode(''),0,0,'C',0);
$pdf->Cell(45,4,utf8_decode('Parentesco'),0,0,'C',0);
$pdf->Cell(5,4,utf8_decode(''),0,0,'C',0);
$pdf->Cell(25,4,utf8_decode('Edad'),0,0,'C',0);
$pdf->Cell(5,4,utf8_decode(''),0,0,'C',0);
$pdf->Cell(39,4,utf8_decode('Ocupación'),0,0,'C',0);
//
$Y=24;
$ResES=mysqli_query($conn, "SELECT * FROM es_estructurafamiliar WHERE IdPaciente='".$_GET["paciente"]."' ORDER BY Id ASC");
while($RResES=mysqli_fetch_array($ResES))
{
    $pdf->SetY($Y);
    $pdf->SetX(6);
    $pdf->SetFillColor(255,255,255);
    $pdf->SetFont('Arial','',10);
    $pdf->SetTextColor(000,000,000);
    $pdf->Cell(80,4,utf8_decode($RResES["Nombre"]),'B',0,'L',0);
    $pdf->Cell(5,4,utf8_decode(''),0,0,'C',0);
    $pdf->Cell(45,4,utf8_decode($RResES["Parentesco"]),'B',0,'L',0);
    $pdf->Cell(5,4,utf8_decode(''),0,0,'C',0);
    $pdf->Cell(25,4,utf8_decode($RResES["Edad"]),'B',0,'L',0);
    $pdf->Cell(5,4,utf8_decode(''),0,0,'C',0);
    $pdf->Cell(39,4,utf8_decode($RResES["Ocupacion"]),'B',0,'L',0);

    $Y=$Y+6;
}
//4. Situacion economica
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(112);
$pdf->SetX(4);
$pdf->Cell(208,4,utf8_decode('4. Situación económica'),0,0,'L',0);
//
$pdf->SetY(118);
$pdf->SetX(4);
$pdf->Cell(208,6,utf8_decode('INGRESO FAMILIAR MENSUAL (¿Quién aporta al gasto familiar en casa?)'),1,0,'C',0);
//
$pdf->SetY(124);
$pdf->SetX(4);
$pdf->Cell(52,12,utf8_decode('ESPOSO'),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(124);
$pdf->SetX(56);
$pdf->Cell(52,6,'$ '.number_format($ResSI["Esposo"],2),1,0,'R',0);
//
$pdf->SetY(130);
$pdf->SetX(56);
$pdf->Cell(52,6,utf8_decode($ResSI["OcupacionE"]),1,0,'L',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(124);
$pdf->SetX(108);
$pdf->Cell(52,12,utf8_decode('HIJO(S)'),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(124);
$pdf->SetX(160);
$pdf->Cell(52,6,'$ '.number_format($ResSI["Hijos"],2),1,0,'R',0);
//
$pdf->SetY(130);
$pdf->SetX(160);
$pdf->Cell(52,6,utf8_decode($ResSI["OcupacionH"]),1,0,'L',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(136);
$pdf->SetX(4);
$pdf->Cell(52,12,utf8_decode('PADRE/MADRE'),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(136);
$pdf->SetX(56);
$pdf->Cell(52,6,'$ '.number_format($ResSI["PadreMadre"],2),1,0,'R',0);
//
$pdf->SetY(142);
$pdf->SetX(56);
$pdf->Cell(52,6,utf8_decode($ResSI["OcupacionPM"]),1,0,'L',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(194,000,005);
$pdf->SetY(136);
$pdf->SetX(108);
$pdf->Cell(52,12,utf8_decode('OTROS'),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(136);
$pdf->SetX(160);
$pdf->Cell(52,6,'$ '.number_format($ResSI["Otros"],2),1,0,'R',0);
//
$pdf->SetY(142);
$pdf->SetX(160);
$pdf->Cell(52,6,utf8_decode($ResSI["OcupacionO"]),1,0,'L',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(148);
$pdf->SetX(4);
$pdf->Cell(208,6,utf8_decode('EGRESO FAMILIAR MESUAL (¿Cómo es distribuido este ingreso familiar?)'),1,0,'C',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(154);
$pdf->SetX(4);
$pdf->Cell(52,6,utf8_decode('ALIMENTACIÓN'),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->Cell(52,6,'$ '.number_format($ResSE["Alimentacion"],2),1,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->Cell(52,6,utf8_decode('TRANSPORTE'),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->Cell(52,6,'$ '.number_format($ResSE["Transporte"],2),1,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(160);
$pdf->SetX(4);
$pdf->Cell(52,6,utf8_decode('RENTA O PREDIAL'),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->Cell(52,6,'$ '.number_format($ResSE["Renta"],2),1,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->Cell(52,6,utf8_decode('GAS'),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->Cell(52,6,'$ '.number_format($ResSE["Gas"],2),1,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(166);
$pdf->SetX(4);
$pdf->Cell(52,6,utf8_decode('TELÉFONO'),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->Cell(52,6,'$ '.number_format($ResSE["Telefono"],2),1,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->Cell(52,6,utf8_decode('AGUA'),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->Cell(52,6,'$ '.number_format($ResSE["Agua"],2),1,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(172);
$pdf->SetX(4);
$pdf->Cell(52,6,utf8_decode('SERVICIO MÉDICO'),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->Cell(52,6,'$ '.number_format($ResSE["ServicioMedico"],2),1,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->Cell(52,6,utf8_decode('LUZ'),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->Cell(52,6,'$ '.number_format($ResSE["Luz"],2),1,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(178);
$pdf->SetX(4);
$pdf->Cell(52,6,utf8_decode('MEDICAMENTOS'),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->Cell(52,6,'$ '.number_format($ResSE["Medicamentos"],2),1,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->Cell(52,6,utf8_decode('OTROS'),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->Cell(52,6,'$ '.number_format($ResSE["Otros"],2),1,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(184);
$pdf->SetX(4);
$pdf->Cell(52,6,utf8_decode('TOTAL INGRESOS'),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->Cell(52,6,'$ '.number_format($TI,2),1,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->Cell(52,6,utf8_decode('TOTAL EGRESOS'),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->Cell(52,6,'$ '.number_format($TE,2),1,0,'R',0);
//5. descripción de la vivienda
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(192);
$pdf->SetX(4);
$pdf->Cell(208,4,utf8_decode('5. Descripción de la vivienda'),0,0,'L',0);
//
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(198);
$pdf->SetX(4);
$pdf->Cell(69,12,utf8_decode('TIPO DE VIVIENDA'),1,0,'C',0);
//
$pdf->Cell(69,12,utf8_decode('SU VIVIENDA ES:'),1,0,'C',0);
//
$pdf->MultiCell(70,6,utf8_decode('DE QUE MATERIAL ES LA MAYOR PARTE DEL PISO DE LA VIVIENDA'),1,'C',0);
//
$pdf->SetY(210);
$pdf->SetX(4);
$pdf->Cell(69,38,'',1,0,'L', FALSE, 1);
//
$pdf->SetY(212);
$pdf->SetX(6);
$pdf->Cell(67,6,'CASA SOLA',0,0,'L', FALSE, 1);
$pdf->SetX(50);
if($ResViv["TipoVivienda"]==1){$viv='X';}else{$viv='  ';}
$pdf->Cell(20,6,'( '.$viv.' )',0,0,'L', FALSE, 1);
//
$pdf->SetY(218);
$pdf->SetX(6);
$pdf->Cell(67,6,'DEPARTAMENTO',0,0,'L', FALSE, 1);
$pdf->SetX(50);
if($ResViv["TipoVivienda"]==2){$viv='X';}else{$viv='  ';}
$pdf->Cell(20,6,'( '.$viv.' )',0,0,'L', FALSE, 1);
//
$pdf->SetY(224);
$pdf->SetX(6);
$pdf->Cell(67,6,'VECINDAD',0,0,'L', FALSE, 1);
$pdf->SetX(50);
if($ResViv["TipoVivienda"]==3){$viv='X';}else{$viv='  ';}
$pdf->Cell(20,6,'( '.$viv.' )',0,0,'L', FALSE, 1);
//
$pdf->SetY(230);
$pdf->SetX(6);
$pdf->Cell(67,6,'RANCHERIA',0,0,'L', FALSE, 1);
$pdf->SetX(50);
if($ResViv["TipoVivienda"]==4){$viv='X';}else{$viv='  ';}
$pdf->Cell(20,6,'( '.$viv.' )',0,0,'L', FALSE, 1);
//
$pdf->SetY(236);
$pdf->SetX(6);
$pdf->Cell(67,6,'OTRA (especifique)',0,0,'L', FALSE, 1);
$pdf->SetX(50);
if($ResViv["TipoVivienda"]==5){$viv='X';}else{$viv='  ';}
$pdf->Cell(20,6,'( '.$viv.' )',0,0,'L', FALSE, 1);
//
$pdf->SetY(240);
$pdf->SetX(6);
$pdf->SetFont('Arial','',10);
$pdf->Cell(67,6,utf8_decode($ResViv["OtroTV"]),0,0,'L', FALSE, 1);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(210);
$pdf->SetX(73);
$pdf->Cell(69,38,'',1,0,'L', FALSE, 1);
//
$pdf->SetY(212);
$pdf->SetX(75);
$pdf->Cell(67,6,'PROPIA',0,0,'L', FALSE, 1);
$pdf->SetX(125);
if($ResViv["Vivienda"]==1){$viv='X';}else{$viv='  ';}
$pdf->Cell(20,6,'( '.$viv.' )',0,0,'L', FALSE, 1);
//
$pdf->SetY(218);
$pdf->SetX(75);
$pdf->Cell(67,6,'SE ESTA PAGANDO',0,0,'L', FALSE, 1);
$pdf->SetX(125);
if($ResViv["Vivienda"]==2){$viv='X';}else{$viv='  ';}
$pdf->Cell(20,6,'( '.$viv.' )',0,0,'L', FALSE, 1);
//
$pdf->SetY(224);
$pdf->SetX(75);
$pdf->Cell(67,6,'RENTADA',0,0,'L', FALSE, 1);
$pdf->SetX(125);
if($ResViv["Vivienda"]==3){$viv='X';}else{$viv='  ';}
$pdf->Cell(20,6,'( '.$viv.' )',0,0,'L', FALSE, 1);
//
$pdf->SetY(230);
$pdf->SetX(75);
$pdf->Cell(67,6,'PRESTADA',0,0,'L', FALSE, 1);
$pdf->SetX(125);
if($ResViv["Vivienda"]==4){$viv='X';}else{$viv='  ';}
$pdf->Cell(20,6,'( '.$viv.' )',0,0,'L', FALSE, 1);
//
$pdf->SetY(236);
$pdf->SetX(75);
$pdf->Cell(67,6,'OTRA (especifique)',0,0,'L', FALSE, 1);
$pdf->SetX(125);
if($ResViv["Vivienda"]==5){$viv='X';}else{$viv='  ';}
$pdf->Cell(20,6,'( '.$viv.' )',0,0,'L', FALSE, 1);
//
$pdf->SetY(240);
$pdf->SetX(67);
$pdf->SetFont('Arial','',10);
$pdf->Cell(67,6,utf8_decode($ResViv["OtroV"]),0,0,'L', FALSE, 1);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(210);
$pdf->SetX(142);
$pdf->Cell(70,38,'',1,0,'L', FALSE, 1);
//
$pdf->SetY(212);
$pdf->SetX(144);
$pdf->Cell(67,6,'TIERRA',0,0,'L', FALSE, 1);
$pdf->SetX(194);
if($ResViv["Material"]==1){$viv='X';}else{$viv='  ';}
$pdf->Cell(20,6,'( '.$viv.' )',0,0,'L', FALSE, 1);
//
$pdf->SetY(218);
$pdf->SetX(144);
$pdf->Cell(67,6,'CEMENTO FIRME',0,0,'L', FALSE, 1);
$pdf->SetX(194);
if($ResViv["Material"]==2){$viv='X';}else{$viv='  ';}
$pdf->Cell(20,6,'( '.$viv.' )',0,0,'L', FALSE, 1);
//
$pdf->SetY(224);
$pdf->SetX(144);
$pdf->Cell(67,6,'LOSETA',0,0,'L', FALSE, 1);
$pdf->SetX(194);
if($ResViv["Material"]==3){$viv='X';}else{$viv='  ';}
$pdf->Cell(20,6,'( '.$viv.' )',0,0,'L', FALSE, 1);
//
$pdf->SetY(230);
$pdf->SetX(144);
$pdf->Cell(67,6,'MOSAICO',0,0,'L', FALSE, 1);
$pdf->SetX(194);
if($ResViv["Material"]==4){$viv='X';}else{$viv='  ';}
$pdf->Cell(20,6,'( '.$viv.' )',0,0,'L', FALSE, 1);
//
$pdf->SetY(236);
$pdf->SetX(144);
$pdf->Cell(67,6,'OTRO (especifique)',0,0,'L', FALSE, 1);
$pdf->SetX(194);
if($ResViv["Material"]==5){$viv='X';}else{$viv='  ';}
$pdf->Cell(20,6,'( '.$viv.' )',0,0,'L', FALSE, 1);
//
$pdf->SetY(240);
$pdf->SetX(144);
$pdf->SetFont('Arial','',10);
$pdf->Cell(67,6,utf8_decode($ResViv["OtroM"]),0,0,'L', FALSE, 1);
//
//Agregamos la tercer pagina
$pdf->AddPage('P', 'Letter');
//
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(8);
$pdf->SetX(4);
$pdf->MultiCell(69,6,utf8_decode('DE QUE MATERIAL SON LAS PAREDES DE SU VIVIENDA'),1,'C',0);
//
$pdf->SetY(8);
$pdf->SetX(73);
$pdf->MultiCell(69,6,utf8_decode('DE QUE MATERIAL ES EL TECHO DE SU VIVIENDA'),1,'C',0);
//
$pdf->SetY(8);
$pdf->SetX(142);
$pdf->Cell(70,12,utf8_decode('NÚMERO DE HABITACIONES'),1,0,'C',0);
//
$pdf->SetY(20);
$pdf->SetX(4);
$pdf->Cell(69,38,'',1,0,'L', FALSE, 1);
//
$pdf->SetY(22);
$pdf->SetX(6);
$pdf->Cell(67,6,'TABIQUE',0,0,'L', FALSE, 1);
$pdf->SetX(50);
if($ResViv["Paredes"]==1){$viv='X';}else{$viv='  ';}
$pdf->Cell(20,6,'( '.$viv.' )',0,0,'L', FALSE, 1);
//
$pdf->SetY(28);
$pdf->SetX(6);
$pdf->Cell(67,6,'ADOBE',0,0,'L', FALSE, 1);
$pdf->SetX(50);
if($ResViv["Paredes"]==2){$viv='X';}else{$viv='  ';}
$pdf->Cell(20,6,'( '.$viv.' )',0,0,'L', FALSE, 1);
//
$pdf->SetY(34);
$pdf->SetX(6);
$pdf->Cell(67,6,'MADERA',0,0,'L', FALSE, 1);
$pdf->SetX(50);
if($ResViv["Paredes"]==3){$viv='X';}else{$viv='  ';}
$pdf->Cell(20,6,'( '.$viv.' )',0,0,'L', FALSE, 1);
//
$pdf->SetY(40);
$pdf->SetX(6);
$pdf->Cell(67,6,'LAMINA',0,0,'L', FALSE, 1);
$pdf->SetX(50);
if($ResViv["Paredes"]==4){$viv='X';}else{$viv='  ';}
$pdf->Cell(20,6,'( '.$viv.' )',0,0,'L', FALSE, 1);
//
$pdf->SetY(46);
$pdf->SetX(6);
$pdf->Cell(67,6,'OTRO (especifique)',0,0,'L', FALSE, 1);
$pdf->SetX(50);
if($ResViv["Paredes"]==5){$viv='X';}else{$viv='  ';}
$pdf->Cell(20,6,'( '.$viv.' )',0,0,'L', FALSE, 1);
//
$pdf->SetY(52);
$pdf->SetX(6);
$pdf->SetFont('Arial','',10);
$pdf->Cell(67,6,utf8_decode($ResViv["OtroP"]),0,0,'L', FALSE, 1);
//
$pdf->SetY(20);
$pdf->SetX(73);
$pdf->Cell(69,38,'',1,0,'L', FALSE, 1);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(22);
$pdf->SetX(75);
$pdf->Cell(67,6,'CONCRETO',0,0,'L', FALSE, 1);
$pdf->SetX(125);
if($ResViv["Techo"]==1){$viv='X';}else{$viv='  ';}
$pdf->Cell(20,6,'( '.$viv.' )',0,0,'L', FALSE, 1);
//
$pdf->SetY(28);
$pdf->SetX(75);
$pdf->Cell(67,6,utf8_decode('LÁMINA GALVANIZADA'),0,0,'L', FALSE, 1);
$pdf->SetX(125);
if($ResViv["Techo"]==2){$viv='X';}else{$viv='  ';}
$pdf->Cell(20,6,'( '.$viv.' )',0,0,'L', FALSE, 1);
//
$pdf->SetY(34);
$pdf->SetX(75);
$pdf->Cell(67,6,utf8_decode('LÁMINA DE CARTÓN'),0,0,'L', FALSE, 1);
$pdf->SetX(125);
if($ResViv["Techo"]==3){$viv='X';}else{$viv='  ';}
$pdf->Cell(20,6,'( '.$viv.' )',0,0,'L', FALSE, 1);
//
$pdf->SetY(40);
$pdf->SetX(75);
$pdf->Cell(67,6,utf8_decode('LÁMINA DE ASBESTO'),0,0,'L', FALSE, 1);
$pdf->SetX(125);
if($ResViv["Techo"]==4){$viv='X';}else{$viv='  ';}
$pdf->Cell(20,6,'( '.$viv.' )',0,0,'L', FALSE, 1);
//
$pdf->SetY(46);
$pdf->SetX(75);
$pdf->Cell(67,6,'OTRO (especifique)',0,0,'L', FALSE, 1);
$pdf->SetX(125);
if($ResViv["Techo"]==5){$viv='X';}else{$viv='  ';}
$pdf->Cell(20,6,'( '.$viv.' )',0,0,'L', FALSE, 1);
//
$pdf->SetY(52);
$pdf->SetX(6);
$pdf->SetFont('Arial','',10);
$pdf->Cell(67,6,utf8_decode($ResViv["OtroT"]),0,0,'L', FALSE, 1);
//
$pdf->SetY(20);
$pdf->SetX(142);
$pdf->Cell(70,38,'',1,0,'L', FALSE, 1);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(22);
$pdf->SetX(144);
$pdf->Cell(67,6,'COMEDOR',0,0,'L', FALSE, 1);
$pdf->SetX(194);
$pdf->Cell(20,6,'( '.$ResViv["Comedor"].' )',0,0,'L', FALSE, 1);
//
$pdf->SetY(28);
$pdf->SetX(144);
$pdf->Cell(67,6,utf8_decode('COCINA'),0,0,'L', FALSE, 1);
$pdf->SetX(194);
$pdf->Cell(20,6,'( '.$ResViv["Cocina"].' )',0,0,'L', FALSE, 1);
//
$pdf->SetY(34);
$pdf->SetX(144);
$pdf->Cell(67,6,utf8_decode('BAÑO'),0,0,'L', FALSE, 1);
$pdf->SetX(194);
$pdf->Cell(20,6,'( '.$ResViv["Bannos"].' )',0,0,'L', FALSE, 1);
//
$pdf->SetY(40);
$pdf->SetX(144);
$pdf->Cell(67,6,utf8_decode('RECAMARAS'),0,0,'L', FALSE, 1);
$pdf->SetX(194);
$pdf->Cell(20,6,'( '.$ResViv["Recamaras"].' )',0,0,'L', FALSE, 1);
//6. Diagnostico Social
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(60);
$pdf->SetX(4);
$pdf->Cell(208,4,utf8_decode('6. Diagnóstico Social'),0,0,'L',0);
//
$pdf->SetY(66);
$pdf->SetX(4);
$pdf->Cell(208,50,'',1,0,'L',0);
//
$pdf->SetY(68);
$pdf->SetX(6);
$pdf->Cell(204,6,'OBSERVACIONES: ',0,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(74);
$pdf->SetX(6);
$pdf->MultiCell(204,6,utf8_decode($ResDS["Diagnostico"]),0,'J',0);
//7. acompañante
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(118);
$pdf->SetX(4);
$pdf->Cell(208,4,utf8_decode('7. Acompañante'),0,0,'L',0);
//
$pdf->SetY(124);
$pdf->SetX(4);
$pdf->Cell(208,50,'',1,0,'L',0);
//
$pdf->SetY(126);
$pdf->SetX(6);
$pdf->Cell(55,6,utf8_decode('NOMBRE DEL ACOMPAÑANTE: '),0,0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(125,6,utf8_decode($ResAco["Nombre"].' '.$ResACo["Apellidos"]),'B',0,'L',0);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(13,6,utf8_decode('SEXO: '),0,0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(10,6,$ResAco["Sexo"],'B',0,'L',0);
//
$pdf->SetY(132);
$pdf->SetX(6);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(22,6,utf8_decode('DOMICILIO: '),0,0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(181,6,utf8_decode($ResAco["Domicilio"]),'B',0,'L',0);
//
$pdf->SetY(138);
$pdf->SetX(6);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(28,6,utf8_decode('PARENTESCO: '),0,0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(50,6,utf8_decode($ResAco["Domicilio"]),'B',0,'L',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->Cell(13,6,utf8_decode('EDAD: '),0,0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(15,6,$edadaco,'B',0,'L',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->Cell(25,6,utf8_decode('OCUPACIÓN: '),0,0,'L',0);
$pdf->SetFont('Arial','',10);
$ocuaco=mysqli_fetch_array(mysqli_query($conn, "SELECT Ocupacion FROM ocupaciones WHERE Id='".$ResAco["Ocupacion"]."' LIMIT 1"));
$pdf->Cell(72,6,utf8_decode($ocuaco["Ocupacion"]),'B',0,'L',0);







//Envio Archivo
$pdf->Output();