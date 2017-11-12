<?php
	include_once "config.php";
	//Classe para a conexão com o banco de dados	
	include_once "class/ConexaoDB.php";

	//Classe para a pesquisa de usuários do sistema / atendentes e gerentes
	include_once "class/Usuario.php";

	//Criação das instâncias das classes
	$Db_con = new ConexaoDB(ROOT);
	$Usuario = new Usuario($Db_con->conexao());

	$Usuario->set_usuario_usuario((isset($_POST['usuario_usuario']))? $_POST['usuario_usuario']: "");
	$Usuario->set_usuario_pw((isset($_POST['usuario_senha_atual']))? $_POST['usuario_senha_atual']: "");
	$Usuario->set_usuario_nova_senha((isset($_POST['usuario_nova_senha']))? $_POST['usuario_nova_senha']: "");
	$Usuario->set_usuario_nova_senha_confirmacao((isset($_POST['usuario_nova_senha_confirmacao']))? $_POST['usuario_nova_senha_confirmacao']: "");

	$Usuario->alteracao_senha();

	$erros=$Usuario->erros;

	if (count($erros)>0){		
		$usuario_usuario = $Usuario->get_usuario_usuario();
		$usuario_pw = $Usuario->get_usuario_pw();
		$usuario_nova_senha = $Usuario->get_usuario_nova_senha();
		$usuario_confirmacao = $Usuario->get_usuario_nova_senha_confirmacao();
		include "altera_senha_view.php";
	} else {
		header("location: index.php");
	}
?>