-- ============================================================
-- housegym.sql (corregido)
-- MariaDB / MySQL
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET NAMES utf8mb4;
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------
-- Base de datos
-- ------------------------------------------------------------
CREATE DATABASE IF NOT EXISTS `housegym`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;
USE `housegym`;

-- ------------------------------------------------------------
-- Eliminar vistas y tablas si existen (orden inverso de dependencia)
-- ------------------------------------------------------------
DROP VIEW  IF EXISTS `v_rutina_personalizada`;
DROP VIEW  IF EXISTS `v_rutina_global`;
DROP VIEW  IF EXISTS `v_maquinas`;

DROP TABLE IF EXISTS `rutina_personalizada_detalle`;
DROP TABLE IF EXISTS `rutina_global_detalle`;
DROP TABLE IF EXISTS `rutina_personalizada`;
DROP TABLE IF EXISTS `rutina_global`;
DROP TABLE IF EXISTS `catalogo`;
DROP TABLE IF EXISTS `ejercicios`;
DROP TABLE IF EXISTS `maquinas`;
DROP TABLE IF EXISTS `dietas`;
DROP TABLE IF EXISTS `grupo_muscular`;
DROP TABLE IF EXISTS `admins`;
DROP TABLE IF EXISTS `usuarios`;

-- ============================================================
-- TABLAS
-- ============================================================

