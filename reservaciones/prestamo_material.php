<?php
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

$mensaje='';

if(isset($_POST["hacer"]))
{
    $r=explode('_', $_POST["recibio"]);

    $ResSMA=mysqli_query($conn, "SELECT * FROM material_apoyo_inventario WHERE IdReservacion='".$_POST["reservacion"]."' AND ES='S' LIMIT 1");
    if(mysqli_num_rows($ResSMA)==0)
    {
        mysqli_query($conn, "INSERT INTO material_apoyo_inventario (Fecha, IdReservacion, IdPA, PA, ES, IdMaterial, Cantidad, Observaciones, Usuario)
                                                        VALUES ('".$_POST["fecha"]."', '".$_POST["reservacion"]."', '".$r[1]."', '".$r[0]."', 'S', '".$_POST["material"]."', 
                                                                '1', '".$_POST["observaciones"]."',  '".$_SESSION["Id"]."')");

        //creamos el recibo
        mysqli_query($conn, "INSERT INTO material_apoyo_recibo (IdReservacion, Fecha, Usuario) VALUES ('".$_POST["reservacion"]."', '".date("Y-m-d")."', '".$_SESSION["Id"]."')");

        //bitacora
        mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '16', '".json_encode($_POST)."')");
    }
    else
    {
        mysqli_query($conn, "UPDATE material_apoyo_inventario SET Fecha='".$_POST["fecha"]."',
                                                                    IdPA='".$r[1]."',
                                                                    PA='".$r[0]."',
                                                                    IdMaterial='".$_POST["material"]."',
                                                                    Observaciones='".$_POST["observaciones"]."', 
                                                                    Usuario='".$_SESSION["Id"]."'
                                                            WHERE IdReservacion='".$_POST["reservacion"]."' AND ES='S'");

        //bitacora
        mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '18', '".json_encode($_POST)."')");
    }

    $ResEMA=mysqli_query($conn, "SELECT * FROM material_apoyo_inventario WHERE IdReservacion='".$_POST["reservacion"]."' AND ES='S' LIMIT 1");
    if(mysqli_num_rows($ResEMA)==1)
    {
        $ReseMA=mysqli_query($conn, "SELECT * FROM material_apoyo_inventario WHERE IdReservacion='".$_POST["reservacion"]."' AND ES='E' LIMIT 1");
        $d=explode('_', $_POST["devuelve"]);
        if(mysqli_num_rows($ReseMA)==0)
        {
            mysqli_query($conn, "INSERT INTO material_apoyo_inventario (Fecha, IdReservacion, IdPA, PA, ES, IdMaterial, Cantidad, Observaciones, Usuario)
                                                                VALUES ('".$_POST["fechadev"]."', '".$_POST["reservacion"]."', '".$d[1]."', '".$d[0]."', 'E', '".$_POST["material"]."', 
                                                                        '1', '".$_POST["observaciones"]."', '".$_SESSION["Id"]."')");

            //bitacora
            mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '17', '".json_encode($_POST)."')");
        }
        else
        {
            mysqli_query($conn, "UPDATE material_apoyo_inventario SET (Fecha='".$_POST["fechadev"]."',
                                                                        IdPA='".$d[1]."', 
                                                                        PA='".$d[0]."',
                                                                        IdMaterial='".$_POST["material"]."', 
                                                                        Observaciones='".$_POST["observaciones"]."',
                                                                        Usuario='".$_SESSION["Id"]."'
                                                                WHERE IdReservacion='".$_POST["reservacion"]."' AND ES='E'");

            //bitacora
            mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '19', '".json_encode($_POST)."')");
        }
        

        
    }

    $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Registro Exitoso</div>';
}

$ResRes=mysqli_fetch_array(mysqli_query($conn, "SELECT IdPaciente FROM reservacion WHERE Id='".$_POST["reservacion"]."' LIMIT 1"));

$ResSMA=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM material_apoyo_inventario WHERE IdReservacion='".$_POST["reservacion"]."' AND ES='S' LIMIT 1"));
$ResEMA=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM material_apoyo_inventario WHERE IdReservacion='".$_POST["reservacion"]."' AND ES='E' LIMIT 1"));

$ResRec=mysqli_fetch_array(mysqli_query($conn, "SELECT Id FROM material_apoyo_recibo WHERE IdReservacion='".$_POST["reservacion"]."' LIMIT 1"));



$cadena=$mensaje.'<div class="c100 card" style="border:0; box-shadow: none;">
            <h2>Prestamo material de apoyo</h2>
            <div class="c100">
                    <a href="reservaciones/recibo_ma.php?recibo='.$ResRec["Id"].'" target="_blank"><i class="fas fa-print"></i></a>
            </div>  ';

$cadena.='  <form name="fprestamaterial" id="fprestamaterial">
                <div class="c30">
                    <label class="l_form">Material</label>
                    <select name="material" id="material">
                        <option value="0">Selecciona</option>';
