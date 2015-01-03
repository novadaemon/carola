-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 03-01-2015 a las 19:28:03
-- Versión del servidor: 5.6.12-log
-- Versión de PHP: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `carola`
--
CREATE DATABASE IF NOT EXISTS `carola` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `carola`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ftps`
--

CREATE TABLE IF NOT EXISTS `ftps` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) NOT NULL,
  `direccion_ip` varchar(15) NOT NULL,
  `activo` bit(1) NOT NULL DEFAULT b'1',
  `user` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'No indexado',
  `date_last_scan` date NOT NULL,
  `message` varchar(400) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ip_unique` (`direccion_ip`) COMMENT 'no duplicar los ftps',
  KEY `i1` (`id`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Tabla que almacena los ftp que deben escanearse' AUTO_INCREMENT=108 ;

--
-- Volcado de datos para la tabla `ftps`
--

INSERT INTO `ftps` (`id`, `descripcion`, `direccion_ip`, `activo`, `user`, `pass`, `status`, `date_last_scan`, `message`) VALUES
(1, 'gntk', '127.0.0.1', b'1', 'anonymous', 'anonymous@ftpindexer.cu', 'Parcialmente indexado', '2015-01-03', 'Parcialmente indexado'),
(67, 'Partagas', '192.168.128.2', b'1', 'anonymous', 'anonymous@ftpindexer.cu', 'Indizado', '2015-01-03', 'Error al conectarse al ftp'),
(69, 'Alamar', '192.168.176.2', b'1', 'anonymous', 'anonymous@ftpindexer.cu', 'Indizado', '2015-01-03', 'Error al conectarse al ftp'),
(70, 'WifiNet', '192.168.160.1', b'1', 'anonymous', 'anonymous@ftpindexer.cu', 'Indizado', '2015-01-03', 'Error al conectarse al ftp'),
(71, 'PowerNet', '192.168.154.1', b'1', 'anonymous', 'anonymous@ftpindexer.cu', 'Parcialmente indexado', '2015-01-03', 'Parcialmente indexado'),
(72, 'Alamar II', '192.168.176.3', b'1', 'anonymous', 'anonymous@ftpindexer.cu', 'Indizado', '2015-01-03', 'Error al conectarse al ftp'),
(73, '96.2', '192.168.96.2', b'1', 'anonymous', 'anonymous@ftpindexer.cu', 'Parcialmente indexado', '2015-01-03', 'Parcialmente indexado'),
(74, '136.1', '192.168.136.1', b'1', 'anonymous', 'anonymous@ftpindexer.cu', 'Indizado', '2015-01-03', 'Error al conectarse al ftp'),
(75, '140.1', '192.168.140.1', b'1', 'anonymous', 'anonymous@ftpindexer.cu', 'No indizado', '2015-01-03', 'Error al conectarse al ftp'),
(76, '152.1', '192.168.152.1', b'1', 'anonymous', 'anonymous@ftpindexer.cu', 'Indizado', '2015-01-03', 'Error al conectarse al ftp');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ftptree`
--

CREATE TABLE IF NOT EXISTS `ftptree` (
  `id` bigint(12) unsigned NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Fecha` date NOT NULL,
  `Tamanho` bigint(20) NOT NULL,
  `profundidad` int(2) NOT NULL,
  `idftp` int(8) NOT NULL,
  `path` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `ext` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idi` (`id`) USING BTREE,
  KEY `myfk` (`idftp`),
  FULLTEXT KEY `text` (`Nombre`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1747470 ;

--
-- Filtros para la tabla `ftptree`
--
ALTER TABLE `ftptree`
  ADD CONSTRAINT `myfk` FOREIGN KEY (`idftp`) REFERENCES `ftps` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
