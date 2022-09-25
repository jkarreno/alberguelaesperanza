<?php 
//Inicio la sesion 
session_start();
include('../conexion.php');
include('../funciones.php');
include ("../caja/excelgen.class.php");

//initiate a instance of "excelgen" class
$excel = new ExcelGen("ReportePersonas");

//initiate $row,$col variables
$row=0;
$col=0;

$hos=0;
$ali=0;
$lav=0;

$excel->WriteText($row,$col,"Numero");$col++;
$excel->WriteText($row,$col,"Registro");$col++;
$excel->WriteText($row,$col,"Tipo");$col++;
$excel->WriteText($row,$col,"Nombre");$col++;
$excel->WriteText($row,$col,"Apellidos");$col++;
$excel->WriteText($row,$col,"Direccion");$col++;
$excel->WriteText($row,$col,"Colonia");$col++;
$excel->WriteText($row,$col,"C.P.");$col++;
$excel->WriteText($row,$col,"Municipio");$col++;
$excel->WriteText($row,$col,"Estado");$col++;
$excel->WriteText($row,$col,"Telefono Fijo");$col++;
$excel->WriteText($row,$col,"Telefono Celular");$col++;
$excel->WriteText($row,$col,"Correo Electrónico");$col++;
$excel->WriteText($row,$col,"Sexo");$col++;
$excel->WriteText($row,$col,"Fecha de Nacimiento");$col++;
$excel->WriteText($row,$col,"Edad");$col++;
$excel->WriteText($row,$col,"Talla");$col++;
$excel->WriteText($row,$col,"Peso");$col++;
$excel->WriteText($row,$col,"Religion");$col++;
$excel->WriteText($row,$col,"Escolaridad");$col++;
$excel->WriteText($row,$col,utf8_decode("Ocupación"));$col++;
$excel->WriteText($row,$col,"CURP");$col++;
$excel->WriteText($row,$col,"INE");$col++;
$excel->WriteText($row,$col,"Edo Civil");$col++;
$excel->WriteText($row,$col,"Hospital");$col++;
$excel->WriteText($row,$col,"Carnet");$col++;
$excel->WriteText($row,$col,"Diagnostico");$col++;
$excel->WriteText($row,$col,"Indigena");$col++;
$excel->WriteText($row,$col,"Discapacitado");$col++;
$excel->WriteText($row,$col,"Hospedaje");$col++;
$excel->WriteText($row,$col,"Alimentos");$col++;
$excel->WriteText($row,$col,"Lavanderia");$col++;
$excel->WriteText($row,$col,"Fecha de Registro");$col++;

$row++;
$col=0;

