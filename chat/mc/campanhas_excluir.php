<?php
	include_once "config.php";

	//Classe para a conexão com o banco de dados	
	include_once "class/ConexaoDB.php";

	//Classe para a pesquisa de usuários do sistema / atendentes e gerentes
	include_once "class/Usuario.php";

	//Classe com funções auxiliares comuns à aplicação toda
	include_once "class/Campanha.php";

	//Criação das instâncias das classes
	$Db_con = new ConexaoDB(ROOT);
	$Usuario = new Usuario($Db_con->conexao());
	$Campanha = new Campanha($Db_con->conexao());

	$Retorno = new stdClass();
	$Retorno->status = 0;

	//Verifica se o usuário está logado no sistema, para evitar entradas diretas. Caso negativo, será direcionado para a tela de login
	if (!$Usuario->usuario_logado()){
		$Retorno->Status = 100;
		echo json_encode($Retorno);
		return;
	}
	
	$campanha_id = (isset($_POST['campanha_id']))? $_POST['campanha_id'] : 0;

	if ($campanha_id ==0){
		$Retorno->status = 10;
		$Retorno->erros[] = "O código da campanha não foi informado";
		echo json_encode($Retorno);
		return;
	}

	$Campanha->set_campcab_id($campanha_id);
	$Campanha->Exclui_campanha();

	if (count($Campanha->erros)>0){
		$Retorno->status = 20;
		$Retorno->erros = $Campanha->erros;
		echo json_encode($Retorno);
		return;
	}

	echo json_encode($Retorno);
	return;
?>

