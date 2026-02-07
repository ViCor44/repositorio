-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 08-Fev-2026 às 00:30
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `parque_repositorio`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `comentarios`
--

CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL,
  `documento_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `texto` text NOT NULL,
  `criado_em` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `comentarios`
--

INSERT INTO `comentarios` (`id`, `documento_id`, `user_id`, `texto`, `criado_em`) VALUES
(1, 1, 1, 'Primeiro comentário', '2026-02-07 15:02:05');

-- --------------------------------------------------------

--
-- Estrutura da tabela `documentos`
--

CREATE TABLE `documentos` (
  `id` int(11) NOT NULL,
  `parque_id` int(11) NOT NULL,
  `zona_id` int(11) DEFAULT NULL,
  `edificio_id` int(11) DEFAULT NULL,
  `sala_id` int(11) DEFAULT NULL,
  `titulo` varchar(200) NOT NULL,
  `tipo` enum('eletrico','rede','fibra','hvac','cctv','plc','outro') NOT NULL,
  `estado` enum('ativo','em_revisao','obsoleto') DEFAULT 'ativo',
  `cmms_ref` varchar(100) DEFAULT NULL,
  `criado_em` datetime DEFAULT current_timestamp(),
  `criado_por` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `documentos`
--

INSERT INTO `documentos` (`id`, `parque_id`, `zona_id`, `edificio_id`, `sala_id`, `titulo`, `tipo`, `estado`, `cmms_ref`, `criado_em`, `criado_por`) VALUES
(1, 1, NULL, NULL, 13, 'Camaras IA Semáforos', 'rede', 'ativo', NULL, '2026-02-07 13:07:11', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `documento_relacoes`
--

CREATE TABLE `documento_relacoes` (
  `id` int(11) NOT NULL,
  `documento_origem_id` int(11) NOT NULL,
  `documento_destino_id` int(11) NOT NULL,
  `tipo_relacao` enum('alimenta','depende','relacionado','backup','redundancia') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `documento_tags`
--

CREATE TABLE `documento_tags` (
  `documento_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `documento_tags`
--

