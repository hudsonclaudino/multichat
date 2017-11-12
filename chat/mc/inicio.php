
<!DOCTYPE html>
<html>

<head>
	<title>MULTICHAT - Conectando pessoas</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<script src="jquery\jquery-3.2.1.js"></script>
	<script src="bootstrap/js/bootstrap.js"></script>	
	<link rel="stylesheet" href="css/multichat_style.css">

	<script>		

		var obj_mensagem_erro;

		function recup_senha(){
			
			$.trim($("#login-user"));
			if ($("#login-user").val()==""){
				alert("Informe o código de usuário");
				return false;
			} 
			
			$(".loading").show();
			$.ajax({
				url: 'recupera_senha.php',
				data: {usuario: $("#login-user").val()},
				method: "post",
				async: false,
				success: function (data){
					$(".loading").hide();
					alert(data);
					window.location.reload();
				}
			});
		}

		function mensagem_de_erro(mensagem){

			obj_mensagem_erro.innerHTML = mensagem;
			return false;
			
		}

		$(document).ready( function(){

			obj_mensagem_erro = document.getElementById("mensagem_erro");

			//Botão de login
			$("#btn-login").click( function(){
				obj_mensagem_erro.innerHTML = "";
				
				$("#inpgroup-user","#inpgroup-pw").removeClass("has-error");
				if ($("#login-user").val()=="" || $("#login-pw").val()==""){
					mensagem_de_erro("Informe o usuário e a senha.");
					//alert("Informe o usuário e a senha.");
					return false;
					
				}
				$(".loading").show();
				$.ajax({
					url: 'login_valida_usuario.php',
					method: 'post',
					data: $('#form-login').serialize(),					
					success: function(data){
						$(".loading").hide();
						if (data>0){	
	
							window.location.reload();
							return false;
						}	
						mensagem_de_erro("Usuário e/ou senha inválida!");
						
						//alert("Usuário e/ou senha inválida!");					
					}
				});
				$("#login-user").val("");
				$("#login-pw").val("");

				return false;
			})			

		})

	</script>
</head>

<body>

<!--
<header >
	<div class="container divisor_caixas" id="cabecalho">
		<div class="row">
			<div class="col-md-6 col-md-offset-0">
				<span id="logo">MULTICHAT</span>
			</div>
			<div class="col-md-6">
		
				<form class='form-inline' id='form-login'>
					<div class='input-group' id='inpgroup-user'>
						<span class='input-group-addon'><i class='glyphicon glyphicon-user'></i></span>
						<input type='text' id='login-user' name='login-user' class='form-control'>
					</div>
					<div class='input-group' id='inpgroup-pw'>
						<span class='input-group-addon'><i class='glyphicon glyphicon-lock'></i></span>
						<input type='password' id='login-pw' name='login-pw' class='form-control'>
					</div>				
					<button class='btn btn-success' id='btn-login'>
						Entrar
					</button>
				</form>
			</div>
			<div class="row">
				<a id="recup_senha" href="javascript:recup_senha();">Esqueci minha senha</a>			
			</div>
		</div>
	</div>
</header>
-->
<div class="container" id="system_ident_container">
	<div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-12 col-sx-offset-0">
		<div class="texto_ao_centro">
			<span id="logo">MULTICHAT</span>			
		</div>
		<div class="titulo-pagina-detalhe"><span class="login_texto">ACESSO AO SISTEMA</span></div>
		<div class="col-md-10 col-md-offset-1">	
			<form id='form-login'>
				<div class='input-group' id='inpgroup-user'>
					<span class='input-group-addon'><i class='glyphicon glyphicon-user'></i></span>
					<input type='text' id='login-user' name='login-user' class='form-control'>
				</div>				
				<div class='input-group' id='inpgroup-pw'>
					<span class='input-group-addon'><i class='glyphicon glyphicon-lock'></i></span>
					<input type='password' id='login-pw' name='login-pw' class='form-control'>
				</div>		
				<div>
					<button class='btn btn-success col-md-12' id='btn-login'>
						Entrar
					</button>
				</div>
			</form>
			<div class="texto_ao_centro">
				<a id="recup_senha" href="javascript:recup_senha();">Esqueci minha senha</a>			
			</div>
			<div id="mensagem_erro_container" class="texto_ao_centro">
			  <span id="mensagem_erro"></span>	  
			</div>
		</div>
	</div>
</div>

<!--
<div class="container divisor_caixas" id="conteudo">
	<div class="row">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<h1>O QUE VOCÊ QUER, SEU MANÉ?</h1>
			<img src="img/01.jpg" width=70%>		
			<h4>SE IDENTIFICA AÍ, OU XISPA FORA!</h4>
		</div>	
		<div class="col-md-3"></div>
	</div>
</div>
<div class="loading">	
	<img id="loading-gif" src="img/loading.gif">
</div>
<div class="container divisor_caixas" id="footer">
	<span>MULTICHAT - Uma empresa do grupo MULTISMS - 2017 - Todos os direitos reservados.</span>
</div>
-->
</body>

</html>

