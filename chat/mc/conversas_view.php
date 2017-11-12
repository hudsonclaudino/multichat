<?php
	include_once "config.php";
	//Classe para a conexão com o banco de dados	
	include_once "class/ConexaoDB.php";
	//Classe para a pesquisa de usuários do sistema
	include_once "class/Usuario.php";
	//Classe para acesso aos dados das campanhas
	include_once "class/Campanha.php";
	//Classe para acesso aos dados das conversas
	include_once "class/Conversa.php";

	//Criação das instâncias das classes
	$Db_con = new ConexaoDB(ROOT);
	$Usuario = new Usuario($Db_con->conexao());
	$Campanha = new Campanha($Db_con->conexao());
	$Conversa = new Conversa($Db_con->conexao());

	//Verifica se o usuário está logado no sistema, para evitar entradas diretas. Caso negativo, será direcionado para a tela de login
	//Verifica se o nível de acesso do usuário é o de gerente. Caso negativo, terá o acesso cancelado e será redirecionado para a tela de login.
	if (!$Usuario->usuario_logado() ||
		$_SESSION["multichat_nivel"]!=1){
		header("location: logout.php");
	}

?>

<!DOCTYPE html>
<html>

<head>
	<title>MULTICHAT - Conectando pessoas - Administração de conversas</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<script src="jquery\jquery-3.2.1.js"></script>
	<script src="bootstrap/js/bootstrap.js"></script>	
	<script src="jquery\jquery.tablesorter.min.js"></script>
	<link rel="stylesheet" href="css/multichat_style.css">
	<link rel="stylesheet" href="css/multichat_operador_style.css">
	<script src="js\funcoes_auxiliares.js" type="text/javascript"></script>
	

	<script>

		var conversa_argumento_pesquisa;
		var tabela_conversas_corpo;
		var div_lista_conversas;
		var conversa_data_inicio = "";

		var ctrl_md_conversa_cliente_nome;
		var ctrl_md_conversa_cliente_cpf;
		var ctrl_md_conversa_cliente_email;
		var ctrl_md_conversa_status_descricao;
		var ctrl_md_conversa_texto;
		var ctrl_btn_envio_email;

		//Pesquisa conversas com base no argumento de pesquisa
		function pesquisa_conversas(){

			document.getElementById("mensagem_erro").innerHTML = "Já vou, caraio!";
	//		$("#modal_mensagem_erro").modal('show');

			var formData = new FormData();
			formData.append('conversa_pesquisa',conversa_argumento_pesquisa.value);

			$.ajax({
				url: "conversa_pesquisa.php",
				method: "post",
				data: formData,
				contentType: false,
				processData: false,
				async: false,
				success: retorno_conversa_pesquisa
			});
		}

		//Retorno da chamada do ajax para tratar a pesquisa de conversas
		function retorno_conversa_pesquisa( data ){
			try {
				var retorno = JSON.parse(data);
				switch(retorno.status){
					case 0:
						conversa_pesquisa_formata_retorno(retorno.Conversas);
						break;
					case 100:
						window.location.replace("logout.php");
						break;
					default:
						mensagem = "Problemas no acesso às conversas. \n";
						for (x in retorno.erros){
							mensagem+= "\n - " + retorno.erros[x];
						}
						alert(mensagem);
				}
			} catch(e){
				alert(e);
				alert(data);
			}
			return;
		}

		function conversa_pesquisa_formata_retorno (conversas){

			//limpa a tabela de demonstração de conversas
			var linhas = tabela_conversas_corpo.getElementsByTagName("tr");
			while(linhas.length>0){
				tabela_conversas_corpo.deleteRow(0);	
			}

			if (!conversas){
				var semConversas = document.createElement("span");
				div_lista_conversas.classList.add("texto_ao_centro");
				semConversas.innerHTML = "Não há conversas para pesquisa";
				div_lista_conversas.appendChild(semConversas);
				return;
			}

			for (x in conversas){

				var linhas = document.createElement("tr");
				var dt_conversa_fila_entrada = new Date(conversas[x].conversa_fila_entrada);
				var dt_conversa_fila_atendimento = new Date(conversas[x].conversa_fila_atendimento);
				var dt_conversa_fim_atendimento = new Date(conversas[x].conversa_fim_atendimento);
				var dtform_conversa_fila_entrada = formata_data(dt_conversa_fila_entrada) + " " + formata_horario(dt_conversa_fila_entrada);			
				var calc_fmt_espera = milissegundos_em_tempo_decorrido(dt_conversa_fila_atendimento - dt_conversa_fila_entrada);
				var calc_fmt_atendimento = "";
				if (conversas[x].conversa_fim_atendimento){
					calc_fmt_atendimento = milissegundos_em_tempo_decorrido(dt_conversa_fim_atendimento - dt_conversa_fila_atendimento);
				}


				//entrada na fila
				var tb_entrada_na_fila = document.createElement("td");
				tb_entrada_na_fila.classList.add("texto_ao_centro");
				tb_entrada_na_fila.innerHTML = dtform_conversa_fila_entrada;
				linhas.appendChild(tb_entrada_na_fila);
				
				//nome do cliente				
				var tb_cliente_nome = document.createElement("td");
				tb_cliente_nome.innerHTML = conversas[x].conversa_cliente_nome;
				linhas.appendChild(tb_cliente_nome);

				//tempo de espera
				var tb_tempo_espera = document.createElement("td");
				tb_tempo_espera.classList.add("texto_ao_centro");
				tb_tempo_espera.innerHTML = calc_fmt_espera;
				linhas.appendChild(tb_tempo_espera);

				//tempo de atendimento
				var tb_tempo_atendimento = document.createElement("td");
				tb_tempo_atendimento.classList.add("texto_ao_centro");
				tb_tempo_atendimento.innerHTML = calc_fmt_atendimento;
				linhas.appendChild(tb_tempo_atendimento);

				//Número da campanha
				var tb_campanha_numero = document.createElement("td");
				tb_campanha_numero.classList.add("texto_ao_centro");
				tb_campanha_numero.innerHTML = conversas[x].conversa_campcab_id;
				linhas.appendChild(tb_campanha_numero);

				//Nome do atendente
				var tb_operador = document.createElement("td");
				tb_operador.classList.add("texto_ao_centro");
				tb_operador.innerHTML = conversas[x].conversa_usuario;
				linhas.appendChild(tb_operador);

				//Status da conversa
				var tb_status = document.createElement("td");
				tb_status.classList.add("texto_ao_centro");
				tb_status.innerHTML = conversas[x].conversa_status_descricao;
				linhas.appendChild(tb_status);

				//Detalhes
				var conversa_cpf = "";
				if (conversas[x].conversa_cliente_cpf){
					conversa_cpf = conversas[x].conversa_cliente_cpf;
				}
				var tb_detalhes = document.createElement("td");
				tb_detalhes.classList.add("texto_ao_centro");
				var link_detalhes =  "<button class='btn btn-link'";
				link_detalhes+= " data-conversa-id='" + conversas[x].conversa_id + "'";
				link_detalhes+= " data-conversa-cliente-nome='" + conversas[x].conversa_cliente_nome + "'";
				link_detalhes+= " data-conversa-cliente-cpf='" + conversa_cpf + "'";
				link_detalhes+= " data-conversa-status-descricao='" + conversas[x].conversa_status_descricao + "'";
				link_detalhes+= " data-conversa-cliente-email='" + conversas[x].conversa_cliente_email + "'";
				link_detalhes+= " onclick='display_conversa(this);'><span class='glyphicon glyphicon-search'></span></button>";
				tb_detalhes.innerHTML = link_detalhes;
				linhas.appendChild(tb_detalhes);

				//adiciona a linha à tabela
				tabela_conversas_corpo.appendChild(linhas);
			}
			return;
		}

		//Abre um modal com os detalhes da conversa a ser consultada
		function display_conversa(obj){

			conversa_id = obj.getAttribute('data-conversa-id');
			ctrl_md_conversa_cliente_nome.value = obj.getAttribute('data-conversa-cliente-nome');
			ctrl_md_conversa_cliente_cpf.value = obj.getAttribute('data-conversa-cliente-cpf');
			ctrl_md_conversa_status_descricao.value = obj.getAttribute('data-conversa-status-descricao');
			ctrl_md_conversa_cliente_email.value = obj.getAttribute('data-conversa-cliente-email');
			ctrl_btn_envio_email.setAttribute('data-conversa-id',conversa_id);
			ctrl_md_conversa_texto.innerHTML = "";
			conversa_data_inicio = "";	
			verifica_novas_mensagens(conversa_id);
			$("#conversa_detalhe").modal('show');
			return false;

		}

		function verifica_novas_mensagens(conversa_id){

			var formData = new FormData();
			formData.append("ultima_mensagem",0);
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
						finalizacao_da_conversa();
						break;
					case 100:
						window.location.replace("logout.php");
					default:
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

			if (data_formatada != conversa_data_inicio){
				var data_da_conversa = document.createElement("span");
				data_da_conversa.classList.add("mensagem_data_corrente");
				data_da_conversa.innerHTML = data_formatada;
				ctrl_md_conversa_texto.appendChild(data_da_conversa);
				conversa_data_inicio = data_formatada;
			}			
			
			var classe_mensagem;			

			if (mensagem.mensagem_remetente_tipo=="1"){			
				classe_mensagem = "mensagem_operador_texto";	
			}			
			else {			
				classe_mensagem = "mensagem_cliente_texto";				
			}

			var nova_mensagem = document.createElement("span");			
			nova_mensagem.classList.add(classe_mensagem);
			nova_mensagem.innerHTML = "<strong>" + mensagem.mensagem_remetente + ": </strong>" + mensagem.mensagem_texto + "<span class='mensagem_hora_corrente'>" + " - " + horario_formatado +  "</span>";

			ctrl_md_conversa_texto.appendChild(nova_mensagem);								
			ctrl_md_conversa_texto.scrollTop= ctrl_md_conversa_texto.scrollHeight;
			return;

		}

		function finalizacao_da_conversa(){
			
			conversa_finalizada = document.createElement("div");
			conversa_finalizada.classList.add("conversa_finalizada");
			texto_fim = document.createElement("span");
			texto_fim.innerHTML = "Conversa finalizada";			
			conversa_finalizada.appendChild(texto_fim);
			ctrl_md_conversa_texto.appendChild(conversa_finalizada);			
			return;	

		}

		function conversa_enviar_email(obj){

			conversa_id = obj.getAttribute("data-conversa-id");

			var formData = new FormData();
			formData.append("conversa_id",conversa_id);
			formData.append("lista_emails",ctrl_md_conversa_cliente_email.value);

			$.ajax({
				url: "conversa_enviar_email.php",
				method: "post",
				data: formData,
				async: false,
				processData: false,
				contentType: false,
				success: retorno_conversa_enviar_email
			});

			return false;
		}

		function retorno_conversa_enviar_email( data ){

			try{
				retorno = JSON.parse(data);
				switch(retorno.status){
					case 0:
						alert("Emails enviados com sucesso");
						break;
					case 100:
						window.location.replace("logout.php");
						break;
					default:
						var mensagem = "Problemas no processo de envio de email. \n";
						for (x in retorno.erros){
							mensagem+= "\n - " + retorno.erros[x];
						}
						alert(mensagem);
				}
			} catch (e){
				alert(e);
				alert(data);
			}
			return;
		}

		//Procedimentos iniciais da página
		$(document).ready(function(){					

			//carrega elemento em variável para evitar busca em todo o código a cada utilização
			conversa_argumento_pesquisa = document.getElementById('conversa_pesquisa');
			tabela_conversas_corpo = document.getElementById("tab_conversas_tbody");
			div_lista_conversas = document.getElementById("lista_conversas");

			ctrl_md_conversa_cliente_nome = document.getElementById("md_conversa_cliente_nome");
			ctrl_md_conversa_cliente_cpf = document.getElementById("md_conversa_cliente_cpf");
			ctrl_md_conversa_cliente_email = document.getElementById("md_conversa_cliente_email");
			ctrl_md_conversa_status_descricao = document.getElementById("md_conversa_status_descricao");
			ctrl_md_conversa_texto = document.getElementById("md_conversa_texto");
			ctrl_btn_envio_email = document.getElementById("btn_envio_email");

			pesquisa_conversas();

			//Aciona o classificador de dados da tabela	
			$("#tab_conversas").tablesorter();	
			
		});
	</script>

