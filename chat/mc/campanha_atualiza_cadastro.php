<?php
	
	include_once "config.php";
	//Classe para a conexão com o banco de dados	
	include_once "class/ConexaoDB.php";

	//Classe para a pesquisa de usuários do sistema / atendentes e gerentes
	include_once "class/Usuario.php";

	//Classe com funções auxiliares comuns à aplicação toda
	include_once "class/Auxiliares.php";

	//Classe para administração das campanhas
	include_once "class/Campanha.php";


	//Criação das instâncias das classes
	$Db_con = new ConexaoDB(ROOT);
	$Usuario = new Usuario($Db_con->conexao());	
	$Campanha = new Campanha($Db_con->conexao());
	$Auxiliar = new Auxiliares();

	$retorno = new stdClass();
	$retorno->status = 0;

	//Verifica se o usuário está logado no sistema, para evitar entradas diretas. Caso negativo, será direcionado para a tela de login
	if (!$Usuario->usuario_logado() ||
		$_SESSION["multichat_nivel"]!=1){
		$retorno->status = 100;
		echo json_encode($retorno);
		return;
	}

	$campcab_id= isset($_POST['campcab_id'])? $_POST['campcab_id'] :0;
	$campcab_nome = isset($_POST['campcab_nome'])? $_POST['campcab_nome']:"";
	$campcab_mensagem = isset($_POST['campcab_mensagem'])? $_POST['campcab_mensagem']: " ";

	

	$Campanha->set_campcab_id($campcab_id);
	$Campanha->set_campcab_nome($campcab_nome);
	$Campanha->set_campcab_mensagem($campcab_mensagem);

	$Campanha->valida_campanha_cab();


	if (count($Campanha->erros)>0){
		
		$retorno->status = 10;
		$retorno->erros = $Campanha->erros;
		$retorno->ultimo_id = 0;
		$retorno_json = json_encode($retorno);
		echo $retorno_json;
		return;
	}

	$Campanha->atualiza_campanha();	

	if (count($Campanha->erros)>0){
		$retorno->status = 20;
		$retorno->erros = $Campanha->erros;
		$retorno->ultimo_id = 0;
	} else {
		$retorno->status = 00;
		$retorno->erros = array();
		$retorno->ultimo_id = $Campanha->get_campcab_id();
		$retorno->nome = $Campanha->get_campcab_nome();
		$retorno->mensagem = $Campanha->get_campcab_mensagem();
	}

	$retorno_json = json_encode($retorno);
	echo $retorno_json;
	return;
	

	

?>