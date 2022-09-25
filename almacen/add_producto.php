<?php
//Inicio la sesion 
session_start();

include('../conexion.php');


$cadena='<div class="c100 card">
            <h2>Nuevo producto</h2>
            <form name="fadproducto" id="fadproducto">
                <div class="c30">
                    <label class="l_form">Nombre del producto:</label>
                    <input type="text" name="nombre" id="nombre" required>
                </div>

                <div class="c20">
                    <label class="l_form">Categoría</label>
                    <select name="categoria" id="categoria" required>';
$ResCat=mysqli_query($conn, "SELECT * FROM cat_almacenes ORDER BY Nombre ASC");
while($RResCat=mysqli_fetch_array($ResCat))
{
    $cadena.='          <option value="'.$RResCat["Id"].'">'.$RResCat["Nombre"].'</option>';
}
$cadena.='          </select>
                </div>

                <div class="c20">
                    <label class="l_form">Presentacion:</label>
                    <select name="presentacion" id="presentacion" required>
                        <option value="0">Selecciona</option>';
$ResPresentaciones=mysqli_query($conn, "SELECT * FROM presentaciones ORDER BY Nombre ASC");
while($RResPr=mysqli_fetch_array($ResPresentaciones))
{
    $cadena.='          <option value="'.$RResPr["Id"].'">'.$RResPr["Nombre"].'</option>';
}
$cadena.='         </select>
                </div>

                <div class="c20">
                    <label class="l_form">Volumen</label>
                    <input type="text" name="volumen" id="volumen">
                </div>

                <div class="c30">
                    <label class="l_form">Marca:</label>
                    <input type="text" name="marca" id="marca">
                </div>
                <div class="c30">
                    <label class="l_form">Fecha de caducidad:</label>
                    <input type="month" name="caducidad" id="caducidad">
                </div>
                <div class="c30">
                    <label class="l_form">Inventario Inicial:</label>
                    <input type="number" name="inventarioi" id="inventarioi">
                </div>

                <div class="c30">
                    <label class="l_form">Almacen</label>
                    <select name="almacen" id="almacen">';
$ResAlmacenes=mysqli_query($conn, "SELECT * FROM almacenes ORDER BY Id ASC");
while($RResA=mysqli_fetch_array($ResAlmacenes))
{
    $cadena.='          <option value="'.$RResA["Id"].'">'.$RResA["Nombre"].'</option>';
}
$cadena.='          </select>
                </div>     
                <div class="c30">
                    <label class="l_form">Origen</label>
                    <input list="origen" name="origen" id="origen">
                        <datalist id="origen">';
$ResOrigen=mysqli_query($conn, "SELECT Origen FROM productos_inventario GROUP BY Origen ORDER BY Origen ASC");
while($RResO=mysqli_fetch_array($ResOrigen))
{
    $cadena.='              <option value"'.$RResO["Origen"].'">';
}
$cadena.='              </datalist>
                </div>
                <div class="c30">
                    <label class="l_form">Observaciones:</label>
                    <input type="text" name="observaciones" id="observaciones">
                </div>

                <div class="c90 c_center">
                    <input type="hidden" name="hacer" id="hacer" value="addproducto">
				    <input type="submit" name="botadproducto" id="botadproducto" value="Agregar>>">
                </div>
            </form>
        </div>';

echo $cadena;

?>
<script>
$("#fadproducto").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fadproducto"));

	$.ajax({
		url: "almacen/almacen.php",
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