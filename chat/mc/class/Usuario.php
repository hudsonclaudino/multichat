<?php

class Usuario{

	private $usuario;
	private $nivel;
	private $db_conn;

	private $usuario_id;
	private $usuario_usuario;
	private $usuario_nome;
	private $usuario_email;
	private $usuario_pw;
	private $usuario_timestamp;
	private $usuario_nivel;
	private $usuario_status;
	private $usuario_ultima_alteracao;
	private $usuario_quantidade;
	private $usuario_nova_senha;
	private $usuario_nova_senha_confirmacao;
	public $erros;

	private $usuarios;

	private $stat_ultimo_acesso_inicial;
	private $stat_ultimo_acesso_final;


	// Carrega a conexão do banco de dados utilizada nas pesquisas
	public function __construct($conn){
		$this->db_conn=$conn;
		return;
	}

	public function set_usuario_id($usuario_id){
		$this->usuario_id=$usuario_id;

	}

	public function set_usuario_email($usuario_email){
		$this->usuario_email = $usuario_email;
	}

	public function set_usuario_usuario($usuario_usuario){
		$this->usuario_usuario = $usuario_usuario;
	}

	public function set_usuario_nome($usuario_nome){
		$this->usuario_nome = $usuario_nome;
	}

	public function set_usuario_nivel($usuario_nivel){
		$this->usuario_nivel = $usuario_nivel;
	}

	public function set_usuario_pw($usuario_pw){
		$this->usuario_pw = $usuario_pw;
	}

	public function set_usuario_nova_senha($usuario_nova_senha){
		$this->usuario_nova_senha = $usuario_nova_senha;
	}

	public function set_usuario_nova_senha_confirmacao($usuario_nova_senha_confirmacao){
		$this->usuario_nova_senha_confirmacao = $usuario_nova_senha_confirmacao;
	}

	public function set_usuario_status($status){
		$this->usuario_status = $status;
	}
	
	public function set_stat_ultimo_acesso_inicial($data_inicial){
		$this->stat_ultimo_acesso_inicial = $data_inicial . " 00:00:00";
	}

	public function set_stat_ultimo_acesso_final($data_final){
		$this->stat_ultimo_acesso_final = $data_final . " 23:59:59";
	}
	public function get_usuario_id(){
		return $this->usuario_id;
	}

	public function get_usuario_email(){
		return $this->usuario_email;
	}

	public function get_usuario_usuario(){
		return $this->usuario_usuario;
	}

	public function get_usuario_nome(){
		return $this->usuario_nome;
	}

	public function get_usuario_nivel(){
		return $this->usuario_nivel;
	}

	public function get_usuario_pw(){
		return $this->usuario_pw;
	}

	public function get_usuario_nova_senha(){
		return $this->usuario_nova_senha;
	}

	public function get_usuario_nova_senha_confirmacao(){
		return $this->usuario_nova_senha_confirmacao;
	}

	public function get_usuario_status(){
		return $this->usuario_status;
	}

	public function get_usuarios(){
		return $this->usuarios;
	}


	//Verifica se o usuário e senha informados pelo usuário são válidos
	public function valida_acesso(){

		$login_user=$this->usuario_usuario;
		$login_pw=md5($this->usuario_pw);

		$sql = "select usuario_nivel from MULTICHAT_USUARIO where usuario_usuario = '" . $login_user . "' and usuario_pw='" . $login_pw . "'";

		$resultado=$this->db_conn->query($sql);

		if (!$resultado || mysqli_num_rows($resultado)==0){						
			return 0;
		} else {
			$nivel=mysqli_fetch_assoc($resultado)['usuario_nivel'];			
			return $nivel;
		}			
	}
	
	public function verifica_nivel_usuario(){

		$this->usuario_nivel = $this->valida_acesso();

		if ($this->usuario_nivel > 0){
			$this->grava_login($this->usuario_usuario, $this->usuario_nivel);			
		} else{
			$this->elimina_login();
		}

		return $this->usuario_nivel;		
	}

