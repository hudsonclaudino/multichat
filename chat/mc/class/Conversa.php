<?php  

class Conversa{

	private $db_conn;
	private $conversa_id;
	private $conversa_cliente_ip;
	private $conversa_cliente_navegador;
	private $conversa_fila_entrada;
	private $conversa_fila_atendimento;
	private $conversa_fim_atendimento;
	private $conversa_campcab_id;
	private $conversa_campdet_id;
	private $conversa_cliente_nome;
	private $conversa_cliente_email;
	private $conversa_cliente_cpf;
	private $conversa_usuario;
	private $conversa_mensagem_offline;
	private $conversa_mensagem_offline_leitura_data;
	private $conversa_mensagem_offline_leitura_usuario;
	private $conversa_status;	
	//0 - Aguardando atendimento
	//1 - Em atendimento
	//2 - Atendimento finalizado
	//3 - Desistência
	//4 - Mensagem offline
	private $conversa_acordo;
	private $conversas;
	public $erros;

	private $mensagem_id;
	private $mensagem_conversa_id;
	private $mensagem_remetente;
	//1 - Enviada pelo operador
	//2 - Enviada pelo cliente
	private $mensagem_cadastro_data;
	private $mensagem_texto;
	private $mensagem_ultima_mensagem_id;
	public  $mensagens;		
	private $Email;		
	private $Campanha;
	private $conversa_pesquisa;
	private $conversa_emails_alternativos = array();

	private $stat_data_inicial;
	private $stat_data_final;

	private $stat_total_conversas;
	private $stat_conversas_na_fila;
	private $stat_conversas_em_atendimento;
	private $stat_conversas_finalizadas;
	private $stat_conversas_abandonadas;
	private $stat_conversas_mensagem_offline;
	private $stat_conversas_media_espera;
	private $stat_fila_mais_antiga;	

	public function __construct($con){
		$this->db_conn = $con;
	}

	//getters and setters
	public function set_conversa_id($conversa){
		$this->conversa_id = $conversa;
	}
	public function get_conversa_id(){
		return $this->conversa_id;
	}

	public function set_conversa_cliente_ip($ip){
		$this->conversa_cliente_ip;
	}

	public function get_conversa_cliente_ip(){
		return $this->conversa_cliente_ip;
	}
	
	public function set_conversa_cliente_navegador($navegador){
		$this->conversa_cliente_navegador = $navegador;
	}

	public function get_conversa_cliente_navegador(){
		return $this->conversa_cliente_navegador;
	}

	public function set_conversa_fila_entrada($entrada){
		$this->conversa_fila_entrada = $entrada;
	}

	public function get_conversa_fila_entrada(){
		return $this->conversa_fila_entrada;
	}

	public function set_conversa_fila_atendimento($atendimento){
		$this->conversa_fila_atendimento = $atendimento;
	}

	public function get_conversa_fila_atendimento(){
		return $this->conversa_fila_atendimento;
	}
	
	public function set_conversa_fim_atendimento($fim){
		$this->conversa_fim_atendimento = $fim;
	}

	public function get_conversa_fim_atendimento(){
		return $this->conversa_fim_atendimento;
	}
	
	public function set_conversa_campcab_id($id){
		$this->conversa_campcab_id = $id;
	}

	public function get_conversa_campcab_id(){
		return $this->conversa_campcab_id;
	}

	public function set_conversa_campdet_id($id){
		$this->conversa_campdet_id = $id;
	}

	public function get_conversa_campdet_id(){
		return $this->conversa_campdet_id;
	}
		
	public function set_conversa_cliente_nome($nome){
		$this->conversa_cliente_nome = $nome;
	}

	public function get_conversa_cliente_nome(){
		return $this->conversa_cliente_nome;
	}

	public function set_conversa_cliente_email($email){
		$this->conversa_cliente_email = $email;
	}

	public function get_conversa_cliente_email(){
		return $this->conversa_cliente_email;
	}
	
	public function set_conversa_cliente_cpf($cpf){
		$this->conversa_cliente_cpf = preg_replace("/[^0-9]/","",$cpf);
	}

	public function get_conversa_cliente_cpf(){
		return $this->conversa_cliente_cpf;
	}
	
	public function set_conversa_usuario($usuario){
		$this->conversa_usuario = $usuario;
	}

