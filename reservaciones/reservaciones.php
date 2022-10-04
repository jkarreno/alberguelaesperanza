<?php
date_default_timezone_set('America/Mexico_City');
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

//Estatus 1-confirmado || 2-cancelado 
//pagada 0-no pagada || 1- parcialmente pagada ||2- totalmente pagada

$mensaje='';

//acciones
if(isset($_POST["hacer"]))
{
    $_POST["fecha_reservacion"]=$_POST["fechares"];
     //agregar reservacion
	if($_POST["hacer"]=='adreservacion')
	{
        $fechares = $_POST["fechares"];
        $ResPac=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, Nombre, Apellidos, Apellidos2 FROM pacientes WHERE Id='".$_POST["num_paciente"]."' LIMIT 1"));
        //reservacion
        mysqli_query($conn, "INSERT INTO reservacion (IdPaciente, Fecha, FechaReservacion, Dias, Estatus, Instituto, Diagnostico)
                                        VALUES ('".$_POST["num_paciente"]."', '".$_POST["fechares"]."', '".date("Y-m-d")."', '".$_POST["diasres"]."', '0', 
                                                '".$_POST["instituto"]."', '".$_POST["diagnostico"]."')");

        $ResId=mysqli_fetch_array(mysqli_query($conn, "SELECT Id FROM reservacion WHERE IdPaciente='".$_POST["num_paciente"]."' AND Fecha='".$_POST["fechares"]."' LIMIT 1"));
        //reservacion del paciente
        for($j=1; $j<=$_POST["diasres"]; $j++)
        {
            if($_POST["hospitalizado"]==1){$cama=-1;}
            else{$cama=$_POST["cama"];}
            mysqli_query($conn, "INSERT INTO reservaciones (IdReservacion, Tipo, IdPA, Fecha, Cama) VALUES ('".$ResId["Id"]."', 'P', '".$_POST["num_paciente"]."', '".$fechares."', '".$cama."')");
            $fechares=date("Y-m-d",strtotime($fechares."+ 1 days"));
        }

        //reservcion de los acompañantes
        $fechares = $_POST["fechares"];
        $ResAco=mysqli_query($conn, "SELECT Id FROM acompannantes WHERE IdPaciente='".$_POST["num_paciente"]."' ORDER BY Id ASC");
        while($RResAco=mysqli_fetch_array($ResAco))
        {
            if($_POST["aco_".$RResAco["Id"]]==1)
            {
                for($j=1; $j<=$_POST["diasres"]; $j++)
                {
                    mysqli_query($conn, "INSERT INTO reservaciones (IdReservacion, Tipo, IdPA, Fecha, Cama) VALUES ('".$ResId["Id"]."', 'A', '".$RResAco["Id"]."', '".$fechares."', '".$_POST["cama_aco_".$RResAco["Id"]]."')") or die(mysqli_error($conn));
                    $fechares=date("Y-m-d",strtotime($fechares."+ 1 days"));
                }
            }
            $fechares = $_POST["fechares"];
        }

        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se reservo para el paciente '.$ResPac["Nombre"].' '.$ResPac["Apellidos"].' '.$ResPac["Apellidos2"].'"</div>';

        //bitacora
        mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '3', '".json_encode($_POST)."')");

    }
    elseif($_POST["hacer"]=='edreservacion')
    {
        $fechares = $_POST["fechares"];
        $ResDiasRes=mysqli_fetch_array(mysqli_query($conn, "SELECT Dias, Estatus FROM reservacion WHERE Id='".$_POST["idreservacion"]."' LIMIT 1"));

        $ResPac=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, Nombre, Apellidos, Apellidos2 FROM pacientes WHERE Id='".$_POST["num_paciente"]."' LIMIT 1"));
        //editar reservacion
        if($_POST["diasres"]<=$ResDiasRes["Dias"]) //si son los mismos dias o menos, ya esta pagada
        {
            mysqli_query($conn, "UPDATE reservacion SET Fecha='".$_POST["fechares"]."', 
                                                        Dias='".$_POST["diasres"]."',
                                                        Estatus='".$ResDiasRes["Estatus"]."',
                                                        Instituto='".$_POST["instituto"]."',
                                                        Diagnostico='".$_POST["diagnostico"]."'
                                                    WHERE Id='".$_POST["idreservacion"]."'") or die(mysqli_error($conn));
        }
        elseif($_POST["diasres"]>$ResDiasRes["Dias"]) //si son mas dias, hay que mostrar no pagada
        {
            mysqli_query($conn, "UPDATE reservacion SET Fecha='".$_POST["fechares"]."', 
                                                        Dias='".$_POST["diasres"]."',
                                                        Estatus='".$ResDiasRes["Estatus"]."',
                                                        Instituto='".$_POST["instituto"]."',
                                                        Diagnostico='".$_POST["diagnostico"]."',
                                                        Pagada='1'
                                                    WHERE Id='".$_POST["idreservacion"]."'") or die(mysqli_error($conn));
        }
        

        //borra las reservaciones de cama
        mysqli_query($conn, "DELETE FROM reservaciones WHERE IdReservacion='".$_POST["idreservacion"]."'");

        //reservacion del paciente
        for($j=1; $j<=$_POST["diasres"]; $j++)
        {
            if($_POST["hospitalizado"]==1){$cama=-1;}
            else{$cama=$_POST["cama"];}
            mysqli_query($conn, "INSERT INTO reservaciones (IdReservacion, Tipo, IdPA, Fecha, Cama) VALUES ('".$_POST["idreservacion"]."', 'P', '".$ResPac["Id"]."', '".$fechares."', '".$cama."')") or die(mysqli_error($conn));
            $fechares=date("Y-m-d",strtotime($fechares."+ 1 days"));
        }

        //reservcion de los acompañantes
        $fechares = $_POST["fechares"];
        $ResAco=mysqli_query($conn, "SELECT Id FROM acompannantes WHERE IdPaciente='".$_POST["num_paciente"]."' ORDER BY Id ASC");
        while($RResAco=mysqli_fetch_array($ResAco))
        {
            if($_POST["aco_".$RResAco["Id"]]==1)
            {
                for($j=1; $j<=$_POST["diasres"]; $j++)
                {
                    mysqli_query($conn, "INSERT INTO reservaciones (IdReservacion, Tipo, IdPA, Fecha, Cama) VALUES ('".$_POST["idreservacion"]."', 'A', '".$RResAco["Id"]."', '".$fechares."', '".$_POST["cama_aco_".$RResAco["Id"]]."')") or die(mysqli_error($conn));
                    $fechares=date("Y-m-d",strtotime($fechares."+ 1 days"));
                }
            }
            $fechares = $_POST["fechares"];
        } 

        //reservaciones en blanco
        $fechares = $_POST["fechares"];
        for($J=1; $J<=$_POST["r_blanco"]; $J++)
        {
            if($_POST["acom_".$J]!=0)
            {
                for($j=1; $j<=$_POST["diasres"]; $j++)
                {
                    mysqli_query($conn, "INSERT INTO reservaciones (IdReservacion, Tipo, IdPA, Fecha, Cama) VALUES ('".$_POST["idreservacion"]."', 'A', '".$_POST["acom_".$J]."', '".$fechares."', '".$_POST["cama_aco_".$J]."')") or die(mysqli_error($conn));
                    $fechares=date("Y-m-d",strtotime($fechares."+ 1 days"));
                }
            }
            
        }

        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se actualizo la reservación para el paciente '.$ResPac["Nombre"].' '.$ResPac["Apellidos"].' '.$ResPac["Apellidos2"].'"</div>';

        //bitacora
        mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '4', '".json_encode($_POST)."')");
    }
    //cancelar reservacion
    elseif($_POST["hacer"]=='canreservacion')
    {
        //cancela reservacion
        mysqli_query($conn, "UPDATE reservacion SET Estatus='2' WHERE Id='".$_POST["idres"]."'");
        mysqli_query($conn, "UPDATE reservaciones SET Estatus='2' WHERE IdReservacion='".$_POST["idres"]."'");

        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se Cancelo la reservación"</div>';

        //bitacora
        mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '5', '".json_encode($_POST)."')");
    }
    //confirmar reserevacion
    elseif($_POST["hacer"]=='confirmar')
    {
        mysqli_query($conn, "UPDATE reservacion SET Estatus='1' WHERE Id='".$_POST["idres"]."'");
        mysqli_query($conn, "UPDATE reservaciones SET Estatus='1' WHERE IdReservacion='".$_POST["idres"]."'");

        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Reservación confirmada"</div>';

        //bitacora
        mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '6', '".json_encode($_POST)."')");
    }
    //extender reservación
    elseif($_POST["hacer"]=='extender')
    {
        //selecciona la reservación
        $ResRes=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM reservacion WHERE Id='".$_POST["idreservacion"]."' LIMIT 1"));
        //aumenta 1 dia
        $dias=$ResRes["Dias"]+1;
        //selecciona las camas
        $ResRC=mysqli_query($conn, "SELECT * FROM reservaciones WHERE IdReservacion='".$_POST["idreservacion"]."' AND Fecha='".$_POST["fecha"]."'");
        //aumenta un dia a la fecha
        $fechares=date("Y-m-d",strtotime($_POST["fechares"]."+ 1 days"));
        //verifica disponibilidad de camas
        $cd=0;
        while($RResRC=mysqli_fetch_array($ResRC))
        {
            $ResCD=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservaciones WHERE Fecha='".$fechares."' AND Cama='".$RResRC["Cama"]."'")); //CD - Cama Disponible
            if($ResCD>0){$cd++;}
        }

        if($cd>0)
        {
            $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-exclamation-triangle"></i> No se puede exteder la reservación, cree una nueva (cama no disponible)"</div>';
        }

        else
        {
            //incrementa un dia a la reservacion
            mysqli_query($conn, "UPDATE reservacion SET Dias='".$dias."', Pagada='0' WHERE Id='".$ResRes["Id"]."'");
            //incrementa el dia a las camas
            $ResRC2=mysqli_query($conn, "SELECT * FROM reservaciones WHERE IdReservacion='".$ResRes["Id"]."' AND Fecha='".$_POST["fechares"]."'") or die(mysqli_error($conn));
            $a='';
            while($RResRC2=mysqli_fetch_array($ResRC2))
            {
                mysqli_query($conn, "INSERT INTO reservaciones (IdReservacion, Tipo, IdPA, Fecha, Cama, Estatus)
                                                        VALUES ('".$RResRC2["IdReservacion"]."', '".$RResRC2["Tipo"]."', '".$RResRC2["IdPA"]."', '".$fechares."', 
                                                                '".$RResRC2["Cama"]."', '".$RResRC2["Estatus"]."')") or die(mysqli_error($conn));
            }
            
            $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se agrego un dia a la reservación</div>';
        }

        //bitacora
        mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '7', '".json_encode($_POST)."')");
    }
    elseif($_POST["hacer"]=='liberar')
    {
        mysqli_query($conn, "UPDATE reservacion SET Liberada='1' WHERE Id='".$_POST["idres"]."'");

        mysqli_query($conn, "UPDATE reservaciones SET Liberada='1' WHERE IdReservacion='".$_POST["idres"]."'") or die(mysqli_error($conn));

        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se libero la reservación '.$_POST["idres"].'</div>';

        //bitacora
        mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '8', '".json_encode($_POST)."')");
    }
    elseif($_POST["hacer"]=='cambiar_cama')//cmabio de cama
    {
        $fechares = $_POST["fechares"];
        //paciente
        $ResP=mysqli_fetch_array(mysqli_query($conn, "SELECT IdPA FROM reservaciones WHERE IdReservacion='".$_POST["idreservacion"]."' AND Tipo='P' GROUP BY IdPA"));

        mysqli_query($conn, "UPDATE reservaciones SET Cama='".$_POST["cama_p_".$ResP["IdPA"]]."'
                                                WHERE IdReservacion='".$_POST["idreservacion"]."' AND IdPA='".$ResP["IdPA"]."' AND Tipo='P' AND Fecha>='".$fechares."'");

        //acompanantes
        $ResA=mysqli_query($conn, "SELECT IdPA FROM reservaciones WHERE IdReservacion='".$_POST["idreservacion"]."' AND Tipo='A' GROUP BY IdPA");

        while($RResA=mysqli_fetch_array($ResA))
        {
            mysqli_query($conn, "UPDATE reservaciones SET Cama='".$_POST["cama_a_".$RResA["IdPA"]]."'
                                                WHERE IdReservacion='".$_POST["idreservacion"]."' AND IdPA='".$RResA["IdPA"]."' AND Tipo='A' AND Fecha>='".$fechares."'");
        }

        //bitacora
        mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '135', '".json_encode($_POST)."')");
    }
}

