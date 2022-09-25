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

$ResAco=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM acompannantes WHERE Id='".$_GET["idacompannante"]."' LIMIT 1"));
$Estado=mysqli_fetch_array(mysqli_query($conn, "SELECT Estado FROM Estados WHERE Id='".$ResAco["Estado"]."' LIMIT 1"));

//genera codigo QR
$filename = $PNG_TEMP_DIR.'A_'.$ResAco["Id"].'.png';
QRcode::png('?re='.$ResAco["Id"], $filename, 'H', '4', 2);


//crear el nuevo archivo pdf
$pdf=new FPDF();

//desabilitar el corte automatico de pagina
$pdf->SetAutoPageBreak(false);

//Agregamos la primer pagina
$pdf->AddPage('P', 'Letter');

//marco de la credencial
$pdf->SetFillColor(000,000,000);
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(4);
$pdf->SetX(4);
$pdf->Cell(100,60,'',1,0,'L', FALSE, 1);
//marco 2 de la credencial
$pdf->SetFillColor(000,000,000);
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(4);
$pdf->SetX(104);
$pdf->Cell(100,60,'',1,0,'L', FALSE, 1);
//logo 
$pdf->Image('../images/logo.png',60,8,40);
//foto paciente
$pdf->Image('./fotos/'.$ResAco["Foto"],8,8,30);
//folio num. paciente
//folio num. paciente
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(27);
$pdf->SetX(70);
$pdf->Cell(8,4,'Registro: ',0,0,'L',0);
$pdf->SetX(85);
$pdf->Cell(13,4,$ResAco["Id"],'B',0,'L',0);
//
$pdf->SetY(32);
$pdf->SetX(8);
$pdf->Cell(60,4,'Nombre: ',0,0,'L',0);
$pdf->SetFont('Arial','',9);
$pdf->SetY(32);
$pdf->SetX(23);
$pdf->Cell(75,4,strtoupper(utf8_decode($ResAco["Nombre"].' '.$ResAco["Apellidos"])),'B',0,'L',0);
//
$pdf->SetFont('Arial','B',9);
$pdf->SetY(38);
$pdf->SetX(8);
$pdf->Cell(60,4,'Procedencia: ',0,0,'L',0);
$pdf->SetFont('Arial','',9);
$pdf->SetY(38);
$pdf->SetX(29);
$pdf->Cell(69,4,$Estado["Estado"],'B',0,'L',0);
$pdf->SetFont('Arial','B',9);
$pdf->SetY(42);
$pdf->SetX(40);
//
$pdf->SetFont('Arial','B',9);
$pdf->SetY(44);
$pdf->SetX(8);
$pdf->Cell(10,4,'Paciente: ',0,0,'L',0);
$pdf->SetFont('Arial','',9);
$pdf->SetY(44);
$pdf->SetX(24);
$ResPac=mysqli_fetch_array(mysqli_query($conn, "SELECT Nombre, Apellidos FROM pacientes WHERE Id='".$ResAco["IdPaciente"]."' LIMIT 1"));
$pdf->Cell(74,4,strtoupper(utf8_decode($ResPac["Nombre"].' '.$ResPac["Apellidos"])),'B',0,'L',0);
//
$pdf->SetFont('Arial','B',9);
$pdf->SetY(50);
$pdf->SetX(8);
$pdf->Cell(20,4,'Con Registro: ',0,0,'L',0);
$pdf->SetFont('Arial','',9);
$pdf->SetY(50);
$pdf->SetX(30);
$pdf->Cell(68,4,$ResAco["IdPaciente"],'B',0,'L',0);
//
$pdf->SetFont('Arial','B',8);
$pdf->SetY(55);
$pdf->SetX(8);
$pdf->Cell(60,4,'VOLUNTARIAS VICENTINAS ALBERGUE "LA ESPERANZA" I.A.P.',0,0,'L',0);
$pdf->SetFont('Arial','',9);
$pdf->SetY(59);
$pdf->SetX(8);
$pdf->Cell(60,4,'Xontepec No. 105',0,0,'L',0);
$pdf->SetX(70);
$pdf->Cell(60,4,'Tel: 55 5606 - 8916',0,0,'L',0);
//
//parte trasera
$pdf->SetFont('Arial','B',7 );
$pdf->SetY(4);
$pdf->SetX(106);
$pdf->Cell(57,4,'Al ingresar al albergue te comprometes a:',0,0,'L',0);
$pdf->SetFont('Arial','',7);
$pdf->SetY(8);
$pdf->SetX(106);
$pdf->Cell(60,4,'- Cumplir con todas las reglas del albergue',0,0,'L',0);
$pdf->SetY(12);
$pdf->SetX(106);
$pdf->Cell(60,4,utf8_decode('- Respetar a tus compañeros, a las hermanas y a'),0,0,'L',0);
$pdf->SetY(16);
$pdf->SetX(106);
$pdf->Cell(60,4,utf8_decode('  las voluntarias'),0,0,'L',0);
$pdf->SetY(20);
$pdf->SetX(106);
$pdf->Cell(60,4,utf8_decode('- Asistit a la junta de trabajo todos los dias a'),0,0,'L',0);
$pdf->SetY(24);
$pdf->SetX(106);
$pdf->Cell(60,4,utf8_decode('  las 6:30 pm'),0,0,'L',0);
$pdf->SetY(28);
$pdf->SetX(106);
$pdf->Cell(60,4,utf8_decode('- Asear la habitación, áreas comunes dentro del '),0,0,'L',0);
$pdf->SetY(32);
$pdf->SetX(106);
$pdf->Cell(60,4,utf8_decode('  albergue, tareas asignadas (limpieza, ayuda en '),0,0,'L',0);
$pdf->SetY(36);
$pdf->SetX(106);
$pdf->Cell(60,4,utf8_decode(' cocina, mantenimiento, sacar la basura, etc.) y '),0,0,'L',0);
$pdf->SetY(40);
$pdf->SetX(106);
$pdf->Cell(60,4,utf8_decode(' ayudas que le sean solicitads'),0,0,'L',0);
$pdf->SetY(44);
$pdf->SetX(106);
$pdf->Cell(60,4,utf8_decode('- No utlizar el telefono celular después de las 9:00m'),0,0,'L',0);
$pdf->SetY(48);
$pdf->SetX(106);
$pdf->Cell(60,4,utf8_decode('- No preparar, ingerir o guardar alimentos dentro de la habitación'),0,0,'L',0);
$pdf->SetY(52);
$pdf->SetX(106);
$pdf->Cell(60,4,utf8_decode('- Atender y estar pendiente de las necesidades de tu paciente'),0,0,'L',0);
$pdf->SetY(56);
$pdf->SetX(106);
$pdf->Cell(60,4,utf8_decode('- Avisar de cualquier anomalía o problema que detectes a las Hermanas o a la'),0,0,'L',0);
$pdf->SetY(60);
$pdf->SetX(106);
$pdf->Cell(60,4,utf8_decode('  Directora del Albergue'),0,0,'L',0);
$pdf->SetFont('Arial','B',7 );
$pdf->SetY(44);
$pdf->SetX(106);
$pdf->Cell(60,4,utf8_decode('  NO'),0,0,'L',0);
$pdf->SetY(48);
$pdf->SetX(106);
$pdf->Cell(60,4,utf8_decode('  NO'),0,0,'L',0);
//codigo QR
$pdf->Image($PNG_WEB_DIR.basename($filename),163,5,40);

//bitacora
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '42', '".json_encode($_GET)."')");

//Envio Archivo
$pdf->Output();

