<?php
	// guardar_usuarios.php
	session_start();
	if (isset($_SESSION['id'])){
		$id = $_POST['id'];
		
		require_once("conexion.inc.php");
		$conexion = new MySQLi($h,$u,$p,$bd);
		$conexion->query("SET NAMES utf8");
		
		$id = $_POST['id'];
		$nombre = $_POST['nombre'];
		$correo = $_POST['correo'];
		
		if ($id == "0"){
			$sql = "INSERT INTO usuarios (nombre,correo) VALUES ('$nombre','$correo')";
			$conexion->query($sql);
			$sql = "SELECT MAX(id) AS id FROM usuarios";
			$resultado=$conexion->query($sql);
			
			if ($resultado->num_rows > 0) {
				$fila = $resultado->fetch_assoc();
				$id = $fila['id'];
				exec("mkdir ./usuario/usuario".$id);
				exec("mkdir ./usuario/usuario".$id."/privadas");
				exec("mkdir ./usuario/usuario".$id."/publicas");
			}
		}else{
			$sql = "UPDATE usuarios SET nombre = '$nombre',correo = '$correo' WHERE id = $id";
			$conexion->query($sql);
		}
		$conexion->close();
	}
?>