/*
SQLyog Community v13.2.0 (64 bit)
MySQL - 10.4.32-MariaDB : Database - encuesta
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`encuesta` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `encuesta`;

/*Table structure for table `colonias` */

DROP TABLE IF EXISTS `colonias`;

CREATE TABLE `colonias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL DEFAULT '',
  `ciudad` varchar(50) DEFAULT NULL,
  `municipio` int(6) DEFAULT NULL,
  `asentamiento` varchar(25) DEFAULT NULL,
  `codigo_postal` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_municipio` (`municipio`) USING BTREE,
  KEY `index_nombre` (`nombre`) USING BTREE,
  KEY `index_asentamiento` (`asentamiento`) USING BTREE,
  KEY `index_codigo_postal` (`codigo_postal`) USING BTREE,
  KEY `index_ciudad` (`ciudad`) USING BTREE,
  CONSTRAINT `fk_municipio` FOREIGN KEY (`municipio`) REFERENCES `municipios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1607710190 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `dependencias_preguntas` */

DROP TABLE IF EXISTS `dependencias_preguntas`;

CREATE TABLE `dependencias_preguntas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pregunta_id` bigint(20) unsigned NOT NULL,
  `depende_de_pregunta_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_dependencia_pregunta` (`pregunta_id`),
  KEY `fk_depende_de_pregunta` (`depende_de_pregunta_id`),
  CONSTRAINT `fk_depende_de_pregunta` FOREIGN KEY (`depende_de_pregunta_id`) REFERENCES `preguntas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_dependencia_pregunta` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `estados` */

DROP TABLE IF EXISTS `estados`;

CREATE TABLE `estados` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `pais` int(3) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `index_pais` (`pais`) USING BTREE,
  CONSTRAINT `fk_pais` FOREIGN KEY (`pais`) REFERENCES `paises` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `estudiante_grupo` */

DROP TABLE IF EXISTS `estudiante_grupo`;

CREATE TABLE `estudiante_grupo` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `estudiante_id` bigint(20) unsigned DEFAULT NULL,
  `grupo_id` int(11) DEFAULT NULL,
  `tutor_id` int(11) NOT NULL,
  `periodo_id` bigint(20) unsigned DEFAULT NULL,
  `activo` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `desactivated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `estudiante_id` (`estudiante_id`),
  KEY `grupo_id` (`grupo_id`),
  KEY `periodo_id` (`periodo_id`),
  CONSTRAINT `estudiante_grupo_ibfk_1` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON DELETE NO ACTION,
  CONSTRAINT `estudiante_grupo_ibfk_2` FOREIGN KEY (`grupo_id`) REFERENCES `t_grupos` (`id`) ON DELETE NO ACTION,
  CONSTRAINT `estudiante_grupo_ibfk_3` FOREIGN KEY (`periodo_id`) REFERENCES `periodos_escolar` (`id`) ON DELETE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `estudiante_respuesta` */

DROP TABLE IF EXISTS `estudiante_respuesta`;

CREATE TABLE `estudiante_respuesta` (
  `id` bigint(200) unsigned NOT NULL AUTO_INCREMENT,
  `estudiante_id` bigint(200) unsigned NOT NULL,
  `pregunta_id` bigint(200) unsigned NOT NULL,
  `opcion_id` bigint(200) unsigned DEFAULT NULL,
  `seccion_id` bigint(200) unsigned DEFAULT NULL,
  `respuesta_texto` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `estudiante_respuesta_usuario_id_foreign` (`estudiante_id`),
  KEY `estudiante_respuesta_pregunta_id_foreign` (`pregunta_id`),
  KEY `estudiante_respuesta_opcion_id_foreign` (`opcion_id`),
  KEY `estudiante_respuesta_seccion_id_foreign` (`seccion_id`),
  CONSTRAINT `estudiante_respuesta_estudiante_id_foreign` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`),
  CONSTRAINT `estudiante_respuesta_opcion_id_foreign` FOREIGN KEY (`opcion_id`) REFERENCES `opciones_respuesta` (`id`),
  CONSTRAINT `estudiante_respuesta_pregunta_id_foreign` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`),
  CONSTRAINT `estudiante_respuesta_seccion_id_foreign` FOREIGN KEY (`seccion_id`) REFERENCES `secciones` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2863 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `estudiantes` */

DROP TABLE IF EXISTS `estudiantes`;

CREATE TABLE `estudiantes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint(20) unsigned DEFAULT NULL,
  `matricula` text DEFAULT NULL,
  `telefono` text DEFAULT NULL,
  `genero` int(11) DEFAULT NULL,
  `activo` int(11) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `grupos_v` (`genero`),
  CONSTRAINT `estudiantes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `grupo_tutor` */

DROP TABLE IF EXISTS `grupo_tutor`;

