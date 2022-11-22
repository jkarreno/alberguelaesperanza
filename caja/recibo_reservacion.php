<?php
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

//bitacora
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '10', '".json_encode($_GET)."')");

require('../libs/fpdf/fpdf.php');

$ResPRes=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM pagoreservacion WHERE Id='".$_GET["idrecibo"]."'"));
$ResRes=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM reservacion WHERE Id='".$ResPRes["IdReservacion"]."'"));
$ResPac=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, Nombre, Apellidos FROM pacientes WHERE Id='".$ResRes["IdPaciente"]."'"));

if($ResRes["Dias"]>1)
{
    $dias=date("Y-m-d",strtotime($ResRes["Fecha"]."+ ".($ResRes["Dias"]-1)." days"));
}
elseif($ResRes["Dias"]==1)
{
    $dias=$ResRes["Fecha"];
}

//crear el nuevo archivo pdf
$pdf=new FPDF('P', 'mm', 'Letter');

//desabilitar el corte automatico de pagina
$pdf->SetAutoPageBreak(false);

//Agregamos la primer pagina
$pdf->AddPage();

//marco del recibo
$pdf->SetFillColor(000,000,000);
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(4);
$pdf->SetX(4);
$pdf->Cell(208,90,'',1,0,'L', FALSE, 1);
//logo 
$pdf->Image('../images/logo.png',140,8,70);
//Titulo
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',14);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(8);
$pdf->SetX(8);
$pdf->Cell(60,4,'Recibo pago de hospedaje',0,0,'L',0);
//
$pdf->SetY(16);
$pdf->SetX(8);
$pdf->Cell(60,4,'Folio: ',0,0,'L',0);
//
$pdf->SetTextColor(255,000,000);
$pdf->SetX(28);
$pdf->Cell(60,4,$ResPRes["Id"],0,0,'L',0);
//Fecha
$pdf->SetTextColor(000,000,000);
$pdf->SetX(60);
$pdf->Cell(60,4,'Fecha: ',0,0,'L',0);
//
$pdf->SetX(77);
$pdf->SetFont('Arial','',14);
$pdf->Cell(60,4,fecha($ResPRes["Fecha"]),0,0,'L',0);
//
$pdf->SetTextColor(000,000,000);
$pdf->SetY(24);
$pdf->SetX(8);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(60,4,'Paciente: ',0,0,'L',0);
//
$pdf->SetY(32);
$pdf->SetX(8);
$pdf->SetFont('Arial','',14);
$pdf->Cell(60,4,$ResPac["Id"].' - '.$ResPac["Nombre"].' '.$ResPac["Apellidos"],0,0,'L',0);
//
$pdf->SetY(40);
$pdf->SetX(8);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(60,4,'Dias de hospedaje: ',0,0,'L',0);
//
$pdf->SetY(40);
$pdf->SetX(55);
$pdf->SetFont('Arial','',14);
$pdf->Cell(60,4,$ResRes["Dias"],0,0,'L',0);
//
$pdf->SetY(48);
$pdf->SetX(8);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(60,4,'Del: ',0,0,'L',0);
//
$pdf->SetY(48);
$pdf->SetX(20);
$pdf->SetFont('Arial','',14);
$pdf->Cell(60,4,fecha($ResRes["Fecha"]),0,0,'L',0);
//
$pdf->SetY(48);
$pdf->SetX(80);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(60,4,'Al: ',0,0,'L',0);
//
$pdf->SetY(48);
$pdf->SetX(90);
$pdf->SetFont('Arial','',14);
$pdf->Cell(60,4,fecha($dias),0,0,'L',0);
//
$pdf->SetY(56);
$pdf->SetX(8);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(30,4,'Monto: ',0,0,'L',0);
//
$pdf->SetFont('Arial','',14);
$pdf->Cell(30,4,'$ '.number_format($ResPRes["Pago"], 2),0,0,'R',0);
//descuento
$pdf->SetY(62);
$pdf->SetX(8);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(30,4,'Descuento: ',0,0,'L',0);
//
$pdf->SetFont('Arial','',14);
$pdf->Cell(30,4,'$ '.number_format($ResPRes["Descuento"], 2),0,0,'R',0);
$pdf->Cell(60,4,$ResPRes["DetDescuento"],0,0,'L',0);
//extra
$pdf->SetY(68);
$pdf->SetX(8);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(30,4,'Extra: ',0,0,'L',0);
//
$pdf->SetFont('Arial','',14);
$pdf->Cell(30,4,'$ '.number_format($ResPRes["Extra"], 2),0,0,'R',0);
$pdf->Cell(60,4,$ResPRes["DetExtra"],0,0,'L',0);
//total
$pdf->SetY(74);
$pdf->SetX(8);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(30,4,'Total: ',0,0,'L',0);
//
$pdf->SetFont('Arial','',14);
$pdf->Cell(30,4,'$ '.number_format($ResPRes["Monto"], 2),0,0,'R',0);
$pdf->Cell(60,4,num2letras($ResPRes["Monto"]).' pesos 00/100',0,0,'L',0);
//
$pdf->SetY(80);
$pdf->SetX(8);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(33,4,'Cobrado por: ',0,0,'L',0);
//
if($ResPRes["Usuario"]==0){$cobradoby='---';}
else
{
    $ResUsuario=mysqli_fetch_array(mysqli_query($conn, "SELECT Nombre FROM usuarios WHERE Id='".$ResPRes["Usuario"]."' LIMIT 1"));
    $cobradoby=$ResUsuario["Nombre"];
}
$pdf->SetFont('Arial','',14);
$pdf->Cell(30,4,$cobradoby,0,0,'L',0);