	public function get_conversa_usuario(){
		return $this->conversa_usuario;
	}
	
	public function set_conversa_mensagem_offline($mensagem){
		$this->conversa_mensagem_offline = $mensagem;
	}

	public function get_conversa_mensagem_offline(){
		return $this->conversa_mensagem_offline;
	}
	
	public function set_conversa_mensagem_offline_leitura_data($data){
		$this->conversa_mensagem_offline_leitura_data = $data;
	}

	public function get_conversa_mensagem_offline_leitura_data(){
		return $this->conversa_mensagem_offline_leitura_data;
	}

	public function set_conversa_mensagem_offline_leitura_usuario($usuario){
		$this->conversa_mensagem_offline_leitura_usuario = $usuario;		
	}

	public function get_conversa_mensagem_offline_leitura_usuario(){
		return $this->conversa_mensagem_offline_leitura_usuario;
	}
	
	public function set_conversa_status($status){
		$this->conversa_status = $status;
	}

	public function get_conversa_status(){
		return $this->conversa_status;
	}

	public function get_conversa_status_descricao(){
		switch($this->conversa_status){
			case 0: return "Aguardando atendimento"; break;
			case 1: return "Em atendimento"; break;
			case 2: return "Atendido"; break;
			case 3: return "Desistência"; break;
			case 4: return "Mensagem offline"; break;
			case 5: return "Interrompido pelo operador"; break;
			case 6: return "Interrompido pelo cliente"; break;
			default: return "Aguardando atendimento"; break;
		}
	}
	
	public function set_conversa_acordo($acordo){
		$this->conversa_acordo = $acordo;
	}

	public function get_conversa_acordo(){
		return $this->conversa_acordo;
	}	

	public function get_conversas(){
		return $this->conversas;
	}

	public function set_conversa_pesquisa($conversa_pesquisa){
		$this->conversa_pesquisa = trim($conversa_pesquisa);
	}

	public function set_mensagem_id($mensagem_id){
		$this->mensagem_id = $mensagem_id;
	}

	public function get_mensagem_id(){
		return $this->mensagem_id;
	}

	public function set_mensagem_conversa_id ($conversa_id){
		$this->mensagem_conversa_id = $conversa_id;
	}

	public function get_mensagem_conversa_id(){
		return $this->mensagem_conversa_id;
	}
	
	public function set_mensagem_remetente ($mensagem_remetente){
		$this->mensagem_remetente = $mensagem_remetente;
	}

	public function get_mensagem_remetente(){
		return $this->mensagem_remetente;
	}

	public function get_mensagem_cadastro_data(){
		return $this->mensagem_cadastro_data;
	}
	
	public function set_mensagem_texto($texto){
		$this->mensagem_texto = $texto;
	}

	public function get_mensagem_texto(){
		return $this->mensagem_texto;
	}
	
	public function get_mensagens(){
		return $this->mensagens;
	}	

	public function set_mensagem_ultima_mensagem_id($id){
		$this->mensagem_ultima_mensagem_id = $id;
	}

	public function get_mensagem_ultima_mensagem_id(){
		return $this->mensagem_ultima_mensagem_id;
	}

	public function set_conversa_emails_alternativos($emails){

		$this->conversa_emails_alternativos = array();

		if (strlen($emails)==0){
			return;
		}

		$this->conversa_emails_alternativos = explode(";",$emails);
	}

	public function set_stat_data_inicial ($data_inicial){
		$this->stat_data_inicial = $data_inicial . " 00:00:00";
	}

	public function set_stat_data_final ($data_final){
		$this->stat_data_final = $data_final . " 23:59:59";
	}

	public function get_stat_data_inicial(){
		return $this->stat_data_inicial;
	}

	public function get_stat_data_final(){
		return $this->stat_data_final;
	}

	public function get_stat_total_conversas(){
		return $this->stat_total_conversas;
	}

	public function get_stat_conversas_na_fila(){
		return $this->stat_conversas_na_fila;
	}

	public function get_stat_conversas_em_atendimento(){
		return $this->stat_conversas_em_atendimento;
	}

	public function get_stat_conversas_finalizadas(){
		return $this->stat_conversas_finalizadas;
	}

	public function get_stat_conversas_abandonadas(){
		return $this->stat_conversas_abandonadas;
	}

	public function get_stat_conversas_mensagem_offline(){
		return $this->stat_conversas_mensagem_offline;
	}

