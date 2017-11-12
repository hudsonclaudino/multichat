<?php

	//Classe para a conexão com o banco de dados	
	include_once "class/ConexaoDB.php";

	//Classe para a pesquisa de usuários do sistema / atendentes e gerentes
	include_once "class/Usuario.php";

	//Criação das instâncias das classes
	$Db_con = new ConexaoDB(ROOT);
	$Usuario = new Usuario($Db_con->conexao());

?>

<!DOCTYPE html>
<html>

<head>
	<title>MULTICHAT - Conectando pessoas - Alteração de senha</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.css">
	<script src="jquery\jquery-3.2.1.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>	
	<link rel="stylesheet" href="css/multichat_style.css">
	
</head>

<body>

<header>

	<div class="container divisor_caixas" id="cabecalho">
		<div class="col-md-12">
			<span id="logo">MULTICHAT</span>
		</div>		
	</div>
</header

<div class="container">
	<div class="row">
		<div class="col-md-4"></div>
		<div class="box_troca_senha col-md-4">
			<div class="titulo-box-gerente texto_ao_centro">
				ALTERAÇÃO DE SENHA
			</div>
			<div class="form_troca_senha">
				<form action="altera_senha_control.php" method="post">
					<div class="form-group row">						
						<label for="usuario_usuario" class="control-label col-md-3 texto_a_direita">CÓDIGO DE USUÁRIO:</label>
						<div class="col-md-9">
							<input type="text" class="form-control" id="usuario_usuario" name="usuario_usuario"
							<?php
								 if (isset($usuario_usuario)){
								 	echo 'value=' . $usuario_usuario;
							} ?>
							>
							<?php
								 if(isset($erros['usuario_nao_informado'])){
								 	echo "<span class='mensagem_erro'>" . $erros['usuario_nao_informado'] . "</span><br>";
								 } 	
								 if(isset($erros['acesso_nao_autorizado'])){
								 	echo "<span class='mensagem_erro'>" . $erros['acesso_nao_autorizado'] . "</span><br>";
								 } 	
								 
								 ?>							
						</div>						
					</div>
					<div class="form-group row">
						<label for="usuario_senha-atual" class="control-label col-md-3 texto_a_direita">SENHA ATUAL:</label>
						<div class="col-md-9">
							<input type="password" class="form-control" id="usuario_senha_atual" name="usuario_senha_atual"
							<?php
								if (isset($usuario_pw)){
								 	echo 'value=' . $usuario_pw;
							} ?>
							>
							<?php
								 if(isset($erros['senha_atual_nao_informada'])){
								 	echo "<span class='mensagem_erro'>" . $erros['senha_atual_nao_informada'] . "</span><br>";
								 } 	?>	
						</div>						
					</div>
					<div class="form-group row">
						<label for="usuario_nova_senha" class="control-label col-md-3  texto_a_direita">NOVA SENHA:</label>
						<div class="col-md-9">
							<input type="password" class="form-control" id="usuario_nova_senha" name="usuario_nova_senha"
							<?php
								if (isset($usuario_nova_senha)){
								 	echo 'value=' . $usuario_nova_senha;
							} ?>
							>								
							<?php
								 if(isset($erros['nova_senha_nao_informada'])){
								 	echo "<span class='mensagem_erro'>" . $erros['nova_senha_nao_informada'] . "</span><br>";
								 }
								 if(isset($erros['senha_nao_alterada'])){
								 	echo "<span class='mensagem_erro'>" . $erros['senha_nao_alterada'] . "</span><br>";
								 }

								 ?>
						</div>						
					</div>
					<div class="form-group row">
						<label for="usuario_nova_senha_confirmacao" class="control-label col-md-3  texto_a_direita">CONFIRME A SENHA:</label>
						<div class="col-md-9">
							<input type="password" class="form-control" id="usuario_nova_senha_confirmacao" name="usuario_nova_senha_confirmacao"
							<?php
								if (isset($usuario_confirmacao)){
								 	echo 'value=' . $usuario_confirmacao;
							} 
							?>	
							>	
							<?php
								 if(isset($erros['confirmacao_nao_informada'])){
								 	echo "<span class='mensagem_erro'>" . $erros['confirmacao_nao_informada'] . "</span><br>";
								 } 	
								 if(isset($erros['confirmacao_nao_confere'])){
								 	echo "<span class='mensagem_erro'>" . $erros['confirmacao_nao_confere'] . "</span><br>";
								 } 	
								 ?>
						</div>						
					</div>	
					<div>
						<div class="botoes_usuario">
							<button class="btn btn-default" id="usuario_cancelar" name="usuario_cancelar" type="button" onclick="javascript: window.location.replace('index.php');">
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
		<div class="col-md-4"></div>
	</div>	
</div>

<!-- Rodapé padrão do site -->
<?php include "footer.html"; ?>

</body>

</html>

