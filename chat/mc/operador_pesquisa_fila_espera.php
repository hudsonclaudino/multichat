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

	//Gerenciamento de conversas
	include_once "class/Conversa.php";

	//Criação das instâncias das classes
	$Db_con = new ConexaoDB(ROOT);
	$Usuario = new Usuario($Db_con->conexao());
	$Campanha = new Campanha($Db_con->conexao());
	$Conversa = new Conversa($Db_con->conexao());
	$Auxiliar = new Auxiliares();

	$Retorno = new stdClass();
	$Retorno->status = 0;	

	if (!$Usuario->usuario_logado() ||
		$_SESSION["multichat_nivel"]!=2){
		$Retorno->status = 100;
		echo json_encode($Retorno);
		return;
	}

	$Conversa->lista_conversas();

	if (count($Conversa->erros)>0){
		$Retorno->status = 10;
		$Retorno->erros = $Conversa->erros;		
	} else{
		$Retorno->conversas = $Conversa->get_conversas();
	}

	echo json_encode($Retorno);
	return;
	
?>