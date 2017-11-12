<?php
	session_start();
	
	header("content-type: text/html; charset: utf-8");
	include_once "config.php";
	include_once "class/ConexaoDB.php";
	include_once "class/Usuario.php";

	$Db_con = new ConexaoDB(ROOT);
	$Usuario = new Usuario($Db_con->conexao());

	if (!$Usuario->usuario_logado()){
		include "inicio.php";
	} else{
		if ($_SESSION["multichat_nivel"]==1){
			include "gerente.php";
		} else{
			include "operador_dashboard.php";
		}
	}


