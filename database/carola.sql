-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 09-01-2015 a las 23:47:49
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
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `message` varchar(400) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ip_unique` (`direccion_ip`) COMMENT 'no duplicar los ftps',
  KEY `i1` (`id`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Tabla que almacena los ftp que deben escanearse' AUTO_INCREMENT=113 ;

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
  `path` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `ext` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idi` (`id`) USING BTREE,
  KEY `myfk` (`idftp`),
  FULLTEXT KEY `text` (`Nombre`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Disparadores `ftptree`
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ftptree`
--
ALTER TABLE `ftptree`
  ADD CONSTRAINT `myfk` FOREIGN KEY (`idftp`) REFERENCES `ftps` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
