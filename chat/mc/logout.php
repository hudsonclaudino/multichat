<?php
	session_start();

	include_once "config.php";
	include_once "class/ConexaoDB.php";
	include_once "class/Usuario.php";

	$Db_con = new ConexaoDB(ROOT);
	$Usuario = new Usuario($Db_con->conexao());

	$Usuario->elimina_login();

	header('location: index.php');

	

?>