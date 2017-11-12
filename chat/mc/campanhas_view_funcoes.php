<?php
	header("Content-type: text/html; charset=utf-8"); 
	function montar_tabela_campanhas($registros){

		if (is_null($registros)){
			echo "<p><p>Não há campanhas criadas no sistema!";
			return;
		}
		//Inicia tabela
		echo "<table id='tab_campanhas' class='table table-striped tabelas_padrao tablesorter'>";

		//Montagem da linha de cabeçalho
		echo "<thead>";
		echo "<tr>";
		echo "<th>ID</th>";
		echo "<th>Nome</th>";
		echo "<th class='texto_ao_centro'>Criada em </th>";
		echo "<th>por</th>";
		echo "<th class='texto_ao_centro'>Enviada em </th>";
		echo "<th>por</th>";
		echo "<th class='texto_ao_centro'>Status</th>";
		echo "<th class='texto_ao_centro'>Convites</th>";
		echo "<th class='texto_ao_centro'>Conversas</th>";
		echo "<th class='texto_ao_centro'>Opções</th>";
		echo "</tr>";
		echo "</thead>";
		echo "<tbody>";	

		//Montagem das linhas detalhe

		foreach($registros as $registro){

			$criacao_data = date_format(date_create($registro['criacao_data']),"d-m-y");

			if (is_null($registro['envio_data'])){
				$envio_data="Não enviado";
				$envio_usuario="";
			} else {
				$envio_data=date_format(date_create($registro['envio_data']),"d-m-y");
				$envio_usuario = $registro['envio_usuario'];
			}
	
			$GLOBALS['Campanha']->set_campcab_status($registro['status']);
			$status=$GLOBALS['Campanha']->get_campcab_status_extenso();

			echo "<tr>";
			echo "<td> {$registro['id']} </td> ";
			echo "<td> {$registro['nome']} </td> ";
			echo "<td class='texto_ao_centro'> {$criacao_data} </td> ";			
			echo "<td> {$registro['criacao_usuario']} </td> ";	
			echo "<td class='texto_ao_centro'> {$envio_data} </td> ";			
			echo "<td> {$envio_usuario} </td> ";	
			echo "<td class='texto_ao_centro'> {$status} </td> ";	
			echo "<td class='texto_ao_centro'> {$registro['envios']} </td> ";			
			echo "<td class='texto_ao_centro'> {$registro['conversas']} </td> ";			
	
			echo "<td class='texto_ao_centro'>";
			
			$classe = (is_null($registro['envio_data']))? "glyphicon-pencil" : "glyphicon-search"	;

			echo "<a class='btn_usuario_acoes' href='javascript:altera_campanha({$registro['id']});' alt='Alterar campanha'><i class='glyphicon {$classe}'></i></a>";	

			echo "<a class='btn_usuario_acoes' href='javascript:exclui_campanha({$registro['id']});' alt='Remover campanha'><i class='glyphicon glyphicon-trash'></i></a>";
			echo "</td> ";			
			echo "</tr>";
		}

		//Encerra a tabela
		echo "</tbody>";
		echo "</table>";

	}

	function montar_tabela_convites($registros){

		
		//Inicia tabela
		echo "<table id='tab_convites' class='table table-striped tabelas_padrao tablesorter'>";

		//Montagem da linha de cabeçalho
		echo "<thead>";
		echo "<tr>";		
		echo "<th class='col-md-4'>NOME</th>";
		echo "<th class='texto_ao_centro  col-md-3'>CPF</th>";
		echo "<th class='col-md-3'>EMAIL</th>";
		echo "<th class='texto_ao_centro col-md-1'>CONVERSAS</th>";		
		echo "<th class='texto_ao_centro col-md-1'>OPÇÕES</th>";
		echo "</tr>";
		echo "</thead>";
		echo "<tbody>";	

		if (is_null($registros)){
			//Encerra a tabela
			echo "</tbody>";
			echo "</table>";
			echo "<p class='texto_ao_centro'><p>Não há convites cadastrados para esta campanha.";
			return;
		}
		//Montagem das linhas detalhe

		foreach($registros as $registro){

			$cpf_num = is_numeric($registro['campdet_cliente_cpf'])? str_pad(trim($registro['campdet_cliente_cpf']),11,0,STR_PAD_LEFT): "00000000000";
			$cpf = sprintf("%03u.%03u.%03u-%02u",substr($cpf_num,0,3),substr($cpf_num,3,3),substr($cpf_num,6,3),substr($cpf_num,-2));

			echo "<tr>";
			echo "<td>{$registro['campdet_cliente_nome']}</td>";
			echo "<td class='texto_ao_centro'>{$cpf}</td>";
			echo "<td>{$registro['campdet_cliente_email']}</td>";
			echo "<td class='texto_ao_centro'>{$registro['total_conversas']}</td>";
			echo "<td class='texto_ao_centro'>";
			echo "<a class='btn_usuario_acoes' href='javascript:exclui_convite({$registro['campdet_id']});' alt='Remover convite'><i class='glyphicon glyphicon-trash'></i></a>";
			echo "</td>";

		}

		echo "</tbody>";
		echo "</table>";
		return;			

	}
?>