	//Verifica se o usuário já está usado através do acesso a um cookie, e renova o tempo do cookie caso positivo
	public function usuario_logado(){

		if (isset($_COOKIE["multichat_user"])){
			
			$cookie_content = explode("-",$_COOKIE["multichat_user"]);
			$this->grava_login($cookie_content[1],$cookie_content[0]);
			return true;
		} else {			
			$this->elimina_login();
			return false;
		}
	}

	//Elimina o login ativo no sistema, para obrigaro usuário a se logar novamente.
	public function elimina_login(){
		
		if (isset($_SESSION['multichat_user'])){
			$this->set_usuario_usuario($_SESSION['multichat_user']);
			$this->set_usuario_status(0);
			$this->atualiza_status_usuario();		
			unset($_SESSION["multichat_user"]);
			unset($_SESSION["multichat_nivel"]);
			unset($_SESSION["multichat_funcao"]);
		}	

		setcookie("multichat_user","",time()-3600,"/");
		
		return;
	}

	//grava os dados de acesso para a sessão atual e para sessões que iniciarem antes da expiração do cookie
	private function grava_login($login_user, $login_nivel){
		$minutos=15;

		setcookie("multichat_user",$login_nivel . "-" . $login_user,time()+(60*$minutos),"/");		
		$_SESSION["multichat_user"] = $login_user;
		$_SESSION["multichat_nivel"] = $login_nivel;
		$_SESSION["multichat_funcao"] = "GERENTE";

		//atualização de qualquer coluna, apenas para forçar a atualização do timestamp
		$sql = "Update MULTICHAT_USUARIO set usuario_timestamp = NOW(), usuario_status = 1 where usuario_usuario = '" . $login_user . "'";
		$Resultado = $this->db_conn->query($sql);

		if ($this->db_conn->error > 0){
			die("Erro na atualização do login do usuário. " . $this->db_conn->error);
		}

		return;
	}

	//Lista os usuários online no sistema
	public function lista_usuarios_online(){

		$dados=array();

		$sql = "select usuario_nome, usuario_usuario, usuario_nivel as conversas from MULTICHAT_USUARIO where usuario_nivel = 2 and usuario_status = 1";

		$resultado=$this->db_conn->query($sql) or die("Erro no acesso a tabela:" . $this->db_conn->error);

		if ($resultado->num_rows==0){			
			return $dados;
		}

		
		While($registro=$resultado->fetch_assoc()){
			$dados[]=$registro;						
		}		

		return $dados;
	}

	public function consulta_usuario_por_chave($chave){

		switch($chave){
			case "id":
				$condicao=" where usuario_id =" . $this->usuario_id;
				break;
			case "usuario":
				$condicao=" where usuario_usuario ='" . $this->usuario_usuario . "'";
				break;
		}		

		$sql = "select * from MULTICHAT_USUARIO " . $condicao;		

		$resultset=$this->db_conn->query($sql) or die("Erro na leitura do usuário, código xset /" + $this->db_conn->error);

		$this->usuario_id = 0;
		$this->usuario_usuario = "";
		$this->usuario_email = "";
		$this->usuario_nome="";
		$this->usuario_nivel = 0;
		$this->usuario_timestamp="";
		$this->usuario_status="0";
		$this->usuario_ultima_alteracao="";
		$this->usuario_quantidade=0;		

		if (!$resultset){
			return;
		}

		$resultado=$resultset->fetch_assoc();

		if (count($resultado)==0){
			return;
		}

		$this->usuario_id = $resultado['usuario_id'];
		$this->usuario_usuario = $resultado['usuario_usuario'];
		$this->usuario_email = $resultado['usuario_email'];
		$this->usuario_nome = $resultado['usuario_nome'];
		$this->usuario_nivel = $resultado['usuario_nivel'];
		$this->usuario_timestamp = $resultado['usuario_timestamp'];
		$this->usuario_status  = $resultado['usuario_status'];
		$this->usuario_ultima_alteracao = $resultado['usuario_ultima_alteracao'];
		$this->usuario_pw = $resultado['usuario_pw'];
		$this->usuario_quantidade = count($resultado);

		return;
	}

