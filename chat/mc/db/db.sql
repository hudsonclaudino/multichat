CREATE DATABASE multichatdb;

CREATE USER 'multichatuser'@'localhost' IDENTIFIED VIA mysql_native_password USING '***';GRANT ALL PRIVILEGES ON *.* TO 'multichatuser'@'localhost' REQUIRE NONE WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;

create table MULTICHAT_USUARIO (
    	usuario_id integer(6) AUTO_INCREMENT primary key,
    	usuario_nome varchar(30) not null,
    	usuario_email varchar(30) not null,
    	usuario_usuario varchar(20) not null,
    	usuario_pw varchar(32) not null,
    	usuario_nivel integer(1) not null,
    	usuario_timestamp TIMESTAMP,
		usuario_status integer(1),
		usuario_ultima_alteracao varchar(20));

create table MULTICHAT_CAMPANHA_CAB (
		campcab_id integer(15) AUTO_INCREMENT primary key,
		campcab_nome varchar(30) not null,
		campcab_descricao varchar(30),
		campcab_mensagem varchar(500) not null,
		campcab_criacao_data TIMESTAMP,
		campcab_criacao_usuario varchar(20),
		campcab_envio_data date,
		campcab_envio_usuario varchar(20),
		campcab_status integer(1) default 0);

create table MULTICHAT_CAMPANHA_DET (
		campdet_id integer(15) AUTO_INCREMENT primary key,
		campdet_campcab_id integer(15),
		campdet_cliente_nome varchar(30) not null,
		campdet_cliente_cpf varchar(14),
		campdet_cliente_email varchar(30),
		campdet_cadastro_usuario varchar(30),
		campdet_token_conversa varchar(32),
		campdet_conversa_id integer(15),
		campdet_cadastro_data TIMESTAMP);
		
create table MULTICHAT_CONVERSA (
		conversa_id integer(15) AUTO_INCREMENT primary key,
		conversa_cliente_ip varchar(15),
		conversa_cliente_navegador varchar(20),
		conversa_fila_entrada datetime,
		conversa_fila_atendimento datetime,
		conversa_fim_atendimento datetime,
		conversa_campcab_id integer(15),
		conversa_campdet_id integer(15),
		conversa_cliente_nome varchar(30),
		conversa_cliente_email varchar(30),
		conversa_cliente_cpf varchar(14),
		conversa_usuario varchar(20),
		conversa_mensagem_offline varchar(500),
		conversa_mensagem_offline_leitura_data datetime,
		conversa_mensagem_offline_leitura_usuario varchar(20),
		conversa_acordo varchar(20),
		conversa_status integer(1));

create table MULTICHAT_MENSAGEM (
		mensagem_id integer(15) AUTO_INCREMENT primary key,
		mensagem_conversa_id integer(15),
		mensagem_remetente integer(1),
		mensagem_cadastro_data TIMESTAMP,
		mensagem_texto varchar(500));
		