INSERT INTO `documento_tags` (`documento_id`, `tag_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `documento_versoes`
--

CREATE TABLE `documento_versoes` (
  `id` int(11) NOT NULL,
  `documento_id` int(11) NOT NULL,
  `versao` varchar(20) NOT NULL,
  `ficheiro_path` varchar(255) NOT NULL,
  `checksum` char(64) DEFAULT NULL,
  `formato` varchar(10) DEFAULT NULL,
  `tamanho` int(11) DEFAULT NULL,
  `changelog` text DEFAULT NULL,
  `criado_em` datetime DEFAULT current_timestamp(),
  `criado_por` int(11) NOT NULL,
  `aprovado_por` int(11) DEFAULT NULL,
  `aprovado_em` datetime DEFAULT NULL,
  `is_ativo` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `documento_versoes`
--

INSERT INTO `documento_versoes` (`id`, `documento_id`, `versao`, `ficheiro_path`, `checksum`, `formato`, `tamanho`, `changelog`, `criado_em`, `criado_por`, `aprovado_por`, `aprovado_em`, `is_ativo`) VALUES
(1, 1, '1.0', '31f6301d1b15d350fce594caebf2925b.pdf', NULL, 'pdf', 969351, NULL, '2026-02-07 13:07:11', 1, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `edificios`
--

CREATE TABLE `edificios` (
  `id` int(11) NOT NULL,
  `zona_id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `edificios`
--

INSERT INTO `edificios` (`id`, `zona_id`, `nome`) VALUES
(1, 4, 'Central Tecnica Principal'),
(5, 3, 'Complexo Piscinas'),
(6, 2, 'Torre dos Escorregas'),
(7, 6, 'Torre');

-- --------------------------------------------------------

--
-- Estrutura da tabela `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `acao` varchar(100) DEFAULT NULL,
  `entidade` varchar(50) DEFAULT NULL,
  `entidade_id` int(11) DEFAULT NULL,
  `data` datetime DEFAULT current_timestamp(),
  `ip` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `logs`
--

INSERT INTO `logs` (`id`, `user_id`, `acao`, `entidade`, `entidade_id`, `data`, `ip`) VALUES
(1, 1, 'logout', 'user', 1, '2026-02-07 14:53:46', '::1'),
(2, 1, 'login', 'user', 1, '2026-02-07 14:53:51', '::1'),
(3, 1, 'download', 'documento', 1, '2026-02-07 14:53:59', '::1'),
(4, 1, 'logout', 'user', 1, '2026-02-07 14:54:26', '::1'),
(5, 1, 'login', 'user', 1, '2026-02-07 15:01:28', '::1'),
(6, 1, 'comentario', 'documento', 1, '2026-02-07 15:02:05', '::1'),
(7, 1, 'logout', 'user', 1, '2026-02-07 15:02:17', '::1'),
(8, 1, 'login', 'user', 1, '2026-02-07 15:06:21', '::1'),
(9, 1, 'add_tag', 'documento', 1, '2026-02-07 15:22:45', '::1'),
(10, 1, 'logout', 'user', 1, '2026-02-07 15:38:07', '::1'),
(11, 1, 'login', 'user', 1, '2026-02-07 15:41:17', '::1'),
(12, 1, 'logout', 'user', 1, '2026-02-07 15:41:40', '::1'),
(13, 1, 'login', 'user', 1, '2026-02-07 15:49:09', '::1'),
(14, 1, 'logout', 'user', 1, '2026-02-07 18:44:45', '::1'),
(15, 1, 'login', 'user', 1, '2026-02-07 18:44:49', '::1'),
(16, 1, 'logout', 'user', 1, '2026-02-07 18:54:22', '::1'),
(17, 1, 'logout', 'user', 1, '2026-02-07 18:55:31', '::1'),
(18, 1, 'logout', 'user', 1, '2026-02-07 19:22:27', '::1'),
(19, 1, 'logout', 'user', 1, '2026-02-07 19:32:56', '::1'),
(20, 1, 'approve_user', 'user', 2, '2026-02-07 19:41:09', '::1'),
(21, 1, 'logout', 'user', 1, '2026-02-07 23:13:18', '::1'),
(22, 1, 'logout', 'user', 1, '2026-02-07 23:18:48', '::1');

-- --------------------------------------------------------

--
-- Estrutura da tabela `parques`
--

CREATE TABLE `parques` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `descricao` text DEFAULT NULL,
  `criado_em` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `parques`
--

INSERT INTO `parques` (`id`, `nome`, `descricao`, `criado_em`) VALUES
(1, 'Slide & Splash', 'Repositorio tecnico central do parque aquatico', '2026-02-06 22:02:48');

-- --------------------------------------------------------

--
-- Estrutura da tabela `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `roles`
--

INSERT INTO `roles` (`id`, `nome`) VALUES
(1, 'Administrador'),
(5, 'Auditor'),
(4, 'Consulta'),
(2, 'Engenheiro'),
(3, 'Tecnico');

-- --------------------------------------------------------

--
-- Estrutura da tabela `salas`
--

CREATE TABLE `salas` (
  `id` int(11) NOT NULL,
  `edificio_id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `tipo` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `salas`
--

INSERT INTO `salas` (`id`, `edificio_id`, `nome`, `tipo`) VALUES
(9, 1, 'Sala Quadros Eletricos', 'eletrico'),
(10, 1, 'Sala Bombas 1', 'bombagem'),
(11, 1, 'Sala Controlo PLC', 'automacao'),
(12, 1, 'Sala TI', 'datacenter'),
(13, 7, 'Torre', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `tags`
--

INSERT INTO `tags` (`id`, `nome`) VALUES
(1, 'Rede');

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `criado_em` datetime DEFAULT current_timestamp(),
  `twofa_enabled` tinyint(1) DEFAULT 0,
  `twofa_secret` varchar(64) DEFAULT NULL,
  `status` enum('pendente','ativo','bloqueado') DEFAULT 'pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `nome`, `email`, `password_hash`, `role_id`, `ativo`, `criado_em`, `twofa_enabled`, `twofa_secret`, `status`) VALUES
(1, 'Administrador Geral', 'admin@aquaworld.local', '$2y$10$qiiDl8eJ0S8wbJKdf9VKJe7eDWfGkodLfm9lAp.5thyrhnwCCjgJC', 1, 1, '2026-02-06 22:02:15', 0, NULL, 'ativo');

-- --------------------------------------------------------

--
-- Estrutura da tabela `zonas`
--

CREATE TABLE `zonas` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `zonas`
--

INSERT INTO `zonas` (`id`, `nome`) VALUES
(1, 'Zona Infantil'),
(2, 'Zona Radical'),
(3, 'Zona Piscinas'),
(4, 'Zona Tecnica Central'),
(6, 'Torre');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_coment_documento` (`documento_id`),
  ADD KEY `fk_coment_user` (`user_id`);

--
-- Índices para tabela `documentos`
--
ALTER TABLE `documentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_doc_parque` (`parque_id`),
  ADD KEY `fk_doc_zona` (`zona_id`),
  ADD KEY `fk_doc_edificio` (`edificio_id`),
  ADD KEY `fk_doc_sala` (`sala_id`);

--
-- Índices para tabela `documento_relacoes`
--
ALTER TABLE `documento_relacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_rel_origem` (`documento_origem_id`),
  ADD KEY `fk_rel_destino` (`documento_destino_id`);

--
-- Índices para tabela `documento_tags`
--
ALTER TABLE `documento_tags`
  ADD PRIMARY KEY (`documento_id`,`tag_id`),
  ADD KEY `fk_dt_tag` (`tag_id`);

--
-- Índices para tabela `documento_versoes`
--
ALTER TABLE `documento_versoes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `documento_id` (`documento_id`,`versao`);

--
-- Índices para tabela `edificios`
--
ALTER TABLE `edificios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_edificios_zona` (`zona_id`);

--
-- Índices para tabela `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_logs_user` (`user_id`),
  ADD KEY `idx_logs_entidade` (`entidade`,`entidade_id`);

--
-- Índices para tabela `parques`
--
ALTER TABLE `parques`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices para tabela `salas`
--
ALTER TABLE `salas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_salas_edificio` (`edificio_id`);

--
-- Índices para tabela `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_users_role` (`role_id`);

--
-- Índices para tabela `zonas`
--
ALTER TABLE `zonas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `documentos`
--
ALTER TABLE `documentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `documento_relacoes`
--
ALTER TABLE `documento_relacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `documento_versoes`
--
ALTER TABLE `documento_versoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `edificios`
--
ALTER TABLE `edificios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `parques`
--
ALTER TABLE `parques`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `salas`
--
ALTER TABLE `salas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `zonas`
--
ALTER TABLE `zonas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `fk_coment_documento` FOREIGN KEY (`documento_id`) REFERENCES `documentos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_coment_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `documentos`
--
ALTER TABLE `documentos`
  ADD CONSTRAINT `fk_doc_edificio` FOREIGN KEY (`edificio_id`) REFERENCES `edificios` (`id`),
  ADD CONSTRAINT `fk_doc_parque` FOREIGN KEY (`parque_id`) REFERENCES `parques` (`id`),
  ADD CONSTRAINT `fk_doc_sala` FOREIGN KEY (`sala_id`) REFERENCES `salas` (`id`),
  ADD CONSTRAINT `fk_doc_zona` FOREIGN KEY (`zona_id`) REFERENCES `zonas` (`id`);

--
-- Limitadores para a tabela `documento_relacoes`
--
ALTER TABLE `documento_relacoes`
  ADD CONSTRAINT `fk_rel_destino` FOREIGN KEY (`documento_destino_id`) REFERENCES `documentos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_rel_origem` FOREIGN KEY (`documento_origem_id`) REFERENCES `documentos` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `documento_tags`
--
ALTER TABLE `documento_tags`
  ADD CONSTRAINT `fk_dt_documento` FOREIGN KEY (`documento_id`) REFERENCES `documentos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_dt_tag` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `documento_versoes`
--
ALTER TABLE `documento_versoes`
  ADD CONSTRAINT `fk_versoes_documento` FOREIGN KEY (`documento_id`) REFERENCES `documentos` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `edificios`
--
ALTER TABLE `edificios`
  ADD CONSTRAINT `fk_edificios_zona` FOREIGN KEY (`zona_id`) REFERENCES `zonas` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `salas`
--
ALTER TABLE `salas`
  ADD CONSTRAINT `fk_salas_edificio` FOREIGN KEY (`edificio_id`) REFERENCES `edificios` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
