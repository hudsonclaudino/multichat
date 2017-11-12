<?php include_once "operador.php";?>

<head>
<script src="js/moments.min.js"></script>
<script>

	//Efetua a pesquisa da lista de espera
	function pesquisa_fila_espera(){

		$.ajax({
			url: "operador_pesquisa_fila_espera.php",
			method: "post",
			async: false,
			success: carrega_fila_espera
		});

		return false;

	}

	//carga da fila de espera retornada pelo ajax
	function carrega_fila_espera( data ){
		try{
			retorno = JSON.parse(data);
				switch(retorno.status){
					case 0:
						lista = carrega_tabela_lista_espera(retorno.conversas);
						fila = document.getElementById("fila_de_espera");
						fila.innerHTML = lista;
						break;
					case 100:
						window.location.replace("logout.php");
						break;
					default:
						mensagem = "NÃO FOI POSSÍVEL OBTER A LISTA DE ESPERA. \n";
						for (x in retorno.erros){
							mensagem+= "\n- " + retorno.erros[x];}
						alert(mensagem);									
				}
		} catch (e){
			fila = document.getElementById("fila_de_espera");
			fila.innerHTML = data;
		}
		return false;
	}

	function carrega_tabela_lista_espera ( lista ){

		vazia = "<div class='lista_vazia'>Não há clientes aguardando atendimento<br>no momento.</div>";

		cabec = "<table id='tab_espera' class='table table-striped tabelas_padrao tablesorter espaco_sup_20'>";
		cabec+= "<thead>";
		cabec+= "<th class='col-md-1 texto_ao_centro'>Posição</th>";
		cabec+= "<th class='col-md-4'>Nome do cliente</th>";
		cabec+= "<th class='col-md-2'>CPF</th>";
		cabec+= "<th class='col-md-2'>Email</th>";
		cabec+= "<th class='col-md-2 texto_ao_centro'>Tempo na fila</th>";
		cabec+= "<th class='col-md-1'>AÇÕES</th>";
		cabec+= "</thead>";	

		retorno = cabec;

		var detalhe="";

		for (x in lista){		
			linha = Number(x) + 1;
			moment.locale("pt");
			horario = moment(lista[x].conversa_fila_entrada);
			elapsed = horario.fromNow();
			
		
			detalhe += "<tr>";
			detalhe += "<td class='texto_ao_centro'>" + linha + "</td>";						
			detalhe += "<td>" + lista[x].conversa_cliente_nome + "</td>";			
			detalhe += "<td>" + lista[x].conversa_cliente_cpf + "</td>";			
			detalhe += "<td>" + lista[x].conversa_cliente_email + "</td>";			
			detalhe += "<td class='texto_ao_centro'>" + elapsed + "</td>";			
			detalhe += "<td>x</td>";			
			detalhe += "</tr>";
		}

		fim_tab = "</table>";

		retorno +=detalhe + fim_tab;
		
		if (lista.length == 0){
			retorno += vazia;
		}		

		return retorno;
	}

	$(document).ready(function(){
		pesquisa_fila_espera();
	});
</script>
</head>

<body>

<div class="container divisor_caixas" id="conteudo">
	<div class="row">
		<div class="col-md-12">
			<span class="titulo-pagina-detalhe">FILA DE ESPERA PARA ATENDIMENTO</span>
		</div>
	</div>
	<div class="row">
		<div id="fila_de_espera" class="col-md-12">
				
		</div>
	</div>
</div>

<!--Rodapé-->
<?php include_once "footer.html";?>

</body>