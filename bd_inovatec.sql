-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 21/11/2024 às 01:57
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `bd_inovatec`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `buffet`
--

CREATE TABLE `buffet` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `tipo_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `buffet`
--

INSERT INTO `buffet` (`id`, `nome`, `descricao`, `criado_em`, `tipo_id`) VALUES
(12, 'comida japonesa', 'culinÃ¡ria japonesa', '2024-11-11 03:51:47', NULL),
(16, 'comida mexicana', 'tacos e nachos', '2024-11-18 03:00:30', NULL),
(17, 'comidas italiana', 'pizzaiolo', '2024-11-18 03:01:36', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `buffet_tipo`
--

CREATE TABLE `buffet_tipo` (
  `buffet_id` int(11) NOT NULL,
  `tipo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cargos`
--

CREATE TABLE `cargos` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `cargos`
--

INSERT INTO `cargos` (`id`, `nome`) VALUES
(1, 'Auxiliar de cozinha'),
(2, 'Barmen'),
(3, 'Garcom'),
(4, 'Faxineiro'),
(5, 'Seguranca'),
(6, 'Porteiro');

-- --------------------------------------------------------

--
-- Estrutura para tabela `escolaridades`
--

CREATE TABLE `escolaridades` (
  `id` int(11) NOT NULL,
  `nivel_escolaridade` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `escolaridades`
--

INSERT INTO `escolaridades` (`id`, `nivel_escolaridade`) VALUES
(16, 'Ensino Superior'),
(17, 'Ensino Fundamental'),
(26, 'Ensino Medio');

-- --------------------------------------------------------

--
-- Estrutura para tabela `eventos`
--

CREATE TABLE `eventos` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `data` date NOT NULL,
  `local` varchar(255) DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `lotacao` int(11) DEFAULT NULL,
  `duracao` int(11) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `tema_id` int(11) DEFAULT NULL,
  `objetivo_id` int(11) DEFAULT NULL,
  `buffet_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `eventos`
--

