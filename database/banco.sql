-- Universidade Abissal: cria o banco, as tabelas e dados de exemplo.
-- Rode uma vez: mysql -u root -p < database/banco.sql   (ou importe pelo phpMyAdmin)
SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS universidade_abissal
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE universidade_abissal;

DROP TABLE IF EXISTS alunos;
DROP TABLE IF EXISTS cursos;

CREATE TABLE cursos (
    id                INT AUTO_INCREMENT PRIMARY KEY,
    nome              VARCHAR(100) NOT NULL UNIQUE,
    departamento      VARCHAR(40)  NOT NULL,
    duracao_semestres INT          NOT NULL,
    vagas             INT          NOT NULL,
    descricao         TEXT,
    criado_em         TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE alunos (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    nome      VARCHAR(100) NOT NULL,
    email     VARCHAR(120) NOT NULL UNIQUE,
    especie   VARCHAR(30)  NOT NULL,
    idade     INT          NOT NULL,
    curso_id  INT          NOT NULL,
    criado_em TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    -- RESTRICT: um curso com alunos matriculados não pode ser excluído
    CONSTRAINT fk_alunos_curso FOREIGN KEY (curso_id) REFERENCES cursos (id) ON DELETE RESTRICT
);

INSERT INTO cursos (nome, departamento, duracao_semestres, vagas, descricao) VALUES
('Bioluminescência Aplicada', 'Zona Abissal', 8, 40, 'Produza sua própria luz e ilumine os 4.000 metros de escuridão do campus.'),
('Engenharia de Correntes Marítimas', 'Fossas Oceânicas', 10, 30, 'Cálculo de correntes, pressão a 11 mil metros e pontes de coral.'),
('Arquitetura de Recifes', 'Recifes de Coral', 8, 35, 'Projeto de moradias sustentáveis para cardumes de até 10 mil peixes.'),
('Tinta de Polvo e Artes Visuais', 'Recifes de Coral', 6, 25, 'Pintura, caligrafia e camuflagem artística com oito tentáculos.'),
('Navegação por Sonar', 'Zona Abissal', 8, 30, 'Ecolocalização, cartografia submarina e comunicação de longa distância.'),
('Relações Exteriores com a Superfície', 'Superfície', 6, 20, 'Diplomacia com navios, mergulhadores e gaivotas.');

INSERT INTO alunos (nome, email, especie, idade, curso_id, criado_em) VALUES
('Octávio Tentáculos', 'octavio@abissal.edu', 'Polvo', 19, 4, '2026-02-01 08:00:00'),
('Lúmen Lanterna', 'lumen@abissal.edu', 'Peixe-lanterna', 21, 1, '2026-02-01 09:00:00'),
('Marina Coralina', 'marina@abissal.edu', 'Cavalo-marinho', 20, 3, '2026-02-02 10:00:00'),
('Bruno Barbatana', 'bruno@abissal.edu', 'Tubarão', 23, 2, '2026-02-03 11:00:00'),
('Gelatina Transparente', 'gelatina@abissal.edu', 'Água-viva', 18, 1, '2026-02-04 12:00:00'),
('Baltazar Jubarte', 'baltazar@abissal.edu', 'Baleia', 34, 6, '2026-02-05 13:00:00'),
('Clara Pinça', 'clara@abissal.edu', 'Caranguejo', 22, 2, '2026-02-06 14:00:00'),
('Lula Gigantesca', 'lula@abissal.edu', 'Lula-gigante', 27, 5, '2026-02-07 15:00:00'),
('Tinta Nanquim', 'tinta@abissal.edu', 'Polvo', 20, 4, '2026-02-08 16:00:00'),
('Abel Abissal', 'abel@abissal.edu', 'Peixe-lanterna', 25, 5, '2026-02-09 08:30:00'),
('Coral Ramalho', 'coral@abissal.edu', 'Cavalo-marinho', 19, 3, '2026-02-10 09:30:00'),
('Tubarina Martelo', 'tubarina@abissal.edu', 'Tubarão', 24, 2, '2026-02-11 10:30:00'),
('Medusa Brilhante', 'medusa@abissal.edu', 'Água-viva', 21, 1, '2026-02-12 11:30:00'),
('Orca Diplomata', 'orca@abissal.edu', 'Baleia', 30, 6, '2026-02-13 12:30:00'),
('Siri Saltitante', 'siri@abissal.edu', 'Caranguejo', 18, 3, '2026-02-14 13:30:00'),
('Kraken Júnior', 'kraken@abissal.edu', 'Lula-gigante', 29, 5, '2026-02-15 14:30:00'),
('Polvira Oito', 'polvira@abissal.edu', 'Polvo', 22, 4, '2026-03-01 08:00:00'),
('Faísca Profunda', 'faisca@abissal.edu', 'Peixe-lanterna', 20, 1, '2026-03-02 09:00:00'),
('Hipocampo Veloz', 'hipocampo@abissal.edu', 'Cavalo-marinho', 23, 6, '2026-03-03 10:00:00'),
('Tita Tintureira', 'tita@abissal.edu', 'Tubarão', 26, 5, '2026-03-04 11:00:00'),
('Água Marinha', 'aguamarinha@abissal.edu', 'Água-viva', 19, 4, '2026-03-05 12:00:00'),
('Azul Cachalote', 'cachalote@abissal.edu', 'Baleia', 41, 2, '2026-03-06 13:00:00'),
('Ermitão Silva', 'ermitao@abissal.edu', 'Caranguejo', 21, 3, '2026-03-07 14:00:00'),
('Abissínia Tentacular', 'abissinia@abissal.edu', 'Lula-gigante', 24, 1, '2026-03-08 15:00:00');
