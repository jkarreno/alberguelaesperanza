<?php
//Inicio la sesion 
session_start();

include('../conexion.php');

$ResRes=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM reservacion WHERE Id='".$_POST["idres"]."' LIMIT 1"));

$ResPac=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, Nombre, Apellidos, Apellidos2 FROM pacientes WHERE Id='".$ResRes["IdPaciente"]."' LIMIT 1"));


$cadena='<div class="c100 card" style="border:0; box-shadow: none;">
            <h2>NuevaEditar reservación '.$ResRes["Id"].'</h2>
            <form name="fedreservacion" id="fedreservacion">
                <div class="c20">
                    <label class="l_form">Num. Paciente:</label>
                    <input type="text" name="num_paciente" id="num_paciente" value="'.$ResPac["Id"].'">
                </div>
                <div class="c70">
                    <label class="l_form">Paciente:</label>
                    <input id="paciente" name="paciente" type="text" value="'.$ResPac["Nombre"].' '.$ResPac["Apellidos"].' '.$ResPac["Apellidos2"].'" disabled>
                </div>
                <div class="c20">
                    <label class="l_form">Fecha:</label>
                    <input type="date" name="fechares" id="fechares" value="'.$ResRes["Fecha"].'">
                </div>
                <div class="c20">
                    <label class="l_form">Dias: </label>
                    <input type="text" name="diasres" id="diasres" value="'.$ResRes["Dias"].'">
                </div>
                <div class="c20">
                    <label class="l_form">Cama: </label>
                    <select name="cama" id="cama" onmousedown="if(this.options.length>5){this.size=5; this.style.height=105;}" onchange="this.size=0; this.style.height=35;" onblur="this.size=0; this.style.height=35;" required>
                        <option value="0"';if($ResRes["Cama"]<=0){$cadena.=' selected';}$cadena.='>No requiere cama</option>';
