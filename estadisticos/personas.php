<?php
//Inicio la sesion 
session_start();

include('../conexion.php');

//Pacientes Mujeres
$PM=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM pacientes WHERE Sexo='F'"));
//Pacientes Hombres
$PH=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM pacientes WHERE Sexo='M'"));
//Total pacientes
$PT=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM pacientes"));

//calcular edades
$fechaactual = date('Y-m-d');
//resta 10 años a la fecha actual
$fecha10 = strtotime ('-10 year' , strtotime($fechaactual));
$fecha10 = date ('Y-m-d',$fecha10);

$PH10=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM pacientes WHERE Sexo='M' AND FechaNacimiento>='".$fecha10."' AND Fallecido='0'"));
$PM10=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM pacientes WHERE Sexo='F' AND FechaNacimiento>='".$fecha10."' AND Fallecido='0'"));

//resta 11 años a 20 años
$fecha20 = strtotime ('-20 year' , strtotime($fechaactual));
$fecha20 = date ('Y-m-d',$fecha20);

$PH20=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM pacientes WHERE Sexo='M' AND FechaNacimiento>='".$fecha20."' AND FechaNacimiento<'".$fecha10."' AND Fallecido='0'"));
$PM20=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM pacientes WHERE Sexo='F' AND FechaNacimiento>='".$fecha20."' AND FechaNacimiento<'".$fecha10."' AND Fallecido='0'"));

//resta 21 años a 30 años
$fecha30 = strtotime ('-30 year' , strtotime($fechaactual));
$fecha30 = date ('Y-m-d',$fecha30);

$PH30=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM pacientes WHERE Sexo='M' AND FechaNacimiento>='".$fecha30."' AND FechaNacimiento<'".$fecha20."' AND Fallecido='0'"));
$PM30=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM pacientes WHERE Sexo='F' AND FechaNacimiento>='".$fecha30."' AND FechaNacimiento<'".$fecha20."' AND Fallecido='0'"));

//resta 31 años a 40 años
$fecha40 = strtotime ('-40 year' , strtotime($fechaactual));
$fecha40 = date ('Y-m-d',$fecha40);

$PH40=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM pacientes WHERE Sexo='M' AND FechaNacimiento>='".$fecha40."' AND FechaNacimiento<'".$fecha30."' AND Fallecido='0'"));
$PM40=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM pacientes WHERE Sexo='F' AND FechaNacimiento>='".$fecha40."' AND FechaNacimiento<'".$fecha30."' AND Fallecido='0'"));

//resta 41 años a 50 años
$fecha50 = strtotime ('-50 year' , strtotime($fechaactual));
$fecha50 = date ('Y-m-d',$fecha50);

$PH50=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM pacientes WHERE Sexo='M' AND FechaNacimiento>='".$fecha50."' AND FechaNacimiento<'".$fecha40."' AND Fallecido='0'"));
$PM50=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM pacientes WHERE Sexo='F' AND FechaNacimiento>='".$fecha50."' AND FechaNacimiento<'".$fecha40."' AND Fallecido='0'"));

//resta 51 años a 60 años
$fecha60 = strtotime ('-60 year' , strtotime($fechaactual));
$fecha60 = date ('Y-m-d',$fecha60);

$PH60=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM pacientes WHERE Sexo='M' AND FechaNacimiento>='".$fecha60."' AND FechaNacimiento<'".$fecha50."' AND Fallecido='0'"));
$PM60=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM pacientes WHERE Sexo='F' AND FechaNacimiento>='".$fecha60."' AND FechaNacimiento<'".$fecha50."' AND Fallecido='0'"));

//resta 61 años a 70 años
$fecha70 = strtotime ('-70 year' , strtotime($fechaactual));
$fecha70 = date ('Y-m-d',$fecha70);

$PH70=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM pacientes WHERE Sexo='M' AND FechaNacimiento>='".$fecha70."' AND FechaNacimiento<'".$fecha60."' AND Fallecido='0'"));
$PM70=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM pacientes WHERE Sexo='F' AND FechaNacimiento>='".$fecha70."' AND FechaNacimiento<'".$fecha60."' AND Fallecido='0'"));

//resta 71 años a 80 años
$fecha80 = strtotime ('-80 year' , strtotime($fechaactual));
$fecha80 = date ('Y-m-d',$fecha80);

$PH80=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM pacientes WHERE Sexo='M' AND FechaNacimiento>='".$fecha80."' AND FechaNacimiento<'".$fecha70."' AND Fallecido='0'"));
$PM80=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM pacientes WHERE Sexo='F' AND FechaNacimiento>='".$fecha80."' AND FechaNacimiento<'".$fecha70."' AND Fallecido='0'"));

