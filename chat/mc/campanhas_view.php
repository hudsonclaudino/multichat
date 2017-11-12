<?php
	include_once "config.php";
	//Classe para a conexão com o banco de dados	
	include_once "class/ConexaoDB.php";

	//Classe para a pesquisa de usuários do sistema / atendentes e gerentes
	include_once "class/Usuario.php";

	//Classe com funções auxiliares comuns à aplicação toda
	include_once "class/Campanha.php";

	//Criação das instâncias das classes
	$Db_con = new ConexaoDB(ROOT);
	$Usuario = new Usuario($Db_con->conexao());
	$Campanha = new Campanha($Db_con->conexao());

	//Verifica se o usuário está logado no sistema, para evitar entradas diretas. Caso negativo, será direcionado para a tela de login
	//Verifica se o nível de acesso do usuário é o de gerente. Caso negativo, terá o acesso cancelado e será redirecionado para a tela de login.
	if (!$Usuario->usuario_logado() ||
		$_SESSION["multichat_nivel"]!=1){
		header("location: logout.php");
	}

	//Funções para a demonstração de dados na tela do gerente
	include "campanhas_view_funcoes.php"; 
?>

<!DOCTYPE html>
<html>

<head>
	<title>MULTICHAT - Conectando pessoas - Gerenciamento de campanhas</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<script src="jquery\jquery-3.2.1.js"></script>
	<script src="bootstrap/js/bootstrap.js"></script>	
	<script src="jquery\jquery.tablesorter.min.js"></script>
	<link rel="stylesheet" href="css/multichat_style.css">

	<script>

		function Carrega_Lista_Campanhas(argumento){
			
			$.ajax({
					url: "campanhas_botao_pesquisar.php",
					method: "post",
					data: {arg: argumento},
					async: false,
					success: retorno_carrega_lista_campanhas							
				});			
			return;		
		}

		function retorno_carrega_lista_campanhas (data){

			try{
				retorno = JSON.parse(data);
				switch(retorno.status){
					case 0:
						$("#lista_campanhas").html(retorno.campanhas);	
						break;
					case 100:
						window.location.replace("logout.php");
						break;
					default:
						mensagem = "Problemas ao carregar a lista de campanhas. \n";
						for (x in retorno.erros){
							mensagem += "\n - " + retorno.erros[x];							
						}
						alert(mensagem);
				}
			} catch(e){
				alert(e);
				alert(data);
			}

			return false;
		}

		function exclui_campanha(argumento){						
			
			if (!confirm('Ao excluir a campanha, todos os convites relativos a ela serão excluídos. Confirma a exclusão?')){
				return false;
			}

			$.ajax({
					url: "campanhas_excluir.php",
					method: "post",
					data: {campanha_id: argumento},
					async:false,
					success: retorno_exclui_campanha
				});
			return;
		}

		function retorno_exclui_campanha (data){

			try{
				retorno = JSON.parse(data);
				switch(retorno.status){
					case 0:
						Carrega_Lista_Campanhas($("#campanha_pesquisar_argumento").val());										
						break;
					case 100:
						window.location.replace("logout.php");
						break;
					default:
						mensagem = "Problemas ao excluir a campanha. \n";
						for (x in retorno.erros){
							mensagem += "\n - " + retorno.erros[x];							
						}
						alert(mensagem);
				}
			} catch(e){
				alert(e);
				alert(data);
			}

			return false;
		}

		function altera_campanha(argumento){
			var texto_html="<form id='altera' method='post' action='adm_campanha_control.php'>";
			texto_html+="<input type='hidden' name='campanha_id' value='";
			texto_html+=argumento;
			texto_html+="'></form>";
			document.getElementById("fake_div").innerHTML = texto_html;
			document.getElementById("altera").submit();
		}
	

		$(document).ready(function(){		

			//Aciona o classificador de dados da tabela	
			$("#tab_campanhas").tablesorter();	

			//Acionamento do botão de pesquisa para filtro da lista
			$("#campanha_pesquisar_botao").click(function(){		
				Carrega_Lista_Campanhas($("#campanha_pesquisar_argumento").val());				
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
			<span class="titulo-pagina-detalhe">GERENCIAMENTO DE CAMPANHAS</span>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<form action="adm_campanha_control.php" method="post">
				<button id="btn_incluir_campanha" class="btn btn-success btn-large" type="submit">
					<span class="glyphicon glyphicon-plus"></span>
					Incluir campanha
				</button>
			</form>
		</div>
		<div class="col-md-3"></div>
		<div class="col-md-3">
			<div class="filtro_usuario">
				<div id="pesquisa_usuario">
					<form>
					  	<div class="input-group">
    						<input type='text' id='campanha_pesquisar_argumento' name='campanha_pesquisar_argumento' class='form-control' placeholder="Pesquisar">
    						<div class="input-group-btn">
      							<button class="btn btn-success" name='campanha_pesquisar_botao' id='campanha_pesquisar_botao'>
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
		<div id="lista_campanhas" class="col-md-12">
			<?php montar_tabela_campanhas($campanhas); ?>
		</div>
	</div>
</div>
<div id='fake_div'></div>

<!-- Rodapé padrão do site -->
<?php include "footer.html"; ?>

</body>

</html>