	public function get_stat_conversas_media_espera(){
		return $this->stat_conversas_media_espera;
	}

	public function get_stat_fila_mais_antiga(){
		return $this->stat_fila_mais_antiga;
	}

   //Cadastra o cliente na fila de espera para a conversa
   	public function cadastra_cliente_na_fila(){

   		$this->erros=array();

   		$this->verificar_sistema_e_ip();
		$this->set_conversa_fila_entrada(date("Y-m-d H:i:s",time()));
		$this->set_conversa_fila_atendimento(null);	
		$this->set_conversa_status(0);

		$sql = "Insert into MULTICHAT_CONVERSA (";
		$sql.= "conversa_cliente_ip,";
		$sql.= "conversa_cliente_navegador,";
		$sql.= "conversa_fila_entrada,";		
		$sql.= "conversa_campcab_id,";
		$sql.= "conversa_campdet_id,";
		$sql.= "conversa_cliente_nome,";
		$sql.= "conversa_cliente_email,";
		$sql.= "conversa_cliente_cpf,";
		$sql.= "conversa_usuario,";		
		$sql.= "conversa_status";
		$sql.= ") values (";
		$sql.= "?,?,?,?,?,?,?,?,?,?)";

		$decl = $this->db_conn->prepare($sql);
		$decl->bind_param("sssiissssi",
				$this->conversa_cliente_ip,
				$this->conversa_cliente_navegador,
				$this->conversa_fila_entrada,
				$this->conversa_campcab_id,
				$this->conversa_campdet_id,
				$this->conversa_cliente_nome,
				$this->conversa_cliente_email,
				$this->conversa_cliente_cpf,
				$this->conversa_usuario,
				$this->conversa_status);

		$decl->execute();

		if ($this->db_conn->errno > 0){
			$this->erros[]="Erro no cadastramento da convite. Por favor, entre em contato com o administrador do sistema, e informe o código " . $this->db_conn->error;
			return false;
		}

		$decl->close();

		$this->set_conversa_id($this->db_conn->insert_id);
		return;	

   	}

   	public function verifica_quantidade_fila(){

   		$condicao = ($this->conversa_id == 0)? "" : " and conversa_id <=" . $this->conversa_id;

   		$sql = "Select count(*) as qtd from MULTICHAT_CONVERSA where conversa_status = 0" . $condicao;
   		$resultado = $this->db_conn->query($sql);

		if ($this->db_conn->errno > 0){
			$this->erros[]="Erro no acesso à fila de espera. Por favor, entre em contato com o administrador do sistema, e informe o código " . $this->db_conn->error;
			return false;
		}   		

		$retorno = $resultado->fetch_assoc();
		return $retorno['qtd'];
   	}

   	public function obtem_conversa_cab_por_id(){

   		$this->inicia_propriedades_conversa();
   		$sql = "select * from MULTICHAT_CONVERSA where conversa_id = " . $this->conversa_id;

   		$resultado = $this->db_conn->query($sql);

   		if ($this->db_conn->errno > 0 ){
   			$this->erros[] = "Erro no acesso à conversa. Status " . $this->db_conn->errno . " / " . $this->db_conn->error;
   			return; 
   		}

   		if ($resultado->num_rows ==0){
   			return;
   		}

   		$registro = $resultado->fetch_assoc();
   		$this->set_propriedades_conversa($registro);

   		return;
   	}

   	function inicia_propriedades_conversa(){

   		$this->conversa_cliente_ip = "";
   		$this->conversa_cliente_navegador = "";
   		$this->conversa_cliente_fila_entrada = "";
   		$this->conversa_cliente_fila_atendimento = "";
		$this->conversa_campcab_id = 0;
		$this->conversa_campdet_id = 0;
		$this->conversa_cliente_nome = "";
		$this->conversa_cliente_email = "";
		$this->conversa_cliente_cpf = "";
		$this->conversa_usuario = "";
		$this->conversa_mensagem_offline = "";
		$this->conversa_mensagem_offline_leitura_data = "";
		$this->conversa_mensagem_offline_leitura_usuario = "";
		$this->conversa_status = 0;

   	}

