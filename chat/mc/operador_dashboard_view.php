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

	if (!$Usuario->usuario_logado() ||
		$_SESSION["multichat_nivel"]!=2){
		header("location: logout.php");
	}
?>

<html>

<head>
	<title>MULTICHAT - Conectando pessoas</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width", initial-scale="1">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.css">	
	<script src="jquery\jquery-3.2.1.js"></script>
	<script src="bootstrap/js/bootstrap.js"></script>		
	<script src="js\funcoes_auxiliares.js"></script>
	<script src="js\notifyme.js"></script>
	<link rel="stylesheet" href="css/multichat_style.css">
	<link rel="stylesheet" href="css/multichat_operador_style.css">
	<script>

		//quadros
		var box_conversa;
		var box_fila_vazia;
		var box_fila_atendimento;
		var box_operador_offline;

		//Icone de status do usuário
		var operador_icone;
		var link_alt_status;
		

		//indicador de operador online
		var operador_online = false;

		//controle de atualização automática da fila de espera
		var atualiza_fila;

		//controle de número de clientes na fila
		var fila_num_clientes;
		var fila_num_clientes_anterior;

		//div responsável por armazenar a conversa com o cliente
		var div_conversa;
		var conversa_id=0; //Código da conversa em curso
		var text_operador_texto; //input responsável pela mensagem a ser enviada
		var conversa_data_inicio=""; //data em que a conversa foi iniciada, para formatação da tela

		//controle das mensagens recebidas
		var controle_atualizacao_conversa;
		var ultima_mensagem=0;
		var cliente_nome;

		function operador_menu_click(){
			$("#operador_menu").toggle();
		}

		//ao clique no ícone de status, alternar o status do operador entre online/offline
		function alterar_status_operador(){
			if (operador_online){
				if (conversa_id !=0){
					alert("Não é possível ficar offline durante conversas ativas!");
					return false;
				}
				tornar_operador_offline();
			} else {
				tornar_operador_online();
			}
		}

		//indica na tabela de usuários que ele está disponível para atendimento
		function tornar_operador_online(){
			
			if (operador_online) return;			

			operador_online = true;

			status_operador_online(1);

			esconde_todos_os_boxes();
			box_fila_vazia.style.display = "block";
			atualiza_controles_operador_online();
			aciona_atualizacao_fila_de_espera();
		}

		function atualiza_controles_operador_online(){
			operador_icone.classList.remove("operador_offline");
			operador_icone.classList.add("operador_online");
			link_alternar_status.innerHTML = "Ficar offline";
			operador_online=true;
		}

		//indica na tabela de usuários que o operador não está disponível para atendimento
		function tornar_operador_offline(){
			
			if (!operador_online) return;			

			operador_online = false;

			status_operador_online(0);

			esconde_todos_os_boxes();
			box_operador_offline.style.display="block";					
			operador_icone.classList.remove("operador_online");
			operador_icone.classList.add("operador_offline");
			link_alternar_status.innerHTML = "Ficar online";
			clearInterval(atualiza_fila);
		}

		function status_operador_online( operador_online ){

			var formData = new FormData();

			formData.append("usuario_status", operador_online);

			$.ajax({

				url: "operador_altera_status.php",
				method: "post",
				processData: false,
				contentType: false,
				data: formData,
				async: false,
				success: retorno_status_operador_online
			});
		}

		//retorno da função ajax que altera o status do operador no banco de dados
		function retorno_status_operador_online( data ){
			
			try {
				retorno = JSON.parse(data);
				switch(retorno.status){
					case 0:
						break;
					case 100:
						window.location.replace("logout.php");
						break;
					default:
						mensagem = "Problemas no tratamento do status do operador. \n";
						for (x in retorno.erros){
							mensage += "\n - " + retorno.erros[x];
						}
						alert(mensagem);
				}
			} catch (e){
				alert(data);
			}

			return false;
		}

		function esconde_todos_os_boxes(){
			box_conversa.style.display = "none";
			box_fila_vazia.style.display = "none";
			box_fila_atendimento.style.display = "none";
			box_operador_offline.style.display = "none";			
			return;
		}

		function aciona_atualizacao_fila_de_espera(){			
			setTimeout(atualiza_fila_de_espera,500);
			atualiza_fila = setInterval(atualiza_fila_de_espera,10000);			
		}

		function atualiza_fila_de_espera(){			

			conversa_id = 0;
			formData = new FormData();
			formData.append("conversa_id",conversa_id);

			$.ajax({
				url: "conversa_cliente_pesquisa_espera.php",
				method: "post",
				async: false,
				data: formData,
				processData: false,
				contentType: false,
				success: atualiza_tela_fila_espera
			});
		
			return false;
		}

		function atualiza_tela_fila_espera (data ){
			try{
				retorno = JSON.parse(data);
				switch(retorno.status){
					case 0:
						atualiza_fila_espera_display();
						break;
					case 20:
						operadores_offline();
						break;
					case 100:
						window.location.replace("logout.php");
						break;
					default:
						mensagem = "Problemas na verificação da fila. \n\n";
						for (x in retorno.erros){
							mensagem+= "\n- " + retorno.erros[x];}
						alert(mensagem);									
				}
			} catch(e){
				alert(e);
				alert ( data );
			}
			return false;
		}

		function atualiza_fila_espera_display(){

			if (retorno.posicao_na_fila==0){
				box_fila_atendimento.style.display = "none";
				box_fila_vazia.style.display = "block";						
			} else {
				box_fila_vazia.style.display = "none";						
				box_fila_atendimento.style.display = "block";
				fila_num_clientes.innerHTML = retorno.posicao_na_fila;
				if (retorno.posicao_na_fila != fila_num_clientes_anterior){
					if (retorno.posicao_na_fila > fila_num_clientes_anterior){
						notifyMe("Novos clientes na fila de espera!");	
					}
					fila_num_clientes_anterior = retorno.posicao_na_fila;							
				}											
			}								
			return;

		}

		//chamar o próximo cliente da fila para o chat
		function chamar_proximo_cliente(){
			$.ajax({
				url: "operador_chamar_proximo_cliente.php",
				method: "post",
				async: false,
				success: retorno_chamar_proximo_cliente
			});
		}

		function retorno_chamar_proximo_cliente( data ){
			try{
				retorno = JSON.parse(data);
				switch(retorno.status){
					case 0:
						aciona_conversa_cliente(retorno.conversa_id, retorno.cliente_nome, retorno.cliente_cpf);
						break;
					case 100:
						window.location.replace("logout.php");
						break;
					default:
						mensagem = "Não foi possível iniciar a conversa com o cliente. \n";
						for (x in retorno.erros){
							mensagem+= "\n- " + retorno.erros[x];}
						alert(mensagem);									
				}
			} catch (e){				
				alert(e);
				alert(data);
			}
			return false;
		}

		//acionamento da conversa com o próximo cliente
		function aciona_conversa_cliente( param_conversa_id,param_nome_cliente, param_cpf_cliente){

			conversa_id = param_conversa_id;

			var cpf_formatado;

			esconde_todos_os_boxes();
			document.getElementById("div_operador_texto").style.display="block";			
			div_conversa.innerHTML = "";
			cliente_nome.innerHTML = param_nome_cliente;
			
			if (Number(param_cpf_cliente)==0){
				cpf_formatado = "CPF não informado"
			} else {
				cpf_formatado = param_cpf_cliente;
			}
			document.getElementById("cpf_cliente").innerHTML = cpf_formatado;
			box_conversa.style.display = "block";
			clearInterval(atualiza_fila);

			aciona_atualizacao_conversa();
		}

		//acionamento da atualizacao da conversa com base na úlitma mensagem recebida
		function aciona_atualizacao_conversa(){	
			verifica_novas_mensagens();		
			controle_atualizacao_conversa = setInterval(verifica_novas_mensagens,3000);
		}

		function verifica_novas_mensagens(){

			var formData = new FormData();
			formData.append("ultima_mensagem",ultima_mensagem);
			formData.append("conversa_id",conversa_id);

			$.ajax({
				url: "conversa_atualiza.php",
				contentType: false,
				processData: false,
				method: "post",
				data: formData,
				async:false,
				success: retorno_verifica_novas_mensagens
			});

		}

		function retorno_verifica_novas_mensagens( data ){
			try {
				retorno = JSON.parse(data);
				switch(retorno.status){
					case 0:
						for (x in retorno.mensagens){
							retorno_verifica_novas_mensagens_atualiza_div(retorno.mensagens[x]);
						}
						break;
					case 30:
						for (x in retorno.mensagens){
							retorno_verifica_novas_mensagens_atualiza_div(retorno.mensagens[x]);
						}
						marca_finalizacao_da_conversa();						
						finaliza_conversa();
						break;
					case 100:
						window.location.replace("logout.php");
						break;
					default:
						mensagem = "Não foi possível atualizar as mensagens \n";
						for (x in retorno.erros){
							mensagem+= "\n- " + retorno.erros[x];}
						alert(mensagem);									
				}
			} catch (e){
				alert(e);
				alert(data);
			}
			return false;
		}

		function retorno_verifica_novas_mensagens_atualiza_div(mensagem){

			ultima_mensagem = mensagem.mensagem_id;

			var horario = new Date(mensagem.mensagem_cadastro_data);
			var data_formatada;
			
			data_formatada = formata_data(horario);
			horario_formatado = formata_horario(horario);	
			

			if (data_formatada != conversa_data_inicio){
				var data_da_conversa = document.createElement("span");
				data_da_conversa.classList.add("mensagem_data_corrente");
				data_da_conversa.innerHTML = data_formatada;
				div_conversa.appendChild(data_da_conversa);
				conversa_data_inicio = data_formatada;
			}
			
			var classe_cabec;
			var classe_mensagem;			

			if (mensagem.mensagem_remetente_tipo=="1"){
				classe_cabec = "mensagem_operador_cabec";
				classe_mensagem = "mensagem_operador_texto";	
			}			
			else {
				classe_cabec = "mensagem_cliente_cabec";
				classe_mensagem = "mensagem_cliente_texto";				
			}

			var nova_mensagem = document.createElement("span");			
			nova_mensagem.classList.add(classe_mensagem);
			nova_mensagem.innerHTML = "<strong>" + mensagem.mensagem_remetente + ": </strong>" + mensagem.mensagem_texto + "<span class='mensagem_hora_corrente'>" + " - " + horario_formatado +  "</span>";
			div_conversa.appendChild(nova_mensagem);					
			
			div_conversa.scrollTop= div_conversa.scrollHeight;
			return;

		}

		function marca_finalizacao_da_conversa(){

			clearInterval(controle_atualizacao_conversa);			
			conversa_finalizada = document.createElement("div");
			conversa_finalizada.classList.add("conversa_finalizada");
			texto_fim = document.createElement("span");
			texto_fim.innerHTML = "Conversa finalizada";
			conversa_finalizada.appendChild(texto_fim);
			div_conversa.appendChild(conversa_finalizada);
			document.getElementById("div_operador_texto").style.display="none";	
			return;	

		}

		function texto_digitado(){

			var texto = text_operador_texto.value;
			texto.trim();
			if (texto.length ==0){
				return false;
			}

			text_operador_texto.value="";

			var formData = new FormData();
			formData.append("conversa_id",conversa_id);
			formData.append("remetente",1); //indica que a mensagem está sendo enviada pelo operador;
			formData.append("mensagem",texto);
			
			$.ajax({
				url: "conversa_grava_mensagem.php",
				data: formData,
				method: "post",
				contentType: false,
				processData: false,
				async: false,
				success: retorno_texto_digitado
			});
			return false;
		}

		function retorno_texto_digitado( data ){

			try {
				retorno = JSON.parse(data);
				switch(retorno.status){
					case 0:
						verifica_novas_mensagens();
						break;
					case 100:
						window.location.replace("logout.php");
						break;
					default:
						mensagem = "Problemas ao enviar a mensagem. \n";
						for (x in retorno.erros){
							mensagem+= "\n- " + retorno.erros[x];}
						alert(mensagem);									
				}
			} catch (e){
				alert(e);
				alert(data);
			}
			return false;
		}

		function verifica_status_operador(){

			$.ajax({
				url: "operador_verifica_status.php",
				method: "post",				
				contentType: false,
				processData: false,
				async: false,
				success: retorno_verifica_status_operador
			});
		}

		function retorno_verifica_status_operador( data ){

			try {
				retorno = JSON.parse(data);
				switch(retorno.status){
					case 0:
						if (retorno.online == 0 ){
							box_operador_offline.style.display="block";						
						} else {
							if (retorno.conversa == 0){
								operador_online = false; //flag alterado apenas para a correta execução da função "tornar_operador_online"
								tornar_operador_online();
							} else {
								atualiza_controles_operador_online();
								aciona_conversa_cliente( retorno.conversa,retorno.nome_cliente, retorno.cpf_cliente);
							}
						}						
						break;
					case 100:
						window.location.replace("logout.php");
						break;
					default:
						mensagem = "Não foi possível identificar o status do operador! \n";
						for (x in retorno.erros){
							mensagem+= "\n- " + retorno.erros[x];}
						alert(mensagem);									
				}
			} catch(e){
				alert(e);
				alert(data);
			}
			return false;
		}

		//Rotina para finalizar a conversa com o cliente, que fará com que o sistema desassocie a mesma do operador e envie um e-mail para o cliente com o que foi discutido
		function finaliza_conversa(){

			var formData = new FormData();
			formData.append("conversa_id",conversa_id);			

			$.ajax({
				url: "conversa_finaliza.php",
				method: "post",
				data: formData,
				contentType: false,
				processData: false,
				async: false,
				success: retorno_finaliza_conversa
			});

			return false;
		}

		function retorno_finaliza_conversa( data ){
			try {
				retorno = JSON.parse(data);
				switch(retorno.status){
					case 0:
						conversa_id =0;
						operador_online = false; //flag alterado apenas para a correta execução da função "tornar_operador_online"
						tornar_operador_online();
						break;
					case 100:
						window.location.replace("logout.php");
						break;
					default:
						mensagem = "Não foi possível atualizar o status da conversa. \n";
						for (x in retorno.erros){
							mensagem+= "\n- " + retorno.erros[x];}
						alert(mensagem);									
				}
			} catch (e){
				alert(e);
				alert(data);
			}
			return false;
		}

		function ativa_associacao_cpf(){

			if (conversa_id == 0){
				alert("Associação de CPF permitida apenas para conversas ativas.");
				return false;
			}

			$('#modal_associa_cpf').modal('show');
		}

		function associa_cpf(){

			var cliente_cpf = document.getElementById("input_cliente_cpf");
			var cliente_cpf_valor = cliente_cpf.value;
			cliente_cpf_valor.trim();

			if (cliente_cpf_valor.length ==0){
				alert("Informe o CPF do cliente!");
				cliente_cpf.focus();
				return false;
			}

			var formData = new FormData();
			formData.append("cliente_cpf",cliente_cpf_valor);
			formData.append("conversa_id",conversa_id);

			$.ajax({
				url: "operador_associa_cpf.php",
				method: 'post',
				data: formData,
				contentType: false,
				processData: false,
				async: false,
				success: associa_cpf_retorno
			});

			return false;
		}

		function associa_cpf_retorno ( data ){
			try {
				retorno = JSON.parse(data);
				switch(retorno.status){
					case 0:
						document.getElementById("cpf_cliente").innerHTML = retorno.cliente_cpf;
						$('#modal_associa_cpf').modal('hide');
						break;
					case 100:
						window.location.replace("logout.php");
						break;
					default:
						mensagem = "NÃO FOI POSSÍVEL ASSOCIAR O CPF AO CLIENTE! \n";
						for (x in retorno.erros){
							mensagem+= "\n- " + retorno.erros[x];}
						alert(mensagem);									
				}
			} catch (e){
				alert(e);
				alert(data);
			}

			return false;
		}

		//procedimentos iniciais, executados quando o documento já está composto.
		$(document).ready(function(){

			box_conversa = document.getElementById("box_conversa");
			box_fila_vazia = document.getElementById("box_fila_vazia");
			box_fila_atendimento = document.getElementById("box_fila_atendimento");
			box_operador_offline = document.getElementById("box_operador_offline");
			esconde_todos_os_boxes();

			operador_icone = document.getElementById("operador_icon");
			link_alt_status = document.getElementById("link_alternar_status");
			fila_num_clientes = document.getElementById("operador_fila_num_clientes");
			div_conversa = document.getElementById("conversa");
			cliente_nome = document.getElementById("nome_cliente");
			text_operador_texto = document.getElementById("operador_texto");	
			

			

			verifica_status_operador();
			
		});

	</script>
	
