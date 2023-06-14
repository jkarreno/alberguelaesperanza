<?php
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

require('../libs/fpdf/fpdf.php');

//crear el nuevo archivo pdf
$pdf=new FPDF('P', 'mm', 'Letter');

//desabilitar el corte automatico de pagina
$pdf->SetAutoPageBreak(false); 

//Agregamos la primer pagina
$pdf->AddPage();
//logo 
$pdf->Image('../images/logo.png',140,8,70);
//titulo
$pdf->SetY(16);
$pdf->SetX(6);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(130,4,'Control de Camas',0,0,'C',0);
//
$pdf->SetY(24);
$pdf->SetFont('Arial','',12);
$pdf->Cell(130,4,fecha($_GET["fecha"]),0,0,'C',0);
$pdf->SetY(30);
$pdf->Cell(130,4,'Hoja: 1',0,0,'C',0);
//hombres
$pdf->SetY(42);
$pdf->SetFont('Arial','',12);
$pdf->Cell(200,4,'01 HOMBRES',0,0,'C',0);

$pdf->SetFillColor(057,044,138);
$pdf->SetFont('Arial','B',8);
$pdf->SetTextColor(255,255,255);
$pdf->SetY(48);
$pdf->SetX(6);
$pdf->Cell(10,8,utf8_decode('Dor'),1,0,'C',1);
$pdf->Cell(10,8,'Cama',1,0,'C',1);
$pdf->Cell(100,8,'Nombre',1,0,'C',1);
$pdf->Cell(15,8,'Registro',1,0,'C',1);
$pdf->Cell(10,8,utf8_decode('P/A'),1,0,'C',1);
$pdf->Cell(10,8,'Sexo',1,0,'C',1);
$pdf->Cell(47,8,'Observaciones',1,0,'C',1);

$y_axis_initial=48;
$y_initial=56;

//habitación 2
$ResCam2=mysqli_query($conn, "SELECT * FROM camas WHERE Habitacion='2' ORDER BY Cama ASC");
while($RResCam2=mysqli_fetch_array($ResCam2))
{
    //$ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE (Fecha='".$_GET["fecha"]."' OR Fecha='".date("Y-m-d",strtotime($_GET["fecha"]."- 1 days"))."') AND Cama='".$RResCam2["Id"]."' AND Estatus<2 AND Liberada='0'");
    $ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE Fecha='".$_GET["fecha"]."'AND Cama='".$RResCam2["Id"]."' AND Estatus<2 AND Liberada='0'");

    $res=mysqli_num_rows($ResRes);

    if($res>0 AND $_POST["camas"]<2) //cama ocupada   
    {
        $RResRes=mysqli_fetch_array($ResRes);
        if($RResRes["Tipo"]=='P')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos) AS Nombre, Sexo FROM pacientes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }
        elseif($RResRes["Tipo"]=='A')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos) AS Nombre, Sexo FROM acompannantes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }

        $print=1;
    }
    elseif($res==0 AND ($_POST["camas"]==2 OR $_POST["camas"]==0)) //cama vacia 
    {
        $ResNombre["Nombre"]='';
        $RResRes["Tipo"]='';
        $ResNombre["Id"]='';
        $ResNombre["Sexo"]='';

        $print=1;
    }

    if($RResCam2["Cama"]=='extra' AND $ResNombre["Nombre"]=='')
    {
        $print=0;
    }

    if($print==1)
    {
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','',8);
        $pdf->SetTextColor(000,000,000);
        $pdf->SetY($y_initial);
        $pdf->SetX(6);
        $pdf->Cell(10,10,'02',1,0,'C',1);
        $pdf->Cell(10,10,$RResCam2["Cama"],1,0,'C',1);
        $pdf->Cell(100,10,utf8_decode($ResNombre["Nombre"]),1,0,'L',1);
        $pdf->Cell(15,10,$ResNombre["Id"],1,0,'C',1);
        $pdf->Cell(10,10,$RResRes["Tipo"],1,0,'C',1);
        $pdf->Cell(10,10,$ResNombre["Sexo"],1,0,'C',1);
        $pdf->Cell(47,10,'',1,0,'C',1);

        $y_initial=$y_initial+10;
    }

    
}

//habitación 3
$ResCam3=mysqli_query($conn, "SELECT * FROM camas WHERE Habitacion='3' ORDER BY Cama ASC");
while($RResCam3=mysqli_fetch_array($ResCam3))
{
    $ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE (Fecha='".$_GET["fecha"]."' OR Fecha='".date("Y-m-d",strtotime($_GET["fecha"]."- 1 days"))."') AND Cama='".$RResCam3["Id"]."' AND Estatus<2 AND Liberada='0'");

    $res=mysqli_num_rows($ResRes);

    if($res>0 AND $_POST["camas"]<2) //cama ocupada   
    {
        $RResRes=mysqli_fetch_array($ResRes);
        if($RResRes["Tipo"]=='P')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos)AS Nombre, Sexo FROM pacientes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }
        elseif($RResRes["Tipo"]=='A')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos) AS Nombre, Sexo FROM acompannantes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }

        $print=1;
    }
    elseif($res==0 AND ($_POST["camas"]==2 OR $_POST["camas"]==0)) //cama vacia 
    {
        $ResNombre["Nombre"]='';
        $RResRes["Tipo"]='';
        $ResNombre["Id"]='';
        $ResNombre["Sexo"]='';

        $print=1;
    }

    if($RResCam3["Cama"]=='extra' AND $ResNombre["Nombre"]=='')
    {
        $print=0;
    }

    if($print==1)
    {
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','',8);
        $pdf->SetTextColor(000,000,000);
        $pdf->SetY($y_initial);
        $pdf->SetX(6);
        $pdf->Cell(10,10,'03',1,0,'C',1);
        $pdf->Cell(10,10,$RResCam3["Cama"],1,0,'C',1);
        $pdf->Cell(100,10,$ResNombre["Nombre"],1,0,'L',1);
        $pdf->Cell(15,10,$ResNombre["Id"],1,0,'C',1);
        $pdf->Cell(10,10,$RResRes["Tipo"],1,0,'C',1);
        $pdf->Cell(10,10,$ResNombre["Sexo"],1,0,'C',1);
        $pdf->Cell(47,10,'',1,0,'C',1);

        $y_initial=$y_initial+10;
    }

    
}

//habitación 4
$ResCam4=mysqli_query($conn, "SELECT * FROM camas WHERE Habitacion='4' ORDER BY Cama ASC");
while($RResCam4=mysqli_fetch_array($ResCam4))
{
    //$ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE (Fecha='".$_GET["fecha"]."' OR Fecha='".date("Y-m-d",strtotime($_GET["fecha"]."- 1 days"))."') AND Cama='".$RResCam4["Id"]."' AND Estatus<2 AND Liberada='0'");
    $ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE Fecha='".$_GET["fecha"]."' AND Cama='".$RResCam4["Id"]."' AND Estatus<2 AND Liberada='0'");

    $res=mysqli_num_rows($ResRes);

    if($res>0 AND $_POST["camas"]<2) //cama ocupada   
    {
        $RResRes=mysqli_fetch_array($ResRes);
        if($RResRes["Tipo"]=='P')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos)AS Nombre, Sexo FROM pacientes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }
        elseif($RResRes["Tipo"]=='A')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos) AS Nombre, Sexo FROM acompannantes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }

        $print=1;
    }
    elseif($res==0 AND ($_POST["camas"]==2 OR $_POST["camas"]==0)) //cama vacia 
    {
        $ResNombre["Nombre"]='';
        $RResRes["Tipo"]='';
        $ResNombre["Id"]='';
        $ResNombre["Sexo"]='';

        $print=1;
    }

    if($RResCam4["Cama"]=='extra' AND $ResNombre["Nombre"]=='')
    {
        $print=0;
    }

    if($print==1)
    {
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','',8);
        $pdf->SetTextColor(000,000,000);
        $pdf->SetY($y_initial);
        $pdf->SetX(6);
        $pdf->Cell(10,10,'04',1,0,'C',1);
        $pdf->Cell(10,10,$RResCam4["Cama"],1,0,'C',1);
        $pdf->Cell(100,10,$ResNombre["Nombre"],1,0,'L',1);
        $pdf->Cell(15,10,$ResNombre["Id"],1,0,'C',1);
        $pdf->Cell(10,10,$RResRes["Tipo"],1,0,'C',1);
        $pdf->Cell(10,10,$ResNombre["Sexo"],1,0,'C',1);
        $pdf->Cell(47,10,'',1,0,'C',1);

        $y_initial=$y_initial+10;
    }

    
}

//Hoja 2
$pdf->AddPage();
//logo 
$pdf->Image('../images/logo.png',140,8,70);
//titulo
$pdf->SetY(16);
$pdf->SetX(6);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(130,4,'Control de Camas',0,0,'C',0);
//
$pdf->SetY(24);
$pdf->SetFont('Arial','',12);
$pdf->Cell(130,4,fecha($_GET["fecha"]),0,0,'C',0);
$pdf->SetY(30);
$pdf->Cell(130,4,'Hoja: 2',0,0,'C',0);
//hombres
$pdf->SetY(42);
$pdf->SetFont('Arial','',12);
$pdf->Cell(200,4,'01 HOMBRES',0,0,'C',0);

$pdf->SetFillColor(057,044,138);
$pdf->SetFont('Arial','B',8);
$pdf->SetTextColor(255,255,255);
$pdf->SetY(48);
$pdf->SetX(6);
$pdf->Cell(10,8,utf8_decode('Dor'),1,0,'C',1);
$pdf->Cell(10,8,'Cama',1,0,'C',1);
$pdf->Cell(100,8,'Nombre',1,0,'C',1);
$pdf->Cell(15,8,'Registro',1,0,'C',1);
$pdf->Cell(10,8,utf8_decode('P/A'),1,0,'C',1);
$pdf->Cell(10,8,'Sexo',1,0,'C',1);
$pdf->Cell(47,8,'Observaciones',1,0,'C',1);

$y_axis_initial=48;
$y_initial=56;

