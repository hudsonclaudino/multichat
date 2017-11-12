<?php
	class ConsisteFormataString{

		//Consiste o cpf informado
		public static function consiste_cpf( $parm_cpf ){

			$cpf = preg_replace("/[^0-9]/","",$parm_cpf);		    
		    $cpf = str_pad(trim($cpf), 11, '0', STR_PAD_LEFT);
		     
		    // Verifica se o numero de digitos informados é igual a 11 
		    if (strlen($cpf) != 11) {
		        return false;
		    }

		    if ($cpf == 0){
		    	return false;
		    }

		    // Verifica se nenhuma das sequências invalidas abaixo 
		    // foi digitada. Caso afirmativo, retorna falso
		    if ($cpf == '00000000000' || 
		        $cpf == '11111111111' || 
		        $cpf == '22222222222' || 
		        $cpf == '33333333333' || 
		        $cpf == '44444444444' || 
		        $cpf == '55555555555' || 
		        $cpf == '66666666666' || 
		        $cpf == '77777777777' || 
		        $cpf == '88888888888' || 
		        $cpf == '99999999999') {
		        return false;
			}
		     // Calcula os digitos verificadores para verificar se o
		     // CPF é válido	    
		         
			for ($t = 9; $t < 11; $t++) {
	             
	            for ($d = 0, $c = 0; $c < $t; $c++) {
	                $d += $cpf{$c} * (($t + 1) - $c);
	            }
	            $d = ((10 * $d) % 11) % 10;
	            if ($cpf{$c} != $d) {
	                return false;
	            }
	        }
		 
		    return true;	
		}

		public static function formata_cpf ($param_cpf){

			$cpf = preg_replace("/[^0-9]/","",($param_cpf));
			$cpf = str_pad(trim($cpf), 11, '0', STR_PAD_LEFT);
			$cpf_formatado = sprintf("%03d.%03d.%03d-%02d",substr($cpf,0,3),substr($cpf,3,3),substr($cpf,6,3),substr($cpf,9,2));
			return $cpf_formatado;

		}
/*
		public static function Remove_special_chars($text){

			$replaces = ("á" => "a",
				         "é" => "e");

			$return = strtr($text,$replaces);

			return $return;

		}
*/
	}
?>