-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 30-Jun-2024 às 03:02
-- Versão do servidor: 10.4.17-MariaDB
-- versão do PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `autodoc-webservice`
--
CREATE DATABASE IF NOT EXISTS `autodoc-webservice` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `autodoc-webservice`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `dados_convenios_publico`
--

DROP TABLE IF EXISTS `dados_convenios_publico`;
CREATE TABLE IF NOT EXISTS `dados_convenios_publico` (
  `NR_CONVENIO` varchar(255) DEFAULT NULL,
  `ID_PROPOSTA` varchar(255) DEFAULT NULL,
  `DIA` varchar(255) DEFAULT NULL,
  `MES` varchar(255) DEFAULT NULL,
  `ANO` varchar(255) DEFAULT NULL,
  `DIA_ASSIN_CONV` varchar(255) DEFAULT NULL,
  `SIT_CONVENIO` varchar(255) DEFAULT NULL,
  `SUBSITUACAO_CONV` varchar(255) DEFAULT NULL,
  `SITUACAO_PUBLICACAO` varchar(255) DEFAULT NULL,
  `INSTRUMENTO_ATIVO` varchar(255) DEFAULT NULL,
  `IND_OPERA_OBTV` varchar(255) DEFAULT NULL,
  `NR_PROCESSO` varchar(255) DEFAULT NULL,
  `UG_EMITENTE` varchar(255) DEFAULT NULL,
  `DIA_PUBL_CONV` varchar(255) DEFAULT NULL,
  `DIA_INIC_VIGENC_CONV` varchar(255) DEFAULT NULL,
  `DIA_FIM_VIGENC_CONV` varchar(255) DEFAULT NULL,
  `DIA_FIM_VIGENC_ORIGINAL_CONV` varchar(255) DEFAULT NULL,
  `DIAS_PREST_CONTAS` varchar(255) DEFAULT NULL,
  `DIA_LIMITE_PREST_CONTAS` varchar(255) DEFAULT NULL,
  `DATA_SUSPENSIVA` varchar(255) DEFAULT NULL,
  `DATA_RETIRADA_SUSPENSIVA` varchar(255) DEFAULT NULL,
  `DIAS_CLAUSULA_SUSPENSIVA` varchar(255) DEFAULT NULL,
  `SITUACAO_CONTRATACAO` varchar(255) DEFAULT NULL,
  `IND_ASSINADO` varchar(255) DEFAULT NULL,
  `MOTIVO_SUSPENSAO` varchar(255) DEFAULT NULL,
  `IND_FOTO` varchar(255) DEFAULT NULL,
  `QTDE_CONVENIOS` varchar(255) DEFAULT NULL,
  `QTD_TA` varchar(255) DEFAULT NULL,
  `QTD_PRORROGA` varchar(255) DEFAULT NULL,
  `VL_GLOBAL_CONV` varchar(255) DEFAULT NULL,
  `VL_REPASSE_CONV` varchar(255) DEFAULT NULL,
  `VL_CONTRAPARTIDA_CONV` varchar(255) DEFAULT NULL,
  `VL_EMPENHADO_CONV` varchar(255) DEFAULT NULL,
  `VL_DESEMBOLSADO_CONV` varchar(255) DEFAULT NULL,
  `VL_SALDO_REMAN_TESOURO` varchar(255) DEFAULT NULL,
  `VL_SALDO_REMAN_CONVENENTE` varchar(255) DEFAULT NULL,
  `VL_RENDIMENTO_APLICACAO` varchar(255) DEFAULT NULL,
  `VL_INGRESSO_CONTRAPARTIDA` varchar(255) DEFAULT NULL,
  `VL_SALDO_CONTA` varchar(255) DEFAULT NULL,
  `VALOR_GLOBAL_ORIGINAL_CONV` varchar(255) DEFAULT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  UNIQUE KEY `NR_CONVENIO` (`NR_CONVENIO`),
  KEY `ID_PROPOSTA` (`ID_PROPOSTA`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `dados_proposta_publico`
--

DROP TABLE IF EXISTS `dados_proposta_publico`;
CREATE TABLE IF NOT EXISTS `dados_proposta_publico` (
  `ID_PROPOSTA` varchar(255) DEFAULT NULL,
  `UF_PROPONENTE` varchar(255) DEFAULT NULL,
  `MUNIC_PROPONENTE` varchar(255) DEFAULT NULL,
  `COD_MUNIC_IBGE` varchar(255) DEFAULT NULL,
  `COD_ORGAO_SUP` varchar(255) DEFAULT NULL,
  `DESC_ORGAO_SUP` varchar(255) DEFAULT NULL,
  `NATUREZA_JURIDICA` varchar(255) DEFAULT NULL,
  `NR_PROPOSTA` varchar(255) DEFAULT NULL,
  `DIA_PROP` varchar(255) DEFAULT NULL,
  `MES_PROP` varchar(255) DEFAULT NULL,
  `ANO_PROP` varchar(255) DEFAULT NULL,
  `DIA_PROPOSTA` varchar(255) DEFAULT NULL,
  `COD_ORGAO` varchar(255) DEFAULT NULL,
  `DESC_ORGAO` varchar(255) DEFAULT NULL,
  `MODALIDADE` varchar(255) DEFAULT NULL,
  `IDENTIF_PROPONENTE` varchar(255) DEFAULT NULL,
  `NM_PROPONENTE` varchar(255) DEFAULT NULL,
  `CEP_PROPONENTE` varchar(255) DEFAULT NULL,
  `ENDERECO_PROPONENTE` varchar(255) DEFAULT NULL,
  `BAIRRO_PROPONENTE` varchar(255) DEFAULT NULL,
  `NM_BANCO` varchar(255) DEFAULT NULL,
  `SITUACAO_CONTA` varchar(255) DEFAULT NULL,
  `SITUACAO_PROJETO_BASICO` varchar(255) DEFAULT NULL,
  `SIT_PROPOSTA` varchar(255) DEFAULT NULL,
  `DIA_INIC_VIGENCIA_PROPOSTA` varchar(255) DEFAULT NULL,
  `DIA_FIM_VIGENCIA_PROPOSTA` varchar(255) DEFAULT NULL,
  `OBJETO_PROPOSTA` varchar(255) DEFAULT NULL,
  `ITEM_INVESTIMENTO` varchar(255) DEFAULT NULL,
  `ENVIADA_MANDATARIA` varchar(255) DEFAULT NULL,
  `NOME_SUBTIPO_PROPOSTA` varchar(255) DEFAULT NULL,
  `DESCRICAO_SUBTIPO_PROPOSTA` varchar(255) DEFAULT NULL,
  `VL_GLOBAL_PROP` varchar(255) DEFAULT NULL,
  `VL_REPASSE_PROP` varchar(255) DEFAULT NULL,
  `VL_CONTRAPARTIDA_PROP` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `dados_webservice`
--

DROP TABLE IF EXISTS `dados_webservice`;
CREATE TABLE IF NOT EXISTS `dados_webservice` (
  `nr_convenio` int(11) NOT NULL,
  `ano_convenio` int(11) NOT NULL,
  `dados` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`dados`)),
  `ultima_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  UNIQUE KEY `nr_convenio` (`nr_convenio`),
  KEY `nr_convenio_2` (`nr_convenio`),
  KEY `ano_convenio` (`ano_convenio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `solicitacoes_atualizacao`
--

DROP TABLE IF EXISTS `solicitacoes_atualizacao`;
CREATE TABLE IF NOT EXISTS `solicitacoes_atualizacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero_convenio` int(15) NOT NULL,
  `ano_convenio` int(4) NOT NULL,
  `returnUrl` varchar(255) NOT NULL,
  `status` enum('Pendente','Enviado','Falha') NOT NULL DEFAULT 'Pendente',
  `resposta` text DEFAULT NULL,
  `data_solicitacao` datetime NOT NULL DEFAULT current_timestamp(),
  `ultima_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
