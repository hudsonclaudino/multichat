<?php
	include_once "config.php";
	//Classe para a conexão com o banco de dados	
	include_once "class/ConexaoDB.php";

	//Classe para a pesquisa de usuários do sistema / atendentes e gerentes
	include_once "class/Usuario.php";

	//Classe com funções auxiliares comuns à aplicação toda
	include_once "class/Auxiliares.php";

	//Criação das instâncias das classes
	$Db_con = new ConexaoDB(ROOT);
	$Usuario = new Usuario($Db_con->conexao());
	$Auxiliar = new Auxiliares();

	//Verifica se o usuário está logado no sistema, para evitar entradas diretas. Caso negativo, será direcionado para a tela de login
	if (!$Usuario->usuario_logado()){
		header("location: logout.php");
	}

	//Verifica se o nível de acesso do usuário é o de gerente. Caso negativo, terá o acesso cancelado e será redirecionado para a tela de login.
	if ($_SESSION["multichat_nivel"]!=1){		
		header("location: logout");
	}

	$dados_usuario=array();
	$dados_usuario['usuario_id']=0;
	$dados_usuario['usuario_usuario']="";
	$dados_usuario['usuario_nome']="";
	$dados_usuario['usuario_email']="";
	$dados_usuario['usuario_nivel']=0;

	if (isset($_GET['id'])){
		$Usuario->set_usuario_id($_GET['id']);
		$Usuario->consulta_usuario_por_chave("id");
		$dados_usuario['usuario_id'] = $Usuario->get_usuario_id();
		$dados_usuario['usuario_usuario'] = $Usuario->get_usuario_usuario();
		$dados_usuario['usuario_nome'] = $Usuario->get_usuario_nome();
		$dados_usuario['usuario_email'] = $Usuario->get_usuario_email();
		$dados_usuario['usuario_nivel'] = $Usuario->get_usuario_nivel();
	}

	include "adm_usuario_view.php";

?>

