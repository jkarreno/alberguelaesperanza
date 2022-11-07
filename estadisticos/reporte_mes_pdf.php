<?php
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

require('../libs/fpdf/fpdf.php');

require_once ('../jpgraph/jpgraph.php');
require_once ('../jpgraph/jpgraph_bar.php');

//0 por confimar | 1 confirmado | 2 cancelada
$anno=$_GET["anno"];
$mes=$_GET["mes"];

//calcula datos

//total de reservaciones
$ResRes=mysqli_query($conn, "SELECT * FROM reservacion WHERE Fecha LIKE '".$anno."-".$mes."-%' ORDER BY Fecha DESC");
$TReservaciones=mysqli_num_rows($ResRes);
//total de ocupaciones
$ResOcu=mysqli_query($conn, "SELECT * FROM reservacion WHERE Fecha LIKE '".$anno."-".$mes."-%' AND Estatus=1 ORDER BY Fecha DESC");
$TOcupaciones=mysqli_num_rows($ResOcu);
//total por confirmar
$ResCon=mysqli_query($conn, "SELECT * FROM reservacion WHERE Fecha LIKE '".$anno."-".$mes."-%' AND Estatus=0 ORDER BY Fecha DESC");
$TpConfirmar=mysqli_num_rows($ResCon);
//total cancelaciones
$ResCan=mysqli_query($conn, "SELECT * FROM reservacion WHERE Fecha LIKE '".$anno."-".$mes."-%' AND Estatus=2 ORDER BY Fecha DESC");
$TCancelaciones=mysqli_num_rows($ResCan);

//Servicios
//hospedajes
$ResHospedajes=mysqli_fetch_array(mysqli_query($conn, "SELECT count(Id) AS hospedajes FROM reservaciones WHERE Fecha LIKE '".$anno."-".$mes."-%' AND Cama>0 AND Estatus='1' "));
$alimentos=$ResHospedajes["hospedajes"]*3;
$lavanderia=$ResHospedajes["hospedajes"]/7;
$servicios=$ResHospedajes["hospedajes"]+$alimentos+$lavanderia;

//edad
$edadn=$anno-12;

