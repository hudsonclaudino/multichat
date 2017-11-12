CREATE DATABASE IF NOT EXISTS governancapm;

USE governancapm;

DROP TABLE IF EXISTS controle_perfil_acesso;

CREATE TABLE `controle_perfil_acesso` (
  `id_sistema_acesso` int(11) NOT NULL AUTO_INCREMENT,
  `id_colaborador` int(11) NOT NULL,
  `id_perfil` int(11) NOT NULL,
  PRIMARY KEY (`id_sistema_acesso`)
) ENGINE=MyISAM AUTO_INCREMENT=679 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS perfil_acesso;

CREATE TABLE `perfil_acesso` (
  `id_perfil_sistema` int(11) NOT NULL AUTO_INCREMENT,
  `id_pai` int(11) NOT NULL,
  `label_menu` varchar(120) NOT NULL,
  `label_form` varchar(120) NOT NULL,
  `name_file` varchar(120) DEFAULT NULL,
  `id_posto` int(11) NOT NULL,
  `id_sistema` int(11) NOT NULL,
  `ordem` int(11) DEFAULT NULL,
  `label_cadastro` varchar(60) NOT NULL,
  `id_grupo_funcionalidade` int(10) unsigned NOT NULL,
  `ativo` char(255) NOT NULL DEFAULT 'S',
  PRIMARY KEY (`id_perfil_sistema`)
) ENGINE=MyISAM AUTO_INCREMENT=281 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_acionamento;

