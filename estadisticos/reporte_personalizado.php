<?php
//Inicio la sesion 
session_start();

include('../conexion.php');

$cadena='<div class="c100 card">
            <div class="c100 card">
                <h2>Reporte Personalizado</h2>
                <form name="fpers" id="fpers">
                    <label class="l_form">Selecciona los filtro que requieres</label>
                    <div class="c20" style="display: flex; flex-wrap: wrap; justify-content: space-around;">
                        <label class="l_form">Periodo</label>
                        <div class="c45">
                            <input type="date" name="periodode" id="periodode" placeholder="Desde:"';if(isset($_POST["hacer"])){$cadena.=' value="'.$_POST["periodode"].'"';}else{$cadena.=' value="'.date("Y-m").'-01"';}$cadena.='>
                        </div>
                        <div class="c10">
                            <label class="l_form">A</label>
                        </div>
                        <div class="c45">
                            <input type="date" name="periodohasta" id="periodohasta"';if(isset($_POST["hacer"])){$cadena.=' value="'.$_POST["periodohasta"].'"';}else{$cadena.=' value="'.date("Y-m-d").'"';}$cadena.='>
                        </div>
                    </div>
                    <div class="c20">
                        <label class="l_form">Personas:</label>
                        <select name="personas" id="personas">
                            <option value="%"';if(isset($_POST["hacer"]) AND $_POST["personas"]=='%'){$cadena.=' selected';}$cadena.='>Todos</option>
                            <option value="P"';if(isset($_POST["hacer"]) AND $_POST["personas"]=='P'){$cadena.=' selected';}$cadena.='>Pacientes</option>
                            <option value="A"';if(isset($_POST["hacer"]) AND $_POST["personas"]=='A'){$cadena.=' selected';}$cadena.='>Acompañantes</option>
                        </select>
                    </div>
                    <div class="c20">
                        <label class="l_form">Genero:</label>
                        <select name="genero" id="genero">
                            <option value="%"';if(isset($_POST["hacer"]) AND $_POST["genero"]=='%'){$cadena.=' selected';}$cadena.='>Ambos</option>
                            <option value="F"';if(isset($_POST["hacer"]) AND $_POST["genero"]=='F'){$cadena.=' selected';}$cadena.='>Femenino</option>
                            <option value="M"';if(isset($_POST["hacer"]) AND $_POST["genero"]=='M'){$cadena.=' selected';}$cadena.='>Masculino</option>
                        </select>
                    </div>
                    <div class="c20" style="display: flex; flex-wrap: wrap; justify-content: space-around;">
                        <label class="l_form">Edades</label>
                        <div class="c45">
                            <input type="number" name="edadi" id="edadi"';if(isset($_POST["hacer"])){$cadena.=' value="'.$_POST["edadi"].'"';}else{$cadena.=' value="0"';}$cadena.='>
                        </div>
                        <div class="c10">
                            <label class="l_form">A</label>
                        </div>
                        <div class="c45">
                            <input type="number" name="edadf" id="edadf"';if(isset($_POST["hacer"])){$cadena.=' value="'.$_POST["edadf"].'"';}else{$cadena.=' value="100"';}$cadena.='>
                        </div>
                    </div>
                    <div class="c10">
                        <label class="l_form">Reservaciones:</label>
                        <select name="reservaciones" id="reservaciones">
                            <option value="1"';if(isset($_POST["hacer"]) AND $_POST["reservaciones"]=='1'){$cadena.=' selected';}$cadena.='>Confirmadas</option>
                            <option value="0"';if(isset($_POST["hacer"]) AND $_POST["reservaciones"]=='0'){$cadena.=' selected';}$cadena.='>Por Confirmar</option>
                            <option value="2"';if(isset($_POST["hacer"]) AND $_POST["reservaciones"]=='2'){$cadena.=' selected';}$cadena.='>Canceladas</option>
                            <option value="%"';if(isset($_POST["hacer"]) AND $_POST["reservaciones"]=='%'){$cadena.=' selected';}$cadena.='>Todas</option>
                        </select>
                    </div>

                    <div class="c30">
                        <label class="l_form">Enfermedades:</label>
                        <select name="enfermedades" id="enfermedades">
                            <option value="%"';if(isset($_POST["hacer"]) AND $_POST["enfermedades"]=='%'){$cadena.=' selected';}$cadena.='>Todas</option>';
$ResDiagnosticos=mysqli_query($conn, "SELECT * FROM diagnosticos ORDER BY Diagnostico ASC");
while($RResD=mysqli_fetch_array($ResDiagnosticos))
{
    $cadena.='              <option value="'.$RResD["Id"].'"';if(isset($_POST["hacer"]) AND $_POST["enfermedades"]==$RResD["Id"]){$cadena.=' selected';}$cadena.='>'.$RResD["Diagnostico"].'</option>';
}
$cadena.='              </select>
                    </div>
                    <div class="c30">
                        <label class="l_form">Hospitales:</label>
                        <select name="hospitales" id="hospitales">
                            <option value="%"';if(isset($_POST["hacer"]) AND $_POST["hospitales"]=='%'){$cadena.=' selected';}$cadena.='>Todos</option>';
$ResHospitales=mysqli_query($conn, "SELECT * FROM institutos ORDER BY Instituto ASC");
while($RResH=mysqli_fetch_array($ResHospitales))
{
    $cadena.='              <option value="'.$RResH["Id"].'"';if(isset($_POST["hacer"]) AND $_POST["hospitales"]==$RResH["Id"]){$cadena.=' selected';}$cadena.='>'.utf8_encode($RResH["Instituto"]).'</option>';
}
$cadena.='             </select>
                    </div>
                    <div class="c30">
                        <label class="l_form">Estados:</label>
                        <select name="estados" id="estados">
                            <option value="%"'.$RResH["Id"].'"';if(isset($_POST["hacer"]) AND $_POST["estados"]=='%'){$cadena.=' selected';}$cadena.='>Todos</option>';
$ResEstados=mysqli_query($conn, "SELECT * FROM Estados ORDER BY Id ASC");
while($RResE=mysqli_fetch_array($ResEstados))
{
    $cadena.='              <option value="'.$RResE["Id"].'"'.$RResH["Id"].'"';if(isset($_POST["hacer"]) AND $_POST["estados"]==$RResE["Id"]){$cadena.=' selected';}$cadena.='>'.utf8_encode($RResE["Estado"]).'</option>';
}
$cadena.='               </select>
                    </div>

                    <div class="c100 c_der">
                        <input type="hidden" name="hacer" id="hacer" value="generarr">
                        <input type="submit" name="botgenrepor" id="botgenrepor" value="Generar Reporte >>">
                    </div>
                </form>
            </div>
        </div>';

