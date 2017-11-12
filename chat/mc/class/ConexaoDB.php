<?php

	class ConexaoDB{

		private $db_user;
		private $db_pass;
		private $db_host;
		private $db_database;
		private $db_conn;

		public function __construct($tipo_conexao){				

			if ($tipo_conexao == "127.0.0.1" || $tipo_conexao == "localhost"){
				$this->db_user = "multichatuser";
				$this->db_pass = "berimbau01";
				$this->db_host = "127.0.0.1";
				$this->db_database = "multichatdb";				
			} else {
				/* */
				$this->db_user = "admin_mchat";
				$this->db_pass = "hud3009";
				$this->db_host = "31.220.57.87";
				$this->db_database = "admin_mchat";
				/* */
				/*
				$this->db_user = "u584855138_mchat";
				$this->db_pass = "hud3009";
				$this->db_host = "mysql.hostinger.com.br";
				$this->db_database = "u584855138_mchat";
				*/
			}
			
			$this->db_conn = new mysqli($this->db_host,$this->db_user,$this->db_pass,$this->db_database);

			if ($this->db_conn->connect_errno !=0){
				echo "Erro no acesso ao banco de dados<br>";
				echo "<br>Código :" . $this->db_conn->connect_errno;
				echo "<br>Descrição :" . $this->db_conn->connect_error;
				echo "<br>Conexao :" . $tipo_conexao;
				die();
			}		
			return;	
		}

		public function erro_db(){

			if ($this->db_conn->errno !=0){
				echo "Erro no acesso ao banco de dados<br>";
				echo "Código :" . $this->db_conn->errno;
				echo "Descrição :" . $this->db_conn->error;
				die();
			}
			return;

		}

		public function conexao(){
			return $this->db_conn;
		}
	}
?>