CREATE TABLE `sgr_acionamento` (
  `id_acionamento` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_usuario` int(10) unsigned NOT NULL,
  `data_acionamento` datetime NOT NULL,
  `data_encerramento_acionamento` datetime DEFAULT NULL,
  `id_posto` int(10) unsigned NOT NULL,
  `id_encerramento_acionamento` int(10) unsigned NOT NULL,
  `id_chamado` int(10) unsigned NOT NULL,
  `id_tipo_tarefa` int(10) unsigned NOT NULL,
  `descricao_acionamento` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id_acionamento`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_altera_tipo_chamado;

CREATE TABLE `sgr_altera_tipo_chamado` (
  `id_altera_tipo_chamado` int(11) NOT NULL AUTO_INCREMENT,
  `id_encerramento` int(11) NOT NULL,
  `id_tipo_chamado` int(11) NOT NULL,
  PRIMARY KEY (`id_altera_tipo_chamado`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_area;

CREATE TABLE `sgr_area` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome_area` varchar(40) NOT NULL,
  `descricao_area` varchar(120) NOT NULL,
  `email` varchar(120) DEFAULT NULL,
  `id_empresa` int(11) NOT NULL,
  `ativo` char(1) NOT NULL DEFAULT 'S',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_areaxpriorizador;

CREATE TABLE `sgr_areaxpriorizador` (
  `id_area_priorizador` int(11) NOT NULL AUTO_INCREMENT,
  `id_area` int(11) DEFAULT NULL,
  `id_priorizador` int(11) DEFAULT NULL,
  `ativo` char(1) DEFAULT 'S',
  PRIMARY KEY (`id_area_priorizador`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_base_fo;

CREATE TABLE `sgr_base_fo` (
  `id_base_fo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_fo` int(10) unsigned NOT NULL DEFAULT '0',
  `site` varchar(20) NOT NULL DEFAULT '',
  `endereco_id` varchar(20) NOT NULL DEFAULT '',
  `plano_ano` varchar(20) NOT NULL DEFAULT '',
  `ot` varchar(40) NOT NULL DEFAULT '',
  `plano_trabalho` varchar(120) NOT NULL DEFAULT '',
  `tecnologia` varchar(20) NOT NULL DEFAULT '',
  `municipio` varchar(120) NOT NULL DEFAULT '',
  `uf` varchar(4) NOT NULL DEFAULT '',
  `regional` varchar(4) NOT NULL DEFAULT '',
  `solucao_abc` varchar(10) NOT NULL DEFAULT '',
  `frequencia` varchar(20) NOT NULL DEFAULT '',
  `solucao_meio_tx_trecho` varchar(40) NOT NULL DEFAULT '',
  `classificacao_tipo_processo` varchar(120) NOT NULL DEFAULT '',
  `site_ponta_a` varchar(10) NOT NULL DEFAULT '',
  `site_ponta_b` varchar(10) NOT NULL DEFAULT '',
  `id_projeto` varchar(45) NOT NULL DEFAULT '',
  `gp_equipamento` varchar(45) NOT NULL DEFAULT '',
  `data_recebimento` varchar(20) NOT NULL DEFAULT '',
  `tipo_servico` varchar(45) NOT NULL DEFAULT '',
  `engenheiro` varchar(120) NOT NULL DEFAULT '',
  `vendor` varchar(40) NOT NULL DEFAULT '',
  `replan_acesso` varchar(20) NOT NULL DEFAULT '',
  `baseline_tx` varchar(20) NOT NULL DEFAULT '',
  `replan_tx` varchar(20) NOT NULL DEFAULT '',
  `status` varchar(40) NOT NULL DEFAULT '',
  `acao` varchar(45) NOT NULL DEFAULT '',
  `data_tx_plan` varchar(20) NOT NULL DEFAULT '',
  `data_tx_real` varchar(20) NOT NULL DEFAULT '',
  `po_hw_status` varchar(40) NOT NULL DEFAULT '',
  `num_po_hw` varchar(40) NOT NULL DEFAULT '',
  `po_se_status` varchar(40) NOT NULL DEFAULT '',
  `num_po_se` varchar(40) NOT NULL DEFAULT '',
  `po_infra_status` varchar(40) NOT NULL DEFAULT '',
  `num_po_infra` varchar(40) NOT NULL DEFAULT '',
  `infra_rfi_status` varchar(40) NOT NULL DEFAULT '',
  `data_infra_rfi_liberacao` varchar(20) NOT NULL DEFAULT '',
  `acionamento_sgi` varchar(40) NOT NULL DEFAULT '',
  `status_rede_externa` varchar(40) NOT NULL DEFAULT '',
  `po_fo_status` varchar(40) NOT NULL DEFAULT '',
  `num_po_fo` varchar(40) NOT NULL DEFAULT '',
  `prev_rede_externa_concluida` varchar(20) DEFAULT NULL,
  `prev_rede_externa_aceita` varchar(20) NOT NULL DEFAULT '',
  `wo_aceitacao_rede_externa` varchar(60) NOT NULL DEFAULT '',
  `gp_rede_externa` varchar(120) NOT NULL DEFAULT '',
  `status_equipamento` varchar(40) NOT NULL DEFAULT '',
  `data_status_equipamento` varchar(20) NOT NULL DEFAULT '',
  `status_wo_cadastro` varchar(40) NOT NULL DEFAULT '',
  `data_wo_cadastro` varchar(20) NOT NULL DEFAULT '',
  `status_aceitacao_fisica` varchar(40) NOT NULL DEFAULT '',
  `data_aceitacao_fisica` varchar(20) NOT NULL DEFAULT '',
  `status_wo_logica` varchar(40) NOT NULL DEFAULT '',
  `status_configuracao_servico` varchar(40) NOT NULL DEFAULT '',
  `data_configuracao_servico` varchar(20) NOT NULL DEFAULT '',
  `status_migracao_servico` varchar(40) NOT NULL DEFAULT '',
  `data_migracao_servico` varchar(20) NOT NULL DEFAULT '',
  `obeservacao` tinytext NOT NULL,
  `modified` varchar(20) NOT NULL DEFAULT '',
  `created` varchar(20) NOT NULL DEFAULT '',
  `created_by` varchar(120) NOT NULL DEFAULT '',
  `modified_by` varchar(120) NOT NULL DEFAULT '',
  `id_usuario` int(10) unsigned NOT NULL DEFAULT '0',
  `data_atualizacao` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `controle` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_base_fo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_base_unica;

CREATE TABLE `sgr_base_unica` (
  `id_base_unica` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_atendido` varchar(10) NOT NULL,
  `cadeia` varchar(120) NOT NULL,
  `ativo` char(1) NOT NULL DEFAULT 'S',
  `data_atualizacao` datetime NOT NULL,
  PRIMARY KEY (`id_base_unica`),
  KEY `Index_2` (`site_atendido`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_base_unica_new;

CREATE TABLE `sgr_base_unica_new` (
  `id_base_unica` int(11) NOT NULL AUTO_INCREMENT,
  `identificador` varchar(45) NOT NULL,
  `cadeia` text NOT NULL,
  `ativo` char(1) NOT NULL DEFAULT 'S',
  `endereco_id` varchar(40) NOT NULL DEFAULT '',
  `plano_ano` varchar(10) NOT NULL DEFAULT '',
  `filiacao` varchar(40) NOT NULL DEFAULT '',
  `solucao_e2e` varchar(40) NOT NULL DEFAULT '',
  `ot` varchar(40) NOT NULL DEFAULT '',
  `plano_trabalho` varchar(40) NOT NULL DEFAULT '',
  `plano` varchar(40) NOT NULL DEFAULT '',
  `tecnologia` varchar(10) NOT NULL DEFAULT '',
  `frequencia` varchar(10) NOT NULL DEFAULT '',
  `municipio` varchar(120) NOT NULL DEFAULT '',
  `uf` varchar(4) NOT NULL DEFAULT '',
  `regional` varchar(6) NOT NULL DEFAULT '',
  `status_geral` varchar(20) NOT NULL DEFAULT '',
  `data_mw` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_base_unica`),
  KEY `Index_2` (`ativo`)
) ENGINE=InnoDB AUTO_INCREMENT=9625 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_chamado;

CREATE TABLE `sgr_chamado` (
  `id_chamado` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_usuario` int(10) unsigned NOT NULL,
  `solicitante` varchar(60) NOT NULL,
  `observacao` text NOT NULL,
  `id_status` int(10) unsigned NOT NULL DEFAULT '1',
  `controle` varchar(10) NOT NULL,
  `notificacao` char(1) NOT NULL,
  `data_abertura_chamado` varchar(45) NOT NULL,
  `id_usuario_tratando` int(11) DEFAULT NULL,
  `email` varchar(60) NOT NULL,
  `contato` varchar(60) NOT NULL,
  `id_sistema` int(10) unsigned NOT NULL,
  `id_tipo_chamado` int(10) unsigned NOT NULL,
  `data_encerramento` datetime NOT NULL,
  `id_motivo_abertura` int(11) DEFAULT '0',
  `id_usuario_priorizador` int(11) NOT NULL DEFAULT '0',
  `id_empresa` int(11) NOT NULL,
  `id_area` int(11) DEFAULT NULL,
  `id_equipe` int(11) DEFAULT NULL,
  `referencia_netcorp` varchar(120) DEFAULT NULL,
  `sc_material` varchar(120) DEFAULT NULL,
  `sc_servico` varchar(120) DEFAULT NULL,
  `local_entrega_hw` varchar(200) DEFAULT NULL,
  `nota_fiscal_hw` varchar(120) DEFAULT NULL,
  `nota_fiscal_material` varchar(120) DEFAULT NULL,
  `aceitacao_logica_wo` varchar(120) DEFAULT '',
  `aceitacao_fisica_wo` varchar(120) DEFAULT NULL,
  `aceitacao_ngnis_wo` varchar(120) DEFAULT NULL,
  `migracao_wo` varchar(120) DEFAULT NULL,
  `migracao_2g` varchar(120) DEFAULT NULL,
  `migracao_3g` varchar(120) DEFAULT NULL,
  `migracao_4g` varchar(120) DEFAULT NULL,
  `migracao_smallcell` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id_chamado`)
) ENGINE=MyISAM AUTO_INCREMENT=498 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_chamadoxplanejado;

CREATE TABLE `sgr_chamadoxplanejado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_chamado` int(11) NOT NULL,
  `data_planejado` date NOT NULL,
  `id_posto_trabalho` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `data_alteracao` datetime NOT NULL,
  `ativo` char(1) NOT NULL DEFAULT 'S',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_chamadoxposto;

CREATE TABLE `sgr_chamadoxposto` (
  `id_chamado_posto` mediumint(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_chamado` int(10) unsigned NOT NULL,
  `id_posto` int(10) unsigned NOT NULL,
  `data_chegada` datetime NOT NULL,
  `data_saida` datetime NOT NULL,
  `id_usuario` int(10) unsigned NOT NULL,
  `id_usuario_tratando` int(11) NOT NULL,
  `data_real` date DEFAULT NULL,
  PRIMARY KEY (`id_chamado_posto`),
  KEY `id_chamado` (`id_chamado`)
) ENGINE=MyISAM AUTO_INCREMENT=540 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_chamadoxposto_bkp;

CREATE TABLE `sgr_chamadoxposto_bkp` (
  `id_chamado_posto` mediumint(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_chamado` int(10) unsigned NOT NULL,
  `id_posto` int(10) unsigned NOT NULL,
  `data_chegada` datetime NOT NULL,
  `data_saida` datetime NOT NULL,
  `id_usuario` int(10) unsigned NOT NULL,
  `id_usuario_tratando` int(11) NOT NULL,
  PRIMARY KEY (`id_chamado_posto`),
  KEY `id_chamado` (`id_chamado`)
) ENGINE=MyISAM AUTO_INCREMENT=763 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_consulta_usuario;

CREATE TABLE `sgr_consulta_usuario` (
  `id_consulta_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `id_chamado` varchar(255) DEFAULT NULL,
  `id_projeto` int(11) DEFAULT NULL,
  `id_tipo_chamado` int(11) DEFAULT NULL,
  `id_posto_trabalho` int(11) DEFAULT NULL,
  `solicitante` varchar(255) DEFAULT NULL,
  `id_status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_consulta_usuario`)
) ENGINE=MyISAM AUTO_INCREMENT=107 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_controle_projeto;

CREATE TABLE `sgr_controle_projeto` (
  `id_controle_projeto` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_projeto` varchar(20) NOT NULL DEFAULT '',
  `nome_projeto` varchar(180) NOT NULL DEFAULT '',
  `data_inicio_elaboracao` varchar(10) NOT NULL DEFAULT '',
  `versao` varchar(4) NOT NULL DEFAULT '',
  `tipo_entrada` varchar(40) NOT NULL DEFAULT '',
  `validador_1` varchar(4) NOT NULL DEFAULT '',
  `validador_2` varchar(180) NOT NULL DEFAULT '',
  `classificacao` varchar(40) NOT NULL DEFAULT '',
  `engenheiro_responsavel` varchar(120) NOT NULL DEFAULT '',
  `sla_elaboracao_lld` varchar(4) NOT NULL DEFAULT '',
  `data_previsao_elaboracao_lld` varchar(10) NOT NULL DEFAULT '',
  `data_real_elaboracao_lld` varchar(10) NOT NULL DEFAULT '',
  `data_carga_boq` varchar(10) NOT NULL DEFAULT '',
  `projeto_estrategico` varchar(40) NOT NULL DEFAULT '',
  `save_opex` varchar(40) NOT NULL DEFAULT '',
  `projeto_evora` varchar(180) NOT NULL DEFAULT '',
  `subprojeto_evora` varchar(180) NOT NULL DEFAULT '',
  `element_detail_evora` varchar(180) NOT NULL DEFAULT '',
  `valor_boq_carregado` varchar(40) NOT NULL DEFAULT '',
  `data_envio_eng_tx` varchar(10) NOT NULL DEFAULT '',
  `quantidade_jm` varchar(8) NOT NULL DEFAULT '',
  `gpm` varchar(120) NOT NULL DEFAULT '',
  `status` varchar(40) NOT NULL DEFAULT '',
  `status_dependencia_area` varchar(60) NOT NULL DEFAULT '',
  `data_conclusao` varchar(10) NOT NULL DEFAULT '',
  `observacao` tinytext NOT NULL,
  `id_usuario` int(10) unsigned NOT NULL DEFAULT '0',
  `data_carga` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ativo` char(1) NOT NULL DEFAULT 'S',
  `controle` varchar(20) NOT NULL DEFAULT '',
  `classificacao_projeto` varchar(120) NOT NULL DEFAULT '',
  `carga_evora` varchar(10) NOT NULL DEFAULT '',
  `status_one` varchar(40) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_controle_projeto`)
) ENGINE=InnoDB AUTO_INCREMENT=381 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_diagnostico;

CREATE TABLE `sgr_diagnostico` (
  `id_diagnostico` smallint(10) NOT NULL AUTO_INCREMENT,
  `id_chamado` int(10) NOT NULL,
  `diagnostico_observacao` text,
  `id_encerramento` int(10) NOT NULL,
  `id_chamado_posto` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id_diagnostico`)
) ENGINE=MyISAM AUTO_INCREMENT=284 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_email_posto;

CREATE TABLE `sgr_email_posto` (
  `id_email_posto` int(11) NOT NULL AUTO_INCREMENT,
  `id_projeto` int(11) NOT NULL,
  `id_posto` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_area` int(11) DEFAULT NULL,
  `id_empresa` int(11) DEFAULT NULL,
  `usuario_abertura` char(1) NOT NULL DEFAULT 'N',
  `ativo` char(1) NOT NULL DEFAULT 'S',
  PRIMARY KEY (`id_email_posto`)
) ENGINE=MyISAM AUTO_INCREMENT=93 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_empresa;

CREATE TABLE `sgr_empresa` (
  `id_empresa` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `label_empresa` varchar(120) NOT NULL,
  `email` varchar(120) DEFAULT NULL,
  `arquivo_banner` varchar(120) NOT NULL,
  `tipo` char(1) NOT NULL,
  `ativo` char(1) NOT NULL DEFAULT 'S',
  PRIMARY KEY (`id_empresa`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_empresaxarea;

CREATE TABLE `sgr_empresaxarea` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) DEFAULT NULL,
  `id_area` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;




DROP TABLE IF EXISTS sgr_encerramento;

CREATE TABLE `sgr_encerramento` (
  `id_encerramento` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `label_encerramento` varchar(120) NOT NULL,
  `ativo` char(1) NOT NULL DEFAULT 'S',
  PRIMARY KEY (`id_encerramento`)
) ENGINE=MyISAM AUTO_INCREMENT=91 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_encerramento_acionamento;

CREATE TABLE `sgr_encerramento_acionamento` (
  `id_encerramento_acionamento` int(11) NOT NULL AUTO_INCREMENT,
  `label_encerramento_acionamento` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id_encerramento_acionamento`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_encerramento_acionamentoxposto;

CREATE TABLE `sgr_encerramento_acionamentoxposto` (
  `id_encerramento_acionamentoxposto` int(11) NOT NULL AUTO_INCREMENT,
  `id_encerramento_acionamento` int(11) NOT NULL,
  `id_posto` int(11) NOT NULL,
  `ativo` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_encerramento_acionamentoxposto`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_encerramentoxencaminhamentoxposto;

CREATE TABLE `sgr_encerramentoxencaminhamentoxposto` (
  `id_encerramentoxencaminhamento` int(11) NOT NULL AUTO_INCREMENT,
  `id_encerramento` int(11) NOT NULL,
  `id_posto_origem` int(11) NOT NULL,
  `id_posto_destino` int(11) NOT NULL,
  `id_tipo_chamado` int(11) NOT NULL,
  PRIMARY KEY (`id_encerramentoxencaminhamento`),
  UNIQUE KEY `id_encerramentoxencaminhamento_UNIQUE` (`id_encerramentoxencaminhamento`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_encerramentoxposto;

CREATE TABLE `sgr_encerramentoxposto` (
  `id_encerramentoxposto` int(11) NOT NULL AUTO_INCREMENT,
  `id_encerramento` int(11) NOT NULL,
  `id_posto` int(11) NOT NULL,
  `ativo` char(11) NOT NULL DEFAULT '''S''',
  `id_sistema` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_encerramentoxposto`),
  UNIQUE KEY `idsupobra_encerramentoxposto_UNIQUE` (`id_encerramentoxposto`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_encerramentoxstatus;

CREATE TABLE `sgr_encerramentoxstatus` (
  `id_encerramentoxstatus` int(11) NOT NULL AUTO_INCREMENT,
  `id_encerramento` int(11) NOT NULL,
  `id_posto_origem` int(11) NOT NULL,
  `id_sistema` int(11) DEFAULT NULL,
  `id_status` int(11) NOT NULL,
  PRIMARY KEY (`id_encerramentoxstatus`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_files;

CREATE TABLE `sgr_files` (
  `id_files` int(11) NOT NULL AUTO_INCREMENT,
  `tmp_nome_file` varchar(120) NOT NULL,
  `id_chamado` int(11) NOT NULL,
  `id_posto` int(11) NOT NULL,
  `data_file` datetime NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nome_file` varchar(120) NOT NULL,
  PRIMARY KEY (`id_files`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_grupo_funcionalidade;

CREATE TABLE `sgr_grupo_funcionalidade` (
  `id_grupo_funcionalidade` int(11) NOT NULL AUTO_INCREMENT,
  `label_grupo_funcionalidade` varchar(120) DEFAULT NULL,
  `ativo` char(1) DEFAULT 'S',
  PRIMARY KEY (`id_grupo_funcionalidade`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_grupo_posto;

CREATE TABLE `sgr_grupo_posto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome_grupo` varchar(120) NOT NULL,
  `id_sistema` int(11) NOT NULL,
  `ordem` int(11) NOT NULL,
  `ativo` char(1) NOT NULL DEFAULT 'S',
  `cor_grupo` varchar(10) DEFAULT '#3399CC',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_home_usuario;

CREATE TABLE `sgr_home_usuario` (
  `id_home_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_projeto` int(11) NOT NULL,
  `id_empresa` int(11) NOT NULL,
  `id_area` int(11) NOT NULL,
  `id_loja` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_home_usuario`)
) ENGINE=MyISAM AUTO_INCREMENT=393 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_hub_fo;

CREATE TABLE `sgr_hub_fo` (
  `id_hub_fo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `plano` varchar(80) NOT NULL DEFAULT '',
  `eficiencia` char(1) NOT NULL DEFAULT '',
  `iub` char(1) NOT NULL DEFAULT '',
  `700_mhz` char(1) NOT NULL DEFAULT '',
  `novos_municipios` char(1) NOT NULL DEFAULT '',
  `uf` varchar(4) NOT NULL DEFAULT '',
  `regional` varchar(6) NOT NULL DEFAULT '',
  `ot` varchar(45) NOT NULL DEFAULT '',
  `site_a_coliseu` varchar(45) NOT NULL DEFAULT '',
  `endereco_id_a_coliseu` varchar(45) NOT NULL DEFAULT '',
  `cidade_a` varchar(80) NOT NULL DEFAULT '',
  `site_b_coliseu` varchar(45) NOT NULL DEFAULT '',
  `endereco_id_b_coliseu` varchar(45) NOT NULL DEFAULT '',
  `cidade_b` varchar(45) NOT NULL DEFAULT '',
  `projeto` varchar(45) NOT NULL DEFAULT '',
  `pm` varchar(45) NOT NULL DEFAULT '',
  `status_projeto` varchar(45) NOT NULL DEFAULT '',
  `status_atual_projeto` varchar(45) NOT NULL DEFAULT '',
  `projeto_predecessor` varchar(45) NOT NULL DEFAULT '',
  `site_a_b` varchar(45) NOT NULL DEFAULT '',
  `detalhamento_ld` varchar(45) NOT NULL DEFAULT '',
  `area_responsavel` varchar(45) NOT NULL DEFAULT '',
  `base_line` varchar(45) NOT NULL DEFAULT '',
  `previsao_conclusao` varchar(45) NOT NULL DEFAULT '',
  `mes_previsto` varchar(45) NOT NULL DEFAULT '',
  `data_entrega` varchar(45) NOT NULL DEFAULT '',
  `mes_entrega` varchar(45) NOT NULL DEFAULT '',
  `saving_c` varchar(45) NOT NULL DEFAULT '',
  `saving_s` varchar(45) NOT NULL DEFAULT '',
  `id_usuario` int(10) unsigned NOT NULL DEFAULT '0',
  `controle` varchar(45) NOT NULL DEFAULT '',
  `ativo` char(1) NOT NULL DEFAULT 'S',
  `data_carga` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id_hub_fo`)
) ENGINE=InnoDB AUTO_INCREMENT=462 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_links;

CREATE TABLE `sgr_links` (
  `id_links` int(11) NOT NULL AUTO_INCREMENT,
  `site_2` varchar(10) NOT NULL,
  `vendor` varchar(20) NOT NULL,
  `ponta_a` varchar(20) NOT NULL,
  `ponta_b` varchar(20) NOT NULL,
  `identificador` varchar(40) NOT NULL,
  `status` varchar(20) NOT NULL,
  `observacao` tinytext,
  `vazia` varchar(60) DEFAULT NULL,
  `tipo_rollout` varchar(40) NOT NULL,
  `hub` varchar(20) DEFAULT NULL,
  `chave` varchar(30) DEFAULT NULL,
  `data` varchar(14) NOT NULL,
  `chave_plano` varchar(30) DEFAULT NULL,
  `sol_tx_trecho` varchar(40) NOT NULL DEFAULT '',
  `vendor_mw` varchar(40) DEFAULT NULL,
  `plano` varchar(2) NOT NULL,
  `frequencia` varchar(10) NOT NULL,
  `sol_tx_e2e` varchar(40) NOT NULL,
  `plano_versao` varchar(20) NOT NULL,
  `id_usuario` varchar(45) NOT NULL,
  `controle` varchar(45) DEFAULT NULL,
  `data_carga` datetime NOT NULL,
  `data_rfi` varchar(12) DEFAULT NULL,
  `ativo` char(1) NOT NULL DEFAULT 'S',
  PRIMARY KEY (`id_links`),
  KEY `Index_2` (`ativo`),
  KEY `index_3` (`identificador`,`status`)
) ENGINE=MyISAM AUTO_INCREMENT=54143 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_log_email;

CREATE TABLE `sgr_log_email` (
  `id_log_email` int(11) NOT NULL AUTO_INCREMENT,
  `id_chamado` smallint(6) DEFAULT NULL,
  `data_envio` datetime DEFAULT NULL,
  `address` varchar(120) DEFAULT NULL,
  `acao` varchar(120) DEFAULT NULL,
  `posto_trabalho` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_log_email`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_log_sistema;

CREATE TABLE `sgr_log_sistema` (
  `id_log_sistema` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `entidade` varchar(60) NOT NULL,
  `id_usuario` int(10) unsigned NOT NULL,
  `data_acao` datetime NOT NULL,
  `valor_antigo` varchar(120) DEFAULT NULL,
  `valor_novo` varchar(120) NOT NULL,
  `acao` varchar(60) NOT NULL,
  `id_entidade` int(10) unsigned DEFAULT NULL,
  `id_posto` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_log_sistema`)
) ENGINE=MyISAM AUTO_INCREMENT=3447 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_loja;

CREATE TABLE `sgr_loja` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome_loja` varchar(120) COLLATE latin1_general_ci NOT NULL,
  `id_empresa` int(11) NOT NULL,
  `ativo` char(1) COLLATE latin1_general_ci NOT NULL DEFAULT 'S',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=166 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;




DROP TABLE IF EXISTS sgr_maximo_atendimentoxposto;

CREATE TABLE `sgr_maximo_atendimentoxposto` (
  `id_maximo_atendimentoxposto` int(11) NOT NULL AUTO_INCREMENT,
  `id_posto_trabalho` int(11) DEFAULT NULL,
  `maximo_atendimento` int(11) DEFAULT NULL,
  `ativo` char(1) DEFAULT 'S',
  PRIMARY KEY (`id_maximo_atendimentoxposto`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_motivo_abertura;

CREATE TABLE `sgr_motivo_abertura` (
  `id_motivo_abertura` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `label_motivo_abertura` varchar(120) NOT NULL,
  `gera_alerta` char(255) DEFAULT 'N',
  `id_projeto` int(11) DEFAULT NULL,
  `id_tipo_chamado` int(11) DEFAULT NULL,
  `ativo` char(255) DEFAULT 'S',
  PRIMARY KEY (`id_motivo_abertura`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_perfil;

CREATE TABLE `sgr_perfil` (
  `id_perfil` int(11) NOT NULL AUTO_INCREMENT,
  `cod_perfil` varchar(14) NOT NULL,
  `nome_perfil` varchar(120) NOT NULL,
  `ativo` char(1) DEFAULT 'S',
  PRIMARY KEY (`id_perfil`)
) ENGINE=MyISAM AUTO_INCREMENT=114 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_perfilxfuncionalidade;

CREATE TABLE `sgr_perfilxfuncionalidade` (
  `id_perfilxfuncionalidade` int(11) NOT NULL AUTO_INCREMENT,
  `id_perfil` int(11) NOT NULL,
  `id_funcionalidade` int(11) NOT NULL,
  PRIMARY KEY (`id_perfilxfuncionalidade`)
) ENGINE=MyISAM AUTO_INCREMENT=16429 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_perfilxtipo_chamado;

CREATE TABLE `sgr_perfilxtipo_chamado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_perfil` int(11) DEFAULT NULL,
  `id_tipo_chamado` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=986 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;




DROP TABLE IF EXISTS sgr_plano_operativo;

CREATE TABLE `sgr_plano_operativo` (
  `id_plano_operativo` int(11) NOT NULL AUTO_INCREMENT,
  `tx_e2e_id` int(11) DEFAULT NULL,
  `tx_trecho_id` char(1) DEFAULT NULL,
  `solucao_abc` char(1) NOT NULL,
  `projeto` varchar(120) NOT NULL,
  `regional` varchar(3) NOT NULL,
  `uf` varchar(2) NOT NULL,
  `ibge` varchar(10) DEFAULT NULL,
  `municipio` varchar(120) NOT NULL,
  `endereco_id` varchar(20) NOT NULL,
  `site_atendido` varchar(10) NOT NULL DEFAULT '',
  `tecnologia` varchar(4) DEFAULT NULL,
  `frequencia` varchar(6) DEFAULT NULL,
  `site_filiacao_z` varchar(12) DEFAULT NULL,
  `endereco_id_z` varchar(10) NOT NULL,
  `ot` varchar(40) DEFAULT NULL,
  `banda_solicitada_ot` varchar(20) DEFAULT NULL,
  `banda_atendida` varchar(20) DEFAULT NULL,
  `banda_projetada` varchar(20) DEFAULT NULL,
  `tecnologia_atendida` varchar(20) NOT NULL,
  `solucao_meio_tx_det` varchar(120) NOT NULL,
  `solucao_meio_tx_simp` varchar(20) NOT NULL,
  `informacao_quadro_resumo` varchar(20) NOT NULL,
  `solucao_meio_tx_trecho` varchar(40) NOT NULL,
  `vendor_mw_conexao` varchar(40) DEFAULT NULL,
  `site_ponta_a` varchar(10) NOT NULL,
  `site_ponta_b` varchar(10) NOT NULL,
  `end_id_a` varchar(20) NOT NULL,
  `end_id_b` varchar(20) NOT NULL,
  `end_trecho_id` varchar(40) NOT NULL,
  `classificacao_tipo_processo` varchar(60) NOT NULL,
  `chave_ot` varchar(40) DEFAULT NULL,
  `ampliacao_ampliar_ativar` varchar(20) DEFAULT NULL,
  `hub_fo_ll` varchar(10) DEFAULT NULL,
  `novo_hub_fo_ll` varchar(10) DEFAULT NULL,
  `ponto_fo_favorece_mw` varchar(120) DEFAULT NULL,
  `codigo_orcamentario` varchar(40) DEFAULT NULL,
  `observacao` text,
  `base_line_eng` varchar(10) DEFAULT NULL,
  `base_line_imp` varchar(10) DEFAULT NULL,
  `base_line_acordado` varchar(10) DEFAULT NULL,
  `ano_rollout_previsto` varchar(4) NOT NULL,
  `dominio` varchar(20) NOT NULL,
  `data_inclusao` varchar(10) NOT NULL,
  `data_revisao` varchar(10) DEFAULT NULL,
  `tipo_casa` varchar(40) NOT NULL,
  `tipo_rollout` varchar(40) NOT NULL,
  `x` char(1) DEFAULT NULL,
  `sequencia` varchar(6) NOT NULL,
  `circuito` varchar(60) DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `data_carga` datetime NOT NULL,
  `controle` varchar(10) NOT NULL,
  PRIMARY KEY (`id_plano_operativo`),
  KEY `tercho_id` (`tx_trecho_id`),
  KEY `site_atendido` (`site_atendido`,`tx_trecho_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=62878 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_posto_trabalho;

CREATE TABLE `sgr_posto_trabalho` (
  `id_posto_trabalho` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `label_posto_trabalho` varchar(60) NOT NULL,
  `ordem_aging` int(11) NOT NULL,
  `id_sistema` int(10) unsigned NOT NULL,
  `cor_aging` varchar(10) NOT NULL DEFAULT '#3399CC',
  `id_grupo` int(11) NOT NULL,
  `ativo` char(1) NOT NULL DEFAULT 'S',
  `label_posto_resumido` varchar(20) DEFAULT NULL,
  `tempo_aging` int(5) DEFAULT '120',
  `responsavel` char(1) NOT NULL DEFAULT '',
  `sla` int(10) unsigned NOT NULL DEFAULT '0',
  `contagem` char(1) DEFAULT NULL,
  PRIMARY KEY (`id_posto_trabalho`)
) ENGINE=MyISAM AUTO_INCREMENT=66 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_projetoxarea;

CREATE TABLE `sgr_projetoxarea` (
  `id_projetoxarea` int(11) NOT NULL AUTO_INCREMENT,
  `id_projeto` int(11) DEFAULT NULL,
  `id_area` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_projetoxarea`)
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_projetoxempresa;

CREATE TABLE `sgr_projetoxempresa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_projeto` int(11) DEFAULT NULL,
  `id_empresa` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;




DROP TABLE IF EXISTS sgr_rollout_implantacao;

CREATE TABLE `sgr_rollout_implantacao` (
  `id_rollout_implantacao` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ano` varchar(4) NOT NULL DEFAULT '',
  `gp` varchar(80) NOT NULL DEFAULT '',
  `tipo` varchar(80) NOT NULL DEFAULT '',
  `status` varchar(40) NOT NULL DEFAULT '',
  `data_em_projeto` varchar(10) NOT NULL DEFAULT '',
  `numero_projeto` varchar(20) NOT NULL DEFAULT '',
  `regional` varchar(3) NOT NULL DEFAULT '',
  `uf` varchar(2) NOT NULL DEFAULT '',
  `municipio` varchar(120) NOT NULL DEFAULT '',
  `endereco_id` varchar(20) NOT NULL DEFAULT '',
  `site_id` varchar(40) NOT NULL DEFAULT '',
  `detentor` varchar(40) NOT NULL DEFAULT '',
  `engenheiro_consultor` varchar(120) NOT NULL DEFAULT '',
  `chave_demanda` varchar(80) NOT NULL DEFAULT '',
  `rft_baseline` varchar(10) NOT NULL DEFAULT '',
  `rft_planejado` varchar(10) NOT NULL DEFAULT '',
  `rft_real` varchar(10) NOT NULL DEFAULT '',
  `rfi_baseline` varchar(10) NOT NULL DEFAULT '',
  `rfi_planejado` varchar(10) NOT NULL DEFAULT '',
  `rfi_real` varchar(10) NOT NULL DEFAULT '',
  `fabricante_fornecedor` varchar(80) NOT NULL DEFAULT '',
  `po_hardware` varchar(20) NOT NULL DEFAULT '',
  `po_servico` varchar(20) NOT NULL DEFAULT '',
  `vistoria_baseline` varchar(10) NOT NULL DEFAULT '',
  `vistoria_planejado` varchar(10) NOT NULL DEFAULT '',
  `vistoria_real` varchar(10) NOT NULL DEFAULT '',
  `ppi_baseline` varchar(10) NOT NULL DEFAULT '',
  `ppi_planejado` varchar(10) NOT NULL DEFAULT '',
  `ppi_real` varchar(10) NOT NULL DEFAULT '',
  `mos_baseline` varchar(10) NOT NULL DEFAULT '',
  `mos_planejado` varchar(10) NOT NULL DEFAULT '',
  `mos_real` varchar(10) NOT NULL DEFAULT '',
  `instalacao_previsto_baseline` varchar(10) NOT NULL DEFAULT '',
  `instalacao_inicio_planejado` varchar(10) NOT NULL DEFAULT '',
  `instalacao_inicio_real` varchar(10) NOT NULL DEFAULT '',
  `instalacao_termino_planejado` varchar(10) NOT NULL DEFAULT '',
  `instalacao_termino_real` varchar(10) NOT NULL DEFAULT '',
  `crq` varchar(20) NOT NULL DEFAULT '',
  `energizacao_baseline` varchar(10) NOT NULL DEFAULT '',
  `energizacao_planejado` varchar(10) NOT NULL DEFAULT '',
  `energizacao_real` varchar(10) NOT NULL DEFAULT '',
  `licenciamento_baseline` varchar(10) NOT NULL DEFAULT '',
  `licenciamento_planejado` varchar(10) NOT NULL DEFAULT '',
  `licenciamento_real` varchar(10) NOT NULL DEFAULT '',
  `construcao_baseline` varchar(10) NOT NULL DEFAULT '',
  `construcao_planejado` varchar(10) NOT NULL DEFAULT '',
  `construcao_real` varchar(10) NOT NULL DEFAULT '',
  `swap_baseline` varchar(10) NOT NULL DEFAULT '',
  `swap_planejado` varchar(10) NOT NULL DEFAULT '',
  `swap_real` varchar(10) NOT NULL DEFAULT '',
  `designacao` varchar(40) NOT NULL DEFAULT '',
  `parceiro` varchar(40) NOT NULL DEFAULT '',
  `ativacao_baseline` varchar(10) NOT NULL DEFAULT '',
  `ativacao_inicio_planejado` varchar(10) NOT NULL DEFAULT '',
  `ativacao_inicio_real` varchar(10) NOT NULL DEFAULT '',
  `ativacao_termino_planejado` varchar(10) NOT NULL DEFAULT '',
  `ativacao_termino_real` varchar(10) NOT NULL DEFAULT '',
  `documentacao_baseline` varchar(10) NOT NULL DEFAULT '',
  `documentacao_planejado` varchar(10) NOT NULL DEFAULT '',
  `documentacao_real` varchar(10) NOT NULL DEFAULT '',
  `cadastro_wo` varchar(40) NOT NULL DEFAULT '',
  `cadastro_baseline` varchar(10) NOT NULL DEFAULT '',
  `cadastro_planejado` varchar(10) NOT NULL DEFAULT '',
  `cadastro_real` varchar(10) NOT NULL DEFAULT '',
  `aceitacao_fisica_wo` varchar(40) NOT NULL DEFAULT '',
  `aceitacao_fisica_baseline` varchar(10) NOT NULL DEFAULT '',
  `aceitacao_fisica_planejado` varchar(10) NOT NULL DEFAULT '',
  `aceitacao_fisica_real` varchar(10) NOT NULL DEFAULT '',
  `aceitacao_logica_wo` varchar(40) NOT NULL DEFAULT '',
  `aceitacao_logica_baseline` varchar(10) NOT NULL DEFAULT '',
  `aceitacao_logica_planejado` varchar(10) NOT NULL DEFAULT '',
  `aceitacao_logica_real` varchar(10) NOT NULL DEFAULT '',
  `configuracao_wo` varchar(40) NOT NULL DEFAULT '',
  `configuracao_baseline` varchar(10) NOT NULL DEFAULT '',
  `configuracao_planejado` varchar(10) NOT NULL DEFAULT '',
  `configuracao_real` varchar(10) NOT NULL DEFAULT '',
  `jumper_baseline` varchar(10) NOT NULL DEFAULT '',
  `jumper_planejado` varchar(10) NOT NULL DEFAULT '',
  `jumper_real` varchar(10) NOT NULL DEFAULT '',
  `migracao_crq` varchar(40) NOT NULL DEFAULT '',
  `migracao_baseline` varchar(10) NOT NULL DEFAULT '',
  `migracao_planejado` varchar(10) NOT NULL DEFAULT '',
  `migracao_real` varchar(10) NOT NULL DEFAULT '',
  `baseline_engenharia` varchar(10) NOT NULL DEFAULT '',
  `mes_ano_conclusao` varchar(10) NOT NULL DEFAULT '',
  `pendencia_hoje` tinytext NOT NULL,
  `observacao` text NOT NULL,
  `id_usuario` int(10) unsigned NOT NULL DEFAULT '0',
  `controle` varchar(10) NOT NULL DEFAULT '',
  `data_carga` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ativo` char(1) NOT NULL DEFAULT 'S',
  PRIMARY KEY (`id_rollout_implantacao`)
) ENGINE=InnoDB AUTO_INCREMENT=233 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_rollout_longhauling;

CREATE TABLE `sgr_rollout_longhauling` (
  `id_rollout_longhauling` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` varchar(45) NOT NULL DEFAULT '',
  `projeto` varchar(45) NOT NULL DEFAULT '',
  `baseline_engenharia` varchar(45) NOT NULL DEFAULT '',
  `data_conclusao` varchar(45) NOT NULL DEFAULT '',
  `ativo` char(1) NOT NULL DEFAULT 'S',
  `id_usuario` int(10) unsigned NOT NULL DEFAULT '0',
  `controle` varchar(10) NOT NULL DEFAULT '',
  `data_carga` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id_rollout_longhauling`)
) ENGINE=InnoDB AUTO_INCREMENT=167 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_sistema_tipo_n1;

CREATE TABLE `sgr_sistema_tipo_n1` (
  `id_n1` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_sistema` int(10) unsigned NOT NULL,
  `id_tipo` int(10) unsigned NOT NULL,
  `id_posto_n1` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_n1`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_sitexchave;

CREATE TABLE `sgr_sitexchave` (
  `id_sitexchave` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site` varchar(10) NOT NULL,
  `plano` varchar(10) NOT NULL,
  `regional` varchar(4) NOT NULL,
  `chave` varchar(20) NOT NULL,
  `status` varchar(20) NOT NULL,
  `data_atualizacao` datetime NOT NULL,
  `ativo` char(1) NOT NULL DEFAULT 'S',
  `id_usuario` int(10) unsigned NOT NULL DEFAULT '0',
  `controle` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_sitexchave`)
) ENGINE=InnoDB AUTO_INCREMENT=63087 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_solicitante;

CREATE TABLE `sgr_solicitante` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_area` int(11) DEFAULT NULL,
  `id_loja` int(11) DEFAULT NULL,
  `nome_solicitante` varchar(120) COLLATE latin1_general_ci NOT NULL,
  `email_solicitante` varchar(120) COLLATE latin1_general_ci NOT NULL,
  `contato_solicitante` varchar(15) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=82 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;




DROP TABLE IF EXISTS sgr_status;

CREATE TABLE `sgr_status` (
  `id_status` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `label_status` varchar(60) NOT NULL,
  `ativo` char(1) DEFAULT 'S',
  PRIMARY KEY (`id_status`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_teste;

CREATE TABLE `sgr_teste` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL DEFAULT '',
  `endereco` varchar(45) NOT NULL DEFAULT '',
  `cor` varchar(45) NOT NULL DEFAULT '',
  `tamanho` varchar(45) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_ticket_baselinexplanejado;

CREATE TABLE `sgr_ticket_baselinexplanejado` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `id_ticket` smallint(6) NOT NULL,
  `date_base_line` date DEFAULT NULL,
  `data_planejado` date DEFAULT NULL,
  `id_posto` int(11) NOT NULL,
  `id_status_posto` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_ticket_header;

CREATE TABLE `sgr_ticket_header` (
  `id_ticket` int(11) NOT NULL,
  `nome_projeto` varchar(60) NOT NULL,
  `regional` varchar(3) NOT NULL,
  `uf` varchar(2) NOT NULL,
  `municipio` varchar(200) NOT NULL,
  `po` varchar(200) NOT NULL,
  `data_po` date NOT NULL,
  `ref_netcorp` varchar(60) NOT NULL,
  `site_2g` varchar(20) NOT NULL,
  `site_3g` varchar(20) DEFAULT NULL,
  `site_4g` varchar(20) DEFAULT NULL,
  `site_smalcell` varchar(20) DEFAULT NULL,
  `funcao` varchar(120) NOT NULL,
  `anel` varchar(60) NOT NULL,
  `detentor` varchar(60) DEFAULT NULL,
  `tipo_infra` varchar(60) DEFAULT NULL,
  `observacao` text,
  `endereco` varchar(200) NOT NULL,
  `latitude` varchar(20) NOT NULL,
  `longitude` varchar(20) NOT NULL,
  `data_base_line` date NOT NULL,
  PRIMARY KEY (`id_ticket`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_ticket_header_2;

CREATE TABLE `sgr_ticket_header_2` (
  `id_ticket` int(10) unsigned NOT NULL DEFAULT '0',
  `chave` varchar(20) NOT NULL,
  `prioridade` varchar(45) NOT NULL,
  `projeto_principal` varchar(45) NOT NULL,
  `projeto_secundario` varchar(45) NOT NULL,
  `det_projeto_associado` varchar(45) NOT NULL,
  `det_ampliacao` varchar(45) NOT NULL,
  `capacidade` varchar(45) NOT NULL,
  `site_id_a` varchar(10) NOT NULL,
  `classificacao_ponta_a` varchar(45) NOT NULL,
  `acq_ponta_a` varchar(45) NOT NULL,
  `qualificacao_ponta_a` varchar(45) NOT NULL,
  `aquisicao_ponta_a` varchar(45) NOT NULL,
  `site_id_b` varchar(10) NOT NULL,
  `classificacao_ponta_b` varchar(45) NOT NULL,
  `acq_ponta_b` varchar(45) NOT NULL,
  `qualificacao_ponta_b` varchar(45) NOT NULL,
  `aquisicao_ponta_b` varchar(45) NOT NULL,
  `enlace` varchar(45) NOT NULL,
  `endereco_id_ponta_a` varchar(45) NOT NULL,
  `municipio_ponta_a` varchar(45) NOT NULL,
  `endereco_id_ponta_b` varchar(45) NOT NULL,
  `municipio_ponta_b` varchar(45) NOT NULL,
  `uf` varchar(45) NOT NULL,
  `detentor_ponta_a` varchar(45) NOT NULL,
  `detentor_ponta_b` varchar(45) NOT NULL,
  `tipo_hop` varchar(45) NOT NULL,
  `hop_qualificacao` varchar(45) NOT NULL,
  `vendor` varchar(45) NOT NULL,
  `regional` varchar(45) NOT NULL,
  `contabiliza_epm` char(1) NOT NULL DEFAULT '',
  `perimetro` varchar(45) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_ticket`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_tipo_chamado;

CREATE TABLE `sgr_tipo_chamado` (
  `id_tipo_chamado` int(11) NOT NULL AUTO_INCREMENT,
  `label_tipo_chamado` varchar(60) NOT NULL,
  `ativo` char(1) NOT NULL DEFAULT 'S',
  PRIMARY KEY (`id_tipo_chamado`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_tipo_tarefa;

CREATE TABLE `sgr_tipo_tarefa` (
  `id_tipo_tarefa` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `label_tipo_tarefa` varchar(60) NOT NULL,
  `id_sistema` int(10) unsigned NOT NULL,
  `id_posto` int(10) unsigned NOT NULL,
  `descricao_default` text,
  `ativo` char(1) NOT NULL DEFAULT 'S',
  PRIMARY KEY (`id_tipo_tarefa`)
) ENGINE=MyISAM AUTO_INCREMENT=145 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_tipoxprojeto;

CREATE TABLE `sgr_tipoxprojeto` (
  `id_tipoxprojeto` int(11) NOT NULL AUTO_INCREMENT,
  `id_tipo_chamado` int(11) NOT NULL,
  `id_projeto` int(11) NOT NULL,
  `ativo` char(1) NOT NULL DEFAULT 'S',
  PRIMARY KEY (`id_tipoxprojeto`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_trata_encaminhamento_especial;

CREATE TABLE `sgr_trata_encaminhamento_especial` (
  `id_trata_encaminhamento_especial` int(11) NOT NULL AUTO_INCREMENT,
  `id_tipo_chamado` int(11) DEFAULT NULL,
  `id_encerramento` int(11) DEFAULT NULL,
  `id_posto_trabalho` int(11) DEFAULT NULL,
  `id_status` int(11) DEFAULT NULL,
  `ativo` char(1) DEFAULT 'S',
  PRIMARY KEY (`id_trata_encaminhamento_especial`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_uf;

CREATE TABLE `sgr_uf` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sigla_uf` varchar(3) NOT NULL DEFAULT '',
  `nome_uf` varchar(100) NOT NULL,
  `regiao` varchar(100) NOT NULL,
  `regiao_oi` varchar(6) NOT NULL,
  `regiao_tim` varchar(6) DEFAULT NULL,
  `ativo` char(1) NOT NULL DEFAULT 'S',
  PRIMARY KEY (`id`),
  UNIQUE KEY `sigla` (`sigla_uf`),
  KEY `valido` (`ativo`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_usuario;

CREATE TABLE `sgr_usuario` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(60) NOT NULL,
  `nome` varchar(120) NOT NULL,
  `password` varchar(60) NOT NULL,
  `id_status_usuario` int(11) NOT NULL,
  `email` varchar(60) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `cpf` varchar(18) NOT NULL,
  `id_empresa` int(11) NOT NULL,
  `id_area` int(11) NOT NULL,
  `ultimo_acesso` datetime DEFAULT '0000-00-00 00:00:00',
  `id_perfil` int(11) NOT NULL,
  `ativo` char(1) NOT NULL DEFAULT 'S',
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `cpf` (`cpf`)
) ENGINE=MyISAM AUTO_INCREMENT=164 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_usuarioxempresa_atendimento;

CREATE TABLE `sgr_usuarioxempresa_atendimento` (
  `id_usuarioxempresa` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `id_empresa` int(11) DEFAULT NULL,
  `ativo` char(1) DEFAULT 'S',
  PRIMARY KEY (`id_usuarioxempresa`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sgr_vendor;

CREATE TABLE `sgr_vendor` (
  `id_vendor` int(11) NOT NULL AUTO_INCREMENT,
  `label_vendor` varchar(120) NOT NULL,
  `ativo` char(1) NOT NULL DEFAULT 'S',
  PRIMARY KEY (`id_vendor`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS sistemas;

CREATE TABLE `sistemas` (
  `id_sistema` int(11) NOT NULL AUTO_INCREMENT,
  `label_sistema` varchar(120) NOT NULL,
  `ativo` char(1) NOT NULL DEFAULT 'S',
  PRIMARY KEY (`id_sistema`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=latin1 PACK_KEYS=1;




DROP TABLE IF EXISTS view_usuario_tratando;

CREATE TABLE `view_usuario_tratando` (
  `nome` varchar(120) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_posto` int(10) unsigned DEFAULT NULL,
  `total` bigint(21) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