//resta 81 años a 90 años
$fecha90 = strtotime ('-90 year' , strtotime($fechaactual));
$fecha90 = date ('Y-m-d',$fecha90);

$PH90=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM pacientes WHERE Sexo='M' AND FechaNacimiento>='".$fecha90."' AND FechaNacimiento<'".$fecha80."' AND Fallecido='0'"));
$PM90=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM pacientes WHERE Sexo='F' AND FechaNacimiento>='".$fecha90."' AND FechaNacimiento<'".$fecha80."' AND Fallecido='0'"));

//resta 91 años a 100 años
$fecha100 = strtotime ('-100 year' , strtotime($fechaactual));
$fecha100 = date ('Y-m-d',$fecha100);

$PH100=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM pacientes WHERE Sexo='M' AND FechaNacimiento>='".$fecha100."' AND FechaNacimiento<'".$fecha90."' AND Fallecido='0'"));
$PM100=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM pacientes WHERE Sexo='F' AND FechaNacimiento>='".$fecha100."' AND FechaNacimiento<'".$fecha90."' AND Fallecido='0'"));

//resta 100 años o mas
$PH100m=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM pacientes WHERE Sexo='M' AND FechaNacimiento<'".$fecha100."' AND Fallecido='0'"));
$PM100m=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM pacientes WHERE Sexo='F' AND FechaNacimiento<'".$fecha100."' AND Fallecido='0'"));

//falelcidos
$PHF=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM pacientes WHERE Sexo='M' AND Fallecido='1'"));
$PMF=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM pacientes WHERE Sexo='F' AND Fallecido='1'"));

$cadena='<div class="c100 card">
            <div class="c20 card">
                <h2>Pacientes </h1>
                <label class="l_form">Hombres: '.number_format($PH).'</label>
                <label class="l_form">Mujeres: '.number_format($PM).'</label>
                <label class="l_form">Total: '.number_format($PT).'</label>
                <div class="c50"><p><strong>0 a 10 años:</strong><br />Hombre:  '.number_format($PH10).'<br /> Mujeres: '.number_format($PM10).'</p></div>
                <div class="c50"><p><strong>11 a 20 años:</strong><br />Hombre:  '.number_format($PH20).'<br /> Mujeres: '.number_format($PM20).'</p></div>
                <div class="c50"><p><strong>21 a 30 años:</strong><br />Hombre:  '.number_format($PH30).'<br /> Mujeres: '.number_format($PM30).'</p></div>
                <div class="c50"><p><strong>31 a 40 años:</strong><br />Hombre:  '.number_format($PH40).'<br /> Mujeres: '.number_format($PM40).'</p></div>
                <div class="c50"><p><strong>41 a 50 años:</strong><br />Hombre:  '.number_format($PH50).'<br /> Mujeres: '.number_format($PM50).'</p></div>
                <div class="c50"><p><strong>51 a 60 años:</strong><br />Hombre:  '.number_format($PH60).'<br /> Mujeres: '.number_format($PM60).'</p></div>
                <div class="c50"><p><strong>61 a 70 años:</strong><br />Hombre:  '.number_format($PH70).'<br /> Mujeres: '.number_format($PM70).'</p></div>
                <div class="c50"><p><strong>71 a 80 años:</strong><br />Hombre:  '.number_format($PH80).'<br /> Mujeres: '.number_format($PM80).'</p></div>
                <div class="c50"><p><strong>81 a 90 años:</strong><br />Hombre:  '.number_format($PH90).'<br /> Mujeres: '.number_format($PM90).'</p></div>
                <div class="c50"><p><strong>91 a 100 años:</strong><br />Hombre:  '.number_format($PH100).'<br /> Mujeres: '.number_format($PM100).'</p></div>
                <div class="c50"><p><strong>más de 100 años:</strong><br />Hombre:  '.number_format($PH100m).'<br /> Mujeres: '.number_format($PM100m).'</p></div>
                <div class="c50"><p><strong>Fallecidos</strong><br />Hombre:  '.number_format($PHF).'<br /> Mujeres: '.number_format($PMF).'</p></div>
            </div>
            <div class="c70 card">
                <canvas id="myChart"></canvas>
            </div>
        </div>';

