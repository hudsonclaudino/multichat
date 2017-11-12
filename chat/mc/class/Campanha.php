<?php

	include_once "config.php";

	class Campanha{

		private $db_conn;
		private $Email;

		private $campcab_id;		
		private $campcab_nome;
		private $campcab_descricao;
		private $campcab_mensagem;
		private $campcab_criacao_data;
		private $campcab_criacao_usuario;
		private $campcab_envio_data;
		private $campcab_envio_usuario;
		private $campcab_status;
		private $campcab_status_extenso;
		public $campcab_quantidade;
		public $campanhas;

		private $campdet_id;
		private $campdet_campcab_id;
		private $campdet_cliente_nome;
		private $campdet_cliente_cpf;
		private $campdet_cliente_email;
		private $campdet_cadastro_usuario;
		private $campdet_cadastro_data;
		public $campdet_quantidade;
		private  $campdet_token_conversa;
		private $campdet_conversa_id;
		public $convites;

		public  $erros;

		public $nome_arquivo_entrada;
		public $nome_arquivo_saida;

		private $stat_data_inicial;
		private $stat_data_final;

		private $stat_novas_campanhas;
		private $stat_convites_gerados;
		private $stat_emails_enviados;
		private $stat_conversas_geradas;

		public function __construct($conexao){
			$this->db_conn = $conexao;
			return;
		}

		public function set_campcab_id($id){
			$this->campcab_id = $id;
		}
		public function set_campcab_status($status){
			$this->campcab_status = $status;
		}
		public function set_campcab_nome($nome){
			$this->campcab_nome = strtoupper($nome);
		}

		public function set_campcab_descricao($descricao){
			$this->campcab_descricao = $descricao;
		}

		public function set_campcab_mensagem($mensagem){
			$this->campcab_mensagem = $mensagem;
		}

		public function set_campdet_id($id){
			$this->campdet_id = $id;
		}

		public function set_campdet_cliente_nome($nome){
			$this->campdet_cliente_nome = $nome;
		}

		public function set_campdet_cliente_cpf($cpf){
			$this->campdet_cliente_cpf = preg_replace("/[^0-9]/","",$cpf);
		}
		public function set_campdet_cliente_email($email){
			$this->campdet_cliente_email = $email;
		}

		public function set_campdet_token_conversa($token){
			$this->campdet_token_conversa= $token;
		}

		public function set_stat_data_inicial($data_inicio){
			$this->stat_data_inicial = $data_inicio . " 00:00:00";
		}
		public function set_stat_data_final($data_fim){
			$this->stat_data_final = $data_fim . " 23:59:59";
		}

		public function get_campcab_status_extenso(){
			switch ($this->campcab_status) {
				case '0':
					$this->campcab_status_extenso= "Criada";
					break;
				case "1":
					$this->campcab_status_extenso= "Enviada";
					break;
				default:
					$this->campcab_status_extenso= "Não identificado";
					break;
			}
			return $this->campcab_status_extenso;

		}

		public function get_campcab_id(){
			return $this->campcab_id;
		}

		public function get_campcab_nome(){
			return $this->campcab_nome;
		}

		public function get_campcab_mensagem(){
			return $this->campcab_mensagem;
		}

		public function get_campcab_criacao_data(){
			return $this->campcab_criacao_data;
		}

		public function get_campcab_criacao_usuario(){
			return $this->campcab_criacao_usuario;
		}

		public function get_campcab_envio_data(){
			return $this->campcab_envio_data;
		}

		public function get_campcab_envio_usuario(){
			return $this->campcab_envio_usuario;
		}

		public function get_campcab_status(){
			return $this->campcab_status;
		}

		public function get_campdet_id(){
			return $this->campdet_id;
		}

		public function get_campdet_cliente_nome(){
			return $this->campdet_cliente_nome;
		}

		public function get_campdet_cliente_email(){
			return $this->campdet_cliente_email;
		}

		public function get_campdet_cliente_cpf(){
			return $this->campdet_cliente_cpf;	
		}

		public function set_campdet_conversa_id($conversa_id){
			$this->campdet_conversa_id = $conversa_id;
		}

		public function get_campdet_conversa_id(){
			return $this->campdet_conversa_id;
		}

		public function get_stat_novas_campanhas(){
			return $this->stat_novas_campanhas;
		}

		public function get_stat_convites_gerados(){
			return $this->stat_convites_gerados;
		}

		public function get_stat_emails_enviados(){
			return $this->stat_emails_enviados;
		}

		public function get_stat_conversas_geradas(){
			return $this->stat_conversas_geradas;
		}

		//lista resumida de campanhas para o dashboard
		public function lista_campanhas_dashboard(){

			$this->campanhas = array();
			$this->campcab_quantidade = 0;

			$sql = "select a.campcab_id as id, a.campcab_nome as nome, a.campcab_criacao_data as criacao_data, a.campcab_envio_data as envio_data, a.campcab_status as status, count(b.campdet_campcab_id) as qtd_envios from MULTICHAT_CAMPANHA_CAB a left join MULTICHAT_CAMPANHA_DET b on a.campcab_id = b.campdet_campcab_id group by a.campcab_id order by a.campcab_criacao_data desc, a.campcab_id desc limit 10";

			$resultado=$this->db_conn->query($sql) or die("Erro no acesso ao cadastro de campanhas, código trgty / " . $this->db_conn->error);

			while($campanha=$resultado->fetch_assoc()){
				$this->campanhas[]=$campanha;
			}
			
			$this->campcab_quantidade = count($this->campanhas);

			return;

		}

		//listagem padrao de campanhas
		public function lista_campanhas($argumento = ""){

			$this->campanhas = array();
			$this->campcab_quantidade = 0;
			$sql_argumento = "";


			if (strlen($argumento)>0){
				$sql_argumento = " where cab.campcab_nome like '%{$argumento}%' or ";	
				$sql_argumento.= " cab.campcab_descricao like '%{$argumento}%' or ";
				$sql_argumento.= " cab.campcab_criacao_usuario like '%{$argumento}%' or ";
				$sql_argumento.= " cab.campcab_envio_usuario like '%{$argumento}%' or ";
				$sql_argumento.= " det.campdet_cliente_nome like '%{$argumento}%' or ";
				$sql_argumento.= " det.campdet_cliente_email like '%{$argumento}%' ";
			}			

			$sql = "Select cab.campcab_id as id, ";
			$sql.=       " cab.campcab_nome as nome, ";
			$sql.=       " cab.campcab_criacao_data as criacao_data, ";
			$sql.=       " cab.campcab_criacao_usuario as criacao_usuario, ";
			$sql.=       " cab.campcab_envio_data as envio_data, ";
			$sql.=       " cab.campcab_envio_usuario as envio_usuario, ";
			$sql.=       " cab.campcab_status as status, ";
			$sql.=       " count(det.campdet_id) as envios, ";
			$sql.=		 " (select count(*) from MULTICHAT_CONVERSA conv where conv.conversa_campdet_id = det.campdet_id) as conversas ";
			$sql.= "from MULTICHAT_CAMPANHA_CAB cab left join MULTICHAT_CAMPANHA_DET det ";
			$sql.= " on cab.campcab_id = det.campdet_campcab_id ";
			$sql.= " group by cab.campcab_id ";
			$sql.= $sql_argumento;			
			$sql.= " order by cab.campcab_id desc";		

			$resultado=$this->db_conn->query($sql) or die("Erro no acesso ao cadastro de campanhas, código trgt9a / " . $this->db_conn->error);

			while($campanha=$resultado->fetch_assoc()){
				$this->campanhas[]=$campanha;
				
			}
			
			$this->campcab_quantidade = count($this->campanhas);

			return;

		}
		public function obtem_campanha_por_id(){			

			$this->campanhas=array();
			$this->campcab_quantidade=0;
			$this->convites=array();
			$this->campdet_quantidade=0;

			$sql = "select campcab_id, campcab_nome, campcab_descricao, campcab_mensagem, campcab_criacao_data, ";
			$sql.= "campcab_criacao_usuario, campcab_envio_data, campcab_envio_usuario, campcab_status, ";
			$sql.= "(select count(*) from MULTICHAT_CAMPANHA_DET where campdet_campcab_id = campcab_id) as convites, ";
			$sql.= "(select count(*) from MULTICHAT_CONVERSA where conversa_campcab_id = campcab_id) as conversas ";
			$sql.= "from MULTICHAT_CAMPANHA_CAB where campcab_id = {$this->campcab_id}";			

			$resultado=$this->db_conn->query($sql) or die("Erro no acesso ao cadastro base de campanhas, código gher / " . $this->db_conn->error . "/" . $sql);

			$dados=$resultado->fetch_assoc();

			if (count($dados)==0){
				return;
			}

			$this->campcab_quantidade=count($dados);
			$this->campcab_id=$dados['campcab_id'];
			$this->campcab_nome=$dados['campcab_nome'];
			$this->campcab_descricao=$dados['campcab_descricao'];
			$this->campcab_mensagem=$dados['campcab_mensagem'];
			$this->campcab_criacao_data=$dados['campcab_criacao_data'];
			$this->campcab_criacao_usuario=$dados['campcab_criacao_usuario'];
			$this->campcab_envio_data=$dados['campcab_envio_data'];
			$this->campcab_envio_usuario=$dados['campcab_envio_usuario'];
			$this->campcab_status=$dados['campcab_status'];
			$this->stat_convites_gerados = $dados['convites'];
			$this->stat_conversas_geradas= $dados['conversas'];

			return;	

		}

		public function obtem_convite_por_id(){

			$convite_id = $this->campdet_id;
			$this->inicia_dados_convite();
			$this->erros = array();

			$sql = "Select * from MULTICHAT_CAMPANHA_DET where campdet_id = " . $convite_id;
			$Resultado = $this->db_conn->query($sql);

			if ($this->db_conn->errno > 0){
				$this->erros[] = "Problemas ao acessar os dados do convite. Status " . $this->db_conn->errno . "/" . $this->db_conn->error;
				return;
			}

			if ($Resultado->num_rows ==0){
				return;
			}

			$dados = $Resultado->fetch_assoc();
			$this->campdet_id = $dados['campdet_id'];
			$this->campdet_campcab_id = $dados['campdet_campcab_id'];
			$this->campdet_cliente_nome = $dados['campdet_cliente_nome'];
			$this->campdet_cliente_cpf = $dados['campdet_cliente_cpf'];
			$this->campdet_cliente_email = $dados['campdet_cliente_email'];
			$this->campdet_cadastro_usuario = $dados['campdet_cadastro_usuario'];
			$this->campdet_cadastro_data = $dados['campdet_cadastro_data'];
			$this->campdet_token_conversa = $dados['campdet_token_conversa'];
			$this->campdet_conversa_id = $dados['campdet_conversa_id'];

			return;

		}

		private function inicia_dados_convite(){

			$this->campdet_id = 0;
			$this->campdet_campcab_id = 0;
			$this->campdet_cliente_nome = "";
			$this->campdet_cliente_cpf = "";
			$this->campdet_cliente_email = "";
			$this->campdet_cadastro_usuario = "";
			$this->campdet_cadastro_data = "";
			$this->campdet_token_conversa = "";
			$this->campdet_conversa_id = 0;

		}

		public function exclui_campanha(){

			$this->erros=array();
			$this->obtem_campanha_por_id();

			if ($this->campcab_quantidade==0){
				$this->erros['campanha_nao_encontrada'] = "Campanha inexistente";
				return;
			}

			if (!is_null($this->campcab_envio_data)){
				$this->erros['campanha_enviada'] = "Não é possível excluir campanhas já enviadas";
				return;
			}

			try{
				$this->db_conn->query("start transaction");
				$this->db_conn->query("delete from MULTICHAT_CAMPANHA_DET where campdet_campcab_id=" . $this->campcab_id);
				$this->db_conn->query("delete from MULTICHAT_CAMPANHA_CAB where campcab_id=" . $this->campcab_id);
				$this->db_conn->query("commit");

			} catch (Exception $e){				
				$this->erros['erro_sql']= "Erro na exclusão da campanha. Status xbge / " . $this->db_conn->error;
				$this->db_conn->query("rollback");
				return;
			}
		}

		public function valida_campanha_cab(){

			$this->erros=array();

			if (strlen($this->campcab_nome)==0){
				$this->erros['nome_nao_informado'] = "Informe o nome da campanha";
			}

			return;
		}

		public function atualiza_campanha(){

			$this->erros=array();					

			if ($this->campcab_id ==0){

				$sql = "Insert into MULTICHAT_CAMPANHA_CAB (campcab_nome, campcab_mensagem, campcab_criacao_usuario) values (?,?,?)";
			} else {
				$sql = "Update MULTICHAT_CAMPANHA_CAB set campcab_nome = ?, campcab_mensagem = ?, campcab_criacao_usuario = ? where campcab_id = ";
				$sql.= $this->campcab_id;
			}

		   try {
 				$statement = $this->db_conn->prepare($sql);
				$statement->bind_param("sss",$this->campcab_nome, $this->campcab_mensagem, $_SESSION['multichat_user']);
				$statement->execute();		

			} catch (mysqli_sql_exception $e) {
				echo $e->errno;
				die();
					throw $e;
			}	

			if ($this->db_conn->errno > 0){
				$mensagem= "Deu erro na atualização: "  . $this->db_conn->errno . "(" . $this->db_conn->error . ")";				
				$this->erros['tepeguei']=$mensagem;
				return;
			}
			$statement->close();

			if ($this->campcab_id == 0 ){
				$this->campcab_id = $this->db_conn->insert_id;
			}
		
			return;
		}

		public function carregar_convites(){

			$this->convites = array();
			$this->campdet_quantidade = 0;

			$sql = "select campdet_cliente_cpf, 
			        campdet_cliente_nome, 
			        campdet_cliente_email,
			        campdet_id,
			        count(conversa_id) as total_conversas
			        from MULTICHAT_CAMPANHA_DET left join
			            MULTICHAT_CONVERSA
			        	on (campdet_id = conversa_campdet_id and campdet_campcab_id = conversa_campcab_id)
			        where campdet_campcab_id = {$this->campcab_id} 
			        group by campdet_id";			

			$pesquisa=$this->db_conn->query($sql);			

			if ($this->db_conn->errno > 0 ){
				$this->erros[]="Erro no acesso ao cadastro de convites. " . $this->db_conn->error . " / " . $sql;
				return;
			}

			while($registro=$pesquisa->fetch_assoc())			
			{
				$this->convites[]=$registro;
			}
			
			
			$this->campdet_quantidade = count($this->convites);

		}

		public function valida_convite_avulso(){

			$this->erros = array();

			if (strlen($this->campdet_cliente_nome)==0){
				$this->erros['nome_nao_informado'] = "Nome do cliente não informado";
			}

			if (strlen($this->campdet_cliente_cpf) > 0){
				if (!$this->valida_cpf_cliente()){
					$this->erros['cpf_invalido'] = "CPF informado é inválido";
				}
			}

			if (strlen($this->campdet_cliente_email)==0){
				$this->erros['email_nao_informado']= "E-mail do cliente não informado";
			} else {
				if (!filter_var($this->campdet_cliente_email,FILTER_VALIDATE_EMAIL)){
					$this->erros['email_invalido']="O e-mail informado é inválido";
				}
				
			}

			return;
		}

		public function valida_cpf_cliente() {	   
	 
		 	$cpf = $this->campdet_cliente_cpf;

		    // Elimina possivel mascara
		    $cpf = preg_replace('[^0-9]', '', $cpf);
		    $cpf = str_pad(trim($cpf), 11, '0', STR_PAD_LEFT);
		     
		    // Verifica se o numero de digitos informados é igual a 11 
		    if (strlen($cpf) != 11) {
		        return false;
		    }

		    if ($cpf == 0){
		    	return false;
		    }
		    // Verifica se nenhuma das sequências invalidas abaixo 
		    // foi digitada. Caso afirmativo, retorna falso
	/*	    if ($cpf == '00000000000' || 
		        $cpf == '11111111111' || 
		        $cpf == '22222222222' || 
		        $cpf == '33333333333' || 
		        $cpf == '44444444444' || 
		        $cpf == '55555555555' || 
		        $cpf == '66666666666' || 
		        $cpf == '77777777777' || 
		        $cpf == '88888888888' || 
		        $cpf == '99999999999') {
		        return false;
			}
	*/	     // Calcula os digitos verificadores para verificar se o
		     // CPF é válido	    
		         
			for ($t = 9; $t < 11; $t++) {
	             
	            for ($d = 0, $c = 0; $c < $t; $c++) {
	                $d += $cpf{$c} * (($t + 1) - $c);
	            }
	            $d = ((10 * $d) % 11) % 10;
	            if ($cpf{$c} != $d) {
	                return false;
	            }
	        }
		 
		    return true;
	    }	

	    public function inclui_convite(){

	    	$sql = "insert into MULTICHAT_CAMPANHA_DET (";
	    	$sql.= "campdet_campcab_id,";
	    	$sql.= "campdet_cliente_nome,";
	    	$sql.= "campdet_cliente_cpf,";
	    	$sql.= "campdet_cliente_email,";
	    	$sql.= "campdet_cadastro_usuario";
	    	$sql.= ") values (";
	    	$sql.= "?,?,?,?,?)";

	    	$decl = $this->db_conn->prepare($sql);

	    	$decl->bind_param("issss",
	    			$this->campcab_id,
	    			$this->campdet_cliente_nome,
	    			$this->campdet_cliente_cpf,
	    			$this->campdet_cliente_email,
	    			$_SESSION['multichat_user']);

	

	    	$decl->execute();	    
	    	
	    	if ($this->db_conn->errno > 0){
	    		$this->erros['erro_na_inclusao'] = "Problemas na inclusão do convite. Status " . $this->db_conn->errno . " / " . $this->db_conn->error;
	    		return;
	    	}

	    	$decl->close();
	    	return;
	    }	

	    public function carrega_arquivo_csv(){
	    	
	    	$num_lidos = 0;
	    	$num_gravados = 0;
	    	$num_erros = 0;

			$arquivo = fopen($this->nome_arquivo_entrada, "r");
			$log = fopen($this->nome_arquivo_saida, "w");
			fwrite($log,"RESULTADO DA IMPORTAÇÃO DO ARQUIVO:\n");
			fwrite($log,"=====================================\n\n");
			$convites = fgetcsv($arquivo,150,";");

			while (!feof($arquivo)){
				
				$num_lidos +=1;
				$this->campdet_cliente_nome = $convites[0];
				$this->campdet_cliente_cpf = $convites[1];
				$this->campdet_cliente_email = $convites[2];
				
				$log_texto = "\n" . $num_lidos . ") ";
				$log_texto.= "NOME: " . $convites[0] . "; ";
				$log_texto.= "CPF: " . $convites[1] . "; ";
				$log_texto.= "EMAIL " . $convites[2] . " .";	

				$this->valida_convite_avulso();
				$mens_erro = "";

				if (count($this->erros)>0){
					$mens_erro = " - ERROS: ";
					foreach($this->erros as $erro){
						$mens_erro.= $erro . " - ";
					}
					$num_erros+=1;
				} else {
					$mens_erro = " - OK ";
					$this->inclui_convite();
					$num_gravados+=1;
				}

				fwrite($log,$log_texto . $mens_erro);
				$convites = fgetcsv($arquivo,150,";");
			}

			fwrite($log,"\n\n");
			fwrite($log,"Registros lidos :" . $num_lidos . "\n");
			fwrite($log,"Registros incluídos :" . $num_gravados . "\n");
			fwrite($log,"Registros com erros :" . $num_erros . "\n");
			fclose($arquivo);
			fclose($log);
			unlink($this->nome_arquivo_entrada);

	    	return;
	    }

	    public function enviar_emails_campanha(){

	    	$this->erros = array();

	    	//carrega dados da campanha
	    	$this->obtem_campanha_por_id();
	    	//obter convites
	    	$this->carregar_convites();

	    	if (count($this->convites)==0){
	    		$this->erros[]="Os convites para o envio da campanha ainda não foram cadastrados!";
	    		return;
	    	}

	    	foreach($this->convites as $convite){

	    		$this->set_campdet_id($convite['campdet_id']);
	    		$this->set_campdet_cliente_nome($convite['campdet_cliente_nome']);
	    		$this->set_campdet_cliente_email($convite['campdet_cliente_email']);

	    		$this->gerar_token_conversa();
	    		try{
	    			$this->enviar_email_convite();	  	  			
	    		} catch(Exception $e){
	    			$this->erros[] = "Erro ao enviar e-mail. Mensagem retornada: " . $e->getMessage();
	    		}
	    		$this->atualiza_conversa_token();
	    	}

	    	if (count($this->erros)	==0 ){
	    		$this->atualiza_envio_campanha();
	    	}	    	

	    	return;
	    }

	    public function enviar_email_convite(){


			$direcionamento = APP_FOLDER . "cliente_view.php";			

			if (!isset($this->Email)){
				$this->Email= new Enviar_email();
			}

			$this->Email->LimpaListaDeDestinatarios();
			$this->Email->setDestinatario($this->campdet_cliente_email);
			$this->Email->setTitulo("Temos uma proposta para você");
			$this->Email->setAssunto($this->campcab_nome);

			$mensagem = "<br>Sr(a) " . $this->campdet_cliente_nome . ",";
			
			$mensagem.="<br>" . $this->campcab_mensagem;

			//botão para o cliente entrar em contato
			$mensagem.="<br><form method='post' action='{$direcionamento}'>";
			$mensagem.="<input type='hidden' id='token' name='token' value='{$this->campdet_token_conversa}'>";
			$mensagem.="<button type='submit' style='margin-top:25px;padding:12px 10px; background-color:#a94442;border-radius:20px;color:white;text-decoration:none;font-weight:bold; cursor:pointer;'>QUERO NEGOCIAR AGORA!</button></form>";

			$this->Email->setMensagem($mensagem);		
			$this->Email->formata_mensagem();
			$this->Email->enviar();
		
		}

		private function gerar_token_conversa(){

			$chave = "cp" . $this->campcab_id . "cv" . $this->campdet_id;
			$this->campdet_token_conversa = md5($chave);
		}

		private function atualiza_conversa_token(){

			$sql = "update MULTICHAT_CAMPANHA_DET set campdet_token_conversa= ? where campdet_id = ? and campdet_campcab_id = ?";
			$decl = $this->db_conn->prepare($sql);
			$decl->bind_param("sii", $this->campdet_token_conversa, $this->campdet_id, $this->campcab_id);
			$decl->execute();

			if ($this->db_conn->errno > 0){
				$this->erros[]="Erro na atualização do convite. Status " . $this->db_conn->errno . ", " . $this->db_conn->error;
				return;
			}

			$decl->close();
			return;
		}

		private function atualiza_envio_campanha(){

			$this->campcab_envio_data = date("Y-m-d H:i:s",time());
			$this->campcab_envio_usuario = $_SESSION['multichat_user'];
			$this->campcab_status = 1;

			$sql = "update MULTICHAT_CAMPANHA_CAB set campcab_envio_data = ?, campcab_envio_usuario = ?, campcab_status = ? where campcab_id = ?";

			$decl = $this->db_conn->prepare($sql);
			$decl->bind_param("ssii", $this->campcab_envio_data, $this->campcab_envio_usuario, $this->campcab_status, $this->campcab_id);
			$decl->execute();

			if ($this->db_conn->errno > 0){
				$this->erros[]="Erro na atualização do convite. Status " . $this->db_conn->errno . ", " . $this->db_conn->error;
				return;
			}

			$decl->close();

		}

		public function obter_conversa_por_token(){

			$this->campdet_id = 0;
			$this->campcab_id = 0;
			$this->campdet_cliente_nome = "";
			$this->campdet_cliente_cpf = "";
			$this->campdet_cliente_email = "";

			$sql="select * from MULTICHAT_CAMPANHA_DET where campdet_token_conversa = '{$this->campdet_token_conversa}'";

			$resultado=$this->db_conn->query($sql);

			if ($this->db_conn->errno > 0){
				echo "Erro no acesso ao banco de dados de convites. ";
				echo "<br>Código: {$this->db_conn->errno}";
				echo "<br>Mensagem: {$this->db_conn->error}";
				die();
			}

			$informacoes = $resultado->fetch_assoc();

			if (count($informacoes)==0){
				return;
			}

			$this->campdet_id = $informacoes['campdet_id'];
			$this->campcab_id = $informacoes['campdet_campcab_id'];
			$this->campdet_cliente_nome = $informacoes['campdet_cliente_nome'];
			$this->campdet_cliente_cpf = $informacoes['campdet_cliente_cpf'];
			$this->campdet_cliente_email = $informacoes['campdet_cliente_email'];

			return;
		}

		public function atualiza_campanha_conversa_id(){

			$this->erros = array();

			$sql = "Update MULTICHAT_CAMPANHA_DET set campdet_conversa_id = ? where campdet_id = ? and campdet_campcab_id = ?";
			$decl = $this->db_conn->prepare($sql);
			$decl->bind_param("iii",
								$this->campdet_conversa_id,
								$this->campdet_id,
								$this->campcab_id);
			$decl->execute();

			if ($this->db_conn->errno > 0){
				$this->erros[] = "Erro ao atualizar a campanha. Status " . $this->db_conn->errno . "/" . $this->db_conn->error;
				return;
			}

			$decl->close();
			return;
		}

		public function atualiza_cpf_convite(){

			$this->erros = array();

			$sql = "Update MULTICHAT_CAMPANHA_DET set campdet_cliente_cpf = ? where campdet_id = ? and campdet_campcab_id = ?";
			$decl = $this->db_conn->prepare($sql);
			$decl->bind_param("sii",
								$this->campdet_cliente_cpf,
								$this->campdet_id,
								$this->campcab_id);
			$decl->execute();

			if ($this->db_conn->errno > 0){
				$this->erros[] = "Erro ao atualizar o cpf no convite da campanha. Status " . $this->db_conn->errno . "/" . $this->db_conn->error;
				return;
			}

			$decl->close();
			return;
		}

		public function carrega_estatisticas_dashboard_gerente(){

			$this->erros = array();
			$this->stat_novas_campanhas=0;
			$this->stat_convites_gerados=0;
			$this->stat_emails_enviados=0;
			$this->stat_conversas_geradas=0;

			//Pesquisa a quantidade de campanhas geradas no intervalo determinado.
			$sql = "select count(*) as quant from MULTICHAT_CAMPANHA_CAB where campcab_criacao_data ";
			$sql.= " between '" . $this->stat_data_inicial . "' and '" . $this->stat_data_final . "'";

			$Resultado=$this->db_conn->query($sql);

			if ($this->db_conn->error > 0){
				$this->erros[] = "Problemas na verificação de campanhas. Status " . $this->db_conn->errno . "/" . $this->db_conn->error;
				return;
			}

			if ($Resultado->num_rows > 0){
				$dados = $Resultado->fetch_assoc();
				$this->stat_novas_campanhas = $dados['quant'];
			}

			$Resultado->close();

			//Pesquisa a quantidade de convites gerados no intervalo estipulado
			$sql = "select count(*) as quant from MULTICHAT_CAMPANHA_DET where campdet_cadastro_data ";
			$sql.= " between '" . $this->stat_data_inicial . "' and '" . $this->stat_data_final . "'";

			$Resultado=$this->db_conn->query($sql);

			if ($this->db_conn->error > 0){
				$this->erros[] = "Problemas na verificação de convites. Status " . $this->db_conn->errno . "/" . $this->db_conn->error;
				return;
			}

			if ($Resultado->num_rows > 0){
				$dados = $Resultado->fetch_assoc();
				$this->stat_convites_gerados = $dados['quant'];
			}

			$Resultado->close();

			//Pesquisa a quantidade de emails enviados no intervalo estipulado
			$sql = "select count(campdet_id) as quant from MULTICHAT_CAMPANHA_DET right join MULTICHAT_CAMPANHA_CAB ";
			$sql.= " on campdet_campcab_id = campcab_id ";
			$sql.= " where campcab_envio_data ";
			$sql.= " between '" . $this->stat_data_inicial . "' and '" . $this->stat_data_final . "'";

			$Resultado=$this->db_conn->query($sql);

			if ($this->db_conn->error > 0){
				$this->erros[] = "Problemas na verificação de emails enviados. Status " . $this->db_conn->errno . "/" . $this->db_conn->error;
				return;
			}

			if ($Resultado->num_rows > 0){
				$dados = $Resultado->fetch_assoc();
				$this->stat_emails_enviados = $dados['quant'];
			}

			$Resultado->close();

			//Pesquisa a quantidade de conversas geradas pelos convites enviados
			$sql = "select count(campdet_id) as quant from MULTICHAT_CAMPANHA_DET right join MULTICHAT_CAMPANHA_CAB ";
			$sql.= " on campdet_campcab_id = campcab_id ";
			$sql.= " where campcab_envio_data ";
			$sql.= " between '" . $this->stat_data_inicial . "' and '" . $this->stat_data_final . "' ";
			$sql.= " and campdet_conversa_id is not NULL";

			$Resultado=$this->db_conn->query($sql);

			if ($this->db_conn->error > 0){
				$this->erros[] = "Problemas na verificação de conversas geradas baseadas em convites. Status " . $this->db_conn->errno . "/" . $this->db_conn->error;
				return;
			}

			if ($Resultado->num_rows > 0){
				$dados = $Resultado->fetch_assoc();
				$this->stat_conversas_geradas = $dados['quant'];
			}

			$Resultado->close();

			return;
		}

		public function exclui_convite(){

			$this->erros = array();

			//verifica se o convite a ser excluido pertence a uma campanha já enviada
			$this->obtem_convite_por_id();

			if ($this->campdet_id==0){
				$this->erros[] = "Convite não encontrado.";
				return;
			}

			$this->set_campcab_id($this->campdet_campcab_id);
			$this->obtem_campanha_por_id();

			if ($this->campcab_envio_data){
				$this->erros[] = "Campanha já enviada";
				return;
			}

			//efetua a exclusão do convite
			$sql = "delete from MULTICHAT_CAMPANHA_DET where campdet_id = ?";
			$decl = $this->db_conn->prepare($sql);
			$decl->bind_param("i", $this->campdet_id);
			$decl->execute();

			if ($this->db_conn->errno > 0){
				$this->erros[] = "Não foi possível excluir o convite. Status " . $this->db_conn->errno . "/" . $this->db_conn->error;
				return;
			}

			$decl->close();
		}

		public function conta_conversas(){

			$this->erros = array();
			$this->stat_conversas_geradas = 0;

			$sql = "select COALENSE(count(conversa_id),0) as quant from MULTICHAT_CONVERSA where conversa_campcab_id = " . 
			$this->campcab_id;

			if ($this->campdet_id > 0 ){
				$sql.= " and conversa_campdet_id = " . $this->campdet_id;
			}

			$resultado = $this->db_conn->query($sql);

			if ($this->db_conn->errno > 0){
				$this->erros[] = "Problemas ao contabilizar as conversas. Status " . $this->db_conn->errno . "/" 
					. $this->db_conn->error;
				return;
			}

			$quant = $resultado->fetch_assoc();
			$this->stat_conversas_geradas = $quant['quant'];

			$resultado->close();
			return;

		}

	}
?>