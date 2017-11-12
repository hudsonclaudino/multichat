<?php
	include_once "config.php";
	//Classe para a conexão com o banco de dados	
	include_once "class/ConexaoDB.php";

	//Classe para a pesquisa de usuários do sistema / atendentes e gerentes
	include_once "class/Usuario.php";

	//Classe com funções auxiliares comuns à aplicação toda
	include_once "class/Auxiliares.php";

	//Classe com funções auxiliares comuns à aplicação toda
	include_once "class/Campanha.php";

	//Criação das instâncias das classes
	$Db_con = new ConexaoDB(ROOT);
	$Usuario = new Usuario($Db_con->conexao());
	$Auxiliar = new Auxiliares();
	$Campanha = new Campanha($Db_con->conexao());

	//Verifica se o usuário está logado no sistema, para evitar entradas diretas. Caso negativo, será direcionado para a tela de login
	if (!$Usuario->usuario_logado()){
		header("location: logout.php");
	}

	//Verifica se o nível de acesso do usuário é o de gerente. Caso negativo, terá o acesso cancelado e será redirecionado para a tela de login.
	if ($_SESSION["multichat_nivel"]!=1){		
		header("location: logout.php");
	}

	//Consulta a lista de usuários, para a montagem da tela
	$Campanha->lista_campanhas();
	$campanhas = $Campanha->campanhas;

	//Apresentação dos dados para a tela de gerentes
	include "campanhas_view.php";

?>