</head>

<body>

<!--Modal para mensagens de erro -->
<div class="modal fade" id="modal_mensagem_erro" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <span>CONECTA-MULTI</span>
      <p id="mensagem_erro"></p>
      	<div>
      		<button type="button" class="btn btn-danger btn-small" data-dismiss="modal">OK</button>		        			
      	</div>
    </div>
  </div>
</div>

<!--Modal para associação do CPF -->
<div class="modal fade md_single_input" id="modal_associa_cpf" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <span class="logo">CPF DO CLIENTE:</span>
      <div class="container-fluid">
      	<input id="input_cliente_cpf" type="text" class="form-control texto_ao_centro">
      </div>
      <div class="md_botoes">      		
      		<button type="button" class="btn btn-default btn-small" data-dismiss="modal">Cancelar</button>		        			
      		<button type="button" class="btn btn-success btn-small" onclick="associa_cpf();">Aceitar</button>		        			
      </div>
    </div>
  </div>
</div>

<div id="atendente_dashboard_externo" class="container operador_dashboard_externo">
   <div class="col-md-2"></div>
   
   <div id="operador_dashboard" class="operador_dashboard_interno col-md-8">

   		<div id="operador_barra_superior" class="row">
   			<span class="operador_barra_superior_logo col-md-6">CONECTA-MULTI</span>   		
   			<div class="col-md-3 operador_icones">
 	
   			</div>
   			<div class="col-md-3 operador_ident_usuario">
   				<div class="alinha_usuario">
	   				<i id="operador_icon" class="glyphicon glyphicon-user operador_offline" onclick="alterar_status_operador();"></i>   				
	   				<span id="operador_usuario">Usuário</span>	 
	   				<button id="btn_operador_menu" onclick="operador_menu_click();"><i class="caret"></i></button>
	   				<div id="operador_menu">					  	   					   				
					  <ul>
					  	<li style="display:none;"><a id="link_alternar_status" href="javascript:alterar_status_operador();">ONLINE</a></li>					  	
					    <li><a href="altera_senha_view.php">Alterar senha</a></li>
					    <li class="divider"></li>
					    <li><a href="logout.php">Sair do sistema</a></li>					    
					 </ul>

					</div>   				
   				</div>
   			</div>
   
   		</div>

   		<div id="operador_barra_superior" class="row oper_nav">
   			<ul>
   				<li><a id='link_associa_cpf' href="javascript:ativa_associacao_cpf();">ASSOCIAR CPF</a></li>   				
   				<li><a id="link_finaliza_conversa" href="javascript:finaliza_conversa();">FINALIZAR CONVERSA</a></li>   				
   			</ul>
   		</div>
   		<div class="row">
   			<div class="col-md-12 height_box_conversa">
   				<div id="box_conversa">
		   			<div id="operador_cliente_ident">
		   				<span>Cliente: 
		   					<span id="nome_cliente"> Agisvaildo Coimbra dos Santos</span> (
		   					<span id="cpf_cliente">123.456.789-00</span>)
		   				</span>
		   			</div>   				
	   				<div id="conversa">
	   				</div>
	   				<div id="div_operador_texto">
	   					<div class="input-group">
      						<input type="text" id="operador_texto" class="form-control" onkeydown="if (event.keyCode == 13 || event.which == 13) {texto_digitado(); return false;}">
      						<span class="input-group-btn">
        						<button class="btn btn-success" type="button" onclick="texto_digitado(); return false;">
        							+
        						</button>
      						</span>
    					</div>	     				    					
	   				</div>  
   				</div>
   				<div id="box_operador_offline" class="height_box_conversa box_externo">
   					<div class="box_interno">
   						<h1>Você está offline. <br>Fique online para começar!</h1>   					
   						<button id="btn_ficar_online" class="btn btn-success" onclick="tornar_operador_online();">Ficar online</button>
   					</div>
   				</div>
   				<div id="box_fila_vazia"  class="height_box_conversa">
 					<div class="box_interno">
   						<h1>Neste momento<br> não há clientes <br>aguardando atendimento!</h1>   					   						
   					</div>   					
   				</div>
   				<div id="box_fila_atendimento" class="height_box_conversa">
   					<div class="box_interno">
   						<h1>Neste momento há<br><span id="operador_fila_num_clientes">8</span> cliente(s)<br> aguardando atendimento!</h1> 		
   						<button class="btn btn-success" onclick="chamar_proximo_cliente();">Chamar próximo cliente</button>
   					</div>
   				</div>
 
   			</div>
   		</div>
   </div>  
   <div class="col-md-2"></div>
</div>

</body>

</html>