   	function set_propriedades_conversa($registro){

   		$this->conversa_id = $registro['conversa_id'];
   		$this->conversa_cliente_ip = $registro['conversa_cliente_ip'];
   		$this->conversa_cliente_navegador = $registro['conversa_cliente_navegador'];
   		$this->conversa_fila_entrada = $registro['conversa_fila_entrada'];
   		$this->conversa_fila_atendimento =$registro['conversa_fila_atendimento'];
		$this->conversa_campcab_id = $registro['conversa_campcab_id'];
		$this->conversa_campdet_id = $registro['conversa_campdet_id'];
		$this->conversa_cliente_nome = $registro['conversa_cliente_nome'];
		$this->conversa_cliente_email = $registro['conversa_cliente_email'];
		$this->conversa_cliente_cpf = $registro['conversa_cliente_cpf'];
		$this->conversa_usuario = $registro['conversa_usuario'];
		$this->conversa_mensagem_offline = $registro['conversa_mensagem_offline'];
		$this->conversa_mensagem_offline_leitura_data = $registro['conversa_mensagem_offline_leitura_data'];
		$this->conversa_mensagem_offline_leitura_usuario = $registro['conversa_mensagem_offline_leitura_usuario'];
		$this->conversa_status = $registro['conversa_status'];

   	}

   	//verifica o sistema operacional e o ip do cliente que está solicitando a conversa
   	private function verificar_sistema_e_ip(){

   		$this->set_conversa_cliente_ip($_SERVER['REMOTE_ADDR']);

   		$agente = strtoupper($_SERVER['HTTP_USER_AGENT']);
   		if (strpos($agente,"ANDROID")){
   			$sistema = "Android";
   		} else {
   			if (strpos($agente,"IOS")){
   				$sistema = "IOS";
   			} else {
   				if (strpos($agente,"Windows")){
   					$sistema = "Windows";
   				} else {
   					$sistema = "Outros";
   				}
   			}
   		}

   		$this->set_conversa_cliente_navegador($sistema);

   	}

   	public function grava_mensagem_offline(){

   		$this->erros=array();

		$this->set_conversa_fila_atendimento(date("Y-m-d H:i:s",time()));		
		$this->set_conversa_status(4);

		$sql = "Update MULTICHAT_CONVERSA  set ";		
		$sql.= "conversa_fila_atendimento = ?,";				
		$sql.= "conversa_mensagem_offline = ?,";				
		$sql.= "conversa_status = ? ";		
		$sql.= "where conversa_id = ?";

		$decl = $this->db_conn->prepare($sql);
		$decl->bind_param("ssii",
				$this->conversa_fila_atendimento,
				$this->conversa_mensagem_offline,
				$this->conversa_status,
				$this->conversa_id);

		$decl->execute();

		if ($this->db_conn->errno > 0){
			$this->erros[]="Erro ao deixar a mensagem. Por favor, entre em contato com o administrador do sistema, e informe o código " . $this->db_conn->error;
			return false;
		}

		$decl->close();
		
		$this->set_mensagem_remetente(2);
		$this->set_mensagem_texto($this->get_conversa_mensagem_offline());
		$this->cadastra_mensagem_enviada();
		return;	

   	}

   	public function encerrar_conversa(){

   		$this->obtem_conversa_cab_por_id();

		switch ($this->conversa_status){
			case 0: //cliente estava na fila, marcar como desistência
				$this->marcar_conversa_como_desistencia();
				break;
			case 1: //cliente estava em uma conversa, mandar conversa por e-mail e marcar como encerrada
				$this->marcar_conversa_como_encerrada();
				$this->enviar_conversa_por_email();
				break;				 
			case 2: //conversa encerrada pelo operador, simplesmente encerrar
				break;
			case 3: //conversa já marcada como desistênca, simplesmente encerrar
				break;
			case 4: //mensagem offline enviada, simplesmente encerrar
				break;
			default: //encerrar
		}

   		return;
   	}

   	public function marcar_conversa_como_desistencia(){

   		$this->erros=array();

		$this->set_conversa_fila_atendimento(date("Y-m-d H:i:s",time()));		
		$this->set_conversa_status(3);

		$sql = "Update MULTICHAT_CONVERSA  set ";	
		$sql.= "conversa_fila_atendimento = ?,";		
		$sql.= "conversa_status = ? ";		
		$sql.= "where conversa_id = ?";

		$decl = $this->db_conn->prepare($sql);
		$decl->bind_param("sii",				
				$this->conversa_fila_atendimento,
				$this->conversa_status,
				$this->conversa_id);

		$decl->execute();

		if ($this->db_conn->errno > 0){
			$this->erros[]="Erro ao marcar a desistência. Por favor, entre em contato com o administrador do sistema, e informe o código " . $this->db_conn->error;
			return false;
		}

		$decl->close();
		
		return;	
   	}