$ResCamas=mysqli_query($conn, "SELECT c.Id, h.Habitacion, c.Cama FROM camas AS c 
                                INNER JOIN habitaciones as h ON c.Habitacion = h.Id 
                                ORDER BY h.Habitacion ASC, c.Cama ASC");
while($RResCam=mysqli_fetch_array($ResCamas))
{
    $ResCamaResP=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM reservaciones WHERE IdReservacion='".$ResRes["Id"]."' AND Tipo='P' AND IdPA='".$ResPac["Id"]."' LIMIT 1"));

    $cadena.='          <option value="'.$RResCam["Id"].'"';if($ResCamaResP["Cama"]==$RResCam["Id"]){ $cadena.=' selected';}$cadena.='>'.$RResCam["Habitacion"].' - '.$RResCam["Cama"].'</option>';
}
$cadena.='           </select>
                </div>
                <div class="c20"> 
                    <label class="l_form">Hospitalizado</label>
                    <ul class="tg-list">
                        <li class="tg-list-item">
                            <input class="tgl tgl-light" id="hospitalizado" name="hospitalizado" type="checkbox" value="1" ';if($ResCamaResP["Cama"]==-1){$cadena.=' checked';}$cadena.='/>
                            <label class="tgl-btn" for="hospitalizado"></label>
                        </li>
                    </ul>
                </div>

                <div class="c40">
                    <label class="l_form">Instituto al que asiste</label>
                    <select name="instituto" id="instituto" onmousedown="if(this.options.length>5){this.size=5; this.style.height=105;}" onchange="this.size=0; this.style.height=35;" onblur="this.size=0; this.style.height=35;">';
$ResInstitutos=mysqli_query($conn, "SELECT * FROM institutos ORDER BY Instituto ASC");
while($RResI=mysqli_fetch_array($ResInstitutos))
{
$cadena.='              <option value="'.$RResI["Id"].'"';if($ResRes["Instituto"]==$RResI["Id"]){$cadena.=' selected';}$cadena.='>'.$RResI["Instituto"].'</option>';
}
$cadena.='          </select> 
                </div>
                <div class="c40">
                    <label class="l_form">Diagnostico/enfermedad</label>
                    <select name="diagnostico" id="diagnostico" onmousedown="if(this.options.length>5){this.size=5; this.style.height=105;}" onchange="this.size=0; this.style.height=35;" onblur="this.size=0; this.style.height=35;">';
$ResDiagnosticos=mysqli_query($conn, "SELECT * FROM diagnosticos ORDER BY Diagnostico");
while($RResD=mysqli_fetch_array($ResDiagnosticos))
{
$cadena.='              <option value="'.$RResD["Id"].'"';if($ResRes["Diagnostico"]==$RResD["Id"]){$cadena.=' selected';}$cadena.='>'.$RResD["Diagnostico"].'</option>';
}
$cadena.='          </select>
                </div>

                <div class="c100">
                    <label class="l_form">Acompañantes: </label>
                </div>';

    $ResAco=mysqli_query($conn, "SELECT Id, Nombre, Apellidos, Apellidos2 FROM acompannantes WHERE IdPaciente='".$ResPac["Id"]."' ORDER BY Nombre ASC");
    while($RResAco=mysqli_fetch_array($ResAco))
    {
        $ResAcoCam=mysqli_num_rows(mysqli_query($conn, "SELECT IdPA FROM reservaciones WHERE IdReservacion='".$ResRes["Id"]."' AND Tipo='A' AND IdPA='".$RResAco["Id"]."'"));
        $cadena.='  <div class="c10">
                        <ul class="tg-list">
                        <li class="tg-list-item">
                            <input class="tgl tgl-light" id="aco_'.$RResAco["Id"].'" name="aco_'.$RResAco["Id"].'" type="checkbox" value="1" ';if($ResAcoCam>0){$cadena.=' checked';}$cadena.='/>
                            <label class="tgl-btn" for="aco_'.$RResAco["Id"].'"></label>
                        </li>
                        </ul>
                    </div>
                    <div class="c60"> 
                        '.$RResAco["Nombre"].' '.$RResAco["Apellidos"].' '.$RResAco["Apellidos2"].'
                    </div> 
                    <div class="c30"> 
                        <select name="cama_aco_'.$RResAco["Id"].'" id="cama_aco_'.$RResAco["Id"].'" onmousedown="if(this.options.length>5){this.size=5; this.style.height=105;}" onchange="this.size=0; this.style.height=35;" onblur="this.size=0; this.style.height=35;">';
        $ResCamas=mysqli_query($conn, "SELECT c.Id, h.Habitacion, c.Cama FROM camas AS c 
                                        INNER JOIN habitaciones as h ON c.Habitacion = h.Id 
                                        ORDER BY h.Habitacion ASC, c.Cama ASC");
        while($RResCam=mysqli_fetch_array($ResCamas))
        {
            $ResCamaResA=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM reservaciones WHERE IdReservacion='".$ResRes["Id"]."' AND Tipo='A' AND IdPA='".$RResAco["Id"]."' LIMIT 1"));
            
            $cadena.='          <option value="'.$RResCam["Id"].'"';if($RResCam["Id"]==$ResCamaResA["Cama"]){$cadena.=' selected';}$cadena.='>'.$RResCam["Habitacion"].' - '.$RResCam["Cama"].'</option>';
        }
        $cadena.='      </select> 
                    </div>';
    }

    $cadena.='<div class="c100"><label class="l_form">&nbsp;</label></div>';

    //reservación en blanco
    $ResCamV=mysqli_query($conn, "SELECT * FROM reservaciones WHERE IdReservacion='".$ResRes["Id"]."' AND Tipo='A' AND IdPA='0' AND Fecha='".$ResRes["Fecha"]."' ORDER BY Id ASC");
    $cadena.='<input type="hidden" name="r_blanco" id="r_blanco" value="'.mysqli_num_rows($ResCamV).'">';
    $J=1;
    while($RResCamV=mysqli_fetch_array($ResCamV))
    {
        $cadena.='<div class="c60">
                    <select name="acom_'.$J.'" id="acom_'.$J.'">
                        <option value="0">Selecciona</option>';
        $ResAcom=mysqli_query($conn, "SELECT Id, Nombre FROM acompannantes WHERE IdPaciente='".$ResPac["Id"]."' ORDER BY Nombre ASC");
        while($RResAcom=mysqli_fetch_array($ResAcom))
        {
            $cadena.=' <option value="'.$RResAcom["Id"].'">'.$RResAcom["Nombre"].'</option>';
        }
        $cadena.=' </select>
                </div> 
                <div class="c30">
                    <select name="cama_aco_'.$J.'" id="cama_aco_'.$J.'">';
        $ResCamasJ=mysqli_query($conn, "SELECT c.Id, h.Habitacion, c.Cama FROM camas AS c 
                                        INNER JOIN habitaciones as h ON c.Habitacion = h.Id 
                                        ORDER BY h.Habitacion ASC, c.Cama ASC");
        while($RResCamJ=mysqli_fetch_array($ResCamasJ))
        {
            $ResCamaResA=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM reservaciones WHERE IdReservacion='".$ResRes["Id"]."' AND Tipo='A' AND IdPA='0' LIMIT 1"));

            $cadena.='  <option value="'.$RResCamJ["Id"].'"';if($RResCamJ["Id"]==$ResCamaResA["Cama"]){$cadena.=' selected';}$cadena.='>'.$RResCamJ["Habitacion"].' - '.$RResCamJ["Cama"].'</option>';
        }
        $cadena.='  </select> 
                </div>';

        $J++;

    }
    
    


$cadena.='      <div class="c100"> 
                    <input type="hidden" name="idreservacion" id="idreservacion" value="'.$ResRes["Id"].'"> 
                    <input type="hidden" name="hacer" id="hacer" value="edreservacion">
                    <input type="submit" name="botadreserv" id="botadreserv" value="Actualizar>>" onclick="cerrarmodal()">
                </div>
            </form>
        </div>';

echo $cadena;

?>
<script>
$("#fedreservacion").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fedreservacion"));

	$.ajax({
		url: "reservaciones/reservaciones.php",
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