SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `tb_usuario` (
  `usu_id` int(10) NOT NULL,
  `usu_login` varchar(40) NOT NULL,
  `usu_senha` varchar(40) NOT NULL,
  `usu_nome` varchar(50) DEFAULT NULL,
  `usu_sobrenome` varchar(50) DEFAULT NULL,
  `usu_email` varchar(100) DEFAULT NULL,
  `usu_ativo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `tb_usuario` (`usu_id`, `usu_login`, `usu_senha`, `usu_nome`, `usu_sobrenome`, `usu_email`, `usu_ativo`) VALUES
(1, 'admin', '213299609efe85beef603ede5c10a508', 'Admin', NULL, 'admin@gmail.com', 1);

ALTER TABLE `tb_usuario`
  ADD PRIMARY KEY (`usu_id`),
  ADD UNIQUE KEY `usu_id` (`usu_id`),
  ADD UNIQUE KEY `uk_usu_login` (`usu_login`);
  
ALTER TABLE `tb_usuario`
  MODIFY `usu_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

CREATE TABLE `tb_menu` (
  `men_id` int(11) NOT NULL,
  `men_descricao` varchar(35) NOT NULL,
  `men_controller` varchar(50) NOT NULL,
  `men_action` varchar(50) NOT NULL,
  `men_vars` varchar(100) DEFAULT NULL,
  `men_id_pai` int(11) DEFAULT NULL,
  `men_ativo` tinyint(1) NOT NULL DEFAULT 1,
  `men_icon` varchar(50) DEFAULT NULL,
  `men_order` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `tb_menu` (`men_id`, `men_descricao`, `men_controller`, `men_action`, `men_vars`, `men_id_pai`, `men_ativo`, `men_icon`, `men_order`) VALUES
(1, 'Início', 'Start', 'index', NULL, NULL, 1, '<i class=\"icon icon-home\"></i>', 0),
(13, 'Relatórios', 'Relatorio', 'index', NULL, NULL, 1, '<i class=\"icon icon-print\"></i>', 6),
(15, 'Lançamentos', 'Lancamentos', 'index', NULL, NULL, 1, '<i class=\"icon icon-money\"></i>', 1);

ALTER TABLE `tb_menu`
  ADD PRIMARY KEY (`men_id`);
  
ALTER TABLE `tb_menu`
  MODIFY `men_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

CREATE TABLE `tb_conta` (
  `con_id` int(11) NOT NULL,
  `con_nome` varchar(50) NOT NULL,
  `con_sigla` varchar(4) NOT NULL,
  `con_data_saldo` date NOT NULL,
  `con_saldo_inicial` double NOT NULL DEFAULT 0,
  `con_ativo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `tb_conta` (`con_id`, `con_nome`, `con_sigla`, `con_data_saldo`, `con_saldo_inicial`, `con_ativo`) VALUES
(1, 'Carteira', 'CART', '2000-01-01', 0, 1);

ALTER TABLE `tb_conta`
  ADD PRIMARY KEY (`con_id`),
  ADD UNIQUE KEY `idx_con_sigla` (`con_sigla`);
  
ALTER TABLE `tb_conta`
  MODIFY `con_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

CREATE TABLE `tb_base_despesa` (
  `bdp_id` int(11) NOT NULL,
  `bdp_descricao` varchar(50) NOT NULL,
  `bdp_tipo` char(1) NOT NULL COMMENT 'I=Investimento; F=Fixas; V=Variáveis',
  `bdp_contabiliza` tinyint(1) NOT NULL DEFAULT 1,
  `bdp_ativo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

ALTER TABLE `tb_base_despesa`
  ADD PRIMARY KEY (`bdp_id`),
  ADD UNIQUE KEY `bdp_descricao_tipo` (`bdp_descricao`,`bdp_tipo`);
  
ALTER TABLE `tb_base_despesa`
  MODIFY `bdp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;

CREATE TABLE `tb_meta_despesa` (
  `mdp_id` int(11) NOT NULL,
  `mdp_despesa` int(11) NOT NULL,
  `mdp_mes` char(2) NOT NULL,
  `mdp_ano` char(4) NOT NULL,
  `mdp_valor` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

ALTER TABLE `tb_meta_despesa`
  ADD PRIMARY KEY (`mdp_id`),
  ADD UNIQUE KEY `idx_despesa_mes_ano` (`mdp_despesa`,`mdp_mes`,`mdp_ano`);
  
ALTER TABLE `tb_meta_despesa`
  MODIFY `mdp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
  
ALTER TABLE `tb_meta_despesa`
  ADD CONSTRAINT `fk_mdp_despesa` FOREIGN KEY (`mdp_despesa`) REFERENCES `tb_base_despesa` (`bdp_id`) ON UPDATE CASCADE;
COMMIT;

CREATE TABLE `tb_lancamento` (
  `lan_id` int(11) NOT NULL,
  `lan_despesa` varchar(60) NOT NULL,
  `lan_tipo` char(1) NOT NULL COMMENT 'R=Receita; D=Despesa; T=Transferência',
  `lan_parcela` varchar(12) DEFAULT NULL,
  `lan_compra` date DEFAULT NULL COMMENT 'data que a compra foi realizada',
  `lan_vencimento` date NOT NULL,
  `lan_valor` double NOT NULL,
  `lan_categoria` int(11) NOT NULL,
  `lan_pagamento` date DEFAULT NULL,
  `lan_valor_pago` double DEFAULT NULL,
  `lan_conta` int(11) DEFAULT NULL,
  `lan_observacao` text DEFAULT NULL,
  `lan_confirmado` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

ALTER TABLE `tb_lancamento`
  ADD PRIMARY KEY (`lan_id`),
  ADD KEY `fk_lan_conta` (`lan_conta`),
  ADD KEY `fk_lan_categoria` (`lan_categoria`);
  
ALTER TABLE `tb_lancamento`
  MODIFY `lan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
  
ALTER TABLE `tb_lancamento`
  ADD CONSTRAINT `fk_lan_categoria` FOREIGN KEY (`lan_categoria`) REFERENCES `tb_base_despesa` (`bdp_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_lan_conta` FOREIGN KEY (`lan_conta`) REFERENCES `tb_conta` (`con_id`) ON UPDATE CASCADE;
COMMIT;