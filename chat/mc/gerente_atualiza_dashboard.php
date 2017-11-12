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
	if ($_SESSION["multichat_nivel"]!=1){
		$Usuario->elimina_login();
		$Retorno->status = 100;
		echo json_encode($Retorno);
		return;	
	}

	//verifica se os parâmetros necessários foi informado
	if (!isset($_POST['dash_data_inicial']) ||
		!isset($_POST['dash_data_final'])){
		$Retorno->status = 10;
		$Retorno->erros[] = "Parâmetros de pesquisa não informado, entre em contato com o administrador";
		echo json_encode($Retorno);
		return;
	}

	$Conversa->set_stat_data_inicial($_POST['dash_data_inicial']);
	$Conversa->set_stat_data_final($_POST['dash_data_final']);
	$Conversa->carrega_estatisticas();

	if (count($Conversa->erros)>0){
		$Retorno->erros = $Conversa->erros;
		$Retorno->status = 20;
		echo json_encode($Retorno);
		return;
	}

	$Retorno->stat_total_conversas = $Conversa->get_stat_total_conversas();
	$Retorno->stat_conversas_na_fila = $Conversa->get_stat_conversas_na_fila();
	$Retorno->stat_conversas_em_atendimento = $Conversa->get_stat_conversas_em_atendimento();
	$Retorno->stat_conversas_finalizadas = $Conversa->get_stat_conversas_finalizadas();
	$Retorno->stat_conversas_abandonadas = $Conversa->get_stat_conversas_abandonadas();
	$Retorno->stat_conversas_mensagem_offline = $Conversa->get_stat_conversas_mensagem_offline();	
	$Retorno->stat_conversas_media_espera = $Conversa->get_stat_conversas_media_espera();
	$Retorno->stat_fila_mais_antiga = $Conversa->get_stat_fila_mais_antiga();	

	$Usuario->set_stat_ultimo_acesso_inicial($_POST['dash_data_inicial']);
	$Usuario->set_stat_ultimo_acesso_final($_POST['dash_data_final']);

	$Usuario->lista_usuarios_dashboad_gerente();

	if (count($Usuario->erros)>0){
		$Retorno->erros = $Usuario->erros;
		$Retorno->status = 30;
		echo json_encode($Retorno);
		return;
	}

	$usuarios_retornados = $Usuario->get_usuarios();
	$Retorno->stat_operadores = array();

	foreach($usuarios_retornados as $detalhe){
		$linha = array();
		$linha[0] = $detalhe['usuario_nome'];
		$linha[1] = $detalhe['usuario_timestamp'];
		$linha[2] = $detalhe['usuario_status'];
		$linha[3] = $detalhe['usuario_conversas'];
		$Retorno->stat_operadores[] = $linha;

	}

	$Campanha->set_stat_data_inicial($_POST['dash_data_inicial']);
	$Campanha->set_stat_data_final($_POST['dash_data_final']);
	$Campanha->carrega_estatisticas_dashboard_gerente();

	if (count($Campanha->erros)>0){
		$Retorno->erros = $Campanha->erros;
		$Retorno->status = 40;
		echo json_encode($Retorno);
		return;
	}

	$Retorno->campanhas_total = $Campanha->get_stat_novas_campanhas();
	$Retorno->convites_gerados = $Campanha->get_stat_convites_gerados();
	$Retorno->emails_enviados = $Campanha->get_stat_emails_enviados();
	$Retorno->clientes_atingidos = $Campanha->get_stat_conversas_geradas();

	echo json_encode($Retorno);
	return;

?>