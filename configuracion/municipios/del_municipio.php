<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$ResPob=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM municipios WHERE Id='".$_POST["municipio"]."' LIMIT 1"));
$ResEst=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM Estados WHERE Id='".$ResPob["Estado"]."' LIMIT 1"));

$cadena='<div class="c100 card">
            <h2>Eliminar Municipion</h2>
            <label class="l_form">Esta seguro de eliminar el municipio '.utf8_encode($ResPob["municipio"]).' de '.utf8_encode($ResEst["Estado"]).'</label>
            <label class="l_form"><a href="#" onclick="delete_municipio_2(\''.$ResPob["municipio"].'\')">Si</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="municipios()">No</a></label>
        </div>';
    
echo $cadena;

?>
<script>
function delete_municipio_2(municipio){

    $.ajax({
                type: 'POST',
                url : 'configuracion/municipios/municipios.php',
                data: 'hacer=delmunicipio&municipio=' + municipio
    }).done (function ( info ){
        $('#contenido2').html(info);
    });
}

</script>