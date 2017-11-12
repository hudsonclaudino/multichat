
function contarLinhasTabela(idTabela){
	var tabela = document.getElementById(idTabela);
	var linhas = tabela.getElementsByTagName('tr').length;
	return (linhas - 1);
}

function leading_zeros(valor,tamanho){

	var zeros_a_esquerda = "";

	if (valor.toString().length >= tamanho){
		return valor.toString();
	}

	for (ind=0;ind < (tamanho - valor.toString().length);ind++){
		zeros_a_esquerda+="0";
	}

	valor_formatado = zeros_a_esquerda + valor.toString();
	return valor_formatado;
}

function formata_data( data ){

	formatada = leading_zeros(data.getDate(),2)+"/"+leading_zeros(Number(data.getMonth())+1,2)+"/"+data.getFullYear();
	return formatada;
}

function formata_data_YMD( data ){

	formatada = data.getFullYear()+"-"+leading_zeros(Number(data.getMonth())+1,2)+"-"+leading_zeros(data.getDate(),2);
	return formatada;
}

function formata_horario( data ){

	formatado = leading_zeros(data.getHours(),2) + ":" + leading_zeros(data.getMinutes(),2);
	return formatado;
}

function milissegundos_em_tempo_decorrido(mili){

	var padrao_segundos = 1000; //1000 milissegundos
	var padrao_minutos =  60; //60 segundos
	var padrao_horas =  60; //60 minutos
	var padrao_dias =  24; //24 horas
	
	var total_segundos = Math.floor(mili / padrao_segundos);

	var total_minutos = Math.floor(total_segundos / padrao_minutos);
	var segundos = total_segundos - (total_minutos * padrao_minutos);

	var total_horas = Math.floor(total_minutos / padrao_horas);
	var minutos = total_minutos - (total_horas * padrao_horas);

	var dias = Math.floor(total_horas / padrao_dias);
	var horas = total_horas - (dias * padrao_dias);

	var hora_form = "";

	if (dias > 0){
		hora_form += dias.toString() + "d ";
	}

	if (horas > 0){
		hora_form += horas.toString() + "h ";
	}

	if (minutos > 0){
		hora_form += minutos.toString() + "m ";
	}

	if (segundos > 0){
		hora_form += segundos.toString() + "s";
	}

	if (hora_form.length==0){
		hora_form = "0s";
	}

	return hora_form;

}