</head>

<body>

<?php

	//Cabeçalho padrão do site
	include "cabecalho_padrao.php";
	//Barra de navegação para o nível de gerente
	include "navbar_gerente.php";

?>
<!--Modal para demonstração de detalhes da mensagem -->
<div class="modal fade" id="conversa_detalhe" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-body container-fluid">
     			<div>
	      			<span>Histórico de negociação</span>
	      		</div>
	      		<div class="row">
		      		<div class="form-group col-md-5">
		      			<label class="form-control-label"> Cliente </label>
		      			<input class="form-control" type="text" readonly id='md_conversa_cliente_nome' value="Hudson da Silva Claudino"/>
		      		</div>
		      		<div class="form-group col-md-4">
		      			<label class="form-control-label"> CPF </label>
		      			<input class="form-control" type="text" readonly id='md_conversa_cliente_cpf' value="123.456.789-01"/>
		      		</div>
		      		<div class="form-group col-md-3">
		      			<label class="form-control-label"> Status </label>
		      			<input class="form-control" type="text" readonly id='md_conversa_status_descricao' value="Atendido"/>
		      		</div>      	 
	      		</div>     
	      		<div class="row">
	      			<div class="col-md-12">
	      				<div id="md_conversa_texto">

						</div>
	      			</div>
	      		</div>
	      		<div class="row">
	      			<div class="col-md-12">
	      				<div class="md_botoes">
	      					<div class="input-group">
	      						<input type="email" class="form-control" id="md_conversa_cliente_email"/>
	      						<div class="input-group-btn">
	      							<button class="btn btn-success" id="btn_envio_email" onclick="conversa_enviar_email(this);">
	      								<i class="glyphicon glyphicon-envelope"></i> Enviar cópias
	      							</button>
	      						</div>
	      					</div>	      					
	      					<button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>		        			
	      				</div>
	      			</div>
	      		</div>

      		</div>
    	</div>
  	</div>
