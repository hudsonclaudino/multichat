<?php
	include_once "config.php";
	//Classe para a conexão com o banco de dados	
	include_once "class/ConexaoDB.php";

	//Classe para a pesquisa de usuários do sistema / atendentes e gerentes
	include_once "class/Usuario.php";

	//Criação das instâncias das classes
	$Db_con = new ConexaoDB(ROOT);
	$Usuario = new Usuario($Db_con->conexao());

	//Verifica se o usuário está logado no sistema, para evitar entradas diretas. Caso negativo, será direcionado para a tela de login
	if (!$Usuario->usuario_logado()){
		header("location: logout.php");
	}

	//Funções para a demonstração de dados na tela do gerente
	include "gerente_view_funcoes.php";


?> 

<!DOCTYPE html>
<html>

<head>
	<title>MULTICHAT - Conectando pessoas - Painel administrativo</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.css">
	<script src="jquery\jquery-3.2.1.js"></script>
	<script src="bootstrap/js/bootstrap.js"></script>	
	<link rel="stylesheet" href="css/multichat_style.css">
	<script src="js/funcoes_auxiliares.js"></script>
	<script>

		var obj_dash_data_inicial;
		var obj_dash_data_final;

		var obj_numero_total_atendimentos;
		var obj_progress_total_atendimentos;
		var obj_percent_total_atendimentos;

		var obj_numero_total_desistencias;
		var obj_progress_total_desistencias;
		var obj_percent_total_desistencias;

		var obj_numero_total_recados;
		var obj_progress_total_recados;
		var obj_percent_total_recados;

		var obj_tempo_medio_espera;

		var obj_dash_operadores_lista_tbody;

		var obj_dash_clientes_na_fila;
		var obj_tempo_espera_proximo;

		var obj_dash_campanhas;
		var obj_dash_convites_gerados;

		var obj_numero_emails_enviados;
		var obj_progress_emails_enviados;
		var obj_percent_emails_enviados;

		var obj_numero_clientes_atingidos;
		var obj_progress_clientes_atingidos;
		var obj_percent_clientes_atingidos;

		function carrega_controles_em_variaveis(){

			obj_dash_data_inicial = document.getElementById("dash_data_inicial");
			obj_dash_data_final = document.getElementById("dash_data_final");

			obj_numero_total_atendimentos = document.getElementById("numero_total_atendimentos");			
		    obj_progress_total_atendimentos = document.getElementById("progress_total_atendimentos");
		    obj_percent_total_atendimentos = document.getElementById("percent_total_atendimentos");

			obj_numero_total_desistencias = document.getElementById("numero_total_desistencias");			
		    obj_progress_total_desistencias = document.getElementById("progress_total_desistencias");
		    obj_percent_total_desistencias = document.getElementById("percent_total_desistencias");

			obj_numero_total_recados = document.getElementById("numero_total_recados");			
		    obj_progress_total_recados = document.getElementById("progress_total_recados");
		    obj_percent_total_recados = document.getElementById("percent_total_recados");

		    obj_tempo_medio_espera = document.getElementById("tempo_medio_espera");

		    obj_dash_operadores_lista_tbody = document.getElementById("dash_operadores_lista_tbody");

		    obj_dash_clientes_na_fila = document.getElementById("dash_clientes_na_fila");

		    obj_dash_tempo_espera_proximo = document.getElementById("dash_tempo_espera_proximo");

		    obj_dash_campanhas = document.getElementById("dash_campanhas");

		    obj_dash_convites_gerados = document.getElementById("dash_convites_gerados");

			obj_numero_emails_enviados = document.getElementById("numero_emails_enviados");			
		    obj_progress_emails_enviados = document.getElementById("progress_emails_enviados");
		    obj_percent_emails_enviados = document.getElementById("percent_emails_enviados");

			obj_numero_clientes_atingidos = document.getElementById("numero_clientes_atingidos");			
		    obj_progress_clientes_atingidos = document.getElementById("progress_clientes_atingidos");
		    obj_percent_clientes_atingidos = document.getElementById("percent_clientes_atingidos");

		}

		function buscar_dados_dashboard(){

			if (obj_dash_data_inicial.value > obj_dash_data_final){
				alert("A data inicial informada deve ser igual ou anterior a data final.");
				return false;
			}

			var formData = new FormData();
			formData.append("dash_data_inicial",obj_dash_data_inicial.value);
			formData.append("dash_data_final",obj_dash_data_final.value);

			$.ajax({
				url: "gerente_atualiza_dashboard.php",
				method: "post",
				data: formData,
				contentType: false,
				processData: false,
				async: false,
				success: buscar_dados_dashboard_retorno
			});

			return false;
		}

		function buscar_dados_dashboard_retorno (data){

			try {
				Retorno = JSON.parse(data);
				if (Retorno.status == 0){
					atualiza_dashboard(Retorno);
				} else {
					if (Retorno.status ==100){
						window.location.replace("logout.php");						
					} else {
						mensagem = "Não foi possível atualizar o dashboard! \n";
						for (x in Retorno.erros){
							mensagem+= "\n - " + Retorno.erros[x];
						}
						alert(mensagem);
					}
				}
			} catch (e) {
				alert(e);
				alert(data);
			}

			return false;
		}

		function atualiza_dashboard(Retorno_stat){

			var conversas_total = 0;

			var atendidos_total = 0;
			var atendidos_percent = 0;

			var desistencias_total = 0;
			var desistencias_percent = 0;

			var recados_total = 0;
			var recados_percent = 0;

			var conversas_media_espera = "0s";

			var clientes_na_fila = 0;
			var espera_mais_antiga = 0;

			var campanhas_total = 0;

			var convites_gerados = 0;
			var emails_enviados = 0;
			var emails_enviados_percent = 0;
			var clientes_atingidos = 0;
			var clientes_atingidos_percent = 0;

			//calcula percentual de atendimentos efetuados
			conversas_total = Number(Retorno_stat.stat_total_conversas);			
			atendidos_total = Number(Retorno_stat.stat_conversas_em_atendimento) + Number(Retorno_stat.stat_conversas_finalizadas);

			if (conversas_total > 0){
				atendidos_percent = Math.round(atendidos_total / conversas_total * 1000)/10;
			}
			
			obj_numero_total_atendimentos.innerHTML = atendidos_total;
		    obj_progress_total_atendimentos.style.width = atendidos_percent + "%";
		    obj_percent_total_atendimentos.innerHTML = atendidos_percent;

		    //calcula o percentual de desistências
		    desistencias_total = Number(Retorno_stat.stat_conversas_abandonadas) + Number(Retorno_stat.stat_conversas_mensagem_offline);

			if (conversas_total > 0){
				desistencias_percent = Math.round(desistencias_total / conversas_total * 1000)/10;
			}

			obj_numero_total_desistencias.innerHTML = desistencias_total;
		    obj_progress_total_desistencias.style.width = desistencias_percent + "%";
		    obj_percent_total_desistencias.innerHTML = desistencias_percent;

		    //calcula o percentual de desistentes que deixaram recados	
		    recados_total = Number(Retorno_stat.stat_conversas_mensagem_offline);

			if (desistencias_total > 0){
				recados_percent = Math.round(recados_total / desistencias_total * 1000)/10;
			}

			obj_numero_total_recados.innerHTML = recados_total;
		    obj_progress_total_recados.style.width = recados_percent + "%";
		    obj_percent_total_recados.innerHTML = recados_percent;

		    //formata o tempo médio de espera do cliente
		    conversas_media_espera = milissegundos_em_tempo_decorrido(Number(Retorno_stat.stat_conversas_media_espera) * 1000);
		    obj_tempo_medio_espera.innerHTML = conversas_media_espera;

		    //formata lista de operadores
		    var operadores_array = [
		    ["Nilton Júnior","06/09/17 09:56","1",19],
		    ["Hudson Claudino","06/09/17 09:54","0",13],
		    ["Nilton Júnior","05/09/17 19:56","1",0],
		    ["Hudson Claudino","04/09/17 07:56","1",1],
		    ["Nilton Júnior","03/09/17 09:56","0",129],
		    ["Nilton Júnior","06/09/17 09:56","1",19],
		    ["Hudson Claudino","06/09/17 09:54","0",13],
		    ["Nilton Júnior","05/09/17 19:56","1",0],
		    ["Hudson Claudino","04/09/17 07:56","1",1],
		    ["Nilton Júnior","03/09/17 09:56","0",129],
		    ["Nilton Júnior","06/09/17 09:56","1",19],
		    ["Hudson Claudino","06/09/17 09:54","0",13],
		    ["Nilton Júnior","05/09/17 19:56","1",0],
		    ["Hudson Claudino","04/09/17 07:56","1",1],
		    ["Nilton Júnior","03/09/17 09:56","0",129],
		    ["Hudson Claudino","02/09/17 09:56","1",19]
		    ];

		    operadores_array = Retorno_stat.stat_operadores;

		    var operadores_detalhe = "";
		    if (operadores_array[0][0]){
			    for (x in operadores_array){
			    	operadores_detalhe += "<tr>";
			    	//nome do operador
			    	operadores_detalhe += "<td>" + operadores_array[x][0] + "</td>";
			    	//Data do último acesso
			    	operadores_detalhe += "<td>" + operadores_array[x][1] + "</td>";
			    	//Status do operador
			    	if (Number(operadores_array[x][2])==1){
			    		operadores_detalhe += "<td class='verde'>Online</td>" ;
			    	} else {
			    		operadores_detalhe += "<td class='vermelho'>Offline</td>" ;		    		
			    	}		    	
			    	//Atendimentos no período
			    	operadores_detalhe += "<td>" + operadores_array[x][3] + "</td>";
			    	operadores_detalhe +=" </tr>";
			    }
			}

		    obj_dash_operadores_lista_tbody.innerHTML = operadores_detalhe;

		    //formata o número de clientes na fila
		    clientes_na_fila = Number(Retorno_stat.stat_conversas_na_fila);
		    obj_dash_clientes_na_fila.innerHTML = clientes_na_fila;

		    //formata espera mais antiga
		    espera_mais_antiga = milissegundos_em_tempo_decorrido(Number(Retorno_stat.stat_fila_mais_antiga) * 1000);
		    obj_dash_tempo_espera_proximo.innerHTML = espera_mais_antiga;	

		    //formata dash de campanhas
		    campanhas_total = Number(Retorno_stat.campanhas_total);
		    obj_dash_campanhas.innerHTML = campanhas_total;	 

		    //formata dash de emails enviados
		    convites_gerados = Number(Retorno_stat.convites_gerados);
		    obj_dash_convites_gerados.innerHTML = convites_gerados;

		    //calcula o percentual de emails abertos	
		    emails_enviados = Number(Retorno_stat.emails_enviados);

			if (convites_gerados > 0){
				emails_enviados_percent = Math.round(emails_enviados / convites_gerados * 1000)/10;
			}

			obj_numero_emails_enviados.innerHTML = emails_enviados;
		    obj_progress_emails_enviados.style.width = emails_enviados_percent + "%";
		    obj_percent_emails_enviados.innerHTML = emails_enviados_percent;

		    //calcula o percentual de clientes atingidos	
		    clientes_atingidos = Number(Retorno_stat.clientes_atingidos);

			if (emails_enviados > 0){
				clientes_atingidos_percent = Math.round(clientes_atingidos / emails_enviados * 1000)/10;
			}

			obj_numero_clientes_atingidos.innerHTML = clientes_atingidos;
		    obj_progress_clientes_atingidos.style.width = clientes_atingidos_percent + "%";
		    obj_percent_clientes_atingidos.innerHTML = clientes_atingidos_percent;

		    return false;		    
	
		}

		$(document).ready(function(){

			carrega_controles_em_variaveis();

			var data_final = new Date();
			var sete_dias = 1000 * 60 * 60 * 24 * 7;
			var data_inicial = new Date(data_final.getTime() - sete_dias);
			obj_dash_data_inicial.value = formata_data_YMD(data_inicial);
			obj_dash_data_final.value = formata_data_YMD(data_final);

			buscar_dados_dashboard();

		});
	</script>
	
