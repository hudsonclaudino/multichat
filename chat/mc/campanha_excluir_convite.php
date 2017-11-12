<?php
	include_once "config.php";
	//Classe para a conexão com o banco de dados	
	include_once "class/ConexaoDB.php";

	//Classe para a pesquisa de usuários do sistema / atendentes e gerentes
	include_once "class/Usuario.php"; 

	//Classe para a administração de campanhas
	include_once "class/Campanha.php";

	//Criação das instâncias das classes
	$Db_con = new ConexaoDB(ROOT);
	$Usuario = new Usuario($Db_con->conexao());
	$Campanha = new Campanha($Db_con->conexao());
	
	$Retorno = new stdClass();
	$Retorno->status = 0;

	//Verifica se o usuário está logado no sistema, para evitar entradas diretas. Caso negativo, será direcionado para a tela de login
	if (!$Usuario->usuario_logado() ||
		$_SESSION["multichat_nivel"]!=1){
		$Retorno->status = 100;
		echo json_encode($Retorno);
		return;
	}

	if (!isset($_POST['convite_id'])){
		$Retorno->status = 10;
		$Retorno->erros[] = "Um ou mais parâmetros não foram informados. Contate o administrador";
		echo json_encode($Retorno);
		return;
	}

	$Campanha->set_campdet_id($_POST['convite_id']);
	$Campanha->exclui_convite();

	if (count($Campanha->erros)>0){
		$Retorno->status = 20;
		$Retorno->erros = $Campanha->erros;
		echo json_encode($Retorno);
		return;
	}

	echo json_encode($Retorno);
	return;

?>