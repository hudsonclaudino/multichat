<?php

class Enviar_email 

{

	private $Email;

	//tipo do email a ser enviado
	//0 - Mensagem padrão
	//1 - Nova Senha
	private $tipo_email;

	//texto livre
	private $mensagem;
	private $titulo;	
	
	
	public function __construct(){
	
		header('Content-Type: text/html; charset=utf-8');
		
		include "library/PHPMailer/PHPMailerAutoload.php";				
		$this->Email=new PHPMailer();	
		$this->Email->isSMTP();

		try{
			$this->Email->CharSet="utf-8";
			$this->Email->Host=EMAIL_SERVER;
			$this->Email->Port=587;
			$this->Email->SMTPSecure='tls';
			$this->Email->SMTPAuth=true;
			$this->Email->Username=EMAIL_USER;
			$this->Email->Password=EMAIL_PASSWORD;
			$this->Email->setFrom(EMAIL_USER,EMAIL_DE);
		}
		catch (phpmailerException $e)
		{
			echo $e->errorMessage();
			die();
		}

		$this->tipo_email = 0;

	}

	public function setDestinatario($destinatario){
		$this->Email->addAddress($destinatario);
	}

	public function setAssunto($assunto){
		$this->Email->Subject=$assunto;
	}

	public function setTitulo($titulo){
		$this->titulo=$titulo;
	}

	public function setMensagem($mensagem){
		$this->mensagem=$mensagem;
	}

	public function setHTML($html){
		$this->Email->msgHTML($html);
	}

	public function LimpaListaDeDestinatarios(){
		$this->Email->ClearAllRecipients();

	}

	public function formata_mensagem(){

		ob_start();

		switch ($this->tipo_email) {
			case '1':
				include "..\emails\email_padrao.php";
				break;
			
			default:
				$email_titulo=$this->titulo;
				$email_mensagem=$this->mensagem;				
				include "emails/email_padrao.php";
				break;
		}

		$mensagem=ob_get_contents();
		ob_end_clean();
		
		$this->setHTML($mensagem);
		return;
	}

	public function enviar(){
		
		if (!$this->Email->send()){
			echo "Deu erro na bagaça: {$this->Email->ErrorInfo}";
		}
		
	}
		
}
		
	