//total de personas atendidas
//$TPersonas=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r WHERE r.IdPA!=0 AND r.Fecha LIKE '".$anno."-".$mes."-%' AND r.Estatus=1 GROUP BY r.IdPA"));
$TPersonas=mysqli_num_rows(mysqli_query($conn, "SELECT concat_ws('-', r.IdPA, r.Tipo) AS idpa FROM reservaciones AS r 
                                                WHERE `Fecha` LIKE '".$anno."-".$mes."-%' AND Estatus=1 AND Cama>0 GROUP BY concat_ws('-', r.IdPA, r.Tipo)"));
//total hombres
$TPH=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                            INNER JOIN pacientes AS p ON r.IdPA=p.Id 
                                            WHERE r.IdPA!=0 AND p.Sexo='M' AND r.Fecha LIKE '".$anno."-".$mes."-%' AND r.Estatus=1 AND Cama>0 GROUP BY r.IdPA"));
//total mujeres
$TPM=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                            INNER JOIN pacientes AS p ON r.IdPA=p.Id 
                                            WHERE r.IdPA!=0 AND p.Sexo='F' AND r.Fecha LIKE '".$anno."-".$mes."-%' AND r.Estatus=1 AND Cama>0 GROUP BY r.IdPA"));

//total pacientes
$TP=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r WHERE r.IdPA!=0 AND r.Tipo='P' AND r.Fecha LIKE '".$anno."-".$mes."-%' AND r.Estatus=1 AND r.Cama>0 GROUP BY r.IdPA"));
//total pacientes hombres
$TPH=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                            INNER JOIN pacientes AS p ON p.Id=r.IdPA
                                            WHERE r.IdPA!=0 AND r.Tipo='P' AND r.Fecha LIKE '".$anno."-".$mes."-%' AND p.Sexo='M' AND r.Estatus=1 AND r.Cama>0 GROUP BY r.IdPA"));
//total pacientes hombres niños
$TPHN=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                            INNER JOIN pacientes AS p ON p.Id=r.IdPA
                                            WHERE r.IdPA!=0 AND r.Tipo='P' AND r.Fecha LIKE '".$anno."-".$mes."-%' AND p.Sexo='M' AND p.FechaNacimiento>'".$edadn."-".$mes."-01' AND r.Estatus=1 AND r.Cama>0 GROUP BY r.IdPA"));
//total pacientes hombres adultos
$TPHA=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                            INNER JOIN pacientes AS p ON p.Id=r.IdPA
                                            WHERE r.IdPA!=0 AND r.Tipo='P' AND r.Fecha LIKE '".$anno."-".$mes."-%' AND p.Sexo='M' AND p.FechaNacimiento<'".$edadn."-".$mes."-01' AND r.Estatus=1 AND r.Cama>0 GROUP BY r.IdPA"));
//total pacientes mujeres
$TPM=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                            INNER JOIN pacientes AS p ON p.Id=r.IdPA
                                            WHERE r.IdPA!=0 AND r.Tipo='P' AND r.Fecha LIKE '".$anno."-".$mes."-%' AND p.Sexo='F' AND r.Estatus=1 AND r.Cama>0 GROUP BY r.IdPA"));
//total pacientes mujeres niñas
$TPMN=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                            INNER JOIN pacientes AS p ON p.Id=r.IdPA
                                            WHERE r.IdPA!=0 AND r.Tipo='P' AND r.Fecha LIKE '".$anno."-".$mes."-%' AND p.Sexo='F' AND p.FechaNacimiento>'".$edadn."-".$mes."-01' AND r.Estatus=1 AND r.Cama>0 GROUP BY r.IdPA"));
//total pacientes mujeres adultas
$TPMA=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                            INNER JOIN pacientes AS p ON p.Id=r.IdPA
                                            WHERE r.IdPA!=0 AND r.Tipo='P' AND r.Fecha LIKE '".$anno."-".$mes."-%' AND p.Sexo='F' AND p.FechaNacimiento<'".$edadn."-".$mes."-01' AND r.Estatus=1  AND r.Cama>0 GROUP BY r.IdPA"));

//total acompañantes
$TA=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r WHERE r.IdPA!=0 AND r.Tipo='A' AND r.Fecha LIKE '".$anno."-".$mes."-%' AND r.Estatus=1 GROUP BY r.IdPA"));
//total acompañantes hombres
$TAH=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                            INNER JOIN acompannantes AS a ON a.Id=r.IdPA
                                            WHERE r.IdPA!=0 AND r.Tipo='A' AND r.Fecha LIKE '".$anno."-".$mes."-%' AND a.Sexo='M' AND r.Estatus=1 GROUP BY r.IdPA"));
//total acompañantes hombres niños
$TAHN=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                            INNER JOIN acompannantes AS p ON p.Id=r.IdPA
                                            WHERE r.IdPA!=0 AND r.Tipo='A' AND r.Fecha LIKE '".$anno."-".$mes."-%' AND p.Sexo='M' AND p.FechaNacimiento>'".$edadn."-".$mes."-01' AND r.Estatus=1 GROUP BY r.IdPA"));
//total acompañantes hombres adultos
$TAHA=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                            INNER JOIN acompannantes AS p ON p.Id=r.IdPA
                                            WHERE r.IdPA!=0 AND r.Tipo='A' AND r.Fecha LIKE '".$anno."-".$mes."-%' AND p.Sexo='M' AND p.FechaNacimiento<'".$edadn."-".$mes."-01' AND r.Estatus=1 GROUP BY r.IdPA"));
//total acompañantes mujeres
$TAM=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                            INNER JOIN acompannantes AS a ON a.Id=r.IdPA
                                            WHERE r.IdPA!=0 AND r.Tipo='A' AND r.Fecha LIKE '".$anno."-".$mes."-%' AND a.Sexo='F' AND r.Estatus=1 GROUP BY r.IdPA"));
//total acompañantes mujeres niñas
$TAMN=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                            INNER JOIN acompannantes AS p ON p.Id=r.IdPA
                                            WHERE r.IdPA!=0 AND r.Tipo='A' AND r.Fecha LIKE '".$anno."-".$mes."-%' AND p.Sexo='F' AND p.FechaNacimiento>'".$edadn."-".$mes."-01' AND r.Estatus=1 GROUP BY r.IdPA"));
//total acompañantes mujeres adultas
$TAMA=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                            INNER JOIN acompannantes AS p ON p.Id=r.IdPA
                                            WHERE r.IdPA!=0 AND r.Tipo='A' AND r.Fecha LIKE '".$anno."-".$mes."-%' AND p.Sexo='F' AND p.FechaNacimiento<'".$edadn."-".$mes."-01' AND r.Estatus=1 GROUP BY r.IdPA"));

//enfermedades
$ResEnfermedades=mysqli_query($conn, "SELECT p.Diagnostico1 AS Diagnostico, COUNT(*) AS Numero FROM `reservacion`AS r 
                                        INNER JOIN pacientes AS p ON p.Id=r.IdPaciente 
                                        WHERE r.Fecha LIKE '".$anno."-".$mes."-%' AND p.Diagnostico1!='' GROUP BY p.Diagnostico1 ORDER BY `Numero` ASC");
$Enfermedades=mysqli_num_rows($ResEnfermedades);

//pacientes por hospital
$ResHospitales=mysqli_query($conn, "SELECT COUNT(I.Hospital) AS Numero, I.Hospital AS Hospital
                                        FROM (SELECT COUNT(i.Instituto) AS Pacientes, i.Instituto AS Hospital 
                                                FROM pacientes AS p 
                                                INNER JOIN institutos AS i ON p.Instituto1=i.Id 
                                                INNER JOIN reservacion AS r ON r.IdPaciente=p.Id 
                                                WHERE r.FechaReservacion LIKE '".$anno."-".$mes."-%' 
                                                GROUP BY r.IdPaciente, i.Instituto) AS I 
                                        GROUP BY I.Hospital ORDER BY I.Hospital ASC");
$NumHosp=mysqli_num_rows($ResHospitales);

//pacientes por procedencia
$ResProcedencia=mysqli_query($conn, "SELECT COUNT(Es.Estado) AS Numero, Es.Estado AS Estado, Es.EId
                                    FROM (SELECT COUNT(E.Estado) AS Pacientes, E.Estado AS Estado, E.Id AS EId
                                        FROM pacientes AS p 
                                        INNER JOIN Estados AS E ON p.Estado=E.Id 
                                        INNER JOIN reservacion AS r ON r.IdPaciente=p.Id 
                                        WHERE r.FechaReservacion LIKE '".$anno."-".$mes."-%' 
                                        GROUP BY r.IdPaciente, E.Estado, E.Id) AS Es
                                    GROUP BY Es.Estado, Es.EId ORDER BY Es.Estado ASC");
$NumEstados=mysqli_num_rows($ResProcedencia);

$Y=$anno;


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
$pdf->Cell(178,4,'Reporte Estadistico',0,0,'C',0);
$pdf->SetY(18);
$pdf->SetX(34);
$pdf->Cell(178,4,mes($_GET["mes"]).' - '.$_GET["anno"],0,0,'C',0);
//reservaciones
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(26);
$pdf->SetX(4);
$pdf->Cell(208,6,utf8_decode('RESERVACIONES: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(26);
$pdf->SetX(37);
$pdf->Cell(20,6,$TReservaciones,0,0,'L',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(32);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Ocupaciones: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(32);
$pdf->SetX(4);
$pdf->Cell(40,6,$TOcupaciones,0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(38);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Por Confirmar: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(38);
$pdf->SetX(4);
$pdf->Cell(40,6,$TpConfirmar,0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(44);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Canceladas: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(44);
$pdf->SetX(4);
$pdf->Cell(40,6,$TCancelaciones,0,0,'R',0);
//grafica
    // Creamos el grafico
    $datos1=array($TOcupaciones, 0, 0);
    $datos2=array(0, $TpConfirmar, 0);
    $datos3=array(0, 0, $TCancelaciones);
    $labels=array("Ocupaciones","Por Confirmar","Canceladas");  

    $grafico = new Graph(800, 600, 'auto');
    $grafico->SetScale("textlin");
    $grafico->title->Set("Reservaciones");
    $grafico->xaxis->SetTickLabels($labels);    

    // Create the bar plots
    $b1plot = new BarPlot($datos1);
    $b2plot = new BarPlot($datos2);
    $b3plot = new BarPlot($datos3);
    // 
    $grafico->Add(array($b1plot, $b2plot, $b3plot));    

    $b1plot->SetColor("white");
    $b1plot->SetFillColor("#26b719");
    $b1plot->SetWidth(200);     

    $b2plot->SetColor("white");
    $b2plot->SetFillColor("#0864bf");
    $b2plot->SetWidth(200);     

    $b3plot->SetColor("white");
    $b3plot->SetFillColor("#ff0000");
    $b3plot->SetWidth(200);     

    $nombreImagen = 'graficas/img/' . uniqid() . '.png';    

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
$pdf->Cell(20,6,number_format($servicios),0,0,'L',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(148);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Hospedajes: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(148);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($ResHospedajes["hospedajes"]),0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(154);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Alimentos: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(154);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($alimentos),0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(160);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Lavanderia: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(160);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($lavanderia),0,0,'R',0);
//grafica
    // Creamos el grafico
    $datos4=array($ResHospedajes["hospedajes"], 0, 0);
    $datos5=array(0, $alimentos, 0);
    $datos6=array(0, 0, $lavanderia);
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

    $nombreImagen2 = 'graficas/img/' . uniqid() . '.png';    

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
$pdf->Cell(208,6,utf8_decode('PERSONAS ATENDIDAS: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(26);
$pdf->SetX(50);
$pdf->Cell(20,6,number_format($TPersonas),0,0,'L',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(32);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Pacientes: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(32);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($TP),0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(38);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Acompañantes: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(38);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($TA),0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(44);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Hombres: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(44);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($TPH+$TAH),0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(50);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Mujeres: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(50);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($TPM+$TAM),0,0,'R',0);
//grafica
    // Creamos el grafico
    $datos7=array($TP, 0, 0, 0);
    $datos8=array(0, $TA, 0, 0);
    $datos9=array(0, 0, ($TPH+$TAH), 0);
    $datos10=array(0, 0, 0, ($TPM+$TAM));
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

    $nombreImagen3 = 'graficas/img/' . uniqid() . '.png';    

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
$pdf->Cell(20,6,number_format($TP),0,0,'L',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(148);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Hombres: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(148);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($TPH),0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(154);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Hombres Niños: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(154);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($TPHN),0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(160);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Hombres Adultos: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(160);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($TPHA),0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(166);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Mujeres: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(166);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($TPM),0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(172);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Mujeres Niñas: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(172);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($TPMN),0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(178);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Mujeres Adultas: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(178);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($TPMA),0,0,'R',0);
//grafica
    // Creamos el grafico
    $datos11=array($TPH, 0, 0, 0, 0, 0);
    $datos12=array(0, $TPHN, 0, 0, 0, 0);
    $datos13=array(0, 0, $TPHA, 0, 0, 0);
    $datos14=array(0, 0, 0, $TPM, 0, 0);
    $datos15=array(0, 0, 0, 0, $TPMN, 0);
    $datos16=array(0, 0, 0, 0, 0, $TPMA);
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

    $nombreImagen4 = 'graficas/img/' . uniqid() . '.png';    

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
$pdf->Cell(45,6,utf8_decode('0 a 12 años: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(32);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($_SESSION["PRDoce"]),0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(38);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('13 a 20 años: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(38);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($_SESSION["PRVeinte"]),0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(44);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('21 a 64 años: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(44);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($_SESSION["PRSesentaycuatro"]),0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(50);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('65 y más años: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(50);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($_SESSION["PRMas"]),0,0,'R',0);
//
//grafico
    //creamos el grafico
    $datosep1=array($_SESSION["PRDoce"], 0, 0, 0);
    $datosep2=array(0, $_SESSION["PRVeinte"], 0, 0);
    $datosep3=array(0, 0, $_SESSION["PRSesentaycuatro"], 0);
    $datosep4=array(0, 0, 0, $_SESSION["PRMas"]);
    
    $labels9=array("0 a 12", "13 a 20", "21 a 64", "65 y más"); 

    $grafico9 = new Graph(800, 600, 'auto');
    $grafico9->SetScale("textlin");
    $grafico9->title->Set("Edades Pacientes");
    $grafico9->xaxis->SetTickLabels($labels9); 

    // Create the bar plots
    $bep1plot = new BarPlot($datosep1);
    $bep2plot = new BarPlot($datosep2);
    $bep3plot = new BarPlot($datosep3);
    $bep4plot = new BarPlot($datosep4);
    // 
    $grafico9->Add(array($bep1plot, $bep2plot, $bep3plot, $bep4plot));

    $bep1plot->SetColor("white");
    $bep1plot->SetFillColor("#685de8");
    $bep1plot->SetWidth(100);     

    $bep2plot->SetColor("white");
    $bep2plot->SetFillColor("#37b83c");
    $bep2plot->SetWidth(100);     

    $bep3plot->SetColor("white");
    $bep3plot->SetFillColor("#f36115");
    $bep3plot->SetWidth(100);     

    $bep4plot->SetColor("white");
    $bep4plot->SetFillColor("#003366");
    $bep4plot->SetWidth(100);

    $nombreImagen9 = 'graficas/img/' . uniqid() . '.png'; 

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
$pdf->Cell(20,6,number_format($TA),0,0,'L',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(148);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Hombres: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(148);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($TAH),0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(154);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Niños: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(154);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($TAHN),0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(160);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Adultos: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(160);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($TAHA),0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(166);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Mujeres: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(166);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($TAM),0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(172);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Niñas: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(172);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($TAMN),0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(178);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('Adultas: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(178);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($TAMA),0,0,'R',0);
//grafica
    // Creamos el grafico
    $datos17=array($TAH, 0, 0, 0, 0, 0);
    $datos18=array(0, $TAHN, 0, 0, 0, 0);
    $datos19=array(0, 0, $TAHA, 0, 0, 0);
    $datos20=array(0, 0, 0, $TAM, 0, 0);
    $datos21=array(0, 0, 0, 0, $TAMN, 0);
    $datos22=array(0, 0, 0, 0, 0, $TAMA);
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

    $nombreImagen5 = 'graficas/img/' . uniqid() . '.png';    

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
$pdf->Cell(45,6,utf8_decode('0 a 12 años: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(32);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($_SESSION["ARDoce"]),0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(38);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('13 a 20 años: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(38);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($_SESSION["ARVeinte"]),0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(44);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('21 a 64 años: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(44);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($_SESSION["ARSesentaycuatro"]),0,0,'R',0);
//
$pdf->SetFont('Arial','B',10);
$pdf->SetY(50);
$pdf->SetX(4);
$pdf->Cell(45,6,utf8_decode('65 y más años: '),1,0,'L',0);
//
$pdf->SetFont('Arial','',10);
$pdf->SetY(50);
$pdf->SetX(4);
$pdf->Cell(40,6,number_format($_SESSION["ARMas"]),0,0,'R',0);
//

//grafico
    //creamos el grafico
    $datosea1=array($_SESSION["ARDoce"], 0, 0, 0);
    $datosea2=array(0, $_SESSION["ARVeinte"], 0, 0);
    $datosea3=array(0, 0, $_SESSION["ARSesentaycuatro"], 0);
    $datosea4=array(0, 0, 0, $_SESSION["ARMas"]);
    $labels10=array("0 a 12", "13 a 20", "21 a 64", "65 y más"); 

    $grafico10 = new Graph(800, 600, 'auto');
    $grafico10->SetScale("textlin");
    $grafico10->title->Set("Edades Acompañantes");
    $grafico10->xaxis->SetTickLabels($labels10); 

    // Create the bar plots
    $bea1plot = new BarPlot($datosea1);
    $bea2plot = new BarPlot($datosea2);
    $bea3plot = new BarPlot($datosea3);
    $bea4plot = new BarPlot($datosea4);
    // 
    $grafico10->Add(array($bea1plot, $bea2plot, $bea3plot, $bea4plot));

    $bea1plot->SetColor("white");
    $bea1plot->SetFillColor("#685de8");
    $bea1plot->SetWidth(100);     

    $bea2plot->SetColor("white");
    $bea2plot->SetFillColor("#37b83c");
    $bea2plot->SetWidth(100);     

    $bea3plot->SetColor("white");
    $bea3plot->SetFillColor("#f36115");
    $bea3plot->SetWidth(100);     

    $bea4plot->SetColor("white");
    $bea4plot->SetFillColor("#003366");
    $bea4plot->SetWidth(100);

    $nombreImagen10 = 'graficas/img/' . uniqid() . '.png'; 

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
$pdf->Cell(20,6,number_format($Enfermedades),0,0,'L',0);
//
$Y=148;
$labels6=array(); $j=0; $datos_e=array(); $color_e=array();

while($RResEnf=mysqli_fetch_array($ResEnfermedades))
{
    $ResEnfermedad=mysqli_fetch_array(mysqli_query($conn, "SELECT Diagnostico FROM diagnosticos WHERE Id='".$RResEnf["Diagnostico"]."' LIMIT 1"));

    $pdf->SetFont('Arial','B',10);
    $pdf->SetY($Y);
    $pdf->SetX(4);
    $pdf->Cell(196,6,utf8_decode($ResEnfermedad["Diagnostico"].': '),1,0,'L',0);
    //
    $pdf->SetFont('Arial','',10);
    $pdf->SetY($Y);
    $pdf->SetX(200);
    $pdf->Cell(12,6,number_format($RResEnf["Numero"]),1,0,'R',0);

    array_push($datos_e, $RResEnf["Numero"]);
    array_push($labels6, $ResEnfermedad["Diagnostico"]);
    array_push($color_e, randomColor());

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
    $grafico6 = new Graph(800, 600, 'auto');
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

    $nombreImagen6 = 'graficas/img/' . uniqid() . '.png'; 

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
$pdf->Image($nombreImagen6,4,($Y+2),208);

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
$pdf->Cell(20,6,$NumHosp,0,0,'L',0);
$Y=32;
$labels7=array(); $j=0; $datos_h=array(); $color_h=array();
while($RResHosp=mysqli_fetch_array($ResHospitales))
{
    $pdf->SetFont('Arial','B',10);
    $pdf->SetY($Y);
    $pdf->SetX(4);
    $pdf->Cell(196,6,utf8_decode($RResHosp["Hospital"].': '),1,0,'L',0);
    //
    $pdf->SetFont('Arial','',10);
    $pdf->SetY($Y);
    $pdf->SetX(200);
    $pdf->Cell(12,6,number_format($RResHosp["Numero"]),1,0,'R',0);

    array_push($datos_h, $RResHosp["Numero"]);
    array_push($labels7, utf8_decode($RResHosp["Hospital"]));
    array_push($color_h, randomColor());

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

    $nombreImagen7 = 'graficas/img/' . uniqid() . '.png'; 

    //guardamos la grafica
    $grafico7->Stroke($nombreImagen7);

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
//Hospitales
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
$pdf->Cell(20,6,$NumEstados,0,0,'L',0);
$Y=32;
$labels8=array(); $j=0; $datos_es=array(); $color_es=array();
while($RResEst=mysqli_fetch_array($ResProcedencia))
{
    $pdf->SetFont('Arial','B',10);
    $pdf->SetY($Y);
    $pdf->SetX(4);
    $pdf->Cell(196,6,$RResEst["Estado"].': ',1,0,'L',0);
    //
    $pdf->SetFont('Arial','',10);
    $pdf->SetY($Y);
    $pdf->SetX(200);
    $pdf->Cell(12,6,number_format($RResEst["Numero"]),1,0,'R',0);

    array_push($datos_es, $RResEst["Numero"]);
    array_push($labels8, $RResEst["Estado"]);
    array_push($color_es, randomColor());

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
    $grafico8 = new Graph(800, 600, 'auto');
    $grafico8->SetScale("textlin");
    $grafico8->title->Set("Estados");
    $grafico8->xaxis->SetTickLabels($labels8);   
    $grafico8->Set90AndMargin(250,40,40,40);
    $grafico8->img->SetAngle(90);
    // Create the bar plots
    $b25plot = new BarPlot($datos_es);
    //
    $grafico8->Add(array($b25plot));

    $b25plot->SetColor("white");
    $b25plot->SetFillColor($color_es);

    $nombreImagen8 = 'graficas/img/' . uniqid() . '.png'; 

    //guardamos la grafica
    $grafico8->Stroke($nombreImagen8);

    if($Y>=120)
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
$pdf->Image($nombreImagen8,4,($Y+2),208);

//bitacora
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '104', '".json_encode($_GET)."')");


//Envio Archivo
$pdf->Output();

//borrar grafica
unlink($nombreImagen);
unlink($nombreImagen2);
unlink($nombreImagen3);
unlink($nombreImagen4);
unlink($nombreImagen5);
unlink($nombreImagen6);
unlink($nombreImagen7);
unlink($nombreImagen8);
unlink($nombreImagen9);

?>