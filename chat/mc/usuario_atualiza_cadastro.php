<?php

	//Classe para a conexão com o banco de dados	
	include_once "class/ConexaoDB.php";
	include_once "config.php";
	//Classe para a pesquisa de usuários do sistema / atendentes e gerentes
	include_once "class/Usuario.php";

	//Classe com funções auxiliares comuns à aplicação toda
	include_once "class/Auxiliares.php";

	//Classe com funções para envio de e-mail
	include_once "class/Enviar_email.php";


	//Criação das instâncias das classes
	$Db_con = new ConexaoDB(ROOT);
	$Usuario = new Usuario($Db_con->conexao());
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

	$usuario_id = isset($_POST['usuario_id'])? $_POST['usuario_id'] : "0";
	$usuario_usuario = isset($_POST['usuario_usuario'])? $_POST['usuario_usuario'] : "";
	$usuario_nome = isset($_POST['usuario_nome'])? $_POST['usuario_nome'] : "";
	$usuario_email = isset($_POST['usuario_email'])? $_POST['usuario_email'] : "";
	$usuario_nivel = isset($_POST['usuario_nivel'])? $_POST['usuario_nivel'] : "";

	//seta os atributos da classe
	if (!is_numeric($usuario_id)) $usuario_id = 0;
	$Usuario->set_usuario_id($usuario_id);
	$Usuario->set_usuario_usuario($usuario_usuario);
	$Usuario->set_usuario_email($usuario_email);
	$Usuario->set_usuario_nivel($usuario_nivel);
	$Usuario->set_usuario_nome($usuario_nome);
	
	$erros=array();
	$erros=$Usuario->consiste_atualizacao_usuario();

	$mensagem_de_erro="";

	if ($usuario_id == "0"){
		if (!isset($erros['usuario_usuario'])){
			$usuario_existe=$Usuario->verifica_existencia_usuario($usuario_usuario);
			if ($usuario_existe){
				$erros["usuario existente"] = "Usuário já cadastrado.";			
			}
		}		
	}

	if (!isset($erros['usuario_email'])){
		$email_duplicado=$Usuario->verifica_email_duplicado();
		if ($email_duplicado>0){
			$erros['email_duplicado'] = "E-mail sendo utilizado por outro usuário";
		}
	}

	if (count($erros)>0){
		$Retorno->status = 10;
		$Retorno->erros = $erros;
		echo json_encode($Retorno);
		return;
	}


	if ($usuario_id ==0){
		$atualizado=$Usuario->Inclui_usuario();	
	}else{
		$atualizado=$Usuario->Atualiza_usuario();
	}
	
	if (!$atualizado){
		$Retorno->status = 20;
		$Retorno->erros[] = "Não foi possível efetuar a atualização";
		echo json_encode($Retorno);
		return;
	}

	echo json_encode($Retorno);
	return;

?>