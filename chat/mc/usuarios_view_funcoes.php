<?php
	
	function montar_tabela_usuarios($registros){

		//Inicia tabela
		echo "<table id='tab_usuarios' class='table table-striped tabelas_padrao tablesorter'>";
		

		//Montagem da linha de cabeçalho
		echo "<thead>";
		echo "<tr>";
		echo "<th>Nome</th>";
		echo "<th>Login</th>";
		echo "<th>E-mail</th>";
		echo "<th class='texto_ao_centro'>Cadastrado em</th>";
		echo "<th class='texto_ao_centro'>Tipo de acesso</th>";
		echo "<th class='texto_ao_centro'>Online</th>";
		echo "<th class='texto_ao_centro'>Opções</th>";
		echo "</tr>";
		echo "</thead>";
		echo "<tbody>";

		//Montagem das linhas detalhe

		foreach($registros as $registro){

			$data_cadastro=date_format(date_create($registro['usuario_timestamp']),"d-m-Y");
			$acesso=$GLOBALS["Usuario"]->converte_tipo_acesso($registro['usuario_nivel']);
			//$status=$GLOBALS["Usuario"]->converte_tipo_status($registro['usuario_status']);
			$status="oo";
			$cor_status=$registro['usuario_status']==1? "usuario_online":"usuario_offline";

			echo "<tr>";
			echo "<td> {$registro['usuario_nome']} </td> ";
			echo "<td> {$registro['usuario_usuario']} </td> ";
			echo "<td> {$registro['usuario_email']} </td> ";			
			echo "<td class='texto_ao_centro'> {$data_cadastro} </td> ";
			echo "<td class='texto_ao_centro'> {$acesso} </td> ";
			echo "<td class='texto_ao_centro'><span class='{$cor_status}'>{$status} </span></td> ";			
			echo "<td class='texto_ao_centro'>";
			echo "<a class='btn_usuario_acoes' href='adm_usuario_control.php?id={$registro['usuario_id']};' alt='atualizar cadastro'><i class='glyphicon glyphicon-pencil'></i> </a>";
			echo "<a class='btn_usuario_acoes' href='javascript:exclui_usuario({$registro['usuario_id']});' alt='Remover usuário'><i class='glyphicon glyphicon-trash'></i></a>";
			echo "</td> ";			
			echo "</tr>";
		}

		//Encerra a tabela
		echo "</tbody>";
		echo "</table>";

	}
?>