//habitación 5
$ResCam5=mysqli_query($conn, "SELECT * FROM camas WHERE Habitacion='5' ORDER BY Cama ASC");
while($RResCam5=mysqli_fetch_array($ResCam5))
{
    //$ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE (Fecha='".$_GET["fecha"]."' OR Fecha='".date("Y-m-d",strtotime($_GET["fecha"]."- 1 days"))."') AND Cama='".$RResCam5["Id"]."' AND Estatus<2 AND Liberada='0'");
    $ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE Fecha='".$_GET["fecha"]."' AND Cama='".$RResCam5["Id"]."' AND Estatus<2 AND Liberada='0'");

    $res=mysqli_num_rows($ResRes);

    if($res>0 AND $_POST["camas"]<2) //cama ocupada   
    {
        $RResRes=mysqli_fetch_array($ResRes);
        if($RResRes["Tipo"]=='P')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos)AS Nombre, Sexo FROM pacientes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }
        elseif($RResRes["Tipo"]=='A')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos) AS Nombre, Sexo FROM acompannantes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }

        $print=1;
    }
    elseif($res==0 AND ($_POST["camas"]==2 OR $_POST["camas"]==0)) //cama vacia 
    {
        $ResNombre["Nombre"]='';
        $RResRes["Tipo"]='';
        $ResNombre["Id"]='';
        $ResNombre["Sexo"]='';

        $print=1;
    }

    if($RResCam5["Cama"]=='extra' AND $ResNombre["Nombre"]=='')
    {
        $print=0;
    }

    if($print==1)
    {
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','',8);
        $pdf->SetTextColor(000,000,000);
        $pdf->SetY($y_initial);
        $pdf->SetX(6);
        $pdf->Cell(10,10,'05',1,0,'C',1);
        $pdf->Cell(10,10,$RResCam5["Cama"],1,0,'C',1);
        $pdf->Cell(100,10,$ResNombre["Nombre"],1,0,'L',1);
        $pdf->Cell(15,10,$ResNombre["Id"],1,0,'C',1);
        $pdf->Cell(10,10,$RResRes["Tipo"],1,0,'C',1);
        $pdf->Cell(10,10,$ResNombre["Sexo"],1,0,'C',1);
        $pdf->Cell(47,10,'',1,0,'C',1);

        $y_initial=$y_initial+10;
    }

    
}

//habitación 29
$ResCam29=mysqli_query($conn, "SELECT * FROM camas WHERE Habitacion='29' ORDER BY Cama ASC");
while($RResCam29=mysqli_fetch_array($ResCam29))
{
    //$ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE (Fecha='".$_GET["fecha"]."' OR Fecha='".date("Y-m-d",strtotime($_GET["fecha"]."- 1 days"))."') AND Cama='".$RResCam29["Id"]."' AND Estatus<2 AND Liberada='0'");
    $ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE Fecha='".$_GET["fecha"]."' AND Cama='".$RResCam29["Id"]."' AND Estatus<2 AND Liberada='0'");

    $res=mysqli_num_rows($ResRes);

    if($res>0 AND $_POST["camas"]<2) //cama ocupada   
    {
        $RResRes=mysqli_fetch_array($ResRes);
        if($RResRes["Tipo"]=='P')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos)AS Nombre, Sexo FROM pacientes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }
        elseif($RResRes["Tipo"]=='A')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos) AS Nombre, Sexo FROM acompannantes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }

        $print=1;
    }
    elseif($res==0 AND ($_POST["camas"]==2 OR $_POST["camas"]==0)) //cama vacia 
    {
        $ResNombre["Nombre"]='';
        $RResRes["Tipo"]='';
        $ResNombre["Id"]='';
        $ResNombre["Sexo"]='';

        $print=1;
    }

    if($RResCam29["Cama"]=='extra' AND $ResNombre["Nombre"]=='')
    {
        $print=0;
    }

    if($print==1)
    {
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','',8);
        $pdf->SetTextColor(000,000,000);
        $pdf->SetY($y_initial);
        $pdf->SetX(6);
        $pdf->Cell(10,10,'29',1,0,'C',1);
        $pdf->Cell(10,10,$RResCam29["Cama"],1,0,'C',1);
        $pdf->Cell(100,10,$ResNombre["Nombre"],1,0,'L',1);
        $pdf->Cell(15,10,$ResNombre["Id"],1,0,'C',1);
        $pdf->Cell(10,10,$RResRes["Tipo"],1,0,'C',1);
        $pdf->Cell(10,10,$ResNombre["Sexo"],1,0,'C',1);
        $pdf->Cell(47,10,'',1,0,'C',1);

        $y_initial=$y_initial+10;
    }

    
}

//habitación 30
$ResCam30=mysqli_query($conn, "SELECT * FROM camas WHERE Habitacion='30' ORDER BY Cama ASC");
while($RResCam30=mysqli_fetch_array($ResCam30))
{
    //$ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE (Fecha='".$_GET["fecha"]."' OR Fecha='".date("Y-m-d",strtotime($_GET["fecha"]."- 1 days"))."') AND Cama='".$RResCam30["Id"]."' AND Estatus<2 AND Liberada='0'");
    $ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE Fecha='".$_GET["fecha"]."' AND Cama='".$RResCam30["Id"]."' AND Estatus<2 AND Liberada='0'");

    $res=mysqli_num_rows($ResRes);

    if($res>0 AND $_POST["camas"]<2) //cama ocupada   
    {
        $RResRes=mysqli_fetch_array($ResRes);
        if($RResRes["Tipo"]=='P')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos)AS Nombre, Sexo FROM pacientes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }
        elseif($RResRes["Tipo"]=='A')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos) AS Nombre, Sexo FROM acompannantes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }

        $print=1;
    }
    elseif($res==0 AND ($_POST["camas"]==2 OR $_POST["camas"]==0)) //cama vacia 
    {
        $ResNombre["Nombre"]='';
        $RResRes["Tipo"]='';
        $ResNombre["Id"]='';
        $ResNombre["Sexo"]='';

        $print=1;
    }

    if($RResCam30["Cama"]=='extra' AND $ResNombre["Nombre"]=='')
    {
        $print=0;
    }

    if($print==1)
    {
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','',8);
        $pdf->SetTextColor(000,000,000);
        $pdf->SetY($y_initial);
        $pdf->SetX(6);
        $pdf->Cell(10,10,'30',1,0,'C',1);
        $pdf->Cell(10,10,$RResCam30["Cama"],1,0,'C',1);
        $pdf->Cell(100,10,$ResNombre["Nombre"],1,0,'L',1);
        $pdf->Cell(15,10,$ResNombre["Id"],1,0,'C',1);
        $pdf->Cell(10,10,$RResRes["Tipo"],1,0,'C',1);
        $pdf->Cell(10,10,$ResNombre["Sexo"],1,0,'C',1);
        $pdf->Cell(47,10,'',1,0,'C',1);

        $y_initial=$y_initial+10;
    }

    
}

//Hoja 3
$pdf->AddPage();
//logo 
$pdf->Image('../images/logo.png',140,8,70);
//titulo
$pdf->SetY(16);
$pdf->SetX(6);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(130,4,'Control de Camas',0,0,'C',0);
//
$pdf->SetY(24);
$pdf->SetFont('Arial','',12);
$pdf->Cell(130,4,fecha($_GET["fecha"]),0,0,'C',0);
$pdf->SetY(30);
$pdf->Cell(130,4,'Hoja: 3',0,0,'C',0);
//hombres
$pdf->SetY(42);
$pdf->SetFont('Arial','',12);
$pdf->Cell(200,4,'02 MUJERES',0,0,'C',0);

$pdf->SetFillColor(057,044,138);
$pdf->SetFont('Arial','B',8);
$pdf->SetTextColor(255,255,255);
$pdf->SetY(48);
$pdf->SetX(6);
$pdf->Cell(10,8,utf8_decode('Dor'),1,0,'C',1);
$pdf->Cell(10,8,'Cama',1,0,'C',1);
$pdf->Cell(100,8,'Nombre',1,0,'C',1);
$pdf->Cell(15,8,'Registro',1,0,'C',1);
$pdf->Cell(10,8,utf8_decode('P/A'),1,0,'C',1);
$pdf->Cell(10,8,'Sexo',1,0,'C',1);
$pdf->Cell(47,8,'Observaciones',1,0,'C',1);

$y_axis_initial=48;
$y_initial=56;

//habitación 07
$ResCam7=mysqli_query($conn, "SELECT * FROM camas WHERE Habitacion='7' ORDER BY Cama ASC");
while($RResCam7=mysqli_fetch_array($ResCam7))
{
    //$ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE (Fecha='".$_GET["fecha"]."' OR Fecha='".date("Y-m-d",strtotime($_GET["fecha"]."- 1 days"))."') AND Cama='".$RResCam7["Id"]."' AND Estatus<2 AND Liberada='0'");
    $ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE Fecha='".$_GET["fecha"]."' AND Cama='".$RResCam7["Id"]."' AND Estatus<2 AND Liberada='0'");

    $res=mysqli_num_rows($ResRes);

    if($res>0 AND $_POST["camas"]<2) //cama ocupada   
    {
        $RResRes=mysqli_fetch_array($ResRes);
        if($RResRes["Tipo"]=='P')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos)AS Nombre, Sexo FROM pacientes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }
        elseif($RResRes["Tipo"]=='A')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos) AS Nombre, Sexo FROM acompannantes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }

        $print=1;
    }
    elseif($res==0 AND ($_POST["camas"]==2 OR $_POST["camas"]==0)) //cama vacia 
    {
        $ResNombre["Nombre"]='';
        $RResRes["Tipo"]='';
        $ResNombre["Id"]='';
        $ResNombre["Sexo"]='';

        $print=1;
    }

    if($RResCam7["Cama"]=='extra' AND $ResNombre["Nombre"]=='')
    {
        $print=0;
    }

    if($print==1)
    {
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','',8);
        $pdf->SetTextColor(000,000,000);
        $pdf->SetY($y_initial);
        $pdf->SetX(6);
        $pdf->Cell(10,10,'07',1,0,'C',1);
        $pdf->Cell(10,10,$RResCam7["Cama"],1,0,'C',1);
        $pdf->Cell(100,10,$ResNombre["Nombre"],1,0,'L',1);
        $pdf->Cell(15,10,$ResNombre["Id"],1,0,'C',1);
        $pdf->Cell(10,10,$RResRes["Tipo"],1,0,'C',1);
        $pdf->Cell(10,10,$ResNombre["Sexo"],1,0,'C',1);
        $pdf->Cell(47,10,'',1,0,'C',1);

        $y_initial=$y_initial+10;
    }

    
}

