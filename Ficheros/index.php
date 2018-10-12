<?php
	// index.php
	$botToken = "604762758:AAHEjpc-VdJqJo3MInOf3Mmk852dkJdejl0";
	$website = "https://api.telegram.org/bot".$botToken;
	$update = file_get_contents("php://input");
	$update = json_decode($update, TRUE);

	$chatId = $update["message"]["chat"]["id"];
	$chatType = $update["message"]["chat"]["type"];
	$message = $update["message"]["text"];
	$user = $update["message"]["from"]["first_name"];

	if ($chatId == "-302786092"){
		switch($message){
			case '/saludar@ProAsir_bot':
				$response = "Hola ".$user;
				sendMessage($chatId,$response);
				break;
			case '/capturar@ProAsir_bot':
				sendPhoto($chatId);
				break;
		}
	}

	function sendMessage($chatId,$response){
		$url = $GLOBALS[website].'/sendMessage?chat_id='.$chatId.'&parse_mode=HTML&text='.urlencode($response);
		file_get_contents($url);
	}

	function sendPhoto($chatId){
		$caption = "Captura";
		echo exec("curl http://proyecto-asir.ddns.net:8080/0/action/snapshot > /dev/null");
		$output = exec("ls /var/www/html/eventos/*.jpg -t | head -1 | grep 'lastsnap' -v | rev | cut -d '/' -f1 | rev");
		$photo = "http://proyecto-asir.ddns.net/eventos/".$output;

		$url = $GLOBALS[website]."/sendPhoto?chat_id=".$chatId."&photo=".$photo."&caption=".$caption;

		file_get_contents($url);
	}

?>
