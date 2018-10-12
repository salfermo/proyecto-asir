<?php
	// login.php
	session_start();

	require_once("conexion.inc.php");
	$c = new mysqli($h,$u,$p,$bd);

	if ($c->connect_error){
		die("La conexión falló: ".$c->connect_error);
	}

	$nombre = $_POST['nombre'];
	$clave = sha1($_POST['clave']);

	$c->query("SET NAMES utf8");
	$sql = "SELECT id FROM usuarios WHERE nombre = '".$nombre."' AND clave = '".$clave."'";

	$resultado=$c->query($sql);
	if ($resultado->num_rows > 0) {
		$fila = $resultado->fetch_assoc();
		$_SESSION['id'] = $fila['id'];
		header("Location: acceso.php");
	}else{
		header("Location: index.html");
	}
	$c->close();
?>