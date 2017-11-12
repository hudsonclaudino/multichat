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

	//Criação das instâncias das classes
	$Db_con = new ConexaoDB(ROOT);
	$Usuario = new Usuario($Db_con->conexao());
	$Campanha = new Campanha($Db_con->conexao());
	$Auxiliar = new Auxiliares();

	$retorno = new stdClass();
	$retorno->status = 0;

	//Verifica se o usuário está logado no sistema, para evitar entradas diretas. Caso negativo, será direcionado para a tela de login
	//Verifica se o nível de acesso do usuário é o de gerente. Caso negativo, terá o acesso cancelado e será redirecionado para a tela de login.
	if (!$Usuario->usuario_logado() ||
		$_SESSION["multichat_nivel"]!=1){
		$retorno->status = 100;
		echo json_encode($retorno);
		return;
	}

	$retorno->nome_arquivo_entrada = $_FILES['arquivo']['name'];

	$nome_arquivo_entrada = $_SESSION['multichat_user'] . rand(0,99999) . ".csv";
	$nome_arquivo_saida = $_SESSION['multichat_user'] . rand(0,99999) . ".txt";	 

	move_uploaded_file($_FILES['arquivo']['tmp_name'], "files/".$nome_arquivo_entrada);

	$Campanha->nome_arquivo_entrada = "files/" . $nome_arquivo_entrada;
	$retorno->nome_arquivo_saida = $Campanha->nome_arquivo_saida = "files/" . $nome_arquivo_saida;
	$Campanha->set_campcab_id($_POST['campanha_id']);
	$Campanha->carrega_arquivo_csv();

	$resposta = json_encode($retorno);
	echo $resposta;
	return;

?>