//habitación 08
$ResCam8=mysqli_query($conn, "SELECT * FROM camas WHERE Habitacion='8' ORDER BY Cama ASC");
while($RResCam8=mysqli_fetch_array($ResCam8))
{
    //$ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE (Fecha='".$_GET["fecha"]."' OR Fecha='".date("Y-m-d",strtotime($_GET["fecha"]."- 1 days"))."') AND Cama='".$RResCam8["Id"]."' AND Estatus<2 AND Liberada='0'");
    $ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE Fecha='".$_GET["fecha"]."' AND Cama='".$RResCam8["Id"]."' AND Estatus<2 AND Liberada='0'");

    $res=mysqli_num_rows($ResRes);

    if($res>0 AND $_POST["camas"]<2) //cama ocupada   
    {
        $RResRes=mysqli_fetch_array($ResRes);
        if($RResRes["Tipo"]=='P')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos)AS Nombre, Sexo FROM pacientes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }
        elseif($RResRes["Tipo"]=='A')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos) AS Nombre, Sexo FROM acompannantes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }

        $print=1;
    }
    elseif($res==0 AND ($_POST["camas"]==2 OR $_POST["camas"]==0)) //cama vacia 
    {
        $ResNombre["Nombre"]='';
        $RResRes["Tipo"]='';
        $ResNombre["Id"]='';
        $ResNombre["Sexo"]='';

        $print=1;
    }

    if($RResCam8["Cama"]=='extra' AND $ResNombre["Nombre"]=='')
    {
        $print=0;
    }

    if($print==1)
    {
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','',8);
        $pdf->SetTextColor(000,000,000);
        $pdf->SetY($y_initial);
        $pdf->SetX(6);
        $pdf->Cell(10,10,'08',1,0,'C',1);
        $pdf->Cell(10,10,$RResCam8["Cama"],1,0,'C',1);
        $pdf->Cell(100,10,$ResNombre["Nombre"],1,0,'L',1);
        $pdf->Cell(15,10,$ResNombre["Id"],1,0,'C',1);
        $pdf->Cell(10,10,$RResRes["Tipo"],1,0,'C',1);
        $pdf->Cell(10,10,$ResNombre["Sexo"],1,0,'C',1);
        $pdf->Cell(47,10,'',1,0,'C',1);

        $y_initial=$y_initial+10;
    }

    
}

//habitación 09
$ResCam9=mysqli_query($conn, "SELECT * FROM camas WHERE Habitacion='9' ORDER BY Cama ASC");
while($RResCam9=mysqli_fetch_array($ResCam9))
{
    //$ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE (Fecha='".$_GET["fecha"]."' OR Fecha='".date("Y-m-d",strtotime($_GET["fecha"]."- 1 days"))."') AND Cama='".$RResCam9["Id"]."' AND Estatus<2 AND Liberada='0'");
    $ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE Fecha='".$_GET["fecha"]."' AND Cama='".$RResCam9["Id"]."' AND Estatus<2 AND Liberada='0'");

    $res=mysqli_num_rows($ResRes);

    if($res>0 AND $_POST["camas"]<2) //cama ocupada   
    {
        $RResRes=mysqli_fetch_array($ResRes);
        if($RResRes["Tipo"]=='P')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos)AS Nombre, Sexo FROM pacientes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }
        elseif($RResRes["Tipo"]=='A')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos) AS Nombre, Sexo FROM acompannantes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }

        $print=1;
    }
    elseif($res==0 AND ($_POST["camas"]==2 OR $_POST["camas"]==0)) //cama vacia 
    {
        $ResNombre["Nombre"]='';
        $RResRes["Tipo"]='';
        $ResNombre["Id"]='';
        $ResNombre["Sexo"]='';

        $print=1;
    }

    if($RResCam9["Cama"]=='extra' AND $ResNombre["Nombre"]=='')
    {
        $print=0;
    }

    if($print==1)
    {
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','',8);
        $pdf->SetTextColor(000,000,000);
        $pdf->SetY($y_initial);
        $pdf->SetX(6);
        $pdf->Cell(10,10,'09',1,0,'C',1);
        $pdf->Cell(10,10,$RResCam9["Cama"],1,0,'C',1);
        $pdf->Cell(100,10,$ResNombre["Nombre"],1,0,'L',1);
        $pdf->Cell(15,10,$ResNombre["Id"],1,0,'C',1);
        $pdf->Cell(10,10,$RResRes["Tipo"],1,0,'C',1);
        $pdf->Cell(10,10,$ResNombre["Sexo"],1,0,'C',1);
        $pdf->Cell(47,10,'',1,0,'C',1);

        $y_initial=$y_initial+10;
    }

    
}

//habitación 11
$num=1;
$ResCam11=mysqli_query($conn, "SELECT * FROM camas WHERE Habitacion='11' ORDER BY Cama ASC");
while($RResCam11=mysqli_fetch_array($ResCam11))
{
    if($num==4)
    {
        //Hoja 4
        $pdf->AddPage();
        //logo 
        $pdf->Image('../images/logo.png',140,8,70);
        //titulo
        $pdf->SetY(16);
        $pdf->SetX(6);
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(130,4,'Control de Camas',0,0,'C',0);
        //
        $pdf->SetY(24);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(130,4,fecha($_GET["fecha"]),0,0,'C',0);
        $pdf->SetY(30);
        $pdf->Cell(130,4,'Hoja: 4',0,0,'C',0);
        //hombres
        $pdf->SetY(42);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(200,4,'02 MUJERES',0,0,'C',0);

        $pdf->SetFillColor(057,044,138);
        $pdf->SetFont('Arial','B',8);
        $pdf->SetTextColor(255,255,255);
        $pdf->SetY(48);
        $pdf->SetX(6);
        $pdf->Cell(10,8,utf8_decode('Dor'),1,0,'C',1);
        $pdf->Cell(10,8,'Cama',1,0,'C',1);
        $pdf->Cell(100,8,'Nombre',1,0,'C',1);
        $pdf->Cell(15,8,'Registro',1,0,'C',1);
        $pdf->Cell(10,8,utf8_decode('P/A'),1,0,'C',1);
        $pdf->Cell(10,8,'Sexo',1,0,'C',1);
        $pdf->Cell(47,8,'Observaciones',1,0,'C',1);

        $y_axis_initial=48;
        $y_initial=56;
    }
    //$ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE (Fecha='".$_GET["fecha"]."' OR Fecha='".date("Y-m-d",strtotime($_GET["fecha"]."- 1 days"))."') AND Cama='".$RResCam11["Id"]."' AND Estatus<2 AND Liberada='0'");
    $ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE Fecha='".$_GET["fecha"]."' AND Cama='".$RResCam11["Id"]."' AND Estatus<2 AND Liberada='0'");

    $res=mysqli_num_rows($ResRes);

    if($res>0 AND $_POST["camas"]<2) //cama ocupada   
    {
        $RResRes=mysqli_fetch_array($ResRes);
        if($RResRes["Tipo"]=='P')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos)AS Nombre, Sexo FROM pacientes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }
        elseif($RResRes["Tipo"]=='A')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos) AS Nombre, Sexo FROM acompannantes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }

        $print=1;
    }
    elseif($res==0 AND ($_POST["camas"]==2 OR $_POST["camas"]==0)) //cama vacia 
    {
        $ResNombre["Nombre"]='';
        $RResRes["Tipo"]='';
        $ResNombre["Id"]='';
        $ResNombre["Sexo"]='';

        $print=1;
    }

    if($RResCam11["Cama"]=='extra' AND $ResNombre["Nombre"]=='')
    {
        $print=0;
    }

    if($print==1)
    {
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','',8);
        $pdf->SetTextColor(000,000,000);
        $pdf->SetY($y_initial);
        $pdf->SetX(6);
        $pdf->Cell(10,10,'11',1,0,'C',1);
        $pdf->Cell(10,10,$RResCam11["Cama"],1,0,'C',1);
        $pdf->Cell(100,10,$ResNombre["Nombre"],1,0,'L',1);
        $pdf->Cell(15,10,$ResNombre["Id"],1,0,'C',1);
        $pdf->Cell(10,10,$RResRes["Tipo"],1,0,'C',1);
        $pdf->Cell(10,10,$ResNombre["Sexo"],1,0,'C',1);
        $pdf->Cell(47,10,'',1,0,'C',1);

        $y_initial=$y_initial+10;
    }

    
    $num++;
}

//habitación 12
$ResCam12=mysqli_query($conn, "SELECT * FROM camas WHERE Habitacion='12' ORDER BY Cama ASC");
while($RResCam12=mysqli_fetch_array($ResCam12))
{
    //$ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE (Fecha='".$_GET["fecha"]."' OR Fecha='".date("Y-m-d",strtotime($_GET["fecha"]."- 1 days"))."') AND Cama='".$RResCam12["Id"]."' AND Estatus<2 AND Liberada='0'");
    $ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE Fecha='".$_GET["fecha"]."' AND Cama='".$RResCam12["Id"]."' AND Estatus<2 AND Liberada='0'");

    $res=mysqli_num_rows($ResRes);

    if($res>0 AND $_POST["camas"]<2) //cama ocupada   
    {
        $RResRes=mysqli_fetch_array($ResRes);
        if($RResRes["Tipo"]=='P')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos)AS Nombre, Sexo FROM pacientes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }
        elseif($RResRes["Tipo"]=='A')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos) AS Nombre, Sexo FROM acompannantes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }

        $print=1;
    }
    elseif($res==0 AND ($_POST["camas"]==2 OR $_POST["camas"]==0)) //cama vacia 
    {
        $ResNombre["Nombre"]='';
        $RResRes["Tipo"]='';
        $ResNombre["Id"]='';
        $ResNombre["Sexo"]='';

        $print=1;
    }

    if($RResCam12["Cama"]=='extra' AND $ResNombre["Nombre"]=='')
    {
        $print=0;
    }

    if($print==1)
    {
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','',8);
        $pdf->SetTextColor(000,000,000);
        $pdf->SetY($y_initial);
        $pdf->SetX(6);
        $pdf->Cell(10,10,'12',1,0,'C',1);
        $pdf->Cell(10,10,$RResCam12["Cama"],1,0,'C',1);
        $pdf->Cell(100,10,$ResNombre["Nombre"],1,0,'L',1);
        $pdf->Cell(15,10,$ResNombre["Id"],1,0,'C',1);
        $pdf->Cell(10,10,$RResRes["Tipo"],1,0,'C',1);
        $pdf->Cell(10,10,$ResNombre["Sexo"],1,0,'C',1);
        $pdf->Cell(47,10,'',1,0,'C',1);

        $y_initial=$y_initial+10;
    }

    
    $num++;
}

