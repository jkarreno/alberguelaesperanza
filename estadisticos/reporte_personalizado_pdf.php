<?php
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

require('../libs/fpdf/fpdf.php');

require_once ('../jpgraph/jpgraph.php');
require_once ('../jpgraph/jpgraph_bar.php');

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
$pdf->Cell(178,4,'Reporte Datos del Periodo',0,0,'C',0);
$pdf->SetY(18);
$pdf->SetX(34);
$pdf->Cell(178,4,'Del: '.fecha($_SESSION["rep_per"][0][0]).' Al: '.fecha($_SESSION["rep_per"][0][1]),0,0,'C',0);
//reservaciones
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(26);
$pdf->SetX(4);
$pdf->Cell(208,6,utf8_decode('RESERVACIONES'),1,0,'L',0);
//
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(32);
$pdf->SetX(4);
$pdf->Cell(45,6,$_SESSION["rep_per"][1][0],1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(32);
$pdf->SetX(4);
$pdf->Cell(40,6,$_SESSION["rep_per"][1][1],0,0,'R',0);
//grafica
    // Creamos el grafico
    $datos1=$_SESSION["rep_per"][1][1];
    $labels=$_SESSION["rep_per"][1][0];  

    $grafico = new Graph(800, 600, 'auto');
    $grafico->SetScale("textlin");
    $grafico->title->Set("Reservaciones");
    $grafico->xaxis->SetTickLabels($labels);    

    // Create the bar plots
    $b1plot = new BarPlot($datos1);
    // 
    $grafico->Add($b1plot);    

    $b1plot->SetColor("white");
    $b1plot->SetFillColor("#26b719");
    $b1plot->SetWidth(200);         

    $nombreImagen = 'graficas/img/imagen1.png';    

    //guardamos la grafica
    $grafico->Stroke($nombreImagen);
$pdf->Image($nombreImagen,50,34,160);
//Servicios otorgados
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(142);
$pdf->SetX(4);
$pdf->Cell(208,6,utf8_decode('SERVICIOS OTORGADOS: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(142);
$pdf->SetX(50);
$pdf->Cell(20,6,number_format(($_SESSION["rep_per"][2][0]+$_SESSION["rep_per"][2][1]+$_SESSION["rep_per"][2][2])),0,0,'L',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(148);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Hospedajes: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(148);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($_SESSION["rep_per"][2][0]),0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(154);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Alimentos: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(154);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($_SESSION["rep_per"][2][1]),0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(160);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Lavanderia: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(160);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($_SESSION["rep_per"][2][2]),0,0,'R',0);
//grafica
    // Creamos el grafico
    $datos4=array($_SESSION["rep_per"][2][0], 0, 0);
    $datos5=array(0, $_SESSION["rep_per"][2][1], 0);
    $datos6=array(0, 0, $_SESSION["rep_per"][2][2]);
    $labels2=array("Hospedajes","Alimentos","Lavanderia");  

    $grafico2 = new Graph(800, 600, 'auto');
    $grafico2->SetScale("textlin");
    $grafico2->title->Set("Servicios");
    $grafico2->xaxis->SetTickLabels($labels2);    

    // Create the bar plots
    $b4plot = new BarPlot($datos4);
    $b5plot = new BarPlot($datos5);
    $b6plot = new BarPlot($datos6);
    // 
    $grafico2->Add(array($b4plot, $b5plot, $b6plot));    

    $b4plot->SetColor("white");
    $b4plot->SetFillColor("#26b719");
    $b4plot->SetWidth(200);     

    $b5plot->SetColor("white");
    $b5plot->SetFillColor("#0864bf");
    $b5plot->SetWidth(200);     

    $b6plot->SetColor("white");
    $b6plot->SetFillColor("#ff0000");
    $b6plot->SetWidth(200);     

    $nombreImagen2 = 'graficas/img/imagen2.png';    

    //guardamos la grafica
    $grafico2->Stroke($nombreImagen2);
$pdf->Image($nombreImagen2,50,150,160);
//Agregamos la segunda pagina
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
$pdf->Cell(178,4,'Reporte Estadistico',0,0,'C',0);
$pdf->SetY(18);
$pdf->SetX(34);
$pdf->Cell(178,4,mes($_GET["mes"]).' - '.$_GET["anno"],0,0,'C',0);
//personas atendidas
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(26);
$pdf->SetX(4);
$pdf->Cell(208,6,utf8_decode('TOTAL ALBERGADOS: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(26);
$pdf->SetX(50);
$pdf->Cell(20,6,number_format($_SESSION["rep_per"][3][0]),0,0,'L',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(32);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Pacientes: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(32);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($_SESSION["rep_per"][3][1]),0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(38);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Acompañantes: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(38);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($_SESSION["rep_per"][3][2]),0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(44);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Hombres: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(44);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($_SESSION["rep_per"][3][3]),0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(50);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Mujeres: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(50);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($_SESSION["rep_per"][3][4]),0,0,'R',0);
//grafica
    // Creamos el grafico
    $datos7=array($_SESSION["rep_per"][3][1], 0, 0, 0);
    $datos8=array(0, $_SESSION["rep_per"][3][2], 0, 0);
    $datos9=array(0, 0, $_SESSION["rep_per"][3][3], 0);
    $datos10=array(0, 0, 0, $_SESSION["rep_per"][3][4]);
    $labels3=array("Pacientes","Acompañantes","Hombres", "Mujeres");  

    $grafico3 = new Graph(800, 600, 'auto');
    $grafico3->SetScale("textlin");
    $grafico3->title->Set("Personas Atendidas");
    $grafico3->xaxis->SetTickLabels($labels3);    

    // Create the bar plots
    $b7plot = new BarPlot($datos7);
    $b8plot = new BarPlot($datos8);
    $b9plot = new BarPlot($datos9);
    $b10plot = new BarPlot($datos10);
    // 
    $grafico3->Add(array($b7plot, $b8plot, $b9plot, $b10plot));    

    $b7plot->SetColor("white");
    $b7plot->SetFillColor("#fa8f92");
    $b7plot->SetWidth(100);     

    $b8plot->SetColor("white");
    $b8plot->SetFillColor("#a29bee");
    $b8plot->SetWidth(100);     

    $b9plot->SetColor("white");
    $b9plot->SetFillColor("#5c96db");
    $b9plot->SetWidth(100);     

    $b10plot->SetColor("white");
    $b10plot->SetFillColor("#ed1e79");
    $b10plot->SetWidth(100);     

    $nombreImagen3 = 'graficas/img/imagen3.png';    

    //guardamos la grafica
    $grafico3->Stroke($nombreImagen3);
$pdf->Image($nombreImagen3,50,34,160);
//PAcientes
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(142);
$pdf->SetX(4);
$pdf->Cell(208,6,utf8_decode('PACIENTES: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(142);
$pdf->SetX(30);
$pdf->Cell(20,6,number_format($_SESSION["rep_per"][3][1]),0,0,'L',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(148);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Hombres: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(148);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($_SESSION["rep_per"][4][1]),0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(154);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Hombres Niños: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(154);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($_SESSION["rep_per"][4][2]),0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(160);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Hombres Adultos: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(160);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($_SESSION["rep_per"][4][3]),0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(166);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Mujeres: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(166);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($_SESSION["rep_per"][4][4]),0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(172);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Mujeres Niñas: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(172);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($_SESSION["rep_per"][4][5]),0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(178);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Mujeres Adultas: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(178);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($_SESSION["rep_per"][4][6]),0,0,'R',0);
//grafica
    // Creamos el grafico
    $datos11=array($_SESSION["rep_per"][4][1], 0, 0, 0, 0, 0);
    $datos12=array(0, $_SESSION["rep_per"][4][2], 0, 0, 0, 0);
    $datos13=array(0, 0, $_SESSION["rep_per"][4][3], 0, 0, 0);
    $datos14=array(0, 0, 0, $_SESSION["rep_per"][4][4], 0, 0);
    $datos15=array(0, 0, 0, 0, $_SESSION["rep_per"][4][5], 0);
    $datos16=array(0, 0, 0, 0, 0, $_SESSION["rep_per"][4][6]);
    $labels4=array("Hombres","Niños","Adultos", "Mujeres", "Niñas", "Adultas");  

    $grafico4 = new Graph(800, 600, 'auto');
    $grafico4->SetScale("textlin");
    $grafico4->title->Set("Pacientes");
    $grafico4->xaxis->SetTickLabels($labels4);    

    // Create the bar plots
    $b11plot = new BarPlot($datos11);
    $b12plot = new BarPlot($datos12);
    $b13plot = new BarPlot($datos13);
    $b14plot = new BarPlot($datos14);
    $b15plot = new BarPlot($datos15);
    $b16plot = new BarPlot($datos16);
    // 
    $grafico4->Add(array($b11plot, $b12plot, $b13plot, $b14plot, $b15plot, $b16plot));    

    $b11plot->SetColor("white");
    $b11plot->SetFillColor("#0000c2");
    $b11plot->SetWidth(100);     

    $b12plot->SetColor("white");
    $b12plot->SetFillColor("#5cb5e7");
    $b12plot->SetWidth(100);     

    $b13plot->SetColor("white");
    $b13plot->SetFillColor("#2e7ada");
    $b13plot->SetWidth(100);     

    $b14plot->SetColor("white");
    $b14plot->SetFillColor("#e01690");
    $b14plot->SetWidth(100);     

    $b15plot->SetColor("white");
    $b15plot->SetFillColor("#e081ba");
    $b15plot->SetWidth(100);     

    $b16plot->SetColor("white");
    $b16plot->SetFillColor("#df46a2");
    $b16plot->SetWidth(100);     

    $nombreImagen4 = 'graficas/img/imagen4.png';    

    //guardamos la grafica
    $grafico4->Stroke($nombreImagen4);
$pdf->Image($nombreImagen4,50,150,160);
//Agregamos la tercera pagina
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
$pdf->Cell(178,4,'Reporte Estadistico',0,0,'C',0);
$pdf->SetY(18);
$pdf->SetX(34);
$pdf->Cell(178,4,mes($_GET["mes"]).' - '.$_GET["anno"],0,0,'C',0);
//edades pacientes
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(26);
$pdf->SetX(4);
$pdf->Cell(208,6,utf8_decode('Edades Pacientes'),1,0,'L',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(32);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode($_SESSION["rep_per"][5][0].': '),1,0,'L',0);
$a=$_SESSION["rep_per"][5][0];
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(32);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($_SESSION["rep_per"][5][1]),0,0,'R',0);
//grafico
    //creamos el grafico
    $datosep1=$_SESSION["rep_per"][5][1];
    
    $labels9=$_SESSION["rep_per"][5][0]; 

    $grafico9 = new Graph(800, 600, 'auto');
    $grafico9->SetScale("textlin");
    $grafico9->title->Set("Edades Pacientes");
    $grafico9->xaxis->SetTickLabels($_SESSION["rep_per"][5][0]); 

    // Create the bar plots
    $bep1plot = new BarPlot($datosep1);
    // 
    $grafico9->Add($bep1plot);

    $bep1plot->SetColor("white");
    $bep1plot->SetFillColor("#685de8");
    $bep1plot->SetWidth(100);     

    $nombreImagen9 = 'graficas/img/imagen9.png'; 

    //guardamos la grafica
    $grafico9->Stroke($nombreImagen9);
$pdf->Image($nombreImagen9,50,34,160);
//acompañantes
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(142);
$pdf->SetX(4);
$pdf->Cell(208,6,utf8_decode('ACOMPAÑANTES: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(142);
$pdf->SetX(50);
$pdf->Cell(20,6,number_format($_SESSION["rep_per"][6][0]),0,0,'L',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(148);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Hombres: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(148);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($_SESSION["rep_per"][6][1]),0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(154);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Niños: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(154);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($_SESSION["rep_per"][6][2]),0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(160);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Adultos: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(160);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($_SESSION["rep_per"][6][3]),0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(166);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Mujeres: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(166);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($_SESSION["rep_per"][6][4]),0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(172);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Niñas: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(172);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($_SESSION["rep_per"][6][5]),0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(178);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Adultas: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(178);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($_SESSION["rep_per"][6][6]),0,0,'R',0);
//grafica
    // Creamos el grafico
    $datos17=array($_SESSION["rep_per"][6][1], 0, 0, 0, 0, 0);
    $datos18=array(0, $_SESSION["rep_per"][6][2], 0, 0, 0, 0);
    $datos19=array(0, 0, $_SESSION["rep_per"][6][3], 0, 0, 0);
    $datos20=array(0, 0, 0, $_SESSION["rep_per"][6][4], 0, 0);
    $datos21=array(0, 0, 0, 0, $_SESSION["rep_per"][6][5], 0);
    $datos22=array(0, 0, 0, 0, 0, $_SESSION["rep_per"][6][6]);
    $labels5=array("Hombres","Niños","Adultos", "Mujeres", "Niñas", "Adultas");  

    $grafico5 = new Graph(800, 600, 'auto');
    $grafico5->SetScale("textlin");
    $grafico5->title->Set("Acompañantes");
    $grafico5->xaxis->SetTickLabels($labels5);    

    // Create the bar plots
    $b17plot = new BarPlot($datos17);
    $b18plot = new BarPlot($datos18);
    $b19plot = new BarPlot($datos19);
    $b20plot = new BarPlot($datos20);
    $b21plot = new BarPlot($datos21);
    $b22plot = new BarPlot($datos22);
    // 
    $grafico5->Add(array($b17plot, $b18plot, $b19plot, $b20plot, $b21plot, $b22plot));    

    $b17plot->SetColor("white");
    $b17plot->SetFillColor("#0000c2");
    $b17plot->SetWidth(100);     

    $b18plot->SetColor("white");
    $b18plot->SetFillColor("#5cb5e7");
    $b18plot->SetWidth(100);     

    $b19plot->SetColor("white");
    $b19plot->SetFillColor("#2e7ada");
    $b19plot->SetWidth(100);     

    $b20plot->SetColor("white");
    $b20plot->SetFillColor("#e01690");
    $b20plot->SetWidth(100);     

    $b21plot->SetColor("white");
    $b21plot->SetFillColor("#e081ba");
    $b21plot->SetWidth(100);     

    $b22plot->SetColor("white");
    $b22plot->SetFillColor("#df46a2");
    $b22plot->SetWidth(100);     

    $nombreImagen5 = 'graficas/img/imagen5.png';    

    //guardamos la grafica
    $grafico5->Stroke($nombreImagen5);
$pdf->Image($nombreImagen5,50,150,160);

//
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
$pdf->Cell(178,4,'Reporte Estadistico',0,0,'C',0);
$pdf->SetY(18);
$pdf->SetX(34);
$pdf->Cell(178,4,mes($_GET["mes"]).' - '.$_GET["anno"],0,0,'C',0);
//acompañantes
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(26);
$pdf->SetX(4);
$pdf->Cell(208,6,utf8_decode('Edades Acompañantes'),1,0,'L',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(32);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode($_SESSION["rep_per"][7][0].': '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(32);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($_SESSION["rep_per"][7][1]),0,0,'R',0);
//grafico
    //creamos el grafico
    $datosea1=$_SESSION["rep_per"][7][1];

    $labels10=$_SESSION["rep_per"][7][0]; 

    $grafico10 = new Graph(800, 600, 'auto');
    $grafico10->SetScale("textlin");
    $grafico10->title->Set("Edades Acompañantes");
    $grafico10->xaxis->SetTickLabels($_SESSION["rep_per"][7][0]); 

    // Create the bar plots
    $bea1plot = new BarPlot($datosea1);
    // 
    $grafico10->Add($bea1plot);

    $bea1plot->SetColor("white");
    $bea1plot->SetFillColor("#685de8");
    $bea1plot->SetWidth(100);     

    $nombreImagen10 = 'graficas/img/imagen10.png'; 

    //guardamos la grafica
    $grafico10->Stroke($nombreImagen10);
$pdf->Image($nombreImagen10,50,34,160);

//Enfermedades
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(142);
$pdf->SetX(4);
$pdf->Cell(208,6,utf8_decode('ENFERMEDADES: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(142);
$pdf->SetX(50);
$pdf->Cell(20,6,number_format($_SESSION["rep_per"][8][0][0]),0,0,'L',0);
//
$Y=148;
$labels6=array(); $j=0; $datos_e=array(); $color_e=array();

$a=1;
for($i=1; $i<=$_SESSION["rep_per"][8][0][0];$i++)
{
    $pdf->SetFont('Arial','',10);
    $pdf->SetY($Y);
    $pdf->SetX(4);
    $pdf->Cell(12,6,$i,1,0,'L',0);
    //
    $pdf->SetFont('Arial','B',10);
    $pdf->SetY($Y);
    $pdf->SetX(16);
    $pdf->Cell(184,6,utf8_decode($_SESSION["rep_per"][8][$i][$a]),1,0,'L',0);
    array_push($labels6, $_SESSION["rep_per"][8][$i][$a]);
    $a++;
    //
    $pdf->SetFont('Arial','',10);
    $pdf->SetY($Y);
    $pdf->SetX(200);
    $pdf->Cell(12,6,number_format($_SESSION["rep_per"][8][$i][$a]),1,0,'R',0);
    array_push($datos_e, $_SESSION["rep_per"][8][$i][$a]);

    array_push($color_e, randomColor());

    $a=1;
    $Y=$Y+6;

    if($Y>=274)
    {
        //Agregamos otra pagina
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
        $pdf->Cell(178,4,'Reporte Estadistico',0,0,'C',0);
        $pdf->SetY(18);
        $pdf->SetX(34);
        $pdf->Cell(178,4,mes($_GET["mes"]).' - '.$_GET["anno"],0,0,'C',0);

        $Y=26;
    }
}
//grafica
    // Creamos el grafico
    $grafico6 = new Graph(800, 950, 'auto');
    $grafico6->SetScale("textlin");
    $grafico6->title->Set("Enfermedades");
    $grafico6->xaxis->SetTickLabels($labels6);   
    $grafico6->Set90AndMargin(250,40,40,40);
    $grafico6->img->SetAngle(90);
    // Create the bar plots
    $b23plot = new BarPlot($datos_e);
    //
    $grafico6->Add(array($b23plot));

    $b23plot->SetColor("white");
    $b23plot->SetFillColor($color_e);

    $nombreImagen6 = 'graficas/img/imagen6.png'; 

    //guardamos la grafica
    $grafico6->Stroke($nombreImagen6);

if($Y>=150)
{
    //Agregamos otra pagina
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
    $pdf->Cell(178,4,'Reporte Estadistico',0,0,'C',0);
    $pdf->SetY(18);
    $pdf->SetX(34);
    $pdf->Cell(178,4,mes($_GET["mes"]).' - '.$_GET["anno"],0,0,'C',0);

    $Y=34;

}
//$pdf->Image($nombreImagen6,4,($Y+2),208);

//Agregamos otra pagina
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
$pdf->Cell(178,4,'Reporte Estadistico',0,0,'C',0);
$pdf->SetY(18);
$pdf->SetX(34);
$pdf->Cell(178,4,mes($_GET["mes"]).' - '.$_GET["anno"],0,0,'C',0);


//Hospitales
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(26);
$pdf->SetX(4);
$pdf->Cell(208,6,utf8_decode('HOSPITALES: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(26);
$pdf->SetX(37);
$pdf->Cell(20,6,$_SESSION["rep_per"][9][0][0],0,0,'L',0);
//
$Y=32;
$labels7=array(); $j=0; $datos_h=array(); $color_h=array();

$a=1;
for($i=1; $i<=$_SESSION["rep_per"][9][0][0];$i++)
{
    $pdf->SetFont('Arial','',10);
    $pdf->SetY($Y);
    $pdf->SetX(4);
    $pdf->Cell(12,6,$i,1,0,'L',0);
    //
    $pdf->SetFont('Arial','B',10);
    $pdf->SetY($Y);
    $pdf->SetX(16);
    $pdf->Cell(184,6,utf8_decode($_SESSION["rep_per"][9][$i][$a]),1,0,'L',0);
    array_push($labels7, $_SESSION["rep_per"][9][$i][$a]);
    $a++;
    //
    $pdf->SetFont('Arial','',10);
    $pdf->SetY($Y);
    $pdf->SetX(200);
    $pdf->Cell(12,6,number_format($_SESSION["rep_per"][9][$i][$a]),1,0,'R',0);
    array_push($datos_h, $_SESSION["rep_per"][9][$i][$a]);

    array_push($color_h, randomColor());

    $a=1;
    $Y=$Y+6;

    if($Y>=274)
    {
        //Agregamos otra pagina
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
        $pdf->Cell(178,4,'Reporte Estadistico',0,0,'C',0);
        $pdf->SetY(18);
        $pdf->SetX(34);
        $pdf->Cell(178,4,mes($_GET["mes"]).' - '.$_GET["anno"],0,0,'C',0);

        $Y=26;
    }
}

//Agregamos otra pagina
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
$pdf->Cell(178,4,'Reporte Estadistico',0,0,'C',0);
$pdf->SetY(18);
$pdf->SetX(34);
$pdf->Cell(178,4,mes($_GET["mes"]).' - '.$_GET["anno"],0,0,'C',0);

$Y=34;

//grafica
    // Creamos el grafico
    $grafico7 = new Graph(800, 600, 'auto');
    $grafico7->SetScale("textlin");
    $grafico7->title->Set("Hospitales");
    $grafico7->xaxis->SetTickLabels($labels7);   
    $grafico7->Set90AndMargin(250,40,40,40);
    $grafico7->img->SetAngle(90);
    // Create the bar plots
    $b24plot = new BarPlot($datos_h);
    //
    $grafico7->Add(array($b24plot));

    $b24plot->SetColor("white");
    $b24plot->SetFillColor($color_h);

    $nombreImagen7 = 'graficas/img/imagen7.png'; 

    //guardamos la grafica
    $grafico7->Stroke($nombreImagen7);

$pdf->Image($nombreImagen7,4,($Y+2),208);
//Agregamos otra pagina
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
$pdf->Cell(178,4,'Reporte Estadistico',0,0,'C',0);
$pdf->SetY(18);
$pdf->SetX(34);
$pdf->Cell(178,4,mes($_GET["mes"]).' - '.$_GET["anno"],0,0,'C',0);
//estados
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(26);
$pdf->SetX(4);
$pdf->Cell(208,6,utf8_decode('ESTADOS: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(26);
$pdf->SetX(37);
$pdf->Cell(20,6,$_SESSION["rep_per"][10][0][0],0,0,'L',0);
$Y=32;
$labels8=array(); $j=0; $datos_es=array(); $color_es=array();

$a=1;
for($i=1; $i<=$_SESSION["rep_per"][10][0][0]; $i++)
{
    $pdf->SetFont('Arial','',10);
    $pdf->SetY($Y);
    $pdf->SetX(4);
    $pdf->Cell(12,6,$i,1,0,'L',0);
    //
    $pdf->SetFont('Arial','B',10);
    $pdf->SetY($Y);
    $pdf->SetX(16);
    $pdf->Cell(184,6,$_SESSION["rep_per"][10][$i][$a],1,0,'L',0);
    array_push($labels8, $_SESSION["rep_per"][10][$i][$a]);
    $a++;
    //
    $pdf->SetFont('Arial','',10);
    $pdf->SetY($Y);
    $pdf->SetX(200);
    $pdf->Cell(12,6,number_format($_SESSION["rep_per"][10][$i][$a]),1,0,'R',0);
    array_push($datos_es, $_SESSION["rep_per"][10][$i][$a]);

    array_push($color_es, randomColor());

    $a=1;
    $Y=$Y+6;

    if($Y>=274)
    {
        //Agregamos otra pagina
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
        $pdf->Cell(178,4,'Reporte Estadistico',0,0,'C',0);
        $pdf->SetY(18);
        $pdf->SetX(34);
        $pdf->Cell(178,4,mes($_GET["mes"]).' - '.$_GET["anno"],0,0,'C',0);

        $Y=26;
    }
}

 //Agregamos otra pagina
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
 $pdf->Cell(178,4,'Reporte Estadistico',0,0,'C',0);
 $pdf->SetY(18);
 $pdf->SetX(34);
 $pdf->Cell(178,4,mes($_GET["mes"]).' - '.$_GET["anno"],0,0,'C',0);

 $Y=34;

//grafica
// Creamos el grafico
//$grafico8 = new Graph(800, 600, 'auto');
//$grafico8->SetScale("textlin");
//$grafico8->title->Set("Estados");
//$grafico8->xaxis->SetTickLabels($labels8);   
//$grafico8->Set90AndMargin(250,40,40,40);
//$grafico8->img->SetAngle(90);
//// Create the bar plots
//$b25plot = new BarPlot($datos_es);
////
//$grafico8->Add(array($b25plot));
//
//$b25plot->SetColor("white");
//$b25plot->SetFillColor($color_es);
////
//$nombreImagen8 = 'graficas/img/imagen8.png'; 
////
//////guardamos la grafica
//$grafico8->Stroke($nombreImagen8);
//
//$pdf->Image($nombreImagen8,4,($Y+2),208);

//$pdf->SetY(8);
//$pdf->SetX(38);
//$pdf->Multicell(200,4,print_r($b25plot),0,'J',0);

//Envio Archivo
$pdf->Output();
