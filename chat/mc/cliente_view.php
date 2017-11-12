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

	$conversa_cliente_nome = "";
	$conversa_cliente_email = "";
	$conversa_campcab_id = 0;
	$conversa_campdet_id = 0;	
	$conversa_cliente_cpf = "";

	if (isset($_POST['token'])){
		$Campanha->set_campdet_token_conversa($_POST['token']);
		$Campanha->obter_conversa_por_token();
		$conversa_cliente_nome = $Campanha->get_campdet_cliente_nome();
		$conversa_cliente_email = $Campanha->get_campdet_cliente_email();
		$conversa_cliente_cpf = $Campanha->get_campdet_cliente_cpf();
		$conversa_campcab_id = $Campanha->get_campcab_id();
		$conversa_campdet_id = $Campanha->get_campdet_id();
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
	<link rel="stylesheet" href="css/multichat_cliente_style.css">
	<link rel="stylesheet" href="css/multichat_operador_style.css">
	<script>

		var atualiza_fila;
		var atualiza_operadores_offline;
		var posicao_anterior_na_fila = 0;

		//div responsável por armazenar a conversa com o cliente
		var div_conversa;
		var controle_conversa_id; //Código da conversa em curso
		var controle_atualiza_conversa;
		var text_cliente_texto; //input responsável pela mensagem a ser enviada
		var conversa_data_inicio=""; //data em que a conversa foi iniciada, para formatação da tela
		var ultima_mensagem=0;

		function fechamento_pagina(){			

			conversa_id = document.getElementById("conversa_id");
			formData = new FormData();
			formData.append("conversa_id",conversa_id.value);

			$.ajax({
				url: "conversa_cliente_fechamento_pagina.php",
				method: "post",
				async: false,
				data: formData,
				processData: false,
				contentType: false,
				success: retorno_fechamento_pagina
			});
		
			return false;
		}

		function retorno_fechamento_pagina( data ){
			try{
				retorno = JSON.parse(data);
				if (retorno.status > 0){
					console.log ("não consegui gravar o retorno");
					console.log (data);
				}
			} catch (e){
				console.log(data);
			}
		}

		function cliente_finaliza_conversa(){

			clearInterval(controle_atualizacao_conversa);

			fechamento_pagina();
			agradece_conversa();
			return false;			

		}

		function agradece_conversa(){

			document.getElementById("cliente_mensagem").style.display = "none";	
			$("#cliente_ident div").css("display","none");
			document.getElementById("div_agradece_conversa").style.display = "block";
			document.getElementById("cliente_ident").style.display = "block";
			return;

		}

		//Demonstra a tela com os dados de contato para quem desiste do chat
		function desistencia(){
			tela_login = document.getElementById("tela_login");
			div_mensagem_offline = document.getElementById("div_mensagem_offline");
			tela_login.style.display="none";
			div_mensagem_offline.style.display = "none";
			despedida = document.getElementById("desistencia");
			despedida.style.display="block";
			return false;
		}

		function janela_mensagem_offline(){

			fila_de_espera = document.getElementById("fila_de_espera");
			fila_de_espera.style.display = "none";
			aviso_offline = document.getElementById("div_operadores_offline");
			aviso_offline.style.display = "none";
			div_mensagem_offline = document.getElementById("div_mensagem_offline");
			div_mensagem_offline.style.display = "block";
			clearInterval(atualiza_operadores_offline);
			clearInterval(atualiza_fila);
		}

		//valida as informações e envia o cliente para a fila de espera		
		function gravacao_na_fila(){			

			nome_cliente = document.getElementById("cliente_nome");
			email_cliente = document.getElementById("cliente_email");
			conversa_token = document.getElementById("conversa_token");
			
			if (nome_cliente.value.trim().length == 0){
				alert("Informe seu nome para iniciarmos o chat.");
				return false;
			}

			if (email_cliente.value.trim().length == 0){
				alert("Informe seu e-mail para iniciarmos o chat.");
				return false;
			}

			if (! valida_email(email_cliente.value)){
				alert("O email informado é inválido!");
				return false;
			}

			formData = new FormData();
			formData.append("cliente_nome",nome_cliente.value);
			formData.append("cliente_email",email_cliente.value);		
			formData.append("conversa_token",conversa_token.value);

			$.ajax({
				url: "conversa_inclui_fila.php",
				method:"post",				
				async: true,
				contentType: false,
				processData: false, 
				data: formData,
				success: retorno_gravacao_fila				
			});			
		
			return false;
		}

		//verifica o retorno da gravação do cliente na fila
		function retorno_gravacao_fila( data ){
			try{
				retorno = JSON.parse(data);
				if (retorno.status == 0){
					aguarde = document.getElementById("fila_de_espera");
					tela_login = document.getElementById("tela_login");
					conversa_id = document.getElementById("conversa_id");
					conversa_id.value = retorno.conversa;
					tela_login.style.display="none";
					aguarde.style.display="block";
					aciona_atualizacao_fila_de_espera();
				} else {
					var mensagem = "Não foi possível efetuar o seu cadastro na fila de espera. \n\n";
					for (x in retorno.erros){
						mensagem+= "- " + retorno.erros[x] + "\n";
					}
					alert(mensagem);
				}
			} catch (e){
				alert(data);
			}
			return false;
		}

		function aciona_atualizacao_fila_de_espera(){			
			setTimeout(atualiza_fila_de_espera,2000);
			atualiza_fila = setInterval(atualiza_fila_de_espera,5000);			
		}

		function atualiza_fila_de_espera(){			

			conversa_id = document.getElementById("conversa_id");
			formData = new FormData();
			formData.append("conversa_id",conversa_id.value);

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
					case 0: //cliente continua na fila de espera
						clientes_na_fila = document.getElementById("clientes_na_fila");
						clientes_na_fila.innerHTML = retorno.posicao_na_fila;
						if (retorno.posicao_na_fila != posicao_anterior_na_fila){
							posicao_anterior_na_fila = retorno.posicao_na_fila;
							notifyMe("Sua posição na fila: " + retorno.posicao_na_fila);
						}
						break;
					case 20: //não há operadores online para atender ao cliente
						operadores_offline();
						break;
					case 30: //conversa iniciada com algum operador
						inicia_conversa(retorno.conversa_id);
						break;
					default: //erro no processamento
						mensagem = "Problemas na verificação da fila. \n\n";
						for (x in retorno.erros){
							mensagem+= "\n- " + retorno.erros[x];}
						alert(mensagem);						
						break;						
				}
			} catch(e){
				alert(e);
				alert ( data );
			}
			return false;
		}

		//caso não tenham operadores online no momento, o sistema irá informar e enviar o usuário para a tela de mensagem offline.
		function operadores_offline(){
			aguarde = document.getElementById("fila_de_espera");
			aguarde.style.display = "none";
			aviso_offline = document.getElementById("div_operadores_offline");
			aviso_offline.style.display = "block";
			atualiza_operadores_offline = setTimeout(janela_mensagem_offline, 6000);			
		}

		function envia_mensagem_offline(){

			mensagem_offline = document.getElementById("mensagem_offline");

			if (mensagem_offline.value.length == 0){
				alert("Deixe a sua mensagem para que possamos retornar o contato!");
				mensagem_offline.focus();
				return false;
			}

			clearInterval(atualiza_fila);
			formData = new FormData();
			formData.append("conversa_id",document.getElementById("conversa_id").value);
			formData.append("mensagem_offline",mensagem_offline.value);

			$.ajax({
				url: "conversa_mensagem_offline.php",
				method: "post",
				data: formData,
				contentType: false,
				processData: false,
				async: false,
				success: retorno_mensagem_offline,
			});

			return false;

		}

		function retorno_mensagem_offline ( data ){

			try{
				retorno=JSON.parse(data);
				if (retorno.status == 0 ){
					div_mensagem_offline = document.getElementById("div_mensagem_offline");
					div_mensagem_offline.style.display = "none";
					div_agradece_mensagem = document.getElementById("div_agradece_mensagem");
					div_agradece_mensagem.style.display = "block";
				} else {
					mensagem = "NÃO FOI POSSÍVEL DEIXAR A MENSAGEM!\n";
					for (x in retorno.erros){
						mensagem+= "\n - " + retorno.erros[x];
					}
					alert(mensagem);
					desistencia();
				}
			} catch(e){
				alert(data);				
			}
			return false;
		}
		
		function notifyMe( mensagem ) {
		  // Let's check if the browser supports notifications
		  if (!("Notification" in window)) {
		    alert("This browser does not support desktop notification");
		  }

		  // Let's check whether notification permissions have already been granted
		  else if (Notification.permission === "granted") {
		    // If it's okay let's create a notification
		    var notification = new Notification(mensagem);
		  }

		  // Otherwise, we need to ask the user for permission
		  else if (Notification.permission !== 'denied') {
		    Notification.requestPermission(function (permission) {
		      // If the user accepts, let's create a notification
		      if (permission === "granted") {
		        var notification = new Notification("Hi there!");
		      }
		    });
		  }

		  // At last, if the user has denied notifications, and you 
		  // want to be respectful there is no need to bother them any more.
		}

		//validação do endereço de e-mail
		function valida_email(email){

			//verifica se o e-mail foi infomarmado para a função
			if (email.length == 0){
				return false;
			}

			//separa o e-mail em duas partes: usuário e domínio.
			var usuario = email.substr(0, email.indexOf("@"));
			var dominio = email.substr(email.indexOf("@")+1);

			//verifica se tanto o usuário quanto o domínio contém caracteres
			if (usuario.length ==0 || dominio.length== 0){
				return false;
			}

			//verifica se o usuário é válido
			if (usuario.search("@") != -1 || 
				usuario.search(" ") != -1 || 
				usuario.search(",") != -1 ||
				usuario.indexOf(".") == 0 ||
				usuario.lastIndexOf(".") == (usuario.length -1)){
				return false;
			}

			//verifica se o domínio é válido
			if (dominio.indexOf(".") < 1 ||
				dominio.lastIndexOf(".") == (dominio.length - 1) ||
				dominio.search("@") != -1 ||
				dominio.search(" ") != -1 ||
				dominio.search(",") != -1){
				return false;
			}

			return true;
		}

		//Inicia a conversa quando um operador selecionar o cliente na fila
		function inicia_conversa(conversa){
			tela_ident = document.getElementById("cliente_ident");
			tela_conversa = document.getElementById("cliente_mensagem");
			controle_conversa_id = conversa;
			tela_ident.style.display = "none";
			tela_conversa.style.display = "block";
			document.getElementById("div_texto_digitado").style.display="block";	

			var data_corrente = new Date();
			document.getElementById("data_cabec_conversa").innerHTML = formata_data(data_corrente);
			clearInterval(atualiza_fila);
			aciona_atualizacao_conversa();
			return;
		}