//habitación 13
$ResCam13=mysqli_query($conn, "SELECT * FROM camas WHERE Habitacion='13' ORDER BY Cama ASC");
while($RResCam13=mysqli_fetch_array($ResCam13))
{
    //$ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE (Fecha='".$_GET["fecha"]."' OR Fecha='".date("Y-m-d",strtotime($_GET["fecha"]."- 1 days"))."') AND Cama='".$RResCam13["Id"]."' AND Estatus<2 AND Liberada='0'");
    $ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE Fecha='".$_GET["fecha"]."' AND Cama='".$RResCam13["Id"]."' AND Estatus<2 AND Liberada='0'");

    $res=mysqli_num_rows($ResRes);

    if($res>0 AND $_POST["camas"]<2) //cama ocupada   
    {
        $RResRes=mysqli_fetch_array($ResRes);
        if($RResRes["Tipo"]=='P')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos)AS Nombre, Sexo FROM pacientes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }
        elseif($RResRes["Tipo"]=='A')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos) AS Nombre, Sexo FROM acompannantes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }

        $print=1;
    }
    elseif($res==0 AND ($_POST["camas"]==2 OR $_POST["camas"]==0)) //cama vacia 
    {
        $ResNombre["Nombre"]='';
        $RResRes["Tipo"]='';
        $ResNombre["Id"]='';
        $ResNombre["Sexo"]='';

        $print=1;
    }

    if($RResCam13["Cama"]=='extra' AND $ResNombre["Nombre"]=='')
    {
        $print=0;
    }

    if($print==1)
    {
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','',8);
        $pdf->SetTextColor(000,000,000);
        $pdf->SetY($y_initial);
        $pdf->SetX(6);
        $pdf->Cell(10,10,'13',1,0,'C',1);
        $pdf->Cell(10,10,$RResCam13["Cama"],1,0,'C',1);
        $pdf->Cell(100,10,$ResNombre["Nombre"],1,0,'L',1);
        $pdf->Cell(15,10,$ResNombre["Id"],1,0,'C',1);
        $pdf->Cell(10,10,$RResRes["Tipo"],1,0,'C',1);
        $pdf->Cell(10,10,$ResNombre["Sexo"],1,0,'C',1);
        $pdf->Cell(47,10,'',1,0,'C',1);

        $y_initial=$y_initial+10;
    }

    
    $num++;
}

//habitación 14
$ResCam14=mysqli_query($conn, "SELECT * FROM camas WHERE Habitacion='14' ORDER BY Cama ASC");
while($RResCam14=mysqli_fetch_array($ResCam14))
{
    //$ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE (Fecha='".$_GET["fecha"]."' OR Fecha='".date("Y-m-d",strtotime($_GET["fecha"]."- 1 days"))."') AND Cama='".$RResCam14["Id"]."' AND Estatus<2 AND Liberada='0'");
    $ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE Fecha='".$_GET["fecha"]."' AND Cama='".$RResCam14["Id"]."' AND Estatus<2 AND Liberada='0'");

    $res=mysqli_num_rows($ResRes);

    if($res>0 AND $_POST["camas"]<2) //cama ocupada   
    {
        $RResRes=mysqli_fetch_array($ResRes);
        if($RResRes["Tipo"]=='P')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos)AS Nombre, Sexo FROM pacientes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }
        elseif($RResRes["Tipo"]=='A')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos) AS Nombre, Sexo FROM acompannantes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }

        $print=1;
    }
    elseif($res==0 AND ($_POST["camas"]==2 OR $_POST["camas"]==0)) //cama vacia 
    {
        $ResNombre["Nombre"]='';
        $RResRes["Tipo"]='';
        $ResNombre["Id"]='';
        $ResNombre["Sexo"]='';

        $print=1;
    }

    if($RResCam14["Cama"]=='extra' AND $ResNombre["Nombre"]=='')
    {
        $print=0;
    }

    if($print==1)
    {
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','',8);
        $pdf->SetTextColor(000,000,000);
        $pdf->SetY($y_initial);
        $pdf->SetX(6);
        $pdf->Cell(10,10,'14',1,0,'C',1);
        $pdf->Cell(10,10,$RResCam14["Cama"],1,0,'C',1);
        $pdf->Cell(100,10,$ResNombre["Nombre"],1,0,'L',1);
        $pdf->Cell(15,10,$ResNombre["Id"],1,0,'C',1);
        $pdf->Cell(10,10,$RResRes["Tipo"],1,0,'C',1);
        $pdf->Cell(10,10,$ResNombre["Sexo"],1,0,'C',1);
        $pdf->Cell(47,10,'',1,0,'C',1);

        $y_initial=$y_initial+10;
    }

    
    $num++;
}

//Hoja 5
$pdf->AddPage();
//logo 
$pdf->Image('../images/logo.png',140,8,70);
//titulo
$pdf->SetY(16);
$pdf->SetX(6);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(130,4,'Control de Camas',0,0,'C',0);
//
$pdf->SetY(24);
$pdf->SetFont('Arial','',12);
$pdf->Cell(130,4,fecha($_GET["fecha"]),0,0,'C',0);
$pdf->SetY(30);
$pdf->Cell(130,4,'Hoja: 5',0,0,'C',0);
//hombres
$pdf->SetY(42);
$pdf->SetFont('Arial','',12);
$pdf->Cell(200,4,'02 MUJERES',0,0,'C',0);

$pdf->SetFillColor(057,044,138);
$pdf->SetFont('Arial','B',8);
$pdf->SetTextColor(255,255,255);
$pdf->SetY(48);
$pdf->SetX(6);
$pdf->Cell(10,8,utf8_decode('Dor'),1,0,'C',1);
$pdf->Cell(10,8,'Cama',1,0,'C',1);
$pdf->Cell(100,8,'Nombre',1,0,'C',1);
$pdf->Cell(15,8,'Registro',1,0,'C',1);
$pdf->Cell(10,8,utf8_decode('P/A'),1,0,'C',1);
$pdf->Cell(10,8,'Sexo',1,0,'C',1);
$pdf->Cell(47,8,'Observaciones',1,0,'C',1);

$y_axis_initial=48;
$y_initial=56;

//habitación 15
$ResCam15=mysqli_query($conn, "SELECT * FROM camas WHERE Habitacion='15' ORDER BY Cama ASC");
while($RResCam15=mysqli_fetch_array($ResCam15))
{
    //$ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE (Fecha='".$_GET["fecha"]."' OR Fecha='".date("Y-m-d",strtotime($_GET["fecha"]."- 1 days"))."') AND Cama='".$RResCam15["Id"]."' AND Estatus<2 AND Liberada='0'");
    $ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE Fecha='".$_GET["fecha"]."' AND Cama='".$RResCam15["Id"]."' AND Estatus<2 AND Liberada='0'");

    $res=mysqli_num_rows($ResRes);

    if($res>0 AND $_POST["camas"]<2) //cama ocupada   
    {
        $RResRes=mysqli_fetch_array($ResRes);
        if($RResRes["Tipo"]=='P')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos)AS Nombre, Sexo FROM pacientes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }
        elseif($RResRes["Tipo"]=='A')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos) AS Nombre, Sexo FROM acompannantes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }

        $print=1;
    }
    elseif($res==0 AND ($_POST["camas"]==2 OR $_POST["camas"]==0)) //cama vacia 
    {
        $ResNombre["Nombre"]='';
        $RResRes["Tipo"]='';
        $ResNombre["Id"]='';
        $ResNombre["Sexo"]='';

        $print=1;
    }

    if($RResCam15["Cama"]=='extra' AND $ResNombre["Nombre"]=='')
    {
        $print=0;
    }

    if($print==1)
    {
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','',8);
        $pdf->SetTextColor(000,000,000);
        $pdf->SetY($y_initial);
        $pdf->SetX(6);
        $pdf->Cell(10,10,'15',1,0,'C',1);
        $pdf->Cell(10,10,$RResCam15["Cama"],1,0,'C',1);
        $pdf->Cell(100,10,$ResNombre["Nombre"],1,0,'L',1);
        $pdf->Cell(15,10,$ResNombre["Id"],1,0,'C',1);
        $pdf->Cell(10,10,$RResRes["Tipo"],1,0,'C',1);
        $pdf->Cell(10,10,$ResNombre["Sexo"],1,0,'C',1);
        $pdf->Cell(47,10,'',1,0,'C',1);

        $y_initial=$y_initial+10;
    }

    
    $num++;
}

//habitación 16
$ResCam16=mysqli_query($conn, "SELECT * FROM camas WHERE Habitacion='16' ORDER BY Cama ASC");
while($RResCam16=mysqli_fetch_array($ResCam16))
{
    //$ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE (Fecha='".$_GET["fecha"]."' OR Fecha='".date("Y-m-d",strtotime($_GET["fecha"]."- 1 days"))."') AND Cama='".$RResCam16["Id"]."' AND Estatus<2 AND Liberada='0'");
    $ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE Fecha='".$_GET["fecha"]."' AND Cama='".$RResCam16["Id"]."' AND Estatus<2 AND Liberada='0'");

    $res=mysqli_num_rows($ResRes);

    if($res>0 AND $_POST["camas"]<2) //cama ocupada   
    {
        $RResRes=mysqli_fetch_array($ResRes);
        if($RResRes["Tipo"]=='P')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos)AS Nombre, Sexo FROM pacientes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }
        elseif($RResRes["Tipo"]=='A')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos) AS Nombre, Sexo FROM acompannantes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }

        $print=1;
    }
    elseif($res==0 AND ($_POST["camas"]==2 OR $_POST["camas"]==0)) //cama vacia 
    {
        $ResNombre["Nombre"]='';
        $RResRes["Tipo"]='';
        $ResNombre["Id"]='';
        $ResNombre["Sexo"]='';

        $print=1;
    }

    if($RResCam16["Cama"]=='extra' AND $ResNombre["Nombre"]=='')
    {
        $print=0;
    }

    if($print==1)
    {
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','',8);
        $pdf->SetTextColor(000,000,000);
        $pdf->SetY($y_initial);
        $pdf->SetX(6);
        $pdf->Cell(10,10,'16',1,0,'C',1);
        $pdf->Cell(10,10,$RResCam16["Cama"],1,0,'C',1);
        $pdf->Cell(100,10,$ResNombre["Nombre"],1,0,'L',1);
        $pdf->Cell(15,10,$ResNombre["Id"],1,0,'C',1);
        $pdf->Cell(10,10,$RResRes["Tipo"],1,0,'C',1);
        $pdf->Cell(10,10,$ResNombre["Sexo"],1,0,'C',1);
        $pdf->Cell(47,10,'',1,0,'C',1);

        $y_initial=$y_initial+10;
    }

    
    $num++;
}

