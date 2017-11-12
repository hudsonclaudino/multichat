<?php
	session_start();
	include_once "config.php";
	//Classe para a conexão com o banco de dados	
	include_once "class/ConexaoDB.php";
	//Classe para a pesquisa de usuários do sistema / atendentes e gerentes
	include_once "class/Usuario.php";
	//Classe para administração das campanhas
	include_once "class/Campanha.php";
	//Classe para administração de conversas
	include_once "class/Conversa.php";

	//Criação das instâncias das classes
	$Db_con = new ConexaoDB(ROOT);
	$Usuario = new Usuario($Db_con->conexao());	
	$Campanha = new Campanha($Db_con->conexao());	
	$Conversa = new Conversa($Db_con->conexao());

	//Retorno da chamada
	$Retorno = new stdClass();
	$Retorno->status = 0;

	//Verifica se o usuário está logado no sistema, para evitar entradas diretas. Caso negativo, será direcionado para a tela de login
	if (!$Usuario->usuario_logado()){
		$Retorno->status = 100;
		echo json_encode($Retorno);
		return;
	}

	//Verifica se o nível de acesso do usuário é o de gerente. Caso negativo, terá o acesso cancelado e será redirecionado para a tela de login.
	if ($_SESSION["multichat_nivel"]!=1){
		$Usuario->elimina_login();
		$Retorno->status = 100;
		echo json_encode($Retorno);
		return;	
	}

	if (!isset($_POST['conversa_pesquisa'])){
		$Retorno->status = 10;
		$Retorno->erros[] = "Parâmetro de pesquisa não informado, entre em contato com o administrador";
		echo json_encode($Retorno);
		return;
	}

	$Conversa->set_conversa_pesquisa($_POST['conversa_pesquisa']);
	$Conversa->lista_conversas_por_argumento();

	if (count($Conversa->erros)>0){
		$Retorno->status = 20;
		$Retorno->erros = $Conversa->erros;
		echo json_encode($Retorno);
		return;
	}

	$Retorno->Conversas = $Conversa->get_conversas();
	echo json_encode($Retorno);
	return;

?>