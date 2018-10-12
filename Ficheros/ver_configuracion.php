<?php
	// ver_configuracion.php
	session_start();
	if (isset($_SESSION['id'])){
		$output = array();
		
		exec("curl -s http://proyecto-asir.ddns.net:8080/0/config/get?query=brightness | grep brightness | cut -d' ' -f 3",$output[0]);
		exec("curl -s http://proyecto-asir.ddns.net:8080/0/config/get?query=contrast | grep contrast | cut -d' ' -f 3",$output[1]);
		exec("curl -s http://proyecto-asir.ddns.net:8080/0/config/get?query=saturation | grep saturation | cut -d' ' -f 3",$output[2]);
		
		echo json_encode($output);
	}
?>