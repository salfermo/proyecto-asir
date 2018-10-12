<?php
	// publicar_imagen.php
	session_start();
	if (isset($_SESSION['id'])){
		$ruta = $_POST["ruta"];
		$nueva_ruta = $_POST["nueva_ruta"];
		exec("mv $ruta $nueva_ruta");
	}
?>