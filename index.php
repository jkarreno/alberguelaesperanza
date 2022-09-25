<html>
	<head>
		<title>Administración</title>
		
		<link rel="stylesheet" href="estilos/estilos_index.css">
		<link rel="stylesheet" href="font_awesome/css/font-awesome.min.css">
		<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
		<link rel="stylesheet" href="estilos/styles.css">
		
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maxmum-scale=1.0, minimum-scale=1.0">
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	</head>
	<body>
		<div class="centrado_index fondo">
			<form name="flogin" id="flogin" method="POST" action="validausuario.php">
			<div class="tit_login">
				BIENVENIDO
			</div>
			<div class="user_log">
				<i class="fa fa-user"></i>&nbsp;&nbsp;<input type="text" name="user" id="user" placeholder="Usuario">
			</div>
			<div class="user_pass">
				<i class="fa fa-key"></i>&nbsp;&nbsp;<input type="password" name="pass" id="pass" placeholder="Contraseña">
			</div>
			<div class="boton_ingresar">
				<input type="submit" name="botingresar" id="botingresar" value="Ingresar" class="boton">
			</div>
			</form>
		</div>
		<div class="power">
			<img src="images/gc.png" border="0" width="100">
		</div>
	</body>
</html>