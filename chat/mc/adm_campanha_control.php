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

	//Verifica se o usuário está logado no sistema, para evitar entradas diretas. Caso negativo, será direcionado para a tela de login
	if (!$Usuario->usuario_logado() ||
		$_SESSION["multichat_nivel"]!=1){
		header("location: logout.php");
	}
	
	$dados_campanha=array();
	$dados_campanha['campcab_id']= (isset($_POST['campanha_id']))? $_POST['campanha_id'] :0;
	$dados_campanha['campcab_nome']="";	
	$dados_campanha['campcab_mensagem']="";
	$dados_campanha['campcab_criacao_data']=trim(date("d-m-Y"));	
	$dados_campanha['campcab_criacao_usuario']=strtoupper($_SESSION['multichat_user']);
	$dados_campanha['campcab_envio_data']="PENDENTE";
	$dados_campanha['campcab_envio_usuario']="";
	$dados_campanha['campcab_status']="";
	$dados_campanha['convites']=0;
	$dados_campanha['conversas']=0;	
	$convites=array();

	if ($dados_campanha['campcab_id'] > 0 ){
		$Campanha->set_campcab_id($dados_campanha['campcab_id']);
		$Campanha->obtem_campanha_por_id();
		$dados_campanha['campcab_nome']= $Auxiliar->trata_nulo($Campanha->get_campcab_nome());
		$dados_campanha['campcab_mensagem']=$Auxiliar->trata_nulo($Campanha->get_campcab_mensagem());
		$dados_campanha['campcab_criacao_data']=date_format(date_create($Campanha->get_campcab_criacao_data()),'d-m-y');
		$dados_campanha['campcab_criacao_usuario']=$Auxiliar->trata_nulo($Campanha->get_campcab_criacao_usuario());

		if (!is_null($Campanha->get_campcab_envio_data())){
			$dados_campanha['campcab_envio_data']=date_format(date_create($Campanha->get_campcab_envio_data()),'d-m-y');
		}
		$dados_campanha['campcab_envio_usuario']=$Auxiliar->trata_nulo($Campanha->get_campcab_envio_usuario());
		$dados_campanha['campcab_status']=$Campanha->get_campcab_status();	
		$dados_campanha['convites']	=$Campanha->get_stat_convites_gerados();
		$dados_campanha['conversas'] = $Campanha->get_stat_conversas_geradas();
	}	
	
	include "adm_campanha_view.php";

?>

