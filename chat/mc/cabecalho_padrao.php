

<header>
	<div class="container divisor_caixas" id="cabecalho">
		<div class="col-md-6">
			<a href="index.php">
			<!--	<span id="logo">MULTICHAT</span> -->
					<img id="logo_achaves" src="img\andrade_chaves_logo.png" />
			</a>
		</div>
		<div class="col-md-6">

			<div class='row' id='info_usuario'>
				<span><i class='glyphicon glyphicon-user'></i></span>
				<span class="maiuscula"><?php echo $_SESSION["multichat_user"]?></span>
				<button class="btn btn-default btn-small" id="btn_menu_usuario" onclick="$('#menu_usuario').toggle();">
					<span class="glyphicon glyphicon-triangle-bottom"></span>
				</button>

				<br>				
				<div id='menu_usuario'>
					<ul>
						<li><a href="altera_senha_view.php">Alterar senha</a></li>
						<li class="separador"> </li>
						<li><a href='logout.php'>Sair</a></li>
					</ul>
				</div>
			</div>		
		</div>
	</div>
</header>
