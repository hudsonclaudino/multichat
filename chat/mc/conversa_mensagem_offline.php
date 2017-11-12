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

	//Criação das instâncias das classes
	$Db_con = new ConexaoDB(ROOT);
	$Usuario = new Usuario($Db_con->conexao());
	$Campanha = new Campanha($Db_con->conexao());
	$Conversa = new Conversa($Db_con->conexao());
	$Auxiliar = new Auxiliares();

	$conversa_id = 0;
	$mensagem_offline = "";

	$Retorno = new stdClass();
	$Retorno->status = 0;	

	if (isset($_POST['conversa_id'])){
		$conversa_id = $_POST['conversa_id'];
	}
	if (isset($_POST['mensagem_offline'])){
		$mensagem_offline = $_POST['mensagem_offline'];
	}


	if ($conversa_id ==0 || $mensagem_offline == ""){
		$Retorno->status = 10;
		$Retorno->mensagem[] = "Código da mensagem e/ou texto não identificados.";
		echo_json_encode($Retorno);
		return;
	}

	$Conversa->set_conversa_id($conversa_id);
	$Conversa->set_conversa_mensagem_offline($mensagem_offline);	

	$Conversa->grava_mensagem_offline();	
	
	if (count($Conversa->erros)>0){
		$Retorno->erros = $Conversa->erros;
		$Retorno->status = 20;
		echo json_encode($Retorno);
		return;
	}

	echo json_encode($Retorno);

?>