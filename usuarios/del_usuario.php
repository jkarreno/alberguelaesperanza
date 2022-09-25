<?php
//Inicio la sesion 
session_start();

include('../conexion.php');

$ResUser=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM usuarios WHERE Id='".$_POST["usuario"]."' LIMIT 1"));

$cadena='<div class="c100 card">
            <h2>Eliminar usuario</h2>
            <label class="l_form">Esta seguro de eliminar el usuario '.$ResUser["Usuario"].'</label>
            <label class="l_form"><a href="#" onclick="delete_user_2(\''.$ResUser["Id"].'\')">Si</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="usuarios()">No</a></label>
        </div>';
    
echo $cadena;

?>
<script>
function delete_user_2(usuario){

    $.ajax({
                type: 'POST',
                url : 'usuarios/usuarios.php',
                data: 'hacer=borrausuario&usuario=' + usuario
    }).done (function ( info ){
        $('#contenido').html(info);
    });
}

</script>