if($_POST["habitacion"]==0 OR !isset($_POST["habitacion"]) OR $_POST["habitacion"]==NULL){$ResHab=mysqli_query($conn, "SELECT * FROM habitaciones ORDER BY Habitacion ASC");}
else{$ResHab=mysqli_query($conn, "SELECT * FROM habitaciones WHERE Id='".$_POST["habitacion"]."'");}

if($_POST["fecha_reservacion"]==date("Y-m-d") AND date("H")<=16)
{
    $RC=mysqli_num_rows(mysqli_query($conn, "SELECT Cama FROM reservaciones WHERE Fecha<='".$_POST["fecha_reservacion"]."' AND Fecha>='".date("Y-m-d",strtotime($_POST["fecha_reservacion"]."- 1 days"))."' AND Estatus='0' GROUP BY Cama")); //camas reservadas
    $RCC=mysqli_num_rows(mysqli_query($conn, "SELECT Cama FROM reservaciones WHERE Fecha<='".$_POST["fecha_reservacion"]."' AND Fecha>='".date("Y-m-d",strtotime($_POST["fecha_reservacion"]."- 1 days"))."' AND Cama>'0' AND Estatus='1' AND Liberada='0' GROUP BY Cama")); //camas ocupadas
    $RCP=mysqli_num_rows(mysqli_query($conn, "SELECT Cama FROM reservaciones WHERE Fecha<='".$_POST["fecha_reservacion"]."' AND Fecha>='".date("Y-m-d",strtotime($_POST["fecha_reservacion"]."- 1 days"))."' AND Cama>'0' AND Tipo='P' AND Estatus='1' AND Liberada='0' GROUP BY Cama")); //pacientes
    $RCPH=mysqli_num_rows(mysqli_query($conn, "SELECT Cama FROM reservaciones WHERE Fecha<='".$_POST["fecha_reservacion"]."' AND Fecha>='".date("Y-m-d",strtotime($_POST["fecha_reservacion"]."- 1 days"))."' AND Cama='-1' AND Tipo='P' AND Estatus='1' AND Liberada='0' GROUP BY Cama")); //pacientes hospitalizados
    $RCA=mysqli_num_rows(mysqli_query($conn, "SELECT Cama FROM reservaciones WHERE Fecha<='".$_POST["fecha_reservacion"]."' AND Fecha>='".date("Y-m-d",strtotime($_POST["fecha_reservacion"]."- 1 days"))."' AND (Cama>'0' OR Cama='-2') AND Tipo='A' AND Estatus='1' AND Liberada='0' GROUP BY Cama")); //acompañantes
}
else
{
    $RC=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservaciones WHERE Fecha='".$_POST["fecha_reservacion"]."' AND Estatus='0' AND Liberada='0'")); //camas reservadas
    $RCC=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservaciones WHERE Fecha='".$_POST["fecha_reservacion"]."' AND Cama>'0' AND Estatus='1' AND Liberada='0'")); //camas ocupadas
    $RCP=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservaciones WHERE Fecha='".$_POST["fecha_reservacion"]."' AND Cama>'0' AND Tipo='P' AND Estatus='1' AND Liberada='0'")); //pacientes
    $RCPH=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservaciones WHERE Fecha='".$_POST["fecha_reservacion"]."' AND Cama='-1' AND Tipo='P' AND Estatus='1' AND Liberada='0'")); //pacientes hospitalizado
    $RCA=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservaciones WHERE Fecha='".$_POST["fecha_reservacion"]."' AND (Cama>'0' OR Cama='-2') AND Tipo='A' AND Estatus='1' AND Liberada='0'")); //acompañantes
}


