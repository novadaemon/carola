/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50611
Source Host           : localhost:3306
Source Database       : ftpindexer

Target Server Type    : MYSQL
Target Server Version : 50611
File Encoding         : 65001

Date: 2014-06-15 20:19:07
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `ftps`
-- ----------------------------
DROP TABLE IF EXISTS `ftps`;
CREATE TABLE `ftps` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) NOT NULL,
  `direccion_ip` varchar(15) NOT NULL,
  `activo` bit(1) NOT NULL DEFAULT b'1',
  `indizado` bit(1) NOT NULL DEFAULT b'0',
  `user` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'No indizado',
  `progress` varchar(400) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=latin1 COMMENT='Tabla que almacena los ftp que deben escanearse';

-- ----------------------------
-- Records of ftps
-- ----------------------------

-- ----------------------------
-- Table structure for `ftptree`
-- ----------------------------
DROP TABLE IF EXISTS `ftptree`;
CREATE TABLE `ftptree` (
  `id` bigint(12) unsigned NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(255) NOT NULL,
  `Fecha` date NOT NULL,
  `Tamanho` bigint(20) NOT NULL,
  `profundidad` int(2) NOT NULL,
  `directorio` bit(1) NOT NULL,
  `idftp` int(8) NOT NULL,
  `path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37353 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of ftptree
-- ----------------------------

-- ----------------------------
-- Table structure for `global`
-- ----------------------------
DROP TABLE IF EXISTS `global`;
CREATE TABLE `global` (
  `id` int(2) NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of global
-- ----------------------------
INSERT INTO `global` VALUES ('0', 'status', 'active');

-- ----------------------------
-- Table structure for `tasks`
-- ----------------------------
DROP TABLE IF EXISTS `tasks`;
CREATE TABLE `tasks` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `idftprelacionado` int(6) NOT NULL,
  `Estado` varchar(255) NOT NULL,
  `Progreso` varchar(400) NOT NULL,
  `abortar` bit(1) NOT NULL DEFAULT b'0',
  `descripcion` varchar(255) NOT NULL,
  PRIMARY KEY (`id`,`idftprelacionado`)
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tasks
-- ----------------------------