$cadena.='<script>
var ctx = document.getElementById(\'myChart\').getContext(\'2d\');
        var myChart = new Chart(ctx, {
            type: \'line\',
            data: {
                labels: [\'0-10\', \'11-20\', \'21-30\', \'31-40\', \'41-50\', \'51-60\', \'61-70\', \'71-80\', \'81-90\', \'90-100\', \'100+\'],
                datasets: [{
                    label: \'Hombres\',
                    data: [\''.$PH10.'\', \''.$PH20.'\', \''.$PH30.'\', \''.$PH40.'\', \''.$PH50.'\', \''.$PH60.'\', \''.$PH70.'\', \''.$PH80.'\', \''.$PH90.'\', \''.$PH100.'\', \''.$PH100m.'\'],
                    backgroundColor: [
                        \'rgba(55,184,60, 0.2)\'
                    ],
                    borderColor: [
                        \'rgba(55,184,60, 1)\'
                    ],
                    borderWidth: 2
                },
                {
                    label: \'Mujeres\',
                    data: [\''.$PM10.'\', \''.$PM20.'\', \''.$PM30.'\', \''.$PM40.'\', \''.$PM50.'\', \''.$PM60.'\', \''.$PH70.'\', \''.$PM80.'\', \''.$PM90.'\', \''.$PM100.'\', \''.$PM100m.'\'],
                    backgroundColor: [
                        \'rgba(176,64,136, 0.2)\'
                    ],
                    borderColor: [
                        \'rgba(176,64,136, 1)\'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    
</script>';


//acompañantes/////////////////////////////////////////////////////////////////////////////////////////////////
//acompañantes Mujeres
$AM=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM acompannantes WHERE Sexo='F'"));
//acompañantes Hombres
$AH=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM acompannantes WHERE Sexo='M'"));
//Total acompañantes
$AT=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM acompannantes"));

//resta 10 años a la fecha actual
$AH10=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM acompannantes WHERE Sexo='M' AND FechaNacimiento>='".$fecha10."'"));
$AM10=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM acompannantes WHERE Sexo='F' AND FechaNacimiento>='".$fecha10."'"));

//resta 11 años a 20 años
$AH20=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM acompannantes WHERE Sexo='M' AND FechaNacimiento>='".$fecha20."' AND FechaNacimiento<'".$fecha10."'"));
$AM20=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM acompannantes WHERE Sexo='F' AND FechaNacimiento>='".$fecha20."' AND FechaNacimiento<'".$fecha10."'"));

//resta 21 años a 30 años
$AH30=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM acompannantes WHERE Sexo='M' AND FechaNacimiento>='".$fecha30."' AND FechaNacimiento<'".$fecha20."'"));
$AM30=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM acompannantes WHERE Sexo='F' AND FechaNacimiento>='".$fecha30."' AND FechaNacimiento<'".$fecha20."'"));

//resta 31 años a 40 años
$AH40=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM acompannantes WHERE Sexo='M' AND FechaNacimiento>='".$fecha40."' AND FechaNacimiento<'".$fecha30."'"));
$AM40=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM acompannantes WHERE Sexo='F' AND FechaNacimiento>='".$fecha40."' AND FechaNacimiento<'".$fecha30."'"));

//resta 41 años a 50 años
$AH50=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM acompannantes WHERE Sexo='M' AND FechaNacimiento>='".$fecha50."' AND FechaNacimiento<'".$fecha40."'"));
$AM50=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM acompannantes WHERE Sexo='F' AND FechaNacimiento>='".$fecha50."' AND FechaNacimiento<'".$fecha40."'"));

//resta 51 años a 60 años
$AH60=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM acompannantes WHERE Sexo='M' AND FechaNacimiento>='".$fecha60."' AND FechaNacimiento<'".$fecha50."'"));
$AM60=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM acompannantes WHERE Sexo='F' AND FechaNacimiento>='".$fecha60."' AND FechaNacimiento<'".$fecha50."'"));

//resta 61 años a 70 años
$AH70=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM acompannantes WHERE Sexo='M' AND FechaNacimiento>='".$fecha70."' AND FechaNacimiento<'".$fecha60."'"));
$AM70=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM acompannantes WHERE Sexo='F' AND FechaNacimiento>='".$fecha70."' AND FechaNacimiento<'".$fecha60."'"));

//resta 71 años a 80 años
$AH80=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM acompannantes WHERE Sexo='M' AND FechaNacimiento>='".$fecha80."' AND FechaNacimiento<'".$fecha70."'"));
$AM80=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM acompannantes WHERE Sexo='F' AND FechaNacimiento>='".$fecha80."' AND FechaNacimiento<'".$fecha70."'"));

//resta 81 años a 90 años
$AH90=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM acompannantes WHERE Sexo='M' AND FechaNacimiento>='".$fecha90."' AND FechaNacimiento<'".$fecha80."'"));
$AM90=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM acompannantes WHERE Sexo='F' AND FechaNacimiento>='".$fecha90."' AND FechaNacimiento<'".$fecha80."'"));

//resta 91 años a 100 años
$AH100=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM acompannantes WHERE Sexo='M' AND FechaNacimiento>='".$fecha100."' AND FechaNacimiento<'".$fecha90."'"));
$AM100=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM acompannantes WHERE Sexo='F' AND FechaNacimiento>='".$fecha100."' AND FechaNacimiento<'".$fecha90."'"));

//resta 100 años o mas
$AH100m=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM acompannantes WHERE Sexo='M' AND FechaNacimiento<'".$fecha100."'"));
$AM100m=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM acompannantes WHERE Sexo='F' AND FechaNacimiento<'".$fecha100."'"));

$cadena.='<div class="c100 card">
            <div class="c20 card">
                <h2>Acompañantes </h1>
                <label class="l_form">Hombres: '.number_format($AH).'</label>
                <label class="l_form">Mujeres: '.number_format($AM).'</label>
                <label class="l_form">Total: '.number_format($AT).'</label>
                <div class="c50"><p><strong>0 a 10 años:</strong><br />Hombre:  '.$AH10.'<br /> Mujeres: '.$AM10.'</p></div>
                <div class="c50"><p><strong>11 a 20 años:</strong><br />Hombre:  '.$AH20.'<br /> Mujeres: '.$AM20.'</p></div>
                <div class="c50"><p><strong>21 a 30 años:</strong><br />Hombre:  '.$AH30.'<br /> Mujeres: '.$AM30.'</p></div>
                <div class="c50"><p><strong>31 a 40 años:</strong><br />Hombre:  '.$AH40.'<br /> Mujeres: '.$AM40.'</p></div>
                <div class="c50"><p><strong>41 a 50 años:</strong><br />Hombre:  '.$AH50.'<br /> Mujeres: '.$AM50.'</p></div>
                <div class="c50"><p><strong>51 a 60 años:</strong><br />Hombre:  '.$AH60.'<br /> Mujeres: '.$AM60.'</p></div>
                <div class="c50"><p><strong>61 a 70 años:</strong><br />Hombre:  '.$AH70.'<br /> Mujeres: '.$AM70.'</p></div>
                <div class="c50"><p><strong>71 a 80 años:</strong><br />Hombre:  '.$AH80.'<br /> Mujeres: '.$AM80.'</p></div>
                <div class="c50"><p><strong>81 a 90 años:</strong><br />Hombre:  '.$AH90.'<br /> Mujeres: '.$AM90.'</p></div>
                <div class="c50"><p><strong>91 a 100 años:</strong><br />Hombre:  '.$AH100.'<br /> Mujeres: '.$AM100.'</p></div>
                <div class="c50"><p><strong>más de 100 años:</strong><br />Hombre:  '.$AH100m.'<br /> Mujeres: '.$AM100m.'</p></div>
            </div>
            <div class="c70 card">
                <canvas id="myChart2"></canvas>
            </div>
        </div>';

$cadena.='<script>
var ctx = document.getElementById(\'myChart2\').getContext(\'2d\');
        var myChart = new Chart(ctx, {
            type: \'line\',
            data: {
                labels: [\'0-10\', \'11-20\', \'21-30\', \'31-40\', \'41-50\', \'51-60\', \'61-70\', \'71-80\', \'81-90\', \'90-100\', \'100+\'],
                datasets: [{
                    label: \'Hombres\',
                    data: [\''.$AH10.'\', \''.$AH20.'\', \''.$AH30.'\', \''.$AH40.'\', \''.$AH50.'\', \''.$AH60.'\', \''.$AH70.'\', \''.$AH80.'\', \''.$AH90.'\', \''.$AH100.'\', \''.$AH100m.'\'],
                    backgroundColor: [
                        \'rgba(55,184,60, 0.2)\'
                    ],
                    borderColor: [
                        \'rgba(55,184,60, 1)\'
                    ],
                    borderWidth: 2
                },
                {
                    label: \'Mujeres\',
                    data: [\''.$AM10.'\', \''.$AM20.'\', \''.$AM30.'\', \''.$AM40.'\', \''.$AM50.'\', \''.$AM60.'\', \''.$AH70.'\', \''.$AM80.'\', \''.$AM90.'\', \''.$AM100.'\', \''.$AM100m.'\'],
                    backgroundColor: [
                        \'rgba(176,64,136, 0.2)\'
                    ],
                    borderColor: [
                        \'rgba(176,64,136, 1)\'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    
</script>';

echo $cadena;

?>


