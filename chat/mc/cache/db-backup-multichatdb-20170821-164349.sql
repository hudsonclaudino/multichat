CREATE DATABASE IF NOT EXISTS multichatdb;

USE multichatdb;

DROP TABLE IF EXISTS multichat_campanha_cab;

CREATE TABLE `multichat_campanha_cab` (
  `campcab_id` int(15) NOT NULL AUTO_INCREMENT,
  `campcab_nome` varchar(30) NOT NULL,
  `campcab_descricao` varchar(30) DEFAULT NULL,
  `campcab_mensagem` varchar(500) NOT NULL,
  `campcab_criacao_data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `campcab_criacao_usuario` varchar(20) DEFAULT NULL,
  `campcab_envio_data` date DEFAULT NULL,
  `campcab_envio_usuario` varchar(20) DEFAULT NULL,
  `campcab_status` int(1) DEFAULT '0',
  PRIMARY KEY (`campcab_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO multichat_campanha_cab VALUES("1","CAMPANHA NÃºMERO 1","","Teste de mensagem de redirecionamento","2017-07-21 14:26:56","gerentejr","2017-07-21","gerentejr","1");
INSERT INTO multichat_campanha_cab VALUES("2","CAMPANHA 2","","teste de envio com novas configuraÃ§Ãµes","2017-08-17 21:42:08","gerente","2017-08-17","gerente","1");



DROP TABLE IF EXISTS multichat_campanha_det;

CREATE TABLE `multichat_campanha_det` (
  `campdet_id` int(15) NOT NULL AUTO_INCREMENT,
  `campdet_campcab_id` int(15) DEFAULT NULL,
  `campdet_cliente_nome` varchar(30) NOT NULL,
  `campdet_cliente_cpf` varchar(14) DEFAULT NULL,
  `campdet_cliente_email` varchar(30) DEFAULT NULL,
  `campdet_cadastro_usuario` varchar(30) DEFAULT NULL,
  `campdet_token_conversa` varchar(32) DEFAULT NULL,
  `campdet_cadastro_data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `campdet_conversa_id` int(15) DEFAULT NULL,
  PRIMARY KEY (`campdet_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO multichat_campanha_det VALUES("1","1","Nilton","","niltonssjr@gmail.com","gerentejr","22afbe17e8c2e7756c97309c317c1175","2017-08-15 23:51:20","118");
INSERT INTO multichat_campanha_det VALUES("2","2","pitstop","","niltonssjr@gmail.com","gerente","5bd31a7aa497002399b5488855080b79","2017-08-17 21:42:08","");



DROP TABLE IF EXISTS multichat_conversa;

CREATE TABLE `multichat_conversa` (
  `conversa_id` int(15) NOT NULL AUTO_INCREMENT,
  `conversa_cliente_ip` varchar(15) DEFAULT NULL,
  `conversa_cliente_navegador` varchar(20) DEFAULT NULL,
  `conversa_fila_entrada` datetime DEFAULT NULL,
  `conversa_fila_atendimento` datetime DEFAULT NULL,
  `conversa_campcab_id` int(15) DEFAULT NULL,
  `conversa_campdet_id` int(15) DEFAULT NULL,
  `conversa_cliente_nome` varchar(30) DEFAULT NULL,
  `conversa_cliente_email` varchar(30) DEFAULT NULL,
  `conversa_cliente_cpf` varchar(14) DEFAULT NULL,
  `conversa_usuario` varchar(20) DEFAULT NULL,
  `conversa_mensagem_offline` varchar(500) DEFAULT NULL,
  `conversa_mensagem_offline_leitura_data` datetime DEFAULT NULL,
  `conversa_mensagem_offline_leitura_usuario` varchar(20) DEFAULT NULL,
  `conversa_status` int(1) DEFAULT NULL,
  `conversa_acordo` varchar(20) DEFAULT NULL,
  `conversa_fim_atendimento` datetime DEFAULT NULL,
  PRIMARY KEY (`conversa_id`)
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=utf8;

INSERT INTO multichat_conversa VALUES("96","","Outros","2017-08-15 18:37:17","2017-08-15 18:37:48","1","1","Nilton","niltonssjr@gmail.com","","atendente","","","","3","","");
INSERT INTO multichat_conversa VALUES("97","","Outros","2017-08-15 18:55:27","2017-08-15 18:58:12","1","1","Nilton","niltonssjr@gmail.com","","atendente","","","","3","","");
INSERT INTO multichat_conversa VALUES("98","","Outros","2017-08-15 18:59:54","2017-08-15 19:00:06","1","1","Nilton","niltonssjr@gmail.com","","atendente","","","","2","","2017-08-15 19:00:25");
INSERT INTO multichat_conversa VALUES("99","","Outros","2017-08-15 19:06:34","2017-08-15 19:06:39","1","1","Nilton","niltonssjr@gmail.com","","atendente","","","","2","","2017-08-15 19:06:56");
INSERT INTO multichat_conversa VALUES("100","","Outros","2017-08-15 19:10:31","2017-08-15 19:10:39","1","1","Nilton","niltonssjr@gmail.com","","atendente","","","","2","","2017-08-15 19:10:48");
INSERT INTO multichat_conversa VALUES("101","","Outros","2017-08-15 20:34:10","2017-08-15 20:34:19","1","1","Nilton","niltonssjr@gmail.com","","atendente","","","","2","","2017-08-15 20:42:12");
INSERT INTO multichat_conversa VALUES("102","","Outros","2017-08-15 21:06:03","2017-08-15 21:06:12","1","1","Nilton","niltonssjr@gmail.com","","atendente","","","","2","","2017-08-15 21:06:46");
INSERT INTO multichat_conversa VALUES("103","","Outros","2017-08-15 21:12:28","2017-08-15 21:12:32","1","1","Nilton","niltonssjr@gmail.com","","atendente","","","","2","","2017-08-15 21:12:38");
INSERT INTO multichat_conversa VALUES("104","","Outros","2017-08-15 21:14:37","2017-08-15 21:14:42","1","1","Nilton","niltonssjr@gmail.com","","atendente","","","","2","","2017-08-15 21:14:53");
INSERT INTO multichat_conversa VALUES("105","","Outros","2017-08-15 21:19:02","2017-08-15 21:19:14","1","1","Nilton","niltonssjr@gmail.com","","atendente","","","","2","","2017-08-15 21:19:22");
INSERT INTO multichat_conversa VALUES("106","","Outros","2017-08-15 21:41:18","2017-08-15 21:41:24","1","1","Nilton","niltonssjr@gmail.com","","atendente","","","","2","","2017-08-15 21:41:54");
INSERT INTO multichat_conversa VALUES("107","","Outros","2017-08-15 21:43:13","2017-08-15 21:43:19","1","1","Nilton","niltonssjr@gmail.com","","atendente","","","","2","","2017-08-15 21:43:42");
INSERT INTO multichat_conversa VALUES("108","","Outros","2017-08-15 21:45:40","2017-08-15 21:45:46","1","1","Nilton","niltonssjr@gmail.com","","atendente","","","","2","","2017-08-15 21:46:20");
INSERT INTO multichat_conversa VALUES("109","","Outros","2017-08-15 21:49:30","2017-08-15 21:49:36","1","1","Nilton","niltonssjr@gmail.com","","atendente","","","","2","","2017-08-15 21:50:26");
INSERT INTO multichat_conversa VALUES("110","","Outros","2017-08-15 21:57:06","2017-08-15 21:57:10","1","1","Nilton","niltonssjr@gmail.com","","atendente","","","","2","","2017-08-15 21:57:28");
INSERT INTO multichat_conversa VALUES("111","","Outros","2017-08-15 22:01:38","2017-08-15 22:01:44","1","1","Nilton","niltonssjr@gmail.com","","atendente","","","","2","","2017-08-15 22:02:09");
INSERT INTO multichat_conversa VALUES("112","","Outros","2017-08-15 22:03:02","2017-08-15 22:03:08","1","1","Nilton","niltonssjr@gmail.com","","atendente","","","","2","","2017-08-15 22:03:14");
INSERT INTO multichat_conversa VALUES("113","","Outros","2017-08-15 22:07:34","2017-08-15 22:07:39","1","1","Nilton","niltonssjr@gmail.com","","atendente","","","","2","","2017-08-15 22:08:00");
INSERT INTO multichat_conversa VALUES("114","","Outros","2017-08-15 22:19:21","2017-08-15 22:19:28","1","1","Nilton","niltonssjr@gmail.com","","atendente","","","","2","","2017-08-15 22:19:36");
INSERT INTO multichat_conversa VALUES("115","","Outros","2017-08-15 22:34:41","2017-08-15 22:34:55","1","1","Nilton","niltonssjr@gmail.com","","atendente","","","","2","","2017-08-15 22:35:51");
INSERT INTO multichat_conversa VALUES("116","","Outros","2017-08-15 23:29:55","2017-08-15 23:30:01","1","1","Nilton","niltonssjr@gmail.com","","atendente","","","","2","","2017-08-15 23:49:38");
INSERT INTO multichat_conversa VALUES("117","","Outros","2017-08-15 23:49:39","2017-08-15 23:49:59","1","1","Nilton","niltonssjr@gmail.com","","atendente","","","","2","","2017-08-15 23:51:09");
INSERT INTO multichat_conversa VALUES("118","","Outros","2017-08-15 23:51:11","2017-08-15 23:51:20","1","1","Nilton","niltonssjr@gmail.com","","atendente","","","","2","","2017-08-16 10:52:12");
INSERT INTO multichat_conversa VALUES("119","","Outros","2017-08-17 19:05:18","2017-08-17 19:05:39","0","0","Cliente teste","teste@gmail.com","","","Estou desistindo de esperar e quero deixar uma mensagem!","","","4","","");



DROP TABLE IF EXISTS multichat_mensagem;

CREATE TABLE `multichat_mensagem` (
  `mensagem_id` int(15) NOT NULL AUTO_INCREMENT,
  `mensagem_conversa_id` int(15) DEFAULT NULL,
  `mensagem_remetente` int(1) DEFAULT NULL,
  `mensagem_cadastro_data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `mensagem_texto` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`mensagem_id`)
) ENGINE=InnoDB AUTO_INCREMENT=136 DEFAULT CHARSET=utf8;

INSERT INTO multichat_mensagem VALUES("79","96","1","2017-08-15 18:37:31","teste de mensagem");
INSERT INTO multichat_mensagem VALUES("80","96","1","2017-08-15 18:37:36","mensagem nÃºmero 2");
INSERT INTO multichat_mensagem VALUES("81","96","2","2017-08-15 18:37:41","podemos encerrar");
INSERT INTO multichat_mensagem VALUES("82","97","1","2017-08-15 18:55:54","teste de mensagem");
INSERT INTO multichat_mensagem VALUES("83","97","2","2017-08-15 18:56:01","mensagem 2");
INSERT INTO multichat_mensagem VALUES("84","98","1","2017-08-15 19:00:15","tete");
INSERT INTO multichat_mensagem VALUES("85","98","2","2017-08-15 19:00:18","sss");
INSERT INTO multichat_mensagem VALUES("86","99","1","2017-08-15 19:06:43","perereca");
INSERT INTO multichat_mensagem VALUES("87","99","1","2017-08-15 19:06:44","teste");
INSERT INTO multichat_mensagem VALUES("88","99","2","2017-08-15 19:06:49","mensagem");
INSERT INTO multichat_mensagem VALUES("89","100","1","2017-08-15 19:10:42","teste");
INSERT INTO multichat_mensagem VALUES("90","100","1","2017-08-15 19:10:44","ssss");
INSERT INTO multichat_mensagem VALUES("91","101","1","2017-08-15 20:34:43","teste");
INSERT INTO multichat_mensagem VALUES("92","101","1","2017-08-15 20:34:49","teste2");
INSERT INTO multichat_mensagem VALUES("93","101","1","2017-08-15 20:34:50","teste 3");
INSERT INTO multichat_mensagem VALUES("94","101","1","2017-08-15 20:41:30","teste");
INSERT INTO multichat_mensagem VALUES("95","102","1","2017-08-15 21:06:39","barusca");
INSERT INTO multichat_mensagem VALUES("96","103","1","2017-08-15 21:12:34","teste");
INSERT INTO multichat_mensagem VALUES("97","104","1","2017-08-15 21:14:45","teste");
INSERT INTO multichat_mensagem VALUES("98","104","1","2017-08-15 21:14:46","teste");
INSERT INTO multichat_mensagem VALUES("99","104","1","2017-08-15 21:14:47","teste");
INSERT INTO multichat_mensagem VALUES("100","105","1","2017-08-15 21:19:17","catumba");
INSERT INTO multichat_mensagem VALUES("101","105","1","2017-08-15 21:19:21","resteval");
INSERT INTO multichat_mensagem VALUES("102","106","1","2017-08-15 21:41:31","primeira mensagem operador");
INSERT INTO multichat_mensagem VALUES("103","106","1","2017-08-15 21:41:36","segunda mensagem operador");
INSERT INTO multichat_mensagem VALUES("104","106","1","2017-08-15 21:41:39","terceira mensagem operador");
INSERT INTO multichat_mensagem VALUES("105","106","2","2017-08-15 21:41:45","primeira mensagem cliente");
INSERT INTO multichat_mensagem VALUES("106","106","2","2017-08-15 21:41:48","segunda mensagem cliente");
INSERT INTO multichat_mensagem VALUES("107","106","2","2017-08-15 21:41:50","terceira mensagem cliente");
INSERT INTO multichat_mensagem VALUES("108","107","1","2017-08-15 21:43:24","pri msg oper");
INSERT INTO multichat_mensagem VALUES("109","107","1","2017-08-15 21:43:29","seg msg op");
INSERT INTO multichat_mensagem VALUES("110","107","2","2017-08-15 21:43:34","pri msg cliente");
INSERT INTO multichat_mensagem VALUES("111","107","2","2017-08-15 21:43:38","seg mensagem cliente");
INSERT INTO multichat_mensagem VALUES("112","108","1","2017-08-15 21:45:51","primeira mensagem op");
INSERT INTO multichat_mensagem VALUES("113","108","1","2017-08-15 21:45:54","segunda mensagem op");
INSERT INTO multichat_mensagem VALUES("114","108","2","2017-08-15 21:45:59","primeira mensagem cli");
INSERT INTO multichat_mensagem VALUES("115","108","2","2017-08-15 21:46:02","segunda mensagem cli");
INSERT INTO multichat_mensagem VALUES("116","108","1","2017-08-15 21:46:08","terceira mensagem op");
INSERT INTO multichat_mensagem VALUES("117","108","2","2017-08-15 21:46:12","terceira mensagem cli");
INSERT INTO multichat_mensagem VALUES("118","109","1","2017-08-15 21:49:45","operador dÃ¡ boas vindas");
INSERT INTO multichat_mensagem VALUES("119","109","2","2017-08-15 21:49:49","O cliente responde");
INSERT INTO multichat_mensagem VALUES("120","109","1","2017-08-15 21:49:56","operador cobra a bagaÃ§a");
INSERT INTO multichat_mensagem VALUES("121","109","2","2017-08-15 21:50:02","cliente nÃ£o tem grana");
INSERT INTO multichat_mensagem VALUES("122","109","1","2017-08-15 21:50:08","operador fica puto");
INSERT INTO multichat_mensagem VALUES("123","109","2","2017-08-15 21:50:14","cliente racha o bico");
INSERT INTO multichat_mensagem VALUES("124","109","1","2017-08-15 21:50:23","operador desliga na cara do cliente");
INSERT INTO multichat_mensagem VALUES("125","110","1","2017-08-15 21:57:16","teste de acentuaÃ§Ã£o");
INSERT INTO multichat_mensagem VALUES("126","110","1","2017-08-15 21:57:26","quero ver se os acentos sÃ£o tratados corretamente neÃ§a bagaÃ§a");
INSERT INTO multichat_mensagem VALUES("127","111","1","2017-08-15 22:01:52","teste de mensagem Ã§Ã§Ã§Ã§Ã§Ã§");
INSERT INTO multichat_mensagem VALUES("128","111","1","2017-08-15 22:02:05","teste pÃ£o aÃ§ucar e chÃ¡");
INSERT INTO multichat_mensagem VALUES("129","112","1","2017-08-15 22:03:12","testando o tÃ­tulo");
INSERT INTO multichat_mensagem VALUES("130","113","1","2017-08-15 22:07:44","Ã§Ã§Ã§Ã§Ã§Ã§Ã§Ã§Ã§Ã§Ã§Ã§Ã§Ã§Ã§Ã§Ã§Ã§Ã§Ã§Ã§Ã§Ã§Ã§Ã§Ã§Ã§Ã§Ã§Ã§");
INSERT INTO multichat_mensagem VALUES("131","113","1","2017-08-15 22:07:48","Ã£Ã£Ã£Ã£Ã£Ã£Ã£Ã£Ã£");
INSERT INTO multichat_mensagem VALUES("132","113","1","2017-08-15 22:07:56","Ã¡Ã¡Ã¡Ã¡Ã¡Ã¡Ã¡Ã¡Ã©Ã©Ã©Ã©Ã©Ã©Ã©Ã©Ã³Ã³Ã³Ã³Ã³Ã³Ã³Ã³Ã³Ã³");
INSERT INTO multichat_mensagem VALUES("133","114","1","2017-08-15 22:19:33","teste de finalizacao pelo cliente");
INSERT INTO multichat_mensagem VALUES("134","115","1","2017-08-15 22:34:59","teste de mensagem");
INSERT INTO multichat_mensagem VALUES("135","119","2","2017-08-17 19:10:05","Estou desistindo de esperar e quero deixar uma mensagem!");



DROP TABLE IF EXISTS multichat_usuario;

CREATE TABLE `multichat_usuario` (
  `usuario_id` int(6) NOT NULL AUTO_INCREMENT,
  `usuario_nome` varchar(30) NOT NULL,
  `usuario_email` varchar(30) NOT NULL,
  `usuario_usuario` varchar(20) NOT NULL,
  `usuario_pw` varchar(32) NOT NULL,
  `usuario_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `usuario_nivel` int(11) NOT NULL,
  `usuario_status` int(1) DEFAULT NULL,
  `usuario_ultima_alteracao` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`usuario_id`)
) ENGINE=InnoDB AUTO_INCREMENT=112 DEFAULT CHARSET=utf8;

INSERT INTO multichat_usuario VALUES("110","Nilton - Gerente","niltonssjr@gmail.com","gerente","e6a93f9d77be281c7a319ddd097cf2fc","2017-07-28 13:03:15","1","0","");
INSERT INTO multichat_usuario VALUES("111","Nilton - Atendente","camisadobrisa@gmail.com","atendente","008436dada17f898a85871fea8bf8510","2017-08-04 00:52:16","2","1","");



