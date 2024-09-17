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

/*Data for the table `usuarios` */

insert  into `usuarios`(`id`,`nombre`,`apellido_paterno`,`apellido_materno`,`matricula`,`carrera`,`email`,`email_verified_at`,`pass`,`remember_token`,`rol_id`,`created_at`,`updated_at`) values 
(1,'Director','UTVT','UTVT',NULL,NULL,'director@gmail.com',NULL,'1234',NULL,1,NULL,NULL),
(2,'Mike','UTVT','UTVT',NULL,NULL,'ptc@gmail.com',NULL,'1234',NULL,2,NULL,NULL),
(3,'Mike','Arriola','Ortega',NULL,NULL,'estudiante@gmail.com',NULL,'1234',NULL,3,NULL,NULL),
(4,'Jimena','Diaz','Diaz',NULL,NULL,'psicologia@gmail.com',NULL,'1234',NULL,4,NULL,NULL),
(5,'Eduh','Olvera','Aldama',NULL,NULL,'eduholvera@gmail.com',NULL,'1234',NULL,2,NULL,NULL),
(6,'Fernanda','Gomez','Alcantara',222110811,'Ingenieria en Desarrollo y Gestión de Sofware','fernanda@gmail.com',NULL,'1234',NULL,3,NULL,NULL),
(7,'Ángel','Camacho','Linares',222220022,'Ingenieria en Desarrollo y Gestión de Sofware','angel@gmail.com',NULL,'1234',NULL,3,NULL,NULL),
(8,'Arturo','De Jesús','Gonzales',222222222,'Ingenieria en Desarrollo y Gestión de Sofware','arturo@gmail.com',NULL,'1234',NULL,3,NULL,NULL),
(9,'Rodrigo','Castillo','Ortega',112233445,'Ingenieria en Desarrollo y Gestión de Sofware','rodrigo@gmail.com',NULL,'1234',NULL,3,NULL,NULL),
(10,'Diego','Castañeda','Ramirez',9988776,'Ingenieria en Desarrollo y Gestión de Sofware','diego@gmail.com',NULL,'1234',NULL,3,NULL,NULL),
(11,'Jesús','Buen','Día',663990893,'Ingenieria en Desarrollo y Gestión de Sofware','jesus@gmail.com',NULL,'1234',NULL,3,NULL,NULL),
(12,'Emiliano','Bermudez','Olivares',98923411,'Ingenieria en Desarrollo y Gestión de Sofware','emi@gmail.com',NULL,'1234',NULL,3,NULL,NULL),
(13,'Carolina','Garcia','Garcia',754971370,'Ingenieria en Desarrollo y Gestión de Sofware','caro@gmail.com',NULL,'1234',NULL,3,NULL,NULL),
(14,'Jorge','Castañeda','Campuzano',543794323,'Ingenieria en Desarrollo y Gestión de Sofware','jorge@gmail.com',NULL,'1234',NULL,3,NULL,NULL),
(15,'Ian','Gonzales','Gomez',280168753,'Ingenieria en Desarrollo y Gestión de Sofware','ian@gmail.com',NULL,'1234',NULL,3,NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