//habitación 17
$ResCam17=mysqli_query($conn, "SELECT * FROM camas WHERE Habitacion='17' ORDER BY Cama ASC");
while($RResCam17=mysqli_fetch_array($ResCam17))
{
    //$ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE (Fecha='".$_GET["fecha"]."' OR Fecha='".date("Y-m-d",strtotime($_GET["fecha"]."- 1 days"))."') AND Cama='".$RResCam17["Id"]."' AND Estatus<2 AND Liberada='0'");
    $ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE Fecha='".$_GET["fecha"]."' AND Cama='".$RResCam17["Id"]."' AND Estatus<2 AND Liberada='0'");

    $res=mysqli_num_rows($ResRes);

    if($res>0 AND $_POST["camas"]<2) //cama ocupada   
    {
        $RResRes=mysqli_fetch_array($ResRes);
        if($RResRes["Tipo"]=='P')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos)AS Nombre, Sexo FROM pacientes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }
        elseif($RResRes["Tipo"]=='A')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos) AS Nombre, Sexo FROM acompannantes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }

        $print=1;
    }
    elseif($res==0 AND ($_POST["camas"]==2 OR $_POST["camas"]==0)) //cama vacia 
    {
        $ResNombre["Nombre"]='';
        $RResRes["Tipo"]='';
        $ResNombre["Id"]='';
        $ResNombre["Sexo"]='';

        $print=1;
    }

    if($RResCam17["Cama"]=='extra' AND $ResNombre["Nombre"]=='')
    {
        $print=0;
    }

    if($print==1)
    {
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','',8);
        $pdf->SetTextColor(000,000,000);
        $pdf->SetY($y_initial);
        $pdf->SetX(6);
        $pdf->Cell(10,10,'17',1,0,'C',1);
        $pdf->Cell(10,10,$RResCam17["Cama"],1,0,'C',1);
        $pdf->Cell(100,10,$ResNombre["Nombre"],1,0,'L',1);
        $pdf->Cell(15,10,$ResNombre["Id"],1,0,'C',1);
        $pdf->Cell(10,10,$RResRes["Tipo"],1,0,'C',1);
        $pdf->Cell(10,10,$ResNombre["Sexo"],1,0,'C',1);
        $pdf->Cell(47,10,'',1,0,'C',1);

        $y_initial=$y_initial+10;
    }

    
    $num++;
}

//habitación 18
$num=1;
$ResCam18=mysqli_query($conn, "SELECT * FROM camas WHERE Habitacion='18' ORDER BY Cama ASC");
while($RResCam18=mysqli_fetch_array($ResCam18))
{
    if($num==5)
    {
        //Hoja 6
        $pdf->AddPage();
        //logo 
        $pdf->Image('../images/logo.png',140,8,70);
        //titulo
        $pdf->SetY(16);
        $pdf->SetX(6);
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(130,4,'Control de Camas',0,0,'C',0);
        //
        $pdf->SetY(24);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(130,4,fecha($_GET["fecha"]),0,0,'C',0);
        $pdf->SetY(30);
        $pdf->Cell(130,4,'Hoja: 6',0,0,'C',0);
        //hombres
        $pdf->SetY(42);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(200,4,'02 MUJERES',0,0,'C',0);

        $pdf->SetFillColor(057,044,138);
        $pdf->SetFont('Arial','B',8);
        $pdf->SetTextColor(255,255,255);
        $pdf->SetY(48);
        $pdf->SetX(6);
        $pdf->Cell(10,8,utf8_decode('Dor'),1,0,'C',1);
        $pdf->Cell(10,8,'Cama',1,0,'C',1);
        $pdf->Cell(100,8,'Nombre',1,0,'C',1);
        $pdf->Cell(15,8,'Registro',1,0,'C',1);
        $pdf->Cell(10,8,utf8_decode('P/A'),1,0,'C',1);
        $pdf->Cell(10,8,'Sexo',1,0,'C',1);
        $pdf->Cell(47,8,'Observaciones',1,0,'C',1);

        $y_axis_initial=48;
        $y_initial=56;
    }
    //$ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE (Fecha='".$_GET["fecha"]."' OR Fecha='".date("Y-m-d",strtotime($_GET["fecha"]."- 1 days"))."') AND Cama='".$RResCam18["Id"]."' AND Estatus<2 AND Liberada='0'");
    $ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE Fecha='".$_GET["fecha"]."' AND Cama='".$RResCam18["Id"]."' AND Estatus<2 AND Liberada='0'");

    $res=mysqli_num_rows($ResRes);

    if($res>0 AND $_POST["camas"]<2) //cama ocupada   
    {
        $RResRes=mysqli_fetch_array($ResRes);
        if($RResRes["Tipo"]=='P')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos)AS Nombre, Sexo FROM pacientes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }
        elseif($RResRes["Tipo"]=='A')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos) AS Nombre, Sexo FROM acompannantes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }

        $print=1;
    }
    elseif($res==0 AND ($_POST["camas"]==2 OR $_POST["camas"]==0)) //cama vacia 
    {
        $ResNombre["Nombre"]='';
        $RResRes["Tipo"]='';
        $ResNombre["Id"]='';
        $ResNombre["Sexo"]='';

        $print=1;
    }

    if($RResCam18["Cama"]=='extra' AND $ResNombre["Nombre"]=='')
    {
        $print=0;
    }

    if($print==1)
    {
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','',8);
        $pdf->SetTextColor(000,000,000);
        $pdf->SetY($y_initial);
        $pdf->SetX(6);
        $pdf->Cell(10,10,'18',1,0,'C',1);
        $pdf->Cell(10,10,$RResCam18["Cama"],1,0,'C',1);
        $pdf->Cell(100,10,$ResNombre["Nombre"],1,0,'L',1);
        $pdf->Cell(15,10,$ResNombre["Id"],1,0,'C',1);
        $pdf->Cell(10,10,$RResRes["Tipo"],1,0,'C',1);
        $pdf->Cell(10,10,$ResNombre["Sexo"],1,0,'C',1);
        $pdf->Cell(47,10,'',1,0,'C',1);

        $y_initial=$y_initial+10;
    }

    
    $num++;
}

//habitación 31
$ResCam31=mysqli_query($conn, "SELECT * FROM camas WHERE Habitacion='31' ORDER BY Cama ASC");
while($RResCam31=mysqli_fetch_array($ResCam31))
{
    //$ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE (Fecha='".$_GET["fecha"]."' OR Fecha='".date("Y-m-d",strtotime($_GET["fecha"]."- 1 days"))."') AND Cama='".$RResCam31["Id"]."' AND Estatus<2 AND Liberada='0'");
    $ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE Fecha='".$_GET["fecha"]."' AND Cama='".$RResCam31["Id"]."' AND Estatus<2 AND Liberada='0'");

    $res=mysqli_num_rows($ResRes);

    if($res>0 AND $_POST["camas"]<2) //cama ocupada   
    {
        $RResRes=mysqli_fetch_array($ResRes);
        if($RResRes["Tipo"]=='P')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos)AS Nombre, Sexo FROM pacientes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }
        elseif($RResRes["Tipo"]=='A')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos) AS Nombre, Sexo FROM acompannantes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }

        $print=1;
    }
    elseif($res==0 AND ($_POST["camas"]==2 OR $_POST["camas"]==0)) //cama vacia 
    {
        $ResNombre["Nombre"]='';
        $RResRes["Tipo"]='';
        $ResNombre["Id"]='';
        $ResNombre["Sexo"]='';

        $print=1;
    }

    if($RResCam31["Cama"]=='extra' AND $ResNombre["Nombre"]=='')
    {
        $print=0;
    }

    if($print==1)
    {
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','',8);
        $pdf->SetTextColor(000,000,000);
        $pdf->SetY($y_initial);
        $pdf->SetX(6);
        $pdf->Cell(10,10,'31',1,0,'C',1);
        $pdf->Cell(10,10,$RResCam31["Cama"],1,0,'C',1);
        $pdf->Cell(100,10,$ResNombre["Nombre"],1,0,'L',1);
        $pdf->Cell(15,10,$ResNombre["Id"],1,0,'C',1);
        $pdf->Cell(10,10,$RResRes["Tipo"],1,0,'C',1);
        $pdf->Cell(10,10,$ResNombre["Sexo"],1,0,'C',1);
        $pdf->Cell(47,10,'',1,0,'C',1);

        $y_initial=$y_initial+10;
    }

    
    $num++;
}

//Hoja 7
$pdf->AddPage();
//logo 
$pdf->Image('../images/logo.png',140,8,70);
//titulo
$pdf->SetY(16);
$pdf->SetX(6);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(130,4,'Control de Camas',0,0,'C',0);
//
$pdf->SetY(24);
$pdf->SetFont('Arial','',12);
$pdf->Cell(130,4,fecha($_GET["fecha"]),0,0,'C',0);
$pdf->SetY(30);
$pdf->Cell(130,4,'Hoja: 7',0,0,'C',0);
//hombres
$pdf->SetY(42);
$pdf->SetFont('Arial','',12);
$pdf->Cell(200,4,'03 CUIDADOS ESPECIALES',0,0,'C',0);

$pdf->SetFillColor(057,044,138);
$pdf->SetFont('Arial','B',8);
$pdf->SetTextColor(255,255,255);
$pdf->SetY(48);
$pdf->SetX(6);
$pdf->Cell(10,8,utf8_decode('Dor'),1,0,'C',1);
$pdf->Cell(10,8,'Cama',1,0,'C',1);
$pdf->Cell(100,8,'Nombre',1,0,'C',1);
$pdf->Cell(15,8,'Registro',1,0,'C',1);
$pdf->Cell(10,8,utf8_decode('P/A'),1,0,'C',1);
$pdf->Cell(10,8,'Sexo',1,0,'C',1);
$pdf->Cell(47,8,'Observaciones',1,0,'C',1);

$y_axis_initial=48;
$y_initial=56;

