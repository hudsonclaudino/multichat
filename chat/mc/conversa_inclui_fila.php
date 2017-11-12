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

	$conversa_cliente_nome = "";
	$conversa_cliente_email = "";
	$conversa_token ="";
	$conversa_campcab_id = 0;
	$conversa_campdet_id = 0;	
	$conversa_cliente_cpf = "";

	$Retorno = new stdClass();
	$Retorno->status = 0;
	$Retorno->conversa = 0;

	if (isset($_POST['cliente_nome'])){
		$conversa_cliente_nome = $_POST['cliente_nome'];
	}

	if (isset($_POST['cliente_email'])){
		$conversa_cliente_email = $_POST['cliente_email'];
	}

	if (isset($_POST['conversa_token'])){
		$conversa_token = $_POST['conversa_token'];
	}

	$Campanha->set_campdet_token_conversa($conversa_token);
	$Campanha->obter_conversa_por_token();

	$conversa_campcab_id = $Campanha->get_campcab_id();
	$conversa_campdet_id = $Campanha->get_campdet_id();
	$conversa_cliente_cpf = $Campanha->get_campdet_cliente_cpf();

	$Conversa->set_conversa_campcab_id($conversa_campcab_id);
	$Conversa->set_conversa_campdet_id($conversa_campdet_id);
	$Conversa->set_conversa_cliente_nome($conversa_cliente_nome);
	$Conversa->set_conversa_cliente_email($conversa_cliente_email);
	$Conversa->set_conversa_cliente_cpf($conversa_cliente_cpf);

	$Conversa->cadastra_cliente_na_fila();

	if (count($Conversa->erros)>0){
		$Retorno->erros = $Conversa->erros;
		$Retorno->status = 10;
		echo json_encode($Retorno);
		return;
	}

	$Retorno->conversa = $Conversa->get_conversa_id();
	echo json_encode($Retorno);


?>