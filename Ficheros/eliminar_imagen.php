<?php
	// eliminar_imagen.php
	session_start();
	if (isset($_SESSION['id'])){
		$ruta = $_POST["ruta"];
		exec("rm $ruta");
	}
?>