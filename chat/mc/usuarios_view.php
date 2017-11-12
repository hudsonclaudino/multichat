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
	//Verifica se o nível de acesso do usuário é o de gerente. Caso negativo, terá o acesso cancelado e será redirecionado para a tela de login.
	if (!$Usuario->usuario_logado() ||
		$_SESSION["multichat_nivel"]!=1){
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

		function Carrega_Lista_Usuarios(argumento){
			
			$.ajax({
					url: "usuarios_botao_pesquisar.php",
					method: "post",
					data: {arg: argumento},
					async: false,
					success: carrega_lista_usuarios_retorno
				});			
			return;		
		}

		function carrega_lista_usuarios_retorno( data ){
			try{
				retorno = JSON.parse(data);
					switch(retorno.status){
						case 0:
							$("#lista_usuarios").html(retorno.usuarios);					
							break;
						case 100:
							window.location.replace("logout.php");
							break;
						default:
							mensagem = "NÃO FOI POSSÍVEL OBTER A LISTA DE USUÁRIOS. \n";
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

		function exclui_usuario(argumento){						
			
			$.ajax({
					url: "usuarios_excluir.php",
					method: "post",
					data: {usuario_id: argumento},
					async:false,
					success: exclui_usuario_retorno					
				});
			return;
		}

		function exclui_usuario_retorno( data ){
			try{
				retorno = JSON.parse(data);
					switch(retorno.status){
						case 0:
							Carrega_Lista_Usuarios($("#usuario_pesquisar_argumento").val());										
							break;
						case 100:
							window.location.replace("logout.php");
							break;
						default:
							mensagem = "Não foi possível excluir o usuário. \n";
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
	

		$(document).ready(function(){		

			//Aciona o classificador de dados da tabela	
			$("#tab_usuarios").tablesorter();	

			//Acionamento do botão de pesquisa para filtro da lista
			$("#usuario_pesquisar_botao").click(function(){		
				Carrega_Lista_Usuarios($("#usuario_pesquisar_argumento").val());				
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
		<div class="col-md-6">
			<form action="adm_usuario_control.php">
				<button id="btn_incluir_usuario" class="btn btn-success btn-large" type="submit">
					<span class="glyphicon glyphicon-plus"></span>
					Incluir usuário
				</button>
			</form>
		</div>
		<div class="col-md-3"></div>
		<div class="col-md-3">
			<div class="filtro_usuario">
				<div id="pesquisa_usuario">
					<form>
					  	<div class="input-group">
    						<input type='text' id='usuario_pesquisar_argumento' name='usuario_pesquisar_argumento' class='form-control' placeholder="Pesquisar">
    						<div class="input-group-btn">
      							<button class="btn btn-success" name='usuario_pesquisar_botao' id='usuario_pesquisar_botao'>
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
		<div id="lista_usuarios" class="col-md-12">
			<?php montar_tabela_usuarios($usuarios); ?>
		</div>
	</div>
</div>


<!-- Rodapé padrão do site -->
<?php include "footer.html"; ?>

</body>

</html>