//acionamento da atualizacao da conversa com base na úlitma mensagem recebida
		function aciona_atualizacao_conversa(){	
			verifica_novas_mensagens();		
			controle_atualizacao_conversa = setInterval(verifica_novas_mensagens,3000);
		}

		function verifica_novas_mensagens(){

			var formData = new FormData();
			formData.append("ultima_mensagem",ultima_mensagem);
			formData.append("conversa_id",controle_conversa_id);

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
				if (retorno.status ==0 || retorno.status==30){
					for (x in retorno.mensagens){
						retorno_verifica_novas_mensagens_atualiza_div(retorno.mensagens[x]);
					}
					if (retorno.status==30){
						finalizacao_da_conversa();
					}
				} else {
					mensagem = "Não foi possível atualizar as mensagens \n";
					for (x in retorno.erros){
						mensagem += "\n - " . retorno.erros[x];
					}
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
			data_na_mensagem = "";
			

			if (data_formatada != conversa_data_inicio){
				data_na_mensagem = data_formatada + " " ;
//				var data_da_conversa = document.createElement("span");
//				data_da_conversa.classList.add("mensagem_data_corrente");
//				data_da_conversa.innerHTML = data_formatada;
//				div_conversa.appendChild(data_da_conversa);
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
			nova_mensagem.innerHTML = "<strong>" + mensagem.mensagem_remetente + ": </strong>" + mensagem.mensagem_texto + "<span class='mensagem_hora_corrente'>" + " - " + data_na_mensagem + horario_formatado +  "</span>";
			div_conversa.appendChild(nova_mensagem);					
			
			div_conversa.scrollTop= div_conversa.scrollHeight;

		}

		function finalizacao_da_conversa(){

			clearInterval(controle_atualizacao_conversa);
			conversa_finalizada = document.createElement("div");
			conversa_finalizada.classList.add("conversa_finalizada");
			texto_fim = document.createElement("span");
			texto_fim.innerHTML = "Conversa finalizada";
			conversa_finalizada.appendChild(texto_fim);
			div_conversa.appendChild(conversa_finalizada);
			document.getElementById("div_texto_digitado").style.display="none";	
			setTimeout(agradece_conversa,2000);
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
			formData.append("conversa_id",controle_conversa_id);
			formData.append("remetente",2); //indica que a mensagem está sendo enviada pelo CLIENTE;
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
				if (retorno.status ==0 ){
					verifica_novas_mensagens();
				} else {
					mensagem = "Problemas ao enviar a mensagem. \n";
					for (x in retorno.erros){
						mensagem += "\n - " + retorno.erros[x];
					}
					alert(mensagem);
				}
			} catch (e){
				alert(e);
				alert(data);
			}
			return false;
		}


		$(document).ready(function(){
			div_conversa = document.getElementById("conversa");		
			text_operador_texto = document.getElementById("operador_texto");		
		});

	</script>
</head>

<body onUnload="fechamento_pagina();">

<div id="cliente_ident" class="container cliente_ident_externo">
   
   <div id="tela_login" class="cliente_ident_interno col-md-4 col-md-offset-4 col-xs-12 col-xs-offset-0">
   		<span>CONECTA-MULTI</span>
   		<p>Por favor, preencha as informações abaixo.<br>Em breve, um de nossos operadores irá atendê-lo.</p>
   		<form>
   			<input type=hidden name="conversa_token" id="conversa_token"
   				<?php 
   					if (isset($_POST['token'])){
   						echo "value=" . $_POST['token'];
   					}
   				?>
   			>
   			<input type=hidden name="conversa_id" id="conversa_id">
   		   <div class="input-group">
	   		   <span class="input-group-addon">
	   		   		<i class="glyphicon glyphicon-user"></i>
	   		   </span>
	   		   <input type="text" class="form-control" id="cliente_nome" name="cliente_nome" 
	   		   <?php if (strlen(trim($conversa_cliente_nome))>0) {echo "value=". $conversa_cliente_nome;} ?>
	   		   placeholder="Informe seu nome">
   		   </div>	
   		   <div class="input-group">
	   		   <span class="input-group-addon">
	   		   		<i class="glyphicon glyphicon-envelope"></i>
	   		   </span>
	   		   <input type="email" class="form-control" id="cliente_email" name="cliente_email" 
	   		   <?php if (strlen(trim($conversa_cliente_email))>0) {echo "value=". $conversa_cliente_email;} ?>
	   		   placeholder="Informe seu email">
   		   </div>
   		   <div class="cliente_ident_botoes">
   				<button class="btn btn-default" type="button" onclick="desistencia();">Sair</button>
   				<button class="btn btn-primary" type="button" onclick="gravacao_na_fila();">Acessar</button>
   		   </div>
   		</form>
   </div>
   <div id="desistencia" class="cliente_ident_interno col-md-4 col-md-offset-4 col-xs-12 col-xs-offset-0">
   		<span>CONECTA-MULTI</span>
   		<p>Aguardamos o seu retorno através de um de nossos canais de atendimento abaixo. Obrigado!<br><br>Central de atendimento:<br>(De segunda à sexta, das 8 as 20 horas)<br>(11)9-5813-0042<br><br>Ou através do site<br><a href="www.conecta-multi.com.br">www.conecta-multi.com.br</a></p>   		
   </div>
   <div id="fila_de_espera" class="cliente_ident_interno col-md-4 col-md-offset-4 col-xs-12 col-xs-offset-0">
   		<span>CONECTA-MULTI</span>
   		<p>Aguarde um instante para ser atendido...</p>   	
   		<p>Sua posição na fila é a de número <span id="clientes_na_fila">0</span></p>
   		<div id="div_loading">
   			<img id="loading" src="img/loading.gif">
   		</div>
   		<div class="div_centraliza">
	   		<button class="btn btn-primary" id="btn_janela_offline" onclick="janela_mensagem_offline();">
	   			Sair da fila e deixar mensagem
	   		</button>
   		</div>
   </div>
      <div id="div_mensagem_offline" class="cliente_ident_interno col-md-4 col-md-offset-4 col-xs-12 col-xs-offset-0">
   		<span>CONECTA-MULTI</span>
   		<p>Deixe a sua mensagem e responderemos assim que possível através do e-mail informado!</p>   	
   		<form>
   		<textarea class="form-control" id="mensagem_offline" name="mensagem_offline" rows=5></textarea>
   		</form>
   		<div class="cliente_offline_botoes">
	   		<button class="btn btn-default" id="btn_sair_offline" onclick="desistencia();">
	   			Cancelar
	   		</button>
	   		<button class="btn btn-primary" id="btn_enviar_offline" onclick="envia_mensagem_offline();">
	   			Enviar
	   		</button>
   		</div>
   </div>
   <div id="div_agradece_mensagem" class="cliente_ident_interno col-md-4 col-md-offset-4 col-xs-12 col-xs-offset-0">
   		<span>CONECTA-MULTI</span>
   		<p>Obrigado por sua mensagem!<br>Caso precise falar conosco antes do nosso retorno, queira por gentileza utilizar um dos canais de atendimento abaixo. Obrigado!<br><br>Central de atendimento:<br>(De segunda à sexta, das 8 as 20 horas)<br>(11)9-5813-0042<br><br>Ou através do site<br><a href="www.conecta-multi.com.br">www.conecta-multi.com.br</a></p>   		
   </div>
    <div id="div_operadores_offline" class="cliente_ident_interno col-md-4 col-md-offset-4 col-xs-12 col-xs-offset-0">
   		<span>CONECTA-MULTI</span>
   		<p>No momento não temos operadores disponíveis...<br>Por favor, deixe uma mensagem, e responderemos em breve. <br> Obrigado.<br><br></p>   		
   </div>
   <div id="div_agradece_conversa" class="cliente_ident_interno col-md-4 col-md-offset-4 col-xs-12 col-xs-offset-0">
   		<span>CONECTA-MULTI</span>
   		<p>Obrigado por falar conosco!<br>Uma cópia da conversa foi enviada para o seu e-mail.<br>Caso precise falar conosco novamente, queira por gentileza utilizar um dos canais de atendimento abaixo. Obrigado!<br><br>Central de atendimento:<br>(De segunda à sexta, das 8 as 20 horas)<br>(11)9-5813-0042<br><br>Ou através do site<br><a href="www.conecta-multi.com.br">www.conecta-multi.com.br</a></p>   		
   </div>
   <div class="col-md-4"></div>
</div>

<div id="cliente_mensagem" class="container cliente_mensagem_externo">
	
	<div class="cliente_ident_interno col-md-8 col-md-offset-2 col-xs-12 col-xs-offset-0">
		<span>CONECTA-MULTI</span>
		<div id="div_data_cabec_conversa"><span id="data_cabec_conversa"></span></div>
   		<div id="conversa">
		</div>
		<div id="div_texto_digitado">
			<div class="input-group">
				<input type="text" id="operador_texto" class="form-control" onkeydown="if (event.keyCode == 13 || event.which == 13) {texto_digitado(); return false;}">
				<span class="input-group-btn">
				<button class="btn btn-success" type="button" onclick="texto_digitado(); return false;">
					+
				</button>
				</span>
			</div>	     				    					
		</div> 
		<div>
			<button id="btn_finaliza_conversa" class="btn col-md-6 col-md-offset-3 col-xs-12 col-xs-offset-0"
			onclick="cliente_finaliza_conversa();">Finalizar a conversa e me enviar cópia por e-mail</button>
		</div>
	</div>
	
</div>

</body>

</html>