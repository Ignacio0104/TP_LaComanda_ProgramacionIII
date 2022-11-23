-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-11-2022 a las 00:48:58
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
-- Estructura de tabla para la tabla `comandas`
--

CREATE TABLE `comandas` (
  `idComanda` varchar(11) NOT NULL,
  `URLImagen` varchar(8000) DEFAULT NULL,
  `estado` varchar(50) NOT NULL,
  `fechaAlta` date NOT NULL,
  `horaAlta` varchar(50) NOT NULL,
  `horaBaja` varchar(50) DEFAULT NULL,
  `idMesa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `comandas`
--

INSERT INTO `comandas` (`idComanda`, `URLImagen`, `estado`, `fechaAlta`, `horaAlta`, `horaBaja`, `idMesa`) VALUES
('2hys0', '.\\fotoComandas\\\\fotoMesa40000.jpg', 'En preparacion', '2022-11-18', '03:14:59am', NULL, 40000),
('3n27k', '.\\fotoComandas\\\\fotoMesa30000.jpg', 'Entregado', '2022-11-18', '02:03:33am', NULL, 30000),
('6fwat', NULL, 'Entregado', '2022-11-18', '02:06:19am', '21:28:53pm', 30000),
('6grie', '.\\fotoComandas\\\\fotoMesa40000.jpg', 'En preparacion', '2022-11-18', '03:09:50am', NULL, 40000),
('6rq5v', '.\\fotoComandas\\\\fotoMesa90000.jpg', 'Entregado', '2022-11-22', '23:46:08pm', '23:52:48pm', 90000),
('73o5z', NULL, 'Entregado', '2022-11-18', '01:58:01am', NULL, 30000),
('7gkax', NULL, 'En preparacion', '2022-11-22', '21:41:37pm', NULL, 12999),
('7z4e6', NULL, 'Entregado', '2022-11-18', '02:00:45am', NULL, 30000),
('c8vvp', '.\\fotoComandas\\\\fotoMesa40000.jpg', 'En preparacion', '2022-11-18', '03:08:45am', NULL, 40000),
('h0fhz', '.\\fotoComandas\\\\fotoMesa40000.jpg', 'En preparacion', '2022-11-18', '03:09:38am', NULL, 40000),
('u2bzj', '.\\fotoComandas\\\\fotoMesa40000.jpg', 'En preparacion', '2022-11-18', '03:10:02am', NULL, 40000);

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
(3, 'Quilmes 710', 255, 'cervecero'),
(4, 'Hamburguesa completa', 1000, 'cocinero'),
(5, 'Milanesa', 980, 'cocinero'),
(6, 'Destornillador', 560, 'bartender'),
(7, 'Corona', 769, 'cervecero'),
(8, 'Sandwich milanesa', 1100, 'cocinero'),
(10, 'Chocotorta', 750, 'cocinero'),
(11, 'Gaseosa', 500, 'bartender'),
(12, 'Gaseosa', 500, 'bartender');

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
(11999, 'cerrado', 0),
(12999, 'Cliente esperando pedido', 1006),
(19999, 'cerrado', 0),
(20000, 'cerrado', 0),
(30000, 'cerrado', 0),
(40000, 'Cliente esperando pedido', 1009),
(50000, 'cliente esperando mozo', 1009),
(60000, 'cerrado', 0),
(70000, 'cerrado', 0),
(80000, 'cerrado', 0),
(90000, 'cerrado', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `idPendiente` int(15) NOT NULL,
  `legajoEmpleado` int(11) NOT NULL,
  `idComanda` varchar(11) NOT NULL,
  `idPlato` int(11) NOT NULL,
  `idMesa` int(11) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `minutosDemora` int(11) NOT NULL,
  `horaFinalizacion` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`idPendiente`, `legajoEmpleado`, `idComanda`, `idPlato`, `idMesa`, `estado`, `minutosDemora`, `horaFinalizacion`) VALUES
(100, 1010, 'u2bzj', 5, 20000, 'Listo para servir', 25, '16:31:05pm'),
(101, 1010, 'u2bzj', 8, 20000, 'En preparacion', 15, NULL),
(102, 1008, 'u2bzj', 7, 20000, 'En preparacion', 15, NULL),
(106, 1011, '6fwat', 10, 30000, 'Entregado', 15, '21:24:03pm'),
(107, 1010, '6fwat', 10, 30000, 'Entregado', 15, '21:23:10pm'),
(108, 1010, '2hys0', 10, 40000, 'En preparacion', 20, NULL),
(109, 1008, '2hys0', 3, 40000, 'En preparacion', 12, NULL),
(110, 1001, '2hys0', 11, 40000, 'En preparacion', 30, NULL),
(111, 1001, '2hys0', 12, 40000, 'En preparacion', 5, NULL),
(112, 1010, '6fwat', 10, 30000, 'Entregado', 15, '21:28:53pm'),
(113, 1008, '6rq5v', 3, 90000, 'Entregado', 2, '23:51:06pm'),
(114, 1011, '6rq5v', 5, 90000, 'Entregado', 25, '23:52:13pm'),
(115, 1008, '6rq5v', 7, 90000, 'Entregado', 2, '23:52:48pm'),
(116, 1011, '6rq5v', 10, 90000, 'Entregado', 40, '23:51:37pm'),
(117, 1010, '6rq5v', 4, 90000, 'Entregado', 30, '23:50:21pm');

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
(1009, 'Maximiliano', 'mozo', '$2y$10$PqxbmxLNdAGEz2EZ2NSCVenChAOKW3djjIpw5dyRDkSa8VH7TAmn6', '2022-11-14', '0000-00-00', '15:23:34pm'),
(1010, 'Brandon', 'cocinero', '$2y$10$dL66nq20U14NSwNI.l68eOiG.IwYvbl6DHZ9CnwAjjiKaWKhBF88C', '2022-11-14', '0000-00-00', '18:36:45pm'),
(1011, 'Barney', 'cocinero', '$2y$10$WoMa1VoY3XxI50TxVFSl9O0BI4Ve9B5AYmoxlnZ/X2nezS304yN52', '2022-11-18', '0000-00-00', '00:38:43am');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comandas`
--
ALTER TABLE `comandas`
  ADD PRIMARY KEY (`idComanda`);

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
  MODIFY `idProducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `idPendiente` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
