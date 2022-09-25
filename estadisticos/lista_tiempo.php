<?php 
date_default_timezone_set('America/Mexico_City');
include ("../conexion.php");
include ("../funciones.php");
?>
<html lang="es-mx">
<head>
	<meta charset="UTF-8" />
	<title>Administración</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
		
	<script src="https://kit.fontawesome.com/2df1cf6d50.js" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.1/css/jquery.dataTables.min.css">

    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.js"></script>
<body>
    <table id="table_id" class="display hover">
    <thead>
        <tr>
            <th>Estatus</th>
            <th>Res</th>
            <th width="100">Fecha</th>
            <th>Registro</th>
            <th>Tipo</th>
            <th>Paciente</th>
            <th>Sexo</th>
            <th>Fecha de Nacimiento</th>
            <th>CURP</th>
            <th>Edad</th>
            <th>Talla</th>
            <th>Peso</th>
            <th>Estado</th>
            <th>Municipio</th>
            <th>Dirección</th>
            <th>Hospital</th>
            <th>Carnet</th>
            <th>Dias</th>
        </tr>
    </thead>
    <tbody>
    <?php
        $fechai=$_GET["fechai"];
        $fechaf=$_GET["fechaf"];

        $ResRes=mysqli_query($conn, "SELECT * FROM reservacion WHERE Fecha>='".$fechai."' AND Fecha<='".$fechaf."' ORDER BY Id DESC");
        while($RResRes=mysqli_fetch_array($ResRes))
        {
            $ResPac=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM pacientes WHERE Id='".$RResRes["IdPaciente"]."' LIMIT 1"));

            if($RResRes["Estatus"]==0){$estatus='<i style="cursor: pointer;" class="far fa-circle"></i>';}
            elseif($RResRes["Estatus"]==1){$estatus='<i class="far fa-check-circle" style="color: #37b64b;"</i>';}
            elseif($RResRes["Estatus"]==2){$estatus='<i class="far fa-times-circle" style="color: #ff0000"></i>';}

            $hospital=mysqli_fetch_array(mysqli_query($conn, "SELECT Instituto FROM institutos WHERE Id='".$ResPac["Instituto1"]."' LIMIT 1"));

            if($ResPac["FechaNacimiento"]!=NULL)
            {
                $fecha_nac = new DateTime(date('Y/m/d',strtotime($ResPac["FechaNacimiento"]))); // Creo un objeto DateTime de la fecha ingresada
                $fecha_hoy = new DateTime(date('Y/m/d',time())); // Creo un objeto DateTime de la fecha de hoy
                $cedad = date_diff($fecha_hoy,$fecha_nac);
                $edad=$cedad->format('%Y').' años '.$cedad->format('%m').' meses';
            }
            else
            {
                $edad='---';
            }

            $direccion=$ResPac["Domicilio"].' '.$ResPac["Colonia"];
        
            $Estado=mysqli_fetch_array(mysqli_query($conn, "SELECT Estado FROM Estados WHERE Id='".$ResPac["Estado"]."' LIMIT 1"));
            $Municipio=mysqli_fetch_array(mysqli_query($conn, "SELECT Municipio FROM municipios WHERE Id='".$ResPac["Municipio"]."' LIMIT 1"));

            echo '<tr>
                <td>'.$estatus.'</td>
                <td>'.$RResRes["Id"].'</td>
                <td width="100">'.fechados($RResRes["Fecha"]).'</td>
                <td>'.$RResRes["IdPaciente"].'</td>
                <td>P</td>
                <td>'.$ResPac["Nombre"].' '.$ResPac["Apellidos"].'</td>
                <td>'.$ResPac["Sexo"].'</td>
                <td>'.fechados($ResPac["FechaNacimiento"]).'</td>
                <td>'.$ResPac["Curp"].'</td>
                <td>'.$edad.'</td>
                <td>'.$ResPac["Talla"].'</td>
                <td>'.$ResPac["Peso"].'</td>
                <td>'.utf8_encode($Estado["Estado"]).'</td>
                <td>'.utf8_encode($Municipio["Municipio"]).'</td>
                <td>'.$direccion.'</td>
                <td>'.$hospital["Instituto"].'</td>
                <td>'.$ResPac["Carnet1"].'</td>
                <td>'.$RResRes["Dias"].'</td>
            </tr>';

            //acompañantes de la reservación
            $ResAcos=mysqli_query($conn, "SELECT IdPA FROM `reservaciones` WHERE IdReservacion='".$RResRes["Id"]."' AND Tipo='A' GROUP BY IdPA");
            while($RResAcos=mysqli_fetch_array($ResAcos))
            {
                $ResAcom=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM acompannantes WHERE Id='".$RResAcos["IdPA"]."' LIMIT 1"));

                if($ResAcom["FechaNacimiento"]!=NULL)
                {
                    $fecha_nac = new DateTime(date('Y/m/d',strtotime($ResAcom["FechaNacimiento"]))); // Creo un objeto DateTime de la fecha ingresada
                    $fecha_hoy = new DateTime(date('Y/m/d',time())); // Creo un objeto DateTime de la fecha de hoy
                    $cedad = date_diff($fecha_hoy,$fecha_nac);
                    $edadaco=$cedad->format('%Y').' años '.$cedad->format('%m').' meses';
                }
                else
                {
                    $edadaco='---';
                }

                $direcciona=$ResAcom["Domicilio"].' '.$ResAcom["Colonia"];

                $EstadoA=mysqli_fetch_array(mysqli_query($conn, "SELECT Estado FROM Estados WHERE Id='".$ResAcom["Estado"]."' LIMIT 1"));
                $MunicipioA=mysqli_fetch_array(mysqli_query($conn, "SELECT Municipio FROM municipios WHERE Id='".$ResAcom["Municipio"]."' LIMIT 1"));

                echo '<tr>
                    <td>'.$estatus.'</td>
                    <td>'.$RResRes["Id"].'</td>
                    <td width="100">'.fechados($RResRes["Fecha"]).'</td>
                    <td>'.$ResAcom["Id"].'</td>
                    <td>A</td>
                    <td>'.$ResAcom["Nombre"].'</td>
                    <td>'.$ResAcom["Sexo"].'</td>
                    <td>'.fechados($ResAcom["FechaNacimiento"]).'</td>
                    <td>'.$ResAcom["Curp"].'</td>
                    <td>'.$edadaco.'</td>
                    <td>'.$ResAcom["Talla"].'</td>
                    <td>'.$ResAcom["Peso"].'</td>
                    <td>'.utf8_encode($EstadoA["Estado"]).'</td>
                    <td>'.utf8_encode($MunicipioA["Municipio"]).'</td>
                    <td>'.$direcciona.'</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>';
            }

            //reset campos
            $edad='';
            $direccion='';
            $Estado='';
            $Municipio='';
            $edadaco='';
            $direcciona='';
            $EstadoA='';
            $MunicipioA='';
        }

        //echo "SELECT * FROM reservacion WHERE Fecha LIKE '".$anno."-".$mes."-%' ORDER BY Id DESC";
    ?>
    </tbody>
</table>
</body>
</html>
<script>
    $(document).ready( function () {
    $('#table_id').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
        }
    });
} );
</script>
<style>
    table.dataTable thead .sorting{
        font-family: Roboto;
        background-color: #695de9;
        color: #ffffff;
    }
    table.dataTable tbody td{
        font-family: Roboto;
    }
</style>