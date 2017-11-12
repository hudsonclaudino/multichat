<?php

	session_start();

	include_once "config.php";
	include_once "class/ConexaoDB.php";
	include_once "class/Usuario.php";

	$Db_con = new ConexaoDB(ROOT);
	$Usuario = new Usuario($Db_con->conexao());

	
	$Usuario->set_usuario_usuario((isset($_POST['login-user']))? $_POST['login-user']: "");
	$Usuario->set_usuario_pw((isset($_POST['login-pw']))? $_POST['login-pw']: "");

	$nivel = $Usuario->verifica_nivel_usuario();

	echo $nivel;
?>