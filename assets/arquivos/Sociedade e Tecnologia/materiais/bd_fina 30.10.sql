-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 30/10/2025 às 19:30
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
-- Banco de dados: `bd_fina`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `categoria`
--

CREATE TABLE `categoria` (
  `id` int(11) NOT NULL,
  `nome_categoria` varchar(255) NOT NULL,
  `fk_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `categoria`
--

INSERT INTO `categoria` (`id`, `nome_categoria`, `fk_user_id`) VALUES
(1, 'Alimentação', 1),
(2, 'Saúde', 1),
(3, 'Educação', 1),
(4, 'Habitação', 1),
(5, 'Transporte', 1),
(6, 'Higiene e Cuidados Pessoais', 1),
(7, 'Serviços Pessoais', 1),
(8, 'Vestuário', 1),
(9, 'Cultura e Recreação', 1),
(10, 'Investimentos', 1),
(11, 'Despesas Diversas', 1),
(14, 'teste', 1),
(16, 'teste', 2),
(17, 'ain', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `movimentacoes`
--

CREATE TABLE `movimentacoes` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `meta_id` int(11) NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `data` date NOT NULL,
  `hora` time NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `movimentacoes`
--

INSERT INTO `movimentacoes` (`id`, `usuario_id`, `meta_id`, `tipo`, `descricao`, `valor`, `data`, `hora`) VALUES
(3, 1, 3, 'aplicacao', 'Amortização', 0.00, '2025-07-14', '00:00:00'),
(4, 1, 3, 'aplicacao', 'teste', 0.00, '2025-07-14', '00:00:00'),
(8, 1, 3, 'aplicacao', 'teste valor float', 0.00, '2025-07-14', '00:00:00'),
(9, 1, 3, 'aplicacao', 'teste 2 float valor key', 30.00, '2025-07-14', '00:00:00'),
(10, 1, 3, 'aplicacao', 'teste 3 float', 20.00, '2025-07-14', '00:00:00'),
(11, 1, 3, 'aplicacao', 'teste hora', 1.00, '2025-07-14', '19:03:42'),
(12, 1, 3, 'aplicacao', 'teste hora', 10.00, '2025-07-14', '19:09:21'),
(13, 1, 3, 'aplicacao', 'teste hora', 2.00, '2025-07-14', '14:14:12'),
(14, 1, 3, 'aplicacao', 'decimal teste', 2.00, '2025-07-14', '14:19:12'),
(17, 1, 1, 'aplicacao', 'ganhei um vale e quis amortizar', 200.00, '2025-08-12', '01:00:02');

-- --------------------------------------------------------

--
-- Estrutura para tabela `perfil_financeiro`
--

CREATE TABLE `perfil_financeiro` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `descricao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `perfil_financeiro`
--

INSERT INTO `perfil_financeiro` (`id`, `nome`, `descricao`) VALUES
(1, 'Conservador', 'Busca segurança acima de tudo, com preservação do capital e aversão a perdas.'),
(2, 'Moderado', 'Aceita algum risco para obter retorno acima da média.'),
(3, 'Agressivo', 'Tolerância elevada a riscos e volatilidade, em busca de retornos mais expressivos.');

-- --------------------------------------------------------

--
-- Estrutura para tabela `planejador`
--

CREATE TABLE `planejador` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `razao` varchar(255) NOT NULL,
  `preco_meta` decimal(10,2) NOT NULL,
  `capital` decimal(10,2) NOT NULL,
  `quanto_tempo_quero_pagar` int(11) NOT NULL,
  `quanto_quero_pagar_mes` decimal(10,2) NOT NULL,
  `criado_em` date NOT NULL,
  `horario_criado` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `planejador`
--

INSERT INTO `planejador` (`id`, `usuario_id`, `razao`, `preco_meta`, `capital`, `quanto_tempo_quero_pagar`, `quanto_quero_pagar_mes`, `criado_em`, `horario_criado`) VALUES
(1, 1, 'Carro', 126000.00, 5200.00, 2000, 36.00, '2025-04-24', '05:48:22'),
(3, 1, 'Casa', 350000.00, 50339.00, 80, 1300.00, '2025-05-27', '16:42:04');

-- --------------------------------------------------------

--
-- Estrutura para tabela `planilhas`
--

CREATE TABLE `planilhas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `nome_arquivo` varchar(255) NOT NULL,
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pontuacoes`
--

CREATE TABLE `pontuacoes` (
  `id` int(11) NOT NULL,
  `pergunta` int(11) NOT NULL,
  `resposta` varchar(50) NOT NULL,
  `pontos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pontuacoes`
--

INSERT INTO `pontuacoes` (`id`, `pergunta`, `resposta`, `pontos`) VALUES
(1, 1, 'economizar', 1),
(2, 1, 'investir', 3),
(3, 1, 'gastar', 2),
(4, 1, 'pagarDividas', 1),
(5, 1, 'seguranca', 1),
(6, 2, 'conservador', 1),
(7, 2, 'moderado', 2),
(8, 2, 'arrojado', 3),
(9, 2, 'especulativo', 3),
(10, 2, 'naoInvisto', 1),
(11, 3, 'iniciante', 1),
(12, 3, 'intermediario', 2),
(13, 3, 'avancado', 3),
(14, 3, 'especialista', 3),
(15, 3, 'naoSei', 1),
(16, 4, 'evito', 1),
(17, 4, 'gerencio', 2),
(18, 4, 'aceito', 3),
(19, 4, 'naoMeImporto', 3),
(20, 4, 'naoTenho', 1),
(21, 5, 'planejo', 3),
(22, 5, 'naoPlanejo', 1),
(23, 5, 'dependo', 1),
(24, 5, 'naoMeImporto', 1),
(25, 5, 'jaAposentei', 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `reset_senha_codigo`
--

CREATE TABLE `reset_senha_codigo` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `code` varchar(6) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `reset_senha_codigo`
--

INSERT INTO `reset_senha_codigo` (`id`, `user_id`, `code`, `created_at`) VALUES
(1, 2, '456031', '2025-04-29 05:40:01');

-- --------------------------------------------------------

--
-- Estrutura para tabela `respostas_perfil`
--

CREATE TABLE `respostas_perfil` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `pergunta1` varchar(50) NOT NULL,
  `pergunta2` varchar(50) NOT NULL,
  `pergunta3` varchar(50) NOT NULL,
  `pergunta4` varchar(50) NOT NULL,
  `pergunta5` varchar(50) NOT NULL,
  `pontuacao_total` int(11) NOT NULL,
  `perfil` varchar(20) NOT NULL,
  `data_resposta` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `respostas_perfil`
--

INSERT INTO `respostas_perfil` (`id`, `usuario_id`, `pergunta1`, `pergunta2`, `pergunta3`, `pergunta4`, `pergunta5`, `pontuacao_total`, `perfil`, `data_resposta`) VALUES
(1, 1, 'economizar', 'conservador', 'iniciante', 'evito', 'naoPlanejo', 5, 'Conservador', '2025-08-12 22:07:21'),
(2, 1, 'economizar', 'moderado', 'iniciante', 'evito', 'dependo', 6, 'Conservador', '2025-08-12 22:14:45'),
(3, 1, 'investir', 'moderado', 'avancado', 'gerencio', 'naoMeImporto', 11, 'Agressivo', '2025-08-12 22:15:57'),
(4, 1, 'economizar', 'moderado', 'especialista', 'aceito', 'planejo', 12, 'Agressivo', '2025-08-12 22:21:42'),
(5, 1, 'economizar', 'arrojado', 'especialista', 'aceito', 'naoPlanejo', 11, 'Agressivo', '2025-08-12 22:23:16'),
(6, 1, 'economizar', 'moderado', 'iniciante', 'aceito', 'naoPlanejo', 8, 'Moderado', '2025-08-12 22:42:49');

-- --------------------------------------------------------

--
-- Estrutura para tabela `transacoes`
--

CREATE TABLE `transacoes` (
  `id` int(11) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `data` date NOT NULL,
  `tipo` tinyint(1) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `categoria_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `transacoes`
--

INSERT INTO `transacoes` (`id`, `descricao`, `valor`, `data`, `tipo`, `usuario_id`, `categoria_id`) VALUES
(1, 'Investimento Selic', -161.00, '2025-04-24', 2, 1, 10),
(3, 'Transação', 50.00, '2025-04-29', 1, 1, 10),
(4, 'teste toastify-js', 2.00, '2025-04-29', 1, 1, 11),
(5, 'camiseta ', -70.00, '2025-05-27', 2, 1, 8),
(6, 'a', 1.00, '2025-06-13', 1, 1, 9),
(7, 'teste paginação 1 ', 2.00, '2025-06-13', 1, 1, 6),
(8, 'teste paginação 2', 1.00, '2025-06-13', 1, 1, 8),
(9, 'teste paginação 3 ', 3.00, '2025-06-13', 1, 1, 11),
(10, 'teste paginação 4 ', 4.00, '2025-06-13', 1, 1, 11),
(11, 'teste paginação 5', -5.00, '2025-06-13', 2, 1, 2),
(12, 'teste paginação 6 ', -6.00, '2025-06-13', 2, 1, 1),
(13, 'teste paginação 7 ', -7.00, '2025-06-13', 2, 1, 2),
(14, 'teste paginação 8 ', -8.00, '2025-06-13', 2, 1, 7),
(15, 'teste paginação 9', -9.00, '2025-06-13', 2, 1, 4),
(16, 'teste paginação 9', -9.00, '2025-06-13', 2, 1, 3),
(17, 'teste paginação 10 ', 200.00, '2025-06-13', 1, 1, 8),
(18, 'teste centavos', 2.56, '2025-07-14', 1, 1, 1),
(19, 'celular', -2000.00, '2025-10-24', 2, 1, 11);

-- --------------------------------------------------------

--
-- Estrutura para tabela `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `perfil_financeiro` int(11) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `user`
--

INSERT INTO `user` (`id`, `nome`, `username`, `email`, `telefone`, `senha`, `foto`, `perfil_financeiro`, `criado_em`) VALUES
(1, 'Administrador', 'Adm', 'adm@adm.com', '(18) 99999-9999', '$2y$10$LCEXFZBpdvmiB4QvlckQw.Hybyz6Q3PZcsoJB/z8Nxkv3Ap.ZtmPO', 'foto_6809b33e65f585.77988032.jpeg', 3, '2025-04-24 03:09:14'),
(2, 'Artur da Silva Pereira', 'Artur', 'pereira.artur@hotmail.com', '(18) 99664-0751', '$2y$10$a4KojPt3dhbGeE78GePlBuirr1ZWgGHUsU34cVBZ7xHiUtOJKchdq', 'foto_680e82d1bb0256.59338862.jpeg', 0, '2025-04-27 19:17:37');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_id_user` (`fk_user_id`);

--
-- Índices de tabela `movimentacoes`
--
ALTER TABLE `movimentacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_movimentacoes_usuario` (`usuario_id`),
  ADD KEY `fk_movimentacoes_meta` (`meta_id`);

--
-- Índices de tabela `perfil_financeiro`
--
ALTER TABLE `perfil_financeiro`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `planejador`
--
ALTER TABLE `planejador`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `planilhas`
--
ALTER TABLE `planilhas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `pontuacoes`
--
ALTER TABLE `pontuacoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `reset_senha_codigo`
--
ALTER TABLE `reset_senha_codigo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Índices de tabela `respostas_perfil`
--
ALTER TABLE `respostas_perfil`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `transacoes`
--
ALTER TABLE `transacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Índices de tabela `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `movimentacoes`
--
ALTER TABLE `movimentacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `perfil_financeiro`
--
ALTER TABLE `perfil_financeiro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `planejador`
--
ALTER TABLE `planejador`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `planilhas`
--
ALTER TABLE `planilhas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pontuacoes`
--
ALTER TABLE `pontuacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de tabela `reset_senha_codigo`
--
ALTER TABLE `reset_senha_codigo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `respostas_perfil`
--
ALTER TABLE `respostas_perfil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `transacoes`
--
ALTER TABLE `transacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `categoria`
--
ALTER TABLE `categoria`
  ADD CONSTRAINT `fk_id_user` FOREIGN KEY (`fk_user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `movimentacoes`
--
ALTER TABLE `movimentacoes`
  ADD CONSTRAINT `fk_movimentacoes_meta` FOREIGN KEY (`meta_id`) REFERENCES `planejador` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_movimentacoes_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `planejador`
--
ALTER TABLE `planejador`
  ADD CONSTRAINT `planejador_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `planilhas`
--
ALTER TABLE `planilhas`
  ADD CONSTRAINT `planilhas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `reset_senha_codigo`
--
ALTER TABLE `reset_senha_codigo`
  ADD CONSTRAINT `reset_senha_codigo_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `respostas_perfil`
--
ALTER TABLE `respostas_perfil`
  ADD CONSTRAINT `respostas_perfil_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `user` (`id`);

--
-- Restrições para tabelas `transacoes`
--
ALTER TABLE `transacoes`
  ADD CONSTRAINT `transacoes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transacoes_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
