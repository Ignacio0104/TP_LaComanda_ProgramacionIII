-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-11-2022 a las 16:20:02
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tp_programacioniii`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE `menu` (
  `idProducto` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `precio` float NOT NULL,
  `tipo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `menu`
--

INSERT INTO `menu` (`idProducto`, `nombre`, `precio`, `tipo`) VALUES
(3, 'Quilmes 710', 255, 'cerveza'),
(4, 'Hamburguesa completa', 1000, 'comida'),
(5, 'Milanesa', 980, 'comida'),
(6, 'Destornillador', 560, 'trago');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `idMesa` int(11) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `legajoMozo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`idMesa`, `estado`, `legajoMozo`) VALUES
(10000, 'cerrado', 0),
(11999, 'cliente esperado pedido', 1006),
(12999, 'cerrado', 0),
(19999, 'cerrado', 0),
(20000, 'cerrado', 0),
(30000, 'cerrado', 0),
(40000, 'cerrado', 0),
(50000, 'cliente esperado pedido', 1009),
(60000, 'cerrado', 0),
(70000, 'cerrado', 0),
(80000, 'cerrado', 0),
(90000, 'cerrado', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `idComanda` int(11) NOT NULL,
  `URLImagen` varchar(8000) DEFAULT NULL,
  `estado` varchar(50) NOT NULL,
  `fechaAlta` date NOT NULL,
  `horaAlta` varchar(50) NOT NULL,
  `horaBaja` varchar(50) DEFAULT NULL,
  `idMesa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pendientes`
--

CREATE TABLE `pendientes` (
  `idPendiente` int(11) NOT NULL,
  `legajoEmpleado` int(11) NOT NULL,
  `idComanda` int(11) NOT NULL,
  `idPlato` int(11) NOT NULL,
  `idMesa` int(11) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `minutosDemora` int(11) NOT NULL,
  `horaFinalizacion` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajadores`
--

CREATE TABLE `trabajadores` (
  `legajo` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `perfilEmpleado` varchar(100) NOT NULL,
  `clave` varchar(300) NOT NULL,
  `fechaAlta` date NOT NULL,
  `fechaBaja` date NOT NULL,
  `horaAlta` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `trabajadores`
--

INSERT INTO `trabajadores` (`legajo`, `nombre`, `perfilEmpleado`, `clave`, `fechaAlta`, `fechaBaja`, `horaAlta`) VALUES
(1000, 'Ignacio', 'socio', '$2y$10$UuXbIon.qnAGq32dd.TyOe17Jcqr0GNkzXJ8zjWgdK9neNCadKdHe', '2022-11-11', '0000-00-00', '16:38:19pm'),
(1001, 'Santiago', 'bartender', '$2y$10$aaRflLja1ukTJN/skade9eoPT51RhE2TJGBX6vdz2cQ/oT9fI8tvy', '2022-11-11', '0000-00-00', '16:46:49pm'),
(1002, 'Pedro', 'socio', '$2y$10$7moZSGrEz1D/ppxR5qaCZOCRx1FH9hgV4A6o45MFnfcyK6x.vrRN2', '2022-11-11', '0000-00-00', '16:38:43pm'),
(1005, 'Jim', 'cervecero', '$2y$10$I/brGaQr7if.U/KacCZH9.tKhEhUGutivSDzve/xXm.cXMHexlwli', '2022-11-11', '0000-00-00', '16:47:04pm'),
(1006, 'Eric', 'mozo', '$2y$10$jC9FI83bXLKM5dlFi1Z/OOEXzwLXHZiFj9v.KZC7nKu7BvE7Cnr4.', '2022-11-11', '0000-00-00', '16:47:22pm'),
(1008, 'Jose', 'cervecero', '$2y$10$zXLDINBrTRI2crCDW0SrEuW6gdVahc2IEaz3CE3NOAjTOXMZ9qF1e', '2022-11-11', '0000-00-00', '18:34:51pm'),
(1009, 'Maximiliano', 'mozo', '$2y$10$PqxbmxLNdAGEz2EZ2NSCVenChAOKW3djjIpw5dyRDkSa8VH7TAmn6', '2022-11-14', '0000-00-00', '15:23:34pm');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`idProducto`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`idMesa`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`idComanda`);

--
-- Indices de la tabla `pendientes`
--
ALTER TABLE `pendientes`
  ADD PRIMARY KEY (`idPendiente`),
  ADD KEY `legajoEmpleado` (`legajoEmpleado`),
  ADD KEY `idComanda` (`idComanda`);

--
-- Indices de la tabla `trabajadores`
--
ALTER TABLE `trabajadores`
  ADD PRIMARY KEY (`legajo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `menu`
--
ALTER TABLE `menu`
  MODIFY `idProducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `pendientes`
--
ALTER TABLE `pendientes`
  ADD CONSTRAINT `pendientes_ibfk_1` FOREIGN KEY (`legajoEmpleado`) REFERENCES `trabajadores` (`legajo`),
  ADD CONSTRAINT `pendientes_ibfk_2` FOREIGN KEY (`idComanda`) REFERENCES `pedidos` (`idComanda`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