</div>

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

<div class="container divisor_caixas" id="conversas_adm">
	<div class="row">
		<div class="col-md-12">
			<span class="titulo-pagina-detalhe">ADMINISTRAÇÃO DE CONVERSAS</span>
		</div>
	</div>
	<div class="row">
		<div class="col-md-9"></div>
		<div class="col-md-3">
			<div class="filtro_conversa">
				<div class="pesquisa_conversa">
					<form>
					  	<div class="input-group">
    						<input type='text' id='conversa_pesquisa' class='form-control' placeholder="Pesquisar">
    						<div class="input-group-btn">
      							<button class="btn btn-success" onclick="pesquisa_conversas();return false;">
        							<i class="glyphicon glyphicon-search"></i>
      							</button>
    						</div>
  						</div>						
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div id="lista_conversas" class="col-md-12">
			<table id="tab_conversas" class="table table-striped tablesorter tabelas_padrao">
				<thead>
					<th class='texto_ao_centro col-md-2'>Entrada na fila</th>
					<th class="col-md-3">Cliente</th>
					<th class='texto_ao_centro col-md-1'>Espera</th>
					<th class='texto_ao_centro col-md-1'>Duração</th>
					<th class='texto_ao_centro col-md-1'>Campanha</th>
					<th class='texto_ao_centro col-md-2'>Atendido por</th>
					<th class='texto_ao_centro col-md-1'>Status</th>
					<th class='texto_ao_centro col-md-1'>Detalhes</th>
				</thead>
				<tbody id="tab_conversas_tbody">
					<tr>
					<td class='texto_ao_centro'>16/08/2017 10:32</th>
					<td>Hudson da Silva Claudino</th>
					<td class='texto_ao_centro'>5 minutos</th>
					<td class='texto_ao_centro'>8 minutos</th>
					<td class='texto_ao_centro'>001</th>
					<td class='texto_ao_centro'>Atendente</th>
					<td class='texto_ao_centro'>Atendido</th>
					<td class='texto_ao_centro'>+</th>
					</tr>
					<tr>
					<td class='texto_ao_centro'>16/08/2017 10:32</th>
					<td>Hudson da Silva Claudino</th>
					<td class='texto_ao_centro'>5 minutos</th>
					<td class='texto_ao_centro'>8 minutos</th>
					<td class='texto_ao_centro'>001</th>
					<td class='texto_ao_centro'>Atendente</th>
					<td class='texto_ao_centro'>Atendido</th>
					<td class='texto_ao_centro'>+</th>
					</tr>
					<tr>
					<td class='texto_ao_centro'>16/08/2017 10:32</th>
					<td>Hudson da Silva Claudino</th>
					<td class='texto_ao_centro'>5 minutos</th>
					<td class='texto_ao_centro'>8 minutos</th>
					<td class='texto_ao_centro'>001</th>
					<td class='texto_ao_centro'>Atendente</th>
					<td class='texto_ao_centro'>Atendido</th>
					<td class='texto_ao_centro'>+</th>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<!-- Rodapé padrão do site -->
<?php include "footer.html"; ?>

</body>

</html>