//footer
$pdf->SetY(85);
$pdf->SetX(8);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(200,4,'VOLUNTARIAS VICENTINAS ALBERGUE "LA ESPERANZA" I. A. P.',0,0,'C',0);
//
$pdf->SetY(89);
$pdf->SetX(8);
$pdf->SetFont('Arial','',10);
$pdf->Cell(100,4,'Xontepec No. 105',0,0,'L',0);
$pdf->Cell(100,4,'Tel: 55 - 56 - 06 - 89 - 16',0,0,'R',0);
//
//corte de linea
$pdf->Line(0, 100, 8, 100);
$pdf->Line(12, 100, 20, 100);
$pdf->Line(24, 100, 32, 100);
$pdf->Line(36, 100, 44, 100);
$pdf->Line(48, 100, 56, 100);
$pdf->Line(60, 100, 68, 100);
$pdf->Line(72, 100, 80, 100);
$pdf->Line(84, 100, 92, 100);
$pdf->Line(96, 100, 104, 100);
$pdf->Line(108, 100, 116, 100);
$pdf->Line(120, 100, 128, 100);
$pdf->Line(132, 100, 140, 100);
$pdf->Line(144, 100, 152, 100);
$pdf->Line(156, 100, 164, 100);
$pdf->Line(168, 100, 176, 100);
$pdf->Line(180, 100, 188, 100);
$pdf->Line(192, 100, 200, 100);
$pdf->Line(204, 100, 212, 100);
$pdf->Line(216, 100, 224, 100);
//
//marco de lavanderia
$pdf->SetFillColor(000,000,000);
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(108);
$pdf->SetX(4);
$pdf->Cell(208,165,'',1,0,'L', FALSE, 1);
//
$pdf->SetY(112);
$pdf->SetX(8);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(200,4,'VOLUNTARIAS VICENTINAS ALBERGUE "LA ESPERANZA" I. A. P.',0,0,'C',0);
$pdf->SetY(118);
$pdf->Cell(200,4,utf8_decode('Entrega y devolución de ropa de cama'),0,0,'C',0);
//titulo
//fecha de entrega 
$pdf->SetFillColor(000,000,000);
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(128);
$pdf->SetX(8);
$pdf->Cell(40,20,'',1,0,'L', FALSE, 1);
$pdf->SetY(123);
$pdf->SetX(10);
$pdf->Cell(40,20,'Fecha de Entrega',0,0,'L', FALSE, 0);
$pdf->SetFont('Arial','',9);
$pdf->SetY(127);
$pdf->SetX(10);
$ResFEntrega=mysqli_fetch_array(mysqli_query($conn, "SELECT Fecha FROM lavanderia_inventario WHERE IdReservacion='".$ResRes["Id"]."' AND ES='S' ORDER BY Id ASC LIMIT 1"));
$pdf->Cell(40,20,fecha($ResFEntrega["Fecha"]),0,0,'L', FALSE, 0);
//
$pdf->SetFont('Arial','B',9);
$pdf->SetY(123);
$pdf->SetX(60);
$pdf->Cell(20,20,'Paciente: ',0,0,'L', FALSE, 0);
$pdf->SetFont('Arial','',9);
$pdf->Cell(40,20,$ResPac["Id"].' - '.$ResPac["Nombre"].' '.$ResPac["Apellidos"],0,0,'L', FALSE, 0);
$pdf->SetY(130);
$pdf->SetX(60);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(25,20,utf8_decode('Reservación: '),0,0,'L', FALSE, 0);
$pdf->SetFont('Arial','',9);
$pdf->Cell(25,20,$ResRes["Id"],0,0,'L', FALSE, 0);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(25,20,utf8_decode('Habitaciones: '),0,0,'L', FALSE, 0);
$pdf->SetFont('Arial','',9);
$ResHabitaciones=mysqli_query($conn, "SELECT H.Habitacion FROM (SELECT r.Cama, h.Habitacion FROM reservaciones AS r 
                                                                INNER JOIN camas AS c ON c.Id=r.Cama
                                                                INNER JOIN habitaciones AS h ON h.Id=c.Habitacion
                                                                WHERE r.IdReservacion = '".$ResRes["Id"]."' GROUP BY r.Cama) AS H 
                                                        GROUP BY H.Habitacion");
