<?php
	session_start();
	include_once "config.php";
	//Classe para a conexão com o banco de dados	
	include_once "class/ConexaoDB.php";

	//Classe para a pesquisa de usuários do sistema / atendentes e gerentes
	include_once "class/Usuario.php";

	//Classe para administração das campanhas
	include_once "class/Campanha.php";

	include_once "campanhas_view_funcoes.php";


	//Criação das instâncias das classes
	$Db_con = new ConexaoDB(ROOT);
	$Usuario = new Usuario($Db_con->conexao());	
	$Campanha = new Campanha($Db_con->conexao());	

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

	if (!isset($_GET['campanha'])){
		$Retorno->status = 10;
		$Retorno->erros=array();
		$Retorno->erros[]="Identificador da campanha não informado";
		$resposta = json_encode($Retorno);
		echo $resposta;
		return;
	}

	$Campanha->set_campcab_id($_GET['campanha']);
	$Campanha->carregar_convites();

	if (count($Campanha->erros)> 0){
		$Retorno->status = 20;
		$Retorno->erros = $Campanha->erros;
		$resposta = json_encode($Retorno);	
		echo $resposta;
		return;
	}
 
	ob_start();
	montar_tabela_convites($Campanha->convites);	
	$Retorno->convites = ob_get_contents();
	ob_end_clean();
	
	echo json_encode($Retorno);
	return;

?>