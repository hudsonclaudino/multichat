<!DOCTYPE html>
<html>

<head>
	<title>MULTICHAT - Conectando pessoas - Painel administrativo</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">	
	
	<style>
		* {
			box-sizing:border-box;
		}

		body{
			background-color:#ccc;
		}

		.col-8-centro{
			width:66.66%;
			margin-left:16.66%;
		}
		[class*="col-"] {
		    float: left;
		    padding: 15px;
		}
		.col-1 {width: 8.33%;}
		.col-2 {min-width: 16.66%;border:1px solid red;}
		.col-3 {width: 25%;}
		.col-4 {width: 33.33%;}
		.col-5 {width: 41.66%;}
		.col-6 {width: 50%;}
		.col-7 {width: 58.33%;}
		.col-8 {width: 66.66%;border:1px solid red;}
		.col-9 {width: 75%;}
		.col-10 {width: 83.33%;}
		.col-11 {width: 91.66%;}
		.col-12 {width: 100%;}

		.row::after {
		    content: "";
		    clear: both;
		    display: table;
		}

		/* Logo do site */
		@font-face{
			font-family:fonte_do_logo;
			src: url("../font/BoltBoldBT.tff");
		}

		#logo{
			font-family: fonte_do_logo;
			color:#a94442;
			font-weight: bold;
			font-size:2.3em;
			text-shadow: 2px 2px 2px rgba(0,0,0,0.4);
		}

		.divisor_caixas{
			border-bottom:1px solid #ccc;
			margin-bottom:3px;
		}


		#conteudo{
			
			background-color:white;	
			padding:5px;
			text-align:center;

		}

		#conteudo h1{
			margin-top:2px;
		}

		/************** FIM DA TELA DE ABERTURA DO SISTEMA */
		#footer{
			height:30px;
			
			background-color:white;
			padding:5px 0px;
			
			text-align:center;
			color:#ccc;
		}

		h2{
			color:white;
			background-color:#a94442;
			display:block;
			padding:5px 30px;
			border-radius:30px;
			margin-top:15px;
		}

		p{
			font-size:1.5em;
		}

		.mensagem_operador_texto{	
			color:blue;	
			margin-top:5px;	
			display: block;
		}

		.mensagem_cliente_texto{	
			color:green;	
			margin-top:5px;	
			display: block;
		}

		.mensagem_data_corrente{
			display:block;
			margin-top:10px;
			margin-bottom:10px;
			padding:5px 10px;
			text-align: center;	
			color:black;
			font-size:1.2em;
			font-weight: bold;
			background-color:#f3f3f3;
			border-radius:40px;
		}

		.mensagem_hora_corrente{
			font-size:0.5em;
		}

		.conversa_finalizada{
			text-align:center;
		}

		.conversa_finalizada > span{
			padding: 5px 10px;
			background-color:#f3f3f3;
			color:black;
			border-radius:20px;
			font-size:1.2em;
			margin-top:10px;
			margin-bottom:10px;
		}



	</style>
</head>

<?php
	header('Content-Type: text/html; charset=utf-8');
	if (!isset($email_titulo)){
		$email_titulo="Título padrão para testes";
	}

	if (!isset($email_mensagem)){
		$email_mensagem="Mensagem padrão para testes";
	}
?>

<body>
	<div class="row">
		
		<div class="col-8-centro">
			<div>
				<div class="divisor_caixas" id="cabecalho">
					<div class="col-12" style="text-align:center">
						<span id="logo">MULTICHAT</span>
					</div>		
				</div>
			</div>

			<div class="divisor_caixas" id="conteudo">
				<h2><?php echo $email_titulo; ?></h2>
				<p><?php echo $email_mensagem; ?></p>			
			</div>

			
			<div id="footer">
				<span>MULTICHAT - Uma empresa do grupo MULTISMS - 2017 - Todos os direitos reservados.</span>
			</div>
		</div>
		
	</div>

</body>

</html>
	