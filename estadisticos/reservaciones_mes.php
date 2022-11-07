<?php
date_default_timezone_set('America/Mexico_City');
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

//0 por confimar | 1 confirmado | 2 cancelada
$anno=$_POST["anno"];
$mes=$_POST["mes"];
$dia=date("d");

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

//edad
$edadn=$anno-12;

//total de personas atendidas
//$TPersonas=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r WHERE r.IdPA!=0 AND r.Fecha LIKE '".$anno."-".$mes."-%' AND r.Estatus=1 GROUP BY r.IdPA"));
$TPersonas=mysqli_num_rows(mysqli_query($conn, "SELECT concat_ws('-', r.IdPA, r.Tipo) AS idpa FROM reservaciones AS r 
                                                WHERE `Fecha` LIKE '".$anno."-".$mes."-%' AND Estatus=1 AND Cama>0 GROUP BY concat_ws('-', r.IdPA, r.Tipo)"));
//total hombres
$TPH=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                            INNER JOIN pacientes AS p ON r.IdPA=p.Id 
                                            WHERE r.IdPA!=0 AND p.Sexo='M' AND r.Fecha LIKE '".$anno."-".$mes."-%' AND r.Estatus=1 AND r.Cama>0 GROUP BY r.IdPA"));
//total mujeres
$TPM=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                            INNER JOIN pacientes AS p ON r.IdPA=p.Id 
                                            WHERE r.IdPA!=0 AND p.Sexo='F' AND r.Fecha LIKE '".$anno."-".$mes."-%' AND r.Estatus=1 AND r.Cama>0 GROUP BY r.IdPA"));

//total pacientes
$ResTP=mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
                                FROM (SELECT Id FROM pacientes WHERE Id IN 
                                (SELECT IdPA FROM reservaciones WHERE Fecha LIKE '".$anno."-".$mes."-%' AND Tipo = 'P' AND Estatus=1 AND Cama>0 GROUP BY IdPA ORDER BY IdPA ASC)) AS s"));
$TP=$ResTP["Numero"];
//total pacientes hombres
$TPH=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                            INNER JOIN pacientes AS p ON p.Id=r.IdPA
                                            WHERE r.IdPA!=0 AND r.Tipo='P' AND r.Fecha LIKE '".$anno."-".$mes."-%' AND p.Sexo='M' AND r.Estatus=1 AND Cama>0 GROUP BY r.IdPA"));
//total pacientes hombres niños
$TPHN=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                            INNER JOIN pacientes AS p ON p.Id=r.IdPA
                                            WHERE r.IdPA!=0 AND r.Tipo='P' AND r.Fecha LIKE '".$anno."-".$mes."-%' AND p.Sexo='M' AND p.FechaNacimiento>'".$edadn."-".$mes."-01' AND r.Estatus=1 AND Cama>0 GROUP BY r.IdPA"));
//total pacientes hombres adultos
$TPHA=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                            INNER JOIN pacientes AS p ON p.Id=r.IdPA
                                            WHERE r.IdPA!=0 AND r.Tipo='P' AND r.Fecha LIKE '".$anno."-".$mes."-%' AND p.Sexo='M' AND p.FechaNacimiento<'".$edadn."-".$mes."-01' AND r.Estatus=1 AND Cama>0 GROUP BY r.IdPA"));
//total pacientes mujeres
$TPM=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                            INNER JOIN pacientes AS p ON p.Id=r.IdPA
                                            WHERE r.IdPA!=0 AND r.Tipo='P' AND r.Fecha LIKE '".$anno."-".$mes."-%' AND p.Sexo='F' AND r.Estatus=1 AND Cama>0 GROUP BY r.IdPA"));
//total pacientes mujeres niñas
$TPMN=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                            INNER JOIN pacientes AS p ON p.Id=r.IdPA
                                            WHERE r.IdPA!=0 AND r.Tipo='P' AND r.Fecha LIKE '".$anno."-".$mes."-%' AND p.Sexo='F' AND p.FechaNacimiento>'".$edadn."-".$mes."-01' AND r.Estatus=1 AND Cama>0 GROUP BY r.IdPA"));
//total pacientes mujeres adultas
$TPMA=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                            INNER JOIN pacientes AS p ON p.Id=r.IdPA
                                            WHERE r.IdPA!=0 AND r.Tipo='P' AND r.Fecha LIKE '".$anno."-".$mes."-%' AND p.Sexo='F' AND p.FechaNacimiento<'".$edadn."-".$mes."-01' AND r.Estatus=1 AND Cama>0 GROUP BY r.IdPA"));

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
//edad 0 a 3 años paciente
//$ResTres=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
//                                FROM (SELECT Id FROM pacientes WHERE Id IN 
//                                (SELECT IdPA FROM reservaciones WHERE Fecha LIKE '".$anno."-".$mes."-%' AND Tipo = 'P' AND Estatus=1 GROUP BY IdPA ORDER BY IdPA ASC) 
//                                AND FechaNacimiento < '".$anno."-".$mes."-31' AND FechaNacimiento >= '".($Y-3)."-".$mes."-01') AS s");
//$RResTres=mysqli_fetch_array($ResTres);
//$_SESSION["PRTres"]=$RResTres["Numero"];
////edad de 3 a 6 años pacientes
//$ResSeis=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
//                                FROM (SELECT Id FROM pacientes WHERE Id IN 
//                                (SELECT IdPA FROM reservaciones WHERE Fecha LIKE '".$anno."-".$mes."-%' AND Tipo = 'P' AND Estatus=1 GROUP BY IdPA ORDER BY IdPA ASC) 
//                                AND FechaNacimiento < '".($Y-3)."-".$mes."-01' AND FechaNacimiento >= '".($Y-6)."-".$mes."-01') AS s");
//$RResSeis=mysqli_fetch_array($ResSeis);
//$_SESSION["PRSeis"]=$RResSeis["Numero"];
////edad de 6 a 12 años pacientes
//$ResDoce=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
//                                FROM (SELECT Id FROM pacientes WHERE Id IN 
//                                (SELECT IdPA FROM reservaciones WHERE Fecha LIKE '".$anno."-".$mes."-%' AND Tipo = 'P' AND Estatus=1 GROUP BY IdPA ORDER BY IdPA ASC) 
//                                AND FechaNacimiento < '".($Y-6)."-".$mes."-01' AND FechaNacimiento >= '".($Y-12)."-".$mes."-01') AS s");
$ResDoce=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
                                FROM (SELECT Id FROM pacientes WHERE Id IN 
                                (SELECT IdPA FROM reservaciones WHERE Fecha LIKE '".$anno."-".$mes."-%' AND Tipo = 'P' AND Estatus=1 AND Cama>0 GROUP BY IdPA ORDER BY IdPA ASC) 
                                AND FechaNacimiento < '".$anno."-".$mes."-31' AND FechaNacimiento >= '".($Y-12)."-".$mes."-01') AS s");
$RResDoce=mysqli_fetch_array($ResDoce);
$_SESSION["PRDoce"]=$RResDoce["Numero"];
//edad de 12 a 18 años pacientes
//$ResDieciocho=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
//                                FROM (SELECT Id FROM pacientes WHERE Id IN 
//                                (SELECT IdPA FROM reservaciones WHERE Fecha LIKE '".$anno."-".$mes."-%' AND Tipo = 'P' AND Estatus=1 GROUP BY IdPA ORDER BY IdPA ASC) 
//                                AND FechaNacimiento < '".($Y-12)."-".$mes."-01' AND FechaNacimiento >= '".($Y-18)."-".$mes."-01') AS s");
//$RResDieciocho=mysqli_fetch_array($ResDieciocho);
//edad de 13 a 20 años pacientes
$ResVeinte=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
                                FROM (SELECT Id FROM pacientes WHERE Id IN 
                                (SELECT IdPA FROM reservaciones WHERE Fecha LIKE '".$anno."-".$mes."-%' AND Tipo = 'P' AND Estatus=1 AND Cama>0 GROUP BY IdPA ORDER BY IdPA ASC) 
                                AND FechaNacimiento < '".($Y-12)."-".$mes."-01' AND FechaNacimiento >= '".($Y-20)."-".$mes."-01') AS s");
$RResVeinte=mysqli_fetch_array($ResVeinte);
$_SESSION["PRVeinte"]=$RResVeinte["Numero"];
//edad de 18 a 50 años pacientes
//$ResCincuenta=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
//                                FROM (SELECT Id FROM pacientes WHERE Id IN 
//                                (SELECT IdPA FROM reservaciones WHERE Fecha LIKE '".$anno."-".$mes."-%' AND Tipo = 'P' AND Estatus=1 GROUP BY IdPA ORDER BY IdPA ASC) 
//                                AND FechaNacimiento < '".($Y-18)."-".$mes."-01' AND FechaNacimiento >= '".($Y-50)."-".$mes."-01') AS s");
//$RResCincuenta=mysqli_fetch_array($ResCincuenta);
//edad de 21 a 64 años pacientes
$ResSesentaycuatro=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
                                FROM (SELECT Id FROM pacientes WHERE Id IN 
                                (SELECT IdPA FROM reservaciones WHERE Fecha LIKE '".$anno."-".$mes."-%' AND Tipo = 'P' AND Estatus=1 AND Cama>0 GROUP BY IdPA ORDER BY IdPA ASC) 
                                AND FechaNacimiento < '".($Y-20)."-".$mes."-01' AND FechaNacimiento >= '".($Y-64)."-".$mes."-01') AS s");
$RResSesentaycuatro=mysqli_fetch_array($ResSesentaycuatro);
$_SESSION["PRSesentaycuatro"]=$RResSesentaycuatro["Numero"];
//edad de 50 a 65 años pacientes
//$ResSesentaycinco=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
//                                FROM (SELECT Id FROM pacientes WHERE Id IN 
//                                (SELECT IdPA FROM reservaciones WHERE Fecha LIKE '".$anno."-".$mes."-%' AND Tipo = 'P' AND Estatus=1 GROUP BY IdPA ORDER BY IdPA ASC) 
//                                AND FechaNacimiento < '".($Y-50)."-".$mes."-01' AND FechaNacimiento >= '".($Y-65)."-".$mes."-01') AS s");
//$RResSesentaycinco=mysqli_fetch_array($ResSesentaycinco);
//$_SESSION["PRSesentaycinco"]=$RResSesentaycinco["Numero"];
//edad de mas de 65 años pacientes
$ResMas=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
                                FROM (SELECT Id FROM pacientes WHERE Id IN 
                                (SELECT IdPA FROM reservaciones WHERE Fecha LIKE '".$anno."-".$mes."-%' AND Tipo = 'P' AND Estatus=1 AND Cama>0 GROUP BY IdPA ORDER BY IdPA ASC) 
                                AND FechaNacimiento < '".($Y-64)."-".$mes."-01') AS s");
$RResMas=mysqli_fetch_array($ResMas);
$_SESSION["PRMas"]=$RResMas["Numero"];

//edad 0 a 3 años acompañantes
//$ResTresA=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
//                                FROM (SELECT Id FROM acompannantes WHERE Id IN 
//                                (SELECT IdPA FROM reservaciones WHERE Fecha LIKE '".$anno."-".$mes."-%' AND Tipo = 'A' AND Estatus=1 GROUP BY IdPA ORDER BY IdPA ASC) 
//                                AND FechaNacimiento < '".$anno."-".$mes."-31' AND FechaNacimiento >= '".($Y-3)."-".$mes."-01') AS s");
//$RResTresA=mysqli_fetch_array($ResTresA);
//$_SESSION["ARTres"]=$RResTresA["Numero"];
////edad de 3 a 6 años acompañantes
//$ResSeisA=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
//                                FROM (SELECT Id FROM acompannantes WHERE Id IN 
//                                (SELECT IdPA FROM reservaciones WHERE Fecha LIKE '".$anno."-".$mes."-%' AND Tipo = 'A' AND Estatus=1 GROUP BY IdPA ORDER BY IdPA ASC) 
//                                AND FechaNacimiento < '".($Y-3)."-".$mes."-01' AND FechaNacimiento >= '".($Y-6)."-".$mes."-01') AS s");
//$RResSeisA=mysqli_fetch_array($ResSeisA);
//$_SESSION["ARSeis"]=$RResSeisA["Numero"];
////edad de 6 a 12 años acompañantes
//$ResDoceA=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
//                                FROM (SELECT Id FROM acompannantes WHERE Id IN 
//                                (SELECT IdPA FROM reservaciones WHERE Fecha LIKE '".$anno."-".$mes."-%' AND Tipo = 'A' AND Estatus=1 GROUP BY IdPA ORDER BY IdPA ASC) 
//                                AND FechaNacimiento < '".($Y-6)."-".$mes."-01' AND FechaNacimiento >= '".($Y-12)."-".$mes."-01') AS s");
//$RResDoceA=mysqli_fetch_array($ResDoceA);
//$_SESSION["ARDoce"]=$RResDoceA["Numero"];
//edad de 0 a 12 años acompañantes
$ResDoceA=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
                                FROM (SELECT Id FROM acompannantes WHERE Id IN 
                                (SELECT IdPA FROM reservaciones WHERE Fecha LIKE '".$anno."-".$mes."-%' AND Tipo = 'A' AND Estatus=1 GROUP BY IdPA ORDER BY IdPA ASC) 
                                AND FechaNacimiento < '".$anno."-".$mes."-31' AND FechaNacimiento >= '".($Y-12)."-".$mes."-01') AS s");
$RResDoceA=mysqli_fetch_array($ResDoceA);
$_SESSION["ARDoce"]=$RResDoceA["Numero"];
//edad de 12 a 18 años acompañantes
//$ResDieciochoA=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
//                                FROM (SELECT Id FROM acompannantes WHERE Id IN 
//                                (SELECT IdPA FROM reservaciones WHERE Fecha LIKE '".$anno."-".$mes."-%' AND Tipo = 'A' AND Estatus=1 GROUP BY IdPA ORDER BY IdPA ASC) 
//                                AND FechaNacimiento < '".($Y-12)."-".$mes."-01' AND FechaNacimiento >= '".($Y-18)."-".$mes."-01') AS s");
//$RResDieciochoA=mysqli_fetch_array($ResDieciochoA);
//$_SESSION["ARDieciocho"]=$RResDieciochoA["Numero"];
//edad de 13 a 20 años acompañantes
$ResVeinteA=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
                                FROM (SELECT Id FROM acompannantes WHERE Id IN 
                                (SELECT IdPA FROM reservaciones WHERE Fecha LIKE '".$anno."-".$mes."-%' AND Tipo = 'A' AND Estatus=1 GROUP BY IdPA ORDER BY IdPA ASC) 
                                AND FechaNacimiento < '".($Y-12)."-".$mes."-01' AND FechaNacimiento >= '".($Y-20)."-".$mes."-01') AS s");
$RResVeinteA=mysqli_fetch_array($ResVeinteA);
$_SESSION["ARVeinte"]=$RResVeinteA["Numero"];
//edad de 21 a 64 años acompañantes
$ResSesentaycuatroA=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
                                FROM (SELECT Id FROM acompannantes WHERE Id IN 
                                (SELECT IdPA FROM reservaciones WHERE Fecha LIKE '".$anno."-".$mes."-%' AND Tipo = 'A' AND Estatus=1 GROUP BY IdPA ORDER BY IdPA ASC) 
                                AND FechaNacimiento < '".($Y-20)."-".$mes."-01' AND FechaNacimiento >= '".($Y-64)."-".$mes."-01') AS s");
$RResSesentaycuatroA=mysqli_fetch_array($ResSesentaycuatroA);
$_SESSION["ARSesentaycuatro"]=$RResSesentaycuatroA["Numero"];
//edad de 50 a 65 años acompañantes
//$ResSesentaycincoA=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
//                                FROM (SELECT Id FROM acompannantes WHERE Id IN 
//                                (SELECT IdPA FROM reservaciones WHERE Fecha LIKE '".$anno."-".$mes."-%' AND Tipo = 'A' AND Estatus=1 GROUP BY IdPA ORDER BY IdPA ASC) 
//                                AND FechaNacimiento < '".($Y-50)."-".$mes."-01' AND FechaNacimiento >= '".($Y-65)."-".$mes."-01') AS s");
//$RResSesentaycincoA=mysqli_fetch_array($ResSesentaycincoA);
//$_SESSION["ARSesentaycinco"]=$RResSesentaycincoA["Numero"];
//edad de mas de 65 años acompañantes
$ResMasA=mysqli_query($conn, "SELECT COUNT(s.Id) AS Numero 
                                FROM (SELECT Id FROM acompannantes WHERE Id IN 
                                (SELECT IdPA FROM reservaciones WHERE Fecha LIKE '".$anno."-".$mes."-%' AND Tipo = 'A' AND Estatus=1 GROUP BY IdPA ORDER BY IdPA ASC) 
                                AND FechaNacimiento < '".($Y-64)."-".$mes."-01') AS s");
$RResMasA=mysqli_fetch_array($ResMasA);
$_SESSION["ARMas"]=$RResMasA["Numero"];


//Servicios
//hospedajes
$ResHospedajes=mysqli_fetch_array(mysqli_query($conn, "SELECT count(Id) AS hospedajes FROM reservaciones WHERE Fecha LIKE '".$anno."-".$mes."-%' AND Cama>0 AND Estatus='1' "));
$alimentos=$ResHospedajes["hospedajes"]*3;
$lavanderia=$ResHospedajes["hospedajes"]/7;
$servicios=$ResHospedajes["hospedajes"]+$alimentos+$lavanderia;

//enfermedades
$ResEnfermedades=mysqli_query($conn, "SELECT p.Diagnostico1 AS Diagnostico, COUNT(*) AS Numero FROM `reservacion`AS r 
                                        INNER JOIN pacientes AS p ON p.Id=r.IdPaciente 
                                        WHERE r.Fecha LIKE '".$anno."-".$mes."-%' AND p.Diagnostico1!='' GROUP BY p.Diagnostico1 ORDER BY `Numero` ASC");
$Enfermedades=mysqli_num_rows($ResEnfermedades);

$L = new DateTime( $anno.'-'.$mes.'-01' ); 
$last = $L->format( 'Y-m-t' );

$cadena='<h2>Reservaciones '; if($mes!='%'){$cadena.=mes($mes).' - ';}$cadena.=$anno.'</h2>
        <div class="c100 card">
            <div class="c100" style="display: flex; flex-wrap: wrap; justify-content: center; align-items: center; align-content: center;">
                <form name="frestiempo" id="frestiempo">
                <div class="c25">
                    De: <input type="date" name="fechai" id="fechai" style="width: calc(100% - 50px);" value="'.$anno.'-'.$mes.'-01"> 
                </div>
                <div class="c25">
                    A: <input type="date" name="fechaf" id="fechaf" style="width: calc(100% - 50px);" value="'.$last.'"> 
                </div>
                <div class="c25">
                    <input type="submit" name="bot_periodo" id="bot_periodo" value="Ver">
                </div>
                <div class="c25">
                    '.permisos(102, '<a href="estadisticos/lista_reservaciones.php?anno='.$anno.'&mes='.$mes.'" target="_blank"><i class="fas fa-th-list"></i> Ver lista</a>').' 
                    '.permisos(103, '<a href="estadisticos/lista_personas_excel.php?anno='.$anno.'&mes='.$mes.'" target="_blank"><i class="far fa-file-excel"></i> Exportar</a>').'
                    '.permisos(104, '<a href="estadisticos/reporte_mes_pdf.php?anno='.$anno.'&mes='.$mes.'" target="_blank"><i class="fas fa-print"></i> Imprimir</a>').'
                </div>
            </div>
        </div>
        
        <div class="c100 card">
            <div class="c45">
                <label class="l_form">Reservaciones: '.number_format($TReservaciones).'</label>
                <label class="l_form"><i class="fas fa-hotel i_estadistico"></i> Estancias: '.number_format($TOcupaciones).'</label>
                <label class="l_form"><i class="fas fa-hotel i_estadistico"></i> Por Confirmar: '.number_format($TpConfirmar).'</label>
                <label class="l_form"><i class="fas fa-hotel i_estadistico"></i> Cancelaciones: '.number_format($TCancelaciones).'</label>
            </div>
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
                <label class="l_form">Servicios otorgados: '.number_format(($ResHospedajes["hospedajes"]+$alimentos+$lavanderia)).'</label>
                <label class="l_form"><i class="fas fa-house-user i_estadistico"></i> Hospedajes: '.number_format($ResHospedajes["hospedajes"]).'</label>
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
                            data:['.$ResHospedajes["hospedajes"].'],
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
                <label class="l_form">Total de Albergados: '.number_format($TPersonas).'</label>
                <label class="l_form"><i class="fas fa-user-injured i_estadistico"></i> Pacientes: '.$TP.'</label>
                <label class="l_form"><i class="fas fa-user-friends i_estadistico"></i> Acompañantes: '.$TA.'</label>
                <label class="l_form"><i class="fas fa-male i_estadistico"></i> Hombres: '.($TPH+$TAH).'</label>
                <label class="l_form"><i class="fas fa-female i_estadistico"></i> Mujeres: '.($TPM+$TAM).'</label>
                
            </div>
            <div class="c45">
                <canvas id="myChart_personas_atendidas"></canvas>
            </div>
            <script>
                var canvas = document.getElementById("myChart_personas_atendidas");
                var pat = canvas.getContext(\'2d\');
                
                var data = {
                    labels: ["Total de albergados"],
                      datasets: [{
                            label: \'Pacientes\',
                            data:['.$TP.'],
                            backgroundColor: [\'rgba(250, 143, 146, 0.2)\'],
                            borderColor: [\'rgba(250, 143, 146, 1)\'],
                            borderWidth: [2]
                            
                        },
                        {
                            label: \'Acompañantes\',
                            data:['.$TA.'],
                            backgroundColor: [\'rgba(162,155,238, 0.2)\'],
                            borderColor: [\'rgba(162,155,238, 1)\'],
                            borderWidth: [2]
                        },
                        {
                            label: \'Hombres\',
                            data:['.($TPH+$TAH).'],
                            backgroundColor: [\'rgba(8,100,191, 0.2)\'],
                            borderColor: [\'rgba(8,100,191, 1)\'],
                            borderWidth: [2]
                        },
                        {
                            label: \'Mujeres\',
                            data:['.($TPM+$TAM).'],
                            backgroundColor: [\'rgba(252,0,103, 0.2)\'],
                            borderColor: [\'rgba(252,0,103, 1)\'],
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
            <div class="c45">
                <label class="l_form">Pacientes: '.$TP.'</label>
                <label class="l_form"><i class="fas fa-male i_estadistico"></i> Hombres: '.$TPH.'</label>
                <label class="l_form"> - Niños: '.$TPHN.'</label>
                <label class="l_form"> - Adultos: '.$TPHA.'</label>
                <label class="l_form"><i class="fas fa-female i_estadistico"></i> Mujeres: '.$TPM.'</label>
                <label class="l_form"> - Niñas: '.$TPMN.'</label>
                <label class="l_form"> - Adultas: '.$TPMA.'</label>
            </div>
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
        </div>
        <div class="c100 card">
            <div class="c45">
            <label class="l_form">Acompañantes: '.$TA.'</label>
            <label class="l_form"><i class="fas fa-male i_estadistico"></i> Hombres: '.$TAH.'</label>
            <label class="l_form"> - Niños: '.$TAHN.'</label>
            <label class="l_form"> - Adultos: '.$TAHA.'</label>
            <label class="l_form"><i class="fas fa-female i_estadistico"></i> Mujeres: '.$TAM.'</label>
            <label class="l_form"> - Niñas: '.$TAMN.'</label>
            <label class="l_form"> - Adultas: '.$TAMA.'</label>
            </div>
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
        </div>

        <div class="c100 card">
            <div class="c45">
            <label class="l_form">Edades Pacientes</label>
            <label class="l_form"><i class="fas fa-child i_estadistico"></i> 0 a 12 años: '.$RResDoce["Numero"].'</label>
            <label class="l_form"><i class="fas fa-male i_estadistico"></i> 13 a 20 años: '.$RResVeinte["Numero"].'</label>
            <label class="l_form"><i class="fas fa-male i_estadistico"></i> 21 a 64 años: '.$RResSesentaycuatro["Numero"].'</label>
            <label class="l_form"><i class="fas fa-wheelchair i_estadistico"></i> 65 y más años: '.$RResMas["Numero"].'</label>
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
                        backgroundColor: [\'rgba(100,88,233, 0.2)\'],
                        borderColor: [\'rgba(100,88,233, 1)\'],
                        borderWidth: [2]
                    },
                    {
                        label: "21 a 64 años", 
                        data: ['.$RResSesentaycuatro["Numero"].'],
                        backgroundColor: [\'rgba(237,30,121, 0.2)\'],
                        borderColor: [\'rgba(237,30,121, 1)\'],
                        borderWidth: [2]
                    },
                    {
                        label: "65 y más años", 
                        data: ['.$RResMas["Numero"].'],
                        backgroundColor: [\'rgba(0,51,102, 0.2)\'],
                        borderColor: [\'rgba(0,51,102, 1)\'],
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
        </div>
        <div class="c100 card">
            <div class="c45">
            <label class="l_form">Edades Acompañantes</label>
            <label class="l_form"><i class="fas fa-child i_estadistico"></i> 0 a 12 años: '.$_SESSION["ARDoce"].'</label>
            <label class="l_form"><i class="fas fa-male i_estadistico"></i> 13 a 20 años: '.$_SESSION["ARVeinte"].'</label>
            <label class="l_form"><i class="fas fa-male i_estadistico"></i> 21 a 64 años: '.$_SESSION["ARSesentaycuatro"].'</label>
            <label class="l_form"><i class="fas fa-wheelchair i_estadistico"></i> 65 y más años: '.$_SESSION["ARMas"].'</label>
            </div>
            <div class="c45">
                <canvas id="myChart_edad_acompanantes"></canvas>
            </div>
            <script>
                var canvas = document.getElementById("myChart_edad_acompanantes");
                var aco = canvas.getContext(\'2d\');
                
                var data = {
                    labels: ["Acompañantes por edades"],
                    datasets: [{
                        label: "0 a 12 años", 
                        data: ['.$_SESSION["ARDoce"].'],
                        backgroundColor: [\'rgba(38,183,25, 0.2)\'],
                        borderColor: [\'rgba(38,183,25, 1)\'],
                        borderWidth: [2]
                    },
                    {
                        label: "13 a 20 años", 
                        data: ['.$_SESSION["ARVeinte"].'],
                        backgroundColor: [\'rgba(100,88,233, 0.2)\'],
                        borderColor: [\'rgba(100,88,233, 1)\'],
                        borderWidth: [2]
                    },
                    {
                        label: "21 a 64 años", 
                        data: ['.$_SESSION["ARSesentaycuatro"].'],
                        backgroundColor: [\'rgba(237,30,121, 0.2)\'],
                        borderColor: [\'rgba(237,30,121, 1)\'],
                        borderWidth: [2]
                    },
                    {
                        label: "65 y más años", 
                        data: ['.$_SESSION["ARMas"].'],
                        backgroundColor: [\'rgba(0,51,102, 0.2)\'],
                        borderColor: [\'rgba(0,51,102, 1)\'],
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
        </div>

        <div class="c100 card">
            <div class="c45">
                <label class="l_form">Enfermedades: '.$Enfermedades.'</label>';
                $e=1; $denf='{
                    labels: ["Enfermedades"], datasets:[';
                while($RResEnf=mysqli_fetch_array($ResEnfermedades))
                {
                    $ResEnfermedad=mysqli_fetch_array(mysqli_query($conn, "SELECT Diagnostico FROM diagnosticos WHERE Id='".$RResEnf["Diagnostico"]."' LIMIT 1"));

                    $cadena.='<label class="l_form"> <i class="fas fa-head-side-cough i_estadistico"></i> '.$ResEnfermedad["Diagnostico"].' - '.$RResEnf["Numero"].'</label>';

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
        </div>

        <div class="c100 card"> 
            <div class="c45">
                <label class="l_form">Hospitales: '.$NumHosp.'</label>';
                $h=1; $dhosp='{
                        labels: ["Hospitales"], datasets:[';
                while($RResHosp=mysqli_fetch_array($ResHospitales))
                {
                    $cadena.='<label class="l_form"><i class="fas fa-hospital i_estadistico"></i> - '.$RResHosp["Hospital"].': '.$RResHosp["Numero"].'</label>';
                    $r=rand(0,255); $g=rand(0,255); $b=rand(0,255);
                    
                    $dhosp.='{
                        label: \''.$RResHosp["Hospital"].'\',
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
        </div>
        <div class="c100 card"> 
            <div class="c45">
                <label class="l_form">Procedencia: '.$NumEstados.' estados</label>';
                $e=1; $destados='{
                    labels: ["Estados"], datasets:[';
                while($RResEst=mysqli_fetch_array($ResProcedencia))
                {
                    $cadena.='<label class="l_form"> <i class="fas fa-map-signs  i_estadistico"></i> '.utf8_encode($RResEst["Estado"]).': '.$RResEst["Numero"].'</label>';

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

                    $ResMunicipio=mysqli_query($conn, "SELECT COUNT(Mu.Municipio) AS Numero, Mu.Municipio AS Municipio
                                                        FROM (SELECT COUNT(m.Municipio) AS Pacientes, m.Municipio AS Municipio
                                                                FROM pacientes AS p 
                                                                INNER JOIN municipios AS m ON p.Municipio=m.Id 
                                                                INNER JOIN reservacion AS r ON r.IdPaciente=p.Id 
                                                                WHERE r.FechaReservacion LIKE '".$anno."-".$mes."-%' AND m.Estado='".$RResEst["EId"]."'
                                                                GROUP BY r.IdPaciente, m.Municipio) AS Mu
                                                        GROUP BY Mu.Municipio ORDER BY Mu.Municipio ASC");
                    while($RResMun=mysqli_fetch_array($ResMunicipio))
                    {
                        //$cadena.='<label class="l_form"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;* '.utf8_encode($RResMun["Municipio"]).': '.$RResMun["Numero"].'</label>';
                        
                    }

                }
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
        

echo $cadena;

//bitacora
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '101', '".json_encode($_POST)."')");

?>
<script>
$("#frestiempo").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("frestiempo"));

	$.ajax({
		url: "estadisticos/reservaciones_tiempo.php",
		type: "POST",
		dataType: "HTML",
		data: formData,
		cache: false,
		contentType: false,
		processData: false
	}).done(function(echo){
		$("#cont_mes_res").html(echo);
	});
});
</script>