-- ------------------------------------------------------------
-- admins
-- ------------------------------------------------------------
CREATE TABLE `admins` (
  `id_admin`   int(11)      NOT NULL AUTO_INCREMENT,
  `usuario`    varchar(50)  NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  PRIMARY KEY (`id_admin`),
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE=InnoDB
  AUTO_INCREMENT=2
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;

INSERT INTO `admins` (`id_admin`, `usuario`, `contrasena`) VALUES
(1, '123', '123');

-- ------------------------------------------------------------
-- grupo_muscular
-- ------------------------------------------------------------
CREATE TABLE `grupo_muscular` (
  `id_grupo` int(11)     NOT NULL AUTO_INCREMENT,
  `nombre`   varchar(50) NOT NULL,
  PRIMARY KEY (`id_grupo`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB
  AUTO_INCREMENT=7
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;

INSERT INTO `grupo_muscular` (`id_grupo`, `nombre`) VALUES
(6, 'Abdomen'),
(4, 'Brazo'),
(5, 'Cardio'),
(3, 'Espalda'),
(2, 'Pecho'),
(1, 'Pierna');

-- ------------------------------------------------------------
-- dietas
-- ------------------------------------------------------------
CREATE TABLE `dietas` (
  `id_dieta`    int(11) NOT NULL AUTO_INCREMENT,
  `tipo`        varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  PRIMARY KEY (`id_dieta`),
  UNIQUE KEY `tipo` (`tipo`)
) ENGINE=InnoDB
  AUTO_INCREMENT=4
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;

INSERT INTO `dietas` (`id_dieta`, `tipo`, `descripcion`) VALUES
(1, 'Hipercalórica',  NULL),
(2, 'Normocalórica',  NULL),
(3, 'Hipocalórica',   NULL);

-- ------------------------------------------------------------
-- maquinas
-- ------------------------------------------------------------
CREATE TABLE `maquinas` (
  `id_maquina`  int(11)      NOT NULL AUTO_INCREMENT,
  `nombre`      varchar(100) NOT NULL,
  `descripcion` text         DEFAULT NULL,
  `foto_url`    varchar(255) DEFAULT NULL,
  `ubicacion`   varchar(255) DEFAULT NULL,
  `id_grupo`    int(11)      DEFAULT NULL,
  `categoria`   varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_maquina`),
  UNIQUE KEY `nombre` (`nombre`),
  KEY `id_grupo` (`id_grupo`),
  CONSTRAINT `maquinas_ibfk_1`
    FOREIGN KEY (`id_grupo`) REFERENCES `grupo_muscular` (`id_grupo`)
) ENGINE=InnoDB
  AUTO_INCREMENT=12
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;

INSERT INTO `maquinas` (`id_maquina`, `nombre`, `descripcion`, `foto_url`, `ubicacion`, `id_grupo`, `categoria`) VALUES
(7,  'Sentadilla', 'Rack de sentadilla',              'uploads/maq_69fd375207c1e5.50973346.jpg', 'Piso 3', NULL, 'Pierna'),
(8,  'Predicador', 'Predicador para biceps',           'uploads/maq_69fd379dca08b9.49716260.jpg', 'Piso 2', NULL, 'Biceps'),
(9,  'Hacka',      'Hacka con discos',                 'uploads/maq_69fd37d8e0edb7.62851769.jpg', 'Piso 3', NULL, 'Pierna'),
(10, 'Prensa',     'Prensa con discos',                'uploads/maq_69fd381ce7b2a3.89537630.jpg', 'Piso 2', NULL, 'Pierna'),
(11, 'Press',      'Press de banca con discos y pesas','uploads/maq_69fd3845586467.58865749.jpg', 'Piso 4', NULL, 'Pecho');

-- ------------------------------------------------------------
-- ejercicios
-- ------------------------------------------------------------
CREATE TABLE `ejercicios` (
  `id_ejercicio` int(11)      NOT NULL AUTO_INCREMENT,
  `nombre`       varchar(100) NOT NULL,
  `descripcion`  text         DEFAULT NULL,
  `foto_url`     varchar(255) DEFAULT NULL,
  `id_grupo`     int(11)      NOT NULL,
  `id_maquina`   int(11)      DEFAULT NULL,
  `imagen_url`   varchar(255) GENERATED ALWAYS AS (`foto_url`) VIRTUAL,
  PRIMARY KEY (`id_ejercicio`),
  UNIQUE KEY `nombre` (`nombre`),
  KEY `id_grupo`   (`id_grupo`),
  KEY `id_maquina` (`id_maquina`),
  CONSTRAINT `ejercicios_ibfk_1`
    FOREIGN KEY (`id_grupo`)   REFERENCES `grupo_muscular` (`id_grupo`),
  CONSTRAINT `ejercicios_ibfk_2`
    FOREIGN KEY (`id_maquina`) REFERENCES `maquinas` (`id_maquina`) ON DELETE SET NULL
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;

-- (sin datos de ejercicios en el dump original)

-- ------------------------------------------------------------
-- catalogo
-- ------------------------------------------------------------
CREATE TABLE `catalogo` (
  `id`           int(11) NOT NULL AUTO_INCREMENT,
  `tipo`         enum('ejercicio','maquina') NOT NULL,
  `id_ejercicio` int(11) DEFAULT NULL,
  `id_maquina`   int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_ejercicio` (`id_ejercicio`),
  KEY `id_maquina`   (`id_maquina`),
  CONSTRAINT `catalogo_ibfk_1`
    FOREIGN KEY (`id_ejercicio`) REFERENCES `ejercicios` (`id_ejercicio`) ON DELETE CASCADE,
  CONSTRAINT `catalogo_ibfk_2`
    FOREIGN KEY (`id_maquina`)   REFERENCES `maquinas`   (`id_maquina`)   ON DELETE CASCADE
) ENGINE=InnoDB
  AUTO_INCREMENT=12
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;

INSERT INTO `catalogo` (`id`, `tipo`, `id_ejercicio`, `id_maquina`) VALUES
(7,  'maquina', NULL, 7),
(8,  'maquina', NULL, 8),
(9,  'maquina', NULL, 9),
(10, 'maquina', NULL, 10),
(11, 'maquina', NULL, 11);

-- ------------------------------------------------------------
-- usuarios
-- ------------------------------------------------------------
CREATE TABLE `usuarios` (
  `id_usuario`        int(11)      NOT NULL AUTO_INCREMENT,
  `nombre`            varchar(100) NOT NULL,
  `genero`            enum('Hombre','Mujer') NOT NULL DEFAULT 'Hombre',
  `cedula`            int(20)      NOT NULL,
  `contrasena`        varchar(255) NOT NULL,
  `activo`            tinyint(1)   NOT NULL DEFAULT 1,
  `plan_personalizado` tinyint(1)  NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `cedula` (`cedula`)
) ENGINE=InnoDB
  AUTO_INCREMENT=14
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `genero`, `cedula`, `contrasena`, `activo`, `plan_personalizado`) VALUES
(9,  'larva',  'Hombre', 1234,   '1234', 1, 1),
(10, 'dario',  'Hombre', 809,    '809',  1, 1),
(11, 'paco',   'Hombre', 1,      '123',  1, 0),
(12, 'miguel', 'Hombre', 756574, 'goty', 1, 1),
(13, 'carlos', 'Hombre', 5678,   'yeah', 1, 1);

-- ------------------------------------------------------------
-- rutina_global
-- ------------------------------------------------------------
CREATE TABLE `rutina_global` (
  `id_rutina_global` int(11)      NOT NULL AUTO_INCREMENT,
  `nombre`           varchar(100) NOT NULL,
  `genero`           enum('Hombre','Mujer') NOT NULL DEFAULT 'Hombre',
  `semana`           tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `descripcion`      text         DEFAULT NULL,
  `activa`           tinyint(1)   NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_rutina_global`),
  UNIQUE KEY `uq_rg_genero_semana` (`genero`, `semana`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;

-- (sin datos)

-- ------------------------------------------------------------
-- rutina_global_detalle
-- ------------------------------------------------------------
CREATE TABLE `rutina_global_detalle` (
  `id`               int(11)  NOT NULL AUTO_INCREMENT,
  `id_rutina_global` int(11)  NOT NULL,
  `dia`              tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `orden`            tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `id_ejercicio`     int(11)  NOT NULL,
  `id_maquina`       int(11)  DEFAULT NULL,
  `series`           tinyint(4) NOT NULL CHECK (`series` > 0),
  `repeticiones`     tinyint(4) NOT NULL CHECK (`repeticiones` > 0),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_rg_dia_ejercicio` (`id_rutina_global`, `dia`, `id_ejercicio`),
  KEY `id_ejercicio`     (`id_ejercicio`),
  KEY `id_maquina`       (`id_maquina`),
  KEY `id_rutina_global` (`id_rutina_global`),
  CONSTRAINT `rutina_global_detalle_ibfk_1`
    FOREIGN KEY (`id_rutina_global`) REFERENCES `rutina_global` (`id_rutina_global`) ON DELETE CASCADE,
  CONSTRAINT `rutina_global_detalle_ibfk_2`
    FOREIGN KEY (`id_ejercicio`)     REFERENCES `ejercicios`    (`id_ejercicio`),
  CONSTRAINT `rutina_global_detalle_ibfk_3`
    FOREIGN KEY (`id_maquina`)       REFERENCES `maquinas`      (`id_maquina`)       ON DELETE SET NULL
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;

-- (sin datos)

-- ------------------------------------------------------------
-- rutina_personalizada
-- ------------------------------------------------------------
CREATE TABLE `rutina_personalizada` (
  `id_rutina_pers` int(11)      NOT NULL AUTO_INCREMENT,
  `id_usuario`     int(11)      NOT NULL,
  `nombre`         varchar(100) NOT NULL,
  `activa`         tinyint(1)   NOT NULL DEFAULT 0,
  `id_dieta`       int(11)      DEFAULT NULL,
  PRIMARY KEY (`id_rutina_pers`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_dieta`   (`id_dieta`),
  CONSTRAINT `rutina_personalizada_ibfk_1`
    FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  CONSTRAINT `rutina_personalizada_ibfk_2`
    FOREIGN KEY (`id_dieta`)   REFERENCES `dietas`   (`id_dieta`)   ON DELETE SET NULL
) ENGINE=InnoDB
  AUTO_INCREMENT=18
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;

INSERT INTO `rutina_personalizada` (`id_rutina_pers`, `id_usuario`, `nombre`, `activa`, `id_dieta`) VALUES
(11, 9,  'Rutina personalizada', 1, 1),
(15, 10, 'Rutina personalizada', 1, NULL),
(16, 12, 'Rutina personalizada', 1, 3),
(17, 13, 'Rutina personalizada', 1, NULL);

-- ------------------------------------------------------------
-- rutina_personalizada_detalle
-- ------------------------------------------------------------
CREATE TABLE `rutina_personalizada_detalle` (
  `id`             int(11)  NOT NULL AUTO_INCREMENT,
  `id_rutina_pers` int(11)  NOT NULL,
  `dia`            tinyint(4) NOT NULL DEFAULT 1,
  `id_ejercicio`   int(11)  NOT NULL,
  `id_maquina`     int(11)  DEFAULT NULL,
  `series`         tinyint(4) NOT NULL CHECK (`series` > 0),
  `repeticiones`   tinyint(4) NOT NULL CHECK (`repeticiones` > 0),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_rp_ejercicio` (`id_rutina_pers`, `id_ejercicio`),
  KEY `id_ejercicio`   (`id_ejercicio`),
  KEY `id_maquina`     (`id_maquina`),
  CONSTRAINT `rutina_personalizada_detalle_ibfk_1`
    FOREIGN KEY (`id_rutina_pers`) REFERENCES `rutina_personalizada` (`id_rutina_pers`) ON DELETE CASCADE,
  CONSTRAINT `rutina_personalizada_detalle_ibfk_2`
    FOREIGN KEY (`id_ejercicio`)   REFERENCES `ejercicios`           (`id_ejercicio`),
  CONSTRAINT `rutina_personalizada_detalle_ibfk_3`
    FOREIGN KEY (`id_maquina`)     REFERENCES `maquinas`             (`id_maquina`)     ON DELETE SET NULL
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;

-- (sin datos)

-- ============================================================
-- TRIGGERS
-- (se crean después de los datos para no dispararse en el INSERT)
-- ============================================================

DELIMITER $$

CREATE TRIGGER `trg_ejercicio_catalogo`
AFTER INSERT ON `ejercicios`
FOR EACH ROW
BEGIN
  INSERT IGNORE INTO `catalogo` (`tipo`, `id_ejercicio`)
  VALUES ('ejercicio', NEW.id_ejercicio);
END$$

CREATE TRIGGER `trg_maquina_catalogo`
AFTER INSERT ON `maquinas`
FOR EACH ROW
BEGIN
  INSERT IGNORE INTO `catalogo` (`tipo`, `id_maquina`)
  VALUES ('maquina', NEW.id_maquina);
END$$

DELIMITER ;

-- ============================================================
-- VISTAS  (sin DEFINER, compatibles con cualquier usuario)
-- ============================================================

-- ------------------------------------------------------------
-- v_maquinas   (fix: era m.piso → ahora m.ubicacion)
-- ------------------------------------------------------------
CREATE OR REPLACE VIEW `v_maquinas` AS
SELECT
  m.`id_maquina`,
  m.`nombre`,
  m.`descripcion`,
  m.`foto_url`,
  m.`ubicacion`,
  g.`nombre` AS `grupo_muscular`
FROM `maquinas` m
JOIN `grupo_muscular` g ON g.`id_grupo` = m.`id_grupo`;

-- ------------------------------------------------------------
-- v_rutina_global   (fix: era m.piso → ahora m.ubicacion)
-- ------------------------------------------------------------
CREATE OR REPLACE VIEW `v_rutina_global` AS
SELECT
  rg.`nombre`       AS `rutina`,
  e.`nombre`        AS `ejercicio`,
  e.`foto_url`,
  e.`descripcion`,
  g.`nombre`        AS `grupo_muscular`,
  m.`nombre`        AS `maquina`,
  m.`ubicacion`     AS `ubicacion_maquina`,
  rgd.`series`,
  rgd.`repeticiones`
FROM `rutina_global_detalle` rgd
JOIN `rutina_global`   rg ON rg.`id_rutina_global` = rgd.`id_rutina_global`
JOIN `ejercicios`       e ON  e.`id_ejercicio`      = rgd.`id_ejercicio`
JOIN `grupo_muscular`   g ON  g.`id_grupo`           =  e.`id_grupo`
LEFT JOIN `maquinas`    m ON  m.`id_maquina`         = rgd.`id_maquina`
WHERE rg.`activa` = 1;

-- ------------------------------------------------------------
-- v_rutina_personalizada
-- ------------------------------------------------------------
CREATE OR REPLACE VIEW `v_rutina_personalizada` AS
SELECT
  u.`cedula`,
  u.`nombre`        AS `nombre_usuario`,
  rp.`nombre`       AS `rutina`,
  e.`nombre`        AS `ejercicio`,
  e.`foto_url`,
  e.`descripcion`,
  g.`nombre`        AS `grupo_muscular`,
  m.`nombre`        AS `maquina`,
  rpd.`series`,
  rpd.`repeticiones`,
  d.`tipo`          AS `dieta`,
  d.`descripcion`   AS `dieta_descripcion`
FROM `rutina_personalizada_detalle` rpd
JOIN  `rutina_personalizada` rp ON rp.`id_rutina_pers` = rpd.`id_rutina_pers`
JOIN  `usuarios`              u ON  u.`id_usuario`     =  rp.`id_usuario`
JOIN  `ejercicios`            e ON  e.`id_ejercicio`   = rpd.`id_ejercicio`
JOIN  `grupo_muscular`        g ON  g.`id_grupo`       =   e.`id_grupo`
LEFT JOIN `maquinas`          m ON  m.`id_maquina`     = rpd.`id_maquina`
LEFT JOIN `dietas`            d ON  d.`id_dieta`       =  rp.`id_dieta`
WHERE rp.`activa` = 1
  AND  u.`plan_personalizado` = 1;

-- ============================================================
SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
