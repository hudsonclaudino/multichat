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

	$conversa_id = 0;

	$Retorno = new stdClass();
	$Retorno->status = 0;
	$Retorno->posicao_na_fila = 0;

	if (isset($_POST['conversa_id'])){
		$conversa_id = $_POST['conversa_id'];
	}

	$operadores = $Usuario->lista_usuarios_online();

	if (count($operadores) ==0 ){ //não há operadores online
		$Retorno->status = 20;
		echo json_encode($Retorno);
		return;
	}

	$Retorno->operadores = count($operadores);
	$Conversa->set_conversa_id($conversa_id);

	if ($Conversa->verifica_conversa_ativa_cliente()){
		$Retorno->status = 30;
		$Retorno->conversa_id = $Conversa->get_conversa_id();
		echo json_encode($Retorno);
		return;
	}

	$posicao_na_fila = $Conversa->verifica_quantidade_fila();
	
	if (count($Conversa->erros)>0){
		$Retorno->erros = $Conversa->erros;
		$Retorno->status = 10;
		echo json_encode($Retorno);
		return;
	}

	$Retorno->posicao_na_fila = $posicao_na_fila;
	echo json_encode($Retorno);

?>