	//Lista os usuários online no sistema
	public function lista_usuarios($argumento = ""){

		$dados=array();

		$sql = "select * from MULTICHAT_USUARIO ";
		$sql_argumento_pesquisa="";

		
		if (isset($argumento)){
			$arg_pesquisa = $argumento;						
			if (strlen($arg_pesquisa) > 0 ){
				$sql_argumento_pesquisa = "where usuario_nome like '%" . $arg_pesquisa . "%' or usuario_usuario like '%" . $arg_pesquisa . "%' or usuario_email like '%" . $arg_pesquisa . "%'";				
			}

		} else {
			if (strtodate($this->stat_ultimo_acesso_inicial) &&
				strtodate($this->stat_ultimo_acesso_final)){
				$arg_pesquisa = "where usuario_timestamp between '" . $this->stat_ultimo_acesso_inicial . "' and '" . $this->stat_ultimo_acesso_final . "'";
			}
		}				

		$sql = $sql . $sql_argumento_pesquisa;		

		$resultado=$this->db_conn->query($sql) or die("Erro no acesso a tabela:" . $this->db_conn->error);

		if ($resultado->num_rows==0){			
			return $dados;
		}

		
		While($registro=$resultado->fetch_assoc()){
			$dados[]=$registro;			
		}		

		return $dados;
	}

	public function lista_usuarios_dashboad_gerente(){

		$this->erros = array();
		$this->usuarios = array();

		$sql = "select usuario_nome, usuario_timestamp, usuario_status, count(conversa_id) as usuario_conversas";
		$sql.= " from MULTICHAT_USUARIO left join MULTICHAT_CONVERSA on usuario_usuario = conversa_usuario ";
		$sql.= " where usuario_timestamp between '" . $this->stat_ultimo_acesso_inicial . "' and '" . $this->stat_ultimo_acesso_final . "' ";
		$sql.= " and conversa_fila_entrada between '". $this->stat_ultimo_acesso_inicial . "' and '" . $this->stat_ultimo_acesso_final . "' ";
		$sql.= " order by usuario_timestamp desc";

		$Resultado= $this->db_conn->query($sql);

		if ($this->db_conn->errno > 0){
			$this->erros[] = "Erro no acesso aos operadores. Status " . $this->db_conn->errno . "/" . $this->db_conn->error;
			return;
		}

		if ($Resultado->num_rows ==0){
			return;
		}

		while ($linha = $Resultado->fetch_assoc()){
			$this->usuarios[] = $linha;
		}

		$Resultado->close();
		return;
	}

	public function converte_tipo_acesso($acesso){
		switch ($acesso){
			case 1:
				return "Gerente";
				break;
			case 2:
				return "Operador";
				break;
			default:
				return "Não identificado";
		}
	}

	public function converte_tipo_status($status){
		switch ($status){
			case 1:
				return "Online";
				break;
			case 0:
				return "Offline";
				break;
			default:
				return "Não identificado";
		}
	}

	public function consiste_atualizacao_usuario(){
		$usuario_erros=array();

		if (strlen($this->usuario_usuario)==0){
			$usuario_erros['usuario'] = "Informe o código de usuário";
		}

		if (strlen($this->usuario_nome)==0){
			$usuario_erros['nome'] = "Informe o nome do usuário";
		}

		if (strlen($this->usuario_email)==0){
			$usuario_erros['email'] = "Informe o e-mail do usuário";
		} else {
			if (!filter_var($this->usuario_email, FILTER_VALIDATE_EMAIL)){
				$usuario_erros['email'] = "O e-mail informado é inválido.";
			}
		}

		if ($this->usuario_nivel !="1" && $this->usuario_nivel !="2"){
			$usuario_erros['nivel'] = "Nivel de usuário inválido";	
		}

		return $usuario_erros;
	}

