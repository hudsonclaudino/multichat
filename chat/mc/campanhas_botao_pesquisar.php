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

	//Funções para a demonstração de dados na tela do gerente
	include "campanhas_view_funcoes.php";

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

	$argumento = isset($_POST['arg'])? $_POST['arg'] : "";		

	//Consulta a lista de campanhas para a montagem da tela
	$Campanha->set_campcab_status(9);
	$Campanha->lista_campanhas($argumento);
	$campanhas=$Campanha->campanhas;

	//Apresentação dos dados para a tela de campanhas
	ob_start();
	montar_tabela_campanhas($campanhas);
	$Retorno->campanhas = ob_get_contents();
	ob_end_clean();

	echo json_encode($Retorno);
	return;

?>