//habitación 01
$ResCam1=mysqli_query($conn, "SELECT * FROM camas WHERE Habitacion='1' ORDER BY Cama ASC");
while($RResCam1=mysqli_fetch_array($ResCam1))
{
    //$ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE (Fecha='".$_GET["fecha"]."' OR Fecha='".date("Y-m-d",strtotime($_GET["fecha"]."- 1 days"))."') AND Cama='".$RResCam1["Id"]."' AND Estatus<2 AND Liberada='0'");
    $ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE Fecha='".$_GET["fecha"]."' AND Cama='".$RResCam1["Id"]."' AND Estatus<2 AND Liberada='0'");

    $res=mysqli_num_rows($ResRes);

    if($res>0 AND $_POST["camas"]<2) //cama ocupada   
    {
        $RResRes=mysqli_fetch_array($ResRes);
        if($RResRes["Tipo"]=='P')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos)AS Nombre, Sexo FROM pacientes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }
        elseif($RResRes["Tipo"]=='A')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos) AS Nombre, Sexo FROM acompannantes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }

        $print=1;
    }
    elseif($res==0 AND ($_POST["camas"]==2 OR $_POST["camas"]==0)) //cama vacia 
    {
        $ResNombre["Nombre"]='';
        $RResRes["Tipo"]='';
        $ResNombre["Id"]='';
        $ResNombre["Sexo"]='';

        $print=1;
    }

    if($RResCam1["Cama"]=='extra' AND $ResNombre["Nombre"]=='')
    {
        $print=0;
    }

    if($print==1)
    {
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','',8);
        $pdf->SetTextColor(000,000,000);
        $pdf->SetY($y_initial);
        $pdf->SetX(6);
        $pdf->Cell(10,10,'01',1,0,'C',1);
        $pdf->Cell(10,10,$RResCam1["Cama"],1,0,'C',1);
        $pdf->Cell(100,10,$ResNombre["Nombre"],1,0,'L',1);
        $pdf->Cell(15,10,$ResNombre["Id"],1,0,'C',1);
        $pdf->Cell(10,10,$RResRes["Tipo"],1,0,'C',1);
        $pdf->Cell(10,10,$ResNombre["Sexo"],1,0,'C',1);
        $pdf->Cell(47,10,'',1,0,'C',1);

        $y_initial=$y_initial+10;
    }

    
    $num++;
}

//habitación 10
$ResCam10=mysqli_query($conn, "SELECT * FROM camas WHERE Habitacion='10' ORDER BY Cama ASC");
while($RResCam10=mysqli_fetch_array($ResCam10))
{
    //$ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE (Fecha='".$_GET["fecha"]."' OR Fecha='".date("Y-m-d",strtotime($_GET["fecha"]."- 1 days"))."') AND Cama='".$RResCam10["Id"]."' AND Estatus<2 AND Liberada='0'");
    $ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE Fecha='".$_GET["fecha"]."' AND Cama='".$RResCam10["Id"]."' AND Estatus<2 AND Liberada='0'");

    $res=mysqli_num_rows($ResRes);

    if($res>0 AND $_POST["camas"]<2) //cama ocupada   
    {
        $RResRes=mysqli_fetch_array($ResRes);
        if($RResRes["Tipo"]=='P')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos)AS Nombre, Sexo FROM pacientes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }
        elseif($RResRes["Tipo"]=='A')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos) AS Nombre, Sexo FROM acompannantes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }

        $print=1;
    }
    elseif($res==0 AND ($_POST["camas"]==2 OR $_POST["camas"]==0)) //cama vacia 
    {
        $ResNombre["Nombre"]='';
        $RResRes["Tipo"]='';
        $ResNombre["Id"]='';
        $ResNombre["Sexo"]='';

        $print=1;
    }

    if($RResCam10["Cama"]=='extra' AND $ResNombre["Nombre"]=='')
    {
        $print=0;
    }

    if($print==1)
    {
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','',8);
        $pdf->SetTextColor(000,000,000);
        $pdf->SetY($y_initial);
        $pdf->SetX(6);
        $pdf->Cell(10,10,'10',1,0,'C',1);
        $pdf->Cell(10,10,$RResCam10["Cama"],1,0,'C',1);
        $pdf->Cell(100,10,$ResNombre["Nombre"],1,0,'L',1);
        $pdf->Cell(15,10,$ResNombre["Id"],1,0,'C',1);
        $pdf->Cell(10,10,$RResRes["Tipo"],1,0,'C',1);
        $pdf->Cell(10,10,$ResNombre["Sexo"],1,0,'C',1);
        $pdf->Cell(47,10,'',1,0,'C',1);

        $y_initial=$y_initial+10;
    }

    
    $num++;
}

//habitación 19
$ResCam19=mysqli_query($conn, "SELECT * FROM camas WHERE Habitacion='19' ORDER BY Cama ASC");
while($RResCam19=mysqli_fetch_array($ResCam19))
{
    //$ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE (Fecha='".$_GET["fecha"]."' OR Fecha='".date("Y-m-d",strtotime($_GET["fecha"]."- 1 days"))."') AND Cama='".$RResCam19["Id"]."' AND Estatus<2 AND Liberada='0'");
    $ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE Fecha='".$_GET["fecha"]."' AND Cama='".$RResCam19["Id"]."' AND Estatus<2 AND Liberada='0'");

    $res=mysqli_num_rows($ResRes);

    if($res>0 AND $_POST["camas"]<2) //cama ocupada   
    {
        $RResRes=mysqli_fetch_array($ResRes);
        if($RResRes["Tipo"]=='P')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos)AS Nombre, Sexo FROM pacientes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }
        elseif($RResRes["Tipo"]=='A')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos) AS Nombre, Sexo FROM acompannantes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }

        $print=1;
    }
    elseif($res==0 AND ($_POST["camas"]==2 OR $_POST["camas"]==0)) //cama vacia 
    {
        $ResNombre["Nombre"]='';
        $RResRes["Tipo"]='';
        $ResNombre["Id"]='';
        $ResNombre["Sexo"]='';

        $print=1;
    }

    if($RResCam19["Cama"]=='extra' AND $ResNombre["Nombre"]=='')
    {
        $print=0;
    }

    if($print==1)
    {
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','',8);
        $pdf->SetTextColor(000,000,000);
        $pdf->SetY($y_initial);
        $pdf->SetX(6);
        $pdf->Cell(10,10,'19',1,0,'C',1);
        $pdf->Cell(10,10,$RResCam19["Cama"],1,0,'C',1);
        $pdf->Cell(100,10,$ResNombre["Nombre"],1,0,'L',1);
        $pdf->Cell(15,10,$ResNombre["Id"],1,0,'C',1);
        $pdf->Cell(10,10,$RResRes["Tipo"],1,0,'C',1);
        $pdf->Cell(10,10,$ResNombre["Sexo"],1,0,'C',1);
        $pdf->Cell(47,10,'',1,0,'C',1);

        $y_initial=$y_initial+10;
    }

    
    $num++;
}

//habitación 20
$ResCam20=mysqli_query($conn, "SELECT * FROM camas WHERE Habitacion='20' ORDER BY Cama ASC");
while($RResCam20=mysqli_fetch_array($ResCam20))
{
    //$ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE (Fecha='".$_GET["fecha"]."' OR Fecha='".date("Y-m-d",strtotime($_GET["fecha"]."- 1 days"))."') AND Cama='".$RResCam20["Id"]."' AND Estatus<2 AND Liberada='0'");
    $ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE Fecha='".$_GET["fecha"]."' AND Cama='".$RResCam20["Id"]."' AND Estatus<2 AND Liberada='0'");

    $res=mysqli_num_rows($ResRes);

    if($res>0 AND $_POST["camas"]<2) //cama ocupada   
    {
        $RResRes=mysqli_fetch_array($ResRes);
        if($RResRes["Tipo"]=='P')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos)AS Nombre, Sexo FROM pacientes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }
        elseif($RResRes["Tipo"]=='A')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos) AS Nombre, Sexo FROM acompannantes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }

        $print=1;
    }
    elseif($res==0 AND ($_POST["camas"]==2 OR $_POST["camas"]==0)) //cama vacia 
    {
        $ResNombre["Nombre"]='';
        $RResRes["Tipo"]='';
        $ResNombre["Id"]='';
        $ResNombre["Sexo"]='';

        $print=1;
    }

    if($RResCam20["Cama"]=='extra' AND $ResNombre["Nombre"]=='')
    {
        $print=0;
    }

    if($print==1)
    {
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','',8);
        $pdf->SetTextColor(000,000,000);
        $pdf->SetY($y_initial);
        $pdf->SetX(6);
        $pdf->Cell(10,10,'20',1,0,'C',1);
        $pdf->Cell(10,10,$RResCam20["Cama"],1,0,'C',1);
        $pdf->Cell(100,10,$ResNombre["Nombre"],1,0,'L',1);
        $pdf->Cell(15,10,$ResNombre["Id"],1,0,'C',1);
        $pdf->Cell(10,10,$RResRes["Tipo"],1,0,'C',1);
        $pdf->Cell(10,10,$ResNombre["Sexo"],1,0,'C',1);
        $pdf->Cell(47,10,'',1,0,'C',1);

        $y_initial=$y_initial+10;
    }

    
    $num++;
}

//habitación 21
$ResCam21=mysqli_query($conn, "SELECT * FROM camas WHERE Habitacion='21' ORDER BY Cama ASC");
while($RResCam21=mysqli_fetch_array($ResCam21))
{
    //$ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE (Fecha='".$_GET["fecha"]."' OR Fecha='".date("Y-m-d",strtotime($_GET["fecha"]."- 1 days"))."') AND Cama='".$RResCam21["Id"]."' AND Estatus<2 AND Liberada='0'");
    $ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE Fecha='".$_GET["fecha"]."' AND Cama='".$RResCam21["Id"]."' AND Estatus<2 AND Liberada='0'");

    $res=mysqli_num_rows($ResRes);

    if($res>0 AND $_POST["camas"]<2) //cama ocupada   
    {
        $RResRes=mysqli_fetch_array($ResRes);
        if($RResRes["Tipo"]=='P')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos)AS Nombre, Sexo FROM pacientes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }
        elseif($RResRes["Tipo"]=='A')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos) AS Nombre, Sexo FROM acompannantes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }

        $print=1;
    }
    elseif($res==0 AND ($_POST["camas"]==2 OR $_POST["camas"]==0)) //cama vacia 
    {
        $ResNombre["Nombre"]='';
        $RResRes["Tipo"]='';
        $ResNombre["Id"]='';
        $ResNombre["Sexo"]='';

        $print=1;
    }

    if($RResCam21["Cama"]=='extra' AND $ResNombre["Nombre"]=='')
    {
        $print=0;
    }

    if($print==1)
    {
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','',8);
        $pdf->SetTextColor(000,000,000);
        $pdf->SetY($y_initial);
        $pdf->SetX(6);
        $pdf->Cell(10,10,'21',1,0,'C',1);
        $pdf->Cell(10,10,$RResCam21["Cama"],1,0,'C',1);
        $pdf->Cell(100,10,$ResNombre["Nombre"],1,0,'L',1);
        $pdf->Cell(15,10,$ResNombre["Id"],1,0,'C',1);
        $pdf->Cell(10,10,$RResRes["Tipo"],1,0,'C',1);
        $pdf->Cell(10,10,$ResNombre["Sexo"],1,0,'C',1);
        $pdf->Cell(47,10,'',1,0,'C',1);

        $y_initial=$y_initial+10;
    }

    
    $num++;
}