	private function marcar_conversa_como_encerrada(){

   		$this->erros=array();
   		$this->set_conversa_fim_atendimento(date("Y-m-d H:i:s",time()));		
		
		$this->set_conversa_status(2);

		$sql = "Update MULTICHAT_CONVERSA  set ";		
		$sql.= "conversa_fim_atendimento = ?,";						
		$sql.= "conversa_status = ? ";		
		$sql.= "where conversa_id = ?";

		$decl = $this->db_conn->prepare($sql);
		$decl->bind_param("sii",
				$this->conversa_fim_atendimento,				
				$this->conversa_status,
				$this->conversa_id);

		$decl->execute();

		if ($this->db_conn->errno > 0){
			$this->erros[]="Erro ao marcar o encerramento. Por favor, entre em contato com o administrador do sistema, e informe o código " . $this->db_conn->error;
			return false;
		}

		$decl->close();
		
		return;	
   	}

   	public function enviar_conversa_por_email(){			

   		$this->set_mensagem_ultima_mensagem_id(0);
   		$this->verifica_ultimas_mensagens();

   		if (count($this->mensagens)==0){
   			return;
   		}

   		$primeira_mensagem = $this->mensagens[0];
   		$data_negociacao_original = $primeira_mensagem['mensagem_cadastro_data'];
   		$data_mensagem = date_format(date_create($data_negociacao_original),"d-m-Y");
   		$titulo_email = "Sua negociação efetuada em " . $data_mensagem;
   		$mensagem = "";
   		
   		foreach($this->mensagens as $mens){
   			$mensagem .= $this->linha_email_conversa($mens);
   		}

   		$mensagem.= "<div class='conversa_finalizada'><span>Conversa finalizada</span></div>";

		if (!isset($this->Email)) $this->Email= new Enviar_email();
		
		$this->Email->LimpaListaDeDestinatarios();

		if (count($this->conversa_emails_alternativos)==0){
			$this->Email->setDestinatario($this->conversa_cliente_email);	
		} else {
			foreach($this->conversa_emails_alternativos as $email){
				$this->Email->setDestinatario($email);
			}
		}
		
		$this->Email->setAssunto($titulo_email);			
		$this->Email->setTitulo($titulo_email);		
		$this->Email->setMensagem($mensagem);		
		$this->Email->formata_mensagem();
		$this->Email->enviar();   		
   		return;

   	}

   	private function linha_email_conversa($msg){

   		$ultima_mensagem = $msg['mensagem_id'];
   		$horario = date_create($msg['mensagem_cadastro_data']);   		
   		$horario_formatado = date_format($horario,"d/m h:m");
   		$texto_email = "";   		

   		$classe_mensagem = ($msg['mensagem_remetente_tipo']=='1')? "mensagem_operador_texto" : "mensagem_cliente_texto";
		
   		$texto_email .= "<span class='{$classe_mensagem}'><strong>{$msg['mensagem_remetente']}:</strong> {$msg['mensagem_texto']}";
   		$texto_email .= "<span class='mensagem_hora_corrente'> - {$horario_formatado}</span></span>";

   		return $texto_email;

   	}

   	//lista conversas por status
   	public function lista_conversas( $status=0){

   		$this->conversas = array();

   		$sql = "Select *  from MULTICHAT_CONVERSA where conversa_status = " . $status . " order by conversa_fila_entrada";

   		$resultado = $this->db_conn->query($sql);

		if ($this->db_conn->errno > 0){
			$this->erros[]="Erro no acesso à fila de espera. Por favor, entre em contato com o administrador do sistema, e informe o código " . $this->db_conn->error;
			return false;
		}   		

		if ($resultado->num_rows ==0){
			return;
		}

		while ($registro=$resultado->fetch_assoc()){
			$linha = array();
			foreach($registro as $key => $value){
				$linha[$key]=$value;
			}
			$this->conversas[] = $linha;
		}
		
		
   	}

