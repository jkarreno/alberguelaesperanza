<?php 
//Inicio la sesion 
session_start();
include('../conexion.php');
include('../funciones.php');

require '../vendor/autoload.php';


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

$excel = new SpreadSheet();
$hojaActiva = $excel->getActiveSheet();
$hojaActiva ->setTitle("ReportePersonalizadoPersonas");

$hojaActiva->setCellValue('A1', "Numero");
$hojaActiva->setCellValue('B1', "Registro");
$hojaActiva->setCellValue('C1', "Tipo");
$hojaActiva->setCellValue('D1', "Nombre");
$hojaActiva->setCellValue('E1', "Primer Apellido");
$hojaActiva->setCellValue('F1', "Segundo Apellido");
$hojaActiva->setCellValue('G1', "Direccion");
$hojaActiva->setCellValue('H1', "Colonia");
$hojaActiva->setCellValue('I1', "C.P.");
$hojaActiva->setCellValue('J1', "Municipio");
$hojaActiva->setCellValue('K1', "Estado");
$hojaActiva->setCellValue('L1', "Telefono Fijo");
$hojaActiva->setCellValue('M1', "Telefono Celular");
$hojaActiva->setCellValue('N1', "Correo Electrónico");
$hojaActiva->setCellValue('O1', "Sexo");
$hojaActiva->setCellValue('P1', "Fecha de Nacimiento");
$hojaActiva->setCellValue('Q1', "Edad");
$hojaActiva->setCellValue('R1', "Talla");
$hojaActiva->setCellValue('S1', "Peso");
$hojaActiva->setCellValue('T1', "Religion");
$hojaActiva->setCellValue('U1', "Escolaridad");
$hojaActiva->setCellValue('V1', "Ocupación");
$hojaActiva->setCellValue('W1', "CURP");
$hojaActiva->setCellValue('X1', "INE");
$hojaActiva->setCellValue('Y1', "Edo Civil");
$hojaActiva->setCellValue('Z1', "Hospital");
$hojaActiva->setCellValue('AA1', "Carnet");
$hojaActiva->setCellValue('AB1', "Diagnostico");
$hojaActiva->setCellValue('AC1', "Indigena");
$hojaActiva->setCellValue('AD1', "Discapacitado");
$hojaActiva->setCellValue('AE1', "Hospedaje");
$hojaActiva->setCellValue('AF1', "Alimentos");
$hojaActiva->setCellValue('AG1', "Lavanderia");
$hojaActiva->setCellValue('AH1', "Fecha de Registro");

$fila=2;

