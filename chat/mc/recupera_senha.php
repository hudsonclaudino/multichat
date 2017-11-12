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


	$Usuario->set_usuario_usuario(isset($_POST['usuario'])? $_POST['usuario'] : "");

	$Usuario->recupera_senha();

	if (count($Usuario->erros)>0){
		echo "Não foi possível recuperar a senha do usuário:\n\n";
		foreach($Usuario->erros as $erro){
			echo "- " . $erro;
		}		
	} else {
		echo "Nova senha enviada para o e-mail do usuário.";
	}
?>