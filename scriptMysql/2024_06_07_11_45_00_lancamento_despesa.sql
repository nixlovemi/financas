CREATE TABLE `tb_lancamento_despesa` (
  `ld_id` int(11) NOT NULL,
  `ld_lan_id` int(11) NOT NULL,
  `ld_bdp_id` int(11) NOT NULL,
  `ld_valor` double NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4121 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `tb_lancamento_despesa`
  ADD PRIMARY KEY (`ld_id`),
  ADD KEY `fk_ld_lan_id` (`ld_lan_id`),
  ADD KEY `fk_ld_bdp_id` (`ld_bdp_id`);
  
ALTER TABLE `tb_lancamento_despesa`
  MODIFY `ld_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
  
ALTER TABLE `tb_lancamento_despesa`
  ADD CONSTRAINT `fk_ld_lan_id` FOREIGN KEY (`ld_lan_id`) REFERENCES `tb_lancamento` (`lan_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ld_bdp_id` FOREIGN KEY (`ld_bdp_id`) REFERENCES `tb_base_despesa` (`bdp_id`) ON UPDATE CASCADE;

-- =====================================================

INSERT INTO tb_lancamento_despesa (ld_lan_id, ld_bdp_id, ld_valor)
SELECT lan_id, lan_categoria, COALESCE(lan_valor_pago, lan_valor)
FROM tb_lancamento;

-- =====================================================
ALTER TABLE tb_lancamento
  DROP FOREIGN KEY fk_lan_categoria;
ALTER TABLE tb_lancamento
  DROP COLUMN lan_categoria;
