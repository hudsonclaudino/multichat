<?php
	
	function montar_tabela_atendentes_online($atendentes){

		//Inicia tabela
		echo "<table class='table table-condensed table-striped table-bordered'>";

		//Montagem da linha de cabeçalho
		echo "<tr>";
		echo "<th>Nome do operador</th>";
		echo "<th>Código de usuário</th>";
		echo "<th>Atendimentos hoje</th>";
		echo "</tr>";

		//Montagem das linhas detalhe
		foreach($atendentes as $atendente){
			echo "<tr>";
			echo "<td> {$atendente['usuario_nome']} </td> ";
			echo "<td> {$atendente['usuario_usuario']} </td>";
			echo "<td> {$atendente['conversas']} </td>";
			echo "</tr>";
		}

		//Encerra a tabela
		echo "</table>";

	}

	function montar_tabela_ultimas_campanhas($ultimas_campanhas){

		$quant_campanhas = 5;
		$ind_campanhas = 0;

		if (count($ultimas_campanhas)==0){
			echo "<br><br>Não há campanhas para pesquisa";
			return;
		}

		//Inicia tabela
		echo "<table class='table table-condensed table-striped table-bordered'>";

		$sql = "select a.campcab_id as id, a.campcab_nome as nome, a.campcab_criacao_data as criacao_data, a.campcab_envio_data as envio_data, a.campcab_status as status, count(b.campdet_campcab_id) as qtd_envios from MULTICHAT_CAMPANHA_CAB a left join MULTICHAT_CAMPANHA_DET b on a.campcab_id = b.campdet_campcab_id group by a.campcab_id order by a.campcab_criacao_data desc, a.campcab_id desc limit 10";

		//Montagem da linha de cabeçalho
		echo "<tr>";
		
		echo "<th>Nome</th>";
		echo "<th>Criação</th>";
		echo "<th>Envio</th>";
		
		echo "<th>Destinatários</th>";
		echo "<th>Conversas</th>";
		echo "</tr>";

		//Montagem das linhas detalhe
		foreach($ultimas_campanhas as $campanha){

			$data_criacao = date_format(date_create($campanha['criacao_data']),"d-m-y");

			if (is_null($campanha['envio_data'])){
				$data_envio = "Pendente";
			} else{
				$data_envio = date_format(date_create($campanha['envio_data']),"d-m-y");
			}

			$GLOBALS['Campanha']->set_campcab_status($campanha['status']);
			
			
			echo "<tr>";
			
			echo "<td> {$campanha['nome']} </td> ";
			echo "<td> {$data_criacao} </td> ";
			echo "<td> {$data_envio} </td> ";
			
			echo "<td> {$campanha['qtd_envios']} </td> ";
			echo "<td> 0</td> ";			

			echo "</tr>";

			$ind_campanhas+=1;
			if ($ind_campanhas >= $quant_campanhas){
				break;
			}
		}

		//Encerra a tabela
		echo "</table>";

	}
?>
