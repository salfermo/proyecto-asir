<?php
	// listado_imagenes.php
	session_start();
	if (isset($_SESSION['id'])){
		if ($_POST["seleccion"] == "propias"){
			exec("find ./usuarios/usuario".$_SESSION['id']." -name '*.jpg'",$output);
		}else{
			exec("find ./usuarios/usuario*/publicas -name '*.jpg'",$output);
		}
		echo json_encode($output);
	}
?>