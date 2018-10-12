<?php
	$fichero = file("scripts/programacion.txt");
	foreach($fichero as $linea){
		echo $linea."-";
	}
?>