   	//lista conversas por argumento de pesquisa, desconsiderando a fila de espera
   	public function lista_conversas_por_argumento(){

   		$this->erros = array();
   		$this->conversas = array();

   		$sql_argumento_de_pesquisa = "";
   		$argumento_pesquisa = $this->conversa_pesquisa;

   		if (strlen($argumento_pesquisa)>0){
   			$sql_argumento_de_pesquisa .= " where conversa_campcab_id like '%{$argumento_pesquisa}%' or ";
   			$sql_argumento_de_pesquisa .= " conversa_cliente_nome like '%{$argumento_pesquisa}%' or ";
   			$sql_argumento_de_pesquisa .= " conversa_cliente_email like '%{$argumento_pesquisa}%' or ";
   			$sql_argumento_de_pesquisa .= " conversa_cliente_cpf like '%{$argumento_pesquisa}%' or ";
   			$sql_argumento_de_pesquisa .= " conversa_usuario like '%{$argumento_pesquisa}%' or ";
   			$sql_argumento_de_pesquisa .= " conversa_mensagem_offline like '%{$argumento_pesquisa}%' or ";
   			$sql_argumento_de_pesquisa .= " conversa_id in (select mensagem_conversa_id from MULTICHAT_MENSAGEM ";
   			$sql_argumento_de_pesquisa .= " where mensagem_texto like '%{$argumento_pesquisa}%') ";
   		}

   		$sql = "select * from MULTICHAT_CONVERSA " . $sql_argumento_de_pesquisa . "order by conversa_id desc";

   		$resultado = $this->db_conn->query($sql);

   		if ($this->db_conn->errno > 0){
   			$this->erros[] = "Erro no acesso às conversas. Status " . $this->db_conn->errno . "/" . $this->db_conn->error;
   			return;
   		}

   		if ($resultado->num_rows ==0){
   			return;
   		}

   		while($registro=$resultado->fetch_assoc()){
   			$campos=array();
   			foreach($registro as $key => $value){
   				$campos[$key] = $value;
   			}
   			$this->set_conversa_status($registro['conversa_status']);
   			$campos['conversa_status_descricao'] = $this->get_conversa_status_descricao();
   			$this->conversas[]=$campos;
   		}

//  		$resultado::free();

   		return;   		
   	}

   	public function verifica_proxima_conversa(){

   		$this->erros = array();

   		$sql = "select min(conversa_id) as id from MULTICHAT_CONVERSA where conversa_status = 0";

   		$resultado = $this->db_conn->query($sql);

   		if ($this->db_conn->errno > 0 ){
   			$this->erros[]= "Problemas no acesso à próxima conversa. Status " . $this->db_conn->errno . "/" . $this->db_conn->error;
   			return;
   		}

   		if ($resultado->num_rows ==0){
   			$this->set_conversa_id(0);
   			return;
   		}

   		$registro = $resultado->fetch_assoc();

   		$this->set_conversa_id($registro['id']);
   		$this->obtem_conversa_cab_por_id();
   		return;
   	}

   	public function inicia_conversa(){

   		$this->erros = array();
   		$this->set_conversa_fila_atendimento(date("Y-m-d H:i:s",time()));		

   		$sql = "Update MULTICHAT_CONVERSA set conversa_status = 1, conversa_usuario = ?, conversa_fila_atendimento = ? where conversa_id = ?";
   		$decl = $this->db_conn->prepare($sql);
   		$decl->bind_param("ssi",
   				$this->conversa_usuario,
   				$this->conversa_fila_atendimento,
   				$this->conversa_id);
   		$decl->execute();

   		if ($this->db_conn->errno > 0){
   			$this->erros[] = "Erro ao iniciar a conversa. Status " . $this->db_conn->errno . "/" . $this->db_conn->error;
   			return;
   		}

   		$decl->close();

   		if ($this->conversa_campcab_id > 0){
   			$this->cadastra_conversa_na_campanha();
   		}

   	}

   	private function cadastra_conversa_na_campanha(){

   		if (!isset($this->Campanha)){
   			$this->Campanha = new Campanha($this->db_conn);
   		}

   		$this->obtem_conversa_cab_por_id();

   		$this->Campanha->set_campcab_id($this->conversa_campcab_id);
   		$this->Campanha->set_campdet_id($this->conversa_campdet_id);
   		$this->Campanha->set_campdet_conversa_id($this->conversa_id);

   		$this->Campanha->atualiza_campanha_conversa_id();

   		if (count($this->Campanha->erros)>0){
   			$this->erros = $this->Campanha->erros;
   			return;
   		}

   	}

