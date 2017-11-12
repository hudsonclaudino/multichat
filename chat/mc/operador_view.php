

<!DOCTYPE html>
<html>

<head>
	<title>MULTICHAT - Conectando pessoas - Painel administrativo</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<script src="jquery\jquery-3.2.1.js"></script>
	<script src="bootstrap/js/bootstrap.js"></script>	
	<link rel="stylesheet" href="css/multichat_style.css">

	<script>

		//variáveis de controle dos botões de status do operador
		var botao_online;
		var botao_offline;
		var operador_online=false;

		//indica na tabela de usuários que ele está disponível para atendimento
		function tornar_operador_online(){
			
			if (operador_online) return;			

			botao_online.classList.remove("btn-default");
			botao_online.classList.add("btn-success");
			botao_offline.classList.remove("btn-danger");
			botao_offline.classList.add("btn-default");

			operador_online = true;

			status_operador_online(1);
		}

		//indica na tabela de usuários que ele não está disponível para atendimento
		function tornar_operador_offline(){

			if (!operador_online) return;			

			botao_offline.classList.remove("btn-default");
			botao_offline.classList.add("btn-danger");
			botao_online.classList.remove("btn-success");
			botao_online.classList.add("btn-default");

			operador_online = false;

			status_operador_online(0);
		}

		//Altera no banco de dados o status do operador
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
							mensagem+= "\n- " + retorno.erros[x];}
						alert(mensagem);									
				}
			} catch (e){
				alert(data);
			}

			return false;
		}

		//configura os botões para que não seja necessário fazer a carga de dados do mesmo a todo momento
		$(document).ready(function(){

			botao_online = document.getElementById("btn_online");
			botao_offline = document.getElementById("btn_offline");

		});

	</script>

</head>

<body>

<?php
	include "cabecalho_padrao.php";
?>

<div class="container divisor_caixas" id="conteudo">
	<div class="row">
		<div class="col-md-2">
			<div class="adm_campanha_stat">
				<span>CLIENTES<BR/> NA FILA</span>
				<p>
					<span id="dashboard_clientes_na_file">
						25
					</span>
				</p>
			</div>
		</div>
		<div class="col-md-2">
			<div class="adm_campanha_stat">
				<span>MÉDIA <BR/> DE ESPERA</span>
				<p>
					<span id="dashboard_media_espera">
						0:15
					</span>
				</p>
			</div>
		</div>
		<div class="col-md-2">
			<div class="adm_campanha_stat">
				<span>MÉDIA <br/> DE ATENDIMENTO</span>
				<p>
					<span id="dashboard_media_atendimento">
						0:23
					</span>					
				</p>
			</div>
		</div>
		<div class="col-md-2">
			<div class="adm_campanha_stat">
				<span>NOVOS <br/>RECADOS</span>
				<p>
					<span id="dashboard_recados">
						5
					</span>					
				</p>
			</div>
		</div>
		<div class="col-md-2">
			<div class="adm_campanha_stat">
				<span>MEUS <br/>ATENDIMENTOS</span>
				<p>
					<span id="dashboard_recados">
						5
					</span>					
				</p>
			</div>
		</div>
		<div class="col-md-2">
			<div class="adm_campanha_stat">
				<span>MEU <br/>STATUS</span>
				<p>
					<div class="botao_on_off">
						<div class="btn-group">
							<button id="btn_offline" class="btn btn-danger" onclick="tornar_operador_offline();">
								Offline
							</button>
							<button id="btn_online" class="btn btn-default" onclick="tornar_operador_online();">
								Online
							</button>
						</div>
					</div>
				</p>
			</div>
		</div>
	</div>	
</div>

</body>

</html>

