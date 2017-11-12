<?php
	include_once "config.php";
	//Classe para a conexão com o banco de dados	
	include_once "class/ConexaoDB.php";

	//Classe para a pesquisa de usuários do sistema / atendentes e gerentes
	include_once "class/Usuario.php"; 

	//Classe para a administração de campanhas
	include_once "class/Campanha.php";

	//Classe para o envio de emails
	include_once "class/Enviar_email.php";

	//Classe com funções auxiliares comuns à aplicação toda
	include_once "class/Auxiliares.php";

	//Criação das instâncias das classes
	$Db_con = new ConexaoDB(ROOT);
	$Usuario = new Usuario($Db_con->conexao());
	$Campanha = new Campanha($Db_con->conexao());
	$Auxiliar = new Auxiliares();

	$resultado = new stdClass();
	$resultado->status = 0;

	//Verifica se o usuário está logado no sistema, para evitar entradas diretas. Caso negativo, será direcionado para a tela de login
	//Verifica se o nível de acesso do usuário é o de gerente. Caso negativo, terá o acesso cancelado e será redirecionado para a tela de login.
	if (!$Usuario->usuario_logado() ||
		$_SESSION["multichat_nivel"]!=1){
		$resultado->status = 100;
		echo json_encode($resultado);
		return;
	}

	//buscar id da campanha
	$resultado->campanha_id = (isset($_POST['campanha_id']))? $_POST['campanha_id'] : 0;
	
	if ($resultado->campanha_id ==0){
		$resultado->status = 10;
		$resultado->erros[]="Código da campanha não informado";
		$resposta = json_enconde($resultado);
		echo $resposta;
		return;
	}
	
	$Campanha->set_campcab_id($resultado->campanha_id);
	$Campanha->enviar_emails_campanha();

	if (count($Campanha->erros)>0){
		$resultado->status = 20;
		$resultado->erros = $Campanha->erros;
	}

	$resposta = json_encode($resultado);
	echo $resposta;
	return;		

?>