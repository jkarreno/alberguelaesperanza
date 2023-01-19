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
$hojaActiva ->setTitle("ReportePersonas");

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

$ResPersonas=mysqli_query($conn, "SELECT concat_ws('-', r.IdPA, r.Tipo) AS idpa FROM `reservaciones` AS r 
                                    WHERE `Fecha` LIKE '".$_GET["anno"]."-".$_GET["mes"]."-%' AND Estatus='1' GROUP BY concat_ws('-', r.IdPA, r.Tipo)");

$J=1;
while($ResP=mysqli_fetch_array($ResPersonas))
{
    $p=explode('-', $ResP["idpa"]);
    $hojaActiva->setCellValue('A'.$fila, $J);
    $hojaActiva->setCellValue('B'.$fila, $p[0]);
    $hojaActiva->setCellValue('C'.$fila, $p[1]);

    if($p[1]=='P')
    {
        $ResPac=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM pacientes WHERE Id='".$p[0]."' LIMIT 1"));

        if($ResPac["FechaNacimiento"]!=NULL)
        {
            $fecha_nac = new DateTime(date('Y/m/d',strtotime($ResPac["FechaNacimiento"]))); // Creo un objeto DateTime de la fecha ingresada
            $fecha_hoy = new DateTime(date('Y/m/d',time())); // Creo un objeto DateTime de la fecha de hoy
            $cedad = date_diff($fecha_hoy,$fecha_nac);
            //$edad=$cedad->format('%Y').'/'.$cedad->format('%m');
            $edad=$cedad->format('%Y');
        }
        else
        {
            $edad='---';
        }

        $ResEstado=mysqli_fetch_array(mysqli_query($conn, "SELECT Estado FROM Estados WHERE Id='".$ResPac["Estado"]."' LIMIT 1"));
        $ResMunicipio=mysqli_fetch_array(mysqli_query($conn, "SELECT Municipio FROM municipios WHERE Id='".$ResPac["Municipio"]."' LIMIT 1"));
        $ResHospital=mysqli_fetch_array(mysqli_query($conn, "SELECT Instituto FROM institutos WHERE Id='".$ResPac["Instituto1"]."' LIMIT 1"));
        $ResDiagnostico=mysqli_fetch_array(mysqli_query($conn, "SELECT Diagnostico FROM diagnosticos WHERE Id='".$ResPac["Diagnostico1"]."' LIMIT 1"));
        $ResReligion=mysqli_fetch_array(mysqli_query($conn, "SELECT Religion FROM religion WHERE Id='".$ResPac["Religion"]."' LIMIT 1"));
        $ResEscolaridad=mysqli_fetch_array(mysqli_query($conn, "SELECT Escolaridad FROM escolaridad WHERE Id='".$ResPac["Escolaridad"]."' LIMIT 1"));
        $ResOcupacion=mysqli_fetch_array(mysqli_query($conn, "SELECT Ocupacion FROM ocupaciones WHERE Id='".$ResPac["Ocupacion"]."' LIMIT 1"));
        $ResEdoCivil=mysqli_fetch_array(mysqli_query($conn, "SELECT EdoCivil FROM edocivil WHERE Id='".$ResPac["EdoCivil"]."' LIMIT 1"));

        $ResHosps=mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(Id) AS HOSP FROM reservaciones WHERE Fecha LIKE '".$_GET["anno"]."-".$_GET["mes"]."-%' 
                                                            AND Estatus='1' AND Tipo='P' AND IdPA='".$p[0]."'"));
        $alimentos=$ResHosps["HOSP"]*3;
        if($ResHosps>7){$lavanderia=ceil($ResHosps["HOSP"]/7);}
        else{$lavanderia=1;}

        $hojaActiva->setCellValue('D'.$fila,$ResPac["Nombre"]);
        $hojaActiva->setCellValue('E'.$fila,$ResPac["Apellidos"]);
        $hojaActiva->setCellValue('F'.$fila,$ResPac["Apellidos2"]);
        $hojaActiva->setCellValue('G'.$fila,$ResPac["Domicilio"]);
        $hojaActiva->setCellValue('H'.$fila,$ResPac["Colonia"]);
        $hojaActiva->setCellValue('I'.$fila,$ResPac["CP"]);
        $hojaActiva->setCellValue('J'.$fila,utf8_encode($ResMunicipio["Municipio"]));
        $hojaActiva->setCellValue('K'.$fila,$ResEstado["Estado"]);
        $hojaActiva->setCellValue('L'.$fila,$ResPac["TelefonoFijo"]);
        $hojaActiva->setCellValue('M'.$fila,$ResPac["TelefonoCelular"]);
        $hojaActiva->setCellValue('N'.$fila,$ResPac["CorreoE"]);
        $hojaActiva->setCellValue('O'.$fila,$ResPac["Sexo"]);
        $hojaActiva->setCellValue('P'.$fila,fechados($ResPac["FechaNacimiento"]));
        $hojaActiva->setCellValue('Q'.$fila,utf8_decode($edad));
        $hojaActiva->setCellValue('R'.$fila,$ResPac["Talla"]);
        $hojaActiva->setCellValue('S'.$fila,$ResPac["Peso"]);
        $hojaActiva->setCellValue('T'.$fila,$ResReligion["Religion"]);
        $hojaActiva->setCellValue('U'.$fila,$ResEscolaridad["Escolaridad"]);
        $hojaActiva->setCellValue('V'.$fila,$ResOcupacion["Ocupacion"]);
        $hojaActiva->setCellValue('W'.$fila,strtoupper($ResPac["Curp"]));
        $hojaActiva->setCellValue('X'.$fila,strtoupper($ResPac["ClaveINE"]));
        $hojaActiva->setCellValue('Y'.$fila,$ResEdoCivil["EdoCivil"]);
        $hojaActiva->setCellValue('Z'.$fila,$ResHospital["Instituto"]);
        $hojaActiva->setCellValue('AA'.$fila,$ResPac["Carnet1"]);
        $hojaActiva->setCellValue('AB'.$fila,$ResDiagnostico["Diagnostico"]);
        if($ResPac["Indigena"]==1){$hojaActiva->setCellValue('AC'.$fila,'SI');}else{$hojaActiva->setCellValue('AC'.$fila,'NO');}
        if($ResPac["Discapacitado"]==1){$hojaActiva->setCellValue('AD'.$fila,'SI');}else{$hojaActiva->setCellValue('AD'.$fila,'NO');}
        $hojaActiva->setCellValue('AE'.$fila,$ResHosps["HOSP"]);
        $hojaActiva->setCellValue('AF'.$fila,$alimentos);
        $hojaActiva->setCellValue('AG'.$fila,$lavanderia);
        $hojaActiva->setCellValue('AH'.$fila,$ResPac["FechaRegistro"]);

        $hos=$hos+$ResHosps["HOSP"];
        $ali=$ali+$alimentos;
        $lav=$lav+$lavanderia;
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

        $ResHosps=mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(Id) AS HOSP FROM reservaciones WHERE Fecha LIKE '".$_GET["anno"]."-".$_GET["mes"]."-%' 
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


$hojaActiva->setCellValue('AE'.$fila,$hos);$col++;
$hojaActiva->setCellValue('AF'.$fila,$ali);$col++;
$hojaActiva->setCellValue('AG'.$fila,$lav);$col++;

//bitacora
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '103', '".json_encode($_GET)."')");

// redirect output to client browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="ReportePersonas.xlsx"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($excel, 'Xlsx');
$writer->save('php://output');
exit;