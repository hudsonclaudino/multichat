<?php
	include_once "config.php";
	//Classe para a conexão com o banco de dados	
	include_once "class/ConexaoDB.php";

	//Classe para a pesquisa de usuários do sistema / atendentes e gerentes
	include_once "class/Usuario.php";

	//Classe para a administração de campanhas
	include_once "class/Campanha.php";
	//Classe para o envio de emails
	include_once "class/Enviar_email.php";
	//Classe com funções auxiliares comuns à aplicação toda
	include_once "class/Auxiliares.php";

	//Gerenciamento de conversas
	include_once "class/Conversa.php";

	//Criação das instâncias das classes
	$Db_con = new ConexaoDB(ROOT);
	$Usuario = new Usuario($Db_con->conexao());
	$Campanha = new Campanha($Db_con->conexao());
	$Conversa = new Conversa($Db_con->conexao());
	$Auxiliar = new Auxiliares();

	$Retorno = new stdClass();
	$Retorno->status = 0;

	if (!isset($_POST['conversa_id'])){
		$Retorno->status = 10;
		$Retorno->erros[] = "Um ou mais parâmetros não foram informados.";
		echo json_encode($Retorno);
		return;
	}

	$emails = (isset($_POST['lista_emails']))? $_POST['lista_emails'] : "";

	$Conversa->set_conversa_id($_POST['conversa_id']);
	$Conversa->set_conversa_emails_alternativos($emails);

	$Conversa->enviar_conversa_por_email();

	if (count($Conversa->erros)>0){
		$Retorno->status = 20;
		$Retorno->erros = $Conversa->erros;
		echo json_encode($Retorno);
		return;
	}

	echo json_encode($Retorno);
	return;

?>