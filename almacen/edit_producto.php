<?php
//Inicio la sesion 
session_start();

include('../conexion.php');

$ResProd=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM productos WHERE Id='".$_POST["producto"]."' LIMIT 1"));


$cadena='<div class="c100 card">
            <h2>Editar producto</h2>
            <form name="feditproducto" id="feditproducto">
                <div class="c30">
                    <label class="l_form">Nombre del producto:</label>
                    <input type="text" name="nombre" id="nombre" value="'.$ResProd["Nombre"].'" required>
                </div>
                <div class="c20">
                    <label class="l_form">Categoría</label>
                    <select name="categoria" id="categoria" required>';
$ResCat=mysqli_query($conn, "SELECT * FROM cat_almacenes ORDER BY Nombre ASC");
while($RResCat=mysqli_fetch_array($ResCat))
{
    $cadena.='          <option value="'.$RResCat["Id"].'"';if($RResCat["Id"]==$ResProd["Categoria"]){$cadena.=' selected';}$cadena.='>'.$RResCat["Nombre"].'</option>';
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
$cadena.='              <option value="'.$RResPr["Id"].'"';if($RResPr["Id"]==$ResProd["Presentacion"]){$cadena.=' selected';}$cadena.='>'.$RResPr["Nombre"].'</option>';
}
$cadena.='         </select>
                </div>
                <div class="c20">
                    <label class="l_form">Volumen:</label>
                    <Input type="texto" name="volumen" id="volumen" value="'.$ResProd["Volumen"].'">
                </div>

                <div class="c30">
                    <label class="l_form">Marca:</label>
                    <input type="text" name="marca" id="marca" value="'.$ResProd["Marca"].'">
                </div>
                <div class="c60">
                    <label class="l_form">Observaciones:</label>
                    <input type="text" name="observaciones" id="observaciones" value="'.$ResProd["Observaciones"].'">
                </div>

                <div class="c90 c_center">
                    <input type="hidden" name="hacer" id="hacer" value="editproducto">
                    <input type="hidden" name="producto" id="producto" value="'.$ResProd["Id"].'">
				    <input type="submit" name="boteditproducto" id="boteditproducto" value="Editar>>">
                </div>
            </form>
        </div>';

echo $cadena;

?>
<script>
$("#feditproducto").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("feditproducto"));

	$.ajax({
		url: "almacen/producto.php",
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