$ResPersonas=mysqli_query($conn, "SELECT concat_ws('-', r.IdPA, r.Tipo) AS idpa FROM `reservaciones` AS r 
                                    WHERE `Fecha` LIKE '".$_GET["anno"]."-".$_GET["mes"]."-%' AND Estatus='1' GROUP BY concat_ws('-', r.IdPA, r.Tipo)");

$J=1;
while($ResP=mysqli_fetch_array($ResPersonas))
{
    $p=explode('-', $ResP["idpa"]);
    $excel->WriteText($row,$col,$J);$col++;
    $excel->WriteText($row,$col,$p[0]);$col++;
    $excel->WriteText($row,$col,$p[1]);$col++;
    

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

        $excel->WriteText($row,$col,$ResPac["Nombre"]);$col++;
        $excel->WriteText($row,$col,$ResPac["Apellidos"]);$col++;
        $excel->WriteText($row,$col,$ResPac["Domicilio"]);$col++;
        $excel->WriteText($row,$col,$ResPac["Colonia"]);$col++;
        $excel->WriteText($row,$col,$ResPac["CP"]);$col++;
        $excel->WriteText($row,$col,$ResMunicipio["Municipio"]);$col++;
        $excel->WriteText($row,$col,$ResEstado["Estado"]);$col++;
        $excel->WriteText($row,$col,$ResPac["TelefonoFijo"]);$col++;
        $excel->WriteText($row,$col,$ResPac["TelefonoCelular"]);$col++;
        $excel->WriteText($row,$col,$ResPac["CorreoE"]);$col++;
        $excel->WriteText($row,$col,$ResPac["Sexo"]);$col++;
        $excel->WriteText($row,$col,fechados($ResPac["FechaNacimiento"]));$col++;
        $excel->WriteText($row,$col,utf8_decode($edad));$col++;
        $excel->WriteText($row,$col,$ResPac["Talla"]);$col++;
        $excel->WriteText($row,$col,$ResPac["Peso"]);$col++;
        $excel->WriteText($row,$col,$ResReligion["Religion"]);$col++;
        $excel->WriteText($row,$col,$ResEscolaridad["Escolaridad"]);$col++;
        $excel->WriteText($row,$col,$ResOcupacion["Ocupacion"]);$col++;
        $excel->WriteText($row,$col,strtoupper($ResPac["Curp"]));$col++;
        $excel->WriteText($row,$col,strtoupper($ResPac["ClaveINE"]));$col++;
        $excel->WriteText($row,$col,$ResEdoCivil["EdoCivil"]);$col++;
        $excel->WriteText($row,$col,$ResHospital["Instituto"]);$col++;
        $excel->WriteText($row,$col,$ResPac["Carnet1"]);$col++;
        $excel->WriteText($row,$col,$ResDiagnostico["Diagnostico"]);$col++;
        if($ResPac["Indigena"]==1){$excel->WriteText($row,$col,'SI');}else{$excel->WriteText($row,$col,'NO');}$col++;
        if($ResPac["Discapacitado"]==1){$excel->WriteText($row,$col,'SI');}else{$excel->WriteText($row,$col,'NO');}$col++;
        $excel->WriteText($row,$col,$ResHosps["HOSP"]);$col++;
        $excel->WriteText($row,$col,$alimentos);$col++;
        $excel->WriteText($row,$col,$lavanderia);$col++;
        $excel->WriteText($row,$col,$ResPac["FechaRegistro"]);$col++;

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

        $excel->WriteText($row,$col,$ResAco["Nombre"]);$col++;
        $excel->WriteText($row,$col,$ResAco["Apellidos"]);$col++;
        $excel->WriteText($row,$col,$ResAco["Domicilio"]);$col++;
        $excel->WriteText($row,$col,$ResAco["Colonia"]);$col++;
        $excel->WriteText($row,$col,$ResAco["CP"]);$col++;
        $excel->WriteText($row,$col,$ResMunicipio["Municipio"]);$col++;
        $excel->WriteText($row,$col,$ResEstado["Estado"]);$col++;
        $excel->WriteText($row,$col,$ResAco["TelefonoFijo"]);$col++;
        $excel->WriteText($row,$col,$ResAco["TelefonoCelular"]);$col++;
        $excel->WriteText($row,$col,'');$col++;
        $excel->WriteText($row,$col,$ResAco["Sexo"]);$col++;
        $excel->WriteText($row,$col,fechados($ResAco["FechaNacimiento"]));$col++;
        $excel->WriteText($row,$col,utf8_decode($edad));$col++;
        $excel->WriteText($row,$col,$ResAco["Talla"]);$col++;
        $excel->WriteText($row,$col,$ResAco["Peso"]);$col++;
        $excel->WriteText($row,$col,$ResReligion["Religion"]);$col++;
        $excel->WriteText($row,$col,$ResEscolaridad["Escolaridad"]);$col++;
        $excel->WriteText($row,$col,$ResOcupacion["Ocupacion"]);$col++;
        $excel->WriteText($row,$col,strtoupper($ResAco["Curp"]));$col++;
        $excel->WriteText($row,$col,'');$col++;
        $excel->WriteText($row,$col,$ResEdoCivil["EdoCivil"]);$col++;
        $excel->WriteText($row,$col,'');$col++;
        $excel->WriteText($row,$col,'');$col++;
        $excel->WriteText($row,$col,'');$col++;
        $excel->WriteText($row,$col,'');$col++;
        $excel->WriteText($row,$col,'');$col++;
        $excel->WriteText($row,$col,$ResHosps["HOSP"]);$col++;
        $excel->WriteText($row,$col,$alimentos);$col++;
        $excel->WriteText($row,$col,$lavanderia);$col++;
        $excel->WriteText($row,$col,'');$col++;

        $hos=$hos+$ResHosps["HOSP"];
        $ali=$ali+$alimentos;
        $lav=$lav+$lavanderia;
    }

    $row++;
	$col=0;
    $J++;
}

$excel->WriteText($row,$col,'');$col++;
$excel->WriteText($row,$col,'');$col++;
$excel->WriteText($row,$col,'');$col++;
$excel->WriteText($row,$col,'');$col++;
$excel->WriteText($row,$col,'');$col++;
$excel->WriteText($row,$col,'');$col++;
$excel->WriteText($row,$col,'');$col++;
$excel->WriteText($row,$col,'');$col++;
$excel->WriteText($row,$col,'');$col++;
$excel->WriteText($row,$col,'');$col++;
$excel->WriteText($row,$col,'');$col++;
$excel->WriteText($row,$col,'');$col++;
$excel->WriteText($row,$col,'');$col++;
$excel->WriteText($row,$col,'');$col++;
$excel->WriteText($row,$col,'');$col++;
$excel->WriteText($row,$col,'');$col++;
$excel->WriteText($row,$col,'');$col++;
$excel->WriteText($row,$col,'');$col++;
$excel->WriteText($row,$col,'');$col++;
$excel->WriteText($row,$col,'');$col++;
$excel->WriteText($row,$col,'');$col++;
$excel->WriteText($row,$col,$hos);$col++;
$excel->WriteText($row,$col,$ali);$col++;
$excel->WriteText($row,$col,$lav);$col++;


//bitacora
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '103', '".json_encode($_GET)."')");

//stream Excel for user to download or show on browser
$excel->SendFile();
?>