	public function verifica_existencia_usuario($usuario = ""){

		$sql="Select count(*) as qtd from MULTICHAT_USUARIO where usuario_usuario = '" . $usuario . "'";

		$resultado=$this->db_conn->query($sql) or die("Erro no acesso à tabela de usuários. Informe o administrador do sistema, código XJd7/" . $this->db_conn->error);

		$quantidade=$resultado->fetch_assoc();

		if ($quantidade['qtd'] > 0){
			return true;
		} else{
			return false;
		}
	}

	public function Inclui_usuario(){

		$usuario_pw = $this->gerar_senha_temporaria();
		
		$this->usuario_pw = $usuario_pw;

		$usuario_pw = md5($usuario_pw);		

		$sql="Insert into MULTICHAT_USUARIO (usuario_nome, usuario_email, usuario_usuario, usuario_pw, usuario_nivel, usuario_ultima_alteracao,usuario_timestamp) values(";
		$sql=$sql . "'" . $this->usuario_nome . "',";
		$sql=$sql . "'" . $this->usuario_email . "',";
		$sql=$sql . "'" . $this->usuario_usuario . "',";		
		$sql=$sql . "'" . $usuario_pw . "',";
		$sql=$sql .  $this->usuario_nivel . ",";
		$sql=$sql . "'" . $_SESSION['multichat_user'] . "',";
		$sql.="CURTIME()";
		$sql=$sql . ")";

		$retorno=$this->db_conn->query($sql) or die("Erro na inclusão do usuário. Status xgft/" . $this->db_conn->error);

		$this->enviar_email_mudanca_senha();

		return $retorno;

	}

	public function atualiza_usuario(){

		$sql="Update MULTICHAT_USUARIO ";
		$sql.="set usuario_nome ='" . $this->usuario_nome . "',";
		$sql.=" usuario_email ='" . $this->usuario_email . "',";
		$sql.=" usuario_nivel =" . $this->usuario_nivel . ",";
		$sql.=" usuario_ultima_alteracao ='" . $_SESSION['multichat_user'] . "' ";
		$sql.="where usuario_id = " . $this->usuario_id;

		$retorno=$this->db_conn->query($sql) or die("Erro na atualização do usuário. Status xppgft/" . $this->db_conn->error);		

		return $retorno;

	}

	public function atualiza_status_usuario(){

		$sql="Update MULTICHAT_USUARIO set usuario_status = ? where usuario_usuario = ? ";

		$decl = $this->db_conn->prepare($sql);
		$decl->bind_param("is",
						$this->usuario_status,
						$this->usuario_usuario
			);
		$decl->execute();

		if ($this->db_conn->errno > 0 ){
			echo "Erro na atualização do status do usuário. Código " . $this->db_conn->errno . " / " . $this->db_conn->error;
			die();
		}

		$decl->close();
		return;

	}

	public function Exclui_usuario($id){
		
		$sql = "delete from MULTICHAT_USUARIO where usuario_id = " . $id;
		$retorno = $this->db_conn->query($sql) or die("Erro na exclusão do usuário, código uytx / " . $this->db_conn->error);
		return $retorno;
	}

	public function verifica_email_duplicado(){
		$sql = "select count(*) as quant from MULTICHAT_USUARIO where usuario_email = '" . $this->usuario_email . "' and usuario_id <> " . $this->usuario_id;
		$retorno = $this->db_conn->query($sql) or die("Erro no acesso ao cadastro de usuarios, código uiox / " . $this->db_conn->error . "  /  " . $sql);
		$quant = $retorno->fetch_assoc();
		return $quant['quant'];
	}

	private function gerar_senha_temporaria(){
		return $this->usuario_usuario . rand(0,9999);
	}

	public function enviar_email_mudanca_senha(){

		$Email= new Enviar_email();
		$Email->setDestinatario($this->usuario_email);
		$Email->setTitulo("Controle de acesso do sistema Multichat");
		$Email->setAssunto("Multichat - Sua nova senha chegou!");

		$mensagem="<br>Agora você já pode acessar o Multichat!<br>";
		$mensagem.="<br>Seus novos dados de acesso são:<br><br>";
		$mensagem.="usuário: {$this->usuario_usuario}<br>";
		$mensagem.="senha: {$this->usuario_pw}<br><br>";
		$mensagem.="Altere a sua senha o quanto antes para tornar seu acesso mais seguro.";

		$Email->setMensagem($mensagem);		
		$Email->formata_mensagem();
		$Email->enviar();
		
	}