$NH=mysqli_num_rows($ResHabitaciones); $J=1;
while($RResHab=mysqli_fetch_array($ResHabitaciones))
{
    $hab.=$RResHab["Habitacion"];
    if($J<$NH){$hab.=', ';}
    $J++;
}
$pdf->Cell(40,20,utf8_decode($hab),0,0,'L', FALSE, 0);

//Quien Entrega
$pdf->SetFillColor(000,000,000);
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(150);
$pdf->SetX(8);
$pdf->Cell(40,15,'',1,0,'L', FALSE, 1);
$pdf->SetY(144);
$pdf->SetX(10);
$pdf->Cell(40,20,'Quien Entrega',0,0,'L', FALSE, 0);
$pdf->SetFont('Arial','',9);
$pdf->SetY(148);
$pdf->SetX(10);
$ResUsuario=mysqli_fetch_array(mysqli_query($conn, "SELECT u.Nombre AS Nombre FROM usuarios AS u
                                                    INNER JOIN lavanderia_observaciones AS lo ON lo.Usuario=u.Id
                                                    WHERE lo.IdReservacion='".$ResRes["Id"]."'"));
$pdf->Cell(40,20,$ResUsuario["Nombre"],0,0,'L',FALSE, 0);
///
///Fecha de devolución
$pdf->SetFillColor(000,000,000);
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(150);
$pdf->SetX(82);
$pdf->Cell(40,15,'',1,0,'L', FALSE, 1);
$pdf->SetY(144);
$pdf->SetX(84);
$pdf->Cell(40,20,utf8_decode('Fecha de Devolución'),0,0,'L', FALSE, 0);
//
//costo si no devuelve
$pdf->SetFillColor(000,000,000);
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(150);
$pdf->SetX(166);
$pdf->Cell(40,15,'',1,0,'L', FALSE, 1);
$pdf->SetY(150);
$pdf->SetX(166);
$pdf->Cell(40,15,utf8_decode('Costo si no se devuelve'),0,0,'C', FALSE, 0);
//
//SABANA PLANA AZUL
$pdf->SetFillColor(000,000,000);
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(167);
$pdf->SetX(8);
$pdf->Cell(40,4,utf8_decode('SABANA PLANA AZUL'),0,0,'L', FALSE, 1);
$pdf->SetX(55);
$ResL1=mysqli_fetch_array(mysqli_query($conn, "SELECT Cantidad FROM lavanderia_inventario WHERE IdReservacion='".$ResRes["Id"]."' AND IdPrenda='1'"));
$pdf->Cell(5,5,$ResL1["Cantidad"],1,0,'C', FALSE, 1);
$pdf->SetX(82);
$pdf->Cell(40,4,utf8_decode('SABANA PLANA AZUL'),0,0,'L', FALSE, 1);
$pdf->SetX(130);
$pdf->Cell(5,5,'',1,0,'L', FALSE, 1);
$pdf->SetX(166);
$pdf->Cell(5,5,'$',0,0,'L', FALSE, 1);
$pdf->SetX(170);
$pdf->Cell(37,5,'250.00',0,0,'R', FALSE, 1);
//Sabana de cajon
$pdf->SetFillColor(000,000,000);
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(174);
$pdf->SetX(8);
$ResL2=mysqli_fetch_array(mysqli_query($conn, "SELECT Cantidad FROM lavanderia_inventario WHERE IdReservacion='".$ResRes["Id"]."' AND IdPrenda='2'"));
$pdf->Cell(40,4,utf8_decode('SABANA PLANA ROJA'),0,0,'L', FALSE, 1);
$pdf->SetX(55);
$pdf->Cell(5,5,$ResL2["Cantidad"],1,0,'L', FALSE, 1);
$pdf->SetX(82);
$pdf->Cell(40,4,utf8_decode('SABANA PLANA ROJA'),0,0,'L', FALSE, 1);
$pdf->SetX(130);
$pdf->Cell(5,5,'',1,0,'L', FALSE, 1);
$pdf->SetX(166);
$pdf->Cell(5,5,'$',0,0,'L', FALSE, 1);
$pdf->SetX(170);
$pdf->Cell(37,5,'120.00',0,0,'R', FALSE, 1);
//Sabana plana
$pdf->SetFillColor(000,000,000);
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(181);
$pdf->SetX(8);
$pdf->Cell(40,4,utf8_decode('SABANA RESORTE AZUL'),0,0,'L', FALSE, 1);
$pdf->SetX(55);
$ResL3=mysqli_fetch_array(mysqli_query($conn, "SELECT Cantidad FROM lavanderia_inventario WHERE IdReservacion='".$ResRes["Id"]."' AND IdPrenda='3'"));
$pdf->Cell(5,5,$ResL3["Cantidad"],1,0,'L', FALSE, 1);
$pdf->SetX(82);
$pdf->Cell(40,4,utf8_decode('SABANA RESORTE AZUL'),0,0,'L', FALSE, 1);
$pdf->SetX(130);
$pdf->Cell(5,5,'',1,0,'L', FALSE, 1);
$pdf->SetX(166);
$pdf->Cell(5,5,'$',0,0,'L', FALSE, 1);
$pdf->SetX(170);
$pdf->Cell(37,5,'120.00',0,0,'R', FALSE, 1);
//Funda para almohada
$pdf->SetFillColor(000,000,000);
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(188);
$pdf->SetX(8);
$pdf->Cell(40,4,utf8_decode('SABANA DE RESORTE ROJA'),0,0,'L', FALSE, 1);
$pdf->SetX(55);
$ResL4=mysqli_fetch_array(mysqli_query($conn, "SELECT Cantidad FROM lavanderia_inventario WHERE IdReservacion='".$ResRes["Id"]."' AND IdPrenda='4'"));
$pdf->Cell(5,5,$ResL4["Cantidad"],1,0,'L', FALSE, 1);
$pdf->SetX(82);
$pdf->Cell(40,4,utf8_decode('SABANA DE RESORTE ROJA'),0,0,'L', FALSE, 1);
$pdf->SetX(130);
$pdf->Cell(5,5,'',1,0,'L', FALSE, 1);
$pdf->SetX(166);
$pdf->Cell(5,5,'$',0,0,'L', FALSE, 1);
$pdf->SetX(170);
$pdf->Cell(37,5,'50.00',0,0,'R', FALSE, 1);
//Funda para almohada
$pdf->SetFillColor(000,000,000);
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(195);
$pdf->SetX(8);
$pdf->Cell(40,4,utf8_decode('FUNDA DE ALMOHADA'),0,0,'L', FALSE, 1);
$pdf->SetX(55);
$ResL5=mysqli_fetch_array(mysqli_query($conn, "SELECT Cantidad FROM lavanderia_inventario WHERE IdReservacion='".$ResRes["Id"]."' AND IdPrenda='5'"));
$pdf->Cell(5,5,$ResL5["Cantidad"],1,0,'L', FALSE, 1);
$pdf->SetX(82);
$pdf->Cell(40,4,utf8_decode('FUNDA DE ALMOHADA'),0,0,'L', FALSE, 1);
$pdf->SetX(130);
$pdf->Cell(5,5,'',1,0,'L', FALSE, 1);
$pdf->SetX(166);
$pdf->Cell(5,5,'$',0,0,'L', FALSE, 1);
$pdf->SetX(170);
$pdf->Cell(37,5,'120.00',0,0,'R', FALSE, 1);
//cobertor
$pdf->SetFillColor(000,000,000);
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(202);
$pdf->SetX(8);
$pdf->Cell(40,4,utf8_decode('CUBRE COLCHON'),0,0,'L', FALSE, 1);
$pdf->SetX(55);
$ResL6=mysqli_fetch_array(mysqli_query($conn, "SELECT Cantidad FROM lavanderia_inventario WHERE IdReservacion='".$ResRes["Id"]."' AND IdPrenda='6'"));
$pdf->Cell(5,5,$ResL6["Cantidad"],1,0,'L', FALSE, 1);
$pdf->SetX(82);
$pdf->Cell(40,4,utf8_decode('CUBRE COLCHON'),0,0,'L', FALSE, 1);
$pdf->SetX(130);
$pdf->Cell(5,5,'',1,0,'L', FALSE, 1);
$pdf->SetX(166);
$pdf->Cell(5,5,'$',0,0,'L', FALSE, 1);
$pdf->SetX(170);
$pdf->Cell(37,5,'250.00',0,0,'R', FALSE, 1);
//colcha
$pdf->SetFillColor(000,000,000);
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(209);
$pdf->SetX(8);
$pdf->Cell(40,4,utf8_decode('COBIJAS'),0,0,'L', FALSE, 1);
$pdf->SetX(55);
$ResL7=mysqli_fetch_array(mysqli_query($conn, "SELECT Cantidad FROM lavanderia_inventario WHERE IdReservacion='".$ResRes["Id"]."' AND IdPrenda='7'"));
$pdf->Cell(5,5,$ResL7["Cantidad"],1,0,'L', FALSE, 1);
$pdf->SetX(82);
$pdf->Cell(40,4,utf8_decode('COBIJAS'),0,0,'L', FALSE, 1);
$pdf->SetX(130);
$pdf->Cell(5,5,'',1,0,'L', FALSE, 1);
$pdf->SetX(166);
$pdf->Cell(5,5,'$',0,0,'L', FALSE, 1);
$pdf->SetX(170);
$pdf->Cell(37,5,'350.00',0,0,'R', FALSE, 1);
//toalla de baño
$pdf->SetFillColor(000,000,000);
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(216);
$pdf->SetX(8);
$pdf->Cell(40,4,utf8_decode('COLCHAS'),0,0,'L', FALSE, 1);
$pdf->SetX(55);
$ResL8=mysqli_fetch_array(mysqli_query($conn, "SELECT Cantidad FROM lavanderia_inventario WHERE IdReservacion='".$ResRes["Id"]."' AND IdPrenda='8'"));
$pdf->Cell(5,5,$ResL8["Cantidad"],1,0,'L', FALSE, 1);
$pdf->SetX(82);
$pdf->Cell(40,4,utf8_decode('COLCHAS'),0,0,'L', FALSE, 1);
$pdf->SetX(130);
$pdf->Cell(5,5,'',1,0,'L', FALSE, 1);
$pdf->SetX(166);
$pdf->Cell(5,5,'$',0,0,'L', FALSE, 1);
$pdf->SetX(170);
$pdf->Cell(37,5,'100.00',0,0,'R', FALSE, 1);
//cobija mediaga
$pdf->SetFillColor(000,000,000);
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(223);
$pdf->SetX(8);
$pdf->Cell(40,4,utf8_decode('ALMOHADAS'),0,0,'L', FALSE, 1);
$pdf->SetX(55);
$ResL8=mysqli_fetch_array(mysqli_query($conn, "SELECT Cantidad FROM lavanderia_inventario WHERE IdReservacion='".$ResRes["Id"]."' AND IdPrenda='9'"));
$pdf->Cell(5,5,$ResL8["Cantidad"],1,0,'L', FALSE, 1);
$pdf->SetX(82);
$pdf->Cell(40,4,utf8_decode('ALMOHADAS'),0,0,'L', FALSE, 1);
$pdf->SetX(130);
$pdf->Cell(5,5,'',1,0,'L', FALSE, 1);
$pdf->SetX(166);
$pdf->Cell(5,5,'$',0,0,'L', FALSE, 1);
$pdf->SetX(170);
$pdf->Cell(37,5,'100.00',0,0,'R', FALSE, 1);
//jabón de tocador
$pdf->SetFillColor(000,000,000);
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(230);
$pdf->SetX(8);
$pdf->Cell(40,4,utf8_decode('TOALLAS'),0,0,'L', FALSE, 1);
$pdf->SetX(55);
$ResL8=mysqli_fetch_array(mysqli_query($conn, "SELECT Cantidad FROM lavanderia_inventario WHERE IdReservacion='".$ResRes["Id"]."' AND IdPrenda='10'"));
$pdf->Cell(5,5,$ResL8["Cantidad"],1,0,'L', FALSE, 1);
$pdf->SetX(82);
$pdf->Cell(40,4,utf8_decode('TOALLAS'),0,0,'L', FALSE, 1);
$pdf->SetX(130);
$pdf->Cell(5,5,'',1,0,'L', FALSE, 1);
$pdf->SetX(166);
$pdf->Cell(5,5,'$',0,0,'L', FALSE, 1);
$pdf->SetX(170);
$pdf->Cell(37,5,'100.00',0,0,'R', FALSE, 1);
//jabón de tocador
$pdf->SetFillColor(000,000,000);
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(237);
$pdf->SetX(8);
$pdf->Cell(40,4,utf8_decode('JABONES'),0,0,'L', FALSE, 1);
$pdf->SetX(55);
$ResL8=mysqli_fetch_array(mysqli_query($conn, "SELECT Cantidad FROM lavanderia_inventario WHERE IdReservacion='".$ResRes["Id"]."' AND IdPrenda='11'"));
$pdf->Cell(5,5,$ResL8["Cantidad"],1,0,'L', FALSE, 1);
$pdf->SetX(82);
$pdf->Cell(40,4,utf8_decode('JABONES'),0,0,'L', FALSE, 1);
$pdf->SetX(130);
$pdf->Cell(5,5,'',1,0,'L', FALSE, 1);
$pdf->SetX(166);
$pdf->Cell(5,5,'$',0,0,'L', FALSE, 1);
$pdf->SetX(170);
$pdf->Cell(37,5,'100.00',0,0,'R', FALSE, 1);
//jabón de tocador
$pdf->SetFillColor(000,000,000);
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(244);
$pdf->SetX(8);
$pdf->Cell(40,4,utf8_decode('COBIJAS MEDIANAS'),0,0,'L', FALSE, 1);
$pdf->SetX(55);
$ResL8=mysqli_fetch_array(mysqli_query($conn, "SELECT Cantidad FROM lavanderia_inventario WHERE IdReservacion='".$ResRes["Id"]."' AND IdPrenda='12'"));
$pdf->Cell(5,5,$ResL8["Cantidad"],1,0,'L', FALSE, 1);
$pdf->SetX(82);
$pdf->Cell(40,4,utf8_decode('COBIJAS MEDIANAS'),0,0,'L', FALSE, 1);
$pdf->SetX(130);
$pdf->Cell(5,5,'',1,0,'L', FALSE, 1);
$pdf->SetX(166);
$pdf->Cell(5,5,'$',0,0,'L', FALSE, 1);
$pdf->SetX(170);
$pdf->Cell(37,5,'100.00',0,0,'R', FALSE, 1);

//
//Recibio
$pdf->SetFillColor(000,000,000);
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(250);
$pdf->SetX(8);
$pdf->Cell(40,16,'',1,0,'L', FALSE, 1);
$pdf->SetY(243);
$pdf->SetX(10);
$pdf->Cell(40,20,'Recibio: ',0,0,'L', FALSE, 0);
//Recibio
$pdf->SetFillColor(000,000,000);
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(250);
$pdf->SetX(82);
$pdf->Cell(40,16,'',1,0,'L', FALSE, 1);
$pdf->SetY(243);
$pdf->SetX(84);
$pdf->Cell(40,20,utf8_decode('Recibio: '),0,0,'L', FALSE, 0);
//total a pagar
$pdf->SetFillColor(000,000,000);
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(250);
$pdf->SetX(166);
$pdf->Cell(40,16,'',1,0,'L', FALSE, 1);
$pdf->SetY(243);
$pdf->SetX(166);
$pdf->Cell(40,20,utf8_decode('Total a pagar'),0,0,'L', FALSE, 0);
//nota
$pdf->SetFillColor(000,000,000);
$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(266);
$pdf->SetX(8);
$pdf->Cell(200,8,utf8_decode('Nota: Cuide este recibo, si lo pierde tendrá que pagar el costo total de la ropa de cama $1,360.00'),0,0,'L', FALSE, 0);

//fecha menos 30 dias
$fecha30=date("Y-m-d",strtotime($ResRes["Fecha"]."- 30 days"));

$ResResP=mysqli_query($conn, "SELECT Id FROM reservacion WHERE IdPaciente='".$ResPac["Id"]."' AND Fecha >='".$fecha30."' AND Fecha<='".date("Y-m-d")."'");
$i=1;
while($RResResP=mysqli_fetch_array($ResResP))
{
    $ResRespuestasA=mysqli_query($conn, "SELECT Id FROM respuestas_encuesta WHERE IdReservacion='".$RResResP["Id"]."'");
    if(mysqli_num_rows($ResRespuestasA)>0)
    {$i=0; break;}
}

$ResRespuestas=mysqli_query($conn, "SELECT Id FROM respuestas_encuesta WHERE IdReservacion='".$ResPRes["IdReservacion"]."'");

if(mysqli_num_rows($ResRespuestas)==0 && $i==1)
{

    //Agregamos la segunda pagina
    $pdf->AddPage();

    //marco del recibo
    $pdf->SetFillColor(000,000,000);
    $pdf->SetFont('Arial','B',9);
    $pdf->SetTextColor(000,000,000);
    $pdf->SetY(4);
    $pdf->SetX(4);
    $pdf->Cell(208,270,'',1,0,'L', FALSE, 1);
    //logo 
    $pdf->Image('../images/logo.png',140,8,70);
    //Titulo
    $pdf->SetFillColor(255,255,255);
    $pdf->SetFont('Arial','B',14);
    $pdf->SetTextColor(000,000,000);
    $pdf->SetY(8);
    $pdf->SetX(8);
    $pdf->Cell(60,4,utf8_decode('Encuesta de Satisfacción'),0,0,'L',0);
    //texto
    $pdf->SetFont('Arial','',14);
    $pdf->SetTextColor(000,000,000);
    $pdf->SetY(14);
    $pdf->SetX(8);
    $pdf->MultiCell(120,6,utf8_decode('Tu opinión es muy importante para nosotros. Nos permite atenderte mejor y brindarte el apoyo que necesitas durante tu estancia. Por favor, entrega este cuestionario a tu salida.'),0,'J',0);
    //reservacion
    $pdf->SetFont('Arial','B',14);
    $pdf->SetTextColor(000,000,000);
    $pdf->SetY(44);
    $pdf->SetX(8);
    $pdf->Cell(33,4,utf8_decode('Reservacion: '),0,0,'L',0);
    $pdf->SetFont('Arial','',14);
    $pdf->Cell(20,4,$ResRes["Id"],0,0,'L',0);
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(50,4,utf8_decode('Fecha Reservación: '),0,0,'L',0);
    $pdf->SetFont('Arial','',14);
    $pdf->Cell(20,4,fecha($ResRes["Fecha"]),0,0,'L',0);
    //paciente
    $pdf->SetFont('Arial','B',14);
    $pdf->SetTextColor(000,000,000);
    $pdf->SetY(50);
    $pdf->SetX(8);
    $pdf->Cell(25,4,utf8_decode('Paciente: '),0,0,'L',0);
    $pdf->SetFont('Arial','',14);
    $pdf->Cell(60,4,$ResPac["Id"].' - '.utf8_decode($ResPac["Nombre"].' '.$ResPac["Apellidos"]),0,0,'L',0);
    //preguntas
    $Y=58; $A=1;
    $ResEncuesta=mysqli_query($conn, "SELECT * FROM preguntas_encuesta ORDER BY Id ASC");
    while($RResE=mysqli_fetch_array($ResEncuesta))
    {
        //pregunta
        $pdf->SetFont('Arial','B',14);
        $pdf->SetTextColor(000,000,000);
        $pdf->SetY($Y);
        $pdf->SetX(8);
        $pdf->Cell(25,4,$A.'.- '.utf8_decode($RResE["Pregunta"]),0,0,'L',0);

        $ResRespuestas=mysqli_query($conn, "SELECT * FROM preguntas_encuesta_respuestas WHERE IdPregunta='".$RResE["Id"]."' ORDER BY Id ASC");
        if($RResE["Tipo"]==0)
        {
            $Y=$Y+12;
        }
        elseif($RResE["Tipo"]==1)
        {
            $Y=$Y+6;
            $pdf->SetFont('Arial','',14);
            $pdf->SetTextColor(000,000,000);
            $pdf->SetY($Y);
            $pdf->SetX(8);


            while($RResR=mysqli_fetch_array($ResRespuestas))
            {
                $pdf->Cell(10,8,utf8_decode($RResR["Respuesta"]),1,0,'C',0);
            }

            $Y=$Y+4;
        }
        elseif($RResE["Tipo"]==2)
        {
            while($RResR=mysqli_fetch_array($ResRespuestas))
            {
                $Y=$Y+6;
                $pdf->SetFont('Arial','',14);
                $pdf->SetTextColor(000,000,000);
                $pdf->SetY($Y);
                $pdf->SetX(12);
                $pdf->Cell(10,8,'(  ) '.utf8_decode($RResR["Respuesta"]),0,0,'L',0);
            }
            $Y=$Y+4;
        }

        $Y=$Y+6;
        $A++;
    }
}



//Envio Archivo
$pdf->Output();