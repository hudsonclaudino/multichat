<?php 
	//Classe para a conexão com o banco de dados	
	include_once "class/ConexaoDB.php";
	include_once "config.php";
	//Classe para a pesquisa de usuários do sistema / atendentes e gerentes
	include_once "class/Usuario.php";

	//Classe para a administração de campanhas
	include_once "class/Campanha.php";

	//Classe com funções auxiliares comuns à aplicação toda
	include_once "class/Auxiliares.php";

	//Gerenciamento de conversas
	include_once "class/Conversa.php";
	include_once "class/ConsisteFormataString.php";

	//Criação das instâncias das classes
	$Db_con = new ConexaoDB(ROOT);
	$Usuario = new Usuario($Db_con->conexao());
	$Campanha = new Campanha($Db_con->conexao());
	$Conversa = new Conversa($Db_con->conexao());
	$Auxiliar = new Auxiliares();

	$Retorno = new stdClass();
	$Retorno->status =0;

	if (!$Usuario->usuario_logado() ||
		$_SESSION["multichat_nivel"]!=2){
		$Retorno->status = 100;
		echo json_encode($Retorno);
		return;	
	}

	//Indica o usuário que solicitou a chamada para a próxima conversa
	$Conversa->set_conversa_usuario($_SESSION['multichat_user']);

	//Verifica qual a conversa mais antiga na fila
	$Conversa->verifica_proxima_conversa();

	if (count($Conversa->erros)>0){
		$Retorno->status = 10;
		$Retorno->erros = $Conversa->erros;
		echo json_encode($Retorno);
		return;
	}

	//Atualiza a tabela de conversas para indicar o usuário que está efetuando o atendimento, e marca a conversa com status de iniciada
	$Conversa->set_conversa_usuario($_SESSION['multichat_user']);
	$Conversa->inicia_conversa();

	if (count($Conversa->erros)>0){
		$Retorno->status = 20;
		$Retorno->erros = $Conversa->erros;
		echo json_encode($Retorno);
		return;
	}

	$Retorno->conversa_id = $Conversa->get_conversa_id();
	$Retorno->cliente_nome = $Conversa->get_conversa_cliente_nome();
	$Retorno->cliente_cpf = ConsisteFormataString::formata_cpf($Conversa->get_conversa_cliente_cpf());
	
	echo json_encode($Retorno);
	return;
?>