	public function alteracao_senha(){

		$this->erros=array();

		if (strlen($this->usuario_usuario)==0){
			$this->erros['usuario_nao_informado'] = "Código de usuário não informado";
		}

		if (strlen($this->usuario_pw)==0){
			$this->erros['senha_atual_nao_informada'] = "Senha atual não informada";
		}

		if (strlen($this->usuario_nova_senha)==0){
			$this->erros['nova_senha_nao_informada'] = "Nova senha não informada";
		}

		if (strlen($this->usuario_nova_senha_confirmacao)==0){
			$this->erros['confirmacao_nao_informada'] = "Confirmação da nova senha não informada";
		}

		if (strlen($this->usuario_nova_senha)>0 && strlen($this->usuario_pw)> 0 && $this->usuario_nova_senha == $this->usuario_pw){
			$this->erros['senha_nao_alterada'] = "Nova senha informada é igual à anterior/";
		}

		if (strlen($this->usuario_nova_senha)>0 && strlen($this->usuario_nova_senha_confirmacao)> 0 && $this->usuario_nova_senha != $this->usuario_nova_senha_confirmacao){
			$this->erros['confirmacao_nao_confere'] = "Nova senha e sua confirmação não conferem";
		}

		if (count($this->erros)>0){
			return;
		}

		$nivel = $this->valida_acesso();

		if ($nivel==0){
			$this->erros['acesso_nao_autorizado'] = "Usuário e/ou senha informados inválidos";
		}

		$sql = "update MULTICHAT_USUARIO ";
		$sql.= "set usuario_pw = '" . md5($this->usuario_nova_senha) . "' ";
		$sql.= "where usuario_usuario = '" . $this->usuario_usuario . "'";

		$this->db_conn->query($sql) or die("Erro na atualização do usuário. Código pxew / " . $this->db_conn->error);
		return;
	}

	public function recupera_senha(){

		$this->erros = array();
		if (strlen($this->usuario_usuario)==0){
			$this->erros['usuario não informado'] = "Usuário não informado";
			return;
		}
		
		$this->consulta_usuario_por_chave("usuario");		

		if ($this->usuario_quantidade == 0){
			$this->erros['usuario_nao_existe'] = "Úsuário não cadastrado no sistema";
			return;
		}	

		$nova_senha = $this->gerar_senha_temporaria();

		$this->usuario_nova_senha = $nova_senha;
		$this->usuario_nova_senha_confirmacao = $nova_senha;		
		$this->usuario_pw = $nova_senha;

		$sql = "update MULTICHAT_USUARIO ";
		$sql.= "set usuario_pw = '" . md5($this->usuario_nova_senha) . "' ";		
		$sql.= "where usuario_usuario = '" . $this->usuario_usuario . "'";

		$this->db_conn->query($sql) or die("Erro na atualização do usuário. Código pxew / " . $this->db_conn->error);		

		$this->enviar_email_mudanca_senha();

		return;

	}

	public function enviar_email_convite(){

		$Email= new Enviar_email();
		$Email->setDestinatario("niltonssjr@gmail.com");
		$Email->setTitulo("Controle de acesso do sistema Multichat");
		$Email->setAssunto("Multichat - Sua nova senha chegou!");

		$mensagem="<br>Agora você já pode acessar o Multichat!<br>";
		$mensagem.="<br>Seus novos dados de acesso são:<br><br>";
		$mensagem.="usuário: {$this->usuario_usuario}<br>";
		$mensagem.="senha: {$this->usuario_pw}<br><br>";
		$mensagem.="Altere a sua senha o quanto antes para tornar seu acesso mais seguro.";

		$Email->setMensagem($mensagem);		
		$Email->formata_mensagem();
		$Email->enviar();
		
	}

}

?> 