   	public function verifica_ultimas_mensagens(){

   		$this->erros = array();
   		$this->mensagens = array();
   		$this->obtem_conversa_cab_por_id();

   		$sql = "select * from MULTICHAT_MENSAGEM where mensagem_conversa_id = " . $this->get_conversa_id() . " and ";
   		$sql.= "mensagem_id > " . $this->get_mensagem_ultima_mensagem_id();

   		$resultado = $this->db_conn->query($sql);

   		if ($this->db_conn->errno > 0){
   			$this->erros[] = "Problemas no acesso à mensagem. Status " . $this->db_conn->errno . "/" . $this->db_conn->error . "/" . $sql;
   			return;
   		}

   		if ($resultado->num_rows ==0){
   			return;
   		}

   		while($registro = $resultado->fetch_assoc()){
   			$dados_mensagem = array();
   			$dados_mensagem['mensagem_id'] = $registro['mensagem_id'];
   			$dados_mensagem['mensagem_cadastro_data'] = $registro['mensagem_cadastro_data'];
   			$dados_mensagem['mensagem_remetente_tipo'] = $registro['mensagem_remetente'];
   			$dados_mensagem['mensagem_remetente'] = ($registro['mensagem_remetente']==1)? $this->conversa_usuario : $this->conversa_cliente_nome;
   			$dados_mensagem['mensagem_texto'] = $registro['mensagem_texto'];
   			$this->mensagens[]=$dados_mensagem;
   		}

   		return;

   	}

   	public function verifica_conversa_ativa_operador(){

   		$usuario_pesquisa = $this->conversa_usuario;
   		$this->inicia_propriedades_conversa();
   		$this->set_conversa_id(0);

   		$this->erros = array();

   		$sql = "select * from MULTICHAT_CONVERSA where conversa_usuario ='" . $usuario_pesquisa . "' and conversa_status =1";

   		$resultado = $this->db_conn->query($sql);

   		if ($this->db_conn->errno > 0){
   			$this->erros[]="Erro no acesso ao cadastro de conversas. Status " . $this->db_conn->errno . "/" . $this->db_conn->error;
   			return;
   		}

   		if ($resultado->num_rows ==0){
   			return;
   		}

   		if ($resultado->num_rows >1){
   			$this->erros[]="Erro lógico no cadastro de conversas. Status DPL";
   			return;
   		}

   		$registro = $resultado->fetch_assoc();
   		$this->set_propriedades_conversa($registro);
   		return;
   	}

   	//verifica se a conversa foi iniciada
   	public function verifica_conversa_ativa_cliente(){   		

   		$this->obtem_conversa_cab_por_id();
   		
   		if ($this->conversa_status == 1){
   			return true;
   		}

   		return false;
   	}

   	public function verifica_conversa_finalizada_cliente(){

   		$this->obtem_conversa_cab_por_id();
   		
   		if ($this->conversa_status == 2){
   			return true;
   		}

   		return false;
   	}

   	public function cadastra_mensagem_enviada(){

  		$this->erros = array();

  		$sql = "insert into MULTICHAT_MENSAGEM (mensagem_conversa_id, mensagem_remetente, mensagem_texto) values (?,?,?)";
  		$decl = $this->db_conn->prepare($sql);
  		$decl->bind_param("iis",
  				$this->conversa_id,
  				$this->mensagem_remetente,
  				$this->mensagem_texto);
  		$decl->execute();

  		if ($this->db_conn->errno > 0){
  			$this->erros[] = "Problemas no cadastramento da mensagem. Status " . $this->db_conn->errno . "/" . $this->db_conn->error;
  			return;
  		}

  		$decl->close();
   	}

   	public function associa_cpf(){

   		$this->erros=array();

		$sql = "Update MULTICHAT_CONVERSA  set ";		
		$sql.= "conversa_cliente_cpf = ? ";						
		$sql.= "where conversa_id = ?";

		$decl = $this->db_conn->prepare($sql);
		$decl->bind_param("si",
				$this->conversa_cliente_cpf,				
				$this->conversa_id);

		$decl->execute();

		if ($this->db_conn->errno > 0){
			$this->erros[]="Erro ao associar o CPF. Por favor, entre em contato com o administrador do sistema, e informe o código " . $this->db_conn->error;
			return false;
		}

		$decl->close();
		
		return;	

   	}