</head>

<body>


<?php
	//Cabecalho padrão do sistema
	include "cabecalho_padrao.php";
	//Barra de navegação para o nível de gerente
	include "navbar_gerente.php";
?>

<div class="container divisor_caixas" id="conteudo" style="display:none;">
	<div class="row">
		<div class="col-md-6">
			<div class="box-externo-gerente">
				<div class="box-gerente">
					<span class="titulo-box-gerente">Conversas de hoje</span>
					
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="box-externo-gerente">
				<div class="box-gerente">
					<span class="titulo-box-gerente">Fila de espera</span>
				</div>
			</div>			
		</div>
	</div>
		<div class="row">
		<div class="col-md-6">
			<div class="box-externo-gerente">
				<div class="box-gerente" id="box_atendentes_online">
					<span class="titulo-box-gerente">Operadores online</span>
					<?php montar_tabela_atendentes_online($usuarios_online); ?>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="box-externo-gerente">
				<div class="box-gerente">
					<span class="titulo-box-gerente">ÚLTIMAS Campanhas</span>
					<?php montar_tabela_ultimas_campanhas($ultimas_campanhas); ?>
				</div>
			</div>			
		</div>
	</div>	
</div>

<div class="container divisor_caixas" id="gerente_dashboard">
	<div class="row">
		<div class="col-md-12 texto_ao_centro">
			<form class="form-inline">
				<div class="form-group">
					<label for="dash_data_inicial">DE: </label>
					<input type="date" class="form-control" id="dash_data_inicial" value="2017-09-04">
				</div>
				<div class="form-group">
					<label for="dash_data_final">ATÉ: </label>
					<input type="date" class="form-control" id="dash_data_final">
				</div>
				<button class="btn btn-success" onclick="buscar_dados_dashboard();return false;"><i class="glyphicon glyphicon-filter"></i></button>
			</form>
		</div>
	</div>
	<div class="row gerente_dash">
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="gerente_dash_boards gerente_dash_conversa">
				<span class="titulo-box-gerente">ATENDIMENTOS</span>
				<span id="numero_total_atendimentos" class="info-box-gerente-com-progress" >19</span>
				<div class="col-md-12">
					<div class="progress">
	  					<div id="progress_total_atendimentos" class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:70%;">
	  					</div>
					</div>
					<span class="dash_descr_progress"><span id="percent_total_atendimentos">70</span>% dos clientes foram atendidos.</span>
				</div>
			</div>
		</div>
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="gerente_dash_boards gerente_dash_conversa">
				<span class="titulo-box-gerente">DESISTÊNCIAS</span>
				<span id="numero_total_desistencias" class="info-box-gerente-com-progress" >19</span>
				<div class="col-md-12">
					<div class="progress">
	  					<div id="progress_total_desistencias" class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:70%;">
	  					</div>
					</div>
					<span class="dash_descr_progress"><span id="percent_total_desistencias">70</span>% dos clientes desistiram da espera.</span>
				</div>
			</div>
		</div>
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="gerente_dash_boards gerente_dash_conversa">
				<span class="titulo-box-gerente">RECADOS</span>
				<span id="numero_total_recados" class="info-box-gerente-com-progress" >19</span>
				<div class="col-md-12">
					<div class="progress">
	  					<div id="progress_total_recados" class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:70%;">
	  					</div>
					</div>
					<span class="dash_descr_progress"><span id="percent_total_recados">70</span>% dos desistentes deixaram recados.</span>
				</div>
			</div>
		</div>
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="gerente_dash_boards gerente_dash_conversa">
				<span class="titulo-box-gerente">MÉDIA DE ESPERA</span>				
				<span id="tempo_medio_espera" class="info-box-gerente" >19</span>
			</div>
		</div>		
	</div>
	<div class="row gerente_dash">		
		<div class="col-md-9 col-sm-12">
			<div  id="dash_operadores" class="gerente_dash_boards gerente_dash_operadores">
				<span class="titulo-box-gerente">OPERADORES</span>
				<div class="container_table">
					<table id="dash_operadores_lista" class='table table-condensed table-striped table-bordered'>
						<thead>
							<th class="texto_ao_centro col-md-5">NOME</th>
							<th class="texto_ao_centro col-md-3">ÚLTIMO ACESSO</th>
							<th class="texto_ao_centro col-md-2">STATUS</th>
							<th class="texto_ao_centro col-md-2">ATENDIMENTOS</th>
						</thead>
						<tbody id="dash_operadores_lista_tbody">
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-md-3 col-sm-12">
			<div class="row">
				<div class="col-md-12 col-sm-6">
					<div  class="gerente_dash_boards gerente_dash_fila">
						<span class="titulo-box-gerente">CLIENTES NA FILA</span>
						<span id="dash_clientes_na_fila" class="info-box-gerente" >19</span>				
					</div>
				</div>
				<div class="col-md-12 col-sm-6">
					<div  class="gerente_dash_boards gerente_dash_fila">
						<span class="titulo-box-gerente">ESPERA DO PRÓXIMO</span>	
						<span id="dash_tempo_espera_proximo" class="info-box-gerente" >19</span>			
					</div>
				</div>
			</div>
		</div>		
	</div>
	<div class="row gerente_dash">
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div  class="gerente_dash_boards gerente_dash_campanha">
				<span class="titulo-box-gerente">NOVAS CAMPANHAS</span>				
				<span id="dash_campanhas" class="info-box-gerente" >19</span>			
			</div>
		</div>
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div  class="gerente_dash_boards gerente_dash_campanha">
				<span class="titulo-box-gerente">CONVITES GERADOS</span>				
				<span id="dash_convites_gerados" class="info-box-gerente" >19</span>	
			</div>
		</div>
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="gerente_dash_boards gerente_dash_campanha">
				<span class="titulo-box-gerente">EMAILS ENVIADOS</span>
				<span id="numero_emails_enviados" class="info-box-gerente-com-progress" >19</span>
				<div class="col-md-12">
					<div class="progress">
	  					<div id="progress_emails_enviados" class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:70%;">
	  					</div>
					</div>
					<span class="dash_descr_progress"><span id="percent_emails_enviados">70</span>% convites gerados foram enviados.</span>
				</div>
			</div>
		</div>
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="gerente_dash_boards gerente_dash_campanha">
				<span class="titulo-box-gerente">CLIENTES ATINGIDOS</span>
				<span id="numero_clientes_atingidos" class="info-box-gerente-com-progress" >19</span>
				<div class="col-md-12">
					<div class="progress">
	  					<div id="progress_clientes_atingidos" class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:70%;">
	  					</div>
					</div>
					<span class="dash_descr_progress"><span id="percent_clientes_atingidos">70</span>% dos emails enviados geraram atendimento.</span>
				</div>
			</div>
		</div>		
	</div>
</div>

<!-- Rodapé padrão do site -->
<?php include "footer.html"; ?>

</body>

</html>

