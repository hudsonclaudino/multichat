<?php
	
	include_once "config.php";
	//Classe para a conexão com o banco de dados	
	include_once "class/ConexaoDB.php";

	//Classe para a pesquisa de usuários do sistema / atendentes e gerentes
	include_once "class/Usuario.php";

	//Criação das instâncias das classes
	$Db_con = new ConexaoDB(ROOT);
	$Usuario = new Usuario($Db_con->conexao());

	$Retorno = new stdClass();
	$Retorno->status = 0;

	//Verifica se o usuário está logado no sistema, para evitar entradas diretas. Caso negativo, será direcionado para a tela de login
	//Verifica se o nível de acesso do usuário é o de gerente. Caso negativo, terá o acesso cancelado e será redirecionado para a tela de login.
	if (!$Usuario->usuario_logado() ||
		$_SESSION["multichat_nivel"]!=1){
		$Retorno->status = 100;
		echo json_encode($Retorno);
		return;
	}
	
	$usuario_id = (isset($_POST['usuario_id']))? $_POST['usuario_id'] : 0;

	if ($usuario_id ==0){
		$Retorno->status = 10;
		$Retorno->erros[] = "O código do usuário não foi informado";
		echo json_encode($Retorno);
		return;
	}

	$Usuario->Exclui_usuario($usuario_id);

	echo json_encode($Retorno);
	return;
?>