$cadena=$mensaje.'<table style="width:80%">'; $J=1;
$cadena.='<thead>
            <tr>
                <td colspan="2" style="text-align: left">
                    <select name="habitacion" id="habitacion" onchange="reservaciones(document.getElementById(\'fecha_reservacion\').value, this.value)">
                        <option value="0">Todas</option>';
$ResHabS=mysqli_query($conn, "SELECT * FROM habitaciones ORDER BY Habitacion ASC");
while($RResHabS=mysqli_fetch_array($ResHabS))
{
    $cadena.='          <option value="'.$RResHabS["Id"].'"';if($_POST["habitacion"]==$RResHabS["Id"]){$cadena.=' selected';}$cadena.='>'.$RResHabS["Habitacion"].'</option>';
}
$cadena.='         </select>
                </td>
                <td colspan="2" style="text-align: left">
                    <select name="status_camas" id="status_camas">
                        <option value="0">Todas las camas</option>
                        <option value="1">Camas ocupadas</option>
                        <option value="2">Camas libres</option>
                    </select>
                </td>
                <td colspan="2" style="text-align: right"><input type="date" name="fecha_reservacion" id="fecha_reservacion" value="'.$_POST["fecha_reservacion"].'" onchange="reservaciones(this.value)"></td>
                <td colspan="6" style="text-align: right">'.permisos(20, '<a href="#" onclick="lista_reservaciones(\''.$_POST["fecha_reservacion"].'\')"><i class="fas fa-list-ul"></i></a>').' | '.permisos(21, '<a href="reservaciones/lista_pdf.php?fecha='.$_POST["fecha_reservacion"].'" target="_blank"><i class="fas fa-clipboard-list"></i></a>').'</td>
            </tr>
            <tr>
                <td colspan="11" style="text-align: left">Camas Reservadas: '.$RC.' | Camas ocupadas: '.$RCC.' | Pacientes: '.$RCP.' | Pacientes Hospitalizados: '.$RCPH.' | Acompañantes: '.$RCA.'</td>
            </tr>
        </thead>';