$ResPersonas=mysqli_query($conn, "SELECT concat_ws('-', r.IdPA, r.Tipo) AS idpa FROM reservaciones AS r 
                                    INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                    WHERE r.Fecha>='".$_GET["periodode"]."' AND r.Fecha<='".$_GET["periodohasta"]."' 
                                    AND r.Estatus LIKE '".$_GET["reservaciones"]."' AND r.Tipo LIKE '".$_GET["personas"]."' 
                                    AND re.Instituto LIKE '".$_GET["hospitales"]."' AND re.Diagnostico LIKE '".$_GET["enfermedades"]."' 
                                    AND r.Cama>0 GROUP BY concat_ws('-', r.IdPA, r.Tipo)");

$J=1;

//edad
$fechai=explode('-', $_GET["periodode"]);
$fechaf=explode('-', $_GET["periodohasta"]);
$edadni=$fechai[0]-12; $mesni=$fechai[1]; $diani=$fechai[2];
$edadnf=$fechaf[0]-12; $mesnf=$fechaf[1]; $dianf=$fechaf[2];
$edadpi=$fechai[0]-$_GET["edadi"];
$edadpf=$fechai[0]-$_GET["edadf"];

$edadi=$edadpi."-".$mesni."-".$diani;
$edadf=$edadpf."-".$mesni."-".$diani;

while($ResP=mysqli_fetch_array($ResPersonas))
{
    $p=explode('-', $ResP["idpa"]);
    $hojaActiva->setCellValue('A'.$fila, $J);
    $hojaActiva->setCellValue('B'.$fila, $p[0]);
    $hojaActiva->setCellValue('C'.$fila, $p[1]);

    if($p[1]=='P')
    {
        $ResPac=mysqli_query($conn, "SELECT * FROM pacientes 
                                    WHERE Id='".$p[0]."' AND Sexo LIKE '".$_GET["genero"]."' 
                                    AND FechaNacimiento >= '".$edadf."' 
                                    AND FechaNacimiento <= '".$edadi."' 
                                    AND Estado LIKE '".$_GET["estados"]."' LIMIT 1");

        //if(mysqli_num_rows($ResPac)==1)
        //{
            $ResP=mysqli_fetch_array($ResPac);

            if($ResP["FechaNacimiento"]!=NULL)
            {
                $fecha_nac = new DateTime(date('Y/m/d',strtotime($ResP["FechaNacimiento"]))); // Creo un objeto DateTime de la fecha ingresada
                $fecha_hoy = new DateTime(date('Y/m/d',time())); // Creo un objeto DateTime de la fecha de hoy
                $cedad = date_diff($fecha_hoy,$fecha_nac);
                //$edad=$cedad->format('%Y').'/'.$cedad->format('%m');
                $edad=$cedad->format('%Y');
            }
            else
            {
                $edad='---';
            }

            $ResEstado=mysqli_fetch_array(mysqli_query($conn, "SELECT Estado FROM Estados WHERE Id='".$ResP["Estado"]."' LIMIT 1"));
            $ResMunicipio=mysqli_fetch_array(mysqli_query($conn, "SELECT Municipio FROM municipios WHERE Id='".$ResP["Municipio"]."' LIMIT 1"));
            $ResHospital=mysqli_fetch_array(mysqli_query($conn, "SELECT Instituto FROM institutos WHERE Id='".$ResP["Instituto1"]."' LIMIT 1"));
            $ResDiagnostico=mysqli_fetch_array(mysqli_query($conn, "SELECT Diagnostico FROM diagnosticos WHERE Id='".$ResP["Diagnostico1"]."' LIMIT 1"));
            $ResReligion=mysqli_fetch_array(mysqli_query($conn, "SELECT Religion FROM religion WHERE Id='".$ResP["Religion"]."' LIMIT 1"));
            $ResEscolaridad=mysqli_fetch_array(mysqli_query($conn, "SELECT Escolaridad FROM escolaridad WHERE Id='".$ResP["Escolaridad"]."' LIMIT 1"));
            $ResOcupacion=mysqli_fetch_array(mysqli_query($conn, "SELECT Ocupacion FROM ocupaciones WHERE Id='".$ResP["Ocupacion"]."' LIMIT 1"));
            $ResEdoCivil=mysqli_fetch_array(mysqli_query($conn, "SELECT EdoCivil FROM edocivil WHERE Id='".$ResP["EdoCivil"]."' LIMIT 1"));

            $ResHosps=mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(Id) AS HOSP FROM reservaciones WHERE Fecha>='".$_GET["periodode"]."' AND Fecha<='".$_GET["periodohasta"]."'
                                                            AND Estatus='1' AND Tipo='P' AND IdPA='".$p[0]."'"));
            $alimentos=$ResHosps["HOSP"]*3;
            if($ResHosps>7){$lavanderia=ceil($ResHosps["HOSP"]/7);}
            else{$lavanderia=1;}
            
            
            $hojaActiva->setCellValue('D'.$fila,$ResP["Nombre"]);
            $hojaActiva->setCellValue('E'.$fila,$ResP["Apellidos"]);
            $hojaActiva->setCellValue('F'.$fila,$ResP["Apellidos2"]);
            $hojaActiva->setCellValue('G'.$fila,$ResP["Domicilio"]);
            $hojaActiva->setCellValue('H'.$fila,$ResP["Colonia"]);
            $hojaActiva->setCellValue('I'.$fila,$ResP["CP"]);
            $hojaActiva->setCellValue('J'.$fila,utf8_encode($ResMunicipio["Municipio"]));
            $hojaActiva->setCellValue('K'.$fila,$ResEstado["Estado"]);
            $hojaActiva->setCellValue('L'.$fila,$ResP["TelefonoFijo"]);
            $hojaActiva->setCellValue('M'.$fila,$ResP["TelefonoCelular"]);
            $hojaActiva->setCellValue('N'.$fila,$ResP["CorreoE"]);
            $hojaActiva->setCellValue('O'.$fila,$ResP["Sexo"]);
            $hojaActiva->setCellValue('P'.$fila,fechados($ResP["FechaNacimiento"]));
            $hojaActiva->setCellValue('Q'.$fila,utf8_decode($edad));
            $hojaActiva->setCellValue('R'.$fila,$ResP["Talla"]);
            $hojaActiva->setCellValue('S'.$fila,$ResP["Peso"]);
            $hojaActiva->setCellValue('T'.$fila,$ResReligion["Religion"]);
            $hojaActiva->setCellValue('U'.$fila,$ResEscolaridad["Escolaridad"]);
            $hojaActiva->setCellValue('V'.$fila,$ResOcupacion["Ocupacion"]);
            $hojaActiva->setCellValue('W'.$fila,strtoupper($ResP["Curp"]));
            $hojaActiva->setCellValue('X'.$fila,strtoupper($ResP["ClaveINE"]));
            $hojaActiva->setCellValue('Y'.$fila,$ResEdoCivil["EdoCivil"]);
            $hojaActiva->setCellValue('Z'.$fila,$ResHospital["Instituto"]);
            $hojaActiva->setCellValue('AA'.$fila,$ResP["Carnet1"]);
            $hojaActiva->setCellValue('AB'.$fila,$ResDiagnostico["Diagnostico"]);
            if($ResP["Indigena"]==1){$hojaActiva->setCellValue('AC'.$fila,'SI');}else{$hojaActiva->setCellValue('AC'.$fila,'NO');}
            if($ResP["Discapacitado"]==1){$hojaActiva->setCellValue('AD'.$fila,'SI');}else{$hojaActiva->setCellValue('AD'.$fila,'NO');}
            $hojaActiva->setCellValue('AE'.$fila,$ResHosps["HOSP"]);
            $hojaActiva->setCellValue('AF'.$fila,$alimentos);
            $hojaActiva->setCellValue('AG'.$fila,$lavanderia);
            $hojaActiva->setCellValue('AH'.$fila,$ResP["FechaRegistro"]);
            
            $hos=$hos+$ResHosps["HOSP"];
            $ali=$ali+$alimentos;
            $lav=$lav+$lavanderia;
        //}

    }
    elseif($p[1]=='A')
    {
        $ResAco=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM acompannantes  WHERE Id='".$p[0]."' LIMIT 1"));

        if($ResAco["FechaNacimiento"]!=NULL)
        {
            $fecha_nac = new DateTime(date('Y/m/d',strtotime($ResAco["FechaNacimiento"]))); // Creo un objeto DateTime de la fecha ingresada
            $fecha_hoy = new DateTime(date('Y/m/d',time())); // Creo un objeto DateTime de la fecha de hoy
            $cedad = date_diff($fecha_hoy,$fecha_nac);
            //$edad=$cedad->format('%Y').'/'.$cedad->format('%m');
            $edad=$cedad->format('%Y');
        }
        else
        {
            $edad='---';
        }

        $ResEstado=mysqli_fetch_array(mysqli_query($conn, "SELECT Estado FROM Estados WHERE Id='".$ResAco["Estado"]."' LIMIT 1"));
        $ResMunicipio=mysqli_fetch_array(mysqli_query($conn, "SELECT Municipio FROM municipios WHERE Id='".$ResAco["Municipio"]."' LIMIT 1"));
        $ResReligion=mysqli_fetch_array(mysqli_query($conn, "SELECT Religion FROM religion WHERE Id='".$ResAco["Religion"]."' LIMIT 1"));
        $ResEscolaridad=mysqli_fetch_array(mysqli_query($conn, "SELECT Escolaridad FROM escolaridad WHERE Id='".$ResAco["Escolaridad"]."' LIMIT 1"));
        $ResOcupacion=mysqli_fetch_array(mysqli_query($conn, "SELECT Ocupacion FROM ocupaciones WHERE Id='".$ResAco["Ocupacion"]."' LIMIT 1"));
        $ResEdoCivil=mysqli_fetch_array(mysqli_query($conn, "SELECT EdoCivil FROM edocivil WHERE Id='".$ResAco["EdoCivil"]."' LIMIT 1"));

        $ResHosps=mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(Id) AS HOSP FROM reservaciones WHERE Fecha>='".$_GET["periodode"]."' AND Fecha<='".$_GET["periodohasta"]."' 
                                                            AND Estatus='1' AND Tipo='A' AND IdPA='".$p[0]."'"));
        $alimentos=$ResHosps["HOSP"]*3;
        if($ResHosps>7){$lavanderia=ceil($ResHosps["HOSP"]/7);}
        else{$lavanderia=1;}

        $hojaActiva->setCellValue('D'.$fila,$ResAco["Nombre"]);
        $hojaActiva->setCellValue('E'.$fila,$ResAco["Apellidos"]);
        $hojaActiva->setCellValue('F'.$fila,$ResAco["Apellidos2"]);
        $hojaActiva->setCellValue('G'.$fila,$ResAco["Domicilio"]);
        $hojaActiva->setCellValue('H'.$fila,$ResAco["Colonia"]);
        $hojaActiva->setCellValue('I'.$fila,$ResAco["CP"]);
        $hojaActiva->setCellValue('J'.$fila,utf8_encode($ResMunicipio["Municipio"]));
        $hojaActiva->setCellValue('K'.$fila,$ResEstado["Estado"]);
        $hojaActiva->setCellValue('L'.$fila,$ResAco["TelefonoFijo"]);
        $hojaActiva->setCellValue('M'.$fila,$ResAco["TelefonoCelular"]);
        $hojaActiva->setCellValue('N'.$fila,'');
        $hojaActiva->setCellValue('O'.$fila,$ResAco["Sexo"]);
        $hojaActiva->setCellValue('P'.$fila,fechados($ResAco["FechaNacimiento"]));
        $hojaActiva->setCellValue('Q'.$fila,utf8_decode($edad));
        $hojaActiva->setCellValue('R'.$fila,$ResAco["Talla"]);
        $hojaActiva->setCellValue('S'.$fila,$ResAco["Peso"]);
        $hojaActiva->setCellValue('T'.$fila,$ResReligion["Religion"]);
        $hojaActiva->setCellValue('U'.$fila,$ResEscolaridad["Escolaridad"]);
        $hojaActiva->setCellValue('V'.$fila,$ResOcupacion["Ocupacion"]);
        $hojaActiva->setCellValue('W'.$fila,strtoupper($ResAco["Curp"]));
        $hojaActiva->setCellValue('X'.$fila,'');
        $hojaActiva->setCellValue('Y'.$fila,$ResEdoCivil["EdoCivil"]);
        $hojaActiva->setCellValue('Z'.$fila,'Acompañante');
        $hojaActiva->setCellValue('AA'.$fila,'Acompañante');
        $hojaActiva->setCellValue('AB'.$fila,'Acompañante');
        $hojaActiva->setCellValue('AC'.$fila,'--');
        $hojaActiva->setCellValue('AD'.$fila,'--');
        $hojaActiva->setCellValue('AE'.$fila,$ResHosps["HOSP"]);
        $hojaActiva->setCellValue('AF'.$fila,$alimentos);
        $hojaActiva->setCellValue('AG'.$fila,$lavanderia);
        $hojaActiva->setCellValue('AH'.$fila,'');

        $hos=$hos+$ResHosps["HOSP"];
        $ali=$ali+$alimentos;
        $lav=$lav+$lavanderia;
    }

    $fila++;
    $J++;
}

    $hojaActiva->setCellValue('A'.$fila, "SELECT concat_ws('-', r.IdPA, r.Tipo) AS idpa FROM reservaciones AS r 
    INNER JOIN reservacion as re ON re.Id=r.IdReservacion
    WHERE r.Fecha>='".$_GET["periodode"]."' AND r.Fecha<='".$_GET["periodohasta"]."' 
    AND r.Estatus LIKE '".$_GET["reservaciones"]."' AND r.Tipo LIKE '".$_GET["personas"]."' 
    AND re.Instituto LIKE '".$_GET["hospitales"]."' AND re.Diagnostico LIKE '".$_GET["enfermedades"]."' 
    AND r.Cama>0 GROUP BY concat_ws('-', r.IdPA, r.Tipo)");

// redirect output to client browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="ReportePersonalizadoPersonas.xlsx"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($excel, 'Xlsx');
$writer->save('php://output');
exit;