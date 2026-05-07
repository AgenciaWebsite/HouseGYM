-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-05-2026 a las 01:19:39
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `housegym`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admins`
--

CREATE TABLE `admins` (
  `id_admin` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalogo`
--

CREATE TABLE `catalogo` (
  `id` int(11) NOT NULL,
  `tipo` enum('ejercicio','maquina') NOT NULL,
  `id_ejercicio` int(11) DEFAULT NULL,
  `id_maquina` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dietas`
--

CREATE TABLE `dietas` (
  `id_dieta` int(11) NOT NULL,
  `tipo` enum('Hipercalórica','Normocalórica','Hipocalórica') NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `dietas`
--

INSERT INTO `dietas` (`id_dieta`, `tipo`, `descripcion`) VALUES
(1, 'Hipercalórica', NULL),
(2, 'Normocalórica', NULL),
(3, 'Hipocalórica', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ejercicios`
--

CREATE TABLE `ejercicios` (
  `id_ejercicio` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `foto_url` varchar(255) DEFAULT NULL,
  `id_grupo` int(11) NOT NULL,
  `id_maquina` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Disparadores `ejercicios`
--
DELIMITER $$
CREATE TRIGGER `trg_ejercicio_catalogo` AFTER INSERT ON `ejercicios` FOR EACH ROW BEGIN
    INSERT IGNORE INTO catalogo (tipo, id_ejercicio)
    VALUES ('ejercicio', NEW.id_ejercicio);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupo_muscular`
--

CREATE TABLE `grupo_muscular` (
  `id_grupo` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `grupo_muscular`
--

INSERT INTO `grupo_muscular` (`id_grupo`, `nombre`) VALUES
(6, 'Abdomen'),
(4, 'Brazo'),
(5, 'Cardio'),
(3, 'Espalda'),
(2, 'Pecho'),
(1, 'Pierna');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `maquinas`
--

CREATE TABLE `maquinas` (
  `id_maquina` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `foto_url` varchar(255) DEFAULT NULL,
  `piso` enum('2','3','4') NOT NULL,
  `id_grupo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Disparadores `maquinas`
--
DELIMITER $$
CREATE TRIGGER `trg_maquina_catalogo` AFTER INSERT ON `maquinas` FOR EACH ROW BEGIN
    INSERT IGNORE INTO catalogo (tipo, id_maquina)
    VALUES ('maquina', NEW.id_maquina);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rutina_global`
--

CREATE TABLE `rutina_global` (
  `id_rutina_global` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `activa` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rutina_global_detalle`
--

CREATE TABLE `rutina_global_detalle` (
  `id` int(11) NOT NULL,
  `id_rutina_global` int(11) NOT NULL,
  `id_ejercicio` int(11) NOT NULL,
  `id_maquina` int(11) DEFAULT NULL,
  `series` tinyint(4) NOT NULL CHECK (`series` > 0),
  `repeticiones` tinyint(4) NOT NULL CHECK (`repeticiones` > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rutina_personalizada`
--

CREATE TABLE `rutina_personalizada` (
  `id_rutina_pers` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `activa` tinyint(1) NOT NULL DEFAULT 0,
  `id_dieta` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rutina_personalizada_detalle`
--

CREATE TABLE `rutina_personalizada_detalle` (
  `id` int(11) NOT NULL,
  `id_rutina_pers` int(11) NOT NULL,
  `id_ejercicio` int(11) NOT NULL,
  `id_maquina` int(11) DEFAULT NULL,
  `series` tinyint(4) NOT NULL CHECK (`series` > 0),
  `repeticiones` tinyint(4) NOT NULL CHECK (`repeticiones` > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `cedula` int(20) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `plan_personalizado` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `v_maquinas`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `v_maquinas` (
`id_maquina` int(11)
,`nombre` varchar(100)
,`descripcion` text
,`foto_url` varchar(255)
,`piso` enum('2','3','4')
,`grupo_muscular` varchar(50)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `v_rutina_global`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `v_rutina_global` (
`rutina` varchar(100)
,`ejercicio` varchar(100)
,`foto_url` varchar(255)
,`descripcion` text
,`grupo_muscular` varchar(50)
,`maquina` varchar(100)
,`piso_maquina` enum('2','3','4')
,`series` tinyint(4)
,`repeticiones` tinyint(4)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `v_rutina_personalizada`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `v_rutina_personalizada` (
`cedula` int(20)
,`rutina` varchar(100)
,`ejercicio` varchar(100)
,`foto_url` varchar(255)
,`descripcion` text
,`grupo_muscular` varchar(50)
,`maquina` varchar(100)
,`series` tinyint(4)
,`repeticiones` tinyint(4)
,`dieta` enum('Hipercalórica','Normocalórica','Hipocalórica')
,`dieta_descripcion` text
);

-- --------------------------------------------------------

--
-- Estructura para la vista `v_maquinas`
--
DROP TABLE IF EXISTS `v_maquinas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_maquinas`  AS SELECT `m`.`id_maquina` AS `id_maquina`, `m`.`nombre` AS `nombre`, `m`.`descripcion` AS `descripcion`, `m`.`foto_url` AS `foto_url`, `m`.`piso` AS `piso`, `g`.`nombre` AS `grupo_muscular` FROM (`maquinas` `m` join `grupo_muscular` `g` on(`g`.`id_grupo` = `m`.`id_grupo`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `v_rutina_global`
--
DROP TABLE IF EXISTS `v_rutina_global`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_rutina_global`  AS SELECT `rg`.`nombre` AS `rutina`, `e`.`nombre` AS `ejercicio`, `e`.`foto_url` AS `foto_url`, `e`.`descripcion` AS `descripcion`, `g`.`nombre` AS `grupo_muscular`, `m`.`nombre` AS `maquina`, `m`.`piso` AS `piso_maquina`, `rgd`.`series` AS `series`, `rgd`.`repeticiones` AS `repeticiones` FROM ((((`rutina_global_detalle` `rgd` join `rutina_global` `rg` on(`rg`.`id_rutina_global` = `rgd`.`id_rutina_global`)) join `ejercicios` `e` on(`e`.`id_ejercicio` = `rgd`.`id_ejercicio`)) join `grupo_muscular` `g` on(`g`.`id_grupo` = `e`.`id_grupo`)) left join `maquinas` `m` on(`m`.`id_maquina` = `rgd`.`id_maquina`)) WHERE `rg`.`activa` = 1 ;

-- --------------------------------------------------------

--
-- Estructura para la vista `v_rutina_personalizada`
--
DROP TABLE IF EXISTS `v_rutina_personalizada`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_rutina_personalizada`  AS SELECT `u`.`cedula` AS `cedula`, `rp`.`nombre` AS `rutina`, `e`.`nombre` AS `ejercicio`, `e`.`foto_url` AS `foto_url`, `e`.`descripcion` AS `descripcion`, `g`.`nombre` AS `grupo_muscular`, `m`.`nombre` AS `maquina`, `rpd`.`series` AS `series`, `rpd`.`repeticiones` AS `repeticiones`, `d`.`tipo` AS `dieta`, `d`.`descripcion` AS `dieta_descripcion` FROM ((((((`rutina_personalizada_detalle` `rpd` join `rutina_personalizada` `rp` on(`rp`.`id_rutina_pers` = `rpd`.`id_rutina_pers`)) join `usuarios` `u` on(`u`.`id_usuario` = `rp`.`id_usuario`)) join `ejercicios` `e` on(`e`.`id_ejercicio` = `rpd`.`id_ejercicio`)) join `grupo_muscular` `g` on(`g`.`id_grupo` = `e`.`id_grupo`)) left join `maquinas` `m` on(`m`.`id_maquina` = `rpd`.`id_maquina`)) left join `dietas` `d` on(`d`.`id_dieta` = `rp`.`id_dieta`)) WHERE `rp`.`activa` = 1 AND `u`.`plan_personalizado` = 1 ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indices de la tabla `catalogo`
--
ALTER TABLE `catalogo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ejercicio` (`id_ejercicio`),
  ADD KEY `id_maquina` (`id_maquina`);

--
-- Indices de la tabla `dietas`
--
ALTER TABLE `dietas`
  ADD PRIMARY KEY (`id_dieta`),
  ADD UNIQUE KEY `tipo` (`tipo`);

--
-- Indices de la tabla `ejercicios`
--
ALTER TABLE `ejercicios`
  ADD PRIMARY KEY (`id_ejercicio`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD KEY `id_grupo` (`id_grupo`),
  ADD KEY `id_maquina` (`id_maquina`);

--
-- Indices de la tabla `grupo_muscular`
--
ALTER TABLE `grupo_muscular`
  ADD PRIMARY KEY (`id_grupo`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `maquinas`
--
ALTER TABLE `maquinas`
  ADD PRIMARY KEY (`id_maquina`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD KEY `id_grupo` (`id_grupo`);

--
-- Indices de la tabla `rutina_global`
--
ALTER TABLE `rutina_global`
  ADD PRIMARY KEY (`id_rutina_global`);

--
-- Indices de la tabla `rutina_global_detalle`
--
ALTER TABLE `rutina_global_detalle`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_rg_ejercicio` (`id_rutina_global`,`id_ejercicio`),
  ADD KEY `id_ejercicio` (`id_ejercicio`),
  ADD KEY `id_maquina` (`id_maquina`);

--
-- Indices de la tabla `rutina_personalizada`
--
ALTER TABLE `rutina_personalizada`
  ADD PRIMARY KEY (`id_rutina_pers`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_dieta` (`id_dieta`);

--
-- Indices de la tabla `rutina_personalizada_detalle`
--
ALTER TABLE `rutina_personalizada_detalle`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_rp_ejercicio` (`id_rutina_pers`,`id_ejercicio`),
  ADD KEY `id_ejercicio` (`id_ejercicio`),
  ADD KEY `id_maquina` (`id_maquina`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `cedula` (`cedula`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admins`
--
ALTER TABLE `admins`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `catalogo`
--
ALTER TABLE `catalogo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `dietas`
--
ALTER TABLE `dietas`
  MODIFY `id_dieta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `ejercicios`
--
ALTER TABLE `ejercicios`
  MODIFY `id_ejercicio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `grupo_muscular`
--
ALTER TABLE `grupo_muscular`
  MODIFY `id_grupo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `maquinas`
--
ALTER TABLE `maquinas`
  MODIFY `id_maquina` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rutina_global`
--
ALTER TABLE `rutina_global`
  MODIFY `id_rutina_global` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rutina_global_detalle`
--
ALTER TABLE `rutina_global_detalle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rutina_personalizada`
--
ALTER TABLE `rutina_personalizada`
  MODIFY `id_rutina_pers` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rutina_personalizada_detalle`
--
ALTER TABLE `rutina_personalizada_detalle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `catalogo`
--
ALTER TABLE `catalogo`
  ADD CONSTRAINT `catalogo_ibfk_1` FOREIGN KEY (`id_ejercicio`) REFERENCES `ejercicios` (`id_ejercicio`) ON DELETE CASCADE,
  ADD CONSTRAINT `catalogo_ibfk_2` FOREIGN KEY (`id_maquina`) REFERENCES `maquinas` (`id_maquina`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ejercicios`
--
ALTER TABLE `ejercicios`
  ADD CONSTRAINT `ejercicios_ibfk_1` FOREIGN KEY (`id_grupo`) REFERENCES `grupo_muscular` (`id_grupo`),
  ADD CONSTRAINT `ejercicios_ibfk_2` FOREIGN KEY (`id_maquina`) REFERENCES `maquinas` (`id_maquina`) ON DELETE SET NULL;

--
-- Filtros para la tabla `maquinas`
--
ALTER TABLE `maquinas`
  ADD CONSTRAINT `maquinas_ibfk_1` FOREIGN KEY (`id_grupo`) REFERENCES `grupo_muscular` (`id_grupo`);

--
-- Filtros para la tabla `rutina_global_detalle`
--
ALTER TABLE `rutina_global_detalle`
  ADD CONSTRAINT `rutina_global_detalle_ibfk_1` FOREIGN KEY (`id_rutina_global`) REFERENCES `rutina_global` (`id_rutina_global`) ON DELETE CASCADE,
  ADD CONSTRAINT `rutina_global_detalle_ibfk_2` FOREIGN KEY (`id_ejercicio`) REFERENCES `ejercicios` (`id_ejercicio`),
  ADD CONSTRAINT `rutina_global_detalle_ibfk_3` FOREIGN KEY (`id_maquina`) REFERENCES `maquinas` (`id_maquina`) ON DELETE SET NULL;

--
-- Filtros para la tabla `rutina_personalizada`
--
ALTER TABLE `rutina_personalizada`
  ADD CONSTRAINT `rutina_personalizada_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `rutina_personalizada_ibfk_2` FOREIGN KEY (`id_dieta`) REFERENCES `dietas` (`id_dieta`) ON DELETE SET NULL;

--
-- Filtros para la tabla `rutina_personalizada_detalle`
--
ALTER TABLE `rutina_personalizada_detalle`
  ADD CONSTRAINT `rutina_personalizada_detalle_ibfk_1` FOREIGN KEY (`id_rutina_pers`) REFERENCES `rutina_personalizada` (`id_rutina_pers`) ON DELETE CASCADE,
  ADD CONSTRAINT `rutina_personalizada_detalle_ibfk_2` FOREIGN KEY (`id_ejercicio`) REFERENCES `ejercicios` (`id_ejercicio`),
  ADD CONSTRAINT `rutina_personalizada_detalle_ibfk_3` FOREIGN KEY (`id_maquina`) REFERENCES `maquinas` (`id_maquina`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
