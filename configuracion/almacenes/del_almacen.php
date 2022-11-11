<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$ResAlm=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM almacenes WHERE Id='".$_POST["almacen"]."' LIMIT 1"));

$cadena='<div class="c100 card">
            <h2>Eliminar Municipion</h2>
            <label class="l_form">Esta seguro de eliminar el almacen '.utf8_encode($ResAlm["Nombre"]).'</label>
            <label class="l_form"><a href="#" onclick="delete_almacen_2(\''.$ResAlm["Id"].'\')">Si</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="almacenes()">No</a></label>
        </div>';
    
echo $cadena;

?>
<script>
function delete_almacen_2(almacen){

    $.ajax({
                type: 'POST',
                url : 'configuracion/almacenes/almacenes.php',
                data: 'hacer=delalmacen&almacen=' + almacen
    }).done (function ( info ){
        $('#contenido2').html(info);
    });
}

</script>