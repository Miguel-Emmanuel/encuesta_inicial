/*
SQLyog Community v13.2.0 (64 bit)
MySQL - 10.4.32-MariaDB : Database - encuesta001
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`encuesta001` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `encuesta001`;

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
) ENGINE=InnoDB AUTO_INCREMENT=323 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `preguntas`;

CREATE TABLE `preguntas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pregunta` text NOT NULL,
  `depende_p` text DEFAULT NULL,
  `tipo` varchar(255) NOT NULL,
  `seccion_id` varchar(255) NOT NULL,
  `activo` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `programa_edu`;

CREATE TABLE `programa_edu` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `grado` varchar(20) DEFAULT NULL,
  `nombre` text DEFAULT NULL,
  `clave` varchar(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `respuestas`;

CREATE TABLE `respuestas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `respuesta` text NOT NULL,
  `pregunta_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `respuesta_fk_pregunta_foreign` (`pregunta_id`),
  CONSTRAINT `respuesta_fk_pregunta_foreign` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `respuestas` */

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

/*Data for the table `roles` */

insert  into `roles`(`id`,`nombre`,`descripcion`,`created_at`,`updated_at`) values 
(1,'Director de Carrera','Hola',NULL,NULL),
(2,'PTC/Tutor','Hola',NULL,NULL),
(3,'Estudiante','Hola',NULL,NULL),
(4,'Psicologia','Hola',NULL,NULL);

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

/*Data for the table `secciones` */

insert  into `secciones`(`id`,`nombre`,`descripcion`,`created_at`,`updated_at`) values 
(1,'general','Datos Generales',NULL,NULL),
(2,'emergencia','Datos de Emergencia',NULL,NULL),
(3,'emergencia2','Datos de Emergencia(2)',NULL,NULL),
(4,'socioeconomico','Aspectos Socioeconomicos',NULL,NULL),
(5,'salud','Condiciones de Salud',NULL,NULL),
(6,'escolar','Rendimiento Escolar',NULL,NULL),
(7,'habitos','Habitos de Estudio y Practicas Escolares',NULL,NULL),
(8,'expectativas','Expectativas Educativas y Ocupacionales',NULL,NULL);

/*Table structure for table `usuario_respuesta` */

DROP TABLE IF EXISTS `usuario_respuesta`;

CREATE TABLE `usuario_respuesta` (
  `id` bigint(200) unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint(200) unsigned NOT NULL,
  `pregunta_id` bigint(200) unsigned NOT NULL,
  `opcion_id` bigint(200) unsigned DEFAULT NULL,
  `seccion_id` bigint(200) unsigned DEFAULT NULL,
  `respuesta_texto` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_respuesta_usuario_id_foreign` (`usuario_id`),
  KEY `usuario_respuesta_pregunta_id_foreign` (`pregunta_id`),
  KEY `usuario_respuesta_opcion_id_foreign` (`opcion_id`),
  KEY `usuario_respuesta_seccion_id_foreign` (`seccion_id`),
  CONSTRAINT `usuario_respuesta_opcion_id_foreign` FOREIGN KEY (`opcion_id`) REFERENCES `opciones_respuesta` (`id`),
  CONSTRAINT `usuario_respuesta_pregunta_id_foreign` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`),
  CONSTRAINT `usuario_respuesta_seccion_id_foreign` FOREIGN KEY (`seccion_id`) REFERENCES `secciones` (`id`),
  CONSTRAINT `usuario_respuesta_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=665 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `usuario_respuesta` */

/*Table structure for table `usuarios` */

DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `apellido_paterno` varchar(50) NOT NULL,
  `apellido_materno` varchar(50) NOT NULL,
  `matricula` int(11) DEFAULT NULL,
  `carrera` varchar(255) DEFAULT NULL,
  `email` text NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `pass` text NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `rol_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`) USING HASH,
  KEY `users_rol_id_foreign` (`rol_id`),
  CONSTRAINT `users_rol_id_foreign` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `usuarios` */

insert  into `usuarios`(`id`,`nombre`,`apellido_paterno`,`apellido_materno`,`matricula`,`carrera`,`email`,`email_verified_at`,`pass`,`remember_token`,`rol_id`,`created_at`,`updated_at`) values 
(1,'Eduh','Olvera','Aldama',NULL,NULL,'director@gmail.com',NULL,'1234',NULL,1,NULL,NULL),
(2,'Eduh','Olvera','Aldama',NULL,NULL,'ptc@gmail.com',NULL,'1234',NULL,2,NULL,NULL),
(3,'Eduh','Olvera','Aldama',NULL,NULL,'estudiante@gmail.com',NULL,'1234',NULL,3,NULL,NULL),
(4,'Eduh','Olvera','Aldama',NULL,NULL,'psicologia@gmail.com',NULL,'1234',NULL,4,NULL,NULL),
(5,'Eduh','Olvera','Aldama',NULL,NULL,'eduholvera@gmail.com',NULL,'1234',NULL,2,NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
