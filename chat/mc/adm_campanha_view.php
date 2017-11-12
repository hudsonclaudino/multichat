<?php
	
	//Verifica se o usuário está logado no sistema, para evitar entradas diretas. Caso negativo, será direcionado para a tela de login
	if (!$Usuario->usuario_logado()){
		header("location: logout.php");
	}

	//Funções para a demonstração de dados na tela do gerente
	include "campanhas_view_funcoes.php";
?>

<!DOCTYPE html>
<html>

<head>
	<title>MULTICHAT - Conectando pessoas - Administração de campanhas</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">	
	<script src="jquery\jquery-3.2.1.js"></script>
	<script src="bootstrap/js/bootstrap.js"></script>
	<script src="jquery\jquery.tablesorter.min.js"></script>
	<link rel="stylesheet" href="jquery\jquery-ui.css">
	<script src="jquery\jquery-ui.js"></script>
	<script src="jquery\jquery.tablesorter.min.js"></script>
	<script src="js\funcoes_auxiliares.js"></script>
	<link rel="stylesheet" href="css/multichat_style.css">

	
	<script>		

		var campanha_id;

		function carregar_convites(campanha){		

			var client = new XMLHttpRequest();			

			client.open("GET","campanha_carrega_convites.php?campanha=" + campanha, false);
			client.setRequestHeader("content-type","application/x-www-form-urlencoded");

			client.onreadystatechange = function( response )
			{					
				if (this.readyState == 4 && this.status == 200){		
					
					elemento=document.getElementById("campanha_convites_lista");
					try{
						var retorno = JSON.parse(this.responseText);
						switch(retorno.status){
							case 0:
								elemento.innerHTML = retorno.convites;				
								break;
							case 100:
								window.location.replace("logout.php");
								break;
							default:
								var mensagem = "NÃO FOI POSSÍVEL CARREGAR OS CONVITES! \n\n";
								for (x in retorno.erros){
									mensagem+= "- " + retorno.erros[x]};
								alert(mensagem);							
						}
					} catch (e){
						elemento.innerHTML = this.responseText;
						linhas = document.getElementById("qtd_convites");
						linhas.innerHTML = contarLinhasTabela("tab_convites");
					}
				}				
			};			  				
			client.send();
		}

		function carregar_arquivo_csv(){

			elem = document.getElementById("input_importar_arquivo");

			fileName = elem.value;

			if (fileName.length ==0){
				return false;
			}

			ext = fileName.substr((fileName.length-3),3).toLowerCase();

			if (ext != 'csv'){
				alert('O arquivo selecionado não é um arquivo CSV');
				elem.value = "";				
				return false;
			}

			document.getElementById("loading").style.display = "block";
			var formData = new FormData();
			formData.append('arquivo',elem.files[0]);
			formData.append('campanha_id',document.getElementById("campcab_id").value);
			
			$.ajax({
				url: "carrega_csv.php",
				contentType: false,
				processData: false,
				method: "post",
				data: formData,
				success: function ( data ){
					document.getElementById("loading").style.display = "none";
					try{
						retorno = JSON.parse(data);
						switch(retorno.status){
							case 0:
								carregar_convites(document.getElementById("campcab_id").value);
								alert("Arquivo importado com sucesso!");
								window.open(retorno.nome_arquivo_saida);								
								break;
							case 100:
								window.location.replace("logout.php");
								break;
							default:
								mensagem = "NÃO FOI POSSÍVEL EFETUAR A IMPORTAÇÃO DO ARQUIVO! \n\n";
								for (x in retorno.erros){
									mensagem+="- " + retorno.erros[x] + "\n";
								}
								alert(mensagem);
						}
					} catch (e){
						alert(data);						
					}
				} 
			});
			
			return false;
		}

		//trata a exclusão de convites acionada pelo ícone de lixeira na linha
		function exclui_convite(convite_id){

			var formData = new FormData();
			formData.append("convite_id",convite_id);
			$.ajax({
				url: "campanha_excluir_convite.php",
				data: formData,
				async: false,
				method: "post",
				processData: false,
				contentType: false,
				success: retorno_exclui_convite
			});

			return false;
		}

		//Processa o retorno da função de exclusão de convites
		function retorno_exclui_convite(data){

			try {
				Retorno = JSON.parse(data);
				switch(Retorno.status){
					case 0:
						carregar_convites(campanha_id.value);
						break;
					case 100:
						window.location.replace("logout.php");
						break;
					default:
						mensagem = "Não foi possível excluir o convite! \n";
						for (x in Retorno.erros){
							mensagem+= "\n - " + Retorno.erros[x];
						}
						alert(mensagem);
				}				
			} catch(e){
				alert(e);
				alert(data);
			}
			return false;

		}

		$(document).ready(function(){

			campanha_id = document.getElementById("campcab_id");
			var id = document.getElementById("campcab_id").value;
			if (id > 0 ){
				var elemento=document.getElementById("div_convites");
				elemento.style.display = "block";	
				carregar_convites(id);
				linhas = document.getElementById("qtd_convites");
				linhas.innerHTML = contarLinhasTabela("tab_convites");
			}

			var envio = document.getElementById("campcab_envio_data").innerHTML;

			if (envio.trim()!="PENDENTE"){
				$("button").attr("disabled",true);
				$("#btn_menu_usuario").attr("disabled",false);
				$("input").attr("disabled",true);
				$("textarea").attr("disabled",true);
				$("#campanha_cancelar").attr("disabled",false);
			}

			$("#campanha_cancelar").click(function(){
				window.location.replace("campanhas_control.php");
				return false;
			});

			$("#btn_enviar_convites").click(function (){
				//Passar o código da campanha para o envio
				document.getElementById("loading").style.display = "block";
				var campanha = document.getElementById("campcab_id");
				//Chamar o envio
				$.ajax({
					url: "campanha_enviar_convites.php",
					method: "post",
					data: {campanha_id: campanha.value},
					async: true,
					success: function ( data ){
						document.getElementById("loading").style.display = "none";
						try{
							retorno = JSON.parse(data);
							switch(retorno.status){
								case 0:
									alert("Convites em processo de envio!");
									//retorna para o menu de campanhas
									window.location.replace("campanhas_control.php");
									break;
								case 100:
									window.location.replace("logout.php");
									break;
								default:
									mensagem="NÃO FOI POSSÍVEL ENVIAR OS CONVITES. \n\n";
									for (x in retorno.erros){
										mensagem+= "- " + retorno.erros[x] + "\n";
									}
									alert(mensagem);
							}
						} catch (e){
							alert(e);
							alert(data); 
						}						
					}
				});
						
				return false;
			});

			$("#btn_incluir_convites").click(function(){
				var cliente_nome = document.getElementById("campdet_nome");
				var cliente_cpf = document.getElementById("campdet_cpf");
				var cliente_email = document.getElementById("campdet_email");
				

				$.ajax({
					url: "campanha_incluir_convite_avulso.php",
					method: "post",
					async: false,
					data: {
						campdet_nome: cliente_nome.value,
						campdet_cpf: cliente_cpf.value,
						campdet_email: cliente_email.value,
						campcab_id: campanha_id.value
					},
					success: function( data ){
						console.log("<" + data + ">");
						retorno = JSON.parse(data);
						switch(retorno.status){
							case 0:
								carregar_convites(campanha_id.value);
								cliente_nome.value = "";
								cliente_cpf.value = "";
								cliente_email.value = "";
								break;
							case 100:
								window.location.replace("logout.php");
								break;
							default:
								mensagem = "NÃO FOI POSSÍVEL INCLUIR O CONVITE! \n";
								for (x in retorno.erros){
									mensagem += "- " + retorno.erros[x] + "\n";
								}
								alert(mensagem);							
						}
					}
				});
				return false;
			});
				

			$("#campanha_gravar").click(function(){

				var data = $("#form_campanha").serialize();

				function onErrorRequest( response ) {
					console.log("Deu ruim a bagaça:\n");
					console.log( response );
					return;
				}

				var client = new XMLHttpRequest();

				client.open("POST", "campanha_atualiza_cadastro.php", false);
				client.setRequestHeader("content-type","application/x-www-form-urlencoded");

				client.onreadystatechange = function( response )
				{
					
					if (this.readyState == 4 && this.status == 200){					
						
						var retorno = JSON.parse(this.responseText);
						switch(retorno.status){
							case 0:
								$("#campcab_id").val(retorno.ultimo_id);	
								$("#campcab_nome").val(retorno.nome);
								$("#campcab_mensagem").html(retorno.mensagem);
								var elemento=document.getElementById("div_convites");
								carregar_convites(retorno.ultimo_id);							
								elemento.style.display = "block";							
								elemento.scrollIntoView();
								break;
							case 100:
								window.location.replace("logout.php");
								break;
							default:
								var mensagem = "NÃO FOI POSSÍVEL INCLUIR A CAMPANHA! \n\n";
								for (x in retorno.erros){
									mensagem+= "- " + retorno.erros[x]};
								alert(mensagem);							
						}
						return false;
					}						
				};			  				
				client.send(data);
				return false;

			});
				
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

<div class="container divisor_caixas conteudo">
	
	<div class="row">
		<h1 class="titulo-pagina-detalhe">CADASTRAMENTO DE CAMPANHA</h1>
	</div>



	<div class="row">
		<div class="col-md-3"></div>
		<div class="col-md-6">			
			<span class="titulo_form_cadastro">NOVA CAMPANHA</span>
			<form id="form_campanha" method="post">			
				<input class="hidden" id="campcab_id" name="campcab_id" value="<?php echo $dados_campanha['campcab_id'];?>">
				<div class="form-group">
					<label for="campcab_nome">NOME DA CAMPANHA:</label>					
					<input class="form-control" type="text" id="campcab_nome" name="campcab_nome" value="<?php echo $dados_campanha['campcab_nome']?>">	
				</div>
				<div class="form-group">
					<label for="campcab_mensagem">MENSAGEM:</label>					
					<textarea class="form-control" id="campcab_mensagem" name="campcab_mensagem" rows=4><?php echo $dados_campanha['campcab_mensagem'] ?></textarea>
				</div>		
				<div class="row">
					<div class="col-md-12">
						<div id="botoes_adm_campanha">
							<button id="campanha_cancelar" class="btn btn-default">Sair sem gravar</button>
							<button id="campanha_gravar" class="btn btn-danger">Gravar e continuar</button>						
						</div>
					</div>
				</div>													
			</form>			
		</div>			
		<div class="col-md-3"></div>
	</div>

	<div class="row">
		<div class="col-md-3">
			<div class="adm_campanha_stat">
				<span>CRIAÇÃO DA CAMPANHA</span>
				<p>
				<?php echo $dados_campanha['campcab_criacao_data']?>
				</p>
			</div>
		</div>
		<div class="col-md-3">
			<div class="adm_campanha_stat">
				<span>ENVIO DOS CONVITES</span>
				<p>
				<span id="campcab_envio_data">
					<?php echo $dados_campanha['campcab_envio_data']?>
				</span>
				</p>
			</div>
		</div>
		<div class="col-md-3">
			<div class="adm_campanha_stat">
				<span>CONVITES GERADOS</span>
				<p id="qtd_convites">
					<?php echo $dados_campanha['convites']?>
				</p>
			</div>
		</div>
		<div class="col-md-3">
			<div class="adm_campanha_stat">
				<span>CONVERSAS RESULTANTES</span>
				<p>
				<?php echo $dados_campanha['conversas']?>
				</p>
			</div>
		</div>
	</div>

</div>

<div class="container divisor_caixas conteudo"  id="div_convites">
	
	<div class="row">
		<h1 class="titulo-pagina-detalhe">CONVITES PARA CHAT</h1>
	</div>

	<div class="container" id="form_convites">
		<div>
			<fieldset id="fieldset_convites" name="fieldset_convites">
				<form class="form-inline">
					<label for="campdet_nome">NOME:</label>
					<input class="form-control" type="text" name="campdet_nome" id="campdet_nome">
					<label for="campdet_cpf">CPF:</label>
					<input class="form-control" type="text" name="campdet_cpf" id="campdet_cpf">
					<label for="campdet_email">EMAIL:</label>
					<input class="form-control" type="email" name="campdet_email" id="campdet_email">
					<button class="btn btn-success" id="btn_incluir_convites"><i class="glyphicon glyphicon-plus"></i> Incluir convite</button>
					<button class="btn btn-primary" id="btn_importar_csv" name="btn_importar_csv" onclick="input_importar_arquivo.click();return false;">
						Importar CSV
					</button>
					<input type="file" class="btn btn-primary" id="input_importar_arquivo" name="input_importar_arquivo" onchange="carregar_arquivo_csv();return false;" style="display:none" accept=".csv">
					<button class="btn btn-primary" id="btn_enviar_convites" name="btn_enviar_convites">Enviar convites</button>
				</form>				
			</fieldset>			
		</div>
	</div>

	<div clss="container">
		<div id="retorno_load_csv">
		</div>
	</div>

	<div class="container">
		<div id="campanha_convites_lista">
			<?php montar_tabela_convites($convites) ?>
		</div>
	</div>

</div>

<div class="loading" id="loading">
	<img id="loading-gif" src="img/loading.gif">
</div>
<div id="fake_div"></div>
<!-- Rodapé padrão do site -->
<?php include "footer.html"; ?>

</body>

</html>

