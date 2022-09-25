<?php
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

//0 por confimar | 1 confirmado | 2 cancelada
$fechai=$_POST["fechai"];
$fechaf=$_POST["fechaf"];
//calcula datos

//total de reservaciones
$ResRes=mysqli_query($conn, "SELECT * FROM reservacion WHERE Fecha >= '".$fechai."' AND Fecha <= '".$fechaf."' ORDER BY Fecha DESC");
$TReservaciones=mysqli_num_rows($ResRes);
//total de ocupaciones
$ResOcu=mysqli_query($conn, "SELECT * FROM reservacion WHERE Fecha >= '".$fechai."' AND Fecha <= '".$fechaf."' AND Estatus=1 ORDER BY Fecha DESC");
$TOcupaciones=mysqli_num_rows($ResOcu);
//total por confirmar
$ResCon=mysqli_query($conn, "SELECT * FROM reservacion WHERE Fecha >= '".$fechai."' AND Fecha <= '".$fechaf."' AND Estatus=0 ORDER BY Fecha DESC");
$TpConfirmar=mysqli_num_rows($ResCon);
//total cancelaciones
$ResCan=mysqli_query($conn, "SELECT * FROM reservacion WHERE Fecha >= '".$fechai."' AND Fecha <= '".$fechaf."' AND Estatus=2 ORDER BY Fecha DESC");
$TCancelaciones=mysqli_num_rows($ResCan);

//edad
$edadn=($fechaf[0].$fechaf[1].$fechaf[2].$fechaf[3])-12;
$mes=$fechaf[4].$fechaf[5];

