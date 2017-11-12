<?php
	session_start();	
	//Classe para a conexão com o banco de dados	
	include_once "class/ConexaoDB.php";
	include_once "config.php";
	//Classe para a pesquisa de usuários do sistema / atendentes e gerentes
	include_once "class/Usuario.php";

	//Classe para a administração de campanhas
	include_once "class/Campanha.php";

	//Classe para o envio de emails
	include_once "class/Enviar_email.php";

	//Classe com funções auxiliares comuns à aplicação toda
	include_once "class/Auxiliares.php";

	//Classe para a administração das conversas
	include_once "class/Conversa.php";

	include_once "class/ConsisteFormataString.php";

	//Criação das instâncias das classes
	$Db_con = new ConexaoDB(ROOT);
	$Usuario = new Usuario($Db_con->conexao());
	$Campanha = new Campanha($Db_con->conexao());
	$Auxiliar = new Auxiliares();
	$Conversa = new Conversa($Db_con->conexao());

	$Retorno = new stdClass();
	$Retorno->status = 0;

	$Usuario->set_usuario_usuario($_SESSION['multichat_user']);
	$Usuario->consulta_usuario_por_chave("usuario");

	$usuario_id = $Usuario->get_usuario_id();

	if ($usuario_id ==0){
		return;
	}

	$Retorno->online = $Usuario->get_usuario_status();

	if ($Retorno->online ==0){
		echo json_encode($Retorno);
		return;
	}

	$Conversa->set_conversa_usuario($_SESSION['multichat_user']);
	$Conversa->verifica_conversa_ativa_operador();

	if (count($Conversa->erros)>0){
		$Retorno->status = 10;
		$Retorno->erros = $Conversa->erros;
		echo json_encode($Retorno);
		return;
	}	

	$Retorno->conversa = $Conversa->get_conversa_id();
	$Retorno->nome_cliente = $Conversa->get_conversa_cliente_nome();
	$Retorno->cpf_cliente = ConsisteFormataString::formata_cpf($Conversa->get_conversa_cliente_cpf());

	echo json_encode($Retorno);
	return;
?>
