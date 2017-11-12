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

	//Criação das instâncias das classes
	$Db_con = new ConexaoDB(ROOT);
	$Usuario = new Usuario($Db_con->conexao());
	$Campanha = new Campanha($Db_con->conexao());
	$Auxiliar = new Auxiliares();

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

	$Retorno->erros=array();

	$Dados_form = new stdClass();
	$Dados_form->campcab_id = isset($_POST['campcab_id'])? $_POST['campcab_id'] : "0";
	$Dados_form->campdet_nome = isset($_POST['campdet_nome'])? $_POST['campdet_nome'] : "";
	$Dados_form->campdet_cpf = isset($_POST['campdet_cpf'])? $_POST['campdet_cpf'] : "";
	$Dados_form->campdet_email = isset($_POST['campdet_email'])? $_POST['campdet_email'] : "";

	$Campanha->set_campcab_id($Dados_form->campcab_id);
	$Campanha->set_campdet_cliente_nome($Dados_form->campdet_nome);
	$Campanha->set_campdet_cliente_cpf($Dados_form->campdet_cpf);
	$Campanha->set_campdet_cliente_email($Dados_form->campdet_email);

	$Campanha->valida_convite_avulso();

	if (count($Campanha->erros)> 0){
		$Retorno->status = 10;
		$Retorno->erros = $Campanha->erros;		
		$resposta = json_encode($Retorno);
		echo $resposta;
		return;
	}

	$Campanha->inclui_convite();

	if (count($Campanha->erros)> 0){
		$Retorno->status = 20;
		$Retorno->erros = $Campanha->erros;
	}

	$resposta = json_encode($Retorno);
	echo $resposta;
	return;	
	
	?>