CREATE TABLE `grupo_tutor` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `grupo_id` int(11) DEFAULT NULL,
  `tutor_id` bigint(20) unsigned DEFAULT NULL,
  `periodo_id` bigint(20) unsigned DEFAULT NULL,
  `activo` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `desactivated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tutor_id` (`tutor_id`),
  KEY `grupo_id` (`grupo_id`),
  KEY `periodo_id` (`periodo_id`),
  CONSTRAINT `grupo_tutor_ibfk_1` FOREIGN KEY (`tutor_id`) REFERENCES `tutores` (`id`) ON DELETE NO ACTION,
  CONSTRAINT `grupo_tutor_ibfk_2` FOREIGN KEY (`grupo_id`) REFERENCES `t_grupos` (`id`) ON DELETE NO ACTION,
  CONSTRAINT `grupo_tutor_ibfk_3` FOREIGN KEY (`periodo_id`) REFERENCES `periodos_escolar` (`id`) ON DELETE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `gruposv` */

DROP TABLE IF EXISTS `gruposv`;

CREATE TABLE `gruposv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombregv` text DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `prioridad` int(11) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `links` */

DROP TABLE IF EXISTS `links`;

CREATE TABLE `links` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `token` text DEFAULT NULL,
  `activo` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `municipios` */

DROP TABLE IF EXISTS `municipios`;

CREATE TABLE `municipios` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `estado` int(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `index_estado` (`estado`) USING BTREE,
  CONSTRAINT `fk_estado` FOREIGN KEY (`estado`) REFERENCES `estados` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32059 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `opciones_respuesta` */

DROP TABLE IF EXISTS `opciones_respuesta`;

CREATE TABLE `opciones_respuesta` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pregunta_id` bigint(20) unsigned NOT NULL,
  `seccion_id` bigint(20) unsigned NOT NULL,
  `opcion1` text NOT NULL,
  `opcion2` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `opciones_respuesta_pregunta_id_foreign` (`pregunta_id`),
  KEY `opciones_respuesta_seccion_id_foreign` (`seccion_id`),
  CONSTRAINT `opciones_respuesta_pregunta_id_foreign` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `opciones_respuesta_seccion_id_foreign` FOREIGN KEY (`seccion_id`) REFERENCES `secciones` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=362 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `paises` */

DROP TABLE IF EXISTS `paises`;

CREATE TABLE `paises` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `periodos_escolar` */

DROP TABLE IF EXISTS `periodos_escolar`;

CREATE TABLE `periodos_escolar` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(50) DEFAULT NULL,
  `inicio` date DEFAULT NULL,
  `fin` date DEFAULT NULL,
  `activo` int(11) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `preguntas` */

DROP TABLE IF EXISTS `preguntas`;

CREATE TABLE `preguntas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pregunta` text NOT NULL,
  `depende_p` text DEFAULT NULL,
  `tipo` varchar(255) NOT NULL,
  `seccion_id` varchar(255) NOT NULL,
  `activo` tinyint(1) NOT NULL,
  `ayuda` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `preguntas_gruposv` */

DROP TABLE IF EXISTS `preguntas_gruposv`;

CREATE TABLE `preguntas_gruposv` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pregunta_id` bigint(20) unsigned NOT NULL,
  `grupov_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pregunta_id` (`pregunta_id`),
  KEY `grupov_id` (`grupov_id`),
  CONSTRAINT `preguntas_gruposv_ibfk_1` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `preguntas_gruposv_ibfk_2` FOREIGN KEY (`grupov_id`) REFERENCES `gruposv` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `programa_edu` */

DROP TABLE IF EXISTS `programa_edu`;

CREATE TABLE `programa_edu` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `grado` varchar(20) DEFAULT NULL,
  `nombre` text DEFAULT NULL,
  `clave` varchar(10) DEFAULT NULL,
  `activo` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `respuestas` */

DROP TABLE IF EXISTS `respuestas`;

CREATE TABLE `respuestas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pregunta_id` varchar(50) NOT NULL,
  `estudiante_id` bigint(20) unsigned NOT NULL,
  `respuesta` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `estudiante_id` (`estudiante_id`),
  CONSTRAINT `respuestas_ibfk_1` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=53074 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `secciones` */

DROP TABLE IF EXISTS `secciones`;

CREATE TABLE `secciones` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `t_grupos` */

DROP TABLE IF EXISTS `t_grupos`;

CREATE TABLE `t_grupos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` text DEFAULT NULL,
  `programa_e` bigint(20) unsigned DEFAULT NULL,
  `nomenclatura` varchar(15) DEFAULT NULL,
  `activo` int(11) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `programa_e` (`programa_e`),
  CONSTRAINT `t_grupos_ibfk_1` FOREIGN KEY (`programa_e`) REFERENCES `programa_edu` (`id`) ON DELETE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `tutores` */

DROP TABLE IF EXISTS `tutores`;

CREATE TABLE `tutores` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint(20) unsigned DEFAULT NULL,
  `clave_sp` text DEFAULT NULL,
  `telefono` text DEFAULT NULL,
  `activo` int(11) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `tutores_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `usuarios` */

DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `apellido_paterno` varchar(50) NOT NULL,
  `apellido_materno` varchar(50) NOT NULL,
  `email` text NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `pass` text NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `rol_id` bigint(20) unsigned NOT NULL,
  `activo` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`) USING HASH,
  KEY `users_rol_id_foreign` (`rol_id`),
  CONSTRAINT `users_rol_id_foreign` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
