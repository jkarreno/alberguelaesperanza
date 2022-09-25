<?php
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

require('../libs/fpdf/fpdf.php');

//LIBRERIA QR
include ("../libs/phpqrcode/qrlib.php"); 
//set it to writable location, a place for temp generated PNG files
$PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
//html PNG location prefix
$PNG_WEB_DIR = 'temp/';

$ResPac=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM pacientes WHERE Id='".$_GET["idpaciente"]."' LIMIT 1"));
$Estado=mysqli_fetch_array(mysqli_query($conn, "SELECT Estado FROM Estados WHERE Id='".$ResPac["Estado"]."' LIMIT 1"));

//genera codigo QR
$filename = $PNG_TEMP_DIR.'P_'.$ResPac["Id"].'.png';
QRcode::png('?re='.$ResPac["Id"], $filename, 'H', '4', 2);

//crear el nuevo archivo pdf
$pdf=new FPDF();

//desabilitar el corte automatico de pagina
$pdf->SetAutoPageBreak(false);

//Agregamos la primer pagina
$pdf->AddPage('P', 'Letter');

//marco del recibo
$pdf->SetFillColor(000,000,000);
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(4);
$pdf->SetX(4);
$pdf->Cell(208,130,'',1,0,'L', FALSE, 1);
//logo 
$pdf->Image('../images/logo.png',190,8,20);
//marco del estado de cuenta
$pdf->SetFillColor(000,000,000);
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(138);
$pdf->SetX(4);
$pdf->Cell(208,130,'',1,0,'L', FALSE, 1);
//nombre del paciente
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(8);
$pdf->SetX(8);
$pdf->Cell(8,4,'Nombre: ',0,0,'L',0);
$pdf->SetX(27);
$pdf->SetFont('Arial','',12);
$pdf->Cell(122,4,utf8_decode($ResPac["Apellidos"].' '.$ResPac["Apellidos2"].' '.$ResPac["Nombre"]),'B',0,'L',0);
//registro
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(8);
$pdf->SetX(150);
$pdf->Cell(20,4,'No. REG: ',0,0,'L',0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(17,4,$ResPac["Id"],'B',0,'L',0);
//Domicilio
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(14);
$pdf->SetX(8);
$pdf->Cell(22,4,'Domicilio: ',0,0,'L',0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(157,4,$ResPac["Domicilio"],'B',0,'L',0);
//colonia
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(20);
$pdf->SetX(8);
$pdf->Cell(18,4,'Colonia: ',0,0,'L',0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(130,4,$ResPac["Colonia"],'B',0,'L',0);
//CP
$pdf->SetFont('Arial','B',12);
$pdf->Cell(12,4,'C.P.: ',0,0,'L',0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(20,4,$ResPac["CP"],'B',0,'L',0);
//estado
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(26);
$pdf->SetX(8);
$pdf->Cell(18,4,'Estado: ',0,0,'L',0);
$pdf->SetFont('Arial','',12);
$ResEstado=mysqli_fetch_array(mysqli_query($conn, "SELECT Estado FROM Estados WHERE Id='".$ResPac["Estado"]."' LIMIT 1"));
$pdf->Cell(90,4,$ResEstado["Estado"],'B',0,'L',0);
//municipio
$pdf->SetFont('Arial','B',12);
$pdf->Cell(24,4,'Municipio: ',0,0,'L',0);
$pdf->SetFont('Arial','',12);
$ResMunicipio=mysqli_fetch_array(mysqli_query($conn, "SELECT Municipio FROM municipios WHERE Id='".$ResPac["Municipio"]."' LIMIT 1"));
$pdf->Cell(68,4,$ResMunicipio["Municipio"],'B',0,'L',0);
//telefono
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(32);
$pdf->SetX(8);
$pdf->Cell(20,4,'Telefono: ',0,0,'L',0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(50,4,$ResPac["TelefonoFijo"],'B',0,'L',0);
//celular
$pdf->SetFont('Arial','B',12);
$pdf->Cell(18,4,'Celular: ',0,0,'L',0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(50,4,$ResPac["TelefonoCelular"],'B',0,'L',0);
//otro
$pdf->SetFont('Arial','B',12);
$pdf->Cell(12,4,'Otro: ',0,0,'L',0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(50,4,$ResPac["TelefonoContacto"],'B',0,'L',0);
//edad
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(38);
$pdf->SetX(8);
$pdf->Cell(13,4,'Edad: ',0,0,'L',0);
$pdf->SetFont('Arial','',12);
$fecha_nac = new DateTime(date('Y/m/d',strtotime($ResPac["FechaNacimiento"]))); // Creo un objeto DateTime de la fecha ingresada
$fecha_hoy = new DateTime(date('Y/m/d',time())); // Creo un objeto DateTime de la fecha de hoy
$cedad = date_diff($fecha_hoy,$fecha_nac);
$edadpac=$cedad->format('%Y').'/'.$cedad->format('%m');
$pdf->Cell(10,4,$edadpac,'B',0,'L',0);
//Sexo
$pdf->SetFont('Arial','B',12);
$pdf->Cell(13,4,'Sexo: ',0,0,'L',0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(8,4,$ResPac["Sexo"],'B',0,'L',0);
//estado civil
$pdf->SetFont('Arial','B',12);
$pdf->Cell(28,4,'Estado Civil: ',0,0,'L',0);
$pdf->SetFont('Arial','',12);
$ResEC=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM edocivil WHERE Id='".$ResPac["EdoCivil"]."' LIMIT 1"));
$pdf->Cell(40,4,$ResEC["EdoCivil"],'B',0,'L',0);
//religion
$pdf->SetFont('Arial','B',12);
$pdf->Cell(20,4,'Religion: ',0,0,'L',0);
$pdf->SetFont('Arial','',12);
$ResRE=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM religion WHERE Id='".$ResPac["Religion"]."' LIMIT 1"));
$pdf->Cell(68,4,$ResRE["Religion"],'B',0,'L',0);
//ocupación
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(44);
$pdf->SetX(8);
$pdf->Cell(25,4,utf8_decode('Ocupación: '),0,0,'L',0);
$pdf->SetFont('Arial','',12);
$ResOcupacion=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM ocupaciones WHERE Id='".$ResPac["Ocupacion"]."' LIMIT 1"));
$pdf->Cell(60,4,$ResOcupacion["Ocupacion"],'B',0,'L',0);
//escolaridad
$pdf->SetFont('Arial','B',12);
$pdf->Cell(27,4,'Escolaridad: ',0,0,'L',0);
$pdf->SetFont('Arial','',12);
$ResEscolaridad=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM escolaridad WHERE Id='".$ResPac["Escolaridad"]."' LIMIT 1"));
$pdf->Cell(88,4,$ResEscolaridad["Escolaridad"],'B',0,'L',0);
//Instituto 
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(50);
$pdf->SetX(8);
$pdf->Cell(38,4,utf8_decode('Instituto Tratante: '),0,0,'L',0);
$pdf->SetFont('Arial','',12);
$ResInstituto=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM institutos WHERE Id='".$ResPac["Instituto1"]."' LIMIT 1"));
$pdf->Cell(162,4,$ResInstituto["Instituto"],'B',0,'L',0);
//Diagnostico
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(56);
$pdf->SetX(8);
$pdf->Cell(28,4,utf8_decode('Diagnostico: '),0,0,'L',0);
$pdf->SetFont('Arial','',12);
$ResDiag=mysqli_fetch_array(mysqli_query($conn, "SELECT Diagnostico FROM diagnosticos WHERE Id='".$ResPac["Diagnostico1"]."' LIMIT 1"));
$pdf->Cell(100,4,utf8_decode($ResDiag["Diagnostico"]),'B',0,'L',0);
//enviado
$pdf->SetFont('Arial','B',12);
$pdf->Cell(20,4,utf8_decode('Enviado: '),0,0,'L',0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(52,4,'','B',0,'L',0);
$pdf->SetY(68);
$pdf->SetX(8);
$pdf->Cell(200,4,'','B',0,'L',0);
//como inicio el problema
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(74);
$pdf->SetX(8);
$pdf->Cell(52,4,utf8_decode('Como inicio el problema: '),0,0,'L',0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(148,4,'','B',0,'L',0);
//primeros cuidadodos
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(80);
$pdf->SetX(8);
$pdf->Cell(62,4,utf8_decode('Primeros cuidados ¿Donde?: '),0,0,'L',0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(138,4,'','B',0,'L',0);
//con quien vive
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(86);
$pdf->SetX(8);
$pdf->Cell(33,4,utf8_decode('Con quien vive: '),0,0,'L',0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(167,4,'','B',0,'L',0);
//dejo problemas fam
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(92);
$pdf->SetX(8);
$pdf->Cell(45,4,utf8_decode('Dejo problemas fam.: '),0,0,'L',0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(155,4,'','B',0,'L',0);
//responsables de sus gastos df
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(98);
$pdf->SetX(8);
$pdf->Cell(68,4,utf8_decode('Responsable de sus gastos D.F.: '),0,0,'L',0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(131,4,'','B',0,'L',0);
//observaciones
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(104);
$pdf->SetX(8);
$pdf->Cell(33,4,utf8_decode('Observaciones: '),0,0,'L',0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(166,4,'','B',0,'L',0);
//nombre de quien recibe
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(120);
$pdf->SetX(107);
$pdf->Cell(100,4,'','B',0,'L',0);
$pdf->SetY(126);
$pdf->SetX(107);
$pdf->Cell(100,4,utf8_decode('Nombre quien recibio y fecha'),0,0,'C',0);
//
//
//estado de cuenta
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(140);
$pdf->SetX(8);
$pdf->Cell(3,4,' ',0,0,'C',0);
$pdf->Cell(40,4,utf8_decode('FECHA INGRESO'),0,0,'C',0);
$pdf->Cell(3,4,' ',0,0,'C',0);
$pdf->Cell(40,4,utf8_decode('FECHA EGRESO'),0,0,'C',0);
$pdf->Cell(3,4,' ',0,0,'C',0);
$pdf->Cell(28,4,utf8_decode('PAGO'),0,0,'C',0);
$pdf->Cell(3,4,' ',0,0,'C',0);
$pdf->Cell(28,4,utf8_decode('RECIBO'),0,0,'C',0);
$pdf->Cell(3,4,' ',0,0,'C',0);
$pdf->Cell(40,4,utf8_decode('FECHA'),0,0,'C',0);
$pdf->Cell(3,4,' ',0,0,'C',0);

$Y=146; $j=1;

$ResReservaciones=mysqli_query($conn, "SELECT * FROM reservacion WHERE IdPaciente='".$_GET["idpaciente"]."' ORDER BY FECHA DESC");
while($ResR=mysqli_fetch_array($ResReservaciones))
{
    $fe=date("Y-m-d",strtotime($ResR["Fecha"]."+ ".($ResR["Dias"]-1)." days"));
    $Rec=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM pagoreservacion WHERE IdReservacion='".$ResR["Id"]."' LIMIT 1"));

    $pdf->SetFillColor(255,255,255);
    $pdf->SetFont('Arial','',12);
    $pdf->SetTextColor(000,000,000);
    $pdf->SetY($Y);
    $pdf->SetX(8);
    $pdf->Cell(3,4,' ',0,0,'C',0);
    $pdf->Cell(40,4,fecha($ResR["Fecha"]),'B',0,'C',0);
    $pdf->Cell(3,4,' ',0,0,'C',0);
    $pdf->Cell(40,4,fecha($fe),'B',0,'C',0);
    $pdf->Cell(3,4,' ',0,0,'C',0);
    $pdf->Cell(28,4,'$ '.number_format($Rec["Monto"],2),'B',0,'R',0);
    $pdf->Cell(3,4,' ',0,0,'C',0);
    $pdf->Cell(28,4,$Rec["Id"],'B',0,'C',0);
    $pdf->Cell(3,4,' ',0,0,'C',0);
    $pdf->Cell(40,4,fecha($Rec["Fecha"]),'B',0,'C',0);
    $pdf->Cell(3,4,'',0,0,'C',0);

    $Y=$Y+6; $j++;
}
while($j<=20)
{
    $pdf->SetFillColor(255,255,255);
    $pdf->SetFont('Arial','',12);
    $pdf->SetTextColor(000,000,000);
    $pdf->SetY($Y);
    $pdf->SetX(8);
    $pdf->Cell(3,4,' ',0,0,'C',0);
    $pdf->Cell(40,4,' ','B',0,'C',0);
    $pdf->Cell(3,4,' ',0,0,'C',0);
    $pdf->Cell(40,4,' ','B',0,'C',0);
    $pdf->Cell(3,4,' ',0,0,'C',0);
    $pdf->Cell(28,4,' ','B',0,'C',0);
    $pdf->Cell(3,4,' ',0,0,'C',0);
    $pdf->Cell(28,4,' ','B',0,'C',0);
    $pdf->Cell(3,4,' ',0,0,'C',0);
    $pdf->Cell(40,4,' ','B',0,'C',0);
    $pdf->Cell(3,4,' ',0,0,'C',0);

    $Y=$Y+6; $j++;
}







//codigo QR
//$pdf->Image($PNG_WEB_DIR.basename($filename),163,5,40);

//Envio Archivo
$pdf->Output();