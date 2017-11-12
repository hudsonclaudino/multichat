<?php

class Auxiliares{

	public function converte_data_para_texto($data_entrada){
		return date_format(date_create($data_entrada),"d-m-y");		
	}

	public function trata_nulo($entrada, $substituicao=""){
		$retorno = is_null($entrada)? $substituicao : $entrada;
		return $retorno;
	}


}

?>