//habitación 22
$ResCam22=mysqli_query($conn, "SELECT * FROM camas WHERE Habitacion='22' ORDER BY Cama ASC");
while($RResCam22=mysqli_fetch_array($ResCam22))
{
    //$ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE (Fecha='".$_GET["fecha"]."' OR Fecha='".date("Y-m-d",strtotime($_GET["fecha"]."- 1 days"))."') AND Cama='".$RResCam22["Id"]."' AND Estatus<2 AND Liberada='0'");
    $ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE Fecha='".$_GET["fecha"]."' AND Cama='".$RResCam22["Id"]."' AND Estatus<2 AND Liberada='0'");

    $res=mysqli_num_rows($ResRes);

    if($res>0 AND $_POST["camas"]<2) //cama ocupada   
    {
        $RResRes=mysqli_fetch_array($ResRes);
        if($RResRes["Tipo"]=='P')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos)AS Nombre, Sexo FROM pacientes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }
        elseif($RResRes["Tipo"]=='A')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos) AS Nombre, Sexo FROM acompannantes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }

        $print=1;
    }
    elseif($res==0 AND ($_POST["camas"]==2 OR $_POST["camas"]==0)) //cama vacia 
    {
        $ResNombre["Nombre"]='';
        $RResRes["Tipo"]='';
        $ResNombre["Id"]='';
        $ResNombre["Sexo"]='';

        $print=1;
    }

    if($RResCam22["Cama"]=='extra' AND $ResNombre["Nombre"]=='')
    {
        $print=0;
    }

    if($print==1)
    {
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','',8);
        $pdf->SetTextColor(000,000,000);
        $pdf->SetY($y_initial);
        $pdf->SetX(6);
        $pdf->Cell(10,10,'22',1,0,'C',1);
        $pdf->Cell(10,10,$RResCam22["Cama"],1,0,'C',1);
        $pdf->Cell(100,10,$ResNombre["Nombre"],1,0,'L',1);
        $pdf->Cell(15,10,$ResNombre["Id"],1,0,'C',1);
        $pdf->Cell(10,10,$RResRes["Tipo"],1,0,'C',1);
        $pdf->Cell(10,10,$ResNombre["Sexo"],1,0,'C',1);
        $pdf->Cell(47,10,'',1,0,'C',1);

        $y_initial=$y_initial+10;
    }

    
    $num++;
}

//habitación 23
$ResCam23=mysqli_query($conn, "SELECT * FROM camas WHERE Habitacion='23' ORDER BY Cama ASC");
while($RResCam23=mysqli_fetch_array($ResCam23))
{
    //$ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE (Fecha='".$_GET["fecha"]."' OR Fecha='".date("Y-m-d",strtotime($_GET["fecha"]."- 1 days"))."') AND Cama='".$RResCam23["Id"]."' AND Estatus<2 AND Liberada='0'");
    $ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE Fecha='".$_GET["fecha"]."' AND Cama='".$RResCam23["Id"]."' AND Estatus<2 AND Liberada='0'");

    $res=mysqli_num_rows($ResRes);

    if($res>0 AND $_POST["camas"]<2) //cama ocupada   
    {
        $RResRes=mysqli_fetch_array($ResRes);
        if($RResRes["Tipo"]=='P')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos)AS Nombre, Sexo FROM pacientes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }
        elseif($RResRes["Tipo"]=='A')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos) AS Nombre, Sexo FROM acompannantes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }

        $print=1;
    }
    elseif($res==0 AND ($_POST["camas"]==2 OR $_POST["camas"]==0)) //cama vacia 
    {
        $ResNombre["Nombre"]='';
        $RResRes["Tipo"]='';
        $ResNombre["Id"]='';
        $ResNombre["Sexo"]='';

        $print=1;
    }

    if($RResCam23["Cama"]=='extra' AND $ResNombre["Nombre"]=='')
    {
        $print=0;
    }

    if($print==1)
    {
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','',8);
        $pdf->SetTextColor(000,000,000);
        $pdf->SetY($y_initial);
        $pdf->SetX(6);
        $pdf->Cell(10,10,'23',1,0,'C',1);
        $pdf->Cell(10,10,$RResCam23["Cama"],1,0,'C',1);
        $pdf->Cell(100,10,$ResNombre["Nombre"],1,0,'L',1);
        $pdf->Cell(15,10,$ResNombre["Id"],1,0,'C',1);
        $pdf->Cell(10,10,$RResRes["Tipo"],1,0,'C',1);
        $pdf->Cell(10,10,$ResNombre["Sexo"],1,0,'C',1);
        $pdf->Cell(47,10,'',1,0,'C',1);

        $y_initial=$y_initial+10;
    }

    
    $num++;
}

//habitación 25
$ResCam25=mysqli_query($conn, "SELECT * FROM camas WHERE Habitacion='25' ORDER BY Cama ASC");
while($RResCam25=mysqli_fetch_array($ResCam25))
{
    //$ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE (Fecha='".$_GET["fecha"]."' OR Fecha='".date("Y-m-d",strtotime($_GET["fecha"]."- 1 days"))."') AND Cama='".$RResCam25["Id"]."' AND Estatus<2 AND Liberada='0'");
    $ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE Fecha='".$_GET["fecha"]."' AND Cama='".$RResCam25["Id"]."' AND Estatus<2 AND Liberada='0'");

    $res=mysqli_num_rows($ResRes);

    if($res>0 AND $_POST["camas"]<2) //cama ocupada   
    {
        $RResRes=mysqli_fetch_array($ResRes);
        if($RResRes["Tipo"]=='P')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos)AS Nombre, Sexo FROM pacientes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }
        elseif($RResRes["Tipo"]=='A')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos) AS Nombre, Sexo FROM acompannantes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }

        $print=1;
    }
    elseif($res==0 AND ($_POST["camas"]==2 OR $_POST["camas"]==0)) //cama vacia 
    {
        $ResNombre["Nombre"]='';
        $RResRes["Tipo"]='';
        $ResNombre["Id"]='';
        $ResNombre["Sexo"]='';

        $print=1;
    }

    if($RResCam25["Cama"]=='extra' AND $ResNombre["Nombre"]=='')
    {
        $print=0;
    }

    if($print==1)
    {
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','',8);
        $pdf->SetTextColor(000,000,000);
        $pdf->SetY($y_initial);
        $pdf->SetX(6);
        $pdf->Cell(10,10,'25',1,0,'C',1);
        $pdf->Cell(10,10,$RResCam25["Cama"],1,0,'C',1);
        $pdf->Cell(100,10,$ResNombre["Nombre"],1,0,'L',1);
        $pdf->Cell(15,10,$ResNombre["Id"],1,0,'C',1);
        $pdf->Cell(10,10,$RResRes["Tipo"],1,0,'C',1);
        $pdf->Cell(10,10,$ResNombre["Sexo"],1,0,'C',1);
        $pdf->Cell(47,10,'',1,0,'C',1);

        $y_initial=$y_initial+10;
    }

    
    $num++;
}
//habitación 26
$ResCam26=mysqli_query($conn, "SELECT * FROM camas WHERE Habitacion='26' ORDER BY Cama ASC");
while($RResCam26=mysqli_fetch_array($ResCam26))
{
    //$ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE (Fecha='".$_GET["fecha"]."' OR Fecha='".date("Y-m-d",strtotime($_GET["fecha"]."- 1 days"))."') AND Cama='".$RResCam26["Id"]."' AND Estatus<2 AND Liberada='0'");
    $ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE Fecha='".$_GET["fecha"]."' AND Cama='".$RResCam26["Id"]."' AND Estatus<2 AND Liberada='0'");

    $res=mysqli_num_rows($ResRes);

    if($res>0 AND $_POST["camas"]<2) //cama ocupada   
    {
        $RResRes=mysqli_fetch_array($ResRes);
        if($RResRes["Tipo"]=='P')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos)AS Nombre, Sexo FROM pacientes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }
        elseif($RResRes["Tipo"]=='A')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos) AS Nombre, Sexo FROM acompannantes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }

        $print=1;
    }
    elseif($res==0 AND ($_POST["camas"]==2 OR $_POST["camas"]==0)) //cama vacia 
    {
        $ResNombre["Nombre"]='';
        $RResRes["Tipo"]='';
        $ResNombre["Id"]='';
        $ResNombre["Sexo"]='';

        $print=1;
    }

    if($RResCam26["Cama"]=='extra' AND $ResNombre["Nombre"]=='')
    {
        $print=0;
    }

    if($print==1)
    {
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','',8);
        $pdf->SetTextColor(000,000,000);
        $pdf->SetY($y_initial);
        $pdf->SetX(6);
        $pdf->Cell(10,10,'26',1,0,'C',1);
        $pdf->Cell(10,10,$RResCam26["Cama"],1,0,'C',1);
        $pdf->Cell(100,10,$ResNombre["Nombre"],1,0,'L',1);
        $pdf->Cell(15,10,$ResNombre["Id"],1,0,'C',1);
        $pdf->Cell(10,10,$RResRes["Tipo"],1,0,'C',1);
        $pdf->Cell(10,10,$ResNombre["Sexo"],1,0,'C',1);
        $pdf->Cell(47,10,'',1,0,'C',1);

        $y_initial=$y_initial+10;
    }

    
    $num++;
}

