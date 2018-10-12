<?php
	// listado_usuarios.php
	session_start();
	if (isset($_SESSION['id'])){
		require_once("conexion.inc.php");
		$conexion = new MySQLi($h,$u,$p,$bd);
		
		$conexion->query("SET NAMES utf8");
		$sql = "SELECT * FROM usuarios ORDER BY id";
		$resultado = $conexion->query($sql);
		
		$data = array();
		$data = $resultado->fetch_all(MYSQLI_ASSOC);
		
		echo json_encode($data);
		
		$conexion->close();
	}
?>