if(isset($_POST["hacer"]))
{
    //fechas
    $fechai=explode('-', $_POST["periodode"]); $_SESSION["rep_per"][0][0]=$_POST["periodode"];
    $fechaf=explode('-', $_POST["periodohasta"]); $_SESSION["rep_per"][0][1]=$_POST["periodohasta"];
    $edadni=$fechai[0]-12; $mesni=$fechai[1]; $diani=$fechai[2];
    $edadnf=$fechaf[0]-12; $mesnf=$fechaf[1]; $dianf=$fechaf[2];
    $edadpi=$fechai[0]-$_POST["edadi"];
    $edadpf=$fechai[0]-$_POST["edadf"];

    //total de reservaciones
    $ResRes=mysqli_query($conn, "SELECT * FROM reservacion as r 
                                    INNER JOIN pacientes as p ON p.Id=r.IdPaciente 
                                    WHERE p.FechaNacimiento < '".$edadpi."-".$mesni."-".$diani."' AND p.FechaNacimiento >= '".$edadpf."-".$mesni."-".$diani."' 
                                    AND p.Estado LIKE '".$_POST["estados"]."' AND r.Instituto LIKE '".$_POST["hospitales"]."' AND r.Diagnostico LIKE '".$_POST["enfermedades"]."' AND r.Fecha >= '".$_POST["periodode"]."' 
                                    AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Estatus LIKE '".$_POST["reservaciones"]."' AND p.Sexo LIKE '".$_POST["genero"]."' 
                                    ORDER BY r.Fecha DESC");
    $TReservaciones=mysqli_num_rows($ResRes);
    //total de ocupaciones
    $ResOcu=mysqli_query($conn, "SELECT * FROM reservacion as r
                                    INNER JOIN pacientes as p ON p.Id=r.IdPaciente 
                                    WHERE p.FechaNacimiento < '".$edadpi."-".$mesni."-".$diani."' AND p.FechaNacimiento >= '".$edadpf."-".$mesni."-".$diani."'
                                    AND p.Estado LIKE '".$_POST["estados"]."' AND r.Instituto LIKE '".$_POST["hospitales"]."' AND r.Diagnostico LIKE '".$_POST["enfermedades"]."' AND r.Fecha >= '".$_POST["periodode"]."' 
                                    AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Estatus=1 AND p.Sexo LIKE '".$_POST["genero"]."' 
                                    ORDER BY r.Fecha DESC");
    $TOcupaciones=mysqli_num_rows($ResOcu);
    //total por confirmar
    $ResCon=mysqli_query($conn, "SELECT * FROM reservacion as r
                                    INNER JOIN pacientes as p ON p.Id=r.IdPaciente
                                    WHERE p.FechaNacimiento < '".$edadpi."-".$mesni."-".$diani."' AND p.FechaNacimiento >= '".$edadpf."-".$mesni."-".$diani."' 
                                    AND p.Estado LIKE '".$_POST["estados"]."' AND r.Instituto LIKE '".$_POST["hospitales"]."' AND r.Diagnostico LIKE '".$_POST["enfermedades"]."' AND r.Fecha >= '".$_POST["periodode"]."' 
                                    AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Estatus=0 AND p.Sexo LIKE '".$_POST["genero"]."' 
                                    ORDER BY r.Fecha DESC");
    $TpConfirmar=mysqli_num_rows($ResCon);
    //total cancelaciones
    $ResCan=mysqli_query($conn, "SELECT * FROM reservacion as r 
                                    INNER JOIN pacientes as p ON p.Id=r.IdPaciente 
                                    WHERE p.FechaNacimiento < '".$edadpi."-".$mesni."-".$diani."' AND p.FechaNacimiento >= '".$edadpf."-".$mesni."-".$diani."' 
                                    AND p.Estado LIKE '".$_POST["estados"]."' AND r.Instituto LIKE '".$_POST["hospitales"]."' AND r.Diagnostico LIKE '".$_POST["enfermedades"]."' AND r.Fecha >= '".$_POST["periodode"]."' 
                                    AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Estatus=2 AND p.Sexo LIKE '".$_POST["genero"]."' 
                                    ORDER BY r.Fecha DESC");
    $TCancelaciones=mysqli_num_rows($ResCan);

    //Servicios
    //hospedajes
    if($_POST["personas"]=='P' OR $_POST["personas"]=='%')
    {
        $ResHospedajesP=mysqli_fetch_array(mysqli_query($conn, "SELECT count(r.Id) AS hospedajes FROM reservaciones as r
                                                            INNER JOIN pacientes as p ON p.Id=r.IdPA
                                                            INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                                            WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND p.FechaNacimiento < '".$edadpi."-".$mesnf."-".$dianf."' 
                                                            AND p.Estado LIKE '".$_POST["estados"]."' AND p.FechaNacimiento >= '".$edadpf."-".$mesnf."-".$dianf."' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Cama>0 
                                                            AND r.Estatus='1' AND r.Tipo LIKE 'P' AND p.Sexo LIKE '".$_POST["genero"]."'"));
    }
    if($_POST["personas"]=='A' OR $_POST["personas"]=='%')
    {
        $ResHospedajesA=mysqli_fetch_array(mysqli_query($conn, "SELECT count(r.Id) AS hospedajes FROM reservaciones as r
                                                            INNER JOIN acompannantes as a ON a.Id=r.IdPA
                                                            INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                                            WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND a.FechaNacimiento < '".$edadpi."-".$mesnf."-".$dianf."' 
                                                            AND a.Estado LIKE '".$_POST["estados"]."' AND a.FechaNacimiento >= '".$edadpf."-".$mesnf."-".$dianf."' 
                                                            AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Cama>0 AND r.Estatus='1' AND r.Tipo LIKE 'A' AND a.Sexo LIKE '".$_POST["genero"]."'"));
    }
    
    $ResHospedajes=$ResHospedajesP["hospedajes"]+$ResHospedajesA["hospedajes"];
    
    $alimentos=$ResHospedajes*3;
    $lavanderia=$ResHospedajes/7;

    $_SESSION["rep_per"][2][0]=$ResHospedajes;
    $_SESSION["rep_per"][2][1]=$alimentos;
    $_SESSION["rep_per"][2][2]=$lavanderia;

    //total de personas atendidas
    if($_POST["personas"]=='P' OR $_POST["personas"]=='%')
    {
        $TPersonasP=mysqli_num_rows(mysqli_query($conn, "SELECT concat_ws('-', r.IdPA, r.Tipo) AS idpa FROM reservaciones AS r 
                                                            INNER JOIN pacientes as p ON p.Id=r.IdPA
                                                            INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                                            WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND p.FechaNacimiento < '".$edadpi."-".$mesnf."-".$dianf."' AND p.FechaNacimiento >= '".$edadpf."-".$mesnf."-".$dianf."' 
                                                            AND p.Estado LIKE '".$_POST["estados"]."' AND p.Sexo LIKE '".$_POST["genero"]."' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Estatus LIKE '".$_POST["reservaciones"]."' AND r.Tipo LIKE 'P' AND r.Cama>0
                                                            GROUP BY concat_ws('-', r.IdPA, r.Tipo)"));
    }
    if($_POST["personas"]=='A' OR $_POST["personas"]=='%')
    {
        $TPersonasA=mysqli_num_rows(mysqli_query($conn, "SELECT concat_ws('-', r.IdPA, r.Tipo) AS idpa FROM reservaciones AS r 
                                                            INNER JOIN acompannantes as a ON a.Id=r.IdPA
                                                            INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                                            WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND a.FechaNacimiento < '".$edadpi."-".$mesnf."-".$dianf."' AND a.FechaNacimiento >= '".$edadpf."-".$mesnf."-".$dianf."' 
                                                            AND a.Estado LIKE '".$_POST["estados"]."' AND a.Sexo LIKE '".$_POST["genero"]."' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Estatus LIKE '".$_POST["reservaciones"]."' AND r.Tipo LIKE 'A' AND r.Cama>0 
                                                            GROUP BY concat_ws('-', r.IdPA, r.Tipo)"));
    }

    $TPersonas=$TPersonasP+$TPersonasA;
    $_SESSION["rep_per"][3][0]=$TPersonas;

    if($_POST["personas"]=='P' OR $_POST["personas"]=='%')
    {
        //total pacientes
        $TP=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                                    INNER JOIN pacientes AS p ON p.Id=r.IdPA
                                                    INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                                    WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND p.FechaNacimiento < '".$edadpi."-".$mesnf."-".$dianf."' AND p.FechaNacimiento >= '".$edadpf."-".$mesni."-".$diani."'  
                                                    AND p.Estado LIKE '".$_POST["estados"]."' AND r.IdPA!=0 AND r.Tipo='P' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Cama>0 AND r.Estatus=1 AND p.Sexo LIKE '".$_POST["genero"]."' 
                                                    GROUP BY r.IdPA"));
        
        if($_POST["genero"]=='%' OR $_POST["genero"]=='M')
        {
            //total pacientes hombres
            $TPH=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                                        INNER JOIN pacientes AS p ON p.Id=r.IdPA
                                                        INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                                        WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND p.FechaNacimiento < '".$edadpi."-".$mesnf."-".$dianf."' AND p.FechaNacimiento >= '".$edadpf."-".$mesnf."-".$dianf."'
                                                        AND p.Estado LIKE '".$_POST["estados"]."' AND r.IdPA!=0 AND r.Tipo='P' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND p.Sexo='M' AND r.Cama>0 AND r.Estatus=1 
                                                        GROUP BY r.IdPA"));

            //total pacientes hombres niños
            $TPHN=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                                        INNER JOIN pacientes AS p ON p.Id=r.IdPA
                                                        INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                                        WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND p.FechaNacimiento < '".$edadpi."-".$mesnf."-".$dianf."' AND p.FechaNacimiento >= '".$edadpf."-".$mesnf."-".$dianf."'
                                                        AND p.Estado LIKE '".$_POST["estados"]."' AND r.IdPA!=0 AND r.Tipo='P' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND p.Sexo='M' AND p.FechaNacimiento>'".$edadni."-".$mesnf."-".$dianf."' AND r.Cama>0 AND r.Estatus=1 
                                                        GROUP BY r.IdPA"));

            //total pacientes hombres adultos
            $TPHA=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                                        INNER JOIN pacientes AS p ON p.Id=r.IdPA
                                                        INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                                        WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND p.FechaNacimiento < '".$edadpi."-".$mesnf."-".$dianf."' AND p.FechaNacimiento >= '".$edadpf."-".$mesnf."-".$dianf."' 
                                                        AND p.Estado LIKE '".$_POST["estados"]."' AND r.IdPA!=0 AND r.Tipo='P' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND p.Sexo='M' AND p.FechaNacimiento<'".$edadni."-".$mesnf."-".$dianf."' AND r.Cama>0 AND r.Estatus=1 
                                                        GROUP BY r.IdPA"));

        }
        if($_POST["genero"]=='%' OR $_POST["genero"]=='F')
        {
            //total pacientes mujeres
            $TPM=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                                        INNER JOIN pacientes AS p ON p.Id=r.IdPA
                                                        INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                                        WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND p.FechaNacimiento < '".$edadpi."-".$mesnf."-".$dianf."' AND p.FechaNacimiento >= '".$edadpf."-".$mesnf."-".$dianf."' 
                                                        AND p.Estado LIKE '".$_POST["estados"]."' AND r.IdPA!=0 AND r.Tipo='P' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND p.Sexo='F' AND r.Cama>0 AND r.Estatus=1 
                                                        GROUP BY r.IdPA"));
            //total pacientes mujeres niñas
            $TPMN=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                                        INNER JOIN pacientes AS p ON p.Id=r.IdPA
                                                        INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                                        WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND p.FechaNacimiento < '".$edadpi."-".$mesnf."-".$dianf."' AND p.FechaNacimiento >= '".$edadpf."-".$mesnf."-".$dianf."' 
                                                        AND p.Estado LIKE '".$_POST["estados"]."' AND r.IdPA!=0 AND r.Tipo='P' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND p.Sexo='F' AND p.FechaNacimiento>='".$edadni."-".$mesni."-".$diani."' AND r.Cama>0 AND r.Estatus=1 
                                                        GROUP BY r.IdPA"));
            //total pacientes mujeres adultas
            $TPMA=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                                        INNER JOIN pacientes AS p ON p.Id=r.IdPA
                                                        INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                                        WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND p.FechaNacimiento < '".$edadpi."-".$mesnf."-".$dianf."' AND p.FechaNacimiento >= '".$edadpf."-".$mesnf."-".$dianf."' 
                                                        AND p.Estado LIKE '".$_POST["estados"]."' AND r.IdPA!=0 AND r.Tipo='P' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND p.Sexo='F' AND p.FechaNacimiento<'".$edadni."-".$mesni."-".$diani."' AND r.Cama>0 AND r.Estatus=1 
                                                        GROUP BY r.IdPA"));
        }
    }
    
    if($_POST["personas"]=='A' OR $_POST["personas"]=='%')
    {
        //total acompañantes
        $TA=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                                    INNER JOIN acompannantes AS a ON a.Id=r.IdPA
                                                    INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                                    WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND a.FechaNacimiento < '".$edadpi."-".$mesnf."-".$dianf."' AND a.FechaNacimiento >= '".$edadpf."-".$mesnf."-".$dianf."' 
                                                    AND a.Estado LIKE '".$_POST["estados"]."' AND r.IdPA!=0 AND r.Tipo='A' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Estatus=1 AND a.Sexo LIKE '".$_POST["genero"]."' 
                                                    GROUP BY r.IdPA"));

        if($_POST["genero"]=='%' OR $_POST["genero"]=='M')
        {
            //total acompañantes hombres
            $TAH=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                                        INNER JOIN acompannantes AS a ON a.Id=r.IdPA
                                                        INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                                        WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND a.FechaNacimiento < '".$edadpi."-".$mesnf."-".$dianf."' AND a.FechaNacimiento >= '".$edadpf."-".$mesnf."-".$dianf."' 
                                                        AND a.Estado LIKE '".$_POST["estados"]."' AND r.IdPA!=0 AND r.Tipo='A' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND a.Sexo='M' AND r.Estatus=1 
                                                        GROUP BY r.IdPA"));
            //total acompañantes hombres niños
            $TAHN=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                                        INNER JOIN acompannantes AS a ON a.Id=r.IdPA
                                                        INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                                        WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND a.FechaNacimiento < '".$edadpi."-".$mesnf."-".$dianf."' AND a.FechaNacimiento >= '".$edadpf."-".$mesnf."-".$dianf."' 
                                                        AND a.Estado LIKE '".$_POST["estados"]."' AND r.IdPA!=0 AND r.Tipo='A' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND a.Sexo='M' AND a.FechaNacimiento>='".$edadni."-".$mesni."-".$diani."' AND r.Estatus=1 
                                                        GROUP BY r.IdPA"));
            //total acompañantes hombres adultos
            $TAHA=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                                        INNER JOIN acompannantes AS a ON a.Id=r.IdPA
                                                        INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                                        WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND a.FechaNacimiento < '".$edadpi."-".$mesnf."-".$dianf."' AND a.FechaNacimiento >= '".$edadpf."-".$mesnf."-".$dianf."' 
                                                        AND a.Estado LIKE '".$_POST["estados"]."' AND r.IdPA!=0 AND r.Tipo='A' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND a.Sexo='M' AND a.FechaNacimiento<'".$edadni."-".$mesni."-".$diani."' AND r.Estatus=1 
                                                        GROUP BY r.IdPA"));
        }
        if($_POST["genero"]=='%' OR $_POST["genero"]=='F')
        {
            //total acompañantes mujeres
            $TAM=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                                        INNER JOIN acompannantes AS a ON a.Id=r.IdPA
                                                        INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                                        WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND a.FechaNacimiento < '".$edadpi."-".$mesnf."-".$dianf."' AND a.FechaNacimiento >= '".$edadpf."-".$mesnf."-".$dianf."'
                                                        AND a.Estado LIKE '".$_POST["estados"]."' AND r.IdPA!=0 AND r.Tipo='A' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND a.Sexo='F' AND r.Estatus=1 
                                                        GROUP BY r.IdPA"));
            //total acompañantes mujeres niñas
            $TAMN=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                                        INNER JOIN acompannantes AS a ON a.Id=r.IdPA
                                                        INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                                        WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND a.FechaNacimiento < '".$edadpi."-".$mesnf."-".$dianf."' AND a.FechaNacimiento >= '".$edadpf."-".$mesnf."-".$dianf."' 
                                                        AND a.Estado LIKE '".$_POST["estados"]."' AND r.IdPA!=0 AND r.Tipo='A' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND a.Sexo='F' AND a.FechaNacimiento>='".$edadni."-".$mesni."-".$diani."' AND r.Estatus=1 
                                                        GROUP BY r.IdPA"));
            //total acompañantes mujeres adultas
            $TAMA=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                                        INNER JOIN acompannantes AS a ON a.Id=r.IdPA
                                                        INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                                        WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND a.FechaNacimiento < '".$edadpi."-".$mesnf."-".$dianf."' AND a.FechaNacimiento >= '".$edadpf."-".$mesnf."-".$dianf."' 
                                                        AND a.Estado LIKE '".$_POST["estados"]."' AND r.IdPA!=0 AND r.Tipo='A' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND a.Sexo='F' AND a.FechaNacimiento<'".$edadni."-".$mesni."-".$diani."' AND r.Estatus=1 
                                                        GROUP BY r.IdPA"));
        }
    }

    //edades pacientes
    $Yi=$fechai[0]; $Mi=$fechai[1]; $Di=$fechai[2];
    $edadi=$_POST["edadi"];
    $edadf=$_POST["edadf"];

    //edad paciente
    if($edadi==0 AND $edadf==100)
    {
        //0 a 12 años
        $ResDoce=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
                                        FROM (SELECT r.IdPA AS Id, p.FechaNacimiento FROM `reservaciones` AS r 
                                        INNER JOIN pacientes AS p ON p.Id=r.IdPA 
                                        INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                        WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Cama > 0 AND r.Tipo = 'P' AND r.Estatus=1 
                                        AND p.Estado LIKE '".$_POST["estados"]."' AND p.FechaNacimiento < '".($Yi-$edadi)."-".$Mi."-".$Di."' AND p.FechaNacimiento >= '".($Yi-12)."-".$Mi."-".$Di."' AND p.Sexo LIKE '".$_POST["genero"]."' 
                                        GROUP BY r.IdPA) AS s");
        $RResDoce=mysqli_fetch_array($ResDoce);
        $ResDoce_f=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
                                        FROM (SELECT r.IdPA AS Id, p.FechaNacimiento FROM `reservaciones` AS r 
                                        INNER JOIN pacientes AS p ON p.Id=r.IdPA 
                                        INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                        WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Cama > 0 AND r.Tipo = 'P' AND r.Estatus=1 
                                        AND p.Sexo='F' AND p.Estado LIKE '".$_POST["estados"]."' AND p.FechaNacimiento < '".($Yi-$edadi)."-".$Mi."-".$Di."' AND p.FechaNacimiento >= '".($Yi-12)."-".$Mi."-".$Di."' 
                                        GROUP BY r.IdPA) AS s");
        $RResDoce_f=mysqli_fetch_array($ResDoce_f);
        $ResDoce_m=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
                                        FROM (SELECT r.IdPA AS Id, p.FechaNacimiento FROM `reservaciones` AS r 
                                        INNER JOIN pacientes AS p ON p.Id=r.IdPA 
                                        INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                        WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Cama > 0 AND r.Tipo = 'P' AND r.Estatus=1 
                                        AND p.Sexo='M' AND p.Estado LIKE '".$_POST["estados"]."' AND p.FechaNacimiento < '".($Yi-$edadi)."-".$Mi."-".$Di."' AND p.FechaNacimiento >= '".($Yi-12)."-".$Mi."-".$Di."' 
                                        GROUP BY r.IdPA) AS s");
        $RResDoce_m=mysqli_fetch_array($ResDoce_m);
        //13 a 20 años
        $ResVeinte=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
                                        FROM (SELECT r.IdPA AS Id, p.FechaNacimiento FROM `reservaciones` AS r 
                                        INNER JOIN pacientes AS p ON p.Id=r.IdPA 
                                        INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                        WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Cama > 0 AND r.Tipo = 'P' AND r.Estatus=1 
                                        AND p.Estado LIKE '".$_POST["estados"]."' AND p.FechaNacimiento < '".($Yi-12)."-".$Mi."-".$Di."' AND p.FechaNacimiento >= '".($Yi-20)."-".$Mi."-".$Di."' AND p.Sexo LIKE '".$_POST["genero"]."'  
                                        GROUP BY r.IdPA) AS s");
        $RResVeinte=mysqli_fetch_array($ResVeinte);
        $ResVeinte_m=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
                                        FROM (SELECT r.IdPA AS Id, p.FechaNacimiento FROM `reservaciones` AS r 
                                        INNER JOIN pacientes AS p ON p.Id=r.IdPA 
                                        INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                        WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Cama > 0 AND r.Tipo = 'P' AND r.Estatus=1 
                                        AND p.Sexo='M' AND p.Estado LIKE '".$_POST["estados"]."' AND p.FechaNacimiento < '".($Yi-12)."-".$Mi."-".$Di."' AND p.FechaNacimiento >= '".($Yi-20)."-".$Mi."-".$Di."' 
                                        GROUP BY r.IdPA) AS s");
        $RResVeinte_m=mysqli_fetch_array($ResVeinte_m);
        $ResVeinte_f=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
                                        FROM (SELECT r.IdPA AS Id, p.FechaNacimiento FROM `reservaciones` AS r 
                                        INNER JOIN pacientes AS p ON p.Id=r.IdPA 
                                        INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                        WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Cama > 0 AND r.Tipo = 'P' AND r.Estatus=1 
                                        AND p.Sexo='F' AND p.Estado LIKE '".$_POST["estados"]."' AND p.FechaNacimiento < '".($Yi-12)."-".$Mi."-".$Di."' AND p.FechaNacimiento >= '".($Yi-20)."-".$Mi."-".$Di."' 
                                        GROUP BY r.IdPA) AS s");
        $RResVeinte_f=mysqli_fetch_array($ResVeinte_f);
        //21 a 64 años
        $ResSesentaycuatro=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
                                        FROM (SELECT r.IdPA AS Id, p.FechaNacimiento FROM `reservaciones` AS r 
                                        INNER JOIN pacientes AS p ON p.Id=r.IdPA 
                                        INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                        WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Cama > 0 AND r.Tipo = 'P' AND r.Estatus=1 
                                        AND p.Estado LIKE '".$_POST["estados"]."' AND p.FechaNacimiento < '".($Yi-20)."-".$Mi."-".$Di."' AND p.FechaNacimiento >= '".($Yi-64)."-".$Mi."-".$Di."' AND p.Sexo LIKE '".$_POST["genero"]."'  
                                        GROUP BY r.IdPA) AS s");
        $RResSesentaycuatro=mysqli_fetch_array($ResSesentaycuatro);
        $ResSesentaycuatro_m=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
                                        FROM (SELECT r.IdPA AS Id, p.FechaNacimiento FROM `reservaciones` AS r 
                                        INNER JOIN pacientes AS p ON p.Id=r.IdPA 
                                        INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                        WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Cama > 0 AND r.Tipo = 'P' AND r.Estatus=1 
                                        AND p.Sexo='M' AND p.Estado LIKE '".$_POST["estados"]."' AND p.FechaNacimiento < '".($Yi-20)."-".$Mi."-".$Di."' AND p.FechaNacimiento >= '".($Yi-64)."-".$Mi."-".$Di."' 
                                        GROUP BY r.IdPA) AS s");
        $RResSesentaycuatro_m=mysqli_fetch_array($ResSesentaycuatro_m);
        $ResSesentaycuatro_f=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
                                        FROM (SELECT r.IdPA AS Id, p.FechaNacimiento FROM `reservaciones` AS r 
                                        INNER JOIN pacientes AS p ON p.Id=r.IdPA 
                                        INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                        WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Cama > 0 AND r.Tipo = 'P' AND r.Estatus=1 
                                        AND p.Sexo='F' AND p.Estado LIKE '".$_POST["estados"]."' AND p.FechaNacimiento < '".($Yi-20)."-".$Mi."-".$Di."' AND p.FechaNacimiento >= '".($Yi-64)."-".$Mi."-".$Di."' 
                                        GROUP BY r.IdPA) AS s");
        $RResSesentaycuatro_f=mysqli_fetch_array($ResSesentaycuatro_f);
        //65 y mas años
        $ResMas=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
                                        FROM (SELECT r.IdPA AS Id, p.FechaNacimiento FROM `reservaciones` AS r 
                                        INNER JOIN pacientes AS p ON p.Id=r.IdPA 
                                        INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                        WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Cama > 0 AND r.Tipo = 'P' AND r.Estatus=1 
                                        AND p.Estado LIKE '".$_POST["estados"]."' AND p.FechaNacimiento < '".($Yi-64)."-".$Mi."-".$Di."' AND p.Sexo LIKE '".$_POST["genero"]."'  
                                        GROUP BY r.IdPA) AS s");
        $RResMas=mysqli_fetch_array($ResMas);
        $ResMas_m=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
                                        FROM (SELECT r.IdPA AS Id, p.FechaNacimiento FROM `reservaciones` AS r 
                                        INNER JOIN pacientes AS p ON p.Id=r.IdPA 
                                        INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                        WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Cama > 0 AND r.Tipo = 'P' AND r.Estatus=1 
                                        AND p.Sexo='M' AND p.Estado LIKE '".$_POST["estados"]."' AND p.FechaNacimiento < '".($Yi-64)."-".$Mi."-".$Di."' 
                                        GROUP BY r.IdPA) AS s");
        $RResMas_m=mysqli_fetch_array($ResMas_m);
        $ResMas_f=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
                                        FROM (SELECT r.IdPA AS Id, p.FechaNacimiento FROM `reservaciones` AS r 
                                        INNER JOIN pacientes AS p ON p.Id=r.IdPA 
                                        INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                        WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Cama > 0 AND r.Tipo = 'P' AND r.Estatus=1 
                                        AND Sexo='F' AND p.Estado LIKE '".$_POST["estados"]."' AND p.FechaNacimiento < '".($Yi-64)."-".$Mi."-".$Di."' 
                                        GROUP BY r.IdPA) AS s");
        $RResMas_f=mysqli_fetch_array($ResMas_f);
    }
    else
    {
        $ResEdad=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
                                    FROM (SELECT r.IdPA AS Id, p.FechaNacimiento FROM `reservaciones` AS r 
                                    INNER JOIN pacientes AS p ON p.Id=r.IdPA 
                                    INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                    WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Cama > 0 AND r.Tipo = 'P' AND r.Estatus=1 
                                    AND p.Estado LIKE '".$_POST["estados"]."' AND p.FechaNacimiento < '".($Yi-$edadi)."-".$Mi."-".$Di."' AND p.FechaNacimiento >= '".($Yi-$edadf)."-".$Mi."-".$Di."' AND p.Sexo LIKE '".$_POST["genero"]."'  
                                    GROUP BY r.IdPA) AS s");
        $RResEdad=mysqli_fetch_array($ResEdad);
        $ResEdad_m=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
                                    FROM (SELECT r.IdPA AS Id, p.FechaNacimiento FROM `reservaciones` AS r 
                                    INNER JOIN pacientes AS p ON p.Id=r.IdPA 
                                    INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                    WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Cama > 0 AND r.Tipo = 'P' AND r.Estatus=1 
                                    AND p.Sexo='M' AND p.Estado LIKE '".$_POST["estados"]."' AND p.FechaNacimiento < '".($Yi-$edadi)."-".$Mi."-".$Di."' AND p.FechaNacimiento >= '".($Yi-$edadf)."-".$Mi."-".$Di."' 
                                    GROUP BY r.IdPA) AS s");
        $RResEdad_m=mysqli_fetch_array($ResEdad_m);
        $ResEdad_f=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
                                    FROM (SELECT r.IdPA AS Id, p.FechaNacimiento FROM `reservaciones` AS r 
                                    INNER JOIN pacientes AS p ON p.Id=r.IdPA 
                                    INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                    WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Cama > 0 AND r.Tipo = 'P' AND r.Estatus=1 
                                    AND p.Sexo='F' AND p.Estado LIKE '".$_POST["estados"]."' AND p.FechaNacimiento < '".($Yi-$edadi)."-".$Mi."-".$Di."' AND p.FechaNacimiento >= '".($Yi-$edadf)."-".$Mi."-".$Di."' 
                                    GROUP BY r.IdPA) AS s");
        $RResEdad_f=mysqli_fetch_array($ResEdad_f);

    }
    
    //edad acompañantes
    if($edadi==0 AND $edadf==100)
    {
        //0 a 12 años
        $ResDoceA=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
                                        FROM (SELECT r.IdPA AS Id, a.FechaNacimiento FROM `reservaciones` AS r 
                                        INNER JOIN acompannantes AS a ON a.Id=r.IdPA 
                                        INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                        WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Cama > 0 AND r.Tipo = 'A' AND r.Estatus=1 
                                        AND a.Estado LIKE '".$_POST["estados"]."' AND a.FechaNacimiento < '".($Yi-$edadi)."-".$Mi."-".$Di."' AND a.FechaNacimiento >= '".($Yi-12)."-".$Mi."-".$Di."' AND a.Sexo LIKE '".$_POST["genero"]."'  
                                        GROUP BY r.IdPA) AS s");
        $RResDoceA=mysqli_fetch_array($ResDoceA);
        $ResDoceA_m=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
                                        FROM (SELECT r.IdPA AS Id, a.FechaNacimiento FROM `reservaciones` AS r 
                                        INNER JOIN acompannantes AS a ON a.Id=r.IdPA 
                                        INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                        WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Cama > 0 AND r.Tipo = 'A' AND r.Estatus=1 
                                        AND a.Sexo='M' AND a.Estado LIKE '".$_POST["estados"]."' AND a.FechaNacimiento < '".($Yi-$edadi)."-".$Mi."-".$Di."' AND a.FechaNacimiento >= '".($Yi-12)."-".$Mi."-".$Di."' 
                                        GROUP BY r.IdPA) AS s");
        $RResDoceA_m=mysqli_fetch_array($ResDoceA_m);
        $ResDoceA_f=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
                                        FROM (SELECT r.IdPA AS Id, a.FechaNacimiento FROM `reservaciones` AS r 
                                        INNER JOIN acompannantes AS a ON a.Id=r.IdPA 
                                        INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                        WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Cama > 0 AND r.Tipo = 'A' AND r.Estatus=1 
                                        AND a.Sexo='F' AND a.Estado LIKE '".$_POST["estados"]."' AND a.FechaNacimiento < '".($Yi-$edadi)."-".$Mi."-".$Di."' AND a.FechaNacimiento >= '".($Yi-12)."-".$Mi."-".$Di."' 
                                        GROUP BY r.IdPA) AS s");
        $RResDoceA_f=mysqli_fetch_array($ResDoceA_f);
        //13 a 20 años
        $ResVeinteA=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
                                        FROM (SELECT r.IdPA AS Id, a.FechaNacimiento FROM `reservaciones` AS r 
                                        INNER JOIN acompannantes AS a ON a.Id=r.IdPA 
                                        INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                        WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Cama > 0 AND r.Tipo = 'A' AND r.Estatus=1 
                                        AND a.Estado LIKE '".$_POST["estados"]."' AND a.FechaNacimiento < '".($Yi-12)."-".$Mi."-".$Di."' AND a.FechaNacimiento >= '".($Yi-20)."-".$Mi."-".$Di."' AND a.Sexo LIKE '".$_POST["genero"]."'  
                                        GROUP BY r.IdPA) AS s");
        $RResVeinteA=mysqli_fetch_array($ResVeinteA);
        $ResVeinteA_m=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
                                        FROM (SELECT r.IdPA AS Id, a.FechaNacimiento FROM `reservaciones` AS r 
                                        INNER JOIN acompannantes AS a ON a.Id=r.IdPA 
                                        INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                        WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Cama > 0 AND r.Tipo = 'A' AND r.Estatus=1 
                                        AND a.Sexo='M' AND a.Estado LIKE '".$_POST["estados"]."' AND a.FechaNacimiento < '".($Yi-12)."-".$Mi."-".$Di."' AND a.FechaNacimiento >= '".($Yi-20)."-".$Mi."-".$Di."' 
                                        GROUP BY r.IdPA) AS s");
        $RResVeinteA_m=mysqli_fetch_array($ResVeinteA_m);
        $ResVeinteA_f=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
                                        FROM (SELECT r.IdPA AS Id, a.FechaNacimiento FROM `reservaciones` AS r 
                                        INNER JOIN acompannantes AS a ON a.Id=r.IdPA 
                                        INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                        WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Cama > 0 AND r.Tipo = 'A' AND r.Estatus=1 
                                        AND a.Sexo='F' AND a.Estado LIKE '".$_POST["estados"]."' AND a.FechaNacimiento < '".($Yi-12)."-".$Mi."-".$Di."' AND a.FechaNacimiento >= '".($Yi-20)."-".$Mi."-".$Di."' 
                                        GROUP BY r.IdPA) AS s");
        $RResVeinteA_f=mysqli_fetch_array($ResVeinteA_f);
        //21 a 64 años
        $ResSesentaycuatroA=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
                                        FROM (SELECT r.IdPA AS Id, a.FechaNacimiento FROM `reservaciones` AS r 
                                        INNER JOIN acompannantes AS a ON a.Id=r.IdPA 
                                        INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                        WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Cama > 0 AND r.Tipo = 'A' AND r.Estatus=1 
                                        AND a.Estado LIKE '".$_POST["estados"]."' AND a.FechaNacimiento < '".($Yi-20)."-".$Mi."-".$Di."' AND a.FechaNacimiento >= '".($Yi-64)."-".$Mi."-".$Di."' AND a.Sexo LIKE '".$_POST["genero"]."'  
                                        GROUP BY r.IdPA) AS s");
        $RResSesentaycuatroA=mysqli_fetch_array($ResSesentaycuatroA);
        $ResSesentaycuatroA_m=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
                                        FROM (SELECT r.IdPA AS Id, a.FechaNacimiento FROM `reservaciones` AS r 
                                        INNER JOIN acompannantes AS a ON a.Id=r.IdPA 
                                        INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                        WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Cama > 0 AND r.Tipo = 'A' AND r.Estatus=1 
                                        AND a.Sexo='M' AND a.Estado LIKE '".$_POST["estados"]."' AND a.FechaNacimiento < '".($Yi-20)."-".$Mi."-".$Di."' AND a.FechaNacimiento >= '".($Yi-64)."-".$Mi."-".$Di."' 
                                        GROUP BY r.IdPA) AS s");
        $RResSesentaycuatroA_m=mysqli_fetch_array($ResSesentaycuatroA_m);
        $ResSesentaycuatroA_f=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
                                        FROM (SELECT r.IdPA AS Id, a.FechaNacimiento FROM `reservaciones` AS r 
                                        INNER JOIN acompannantes AS a ON a.Id=r.IdPA 
                                        INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                        WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Cama > 0 AND r.Tipo = 'A' AND r.Estatus=1 
                                        AND a.Sexo='F' AND a.Estado LIKE '".$_POST["estados"]."' AND a.FechaNacimiento < '".($Yi-20)."-".$Mi."-".$Di."' AND a.FechaNacimiento >= '".($Yi-64)."-".$Mi."-".$Di."' 
                                        GROUP BY r.IdPA) AS s");
        $RResSesentaycuatroA_f=mysqli_fetch_array($ResSesentaycuatroA_f);
        //65 y mas años
        $ResMasA=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
                                        FROM (SELECT r.IdPA AS Id, a.FechaNacimiento FROM `reservaciones` AS r 
                                        INNER JOIN acompannantes AS a ON a.Id=r.IdPA 
                                        INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                        WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Cama > 0 AND r.Tipo = 'A' AND r.Estatus=1 
                                        AND a.Estado LIKE '".$_POST["estados"]."' AND a.FechaNacimiento < '".($Yi-64)."-".$Mi."-".$Di."' AND a.Sexo LIKE '".$_POST["genero"]."'  
                                        GROUP BY r.IdPA) AS s");
        $RResMasA=mysqli_fetch_array($ResMasA);
        $ResMasA_m=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
                                        FROM (SELECT r.IdPA AS Id, a.FechaNacimiento FROM `reservaciones` AS r 
                                        INNER JOIN acompannantes AS a ON a.Id=r.IdPA 
                                        INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                        WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Cama > 0 AND r.Tipo = 'A' AND r.Estatus=1 
                                        AND a.Sexo='M' AND a.Estado LIKE '".$_POST["estados"]."' AND a.FechaNacimiento < '".($Yi-64)."-".$Mi."-".$Di."' 
                                        GROUP BY r.IdPA) AS s");
        $RResMasA_m=mysqli_fetch_array($ResMasA_m);
        $ResMasA_f=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
                                        FROM (SELECT r.IdPA AS Id, a.FechaNacimiento FROM `reservaciones` AS r 
                                        INNER JOIN acompannantes AS a ON a.Id=r.IdPA 
                                        INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                        WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Cama > 0 AND r.Tipo = 'A' AND r.Estatus=1 
                                        AND a.Sexo='F' AND a.Estado LIKE '".$_POST["estados"]."' AND a.FechaNacimiento < '".($Yi-64)."-".$Mi."-".$Di."' 
                                        GROUP BY r.IdPA) AS s");
        $RResMasA_f=mysqli_fetch_array($ResMasA_f);
    }
    else
    {
        $ResEdadA=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
                                    FROM (SELECT r.IdPA AS Id, a.FechaNacimiento FROM `reservaciones` AS r 
                                    INNER JOIN acompannantes AS a ON a.Id=r.IdPA 
                                    INNER JOIN reservacion as re ON re.Id=r.IdReservacion
                                    WHERE re.Instituto LIKE '".$_POST["hospitales"]."' AND re.Diagnostico LIKE '".$_POST["enfermedades"]."' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Cama > 0 AND r.Tipo = 'A' AND r.Estatus=1 
                                    AND a.Estado LIKE '".$_POST["estados"]."' AND a.FechaNacimiento < '".($Yi-$edadi)."-".$Mi."-".$Di."' AND a.FechaNacimiento >= '".($Yi-$edadf)."-".$Mi."-".$Di."' AND a.Sexo LIKE '".$_POST["genero"]."'  
                                    GROUP BY r.IdPA) AS s");
        $RResEdadA=mysqli_fetch_array($ResEdadA);
    }
    

    //enfermedades
    $ResEnfermedades=mysqli_query($conn, "SELECT r.Diagnostico AS Diagnostico, COUNT(*) AS Numero FROM `reservacion`AS r 
                                            INNER JOIN pacientes AS p ON p.Id=r.IdPaciente 
                                            WHERE r.Instituto LIKE '".$_POST["hospitales"]."' AND r.Diagnostico LIKE '".$_POST["enfermedades"]."' AND p.FechaNacimiento < '".$edadpi."-".$mesnf."-".$dianf."' AND p.FechaNacimiento >= '".$edadpf."-".$mesnf."-".$dianf."'
                                            AND p.Estado LIKE '".$_POST["estados"]."' AND p.Sexo LIKE '".$_POST["genero"]."' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Diagnostico!='' AND r.Diagnostico LIKE '".$_POST["enfermedades"]."' 
                                            GROUP BY r.Diagnostico 
                                            ORDER BY Numero ASC");
    $Enfermedades=mysqli_num_rows($ResEnfermedades);

    //pacientes por hospital
    $ResHospitales=mysqli_query($conn, "SELECT r.Instituto AS Instituto, COUNT(*) AS Numero FROM reservacion AS r
                                        INNER JOIN pacientes AS p ON p.Id=r.IdPaciente 
                                        WHERE r.Instituto LIKE '".$_POST["hospitales"]."' AND r.Diagnostico LIKE '".$_POST["enfermedades"]."' AND p.FechaNacimiento < '".$edadpi."-".$mesnf."-".$dianf."' AND p.FechaNacimiento >= '".$edadpf."-".$mesnf."-".$dianf."'
                                        AND p.Estado LIKE '".$_POST["estados"]."' AND p.Sexo LIKE '".$_POST["genero"]."' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' AND r.Instituto!='' AND r.Instituto LIKE '".$_POST["hospitales"]."' 
                                        GROUP BY r.Instituto 
                                        ORDER BY Numero ASC");
    $NumHosp=mysqli_num_rows($ResHospitales);

    //pacientes por procedencia
    $ResProcedencia=mysqli_query($conn, "SELECT COUNT(Es.Estado) AS Numero, Es.Estado AS Estado, Es.EId
                                        FROM (SELECT COUNT(E.Estado) AS Pacientes, E.Estado AS Estado, E.Id AS EId
                                            FROM pacientes AS p 
                                            INNER JOIN Estados AS E ON p.Estado=E.Id 
                                            INNER JOIN reservacion AS r ON r.IdPaciente=p.Id 
                                            WHERE E.Id LIKE '".$_POST["estados"]."' AND r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."' 
                                            GROUP BY r.IdPaciente, E.Estado, E.Id) AS Es
                                        GROUP BY Es.Estado, Es.EId ORDER BY Es.Estado ASC");
    $NumEstados=mysqli_num_rows($ResProcedencia);

    

    $cadena.='<div class="c100 card">
            <h2>Resultados</h2>
            <div class="c100" style="display: flex; flex-wrap: wrap; justify-content: center; align-items: center; align-content: center;">
                <div class="c25"></div><div class="c25"></div><div class="c25"></div>
                <div class="c25">
                    <a href="estadisticos/reporte_personalizado_pdf.php" target="_blank"><i class="fas fa-print"></i> Imprimir</a> 
                    <a href="estadisticos/reporte_personalizado_excel.php?periodode='.$_POST["periodode"].'&periodohasta='.$_POST["periodohasta"].'&personas='.$_POST["personas"].'&genero='.$_POST["genero"].'&edadi='.$_POST["edadi"].'&edadf='.$_POST["edadf"].'&reservaciones='.$_POST["reservaciones"].'&enfermedades='.$_POST["enfermedades"].'&hospitales='.$_POST["hospitales"].'&estados='.$_POST["estados"].'" target="_blank"><i class="far fa-file-excel"></i> Exportar</a>
                </div>
            </div>

            <div class="c100 card">
                <div class="c45">
                    <label class="l_form">Reservaciones: '.number_format($TReservaciones).'</label>';
                    if($_POST["reservaciones"]=='%' OR $_POST["reservaciones"]=='1')
                    {
                        $cadena.='<label class="l_form"><i class="fas fa-hotel i_estadistico"></i> Estancias: '.number_format($TOcupaciones).'</label>';
                        $_SESSION["rep_per"][1][0]='Estancias: ';
                        $_SESSION["rep_per"][1][1]=number_format($TOcupaciones);
                    }
                    if($_POST["reservaciones"]=='%' OR $_POST["reservaciones"]=='0')
                    {
                        $cadena.='<label class="l_form"><i class="fas fa-hotel i_estadistico"></i> Por Confirmar: '.number_format($TpConfirmar).'</label>';
                        $_SESSION["rep_per"][1][0]='Por Confirmar: ';
                        $_SESSION["rep_per"][1][1]=number_format($TpConfirmar);
                    }
                    if($_POST["reservaciones"]=='%' OR $_POST["reservaciones"]=='2')
                    {
                        $cadena.='<label class="l_form"><i class="fas fa-hotel i_estadistico"></i> Cancelaciones: '.number_format($TCancelaciones).'</label>';
                        $_SESSION["rep_per"][1][0]='Cancelaciones: ';
                        $_SESSION["rep_per"][1][1]=number_format($TCancelaciones);
                    }
    $cadena.='  </div>
                <div class="c45">
                    <canvas id="myChart_reservaciones"></canvas>
                </div>
                <script>
                    var canvas = document.getElementById("myChart_reservaciones");
                    var res = canvas.getContext(\'2d\');
                    
                    var data = {
                        labels: ["Reservaciones"],
                        datasets: [{
                            label: "Estancias", 
                            data: ['.$TOcupaciones.'],
                            backgroundColor: [\'rgba(55,184,60, 0.2)\'],
                            borderColor: [\'rgba(55,184,60, 1)\'],
                            borderWidth: [2]
                        },
                        {
                            label: "Por Confirmar", 
                            data: ['.$TpConfirmar.'],
                            backgroundColor: [\'rgba(243,243,21, 0.2)\'],
                            borderColor: [\'rgba(243,243,21, 1)\'],
                            borderWidth: [2]
                        },
                        {
                            label: "Canceladas", 
                            data: ['.$TCancelaciones.'],
                            backgroundColor: [\'rgba(255,0,0, 0.2)\'],
                            borderColor: [\'rgba(255,0,0, 1)\'],
                            borderWidth: [2]
                        }]
                    

                    };

                    var options = {
                            rotation: -0.7 * Math.PI
                    };

                    var myPieChartRes = new Chart(res, {
                        type: \'bar\',
                        data: data,
                        options: options
                    });

                </script>
            </div>

            <div class="c100 card">
                <div class="c45">
                    <label class="l_form">Servicios otorgados: '.number_format(($ResHospedajes+$alimentos+$lavanderia)).'</label>
                    <label class="l_form"><i class="fas fa-house-user i_estadistico"></i> Hospedajes: '.number_format($ResHospedajes).'</label>
                    <label class="l_form"><i class="fas fa-utensils i_estadistico"></i> Alimentos: '.number_format($alimentos).'</label>
                    <label class="l_form"><i class="fas fa-tshirt i_estadistico"></i> Lavandería: '.number_format($lavanderia).'</label>
                </div>
                <div class="c45">
                    <canvas id="myChart_servicios"></canvas>
                </div>
                <script>
                    var canvas = document.getElementById("myChart_servicios");
                    var pat = canvas.getContext(\'2d\');
                    
                    var data = {
                        labels: ["Servicios"],
                          datasets: [{
                                label: \'Hospedajes\',
                                data:['.$ResHospedajes.'],
                                backgroundColor: [\'rgba(250, 143, 146, 0.2)\'],
                                borderColor: [\'rgba(250, 143, 146, 1)\'],
                                borderWidth: [2]

                            },
                            {
                                label: \'Alimentos\',
                                data:['.$alimentos.'],
                                backgroundColor: [\'rgba(162,155,238, 0.2)\'],
                                borderColor: [\'rgba(162,155,238, 1)\'],
                                borderWidth: [2]
                            },
                            {
                                label: \'Lavanderia\',
                                data:['.$lavanderia.'],
                                backgroundColor: [\'rgba(8,100,191, 0.2)\'],
                                borderColor: [\'rgba(8,100,191, 1)\'],
                                borderWidth: [2]
                            }]
                    };

                    var options = {
                            rotation: -0.7 * Math.PI
                    };

                    var myPieChartPat = new Chart(pat, {
                        type: \'bar\',
                        data: data,
                        options: options
                    });

                </script>
            </div>

            <div class="c100 card">
                <div class="c45"">
                    <label class="l_form">Total de Albergados: '.number_format($TPersonas).'</label>';
    if($_POST["personas"]=='P' OR $_POST["personas"]=='%')
    {
        $cadena.='  <label class="l_form"><i class="fas fa-user-injured i_estadistico"></i> Pacientes: '.$TP.'</label>';
        $_SESSION["rep_per"][3][1]=$TP;
    }
    if($_POST["personas"]=='A' OR $_POST["personas"]=='%')
    {
        $cadena.='  <label class="l_form"><i class="fas fa-user-friends i_estadistico"></i> Acompañantes: '.$TA.'</label>';
        $_SESSION["rep_per"][3][2]=$TA;
    }
    if($_POST["genero"]=='M' OR $_POST["genero"]=='%')
    {
        $cadena.='  <label class="l_form"><i class="fas fa-male i_estadistico"></i> Hombres: '.($TPH+$TAH).'</label>';
        $_SESSION["rep_per"][3][3]=$TPH+$TAH;
    }
    if($_POST["genero"]=='F' OR $_POST["genero"]=='%')
    {
        $cadena.='  <label class="l_form"><i class="fas fa-female i_estadistico"></i> Mujeres: '.($TPM+$TAM).'</label>';
        $_SESSION["rep_per"][3][4]=$TPM+$TAM;
    }
    $cadena.='  </div>
                <div class="c45">
                    <canvas id="myChart_personas_atendidas"></canvas>
                </div>
                <script>
                    var canvas = document.getElementById("myChart_personas_atendidas");
                    var pat = canvas.getContext(\'2d\');
                    
                    var data = {
                        labels: ["Total de albergados"],
                        datasets: [';
            if($_POST["personas"]=='P' OR $_POST["personas"]=='%')
            {
                $cadena.='  {        
                                label: \'Pacientes\',
                                data:['.$TP.'],
                                backgroundColor: [\'rgba(250, 143, 146, 0.2)\'],
                                borderColor: [\'rgba(250, 143, 146, 1)\'],
                                borderWidth: [2]
                            },';
            }
            if($_POST["personas"]=='A' OR $_POST["personas"]=='%')
            {
                $cadena.='  {
                                label: \'Acompañantes\',
                                data:['.$TA.'],
                                backgroundColor: [\'rgba(162,155,238, 0.2)\'],
                                borderColor: [\'rgba(162,155,238, 1)\'],
                                borderWidth: [2]
                            },';
            }
            if($_POST["genero"]=='M' OR $_POST["genero"]=='%')
            {
                $cadena.='  {
                                label: \'Hombres\',
                                data:['.($TPH+$TAH).'],
                                backgroundColor: [\'rgba(8,100,191, 0.2)\'],
                                borderColor: [\'rgba(8,100,191, 1)\'],
                                borderWidth: [2]
                            },';
            }
            if($_POST["genero"]=='F' OR $_POST["genero"]=='%')
            {
                $cadena.='  {
                                label: \'Mujeres\',
                                data:['.($TPM+$TAM).'],
                                backgroundColor: [\'rgba(252,0,103, 0.2)\'],
                                borderColor: [\'rgba(252,0,103, 1)\'],
                                borderWidth: [2]
                            }';
            }
            $cadena.='       ]
                    };

                    var options = {
                            rotation: -0.7 * Math.PI
                    };

                    var myPieChartPat = new Chart(pat, {
                        type: \'bar\',
                        data: data,
                        options: options
                    });

                </script>
            </div>';
    //pacientes
    if($_POST["personas"]=='P' OR $_POST["personas"]=='%')
    {
        $cadena.='<div class="c100 card">
                <div class="c45">
                    <label class="l_form">Pacientes: '.$TP.'</label>';
        if($_POST["genero"]=='M' OR $_POST["genero"]=='%')
        {
            $cadena.='<label class="l_form"><i class="fas fa-male i_estadistico"></i> Hombres: '.$TPH.'</label>
                    <label class="l_form">- Niños: '.$TPHN.'</label>
                    <label class="l_form">- Adultos: '.$TPHA.'</label>';
            $_SESSION["rep_per"][4][1]=$TPH;
            $_SESSION["rep_per"][4][2]=$TPHN;
            $_SESSION["rep_per"][4][3]=$TPHA;
        }
        if($_POST["genero"]=='F' OR $_POST["genero"]=='%')
        {
            $cadena.='<label class="l_form"><i class="fas fa-female i_estadistico"></i> Mujeres: '.$TPM.'</label>
                    <label class="l_form">- Niñas: '.$TPMN.'</label>
                    <label class="l_form">- Adultas: '.$TPMA.'</label>';
            $_SESSION["rep_per"][4][4]=$TPM;
            $_SESSION["rep_per"][4][5]=$TPMN;
            $_SESSION["rep_per"][4][6]=$TPMA;
        }
        $cadena.='</div>
                <div class="c45">
                    <canvas id="myChart_pacientes"></canvas>
                </div>
                <script>
                    var canvas = document.getElementById("myChart_pacientes");
                    var pac = canvas.getContext(\'2d\');

                    var data = {
                        labels: ["Pacientes Hombres", "Pacientes Mujeres"],
                          datasets: [{
                                label: \'Pacientes\',
                                data:['.$TPH.','.$TPM.'],
                                backgroundColor: [\'rgba(0,0,252, 0.2)\', \'rgba(252,0,103, 0.2)\'],
                                borderColor: [\'rgba(0,0,252, 1)\', \'rgba(252,0,103, 1)\'],
                                borderWidth: [2,2]

                            },
                            {
                                label: \'Adultos\',
                                data:['.$TPHA.', '.$TPMA.'],
                                backgroundColor: [\'rgba(61,142,218, 0.2)\',\'rgba(169,27,125, 0.2)\'],
                                borderColor: [\'rgba(61,142,218, 1)\', \'rgba(169,27,125, 1)\'],
                                borderWidth: [2,2]
                            },
                            {
                                label: [\'Niños\', \'Niñas\'],
                                data:['.$TPHN.', '.$TPMN.'],
                                backgroundColor: [\'rgba(39,216,231, 0.2)\',\'rgba(236,195,196, 0.2)\'],
                                borderColor: [\'rgba(39,216,231, 1)\',\'rgba(236,195,196, 1)\'],
                                borderWidth: [2,2]
                            }]
                    };

                    var options = {
                            rotation: -0.7 * Math.PI
                    };

                    var myPieChartPat = new Chart(pac, {
                        type: \'bar\',
                        data: data,
                        options: options
                    });

                </script>
            </div>';
    }
    //acompañantes
    if($_POST["personas"]=='A' OR $_POST["personas"]=='%')
    {
        $cadena.='<div class="c100 card">
                <div class="c45">
                    <label class="l_form">Acompañantes: '.$TA.'</label>';
        $_SESSION["rep_per"][6][0]=$TA;
        if($_POST["genero"]=='M' OR $_POST["genero"]=='%')
        {
            $cadena.='<label class="l_form"><i class="fas fa-male i_estadistico"></i> Hombres: '.$TAH.'</label>
                    <label class="l_form">- Niños: '.$TAHN.'</label>
                    <label class="l_form">- Adultos: '.$TAHA.'</label>';
            $_SESSION["rep_per"][6][1]=$TAH;
            $_SESSION["rep_per"][6][2]=$TAHN;
            $_SESSION["rep_per"][6][3]=$TAHA;
        }
        if($_POST["genero"]=='F' OR $_POST["genero"]=='%')
        {
            $cadena.='<label class="l_form"><i class="fas fa-female i_estadistico"></i> Mujeres: '.$TAM.'</label>
                    <label class="l_form">- Niñas: '.$TAMN.'</label>
                    <label class="l_form">- Adultas: '.$TAMA.'</label>';
            $_SESSION["rep_per"][6][4]=$TAM;
            $_SESSION["rep_per"][6][5]=$TAMN;
            $_SESSION["rep_per"][6][6]=$TAMA;
        }
        $cadena.='</div>
                <div class="c45">
                    <canvas id="myChart_acompanantes"></canvas>
                </div>
                <script>
                    var canvas = document.getElementById("myChart_acompanantes");
                    var aco = canvas.getContext(\'2d\');

                    var data = {
                        labels: ["Acompañantes Hombres", "Acompañantes Mujeres"],
                          datasets: [{
                                label: \'Acompañantes\',
                                data:['.$TAH.','.$TAM.'],
                                backgroundColor: [\'rgba(0,0,252, 0.2)\', \'rgba(252,0,103, 0.2)\'],
                                borderColor: [\'rgba(0,0,252, 1)\', \'rgba(252,0,103, 1)\'],
                                borderWidth: [2,2]

                            },
                            {
                                label: \'Adultos\',
                                data:['.$TAHA.', '.$TAMA.'],
                                backgroundColor: [\'rgba(61,142,218, 0.2)\',\'rgba(169,27,125, 0.2)\'],
                                borderColor: [\'rgba(61,142,218, 1)\', \'rgba(169,27,125, 1)\'],
                                borderWidth: [2,2]
                            },
                            {
                                label: [\'Niños\', \'Niñas\'],
                                data:['.$TAHN.', '.$TAMN.'],
                                backgroundColor: [\'rgba(39,216,231, 0.2)\',\'rgba(236,195,196, 0.2)\'],
                                borderColor: [\'rgba(39,216,231, 1)\',\'rgba(236,195,196, 1)\'],
                                borderWidth: [2,2]
                            }]
                    };

                    var options = {
                            rotation: -0.7 * Math.PI
                    };

                    var myPieChartPat = new Chart(aco, {
                        type: \'bar\',
                        data: data,
                        options: options
                    });
                
                </script>
            </div>';
    }
    if($_POST["personas"]=='P' OR $_POST["personas"]=='%')
    {
        
        if($edadi==0 AND $edadf==100)
        {
            $_SESSION["rep_per"][5][0]='0 a 100';
            $_SESSION["rep_per"][5][1]=$RResDoce["Numero"];
            $_SESSION["rep_per"][5][2]=$RResDoce_m["Numero"];
            $_SESSION["rep_per"][5][3]=$RResDoce_f["Numero"];
            $_SESSION["rep_per"][5][4]=$RResVeinte["Numero"];
            $_SESSION["rep_per"][5][5]=$RResVeinte_m["Numero"];
            $_SESSION["rep_per"][5][6]=$RResVeinte_f["Numero"];
            $_SESSION["rep_per"][5][7]=$RResSesentaycuatro["Numero"];
            $_SESSION["rep_per"][5][8]=$RResSesentaycuatro_m["Numero"];
            $_SESSION["rep_per"][5][9]=$RResSesentaycuatro_f["Numero"];
            $_SESSION["rep_per"][5][10]=$RResMas["Numero"];
            $_SESSION["rep_per"][5][11]=$RResMas_m["Numero"];
            $_SESSION["rep_per"][5][12]=$RResMas_f["Numero"];

            $cadena.='<div class="c100 card">
                    <div class="c45">
                    <label class="l_form">Edades Pacientes</label>
                        <table border="0">
                            <tr>
                                <td></td>
                                <td><i class="fas fa-mars"></i></td>
                                <td><i class="fas fa-venus"></i></td>
                            </tr>
                            <tr>
                                <td><label class="l_form">0 a 12 años: '.$RResDoce["Numero"].'</label></td>
                                <td>'.$RResDoce_m["Numero"].'</td>
                                <td>'.$RResDoce_f["Numero"].'</td>
                            </tr>
                            <tr>
                                <td><label class="l_form">13 a 20 años: '.$RResVeinte["Numero"].'</label></td>
                                <td>'.$RResVeinte_m["Numero"].'</td>
                                <td>'.$RResVeinte_f["Numero"].'</td>
                            </tr>
                            <tr>
                                <td><label class="l_form">21 a 64 años: '.$RResSesentaycuatro["Numero"].'</label></td>
                                <td>'.$RResSesentaycuatro_m["Numero"].'</td>
                                <td>'.$RResSesentaycuatro_f["Numero"].'</td>
                            </tr>
                            <tr>
                                <td><label class="l_form">65 y mas años: '.$RResMas["Numero"].'</label></td>
                                <td>'.$RResMas_m["Numero"].'</td>
                                <td>'.$RResMas_f["Numero"].'</td>
                            </tr>
                        </table>
                    </div>
                    <div class="c45">
                        <canvas id="myChart_edad_pacientes"></canvas>
                    </div>
                    <script>
                        var canvas = document.getElementById("myChart_edad_pacientes");
                        var aco = canvas.getContext(\'2d\');

                        var data = {
                            labels: ["Pacientes por edades"],
                            datasets: [{
                                label: "0 a 12 años", 
                                data: ['.$RResDoce["Numero"].'],
                                backgroundColor: [\'rgba(38,183,25, 0.2)\'],
                                borderColor: [\'rgba(38,183,25, 1)\'],
                                borderWidth: [2]
                            },
                            {
                                label: "13 a 20 años", 
                                data: ['.$RResVeinte["Numero"].'],
                                backgroundColor: [\'rgba(243,97,21, 0.2)\'],
                                borderColor: [\'rgba(243,97,21, 1)\'],
                                borderWidth: [2]
                            },
                            {
                                label: "21 a 64 años", 
                                data: ['.$RResSesentaycuatro["Numero"].'],
                                backgroundColor: [\'rgba(8,100,191, 0.2)\'],
                                borderColor: [\'rgba(8,100,191, 1)\'],
                                borderWidth: [2]
                            },
                            {
                                label: "65 y más años", 
                                data: ['.$RResMas["Numero"].'],
                                backgroundColor: [\'rgba(237,30,121, 0.2)\'],
                                borderColor: [\'rgba(237,30,121, 1)\'],
                                borderWidth: [2]
                            }]
                        };

                        var options = {
                                rotation: -0.7 * Math.PI
                        };

                        var myPieChartPat = new Chart(aco, {
                            type: \'bar\',
                            data: data,
                            options: options
                        });
                    
                    </script>
                </div>';
        }
        else
        {
            $_SESSION["rep_per"][5][0]=$edadi.' a '.$edadf.' años';
            $_SESSION["rep_per"][5][1]=$RResEdad["Numero"];
            $_SESSION["rep_per"][5][2]=$RResEdad_m["Numero"];
            $_SESSION["rep_per"][5][3]=$RResEdad_f["Numero"];

            $cadena.='<div class="c100 card">
                    <div class="c45">
                    <label class="l_form">Edades Pacientes</label>
                        <table border="0">
                            <tr>
                                <td></td>
                                <td><i class="fas fa-mars"></i></td>
                                <td><i class="fas fa-venus"></i></td>
                            </tr>
                            <tr>
                                <td><label class="l_form">'.$edadi.' a '.$edadf.' años: '.$RResEdad["Numero"].'</label></td>
                                <td>'.$RResEdad_m["Numero"].'</td>
                                <td>'.$RResEdad_f["Numero"].'</td>
                            </td>
                        </table>
                    </div>
                    <div class="c45">
                        <canvas id="myChart_edad_pacientes"></canvas>
                    </div>
                    <script>
                        var canvas = document.getElementById("myChart_edad_pacientes");
                        var aco = canvas.getContext(\'2d\');

                        var data = {
                            labels: ["Pacientes por edades"],
                            datasets: [{
                                label: "'.$edadi.' a '.$edadf.' años", 
                                data: ['.$RResEdad["Numero"].'],
                                backgroundColor: [\'rgba(38,183,25, 0.2)\'],
                                borderColor: [\'rgba(38,183,25, 1)\'],
                                borderWidth: [2]
                            }]
                        };

                        var options = {
                                rotation: -0.7 * Math.PI
                        };

                        var myPieChartPat = new Chart(aco, {
                            type: \'bar\',
                            data: data,
                            options: options
                        });
                    
                    </script>
                </div>';
        }
                
    }
    if($_POST["personas"]=='A' OR $_POST["personas"]=='%')
    {
        if($edadi==0 AND $edadf==100)
        {
            $_SESSION["rep_per"][7][0]='0 a 100';
            $_SESSION["rep_per"][7][1]=$RResDoceA["Numero"];
            $_SESSION["rep_per"][7][2]=$RReaDoceA_m["Numero"];
            $_SESSION["rep_per"][7][3]=$RResDoceA_f["Numero"];
            $_SESSION["rep_per"][7][4]=$RResVeinteA["Numero"];
            $_SESSION["rep_per"][7][5]=$RResVeinteA_m["Numero"];
            $_SESSION["rep_per"][7][6]=$RResVeinteA_f["Numero"];
            $_SESSION["rep_per"][7][7]=$RResSesentaycuatroA["Numero"];
            $_SESSION["rep_per"][7][8]=$RResSesentaycuatroA_m["Numero"];
            $_SESSION["rep_per"][7][9]=$RResSesentaycuatroA_f["Numero"];
            $_SESSION["rep_per"][7][10]=$RResMasA["Numero"];
            $_SESSION["rep_per"][7][11]=$RResMasA_m["Numero"];
            $_SESSION["rep_per"][7][12]=$RResMasA_f["Numero"];

            $cadena.='<div class="c100 card">
                    <div class="c45">
                    <label class="l_form">Edades Acompañantes</label>
                    <table border="0">
                        <tr>
                            <td></td>
                            <td><i class="fas fa-mars"></i></td>
                            <td><i class="fas fa-venus"></i></td>
                        </tr>
                        <tr>
                            <td>
                                <label class="l_form">0 a 12 años: '.$RResDoceA["Numero"].'</label>
                            </td>
                            <td>
                                <label class="l_form">'.$RReaDoceA_m["Numero"].'</label>
                            </td>
                            <td>
                                <label class="l_form">'.$RResDoceA_f["Numero"].'</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="l_form">13 a 20 años: '.$RResVeinteA["Numero"].'</label>
                            </td>
                            <td>
                                <label class="l_form">'.$RResVeinteA_m["Numero"].'</label>
                            </td>
                            <td>
                                <label class="l_form">'.$RResVeinteA_f["Numero"].'</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="l_form">21 a 64 años: '.$RResSesentaycuatroA["Numero"].'</label>
                            </td>
                            <td>
                                <label class="l_form">'.$RResSesentaycuatroA_m["Numero"].'</label>
                            </td>
                            <td>
                                <label class="l_form">'.$RResSesentaycuatroA_f["Numero"].'</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="l_form">65 y mas años: '.$RResMasA["Numero"].'</label>
                            </td>
                            <td>
                                <label class="l_form">'.$RResMasA_m["Numero"].'</label>
                            </td>
                            <td>
                                <label class="l_form">'.$RResMasA_f["Numero"].'</label>
                            </td>
                        </tr>
                    </table> 
                    </div>
                    <div class="c45">
                        <canvas id="myChart_edad_acompannantes"></canvas>
                    </div>
                    <script>
                        var canvas = document.getElementById("myChart_edad_acompannantes");
                        var aco = canvas.getContext(\'2d\');
            
                        var data = {
                            labels: ["Acompañantes por edades"],
                            datasets: [{
                                label: "0 a 12 años", 
                                data: ['.$RResDoceA["Numero"].'],
                                backgroundColor: [\'rgba(38,183,25, 0.2)\'],
                                borderColor: [\'rgba(38,183,25, 1)\'],
                                borderWidth: [2]
                            },
                            {
                                label: "13 a 20 años", 
                                data: ['.$RResVeinteA["Numero"].'],
                                backgroundColor: [\'rgba(243,97,21, 0.2)\'],
                                borderColor: [\'rgba(243,97,21, 1)\'],
                                borderWidth: [2]
                            },
                            {
                                label: "21 a 64 años", 
                                data: ['.$RResSesentaycuatroA["Numero"].'],
                                backgroundColor: [\'rgba(8,100,191, 0.2)\'],
                                borderColor: [\'rgba(8,100,191, 1)\'],
                                borderWidth: [2]
                            },
                            {
                                label: "65 y más años", 
                                data: ['.$RResMasA["Numero"].'],
                                backgroundColor: [\'rgba(237,30,121, 0.2)\'],
                                borderColor: [\'rgba(237,30,121, 1)\'],
                                borderWidth: [2]
                            }]
                        };
                    
                        var options = {
                                rotation: -0.7 * Math.PI
                        };
                    
                        var myPieChartPat = new Chart(aco, {
                            type: \'bar\',
                            data: data,
                            options: options
                        });
                    
                    </script>
                </div>';
        }
        else
        {
            $_SESSION["rep_per"][7][0]=$edadi.' a '.$edadf.' años';
            $_SESSION["rep_per"][7][1]=$RResEdadA["Numero"];
            $cadena.='<div class="c100 card">
                    <div class="c45">
                    <label class="l_form">Edades Acompañantes</label>
                    <label class="l_form">'.$edadi.' a '.$edadf.' años: '.$RResEdadA["Numero"].'</label>
                    </div>
                    <div class="c45">
                        <canvas id="myChart_edad_acompannantes"></canvas>
                    </div>
                    <script>
                        var canvas = document.getElementById("myChart_edad_acompannantes");
                        var aco = canvas.getContext(\'2d\');

                        var data = {
                            labels: ["Acompañantes por edades"],
                            datasets: [{
                                label: "'.$edadi.' a '.$edadf.' años", 
                                data: ['.$RResEdadA["Numero"].'],
                                backgroundColor: [\'rgba(38,183,25, 0.2)\'],
                                borderColor: [\'rgba(38,183,25, 1)\'],
                                borderWidth: [2]
                            }]
                        };

                        var options = {
                                rotation: -0.7 * Math.PI
                        };

                        var myPieChartPat = new Chart(aco, {
                            type: \'bar\',
                            data: data,
                            options: options
                        });
                    
                    </script>
                </div>';
        }
        
    }

    $cadena.='<div class="c100 card">
                <div class="c45">
                    <label class="l_form">Enfermedades: '.$Enfermedades.'</label>';
                    $_SESSION["rep_per"][8][0][0]=$Enfermedades;
                    $e=1; $a=1; $denf='{
                        labels: ["Enfermedades"], datasets:[';
                    while($RResEnf=mysqli_fetch_array($ResEnfermedades))
                    {
                        $ResEnfermedad=mysqli_fetch_array(mysqli_query($conn, "SELECT Diagnostico FROM diagnosticos WHERE Id='".$RResEnf["Diagnostico"]."' LIMIT 1"));

                        $cadena.='<label class="l_form"><i class="fas fa-head-side-cough i_estadistico"></i> '.$ResEnfermedad["Diagnostico"].' - '.$RResEnf["Numero"].'</label>';

                        $_SESSION["rep_per"][8][$e][$a]=$ResEnfermedad["Diagnostico"]; $a++;
                        $_SESSION["rep_per"][8][$e][$a]=$RResEnf["Numero"]; $a=1;

                        $r=rand(0,255); $g=rand(0,255); $b=rand(0,255);

                        $denf.='{
                            label: \''.$ResEnfermedad["Diagnostico"].'\',
                            data:['.$RResEnf["Numero"].'],
                            backgroundColor: [\'rgba('.$r.','.$g.','.$b.', 0.2)\'],
                            borderColor: [\'rgba('.$r.','.$g.','.$b.', 1)\'],
                            borderWidth: [2]

                        }';

                        if($e<$Enfermedades)
                        {
                            $denf.=',';
                        }

                        $e++;
                    }
                    $_SESSION["rep_per"][8][0][1]=$e;
                    $denf.=']};';
    $cadena.='  </div>
                <div class="c45">
                    <canvas id="myChart_enfermedades"></canvas>
                </div>
                <script>
                    var canvas = document.getElementById("myChart_enfermedades");
                    var hos = canvas.getContext(\'2d\');
                    
                    var data = '.$denf.'
                    
                    var options = {
                            rotation: -0.7 * Math.PI
                    };

                    var myPieChartRes = new Chart(hos, {
                        type: \'bar\',
                        data: data,
                        options: {
                            legend: {
                                display: false
                            }
                        },
                        responsive: true
                    });

                </script>
            </div>';

    $cadena.='<div class="c100 card">
                <div class="c45">
                    <label class="l_form">Hospitales: '.$NumHosp.'</label>';
                    $_SESSION["rep_per"][9][0][0]=$NumHosp;
                    $h=1; $a=1; $dhosp='{
                            labels: ["Hospitales"], datasets:[';
                    while($RResHosp=mysqli_fetch_array($ResHospitales))
                    {
                        $ResInstituto=mysqli_fetch_array(mysqli_query($conn, "SELECT Instituto FROM institutos WHERE Id='".$RResHosp["Instituto"]."' LIMIT 1"));

                        $cadena.='<label class="l_form"><i class="fas fa-hospital i_estadistico"></i> - '.$ResInstituto["Instituto"].': '.$RResHosp["Numero"].'</label>';

                        $_SESSION["rep_per"][9][$h][$a]=$ResInstituto["Instituto"]; $a++;
                        $_SESSION["rep_per"][9][$h][$a]=$RResHosp["Numero"]; $a=1;

                        $r=rand(0,255); $g=rand(0,255); $b=rand(0,255);

                        $dhosp.='{
                            label: \''.$ResInstituto["Instituto"].'\',
                            data:['.$RResHosp["Numero"].'],
                            backgroundColor: [\'rgba('.$r.','.$g.','.$b.', 0.2)\'],
                            borderColor: [\'rgba('.$r.','.$g.','.$b.', 1)\'],
                            borderWidth: [2]

                        }';

                        if($h<$NumHosp)
                        {
                            $dhosp.=',';
                        }
                    
                        $h++;
                    }
                    $_SESSION["rep_per"][9][0][1]=$h;
                    $dhosp.=']};';
    $cadena.='  </div>
                <div class="c45">
                    <canvas id="myChart_hospitales"></canvas>
                </div>
                <script>
                    var canvas = document.getElementById("myChart_hospitales");
                    var hos = canvas.getContext(\'2d\');

                    var data = '.$dhosp.'

                    var options = {
                            rotation: -0.7 * Math.PI
                    };

                    var myPieChartRes = new Chart(hos, {
                        type: \'bar\',
                        data: data,
                        options: options
                    });

                </script>
            </div>';

    $cadena.='<div class="c100 card">
                <div class="c45">
                    <label class="l_form">Procedencia: '.$NumEstados.' estados</label>';
                    $ResEstados=mysqli_query($conn, "SELECT * FROM Estados ORDER BY Estado");
                    $_SESSION["rep_per"][10][0][0]=$NumEstados;
                    $e=1; $a=1; $destados='{
                        labels: ["Estados"], datasets:[';
                    while($RResEstados=mysqli_fetch_array($ResEstados))
                    {

                    }
                    while($RResEst=mysqli_fetch_array($ResProcedencia))
                    {
                        $cadena.='<label class="l_form"><i class="fas fa-map-signs  i_estadistico"></i> '.utf8_encode($RResEst["Estado"]).': '.$RResEst["Numero"].'</label>';
                    
                        $_SESSION["rep_per"][10][$e][$a]=$RResEst["Estado"]; $a++;
                        $_SESSION["rep_per"][10][$e][$a]=$RResEst["Numero"]; $a=1;

                        $r=rand(0,255); $g=rand(0,255); $b=rand(0,255);
                    
                        $destados.='{
                            label: \''.utf8_encode($RResEst["Estado"]).'\',
                            data:['.$RResEst["Numero"].'],
                            backgroundColor: [\'rgba('.$r.','.$g.','.$b.', 0.2)\'],
                            borderColor: [\'rgba('.$r.','.$g.','.$b.', 1)\'],
                            borderWidth: [2]

                        }';
                    
                        if($e<$NumEstados)
                        {
                            $destados.=',';
                        }
                    
                    
                        $labels2.='\''.utf8_encode($RResEst["Estado"]).'\',';
                        $data2.=$RResEst["Numero"].',';
                        $backgroundcolor2.='\'rgba('.$r.','.$g.','.$b.', 0.2)\',';
                        $bordercolor2.='\'rgba('.$r.','.$g.','.$b.', 1)\',';
                        $borderwidth2.='2,';
                    
                        //$ResMunicipio=mysqli_query($conn, "SELECT COUNT(Mu.Municipio) AS Numero, Mu.Municipio AS Municipio
                        //                                    FROM (SELECT COUNT(m.Municipio) AS Pacientes, m.Municipio AS Municipio
                        //                                            FROM pacientes AS p 
                        //                                            INNER JOIN municipios AS m ON p.Municipio=m.Id 
                        //                                            INNER JOIN reservacion AS r ON r.IdPaciente=p.Id 
                        //                                            WHERE r.Fecha >= '".$_POST["periodode"]."' AND r.Fecha <= '".$_POST["periodohasta"]."'  AND m.Estado='".$RResEst["EId"]."'
                        //                                            GROUP BY r.IdPaciente, m.Municipio) AS Mu
                        //                                    GROUP BY Mu.Municipio ORDER BY Mu.Municipio ASC");
                        //while($RResMun=mysqli_fetch_array($ResMunicipio))
                        //{
                        //    $cadena.='<label class="l_form"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;* '.utf8_encode($RResMun["Municipio"]).': '.$RResMun["Numero"].'</label>';
//
                        //}
                        $e++;
                    }
                    $_SESSION["rep_per"][10][0][1]=$e;
                    $destados.=']};';
    $cadena.='  </div>
                <div class="c45">
                    <canvas id="myChart_procedencia"></canvas>
                </div>
                <script>
                    var canvas = document.getElementById("myChart_procedencia");
                    var pro = canvas.getContext(\'2d\');

                    var data = '.$destados.'

                    var options = {
                            rotation: -0.7 * Math.PI
                    };

                    var myPieChartRes = new Chart(pro, {
                        type: \'bar\',
                        data: data,
                        options: options
                    });
                
                </script>
            </div>';
    
    
    $cadena.='</div>';

    //bitacora
    mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '105', '".json_encode($_POST)."')");
}
                

echo $cadena;

?>
<script>
    $("#fpers").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fpers"));

	$.ajax({
		url: "estadisticos/reporte_personalizado.php",
		type: "POST",
		dataType: "HTML",
		data: formData,
		cache: false,
		contentType: false,
		processData: false
	}).done(function(echo){
		$("#contenido").html(echo);
	});
});
</script>