   	public function carrega_estatisticas(){

   		$this->erros = array();

   		$this->stat_total_conversas = 0;
		$this->stat_conversas_na_fila = 0;			
		$this->stat_conversas_em_atendimento = 0;
		$this->stat_conversas_finalizadas = 0;
		$this->stat_conversas_abandonadas = 0;
		$this->stat_conversas_mensagem_offline = 0;
		$this->stat_conversas_media_espera = 0;
		$this->stat_fila_mais_antiga = 0;

   		$sql = "Select conversa_status, count(*) as qtd from MULTICHAT_CONVERSA where ";
   		$sql.= "conversa_fila_entrada between '" . $this->stat_data_inicial . "' and '" . $this->stat_data_final . "' group by conversa_status";

   		$Resultado = $this->db_conn->query($sql);

   		if ($this->db_conn->errno > 0){
   			$this->erros[] = "Erro na processo de estatística das conversas. Status " . $this->db_conn->errno . "/" . $this->db_conn->error;
   			return;   		
   		}

   		if ($Resultado->num_rows ==0){
   			return;
   		}

   		while($detalhe = $Resultado->fetch_assoc()){
   			switch ($detalhe['conversa_status']){
   				case 0:
   					$this->stat_conversas_na_fila = $detalhe['qtd'];
   					break;
   				case 1:
   					$this->stat_conversas_em_atendimento = $detalhe['qtd'];
   					break;
   				case 2:
   					$this->stat_conversas_finalizadas = $detalhe['qtd'];
   					break;
   				case 3:
   					$this->stat_conversas_abandonadas = $detalhe['qtd'];
   					break;
   				case 4:
   					$this->stat_conversas_mensagem_offline = $detalhe['qtd'];
   					break;
   				default:
   					$this->erros[] = "Status de conversa não identificado (" . $detalhe['conversa_status'] . ")";
   					return;
   					break;
   			}
   		}

   		$this->stat_total_conversas = $this->stat_conversas_na_fila + 
   										$this->stat_conversas_em_atendimento + 
   										$this->stat_conversas_finalizadas + 
   										$this->stat_conversas_abandonadas + 
   										$this->stat_conversas_mensagem_offline;

		$Resultado->close();   										

		$sql = "select TIME_TO_SEC(AVG(TIMEDIFF(conversa_fila_atendimento,conversa_fila_entrada))) as media from MULTICHAT_CONVERSA where ";
		$sql.= "conversa_fila_entrada between '" . $this->stat_data_inicial . "' and '" . $this->stat_data_final . "' and ";
		$sql.= "conversa_status in (1,2)";

		$Resultado = $this->db_conn->query($sql);

   		if ($this->db_conn->errno > 0){
   			$this->erros[] = "Erro na processo de estatística das conversas. Status " . $this->db_conn->errno . "/" . $this->db_conn->error;
   			return;   		
   		}

   		if ($Resultado->num_rows ==0){
   			return;
   		}

   		$detalhe = $Resultado->fetch_assoc();
   		$this->stat_conversas_media_espera = $detalhe['media'];
   		$Resultado->close().

		$sql = "select TIME_TO_SEC(TIMEDIFF(NOW(),conversa_fila_entrada)) as tempo from MULTICHAT_CONVERSA where ";
		$sql.= "conversa_id = ( select min(conversa_id) from MULTICHAT_CONVERSA where ";	
		$sql.= "conversa_fila_entrada between '" . $this->stat_data_inicial . "' and '" . $this->stat_data_final . "' and ";
		$sql.= "conversa_status = 0)";	

		$Resultado = $this->db_conn->query($sql);

   		if ($this->db_conn->errno > 0){
   			$this->erros[] = "Erro na processo de estatística de tempo das conversas. Status " . $this->db_conn->errno . "/" . $this->db_conn->error;
   			return;   		
   		}

   		if ($Resultado->num_rows ==0){
   			return;
   		}

   		$detalhe = $Resultado->fetch_assoc();
  
  		$this->stat_fila_mais_antiga = ($detalhe['tempo'] != null)? $detalhe['tempo']: 0;  		

   		return;

   	}


}
?>