-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-11-2022 a las 21:50:16
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
('6rq5v', '.\\fotoComandas\\\\fotoMesa90000.jpg', 'Entregado', '2022-11-22', '23:46:08pm', '01:24:53am', 90000),
('73o5z', NULL, 'Entregado', '2022-11-18', '01:58:01am', NULL, 30000),
('7gkax', NULL, 'En preparacion', '2022-11-22', '21:41:37pm', NULL, 12999),
('7z4e6', NULL, 'Entregado', '2022-11-18', '02:00:45am', NULL, 30000),
('c8vvp', '.\\fotoComandas\\\\fotoMesa40000.jpg', 'En preparacion', '2022-11-18', '03:08:45am', NULL, 40000),
('erpdd', '.\\fotoComandas\\\\fotoMesa10000.jpg', 'Entregado', '2022-11-26', '17:09:14pm', '17:19:06pm', 10000),
('fb4ty', NULL, 'Entregado', '2022-11-29', '21:05:17pm', '21:31:23pm', 30000),
('h0fhz', '.\\fotoComandas\\\\fotoMesa40000.jpg', 'En preparacion', '2022-11-18', '03:09:38am', NULL, 40000),
('njn5c', '.\\fotoComandas\\\\fotoMesa90000.jpg', 'Entregado', '2022-11-24', '22:58:51pm', '23:15:01pm', 90000),
('oejki', '.\\fotoComandas\\\\fotoMesa30000.jpg', 'En preparacion', '2022-11-28', '16:02:12pm', '21:26:28pm', 30000),
('oqozf', '.\\fotoComandas\\\\fotoMesa40000.jpg', 'En preparacion', '2022-11-29', '21:06:10pm', NULL, 40000),
('u2bzj', '.\\fotoComandas\\\\fotoMesa40000.jpg', 'En preparacion', '2022-11-18', '03:10:02am', NULL, 40000),
('wsfx6', '.\\fotoComandas\\\\fotoMesa90000.jpg', 'Entregado', '2022-11-23', '01:17:29am', '01:31:51am', 90000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuestas`
--

CREATE TABLE `encuestas` (
  `idComanda` varchar(11) NOT NULL,
  `puntuacionMesa` int(11) NOT NULL,
  `puntuacionRestaurante` int(11) NOT NULL,
  `puntuacionMozo` int(11) NOT NULL,
  `puntuacionCocinero` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `comentarios` varchar(66) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `encuestas`
--

INSERT INTO `encuestas` (`idComanda`, `puntuacionMesa`, `puntuacionRestaurante`, `puntuacionMozo`, `puntuacionCocinero`, `fecha`, `comentarios`) VALUES
('erpdd', 10, 5, 10, 5, '2022-11-26', 'Excelente servicio'),
('fb4ty', 10, 10, 5, 9, '2022-11-29', 'Prueba pre entrega'),
('oejki', 10, 10, 10, 10, '2022-11-28', 'Muy buena comida');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `numeroFactura` int(11) NOT NULL,
  `idComanda` varchar(11) NOT NULL,
  `idMesa` int(11) DEFAULT NULL,
  `fecha` date NOT NULL,
  `monto` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `facturas`
--

INSERT INTO `facturas` (`numeroFactura`, `idComanda`, `idMesa`, `fecha`, `monto`) VALUES
(2, 'njn5c', 90000, '2022-11-25', 3065),
(3, 'njn5c', 90000, '2022-11-26', 4064),
(5, 'oejki', 30000, '2022-11-28', 2854),
(6, 'fb4ty', 30000, '2022-11-29', 2574);

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
(4, 'Hamburguesa completa', 1999, 'cocinero'),
(5, 'Milanesa', 980, 'cocinero'),
(6, 'Destornillador', 560, 'bartender'),
(7, 'Corona', 769, 'cervecero'),
(8, 'Sandwich milanesa', 1100, 'cocinero'),
(10, 'Chocotorta', 750, 'cocinero'),
(11, 'Gaseosa', 450, 'bartender'),
(12, 'Agua', 500, 'bartender'),
(13, 'Helado', 300, 'cocinero'),
(14, 'Papas Fritas', 799, 'cocinero'),
(15, 'Daikiri', 800, 'bartender'),
(16, 'milanesa a caballo', 2500, 'cocinero'),
(17, 'Corona 710', 670, 'cervecero'),
(18, 'Hamburguesa de garbanzo', 1799, 'cocinero'),
(19, 'Empanada de carne', 499, 'cocinero'),
(20, 'Empanada de JYQ', 450, 'cocinero');

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
(40000, 'Cliente esperando pedido', 1006),
(50000, 'cliente esperando mozo', 1006),
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
(121, 1010, '7gkax', 8, 12999, 'En preparacion', 25, NULL),
(122, 1005, 'njn5c', 3, 90000, 'Listo para servir', 10, '23:15:01pm'),
(123, 1011, 'njn5c', 4, 90000, 'Listo para servir', 45, '23:13:37pm'),
(124, 1011, 'njn5c', 10, 90000, 'Listo para servir', 25, '23:13:41pm'),
(125, 1001, 'njn5c', 12, 90000, 'Listo para servir', 15, '23:14:08pm'),
(126, 1001, 'njn5c', 6, 90000, 'Listo para servir', 15, '23:14:12pm'),
(127, 1010, 'erpdd', 16, 10000, 'Listo para servir', 30, '17:18:25pm'),
(128, 1010, 'erpdd', 18, 10000, 'Listo para servir', 30, '17:18:34pm'),
(129, 1011, 'erpdd', 18, 10000, 'Listo para servir', 30, '17:19:06pm'),
(130, 1005, 'erpdd', 17, 10000, 'Listo para servir', 5, '17:17:57pm'),
(131, 0, 'erpdd', 15, 10000, 'Pendiente', 0, NULL),
(132, 1001, 'oejki', 15, 30000, 'Listo para servir', 25, '16:38:51pm'),
(133, 1010, 'oejki', 18, 30000, 'Listo para servir', 45, '16:37:45pm'),
(134, 1005, 'oejki', 3, 30000, 'Listo para servir', 2, '16:37:21pm'),
(136, 1005, 'fb4ty', 3, 30000, 'Listo para servir', 2, '21:31:23pm'),
(137, 1005, 'fb4ty', 20, 30000, 'Listo para servir', 2, '21:31:00pm'),
(138, 1010, 'fb4ty', 8, 30000, 'Listo para servir', 3, '21:30:31pm'),
(139, 1005, 'fb4ty', 7, 30000, 'Listo para servir', 3, '21:30:52pm');

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
(1008, 'Jose', 'cervecero', '$2y$10$zXLDINBrTRI2crCDW0SrEuW6gdVahc2IEaz3CE3NOAjTOXMZ9qF1e', '2022-11-11', '2022-11-23', '18:34:51pm'),
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
-- Indices de la tabla `encuestas`
--
ALTER TABLE `encuestas`
  ADD PRIMARY KEY (`idComanda`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`numeroFactura`);

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
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `numeroFactura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `menu`
--
ALTER TABLE `menu`
  MODIFY `idProducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `idPendiente` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