//total de personas atendidas
//$TPersonas=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r WHERE r.IdPA!=0 AND r.Fecha >= '".$fechai."' AND r.Fecha <= '".$fechaf."' GROUP BY r.IdPA"));
$TPersonas=mysqli_num_rows(mysqli_query($conn, "SELECT concat_ws('-', r.IdPA, r.Tipo) AS idpa FROM reservaciones AS r 
                                                WHERE r.IdPA!=0 AND r.Fecha >= '".$fechai."' AND r.Fecha <= '".$fechaf."' AND r.Estatus=1 GROUP BY concat_ws('-', r.IdPA, r.Tipo)"));
//total hombres
$TPH=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                            INNER JOIN pacientes AS p ON r.IdPA=p.Id 
                                            WHERE r.IdPA!=0 AND p.Sexo='M' AND r.Fecha >= '".$fechai."' AND r.Fecha <= '".$fechaf."' AND r.Estatus=1 GROUP BY r.IdPA"));
//total mujeres
$TPM=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                            INNER JOIN pacientes AS p ON r.IdPA=p.Id 
                                            WHERE r.IdPA!=0 AND p.Sexo='F' AND r.Fecha >= '".$fechai."' AND r.Fecha <= '".$fechaf."' AND r.Estatus=1 GROUP BY r.IdPA"));

//total pacientes
$TP=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r WHERE r.IdPA!=0 AND r.Tipo='P' AND r.Fecha >= '".$fechai."' AND r.Fecha <= '".$fechaf."' AND r.Estatus=1 GROUP BY r.IdPA"));
//total pacientes hombres
$TPH=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                            INNER JOIN pacientes AS p ON p.Id=r.IdPA
                                            WHERE r.IdPA!=0 AND r.Tipo='P' AND r.Fecha >= '".$fechai."' AND r.Fecha <= '".$fechaf."' AND p.Sexo='M' AND r.Estatus=1 GROUP BY r.IdPA"));
//total pacientes hombres niños
$TPHN=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                            INNER JOIN pacientes AS p ON p.Id=r.IdPA
                                            WHERE r.IdPA!=0 AND r.Tipo='P' AND r.Fecha >= '".$fechai."' AND r.Fecha <= '".$fechaf."' AND p.Sexo='M' AND p.FechaNacimiento>'".$edadn."-".$mes."-01' AND r.Estatus=1 GROUP BY r.IdPA"));
//total pacientes hombres adultos
$TPHA=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                            INNER JOIN pacientes AS p ON p.Id=r.IdPA
                                            WHERE r.IdPA!=0 AND r.Tipo='P' AND r.Fecha >= '".$fechai."' AND r.Fecha <= '".$fechaf."' AND p.Sexo='M' AND p.FechaNacimiento<'".$edadn."-".$mes."-01' AND r.Estatus=1 GROUP BY r.IdPA"));
//total pacientes mujeres
$TPM=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                            INNER JOIN pacientes AS p ON p.Id=r.IdPA
                                            WHERE r.IdPA!=0 AND r.Tipo='P' AND r.Fecha >= '".$fechai."' AND r.Fecha <= '".$fechaf."' AND p.Sexo='F' AND r.Estatus=1 GROUP BY r.IdPA"));
//total pacientes mujeres niñas
$TPMN=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                            INNER JOIN pacientes AS p ON p.Id=r.IdPA
                                            WHERE r.IdPA!=0 AND r.Tipo='P' AND r.Fecha >= '".$fechai."' AND r.Fecha <= '".$fechaf."' AND p.Sexo='F' AND p.FechaNacimiento>'".$edadn."-".$mes."-01' AND r.Estatus=1 GROUP BY r.IdPA"));
//total pacientes mujeres adultas
$TPMA=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                            INNER JOIN pacientes AS p ON p.Id=r.IdPA
                                            WHERE r.IdPA!=0 AND r.Tipo='P' AND r.Fecha >= '".$fechai."' AND r.Fecha <= '".$fechaf."' AND p.Sexo='F' AND p.FechaNacimiento<'".$edadn."-".$mes."-01' AND r.Estatus=1 GROUP BY r.IdPA"));

//total acompañantes
$TA=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r WHERE r.IdPA!=0 AND r.Tipo='A' AND r.Fecha >= '".$fechai."' AND r.Fecha <= '".$fechaf."' AND r.Estatus=1 GROUP BY r.IdPA"));
//total acompañantes hombres
$TAH=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                            INNER JOIN acompannantes AS a ON a.Id=r.IdPA
                                            WHERE r.IdPA!=0 AND r.Tipo='A' AND r.Fecha >= '".$fechai."' AND r.Fecha <= '".$fechaf."' AND a.Sexo='M' AND r.Estatus=1 GROUP BY r.IdPA"));
//total acompañantes hombres niños
$TAHN=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                            INNER JOIN pacientes AS p ON p.Id=r.IdPA
                                            WHERE r.IdPA!=0 AND r.Tipo='A' AND r.Fecha >= '".$fechai."' AND r.Fecha <= '".$fechaf."' AND p.Sexo='M' AND p.FechaNacimiento>'".$edadn."-".$mes."-01' AND r.Estatus=1 GROUP BY r.IdPA"));
//total acompañantes hombres adultos
$TAHA=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                            INNER JOIN pacientes AS p ON p.Id=r.IdPA
                                            WHERE r.IdPA!=0 AND r.Tipo='A' AND r.Fecha >= '".$fechai."' AND r.Fecha <= '".$fechaf."' AND p.Sexo='M' AND p.FechaNacimiento<'".$edadn."-".$mes."-01' AND r.Estatus=1 GROUP BY r.IdPA"));
//total acompañantes mujeres
$TAM=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                            INNER JOIN acompannantes AS a ON a.Id=r.IdPA
                                            WHERE r.IdPA!=0 AND r.Tipo='A' AND r.Fecha >= '".$fechai."' AND r.Fecha <= '".$fechaf."' AND a.Sexo='F' AND r.Estatus=1 GROUP BY r.IdPA"));
//total acompañantes mujeres niñas
$TAMN=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                            INNER JOIN pacientes AS p ON p.Id=r.IdPA
                                            WHERE r.IdPA!=0 AND r.Tipo='A' AND r.Fecha >= '".$fechai."' AND r.Fecha <= '".$fechaf."' AND p.Sexo='F' AND p.FechaNacimiento>'".$edadn."-".$mes."-01' AND r.Estatus=1 GROUP BY r.IdPA"));
//total acompañantes mujeres adultas
$TAMA=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(r.IdPA) AS personas, r.IdPA FROM reservaciones AS r 
                                            INNER JOIN pacientes AS p ON p.Id=r.IdPA
                                            WHERE r.IdPA!=0 AND r.Tipo='A' AND r.Fecha >= '".$fechai."' AND r.Fecha <= '".$fechaf."' AND p.Sexo='F' AND p.FechaNacimiento<'".$edadn."-".$mes."-01' AND r.Estatus=1 GROUP BY r.IdPA"));

//pacientes por hospital
$ResHospitales=mysqli_query($conn, "SELECT COUNT(I.Hospital) AS Numero, I.Hospital AS Hospital
                                        FROM (SELECT COUNT(i.Instituto) AS Pacientes, i.Instituto AS Hospital 
                                                FROM pacientes AS p 
                                                INNER JOIN institutos AS i ON p.Instituto1=i.Id 
                                                INNER JOIN reservacion AS r ON r.IdPaciente=p.Id 
                                                WHERE r.FechaReservacion >= '".$fechai."' AND r.FechaReservacion <= '".$fechaf."' 
                                                GROUP BY r.IdPaciente, i.Instituto) AS I 
                                        GROUP BY I.Hospital ORDER BY I.Hospital ASC");
$NumHosp=mysqli_num_rows($ResHospitales);

//pacientes por procedencia
$ResProcedencia=mysqli_query($conn, "SELECT COUNT(Es.Estado) AS Numero, Es.Estado AS Estado, Es.EId
                                    FROM (SELECT COUNT(E.Estado) AS Pacientes, E.Estado AS Estado, E.Id AS EId
                                        FROM pacientes AS p 
                                        INNER JOIN Estados AS E ON p.Estado=E.Id 
                                        INNER JOIN reservacion AS r ON r.IdPaciente=p.Id 
                                        WHERE r.FechaReservacion >= '".$fechai."' AND r.FechaReservacion <= '".$fechaf."' 
                                        GROUP BY r.IdPaciente, E.Estado, E.Id) AS Es
                                    GROUP BY Es.Estado, Es.EId ORDER BY Es.Estado ASC");
$NumEstados=mysqli_num_rows($ResProcedencia);

$cadena='<h2>Reservaciones del periodo: '.fecha($fechai).' al '.fecha($fechaf).'</h2>
        <div class="c100 card">
            <div class="c100" style="display: flex; flex-wrap: wrap; justify-content: center; align-items: center; align-content: center;">
                <form name="frestiempo" id="frestiempo">
                <div class="c25">
                    De: <input type="date" name="fechai" id="fechai" style="width: calc(100% - 50px);" value="'.$fechai.'"> 
                </div>
                <div class="c25">
                    A: <input type="date" name="fechaf" id="fechaf" style="width: calc(100% - 50px);" value="'.$fechaf.'"> 
                </div>
                <div class="c25">
                    <input type="submit" name="bot_periodo" id="bot_periodo" value="Ver">
                </div>
                <div class="c25">
                <a href="estadisticos/lista_tiempo.php?fechai='.$fechai.'&fechaf='.$fechaf.'" target="_blank"><i class="fas fa-th-list"></i> Ver Lista</a>
                <a href="estadisticos/lista_tiempo_excel.php?fechai='.$fechai.'&fechaf='.$fechaf.'" target="_blank"><i class="far fa-file-excel"></i> Exportar</a>
                </div>
            </div>
        </div>
        
        <div class="c100 card">
            <div class="c45">
                <label class="l_form">Reservaciones: '.number_format($TReservaciones).'</label>
                <label class="l_form"><input type="checkbox" name="res_estancias" id="res_estancias" value="1" checked> <i class="fas fa-hotel i_estadistico"></i> Estancias: '.number_format($TOcupaciones).'</label>
                <label class="l_form"><input type="checkbox" name="res_porconfirmar" id="res_porconfirmar" value="1" checked> <i class="fas fa-hotel i_estadistico"></i> Por Confirmar: '.number_format($TpConfirmar).'</label>
                <label class="l_form"><input type="checkbox" name="res_canceladas" id="res_canceladas" value="1" checked> <i class="fas fa-hotel i_estadistico"></i> Cancelaciones: '.number_format($TCancelaciones).'</label>
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
                <label class="l_form">Personas atendidas: '.number_format($TPersonas).'</label>
                <label class="l_form"><input type="checkbox" name="per_ate_pacientes" id="per_ate_pacientes" value="1" checked> <i class="fas fa-user-injured i_estadistico"></i> Pacientes: '.$TP.'</label>
                <label class="l_form"><input type="checkbox" name="per_ate_acompanantes" id="per_ate_acompanantes" value="1" checked> <i class="fas fa-user-friends i_estadistico"></i> Acompañantes: '.$TA.'</label>
                <label class="l_form"><input type="checkbox" name="per_ate_hombres" id="per_ate_hombres" value="1" checked> <i class="fas fa-male i_estadistico"></i> Hombres: '.$TPH.'</label>
                <label class="l_form"><input type="checkbox" name="per_ate_mujeres" id="per_ate_mujeres" value="1" checked> <i class="fas fa-female i_estadistico"></i> Mujeres: '.$TPM.'</label>
            </div>
            <div class="c45">
                <canvas id="myChart_personas_atendidas"></canvas>
            </div>
            <script>
                var canvas = document.getElementById("myChart_personas_atendidas");
                var pat = canvas.getContext(\'2d\');
                
                var data = {
                    labels: ["Personas Atendidas"],
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
                            data:['.$TPH.'],
                            backgroundColor: [\'rgba(8,100,191, 0.2)\'],
                            borderColor: [\'rgba(8,100,191, 1)\'],
                            borderWidth: [2]
                        },
                        {
                            label: \'Mujeres\',
                            data:['.$TPM.'],
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
                <label class="l_form"><input type="checkbox" name="pac_hombres" id="pac_hombres" value="1" checked> <i class="fas fa-male i_estadistico"></i> Hombres: '.$TPH.'</label>
                <label class="l_form"><input type="checkbox" name="pac_ninos" id="pac_ninos" value="1" checked> - Niños: '.$TPHN.'</label>
                <label class="l_form"><input type="checkbox" name="pac_adultos" id="pac_adulots" value="1" checked> - Adultos: '.$TPHA.'</label>
                <label class="l_form"><input type="checkbox" name="pac_mujeres" id="pac_mujeres" value="1" checked> <i class="fas fa-female i_estadistico"></i> Mujeres: '.$TPM.'</label>
                <label class="l_form"><input type="checkbox" name="pac_ninas" id="pac_ninas" value="1" checked> - Niñas: '.$TPMN.'</label>
                <label class="l_form"><input type="checkbox" name="pac_adultas" id="pac_adultas" value="1" checked> - Adultas: '.$TPMA.'</label>
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
            <label class="l_form"><input type="checkbox" name="aco_hombres" id="aco_hombres" value="1" checked> <i class="fas fa-male i_estadistico"></i> Hombres: '.$TAH.'</label>
            <label class="l_form"><input type="checkbox" name="aco_ninos" id="aco_ninos" value="1" checked> - Niños: '.$TAHN.'</label>
            <label class="l_form"><input type="checkbox" name="aco_adultos" id="aco_adultos" value="1" checked> - Adultos: '.$TAHA.'</label>
            <label class="l_form"><input type="checkbox" name="aco_mujeres" id="aco_mujeres" value="1" checked> <i class="fas fa-female i_estadistico"></i> Mujeres: '.$TAM.'</label>
            <label class="l_form"><input type="checkbox" name="aco_ninas" id="aco_ninas" value="1" checked> - Niñas: '.$TAMN.'</label>
            <label class="l_form"><input type="checkbox" name="aco_adultas" id="aco_adultas" value="1" checked> - Adultas: '.$TAMA.'</label>
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
                <label class="l_form">Hospitales: '.$NumHosp.'</label>';
                $h=1; $dhosp='{
                        labels: ["Hospitales"], datasets:[';
                while($RResHosp=mysqli_fetch_array($ResHospitales))
                {
                    $cadena.='<label class="l_form"><input type="checkbox" name="hospital" id="hospital" value="1" checked> <i class="fas fa-hospital i_estadistico"></i> - '.$RResHosp["Hospital"].': '.$RResHosp["Numero"].'</label>';
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
                    $cadena.='<label class="l_form"> <input type="checkbox" name="estado" id="estado" value="1" checked> <i class="fas fa-map-signs  i_estadistico"></i> '.utf8_encode($RResEst["Estado"]).': '.$RResEst["Numero"].'</label>';

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
                        $cadena.='<label class="l_form"> <input type="checkbox" name="municipio" id="municipio" value="1" checked>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;* '.utf8_encode($RResMun["Municipio"]).': '.$RResMun["Numero"].'</label>';
                        
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