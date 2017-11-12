<?php

	define("DS","/");
	//define("ROOT",$_SERVER['DOCUMENT_ROOT']);
	define("ROOT",$_SERVER['HTTP_HOST']);
	define("ROOT_FOLDER",ROOT);

	$slashpos = strripos($_SERVER['REQUEST_URI'],"/");

	if ($slashpos){
		$folder = substr($_SERVER['REQUEST_URI'], 0,$slashpos);		
	} else{
		$folder = $_SERVER['REQUEST_URI'];
	}

	define("APP_FOLDER",ROOT_FOLDER . $folder . DS);

	if ($_SERVER['HTTP_HOST']=='www.conecta-multi.com.br' ||
		$_SERVER['HTTP_HOST']=='conecta-multi.com.br'){
		$email_server="mx1.hostinger.com.br";			
		$email_username="contato@conecta-multi.com.br";
		$email_password="hud3009";
	} else {				
		$email_server="smtp.gmail.com";			
		$email_username="niltonbrisa@gmail.com";
		$email_password="testemultchat";			
	}

	define("EMAIL_SERVER",$email_server);
	define("EMAIL_USER",$email_username);
	define("EMAIL_PASSWORD",$email_password);
	define("EMAIL_DE",'Multichat');

?>