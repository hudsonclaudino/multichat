<?php

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
	include "usuarios_view_funcoes.php";
?>

<!DOCTYPE html>
<html>

<head>
	<title>MULTICHAT - Conectando pessoas - Painel administrativo</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<script src="jquery\jquery-3.2.1.js"></script>
	<script src="bootstrap/js/bootstrap.js"></script>	
	<script src="jquery\jquery.tablesorter.min.js"></script>
	<link rel="stylesheet" href="css/multichat_style.css">
	
	<script>
		$(document).ready(function(){

			$("#usuario_cancelar").click(function(){
				window.location.replace("usuarios_control.php");
			});

			$("#usuario_enviar").click(function(){
				$(".loading").show();
				
				$.ajax({
					url: "usuario_atualiza_cadastro.php", 
					method: "post",
					data: $("#form_usuario").serialize(),
					success: function(data){
						$(".loading").hide();
						try {
							Retorno = JSON.parse(data);
							switch(Retorno.status){
								case 0:
									window.location.replace("usuarios_control.php");
									break;
								case 100:
									window.location.replace("logout.php");
									break;
								default:
									mensagem = "NÃO FOI POSSÍVEL ATUALIZAR O USUÁRIO \n";
									for (x in Retorno.erros){
										mensagem += "\n - " + Retorno.erros[x];
									}
									alert(mensagem);
									return false;
							}
						} catch (e){
							alert(e);
							alert(data);
						}
					}
				});
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

<div class="container divisor_caixas" id="conteudo">
	<div class="row">
		<div class="col-md-12">
			<span class="titulo-pagina-detalhe">CADASTRO DE USUÁRIOS</span>
		</div>
	</div>	
	<div class="row">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<div class="row ">
				<span class="titulo_form_cadastro"><?php echo ($dados_usuario['usuario_id']>0) ? "USUÁRIO " . strtoupper($dados_usuario['usuario_usuario']) : "NOVO USUÁRIO" ?></span>
			</div>
			<div class="row">
				<div class="form_cadastro">
					<form id="form_usuario" class="form-horizontal">
						<input type="hidden" id="usuario_id" name="usuario_id" <?php echo ($dados_usuario['usuario_id']>0) ? "value=" . $dados_usuario['usuario_id'] : "" ?>>
						<div class="form-group">
							<label for="usuario_usuario" class="control-label col-md-3">USUÁRIO:</label>
							<div class="col-md-6">
								<input type="text" class="form-control" id="usuario_usuario" name="usuario_usuario" 
								<?php echo ($dados_usuario['usuario_id']>0) ? "value='" . strtoupper($dados_usuario['usuario_usuario']) . "' readonly" : "required" ?>>
							</div>
							<div class="col-md-3"></div>
						</div>
						<div class="form-group">
							<label for="usuario_nome" class="control-label col-md-3">NOME:</label>
							<div class="col-md-9">
								<input type="text" class="form-control" id="usuario_nome" name="usuario_nome"
								<?php echo ($dados_usuario['usuario_id']>0) ? "value='" . $dados_usuario['usuario_nome'] . "'" : "required" ?>>		 
							</div>
						</div>
						<div class="form-group">
							<label for="usuario_email" class="control-label col-md-3">E-MAIL:</label>
							<div class="col-md-9">
								<input type="email" class="form-control" id="usuario_email" name="usuario_email"
								<?php echo ($dados_usuario['usuario_id']>0) ? "value='" . $dados_usuario['usuario_email'] . "'" : "required" ?>>	 
							</div>
						</div>
						<div class="form-group">
							<label for="usuario_nivel" class="control-label col-md-3">TIPO:</label>
							<div class="col-md-9">
								<label class="radio-inline">
									<input type="radio"  id="usuario_nivel" name="usuario_nivel" value="1"
									<?php echo ($dados_usuario['usuario_nivel']==1) ? "checked" : "" ?>>GERENTE
								</label>
								<label class="radio-inline">
									<input type="radio"  id="usuario_nivel" name="usuario_nivel" value="2"
									<?php echo ($dados_usuario['usuario_nivel']!=1) ? "checked" : "" ?>>OPERADOR
								</label>
							</div>
						</div>	
						<div>
							<div class="botoes_usuario">
								<button class="btn btn-default" id="usuario_cancelar" name="usuario_cancelar" type="button">
									Cancelar
								</button>
								<button class="btn btn-danger" id="usuario_enviar" name="usuario_enviar" type="submit">
									Enviar
								</button>
							</div>
						</div>							
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-3"></div>
	</div>
</div>
<div class="loading">
	<img id="loading-gif" src="img/loading.gif">
</div>

<!-- Rodapé padrão do site -->
<?php include "footer.html"; ?>

</body>

</html>

