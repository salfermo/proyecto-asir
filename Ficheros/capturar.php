<?php
	// capturar.php
	session_start();
	if (isset($_SESSION['id'])){
		echo exec("curl http://proyecto-asir.ddns.net:8080/0/action/snapshot > /dev/null");
		$cadena = "cp ./eventos/lastsnap.jpg ./usuario".$_SESSION['id']."/".$_POST['captura']."/".$_SESSION['id']."-".date("Ymdhis").".jpg";
		echo exec($cadena);
	}else{
		echo "Usted no está autorizado para ver esta página.";
	}
?>