//habitación 27
$ResCam27=mysqli_query($conn, "SELECT * FROM camas WHERE Habitacion='27' ORDER BY Cama ASC");
while($RResCam27=mysqli_fetch_array($ResCam27))
{
    //$ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE (Fecha='".$_GET["fecha"]."' OR Fecha='".date("Y-m-d",strtotime($_GET["fecha"]."- 1 days"))."') AND Cama='".$RResCam27["Id"]."' AND Estatus<2 AND Liberada='0'");
    $ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE Fecha='".$_GET["fecha"]."' AND Cama='".$RResCam27["Id"]."' AND Estatus<2 AND Liberada='0'");

    $res=mysqli_num_rows($ResRes);

    if($res>0 AND $_POST["camas"]<2) //cama ocupada   
    {
        $RResRes=mysqli_fetch_array($ResRes);
        if($RResRes["Tipo"]=='P')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos)AS Nombre, Sexo FROM pacientes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }
        elseif($RResRes["Tipo"]=='A')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos) AS Nombre, Sexo FROM acompannantes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }

        $print=1;
    }
    elseif($res==0 AND ($_POST["camas"]==2 OR $_POST["camas"]==0)) //cama vacia 
    {
        $ResNombre["Nombre"]='';
        $RResRes["Tipo"]='';
        $ResNombre["Id"]='';
        $ResNombre["Sexo"]='';

        $print=1;
    }

    if($RResCam27["Cama"]=='extra' AND $ResNombre["Nombre"]=='')
    {
        $print=0;
    }

    if($print==1)
    {
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','',8);
        $pdf->SetTextColor(000,000,000);
        $pdf->SetY($y_initial);
        $pdf->SetX(6);
        $pdf->Cell(10,10,'27',1,0,'C',1);
        $pdf->Cell(10,10,$RResCam27["Cama"],1,0,'C',1);
        $pdf->Cell(100,10,$ResNombre["Nombre"],1,0,'L',1);
        $pdf->Cell(15,10,$ResNombre["Id"],1,0,'C',1);
        $pdf->Cell(10,10,$RResRes["Tipo"],1,0,'C',1);
        $pdf->Cell(10,10,$ResNombre["Sexo"],1,0,'C',1);
        $pdf->Cell(47,10,'',1,0,'C',1);

        $y_initial=$y_initial+10;
    }

    
    $num++;
}

//Hoja 8
$pdf->AddPage();
//logo 
$pdf->Image('../images/logo.png',140,8,70);
//titulo
$pdf->SetY(16);
$pdf->SetX(6);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(130,4,'Control de Camas',0,0,'C',0);
//
$pdf->SetY(24);
$pdf->SetFont('Arial','',12);
$pdf->Cell(130,4,fecha($_GET["fecha"]),0,0,'C',0);
$pdf->SetY(30);
$pdf->Cell(130,4,'Hoja: 8',0,0,'C',0);
//hombres
$pdf->SetY(42);
$pdf->SetFont('Arial','',12);
$pdf->Cell(200,4,'04 MIXTO',0,0,'C',0);

$pdf->SetFillColor(057,044,138);
$pdf->SetFont('Arial','B',8);
$pdf->SetTextColor(255,255,255);
$pdf->SetY(48);
$pdf->SetX(6);
$pdf->Cell(10,8,utf8_decode('Dor'),1,0,'C',1);
$pdf->Cell(10,8,'Cama',1,0,'C',1);
$pdf->Cell(100,8,'Nombre',1,0,'C',1);
$pdf->Cell(15,8,'Registro',1,0,'C',1);
$pdf->Cell(10,8,utf8_decode('P/A'),1,0,'C',1);
$pdf->Cell(10,8,'Sexo',1,0,'C',1);
$pdf->Cell(47,8,'Observaciones',1,0,'C',1);

$y_axis_initial=48;
$y_initial=56;

//habitación 06
$ResCam6=mysqli_query($conn, "SELECT * FROM camas WHERE Habitacion='6' ORDER BY Cama ASC");
while($RResCam6=mysqli_fetch_array($ResCam6))
{
    //$ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE (Fecha='".$_GET["fecha"]."' OR Fecha='".date("Y-m-d",strtotime($_GET["fecha"]."- 1 days"))."') AND Cama='".$RResCam6["Id"]."' AND Estatus<2 AND Liberada='0'");
    $ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE Fecha='".$_GET["fecha"]."' AND Cama='".$RResCam6["Id"]."' AND Estatus<2 AND Liberada='0'");

    $res=mysqli_num_rows($ResRes);

    if($res>0 AND $_POST["camas"]<2) //cama ocupada   
    {
        $RResRes=mysqli_fetch_array($ResRes);
        if($RResRes["Tipo"]=='P')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos)AS Nombre, Sexo FROM pacientes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }
        elseif($RResRes["Tipo"]=='A')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos) AS Nombre, Sexo FROM acompannantes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }

        $print=1;
    }
    elseif($res==0 AND ($_POST["camas"]==2 OR $_POST["camas"]==0)) //cama vacia 
    {
        $ResNombre["Nombre"]='';
        $RResRes["Tipo"]='';
        $ResNombre["Id"]='';
        $ResNombre["Sexo"]='';

        $print=1;
    }

    if($RResCam6["Cama"]=='extra' AND $ResNombre["Nombre"]=='')
    {
        $print=0;
    }

    if($print==1)
    {
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','',8);
        $pdf->SetTextColor(000,000,000);
        $pdf->SetY($y_initial);
        $pdf->SetX(6);
        $pdf->Cell(10,10,'06',1,0,'C',1);
        $pdf->Cell(10,10,$RResCam6["Cama"],1,0,'C',1);
        $pdf->Cell(100,10,$ResNombre["Nombre"],1,0,'L',1);
        $pdf->Cell(15,10,$ResNombre["Id"],1,0,'C',1);
        $pdf->Cell(10,10,$RResRes["Tipo"],1,0,'C',1);
        $pdf->Cell(10,10,$ResNombre["Sexo"],1,0,'C',1);
        $pdf->Cell(47,10,'',1,0,'C',1);

        $y_initial=$y_initial+10;
    }

    
    $num++;
}

//habitación 24
$ResCam24=mysqli_query($conn, "SELECT * FROM camas WHERE Habitacion='24' ORDER BY Cama ASC");
while($RResCam24=mysqli_fetch_array($ResCam24))
{
    //$ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE (Fecha='".$_GET["fecha"]."' OR Fecha='".date("Y-m-d",strtotime($_GET["fecha"]."- 1 days"))."') AND Cama='".$RResCam24["Id"]."' AND Estatus<2 AND Liberada='0'");
    $ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE Fecha='".$_GET["fecha"]."' AND Cama='".$RResCam24["Id"]."' AND Estatus<2 AND Liberada='0'");

    $res=mysqli_num_rows($ResRes);

    if($res>0 AND $_POST["camas"]<2) //cama ocupada   
    {
        $RResRes=mysqli_fetch_array($ResRes);
        if($RResRes["Tipo"]=='P')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos)AS Nombre, Sexo FROM pacientes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }
        elseif($RResRes["Tipo"]=='A')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos) AS Nombre, Sexo FROM acompannantes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }

        $print=1;
    }
    elseif($res==0 AND ($_POST["camas"]==2 OR $_POST["camas"]==0)) //cama vacia 
    {
        $ResNombre["Nombre"]='';
        $RResRes["Tipo"]='';
        $ResNombre["Id"]='';
        $ResNombre["Sexo"]='';

        $print=1;
    }

    if($RResCam24["Cama"]=='extra' AND $ResNombre["Nombre"]=='')
    {
        $print=0;
    }

    if($print==1)
    {
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','',8);
        $pdf->SetTextColor(000,000,000);
        $pdf->SetY($y_initial);
        $pdf->SetX(6);
        $pdf->Cell(10,10,'24',1,0,'C',1);
        $pdf->Cell(10,10,$RResCam24["Cama"],1,0,'C',1);
        $pdf->Cell(100,10,$ResNombre["Nombre"],1,0,'L',1);
        $pdf->Cell(15,10,$ResNombre["Id"],1,0,'C',1);
        $pdf->Cell(10,10,$RResRes["Tipo"],1,0,'C',1);
        $pdf->Cell(10,10,$ResNombre["Sexo"],1,0,'C',1);
        $pdf->Cell(47,10,'',1,0,'C',1);

        $y_initial=$y_initial+10;
    }

    
    $num++;
}

//habitación 28
$ResCam28=mysqli_query($conn, "SELECT * FROM camas WHERE Habitacion='28' ORDER BY Cama ASC");
while($RResCam28=mysqli_fetch_array($ResCam28))
{
    //$ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE (Fecha='".$_GET["fecha"]."' OR Fecha='".date("Y-m-d",strtotime($_GET["fecha"]."- 1 days"))."') AND Cama='".$RResCam28["Id"]."' AND Estatus<2 AND Liberada='0'");
    $ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE Fecha='".$_GET["fecha"]."' AND Cama='".$RResCam28["Id"]."' AND Estatus<2 AND Liberada='0'");

    $res=mysqli_num_rows($ResRes);

    if($res>0 AND $_POST["camas"]<2) //cama ocupada   
    {
        $RResRes=mysqli_fetch_array($ResRes);
        if($RResRes["Tipo"]=='P')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos)AS Nombre, Sexo FROM pacientes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }
        elseif($RResRes["Tipo"]=='A')
        {
            $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos) AS Nombre, Sexo FROM acompannantes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
        }

        $print=1;
    }
    elseif($res==0 AND ($_POST["camas"]==2 OR $_POST["camas"]==0)) //cama vacia 
    {
        $ResNombre["Nombre"]='';
        $RResRes["Tipo"]='';
        $ResNombre["Id"]='';
        $ResNombre["Sexo"]='';

        $print=1;
    }

    if($RResCam28["Cama"]=='extra' AND $ResNombre["Nombre"]=='')
    {
        $print=0;
    }

    if($print==1)
    {
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','',8);
        $pdf->SetTextColor(000,000,000);
        $pdf->SetY($y_initial);
        $pdf->SetX(6);
        $pdf->Cell(10,10,'28',1,0,'C',1);
        $pdf->Cell(10,10,$RResCam28["Cama"],1,0,'C',1);
        $pdf->Cell(100,10,$ResNombre["Nombre"],1,0,'L',1);
        $pdf->Cell(15,10,$ResNombre["Id"],1,0,'C',1);
        $pdf->Cell(10,10,$RResRes["Tipo"],1,0,'C',1);
        $pdf->Cell(10,10,$ResNombre["Sexo"],1,0,'C',1);
        $pdf->Cell(47,10,'',1,0,'C',1);

        $y_initial=$y_initial+10;
    }

    
    $num++;
}

//bitacora
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '21', '".json_encode($_GET)."')");

//Envio Archivo
$pdf->Output();



?>