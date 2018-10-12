<?php
	// enviar_evento.php
	$botToken = "604762758:AAHEjpc-VdJqJo3MInOf3Mmk852dkJdejl0";
	$website = "https://api.telegram.org/bot".$botToken;
	$chatId = "-302786092";

	$caption = "Evento";
	$output = exec("ls /var/www/html/eventos/*.jpg -t | head -1 | grep 'lastsnap' -v | rev | cut -d '/' -f1 | rev");
	$photo = "http://proyecto-asir.ddns.net/eventos/".$output;
	
	$url = $GLOBALS[website]."/sendPhoto?chat_id=".$chatId."&photo=".$photo."&caption=".$caption;

	file_get_contents($url);
?>