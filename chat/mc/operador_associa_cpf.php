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
	include_once "class/ConsisteFormataString.php";

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
	if ($_SESSION["multichat_nivel"]!=2){
		$Usuario->elimina_login();
		$Retorno->status = 100;
		echo json_encode($Retorno);
		return;	
	}

	//verifica se os parâmetros necessários foi informado
	if (!isset($_POST['conversa_id']) ||
		!isset($_POST['cliente_cpf'])){
		$Retorno->status = 10;
		$Retorno->erros[] = "Parâmetros de pesquisa não informado, entre em contato com o administrador";
		echo json_encode($Retorno);
		return;
	}

	$Campanha->set_campdet_cliente_cpf($_POST['cliente_cpf']);

	if (!ConsisteFormataString::consiste_cpf($_POST['cliente_cpf'])){
		$Retorno->status = 20;
		$Retorno->erros[] = "O CPF informado é inválido.";
		$Retorno->erros[] = "CPF ORIGINAL: " . $_POST['cliente_cpf'];
		$Retorno->erros[] = "CPF PREG: " . preg_replace("/[^0-9]/","",$_POST['cliente_cpf']);
		echo json_encode($Retorno);
		return;
	}

	$Conversa->set_conversa_id($_POST['conversa_id']);
	$Conversa->obtem_conversa_cab_por_id();
	$Conversa->set_conversa_cliente_cpf($_POST['cliente_cpf']);

	//atualiza CPF na conversa
	$Conversa->associa_cpf();

	if (count($Conversa->erros)>0){
		$Retorno->erros = $Conversa->erros;
		$Retorno->status = 30;
		echo json_encode($Retorno);
		return;
	}

	//atualiza cpf no convite
	$campdet = $Conversa->get_conversa_campdet_id();

	if ($campdet > 0){
		$Campanha->set_campcab_id ($Conversa->get_conversa_campcab_id());
		$Campanha->set_campdet_id ($campdet);
		$Campanha->set_campdet_cliente_cpf($_POST['cliente_cpf']);
		$Campanha->atualiza_cpf_convite();
		if (count($Campanha->erros)>0){
			$Retorno->status = 30;
			$Retorno->erros = $Campanha->erros;
			echo json_encode($Retorno);
			return;
		}
	}

	$Retorno->cliente_cpf = ConsisteFormataString::formata_cpf($_POST['cliente_cpf']);
	echo json_encode($Retorno);
	return;

?>