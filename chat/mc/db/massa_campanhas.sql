

insert into MULTICHAT_CAMPANHA_CAB (campcab_nome, campcab_descricao, campcab_mensagem,campcab_criacao_usuario) values
	('GM001','General Motors - Julho/2017','Venha saldar seus débitos com desconto de até 40%','gerente'),
	('BRAD001','Bradesco - Julho/2017','Quite o seu cartão com 30% de desconto','gerente'),
	('BAHIA001','Casas Bahia - Julho/2017','Quite seus débitos e volte a comprar conosco!','gerentejr');

insert into MULTICHAT_CAMPANHA_DET (campdet_campcab_id, campdet_cliente_nome, campdet_cliente_cpf,campdet_cliente_email,campdet_cadastro_usuario) values
	(1,'João da Silva',11111111111,'niltonssjr@gmail.com','gerente'),
	(1,'Pedro de Almeida',22222222222,'niltonssjr@gmail.com','gerente'),
	(1,'Luis Mariano',33333333333,'niltonssjr@gmail.com','gerente'),
	(1,'Fabio Conceição',44444444444,'niltonssjr@gmail.com','gerente'),
	(1,'Jussara Souza',55555555555,'niltonssjr@gmail.com','gerente'),
	(2,'Afonso Lopes de Baião',66666666666,'niltonssjr@gmail.com','gerente'),
	(2,'Ricardo de Oliveira Santos',77777777777,'niltonssjr@gmail.com','gerente'),
	(3,'Pedro Jose Sales',88888888888,'niltonssjr@gmail.com','gerentejr'),
	(3,'Augusto Moreira da Silva',99999999999,'niltonssjr@gmail.com','gerentejr');
	