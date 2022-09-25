<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$cadena='<div class="c100 card">
            <h2>Nuevo municipio</h2>
            <form name="fadmunicipio" id="fadmunicipio">
                <div class="c100">
                    <label class="l_form">Estado:</label>
                    <select name="estado" id="estado">
                        <option value="">Selecciona</option>
                        <option value="01">Ciudad de México</option>
                        <option value="02">Estado de México</option>
                        <option value="03">Aguascalientes</option>
                        <option value="04">Baja California</option>
                        <option value="05">Baja California Sur</option>
                        <option value="06">Campeche</option>
                        <option value="07">Coahuila de Zaragoza</option>
                        <option value="08">Colima</option>
                        <option value="09">Chiapas</option>
                        <option value="10">Chihuahua</option>
                        <option value="11">Durango</option>
                        <option value="12">Guanajuato</option>
                        <option value="13">Guerrero</option>
                        <option value="14">Hidalgo</option>
                        <option value="15">Jalisco</option>
                        <option value="16">Michoacán de Ocampo</option>
                        <option value="17">Morelos</option>
                        <option value="18">Nayarit</option>
                        <option value="19">Nuevo León</option>
                        <option value="20">Oaxaca</option>
                        <option value="21">Puebla</option>
                        <option value="22">Querétaro</option>
                        <option value="23">Quintana Roo</option>
                        <option value="24">San Luis Potosí</option>
                        <option value="25">Sinaloa</option>
                        <option value="26">Sonora</option>
                        <option value="27">Tabasco</option>
                        <option value="28">Tamaulipas</option>
                        <option value="29">Tlaxcala</option>
                        <option value="30">Veracruz</option>
                        <option value="31">Yucatán</option>
                        <option value="32">Zacatecas</option>
                    </select>
                </div>
                <div class="c100">
                    <label class="l_form">Municipio:</label>
                    <input type="text" name="municipio" id="municipio">
                </div>
                
                <input type="hidden" name="hacer" id="hacer" value="addmunicipio">
				<input type="submit" name="botaduser" id="botaduser" value="Agregar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#fadmunicipio").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fadmunicipio"));

	$.ajax({
		url: "configuracion/municipios/municipios.php",
		type: "POST",
		dataType: "HTML",
		data: formData,
		cache: false,
		contentType: false,
		processData: false
	}).done(function(echo){
		$("#contenido2").html(echo);
	});
});
</script>