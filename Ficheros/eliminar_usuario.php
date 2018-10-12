<?php
	session_start();
	if (isset($_SESSION['id'])){
		$id = $_POST['id'];
		
		require_once("conexion.inc.php");
		$conexion = new MySQLi($h,$u,$p,$bd);
		
		$conexion->query("SET NAMES utf8");

		$sql = "DELETE FROM usuarios WHERE id = $id";
		$conexion->query($sql);
		
		exec("rm -r ./usuarios/usuario".$id);

		$conexion->close();
	}
?>