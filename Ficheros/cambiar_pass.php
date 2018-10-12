<?php
	// cambiar_pass.php
	session_start();
	if (isset($_SESSION['id'])){
		$id = $_POST['id'];
		
		require_once("conexion.inc.php");
		$conexion = new MySQLi($h,$u,$p,$bd);
		
		$conexion->query("SET NAMES utf8");
		
		$id = $_POST['id'];
		$pass = $_POST['pass'];
		
		$sql = "UPDATE usuarios SET clave = sha1('$pass') WHERE id = $id";
		exec("echo $sql > consola.log");
		$conexion->query($sql);

		$conexion->close();
	}
?>