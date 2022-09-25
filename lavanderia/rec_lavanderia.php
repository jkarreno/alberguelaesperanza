<?php
date_default_timezone_set('America/Mexico_City');
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

$mensaje='';

//acciones
if(isset($_POST["hacer"]))
{
    $ResLavanderia=mysqli_query($conn, "SELECT * FROM lavanderia ORDER BY Id ASC");
    //salida de lavanderia
    if($_POST["hacer"]=='out_lavanderia')
	{
        $r=explode('_', $_POST["recibio"]);

        while($RResLav=mysqli_fetch_array($ResLavanderia))
        {
            $ResCantL=mysqli_fetch_array(mysqli_query($conn, "SELECT Balance FROM lavanderia_inventario WHERE IdPrenda='".$RResLav["Id"]."' ORDER BY Id DESC, Fecha DESC LIMIT 1"));

            $Balance=$ResCantL["Balance"]-$_POST["prenda_".$RResLav["Id"]];

            mysqli_query($conn, "INSERT INTO lavanderia_inventario (Fecha, IdReservacion, IdPA, PA, ES, IdPrenda, Cantidad, Balance, Usuario)
                                                            VALUES ('".$_POST["fecha_entrega"]." ".date("H:m:s")."', '".$_POST["idreservacion"]."', '".$r[1]."',
                                                                    '".$r[0]."', 'S', '".$RResLav["Id"]."', '".$_POST["prenda_".$RResLav["Id"]]."', '".$Balance."', 
                                                                    '".$_SESSION["Id"]."')") or die(mysqli_error($conn));
        }

        mysqli_query($conn, "INSERT INTO lavanderia_observaciones (IdReservacion, IdPa, PA, Observaciones, ES, Usuario)
                                                            Values ('".$_POST["idreservacion"]."', '".$r[1]."', '".$r[0]."', '".$_POST["observaciones"]."', 'S', '".$_SESSION["Id"]."')");

        $cadena='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Registro Exitoso</div>';

        //bitacora
        mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '11', '".json_encode($_POST)."')");
    }
    //entrada de lavanderia
    if($_POST["hacer"]=='in_lavanderia')
	{
        $e=explode('_', $_POST["entrego"]);
        while($RResLav=mysqli_fetch_array($ResLavanderia))
        {
            $ResCantL=mysqli_fetch_array(mysqli_query($conn, "SELECT Balance FROM lavanderia_inventario WHERE IdPrenda='".$RResLav["Id"]."' ORDER BY Id DESC, Fecha DESC LIMIT 1"));

            if($_POST["tipo"]=='E'){$Balance=$ResCantL["Balance"]+$_POST["prenda_".$RResLav["Id"]];}
            elseif($_POST["tipo"]=='C'){$Balance=$ResCantL["Balance"];}

            mysqli_query($conn, "INSERT INTO lavanderia_inventario (Fecha, IdReservacion, IdPA, PA, ES, IdPrenda, Cantidad, Balance, Usuario)
                                                            VALUES ('".$_POST["fecha_devolucion"]." ".date("H:m:s")."', '".$_POST["idreservacion"]."', '".$e[1]."',
                                                                    '".$e[0]."', '".$_POST["tipo"]."', '".$RResLav["Id"]."', '".$_POST["prenda_".$RResLav["Id"]]."', 
                                                                    '".$Balance."', '".$_SESSION["Id"]."')") or die(mysqli_error($conn));
        }
        mysqli_query($conn, "INSERT INTO lavanderia_observaciones (IdReservacion, IdPa, PA, Observaciones, ES, Usuario)
                                                            Values ('".$_POST["idreservacion"]."', '".$e[1]."', '".$e[0]."', '".$_POST["observaciones"]."', '".$_POST["tipo"]."', '".$_SESSION["Id"]."')") or die (mysqli_error($conn));

        $cadena='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Registro Exitoso</div>';

        //bitacora
        mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '12', '".json_encode($_POST)."')");
    }
    //editar salida de lavanderia
    if($_POST["hacer"]=='edit_out_lavanderia')
	{
        $r=explode('_', $_POST["recibio"]);

        while($RResLav=mysqli_fetch_array($ResLavanderia))
        {
            $ResCantL=mysqli_fetch_array(mysqli_query($conn, "SELECT Cantidad, Balance FROM lavanderia_inventario WHERE IdPrenda='".$RResLav["Id"]."' AND IdReservacion='".$_POST["idreservacion"]."' AND ES='S' LIMIT 1"));

            $Balance=$ResCantL["Balance"]+$ResCantL["Cantidad"];

            $Balance=$Balance-$_POST["prenda_".$RResLav["Id"]];

            mysqli_query($conn, "UPDATE lavanderia_inventario SET Fecha='".$_POST["fecha_entrega"]." ".date("H:m:s")."', 
                                                                    IdPA='".$r[1]."',
                                                                    PA='".$r[0]."',
                                                                    Cantidad='".$_POST["prenda_".$RResLav["Id"]]."',
                                                                    Balance='".$Balance."',
                                                                    Usuario='".$_SESSION["Id"]."'
                                                                WHERE IdReservacion='".$_POST["idreservacion"]."'
                                                                AND IdPrenda='".$RResLav["Id"]."' 
                                                                AND ES='S'") or die(mysqli_error($conn));
        }

        mysqli_query($conn, "UPDATE lavanderia_observaciones SET IdPa='".$r[1]."',
                                                                PA='".$r[0]."',
                                                                Usuario='".$_SESSION["Id"]."', 
                                                                Observaciones='".$_POST["observaciones"]."'
                                                            WHERE IdReservacion='".$_POST["idreservacion"]."'
                                                            AND ES='S'");

        $cadena='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Registro Exitoso</div>';

        //bitacora
        mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '13', '".json_encode($_POST)."')");
    }
    //editar entrada a lavanderia
    if($_POST["hacer"]=='in_lavanderia')
	{
        $e=explode('_', $_POST["entrego"]);

        while($RResLav=mysqli_fetch_array($ResLavanderia))
        {
            mysqli_query($conn, "UPDATE lavanderia_inventario SET Fecha='".$_POST["fecha_devolucion"]." ".date("H:m:s")."', 
                                                                    IdPA='".$e[1]."', 
                                                                    PA='".$e[0]."', 
                                                                    Cantidad='".$_POST["prenda_".$RResLav["Id"]]."', 
                                                                    Usuario='".$_SESSION["Id"]."'
                                                                WHERE IdReservacion='".$_POST["idreservacion"]."'
                                                                AND IdPrenda='".$RResLav["Id"]."' 
                                                                AND ES='".$_POST["tipo"]."'") or die(mysqli_error($conn));
        }
        mysqli_query($conn, "UPDATE lavanderia_observaciones SET IdPa='".$e[1]."', 
                                                                PA='".$e[0]."',
                                                                Observaciones='".$_POST["observaciones"]."',
                                                                Usuario='".$_SESSION["Id"]."' 
                                                            WHERE IdReservacion='".$_POST["idreservacion"]."' 
                                                            AND ES='".$_POST["tipo"]."'") or die (mysqli_error($conn));

        $cadena='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Registro Exitoso</div>';

        //bitacora
        mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '14', '".json_encode($_POST)."')");
    }
    //
}
else
{
    $ResRes=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM reservacion WHERE Id='".$_POST["idreservacion"]."' LIMIT 1"));

    //ver si hay registros
    $rs=mysqli_query($conn, "SELECT * FROM lavanderia_observaciones WHERE IdReservacion='".$_POST["idreservacion"]."' AND ES='S'");
    $re=mysqli_query($conn, "SELECT * FROM lavanderia_observaciones WHERE IdReservacion='".$_POST["idreservacion"]."' AND ES='E'");
    $regs=mysqli_num_rows($rs);
    $rege=mysqli_num_rows($re);

    $ResLavanderia=mysqli_query($conn, "SELECT * FROM lavanderia ORDER BY Id ASC");

    $ResSLav=mysqli_query($conn, "SELECT Fecha FROM lavanderia_inventario WHERE IdReservacion='".$_POST["idreservacion"]."' AND ES='S' ORDER BY Id LIMIT 1");
    if(mysqli_num_rows($ResSLav)==1)
    {
        $RResSLav=mysqli_fetch_array($ResSLav);
        $fechaout=$RResSLav["Fecha"][0].$RResSLav["Fecha"][1].$RResSLav["Fecha"][2].$RResSLav["Fecha"][3].$RResSLav["Fecha"][4].$RResSLav["Fecha"][5].$RResSLav["Fecha"][6].$RResSLav["Fecha"][7].$RResSLav["Fecha"][8].$RResSLav["Fecha"][9];
    }
    else
    {
        $fechaout=date("Y-m-d");
    }

    $cadena=$mensaje.'<div class="c100 card">
                <h2>Entrega y recepción de lavandería</h2>
                <div class="c45 card">
                    <form name="foutlavanderia" id="foutlavanderia">
                    <label class="l_form">Fecha entrega: <input type="date" name="fecha_entrega" id="fecha_entrega" value="'.$fechaout.'"></label>';

    while($RResLav=mysqli_fetch_array($ResLavanderia))
    {
        if($regs>0)
        {
            $ResValue=mysqli_fetch_array(mysqli_query($conn, "SELECT Cantidad FROM lavanderia_inventario WHERE IdReservacion='".$_POST["idreservacion"]."' AND IdPrenda='".$RResLav["Id"]."' AND ES='S' LIMIT 1"));
            $value=$ResValue["Cantidad"];
        }
        else
        {
            $value=0;
        }
        $cadena.='<label class="l_form">'.utf8_encode($RResLav["Prenda"]).': <input type="number" name="prenda_'.$RResLav["Id"].'" id="prenda_'.$RResLav["Id"].'" value="'.$value.'"></label>';
    }

    $cadena.='      <label class="l_form">Recibio: <select name="recibio" id="recibio">
                        <option value="0">Selecciona</option>';
    $ResPac=mysqli_query($conn, "SELECT Id, Nombre, Apellidos FROM pacientes WHERE Id='".$ResRes["IdPaciente"]."'");
    $ResRS=mysqli_fetch_array($rs);
    while($RResPac=mysqli_fetch_array($ResPac))
    {
        $cadena.='      <option value="P_'.$RResPac["Id"].'"';if($ResRS["PA"]=='P' AND $ResRS["IdPA"]==$RResPac["Id"]){$cadena.=' selected';}$cadena.='>'.$RResPac["Id"].' - '.$RResPac["Nombre"].' '.$RResPac["Apellidos"].'</option>';
    }
    $ResAco=mysqli_query($conn, "SELECT Id, Nombre FROM acompannantes WHERE IdPaciente='".$ResRes["IdPaciente"]."'");
    while($RResAco=mysqli_fetch_array($ResAco))
    {
        $cadena.='      <option value="A_'.$RResAco["Id"].'"';if($ResRS["PA"]=='A' AND $ResRS["IdPA"]==$RResAco["Id"]){$cadena.=' selected';}$cadena.='>'.$RResAco["Id"].' - '.$RResAco["Nombre"].'</option>';
    }
    $cadena.='      </select>                
                    </label>
                    <label class="l_form">Observaciones: <textarea name="observaciones" id="observaciones">'.$ResRS["Observaciones"].'</textarea></label>';
    if($regs==0)
    {
        $cadena.='  <div class="c100"> 
                        <input type="hidden" name="hacer" id="hacer" value="out_lavanderia">
                        <input type="hidden" name="idreservacion" id="idreservacion" value="'.$ResRes["Id"].'">
                        '.permisos(12, '<input type="submit" name="botoutlavanderia" id="botoutlavanderia" value="Entregar>>">').'
                    </div>';
    }
    else
    {
        $cadena.='  <div class="c100"> 
                        <input type="hidden" name="hacer" id="hacer" value="edit_out_lavanderia">
                        <input type="hidden" name="idreservacion" id="idreservacion" value="'.$ResRes["Id"].'">
                        '.permisos(13, '<input type="submit" name="boteditoutlavanderia" id="boteditoutlavanderia" value="Editar>>">').'
                    </div>';
    }
    $cadena.='      </form>
                </div>
                <div class="c45 card">
                <form name="finlavanderia" id="finlavanderia">
                <label class="l_form">Tipo: <select name="tipo" id="tipo">
                    <option value="E">Devolución</option>
                    <option value="C">Cambio</option>
                </select></label>';
    $ResELav=mysqli_query($conn, "SELECT Fecha FROM lavanderia_inventario WHERE IdReservacion='".$_POST["idreservacion"]."' AND ES='E' ORDER BY Id LIMIT 1");
    if(mysqli_num_rows($ResELav)==1)
    {
        $RResELav=mysqli_fetch_array($ResELav);
        $fechain=$RResELav["Fecha"][0].$RResELav["Fecha"][1].$RResELav["Fecha"][2].$RResELav["Fecha"][3].$RResELav["Fecha"][4].$RResELav["Fecha"][5].$RResELav["Fecha"][6].$RResELav["Fecha"][7].$RResELav["Fecha"][8].$RResELav["Fecha"][9];
    }
    else
    {
        $fechain=date("Y-m-d");
    }
    $cadena.='  <label class="l_form">Fecha: <input type="date" name="fecha_devolucion" id="fecha_devolucion" value="'.$fechain.'"></label>';
    $ResLavanderia=mysqli_query($conn, "SELECT * FROM lavanderia ORDER BY Id ASC");
    while($RResLav=mysqli_fetch_array($ResLavanderia))
    {
        if($rege>0)
        {
            $ResValue=mysqli_fetch_array(mysqli_query($conn, "SELECT Cantidad FROM lavanderia_inventario WHERE IdReservacion='".$_POST["idreservacion"]."' AND IdPrenda='".$RResLav["Id"]."' AND ES='E' LIMIT 1"));
            $value=$ResValue["Cantidad"];
        }
        else
        {
            $value=0;
        }
        $cadena.='<label class="l_form">'.utf8_encode($RResLav["Prenda"]).': <input type="number" name="prenda_'.$RResLav["Id"].'" id="prenda_'.$RResLav["Id"].'" value="'.$value.'"></label>';
    }

    $cadena.='      <label class="l_form">Entrego: <select name="entrego" id="entrego">
                    <option value="0">Selecciona</option>';
    $ResPac=mysqli_query($conn, "SELECT Id, Nombre, Apellidos FROM pacientes WHERE Id='".$ResRes["IdPaciente"]."'");
    $ResRE=mysqli_fetch_array($re);
    while($RResPac=mysqli_fetch_array($ResPac))
    {
        $cadena.='      <option value="P_'.$RResPac["Id"].'"';if($ResRE["PA"]=='P' AND $ResRE["IdPA"]==$RResPac["Id"]){$cadena.=' selected';}$cadena.='>'.$RResPac["Id"].' - '.$RResPac["Nombre"].' '.$RResPac["Apellidos"].'</option>';
    }
    $ResAco=mysqli_query($conn, "SELECT Id, Nombre FROM acompannantes WHERE IdPaciente='".$ResRes["IdPaciente"]."'");
    while($RResAco=mysqli_fetch_array($ResAco))
    {
        $cadena.='      <option value="A_'.$RResAco["Id"].'"';if($ResRE["PA"]=='A' AND $ResRE["IdPA"]==$RResAco["Id"]){$cadena.=' selected';}$cadena.='>'.$RResAco["Id"].' - '.$RResAco["Nombre"].'</option>';
    }
    $cadena.='      </select>                
                </label>
                <lable class="l_form">Observaciones: <textarea name="observaciones" id="observaciones">'.$ResRE["Observaciones"].'</textarea></label>';
    if($rege==0)
    {
        $cadena.='<div class="c100"> 
                    <input type="hidden" name="hacer" id="hacer" value="in_lavanderia">
                    <input type="hidden" name="idreservacion" id="idreservacion" value="'.$ResRes["Id"].'">
                    '.permisos(14, '<input type="submit" name="botinlavanderia" id="botinlavanderia" value="Recibir>>">').'
                </div>';
    }
    else
    {
        $cadena.='<div class="c100"> 
                    <input type="hidden" name="hacer" id="hacer" value="edit_in_lavanderia">
                    <input type="hidden" name="idreservacion" id="idreservacion" value="'.$ResRes["Id"].'">
                    <input type="submit" name="boteditinlavanderia" id="boteditinlavanderia" value="Recibir>>">
                </div>';
    }
    $cadena.='  </form>
                </div>
            </div>';
}

echo $cadena;

?>
<script>
$("#foutlavanderia").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("foutlavanderia"));

	$.ajax({
		url: "lavanderia/rec_lavanderia.php",
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

$("#finlavanderia").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("finlavanderia"));

	$.ajax({
		url: "lavanderia/rec_lavanderia.php",
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