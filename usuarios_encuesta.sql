/*
SQLyog Community v13.1.9 (64 bit)
MySQL - 10.4.28-MariaDB : Database - encuesta
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

/*Table structure for table `usuarios` */

DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `apellido_paterno` varchar(50) NOT NULL,
  `apellido_materno` varchar(50) NOT NULL,
  `i_genero` varchar(255) DEFAULT NULL,
  `matricula` int(11) DEFAULT NULL,
  `carrera` varchar(255) DEFAULT NULL,
  `grupos_v` varchar(255) DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `usuarios` */

insert  into `usuarios`(`id`,`nombre`,`apellido_paterno`,`apellido_materno`,`i_genero`,`matricula`,`carrera`,`grupos_v`,`email`,`email_verified_at`,`pass`,`remember_token`,`rol_id`,`created_at`,`updated_at`) values 
(1,'Director','UTVT','UTVT',NULL,NULL,NULL,NULL,'director@gmail.com',NULL,'1234',NULL,1,NULL,NULL),
(2,'Jossue','Candelas','Hernández',NULL,NULL,NULL,NULL,'ptc@gmail.com',NULL,'1234',NULL,2,NULL,NULL),
(3,'Mike','Arriola','Ortega',NULL,222110811,'Ingenieria en Desarrollo y Gestión de Software',NULL,'estudiante@gmail.com',NULL,'$2y$10$aER9aGyQDx3kDNS8I8tUseDYXSRTMB6eiGZ6XwjJH768ur7Uczj2C',NULL,3,NULL,NULL),
(4,'Jimena','Diaz','Diaz',NULL,NULL,NULL,NULL,'psicologia@gmail.com',NULL,'1234',NULL,4,NULL,NULL),
(5,'Eduh','Olvera','Aldama',NULL,NULL,NULL,NULL,'eduholvera@gmail.com',NULL,'1234',NULL,2,NULL,NULL),
(6,'Fernanda','Gomez','Alcantara',NULL,222110811,'Ingenieria en Desarrollo y Gestión de Sofware',NULL,'fernanda@gmail.com',NULL,'1234',NULL,3,NULL,NULL),
(7,'Ángel','Camacho','Linares',NULL,222220022,'Ingenieria en Desarrollo y Gestión de Sofware',NULL,'angel@gmail.com',NULL,'1234',NULL,3,NULL,NULL),
(8,'Arturo','De Jesús','Gonzales',NULL,222222222,'Ingenieria en Desarrollo y Gestión de Sofware',NULL,'arturo@gmail.com',NULL,'1234',NULL,3,NULL,NULL),
(9,'Rodrigo','Castillo','Ortega',NULL,112233445,'Ingenieria en Desarrollo y Gestión de Sofware',NULL,'rodrigo@gmail.com',NULL,'1234',NULL,3,NULL,NULL),
(10,'Diego','Castañeda','Ramirez',NULL,9988776,'Ingenieria en Desarrollo y Gestión de Sofware',NULL,'diego@gmail.com',NULL,'1234',NULL,3,NULL,NULL),
(11,'Jesús','Buen','Día',NULL,663990893,'Ingenieria en Desarrollo y Gestión de Sofware',NULL,'jesus@gmail.com',NULL,'1234',NULL,3,NULL,NULL),
(12,'Emiliano','Bermudez','Olivares',NULL,98923411,'Ingenieria en Desarrollo y Gestión de Sofware',NULL,'emi@gmail.com',NULL,'1234',NULL,3,NULL,NULL),
(13,'Carolina','Garcia','Garcia',NULL,754971370,'Ingenieria en Desarrollo y Gestión de Sofware',NULL,'caro@gmail.com',NULL,'1234',NULL,3,NULL,NULL),
(14,'Jorge','Castañeda','Campuzano',NULL,543794323,'Ingenieria en Desarrollo y Gestión de Sofware',NULL,'jorge@gmail.com',NULL,'1234',NULL,3,NULL,NULL),
(15,'Ian','Gonzales','Gomez',NULL,280168753,'Ingenieria en Desarrollo y Gestión de Sofware',NULL,'ian@gmail.com',NULL,'1234',NULL,3,NULL,NULL),
(16,'Eduardo','edu','edu',NULL,222010230,'TICS',NULL,'eduard@gmail.com',NULL,'1234',NULL,3,NULL,NULL),
(17,'Alejandro','Candelas','Hernández',NULL,222110811,'Ingenieria en Desarrollo y Gestión de Software',NULL,'al222110811@gmail.com',NULL,'$2y$10$s1MptiAQ4v6suhTfw6QdqOwpgGLCBt.5Dar4.bNUT3f96kYfXg0Ku',NULL,2,NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