$P=0; $A=0;
while($RResHab=mysqli_fetch_array($ResHab))
{
    $cadena.='<tr>
                <th colspan="11" align="center" class="textotitable">'.$RResHab["Habitacion"].'</th>
            </tr>';
    $ResCam=mysqli_query($conn, "SELECT * FROM camas WHERE Habitacion='".$RResHab["Id"]."' ORDER BY Cama ASC");
    $bgcolor='#ffffff'; $print=0; 
    while($RResCam=mysqli_fetch_array($ResCam))
    {
        
        if(date("H")<=16)
        {
            if($_POST["fecha_reservacion"]==date("Y-m-d"))
            {
                $ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE (Fecha='".$_POST["fecha_reservacion"]."' OR Fecha='".date("Y-m-d",strtotime($_POST["fecha_reservacion"]."- 1 days"))."') AND Cama='".$RResCam["Id"]."' AND Estatus<2 AND Liberada='0'");
            }
            elseif($_POST["fecha_reservacion"]!=date("Y-m-d"))
            {
                $ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE (Fecha='".$_POST["fecha_reservacion"]."' OR Fecha='".$_POST["fecha_reservacion"]."') AND Cama='".$RResCam["Id"]."' AND Estatus<2");
            }
        }
        elseif(date("H")>16)
        {
            $ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE Fecha='".$_POST["fecha_reservacion"]."' AND Cama='".$RResCam["Id"]."' AND Estatus<2 AND Liberada='0'");
        } 
        
        $res=mysqli_num_rows($ResRes);

        
        if($res>0 AND $_POST["camas"]<2) //cama ocupada   
        {
            $RResRes=mysqli_fetch_array($ResRes);
            if($RResRes["Tipo"]=='P')
            {
                $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos, Apellidos2) AS Nombre, Apellidos, Apellidos2, Sexo, FechaNacimiento FROM pacientes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
                $bgcolor='#fa8f92'; $P++; 
            }
            elseif($RResRes["Tipo"]=='A')
            {
                $ResNombre=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, concat_ws(' ', Nombre, Apellidos, Apellidos2) AS Nombre, Apellidos, Apellidos2, Sexo, FechaNacimiento FROM acompannantes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
                $bgcolor='#a29bee'; $A++; 
            }

            $print=1;
        }
        elseif($res==0 AND ($_POST["camas"]==2 OR $_POST["camas"]==0)) //cama vacia 
        {
            $ResNombre["Nombre"]='';
            $RResRes["Tipo"]='';
            $ResNombre["Id"]='';

            $print=1;
        }
        
        if($RResCam["Cama"]=='extra' AND $ResNombre["Nombre"]=='')
        {
            $print=0;
        }

        if($print==1)
        {
            //if(($ResNombre["Nombre"]!='' AND $RResCam["Nombre"]=='extra') OR ($ResNombre["Nombre"]!='' AND $RResCam["Nombre"]=='extra'))
            //{
                $cadena.='<tr style="background: '.$bgcolor.'" id="row_'.$J.'"';if($res==0){$cadena.=permisos(3, ' onclick="limpiar(); abrirmodal(); ad_reservacion(\'0\', \'0\', document.getElementById(\'fecha_reservacion\').value, \'1\', \''.$RResCam["Habitacion"].'\');"');}$cadena.='>
                            <td width="50" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto">';
                            if($RResRes["Tipo"]=='P'){$cadena.=$P;}
                            elseif($RResRes["Tipo"]=='A'){$cadena.=$A;}
                $cadena.='  </td>
                            <td width="100" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto">'.$RResRes["IdReservacion"].'</td>
                            <td width="100" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto">'.$RResCam["Cama"].'</td>
                            <td width="50" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto">'.$RResRes["Tipo"].'</td>
                            <td width="100" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto">'.$ResNombre["Id"].'</td>
                            <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto"><a href="javascript:void(0)" style="color: #000;" onclick="';
                            if($RResRes["Tipo"]=='P'){$cadena.='edit_paciente';}
                            elseif($RResRes["Tipo"]=='A'){$cadena.='edit_acompannante';}
                            $cadena.='(\''.$ResNombre["Id"].'\')">'.$ResNombre["Nombre"].'</a> ';
                            
                            //alerta datos faltantes
                            if(($ResNombre["Sexo"]==NULL OR $ResNombre["FechaNacimiento"]==NULL OR $ResNombre["Apellidos2"]==NULL) AND $ResNombre["Nombre"]!='')
                            {
                                $cadena.='<i class="fas fa-exclamation-triangle" style="color: red; cursor: pointer;" onclick="';
                                if($RResRes["Tipo"]=='P'){$cadena.='edit_paciente';}elseif($RResRes["Tipo"]=='A'){$cadena.='edit_acompannante';}
                                $cadena.='(\''.$ResNombre["Id"].'\')"></i>';
                            }

                            //alerta falta estudio socioeconomico
                            if($RResRes["Tipo"]=='P')
                            {
                                $ResEsD=mysqli_query($conn, "SELECT Id FROM es_diagnosticosocial WHERE IdPaciente='".$ResNombre["Id"]."'");
                                $ResEsE=mysqli_query($conn, "SELECT Id FROM es_estructurafamiliar WHERE IdPaciente='".$ResNombre["Id"]."'");
                                $ResEsS=mysqli_query($conn, "SELECT Id FROM es_salud WHERE IdPaciente='".$ResNombre["Id"]."'");
                                $ResEsSeE=mysqli_query($conn, "SELECT Id FROM es_situacioneconomica_egreso WHERE IdPaciente='".$ResNombre["Id"]."'");
                                $ResEsSeI=mysqli_query($conn, "SELECT Id FROM es_situacioneconomica_ingreso WHERE IdPaciente='".$ResNombre["Id"]."'");
                                $ResEsV=mysqli_query($conn, "SELECT Id FROM es_vivienda WHERE IdPaciente='".$ResNombre["Id"]."'");

                                if(mysqli_num_rows($ResEsD)==0 OR mysqli_num_rows($ResEsE)==0 OR mysqli_num_rows($ResEsS)==0 OR mysqli_num_rows($ResEsSeE)==0 OR mysqli_num_rows($ResEsSeI)==0 OR mysqli_num_rows($ResEsV)==0)
                                {
                                    $cadena.=permisos(27, ' <i class="fas fa-exclamation-triangle" style="color: yellow; cursor: pointer;" onclick="estudiosocioeconomico(\''.$ResNombre["Id"].'\')"></i>');
                                }
                            }
                            $cadena.='</td>
                            <td width="50" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto">';
                            if($ResNombre["Nombre"]!='')
                            {
                                if($RResRes["Estatus"]==0){$cadena.='<i style="cursor: pointer;" class="far fa-circle"';}
                                elseif($RResRes["Estatus"]==1){$cadena.='<i class="far fa-check-circle" style="color: #37b64b; cursor: pointer;"';}
                                $cadena.=' onclick="document.getElementById(\'flotante_'.$J.'\').style.display = \'\';"></i>
                                <div id="flotante_'.$J.'" class="menu_flot" style="display:none;">
                                    <div id="close"><i class="fas fa-times" onclick="document.getElementById(\'flotante_'.$J.'\').style.display = \'none\';"></i></div>
                                    <ul>
                                        '.permisos(6, '<li><a href="#" onclick="confirmar(\''.$RResRes["IdReservacion"].'\', document.getElementById(\'fecha_reservacion\').value)"><i class="far fa-check-circle" style="color: #7ac70c"></i> Confirmar</a></li>').'
                                        '.permisos(5, '<li><a href="#" onclick="can_reservacion(\''.$RResRes["IdReservacion"].'\')"><i class="far fa-times-circle" style="color: #ff0000"></i> Cancelar</a></li>').'
                                        '.permisos(4, '<li><a href="#" onclick="limpiar(); abrirmodal(); edit_reservacion(\''.$RResRes["IdReservacion"].'\')"><i class="fas fa-edit" style="color: #a91b7d"></i> Editar</a></li>').'
                                        '.permisos(7, '<li><a href="#" onclick="ext_reservacion(\''.$RResRes["IdReservacion"].'\', document.getElementById(\'fecha_reservacion\').value)"><i class="fas fa-calendar-plus" style="color: #29abe2"></i> 1 día</a></li>').'
                                        '.permisos(8, '<li><a href="#" onclick="liberar_reservacion(\''.$RResRes["IdReservacion"].'\', document.getElementById(\'fecha_reservacion\').value)"><i class="fas fa-sign-out-alt" style="color: #fd7e14"></i> Liberar</a></li>').'
                                        '.permisos(135, '<li><a href="#" onclick="limpiar(); abrirmodal(); cambio_cama(\''.$RResRes["IdReservacion"].'\', document.getElementById(\'fecha_reservacion\').value)"><i class="fas fa-exchange-alt" style="color: #f4df15"></i> Cambio cama</a></li>').'
                                    </ul>
                                </div>';
                            }

                $cadena.='  </td>
                            <td width="50" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto">';
                            if($ResNombre["Nombre"]!='')
                            {
                                $ResReservacion=mysqli_fetch_array(mysqli_query($conn, "SELECT Fecha, Dias, Pagada FROM reservacion WHERE Id='".$RResRes["IdReservacion"]."' LIMIT 1"));

                                if($ResReservacion["Pagada"]==0){$cadena.='<i style="cursor: pointer; color: #ff0000;"';}
                                elseif($ResReservacion["Pagada"]==1){$cadena.='<i style="cursor: pointer; color: #ff8500;"';}
                                elseif($ResReservacion["Pagada"]>=2){$cadena.='<i style="cursor: pointer; color: #7ac70c;"';}
                                //$cadena.=' class="fas fa-cash-register" onclick="pago_reservacion(\''.$RResRes["IdReservacion"].'\')"></i>';
                                $cadena.=' class="fas fa-cash-register" '.permisos(9, 'onclick="limpiar; abrirmodal(); pago_reservacion(\''.$RResRes["IdReservacion"].'\')"').'></i>';
                            }
                $cadena.='  </td>
                            
                            <td width="50" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto">';
                            if($ResNombre["Nombre"]!='')
                            {
                                //ya recibieron prendas
                                //$ResPrendas1=mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) AS Num FROM  lavanderia_observaciones WHERE IdReservacion='".$RResRes["IdReservacion"]."' AND ES='S'"));
                                $ResPrendas1=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM lavanderia_observaciones WHERE IdReservacion='".$RResRes["IdReservacion"]."' AND ES='S'"));
                                $ResPrendas2=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM lavanderia_observaciones WHERE IdReservacion='".$RResRes["IdReservacion"]."' AND ES='E'"));
                                
                                //fechas
                                $FR=$ResReservacion["Fecha"]; $FC=$_POST["fecha_reservacion"];
                                //dias transcurridos
                                $DT=dias_pasados($FR,$FC);
                                //dias entre 7
                                if($DT<=$ResReservacion["Dias"])
                                {
                                    $C=intdiv($DT,7);

                                    $ResCambios=mysqli_num_rows(mysqli_query($conn, "SELECT ES FROM lavanderia_observaciones WHERE IdReservacion='".$RResRes["IdReservacion"]."' AND ES='C'"));

                                    if($C>$ResCambios AND $ResPrendas2==0){$ResPrendas1=0;}
                                }

                                //cambiamos color de lavanderia
                                if($ResPrendas1==0)
                                {
                                    $colortshirt='#ff0000'; //rojo
                                }
                                elseif($ResPrendas2==0)
                                {
                                    $colortshirt='#ff8500'; //naranja
                                }
                                elseif($ResPrendas1>0 AND $ResPrendas2>0){
                                    $colortshirt='#7ac70c'; //verde
                                }
                                $cadena.=permisos(11, '<a href="#" onclick="limpiar(); abrirmodal(); recibo_lavanderia(\''.$RResRes["IdReservacion"].'\')"><i class="fas fa-tshirt" style="color:'.$colortshirt.'"></i> </a>');
                            }
                $cadena.='  </td>
                            <td width="50" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto">';
                            //fecha menos 30 dias
                            $fecha30=date("Y-m-d",strtotime($RResRes["Fecha"]."- 30 days"));

                            $ResResP=mysqli_query($conn, "SELECT Id FROM reservacion WHERE IdPaciente='".$ResPac["Id"]."' AND Fecha >='".$fecha30."' AND Fecha<='".date("Y-m-d")."'");
                            $i=1;
                            while($RResResP=mysqli_fetch_array($ResResP))
                            {
                                $ResRespuestasA=mysqli_query($conn, "SELECT Id FROM respuestas_encuesta WHERE IdReservacion='".$RResResP["Id"]."'");
                                if(mysqli_num_rows($ResRespuestasA)>0)
                                {$i=0; break;}
                            }
                            if($ResNombre["Nombre"]!='' AND $i==1)
                            {
                                $Respuesta=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM respuestas_encuesta WHERE IdReservacion='".$RResRes["IdReservacion"]."'"));

                                if($Respuesta!=0)
                                {
                                    $colorencuesta='#7ac70c';
                                }
                                else{
                                    $colorencuesta='#ff0000';
                                }
                                $cadena.=permisos(15, '<a href="#" onclick="limpiar(); abrirmodal(); encsatisfaccionres(\''.$RResRes["IdReservacion"].'\');"><i class="fas fa-poll-h" style="color:'.$colorencuesta.'"></i></a>');
                            }
                $cadena.='  </td>
                            <td width="50" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto">';
                            if($ResNombre["Nombre"]!='')
                            {
                                $ResSMA=mysqli_num_rows(mysqli_query($conn, "SELECT * FROM material_apoyo_inventario WHERE IdReservacion='".$RResRes["IdReservacion"]."' AND ES='S' LIMIT 1"));
                                $ResEMA=mysqli_num_rows(mysqli_query($conn, "SELECT * FROM material_apoyo_inventario WHERE IdReservacion='".$RResRes["IdReservacion"]."' AND ES='E' LIMIT 1"));
                                
                                if($ResSMA==1){$colorma=' style="color: #ff0000"';}
                                if($ResEMA==1){$colorma=' style="color: #7ac70c"';}
                                $cadena.=permisos(16, '<a href="#" onclick="limpiar(); abrirmodal(); prestamomaterial(\''.$RResRes["IdReservacion"].'\');"><i class="fas fa-crutch"'.$colorma.'></i></a>');
                                $colorma='#695de9';
                            }
                $cadena.='  </td>
                        </tr>';
                $J++; $RResRes["IdReservacion"]=''; $print=0;
                if($bgcolor=="#ffffff"){$bgcolor='#cccccc';}
                else if($bgcolor=="#cccccc"){$bgcolor="#ffffff";}
                else{$bgcolor="#ffffff";}
            //}
        }
    }
}
$cadena.='</table>';