$ResMaterial=mysqli_query($conn, "SELECT * FROM material_apoyo ORDER BY Nombre ASC");
while($RResM=mysqli_fetch_array($ResMaterial))
{
    $cadena.='          <option value="'.$RResM["Id"].'"';if($ResSMA["IdMaterial"]==$RResM["Id"]){$cadena.=' selected';}$cadena.='>'.$RResM["Nombre"].'</option>';
}
$cadena.='          </select>
                </div>
                <div class="c30">
                    <label class="l_form">Recibe: </label>
                    <select name="recibio" id="recibio">
                        <option value="0">Selecciona</option>';
$ResPac=mysqli_query($conn, "SELECT Id, Nombre, Apellidos FROM pacientes WHERE Id='".$ResRes["IdPaciente"]."'");
while($RResPac=mysqli_fetch_array($ResPac))
{
    $cadena.='          <option value="P_'.$RResPac["Id"].'"';if($ResSMA["PA"]=='P' AND $ResSMA["IdPA"]==$RResPac["Id"]){$cadena.=' selected';}$cadena.='>'.$RResPac["Id"].' - '.$RResPac["Nombre"].' '.$RResPac["Apellidos"].'</option>';
}
$ResAco=mysqli_query($conn, "SELECT Id, Nombre FROM acompannantes WHERE IdPaciente='".$ResRes["IdPaciente"]."'");
while($RResAco=mysqli_fetch_array($ResAco))
{
    $cadena.='          <option value="A_'.$RResAco["Id"].'"';if($ResSMA["PA"]=='A' AND $ResSMA["IdPA"]==$RResAco["Id"]){$cadena.=' selected';}$cadena.='>'.$RResAco["Id"].' - '.$RResAco["Nombre"].'</option>';
}
$cadena.='          </select>   
                </div>
                <div class="c30">
                    <label class="l_form">Fecha: </label>
                    <input type="date" name="fecha" id="fecha" value="';if(isset($ResSMA["Fecha"])){$cadena.=$ResSMA["Fecha"];}else{$cadena.=date("Y-m-d");}$cadena.='">
                </div>
                <div class="c30">
                    <label class="l_form">Observaciones: </label>
                    <input type="text" name="observaciones" id="observaciones" value="'.$ResSMA["Observaciones"].'">
                </div>
                <div class="c30">
                    <label class="l_form">Devuelve: </label>
                    <select name="devuelve" id="devuelve">
                        <option value="0">Selecciona</option>';
$ResPac=mysqli_query($conn, "SELECT Id, Nombre, Apellidos FROM pacientes WHERE Id='".$ResRes["IdPaciente"]."'");
while($RResPac=mysqli_fetch_array($ResPac))
{
$cadena.='              <option value="P_'.$RResPac["Id"].'"';if($ResEMA["PA"]=='P' AND $ResEMA["IdPA"]==$RResPac["Id"]){$cadena.=' selected';}$cadena.='>'.$RResPac["Id"].' - '.$RResPac["Nombre"].' '.$RResPac["Apellidos"].'</option>';
}
$ResAco=mysqli_query($conn, "SELECT Id, Nombre FROM acompannantes WHERE IdPaciente='".$ResRes["IdPaciente"]."'");
while($RResAco=mysqli_fetch_array($ResAco))
{
$cadena.='              <option value="A_'.$RResAco["Id"].'"';if($ResEMA["PA"]=='A' AND $ResEMA["IdPA"]==$RResAco["Id"]){$cadena.=' selected';}$cadena.='>'.$RResAco["Id"].' - '.$RResAco["Nombre"].'</option>';
}
$cadena.='          </select>   
                </div>
                <div class="c30">
                    <label class="l_form">Fecha devolución: </label>
                    <input type="date" name="fechadev" id="fechadev" value="';if(isset($RResEMA["Fecha"])){$cadena.=$RResEMA["Fecha"];}else{$cadena.=date("Y-m-d");}$cadena.='">
                </div>  
                <div class="c80 c_der">
                    <input type="hidden" name="reservacion" id="reservacion" value="'.$_POST["reservacion"].'">
                    <input type="hidden" name="hacer" id="hacer" value="guardamaterial">
                    '.permisos(16, '<input type="submit" name="botmatapo" id="botmatapo" value="Guardar">').'   
                </div> 
                <div class="c20 c_der">
                </div>              
            </form>';

$cadena.='</div>';

echo $cadena;

?>
<script>
$("#fprestamaterial").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fprestamaterial"));

	$.ajax({
		url: "reservaciones/prestamo_material.php",
		type: "POST",
		dataType: "HTML",
		data: formData,
		cache: false,
		contentType: false,
		processData: false
	}).done(function(echo){
		$("#modal-body").html(echo);
	});
});
</script>