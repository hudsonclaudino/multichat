<?php
	include_once "config.php";
	//Classe para a conexão com o banco de dados	
	include_once "class/ConexaoDB.php";

	//Classe para a pesquisa de usuários do sistema / atendentes e gerentes
	include_once "class/Usuario.php";

	//Classe para a pesquisa de campanhas
	include_once "class/Campanha.php";

	//Criação das instâncias das classes
	$Db_con = new ConexaoDB(ROOT);
	$Usuario = new Usuario($Db_con->conexao());
	$Campanha = new Campanha($Db_con->conexao());

	//Verifica se o usuário está logado no sistema, para evitar entradas diretas. Caso negativo, será direcionado para a tela de login
	//Verifica se o nível de acesso do usuário é o de gerente. Caso negativo, terá o acesso cancelado e será redirecionado para a tela de login.
	if (!$Usuario->usuario_logado() ||
		$_SESSION["multichat_nivel"]!=1){
		header("location: logout.php");
	}

	//Consulta a lista de usuários online, para a montagem da tela
	$usuarios_online = $Usuario->lista_usuarios_online();

	//Consulta as últimas campanhas geradas com seus resultados
	$Campanha->lista_campanhas_dashboard();
	$ultimas_campanhas = $Campanha->campanhas;
	$numero_de_campanhas = $Campanha->campcab_quantidade;

	//Apresentação dos dados para a tela de gerentes
	include "gerente_view.php";

?>