INSERT INTO `eventos` (`id`, `nome`, `data`, `local`, `hora`, `lotacao`, `duracao`, `descricao`, `criado_em`, `tema_id`, `objetivo_id`, `buffet_id`) VALUES
(2, 'Dia das crianÃ§as', '2024-10-12', 'FAETEC CVT NilÃ³polis', '13:00:00', 100, 20, 'Muitas brincadeira e diversÃ£o', '2024-10-20 22:44:33', NULL, NULL, NULL),
(3, 'Festa Junina', '2024-06-30', 'FAETEC CVT NilÃ³polis', '13:00:00', 100, 100, 'Venha se divertir no arraiÃ¡ da faetec', '2024-10-20 22:56:06', NULL, NULL, NULL),
(5, 'Halloween', '2024-10-31', 'FAETEC CVT NilÃ³polis', '13:00:00', 100, 18, 'dia das bruxas', '2024-11-16 19:28:49', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `evento_buffet`
--

CREATE TABLE `evento_buffet` (
  `evento_id` int(11) NOT NULL,
  `buffet_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `evento_objetivo`
--

CREATE TABLE `evento_objetivo` (
  `evento_id` int(11) NOT NULL,
  `objetivo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `evento_staff`
--

CREATE TABLE `evento_staff` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `funcao` varchar(50) NOT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `evento_id` int(11) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `evento_staff`
--

INSERT INTO `evento_staff` (`id`, `nome`, `email`, `funcao`, `telefone`, `evento_id`, `criado_em`) VALUES
(1, 'andrei', 'andreieluiz234@gmail.com', 'barmen', '983442525', -1, '2024-10-19 19:42:42');

-- --------------------------------------------------------

--
-- Estrutura para tabela `evento_tema`
--

CREATE TABLE `evento_tema` (
  `evento_id` int(11) NOT NULL,
  `tema_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `faixa_etaria`
--

CREATE TABLE `faixa_etaria` (
  `id` int(11) NOT NULL,
  `descricao` varchar(50) NOT NULL,
  `idade_minima` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `faixa_etaria`
--

INSERT INTO `faixa_etaria` (`id`, `descricao`, `idade_minima`) VALUES
(86, 'menor de dezoito anos', 0),
(87, 'maior de dezoito anos', 0),
(88, 'qualquer idade', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `instituicao`
--

CREATE TABLE `instituicao` (
  `idInstituicao` int(11) NOT NULL,
  `Nome` varchar(45) NOT NULL,
  `Email` varchar(45) NOT NULL,
  `Telefone` varchar(11) NOT NULL,
  `Pronome` varchar(45) DEFAULT NULL,
  `Endereco` varchar(45) NOT NULL,
  `CNPJ` bigint(20) NOT NULL,
  `CEP` varchar(11) NOT NULL,
  `Cidade` varchar(45) DEFAULT NULL,
  `Bairro` varchar(45) NOT NULL,
  `Estado` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `instituicao`
--

INSERT INTO `instituicao` (`idInstituicao`, `Nome`, `Email`, `Telefone`, `Pronome`, `Endereco`, `CNPJ`, `CEP`, `Cidade`, `Bairro`, `Estado`) VALUES
(19, 'faetec', 'faetec@gmail.com', '2126919015', 'esta instituicao', '470 estrada general olimpio da fonseca', 31608763002563, '26545470', 'nilopolis', 'paiol', 'rj');

-- --------------------------------------------------------

--
-- Estrutura para tabela `objetivo`
--

CREATE TABLE `objetivo` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `objetivo`
--

INSERT INTO `objetivo` (`id`, `nome`, `descricao`, `criado_em`) VALUES
(4, 'Formatura', 'Formando 2024', '2024-10-28 03:34:40'),
(7, 'arrecadaÃ§Ã£o', 'just dance', '2024-11-13 02:35:39');

-- --------------------------------------------------------

--
-- Estrutura para tabela `problemas_evento`
--

CREATE TABLE `problemas_evento` (
  `id` int(11) NOT NULL,
  `nome_evento` varchar(255) NOT NULL,
  `data_evento` date NOT NULL,
  `descricao_problema` text NOT NULL,
  `data_reportada` timestamp NOT NULL DEFAULT current_timestamp(),
  `contato` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtor`
--

CREATE TABLE `produtor` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `senha` varchar(45) DEFAULT NULL,
  `cpf` varchar(14) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `produtor`
--

INSERT INTO `produtor` (`id`, `nome`, `email`, `telefone`, `senha`, `cpf`, `criado_em`) VALUES
(9, 'caua', 'cauafelipe@gmail.com', '975684553', '240cf8cac0a6cfb9ef0c56a050a2c5c6', '234.986.564-07', '2024-10-27 22:13:52'),
(20, 'nicolle', 'nicollevitoria@gmail.com', '97856357', '836e86ecbb83e875fd11a2e11e88e4b7', '227.897.685-98', '2024-11-06 04:31:36'),
(30, 'erica', 'ericasouza@gmail.com', '987658788', 'a8698009bce6d1b8c2128eddefc25aad', '227.435.765-08', '2024-11-15 16:06:31'),
(31, 'andrei', 'andreiluiz@gmail.com', '983442525', 'bb85dcf04107a3eee86886fb35022f28', '227.246.477-06', '2024-11-15 16:15:07');

-- --------------------------------------------------------

--
-- Estrutura para tabela `staffs_eventos`
--

CREATE TABLE `staffs_eventos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `cargo` varchar(100) DEFAULT NULL,
  `cargo_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `staffs_eventos`
--

INSERT INTO `staffs_eventos` (`id`, `nome`, `telefone`, `email`, `criado_em`, `cargo`, `cargo_id`) VALUES
(2, 'andrei', '983442525', 'andreieluiz234@gmail.com', '2024-10-20 00:34:41', 'auxiliar de cozinha', NULL),
(3, 'nicolle', '97856357', 'nicollevitoria@gmail.com', '2024-10-21 02:22:47', 'garÃ§om', NULL),
(4, 'nicolle', '98574633', 'nicollevitoria@gmail.com', '2024-10-27 18:09:04', '', NULL),
(5, 'andrei', '983442525', 'andreieluiz234@gmail.com', '2024-10-27 18:20:17', NULL, 3),
(6, 'nicolle', '98574633', 'nicollevitoria@gmail.com', '2024-10-27 18:22:39', NULL, 6),
(7, 'nicolle', '987056643', 'nicollevitoria@gmail.com', '2024-11-13 22:55:16', NULL, 2),
(8, 'andrei', '97220-6160', 'andreieluiz234@gmail.com', '2024-11-14 01:35:01', NULL, 3),
(9, 'erica', '98574633', 'ericasouza@gmail.com', '2024-11-18 03:07:01', NULL, 2),
(10, 'caua', '978865-987', 'cauafelipe@gmail.com', '2024-11-18 03:08:12', NULL, 6),
(11, 'caua', '978865-987', 'cauafelipe@gmail.com', '2024-11-18 03:09:15', NULL, 6),
(12, 'caua', '978865-987', 'cauafelipe@gmail.com', '2024-11-18 03:09:52', NULL, 6);

-- --------------------------------------------------------

--
-- Estrutura para tabela `staff_cargo`
--

CREATE TABLE `staff_cargo` (
  `staff_id` int(11) NOT NULL,
  `cargo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `status_social`
--

CREATE TABLE `status_social` (
  `id` int(11) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `status_social`
--

INSERT INTO `status_social` (`id`, `descricao`, `created_at`) VALUES
(1, 'A', '2024-10-20 01:23:17'),
(2, 'B', '2024-10-20 01:25:07'),
(3, 'C', '2024-10-20 01:25:10'),
(4, 'D', '2024-10-20 01:25:14'),
(5, 'E', '2024-10-20 01:25:17');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tema`
--

CREATE TABLE `tema` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `tema`
--

INSERT INTO `tema` (`id`, `nome`, `descricao`, `criado_em`) VALUES
(1, 'Festa Junina', 'arraia', '2024-10-21 01:18:31'),
(2, 'Dia das crianÃ§as', 'festa comemorativa', '2024-10-21 02:52:21'),
(3, 'Halloween', 'doces ou travessuras', '2024-10-24 00:46:11'),
(8, 'ConsciÃªncia negra', '   respeito nÃ£o tem cor tem consciÃªncia', '2024-10-27 22:18:27'),
(11, 'Dia das mulheres', 'dia delas', '2024-11-18 02:57:40');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipo`
--

CREATE TABLE `tipo` (
  `id` int(11) NOT NULL,
  `descricao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `buffet`
--
ALTER TABLE `buffet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tipo` (`tipo_id`);

--
-- Índices de tabela `buffet_tipo`
--
ALTER TABLE `buffet_tipo`
  ADD PRIMARY KEY (`buffet_id`,`tipo_id`),
  ADD KEY `tipo_id` (`tipo_id`);

--
-- Índices de tabela `cargos`
--
ALTER TABLE `cargos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `escolaridades`
--
ALTER TABLE `escolaridades`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tema_id` (`tema_id`),
  ADD KEY `fk_objetivo_id` (`objetivo_id`),
  ADD KEY `fk_buffet_id` (`buffet_id`);

--
-- Índices de tabela `evento_buffet`
--
ALTER TABLE `evento_buffet`
  ADD PRIMARY KEY (`evento_id`,`buffet_id`),
  ADD KEY `buffet_id` (`buffet_id`);

--
-- Índices de tabela `evento_objetivo`
--
ALTER TABLE `evento_objetivo`
  ADD PRIMARY KEY (`evento_id`,`objetivo_id`),
  ADD KEY `objetivo_id` (`objetivo_id`);

--
-- Índices de tabela `evento_staff`
--
ALTER TABLE `evento_staff`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `evento_tema`
--
ALTER TABLE `evento_tema`
  ADD PRIMARY KEY (`evento_id`,`tema_id`),
  ADD KEY `tema_id` (`tema_id`);

--
-- Índices de tabela `faixa_etaria`
--
ALTER TABLE `faixa_etaria`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `instituicao`
--
ALTER TABLE `instituicao`
  ADD PRIMARY KEY (`idInstituicao`);

--
-- Índices de tabela `objetivo`
--
ALTER TABLE `objetivo`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `problemas_evento`
--
ALTER TABLE `problemas_evento`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `produtor`
--
ALTER TABLE `produtor`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `staffs_eventos`
--
ALTER TABLE `staffs_eventos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cargo_id` (`cargo_id`);

--
-- Índices de tabela `staff_cargo`
--
ALTER TABLE `staff_cargo`
  ADD PRIMARY KEY (`staff_id`,`cargo_id`),
  ADD KEY `cargo_id` (`cargo_id`);

--
-- Índices de tabela `status_social`
--
ALTER TABLE `status_social`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tema`
--
ALTER TABLE `tema`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tipo`
--
ALTER TABLE `tipo`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `buffet`
--
ALTER TABLE `buffet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `cargos`
--
ALTER TABLE `cargos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `escolaridades`
--
ALTER TABLE `escolaridades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de tabela `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `evento_staff`
--
ALTER TABLE `evento_staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `faixa_etaria`
--
ALTER TABLE `faixa_etaria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT de tabela `objetivo`
--
ALTER TABLE `objetivo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `problemas_evento`
--
ALTER TABLE `problemas_evento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produtor`
--
ALTER TABLE `produtor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de tabela `staffs_eventos`
--
ALTER TABLE `staffs_eventos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `status_social`
--
ALTER TABLE `status_social`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `tema`
--
ALTER TABLE `tema`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `buffet`
--
ALTER TABLE `buffet`
  ADD CONSTRAINT `fk_tipo` FOREIGN KEY (`tipo_id`) REFERENCES `tipo` (`id`);

--
-- Restrições para tabelas `buffet_tipo`
--
ALTER TABLE `buffet_tipo`
  ADD CONSTRAINT `buffet_tipo_ibfk_1` FOREIGN KEY (`buffet_id`) REFERENCES `buffet` (`id`),
  ADD CONSTRAINT `buffet_tipo_ibfk_2` FOREIGN KEY (`tipo_id`) REFERENCES `tipo` (`id`);

--
-- Restrições para tabelas `eventos`
--
ALTER TABLE `eventos`
  ADD CONSTRAINT `fk_buffet_id` FOREIGN KEY (`buffet_id`) REFERENCES `buffet` (`id`),
  ADD CONSTRAINT `fk_objetivo_id` FOREIGN KEY (`objetivo_id`) REFERENCES `objetivo` (`id`),
  ADD CONSTRAINT `fk_tema_id` FOREIGN KEY (`tema_id`) REFERENCES `tema` (`id`);

--
-- Restrições para tabelas `evento_buffet`
--
ALTER TABLE `evento_buffet`
  ADD CONSTRAINT `evento_buffet_ibfk_1` FOREIGN KEY (`evento_id`) REFERENCES `eventos` (`id`),
  ADD CONSTRAINT `evento_buffet_ibfk_2` FOREIGN KEY (`buffet_id`) REFERENCES `buffet` (`id`);

--
-- Restrições para tabelas `evento_objetivo`
--
ALTER TABLE `evento_objetivo`
  ADD CONSTRAINT `evento_objetivo_ibfk_1` FOREIGN KEY (`evento_id`) REFERENCES `eventos` (`id`),
  ADD CONSTRAINT `evento_objetivo_ibfk_2` FOREIGN KEY (`objetivo_id`) REFERENCES `objetivo` (`id`);

--
-- Restrições para tabelas `evento_tema`
--
ALTER TABLE `evento_tema`
  ADD CONSTRAINT `evento_tema_ibfk_1` FOREIGN KEY (`evento_id`) REFERENCES `eventos` (`id`),
  ADD CONSTRAINT `evento_tema_ibfk_2` FOREIGN KEY (`tema_id`) REFERENCES `tema` (`id`);

--
-- Restrições para tabelas `staffs_eventos`
--
ALTER TABLE `staffs_eventos`
  ADD CONSTRAINT `staffs_eventos_ibfk_1` FOREIGN KEY (`cargo_id`) REFERENCES `cargos` (`id`);

--
-- Restrições para tabelas `staff_cargo`
--
ALTER TABLE `staff_cargo`
  ADD CONSTRAINT `staff_cargo_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staffs_eventos` (`id`),
  ADD CONSTRAINT `staff_cargo_ibfk_2` FOREIGN KEY (`cargo_id`) REFERENCES `cargos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
