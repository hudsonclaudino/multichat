<?php	

	include_once "class/ConexaoDB.php";
	include_once "class/Usuario.php";
	include_once "config.php";
	$Db_con = new ConexaoDB(ROOT);
	$Usuario = new Usuario($Db_con->conexao());

	if (!$Usuario->usuario_logado() ||
		$_SESSION["multichat_nivel"]!=2){
		header("location: logout.php");
	}

	include_once "operador_view.php";
?>