echo $cadena;

//bitacora
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '2', '".json_encode($_POST)."')");

?>
<script>
function cambio_cama(idreservacion, fecha)
{
    $.ajax({
				type: 'POST',
				url : 'reservaciones/cambio_cama.php',
                data: 'idreservacion=' + idreservacion + '&fechares=' + fecha
	}).done (function ( info ){
		$('#modal-body').html(info);
	});
}

function ad_reservacion(id, nombre, fechares, diasres, habitacion)
{
	$.ajax({
				type: 'POST',
				url : 'reservaciones/add_reservacion.php',
                data: 'idpaciente=' + id + '&nombre=' + nombre + '&fechares=' + fechares + '&diasres=' + diasres + '&habitacion=' + habitacion
	}).done (function ( info ){
		$('#modal-body').html(info);
	});
}

function confirmar(id, fechares)
{
    $.ajax({
				type: 'POST',
				url : 'reservaciones/reservaciones.php',
                data: 'idres=' + id + '&hacer=confirmar&fechares=' + fechares
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}

function edit_reservacion(id)
{
    $.ajax({
				type: 'POST',
				url : 'reservaciones/edit_reservacion.php',
                data: 'idres=' + id 
	}).done (function ( info ){
		$('#modal-body').html(info);
	});
}

function can_reservacion(id)
{
    $.ajax({
				type: 'POST',
				url : 'reservaciones/can_reservacion.php',
                data: 'idres=' + id 
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}

function liberar_reservacion(id, fechares)
{
    $.ajax({
				type: 'POST',
				url : 'reservaciones/reservaciones.php',
                data: 'idres=' + id + '&hacer=liberar&fechares=' + fechares
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}

function pago_reservacion(id)
{
    $.ajax({
				type: 'POST',
				url : 'caja/pago_reservacion.php',
                data: 'idreservacion=' + id 
	}).done (function ( info ){
		$('#modal-body').html(info);
	});
}

function recibo_lavanderia(id)
{
    $.ajax({
				type: 'POST',
				url : 'lavanderia/rec_lavanderia.php',
                data: 'idreservacion=' + id 
	}).done (function ( info ){
		$('#modal-body').html(info);
	});
}

function ext_reservacion(id, fecha)
{
    $.ajax({
				type: 'POST',
				url : 'reservaciones/reservaciones.php',
                data: 'idreservacion=' + id + '&hacer=extender' + '&fechares=' + fecha
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}

function lista_reservaciones(fecha)
{
    $.ajax({
				type: 'POST',
				url : 'reservaciones/lista_reservaciones.php',
                data: 'fecha_reservacion=' + fecha 
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}

function edit_paciente(paciente){
	$.ajax({
				type: 'POST',
				url : 'pacientes/edit_paciente.php',
                data: 'paciente=' + paciente
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}

function edit_acompannante(acompannante){
    $.ajax({
                type: 'POST',
                url : 'pacientes/edit_acompannante.php',
                data: 'acompannante=' + acompannante
    }).done (function ( info ){
		$('#contenido').html(info);
	});
}

function estudiosocioeconomico(paciente){
    $.ajax({
				type: 'POST',
				url : 'trabajo_social/estudio_socioeconomico.php',
                data: 'paciente=' + paciente 
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}

function encsatisfaccionres(reservacion){
    $.ajax({
				type: 'POST',
				url : 'reservaciones/encuesta.php',
                data: 'reservacion=' + reservacion 
	}).done (function ( info ){
		$('#modal-body').html(info);
	});
}

function prestamomaterial(reservacion){
    $.ajax({
				type: 'POST',
				url : 'reservaciones/prestamo_material.php',
                data: 'reservacion=' + reservacion 
	}).done (function ( info ){
		$('#modal-body').html(info);
	});
}

//mostrar mensaje despues de los cambios
setTimeout(function() { 
    $('#mesaje').fadeOut('fast'); 
}, 1000)
</script>