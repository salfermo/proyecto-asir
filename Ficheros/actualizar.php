<?php
	session_start();
	if (isset($_SESSION['id'])){
        $fn = fopen("./scripts/programacion.txt","r");
        $resultado = "";
        while(! feof($fn))  {
                $linea = fgets($fn);
                if (substr($linea,0,4) == $_POST['codigo']){
                        if ($_POST['solicitar'] == "true"){
                                $linea = substr($linea,0,-1).$_SESSION['id'].".".substr($linea,-1);
                        }else{
                                $aux = explode(".",substr($linea,5));
                                $pos = array_search($_SESSION['id'],$aux);
                                unset($aux[$pos]);
                                $aux = implode(".",$aux);
                                $linea = substr($linea,0,5).$aux;
                        }
                }
                if ($linea != "\n"){
                        $resultado .= $linea;
                }
        }

        fclose($fn);

        $salida = "echo '".$resultado."'";
        $salida .= " > ./scripts/programacion.txt";
        echo exec($salida);
	}
?>
