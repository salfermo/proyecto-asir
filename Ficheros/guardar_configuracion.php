<?php
	// guardar_configuracion.php
	session_start();
	if (isset($_SESSION['id'])){
		$brillo = $_POST['brillo'];
		$contraste = $_POST['contraste'];
		$saturacion = $_POST['saturacion'];

		exec("curl http://proyecto-asir.ddns.net:8080/0/config/set?brightness=$brillo");
		exec("curl http://proyecto-asir.ddns.net:8080/0/config/set?contrast=$contraste");
		exec("curl http://proyecto-asir.ddns.net:8080/0/config/set?saturation=$saturacion");
		
		exec("curl http://proyecto-asir.ddns.net:8080/0/config/writeyes");
	}
?>