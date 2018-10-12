<?php
	// eventos.php
	session_start();
	if (isset($_SESSION['id'])){
		exec("find ./eventos -name '*.avi'",$output);
		echo json_encode($output);
	}
?>