<?php
	include_once "config.php";
	//Classe para a conexão com o banco de dados	
	include_once "class/ConexaoDB.php";

	//Classe para a pesquisa de usuários do sistema / atendentes e gerentes
	include_once "class/Usuario.php";

	//Classe para a administração de campanhas
	include_once "class/Campanha.php";

	//Classe com funções auxiliares comuns à aplicação toda
	include_once "class/Auxiliares.php";

	//Gerenciamento de conversas
	include_once "class/Conversa.php";
	include_once "class/Enviar_email.php";

	//Criação das instâncias das classes
	$Db_con = new ConexaoDB(ROOT);
	$Usuario = new Usuario($Db_con->conexao());
	$Campanha = new Campanha($Db_con->conexao());
	$Conversa = new Conversa($Db_con->conexao());
	$Auxiliar = new Auxiliares();

	$conversa_id = 0;	

	$Retorno = new stdClass();
	$Retorno->status = 0;	

	if (isset($_POST['conversa_id'])){
		$conversa_id = $_POST['conversa_id'];
	}

	if ($conversa_id ==0){
		$Retorno->status = 10;
		$Retorno->mensagem[] = "Código da mensagem não identificado.";
		echo json_encode($Retorno);
		return;
	}

	$Conversa->set_conversa_id($conversa_id);	

	$Conversa->encerrar_conversa();

	echo json_encode($Retorno);
	return;

?>