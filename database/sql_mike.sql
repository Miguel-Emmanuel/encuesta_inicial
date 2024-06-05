
/*
SQLyog Community v13.2.0 (64 bit)
MySQL - 10.4.32-MariaDB : Database - ei
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`encuesta_03` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `encuesta_03`;


CREATE TABLE `secciones` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NOT NULL,
  `descripcion` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*Table structure for table `preguntas` */

DROP TABLE IF EXISTS `preguntas`;

CREATE TABLE `preguntas` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pregunta` TEXT NOT NULL,
  `tipo` VARCHAR(255) NOT NULL,
  `seccion_id` VARCHAR(255) NOT NULL,
  `activo` TINYINT(1) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `preguntas` */

CREATE TABLE `opciones_respuesta` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pregunta_id` BIGINT(20) UNSIGNED NOT NULL,
  `opcion1` TEXT NOT NULL,
  `opcion2` TEXT  NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `opciones_respuesta_pregunta_id_foreign` (`pregunta_id`),
  CONSTRAINT `opciones_respuesta_pregunta_id_foreign` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `programa_edu` */

DROP TABLE IF EXISTS `programa_edu`;

CREATE TABLE `programa_edu` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `programa_edu` */

/*Table structure for table `respuestas` */

DROP TABLE IF EXISTS `respuestas`;

CREATE TABLE `respuestas` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `respuesta` TEXT NOT NULL,
  `pregunta_id` BIGINT(20) UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `respuesta_fk_pregunta_foreign` (`pregunta_id`),
  CONSTRAINT `respuesta_fk_pregunta_foreign` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `respuestas` */

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NOT NULL,
  `descripcion` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `roles` */

INSERT  INTO `roles`(`id`,`nombre`,`descripcion`,`created_at`,`updated_at`) VALUES 
(1,'Director de Carrera','Hola',NULL,NULL),
(2,'PTC/Tutor','Hola',NULL,NULL),
(3,'Estudiante','Hola',NULL,NULL),
(4,'Psicologia','Hola',NULL,NULL);

/*Table structure for table `usuario_respuesta` */

DROP TABLE IF EXISTS `usuario_respuesta`;

CREATE TABLE `usuario_respuesta` (
  `id` BIGINT(200) UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id` BIGINT(200) UNSIGNED NOT NULL,
  `pregunta_id` BIGINT(200) UNSIGNED NOT NULL,
  `opcion_id` BIGINT(200) UNSIGNED DEFAULT NULL,
  `respuesta_texto` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_respuesta_usuario_id_foreign` (`usuario_id`),
  KEY `usuario_respuesta_pregunta_id_foreign` (`pregunta_id`),
  KEY `usuario_respuesta_opcion_id_foreign` (`opcion_id`),
  CONSTRAINT `usuario_respuesta_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `usuario_respuesta_pregunta_id_foreign` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`),
  CONSTRAINT `usuario_respuesta_opcion_id_foreign` FOREIGN KEY (`opcion_id`) REFERENCES `opciones_respuesta` (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `usuario_respuesta` */

/*Table structure for table `usuarios` */

DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(50) NOT NULL,
  `apellido_paterno` VARCHAR(50) NOT NULL,
  `apellido_materno` VARCHAR(50) NOT NULL,
  `matricula` INT(11) DEFAULT NULL,
  `carrera` VARCHAR(255) DEFAULT NULL,
  `email` TEXT NOT NULL,
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
  `pass` TEXT NOT NULL,
  `remember_token` VARCHAR(100) DEFAULT NULL,
  `rol_id` BIGINT(20) UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`) USING HASH,
  KEY `users_rol_id_foreign` (`rol_id`),
  CONSTRAINT `users_rol_id_foreign` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`)
) ENGINE=INNODB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `usuarios` */

INSERT  INTO `usuarios`(`id`,`nombre`,`apellido_paterno`,`apellido_materno`,`matricula`,`carrera`,`email`,`email_verified_at`,`pass`,`remember_token`,`rol_id`,`created_at`,`updated_at`) VALUES 
(1,'Eduh','Olvera','Aldama',NULL,NULL,'director@gmail.com',NULL,'1234',NULL,1,NULL,NULL),
(2,'Eduh','Olvera','Aldama',NULL,NULL,'ptc@gmail.com',NULL,'1234',NULL,2,NULL,NULL),
(3,'Eduh','Olvera','Aldama',NULL,NULL,'estudiante@gmail.com',NULL,'1234',NULL,3,NULL,NULL),
(4,'Eduh','Olvera','Aldama',NULL,NULL,'psicologia@gmail.com',NULL,'1234',NULL,4,NULL,NULL),
(5,'Eduh','Olvera','Aldama',NULL,NULL,'eduholvera@gmail.com',NULL,'1234',NULL,2,NULL,NULL);

INSERT INTO secciones (nombre, descripcion)
VALUES 
    ('general', 'Datos Generales'),
    ('emergencia', 'Datos de Emergencia'),
    ('emergencia2', 'Datos de Emergencia(2)'),
    ('socioeconomico', 'Aspectos Socioeconomicos'),
    ('salud', 'Condiciones de Salud'),
    ('escolar', 'Rendimiento Escolar'),
    ('habitos', 'Habitos de Estudio y Practicas Escolares');

INSERT INTO preguntas (id, pregunta, tipo, seccion_id, activo, created_at, updated_at) VALUES
(1, 'Correo', 'texto', 1, 1, NOW(), NOW()),
(2, 'Programa Educativo', 'select', 1, 1, NOW(), NOW()),
(3, 'Nombre', 'texto', 1, 1, NOW(), NOW()),
(4, 'Apellido Paterno', 'texto', 1, 1, NOW(), NOW()),
(5, 'Apellido Materno', 'texto', 1, 1, NOW(), NOW()),
(6, 'CURP', 'texto', 1, 1, NOW(), NOW()),
(7, 'RFC', 'texto', 2, 1, NOW(), NOW()),
(8, 'Sexo', 'texto', 1, 1, NOW(), NOW()),
(9, 'Estado Civil', 'opcion', 1, 1, NOW(), NOW()),
(10, 'Numero de Hijos', 'opcion', 1, 1, NOW(), NOW()),
(11, 'Economicamente alguien depende de ti', 'texto', 1, 1, NOW(), NOW()),
(12, 'Religión', 'texto', 1, 1, NOW(), NOW()),
(13, 'Grupo Sanguíneo', 'texto', 1, 1, NOW(), NOW()),
(14, 'Fecha de Nacimiento', 'texto', 1, 1, NOW(), NOW()),
(15, 'Edad', 'texto', 1, 1, NOW(), NOW()),
(16, 'País de Nacimiento', 'texto', 1, 1, NOW(), NOW()),
(17, 'Estado de Nacimiento', 'texto', 1, 1, NOW(), NOW()),
(18, 'Municipio de Nacimiento', 'texto', 2, 1, NOW(), NOW()),
(19, 'Teléfono Celular', 'texto', 2, 1, NOW(), NOW()),
(20, 'Teléfono Casa', 'texto', 2, 1, NOW(), NOW()),
(21, 'Correo Electrónico Personal', 'texto', 2, 1, NOW(), NOW()),
(22, 'Redes Sociales', 'texto', 2, 1, NOW(), NOW()),
(23, 'Calle', 'texto', 2, 1, NOW(), NOW()),
(24, 'No. Exterior', 'texto', 2, 1, NOW(), NOW()),
(25, 'No. Interior', 'texto', 2, 1, NOW(), NOW()),
(26, 'Colonia', 'texto', 2, 1, NOW(), NOW()),
(27, 'Localidad', 'texto', 2, 1, NOW(), NOW()),
(28, 'Municipio', 'texto', 2, 1, NOW(), NOW()),
(29, 'CP', 'texto', 2, 1, NOW(), NOW()),
(30, 'Menciona 2 referencias cerca de su domicilio', 'texto', 2, 1, NOW(), NOW()),
(31, 'Nombre', 'texto', 3, 1, NOW(), NOW()),
(32, 'Apellido Paterno', 'texto', 3, 1, NOW(), NOW()),
(33, 'Apellido Materno', 'texto', 3, 1, NOW(), NOW()),
(34, 'Parentesco', 'multi', 3, 1, NOW(), NOW()),
(35, 'Correo Electronico', 'texto', 3, 1, NOW(), NOW()),
(36, 'Telefono Celular', 'texto', 3, 1, NOW(), NOW()),
(37, 'Telefono de casa', 'texto', 3, 1, NOW(), NOW()),
(38, 'Domicilio Completo', 'texto', 3, 1, NOW(), NOW()),
(39, 'Menciona dos referencias cerca de su domicilio', 'texto', 3, 1, NOW(), NOW()),
(40, 'Nombre', 'texto', 4, 1, NOW(), NOW()),
(41, 'Apellido Paterno', 'texto', 4, 1, NOW(), NOW()),
(42, 'Apellido Materno', 'texto', 4, 1, NOW(), NOW()),
(43, 'Parentesco', 'multi', 4, 1, NOW(), NOW()),
(44, 'Correo Electronico', 'texto', 4, 1, NOW(), NOW()),
(45, 'Telefono Celular', 'texto', 4, 1, NOW(), NOW()),
(46, 'Telefono casa', 'texto', 4, 1, NOW(), NOW()),
(47, 'Domicilio', 'texto', 4, 1, NOW(), NOW()),
(48, 'Menciona 2 referencias cerca de su domicilio', 'texto', 4, 1, NOW(), NOW()),
(49, 'Trabajas', 'opcion', 5, 1, NOW(), NOW()),
(50, 'Cuantas horas trabajas a la semana', 'opcion', 5, 1, NOW(), NOW()),
(51, 'Lugar de trabajo', 'texto', 5, 1, NOW(), NOW()),
(52, 'Domicilio del lugar de trabajo', 'texto', 5, 1, NOW(), NOW()),
(53, 'Dias de trabajo', 'multi', 5, 1, NOW(), NOW()),
(54, 'Horarios de trabajo', 'texto', 5, 1, NOW(), NOW()),
(55, 'Ingreso mensual', 'texto', 5, 1, NOW(), NOW()),
(56, 'De quien dependes economicamente', 'opcion', 5, 1, NOW(), NOW()),
(57, 'Con quien vives actualmente', 'multi', 5, 1, NOW(), NOW()),
(58, 'Aporta al gasto familiar', 'multi', 5, 1, NOW(), NOW()),
(59, 'Ingreso mensual de todos los integrantes de la familia', 'texto', 5, 1, NOW(), NOW()),
(60, 'Edad de los integrantes de la familia', 'multi', 5, 1, NOW(), NOW()),
(61, 'Ocupacion del quien aporta la mayor parte del gasto familiar', 'texto', 5, 1, NOW(), NOW()),
(62, 'Cual es tu lugar de residencia mientras estudias la carrera', 'opcion', 5, 1, NOW(), NOW()),
(63, 'Tiempo de residencia en el domicilio', 'texto', 5, 1, NOW(), NOW()),
(64, 'En la casa donde vives hay', 'multi', 5, 1, NOW(), NOW()),
(65, 'Cual es el medio de transporte que utilizas regularmente para trasladarte a la escuela', 'multi', 5, 1, NOW(), NOW()),
(66, 'Cuanto tiempo haces diariamente para trasladarte de tu lugar de residencia a la escuela', 'opcion', 5, 1, NOW(), NOW()),
(67, 'Analisis relacion Padre', 'opcion', 6, 1, NOW(), NOW()),
(68, 'Analisis relacion Madre', 'opcion', 6, 1, NOW(), NOW()),
(69, 'Tus papas o tus abuelos son indigenas', 'opcion', 6, 1, NOW(), NOW()),
(70, 'Tus papas o tus abuelos entienden o hablan alguna lengua indigena', 'opcion', 6, 1, NOW(), NOW()),
(71, 'Tu hablas o entiendes alguna lengua indigena', 'opcion', 6, 1, NOW(), NOW()),
(72, 'Indica si presentas alguna de las siguientes condiciones', 'multi', 7, 1, NOW(), NOW()),
(73, 'Tiene algun padecimiento cronico', 'opcion', 7, 1, NOW(), NOW()),
(74, 'Nombre del padecimiento cronico', 'texto', 7, 1, NOW(), NOW()),
(75, 'Tiene alguna alergia', 'opcion', 7, 1, NOW(), NOW()),
(76, 'Nombre de la alergia', 'texto', 7, 1, NOW(), NOW()),
(77, '¿Tomas algún medicamento periódicamente?', 'opcion', 7, 1, NOW(), NOW()),
(78, 'Nombre del medicamento', 'texto', 7, 1, NOW(), NOW()),
(79, '¿Has recibido atención psicológica o psiquiátrica?', 'opcion', 7, 1, NOW(), NOW()),
(80, 'Motivo de la atención psicológica o psiquiátrica', 'texto', 7, 1, NOW(), NOW()),
(81, '¿Ha estado alguna vez hospitalizado(a)?', 'opcion', 7, 1, NOW(), NOW()),
(82, 'Especifica el motivo de la hospitalización', 'texto', 7, 1, NOW(), NOW()),
(83, '¿En qué tipo de escuela realizaste tus estudios previos a la educación superior?', 'opcion', 8, 1, NOW(), NOW()),
(84, '¿En qué tipo de escuela realizaste tus estudios previos a la educación superior?', 'opcion', 8, 1, NOW(), NOW()),
(85, 'Nombre de la institución de nivel medio superior', 'texto', 8, 1, NOW(), NOW()),
(86, 'Especialidad que cursó en el nivel medio superior', 'texto', 8, 1, NOW(), NOW()),
(87, 'Municipio donde se ubica la institución de nivel medio superior', 'texto', 8, 1, NOW(), NOW()),
(88, 'Estado donde se ubica la institución de nivel medio superior', 'texto', 8, 1, NOW(), NOW()),
(89, '¿Cuál fue la escolaridad máxima alcanzada por tu madre?', 'multi', 8, 1, NOW(), NOW()),
(90, '¿Cuál fue la escolaridad máxima alcanzada por tu padre?', 'multi', 8, 1, NOW(), NOW()),
(91, '¿Tienes hermanos que estén cursando una carrera de licenciatura?', 'opcion', 8, 1, NOW(), NOW()),
(92, 'En el proceso para que decidieras cursar tu carrera, ¿qué factores fueron de mayor importancia?', 'multi', 8, 1, NOW(), NOW()),
(93, '¿Cursas alguna otra carrera actualmente?', 'opcion', 8, 1, NOW(), NOW()),
(94, 'Nombre de la otra carrera que cursas actualmente', 'texto', 8, 1, NOW(), NOW()),
(95, 'Nombre de la institución de la otra carrera que cursas actualmente', 'texto', 8, 1, NOW(), NOW()),
(96, '¿Cursaste otra carrera antes de entrar a la UTVT?', 'opcion', 8, 1, NOW(), NOW()),
(97, '¿La carrera que cursaste anteriormente es la misma con la que iniciaste tus estudios?', 'opcion', 8, 1, NOW(), NOW()),
(98, 'Nombre de la institución de la carrera que cursaste antes de entrar a la UTVT', 'texto', 8, 1, NOW(), NOW()),
(99, '¿A qué factores se debió tu cambio de carrera?', 'multi', 8, 1, NOW(), NOW()),
(100, '¿Qué tipo de lecturas acostumbras a utilizar al cursar tus estudios?', 'multi', 9, 1, NOW(), NOW()),
(101, 'En promedio, ¿Cuántas horas dedicas a la semana a la preparación de tus trabajos? (comprende lecturas y preparación de trabajos y tareas)', 'opcion', 9, 1, NOW(), NOW()),
(102, '¿Cuáles son las formas de estudio y/o realización de trabajos escolares que empleas regularmente?', 'multi', 9, 1, NOW(), NOW()),
(103, 'De acuerdo con la carrera que cursas actualmente, ¿en qué espacio laboral pretenderías preferentemente desarrollar tu actividad profesional?', 'opcion', 9, 1, NOW(), NOW()),
(104, 'Una vez concluidos tus estudios, ¿cómo consideras tus posibilidades de encontrar trabajo relacionado con tu profesión?', 'opcion', 9, 1, NOW(), NOW()),
(105, 'En comparación con la ocupación de tu padre o de la persona que ocupa el lugar de jefe de familia, ¿cómo percibes el desarrollo de tu vida profesional una vez que concluyas tus estudios de licenciatura?', 'multi', 9, 1, NOW(), NOW()),
(106, '¿Dentro de tus planes futuros piensas realizar estudios de posgrado después de concluir tu carrera?', 'opcion', 9, 1, NOW(), NOW());


-- Opciones para la pregunta "¿Cuántos días trabajas?"
INSERT INTO `opciones_respuesta` (`pregunta_id`, `opcion1`, `opcion2`, `created_at`, `updated_at`) VALUES
(2, 'TSU en Tecnologias de la Informacion , Area de Desarrollo de Software multiplataforma(DSM)', '' , NOW(), NOW()),
(2, 'TSU en Tecnologias de la Informacion , Area Infraestrcutura de Redes Digitales(IRD)', '' , NOW(), NOW()),
(8, 'Femenino', '' , NOW(), NOW()),
(8, 'Masculino', '' , NOW(), NOW()),
(9, 'Soltero (a)','', NOW(), NOW()),
(9, 'Divorciado (a)','' ,NOW(), NOW()),
(9, 'Viudo (a)','' ,NOW(), NOW()),
(9, 'Casado (a)','' ,NOW(), NOW()),
(9, 'Union libre','' ,NOW(), NOW()),
(10, 'Ninguno','', NOW(), NOW()),
(10, '1 hijo(a)','' ,NOW(), NOW()),
(10, '2 hijo(a)','' ,NOW(), NOW()),
(10, 'mas de 2 hijos(as)','' ,NOW(), NOW()),
(11, 'Si','' ,NOW(), NOW()),
(11, 'No','',NOW(), NOW()),
(34, 'Padre','' ,NOW(), NOW()),
(34, 'Madre','' ,NOW(), NOW()),
(34, 'Otro:','', NOW(), NOW()),
(43, 'Padre:','', NOW(), NOW()),
(43, 'Madre:','', NOW(), NOW()),
(43, 'Otro:','', NOW(), NOW()),
(49, 'Si:','', NOW(), NOW()),
(49, 'No:','', NOW(), NOW()),
(50, 'No Aplica:','', NOW(), NOW()),
(50, 'Menos de 10 horas a la semana','', NOW(), NOW()),
(50, 'De 10 a 20 horas a la semana','', NOW(), NOW()),
(50, 'De 21 a 40 horas a la semana','', NOW(), NOW()),
(53, 'Lunes','', NOW(), NOW()),
(53, 'Maartes','', NOW(), NOW()),
(53, 'Miercoles','', NOW(), NOW()),
(53, 'Jueves','', NOW(), NOW()),
(53, 'Viernes','', NOW(), NOW()),
(53, 'Sabado','', NOW(), NOW()),
(53, 'Domingo','', NOW(), NOW()),
(56, 'Padres(Padre o madre o ambos)','', NOW(), NOW()),
(56, 'Familiares(Tios, abuelos o hermanos)','', NOW(), NOW()),
(56, 'Soy independiente economicamente','', NOW(), NOW()),
(56, 'Pension (renta cerca de la UTVT )','', NOW(), NOW()),
(57, 'Padre ','Si', NOW(), NOW()),
(57, 'Padre ','No', NOW(), NOW()),
(57, 'Madre ','Si', NOW(), NOW()),
(57, 'Madre ','No', NOW(), NOW()),
(57, '1 a 2 hermanos ','Si', NOW(), NOW()),
(57, '1 a 2 hermanos ','No', NOW(), NOW()),
(57, 'mas de 2 hermanos ','Si', NOW(), NOW()),
(57, 'mas de 2 hermanos ','No', NOW(), NOW()),
(57, 'Abuelos paternos ','Si', NOW(), NOW()),
(57, 'Abuelos paternos ','No', NOW(), NOW()),
(57, 'Abuelos maternos ','Si', NOW(), NOW()),
(57, 'Abuelos maternos ','No', NOW(), NOW()),
(57, 'Parientes ','Si', NOW(), NOW()),
(57, 'Parientes ','No', NOW(), NOW()),
(57, 'Pareja sentimental ','Si', NOW(), NOW()),
(57, 'Pareja sentimental ','No', NOW(), NOW()),
(57, 'Otro: ','Si', NOW(), NOW()),
(57, 'Otro: ','No', NOW(), NOW()),

(58, 'Padre ','Si', NOW(), NOW()),
(58, 'Padre ','No', NOW(), NOW()),
(58, 'Madre ','Si', NOW(), NOW()),
(58, 'Madre ','No', NOW(), NOW()),
(58, 'Hermanos ','No', NOW(), NOW()),
(58, 'Hermanos ','Si', NOW(), NOW()),
(58, 'Abuelos maternos ','Si', NOW(), NOW()),
(58, 'Abuelos maternos ','No', NOW(), NOW()),
(58, 'Abuelos paternos ','Si', NOW(), NOW()),
(58, 'Abuelos paternos ','No', NOW(), NOW()),
(58, 'Parientes ','Si', NOW(), NOW()),
(58, 'Parientes ','No', NOW(), NOW()),
(58, 'Pareja sentimental ','Si', NOW(), NOW()),
(58, 'Pareja sentimental ','No', NOW(), NOW()),

(60, 'Padre','1 a 5 años', NOW(), NOW()),
(60, 'Madre ','1 a 5 años', NOW(), NOW()),
(60, 'Hermano 1 ','1 a 5 años', NOW(), NOW()),
(60, 'Hermano 2 ','1 a 5 años', NOW(), NOW()),
(60, 'Hermano 3 ','1 a 5 años', NOW(), NOW()),
(60, 'Hermano 4 ','1 a 5 años', NOW(), NOW()),
(60, 'Padre','6 a 10 años', NOW(), NOW()),
(60, 'Madre ','6 a 10 años', NOW(), NOW()),
(60, 'Hermano 1 ','6 a 10 años', NOW(), NOW()),
(60, 'Hermano 2 ','6 a 10 años', NOW(), NOW()),
(60, 'Hermano 3 ','6 a 10 años', NOW(), NOW()),
(60, 'Hermano 4 ','6 a 10 años', NOW(), NOW()),
(60, 'Padre','11 a 15 años', NOW(), NOW()),
(60, 'Madre ','11 a 15 años', NOW(), NOW()),
(60, 'Hermano 1 ','11 a 15 años', NOW(), NOW()),
(60, 'Hermano 2 ','11 a 15 años', NOW(), NOW()),
(60, 'Hermano 3 ','11 a 15 años', NOW(), NOW()),
(60, 'Hermano 4 ','11 a 15 años', NOW(), NOW()),
(60, 'Padre','11 a 15 años', NOW(), NOW()),
(60, 'Madre ','11 a 15 años', NOW(), NOW()),
(60, 'Hermano 1 ','11 a 15 años', NOW(), NOW()),
(60, 'Hermano 2 ','11 a 15 años', NOW(), NOW()),
(60, 'Hermano 3 ','11 a 15 años', NOW(), NOW()),
(60, 'Hermano 4 ','11 a 15 años', NOW(), NOW()),
(60, 'Padre','15 a 20 años', NOW(), NOW()),
(60, 'Madre ','15 a 20 años', NOW(), NOW()),
(60, 'Hermano 1 ','15 a 20 años', NOW(), NOW()),
(60, 'Hermano 2 ','15 a 20 años', NOW(), NOW()),
(60, 'Hermano 3 ','15 a 20 años', NOW(), NOW()),
(60, 'Hermano 4 ','15 a 20 años', NOW(), NOW()),
(60, 'Padre','21 a 30 años', NOW(), NOW()),
(60, 'Madre ','21 a 30 años', NOW(), NOW()),
(60, 'Hermano 1 ','21 a 30 años', NOW(), NOW()),
(60, 'Hermano 2 ','21 a 30 años', NOW(), NOW()),
(60, 'Hermano 3 ','21 a 30 años', NOW(), NOW()),
(60, 'Hermano 4 ','21 a 30 años', NOW(), NOW()),
(60, 'Padre','31 a 40 años', NOW(), NOW()),
(60, 'Madre ','31 a 40 años', NOW(), NOW()),
(60, 'Hermano 1 ','31 a 40 años', NOW(), NOW()),
(60, 'Hermano 2 ','31 a 40 años', NOW(), NOW()),
(60, 'Hermano 3 ','31 a 40 años', NOW(), NOW()),
(60, 'Hermano 4 ','31 a 40 años', NOW(), NOW()),
(60, 'Padre','41 a 50 años', NOW(), NOW()),
(60, 'Madre ','41 a 50 años', NOW(), NOW()),
(60, 'Hermano 1 ','41 a 50 años', NOW(), NOW()),
(60, 'Hermano 2 ','41 a 50 años', NOW(), NOW()),
(60, 'Hermano 3 ','41 a 50 años', NOW(), NOW()),
(60, 'Hermano 4 ','41 a 50 años', NOW(), NOW()),
(60, 'Padre','50 a 60 años', NOW(), NOW()),
(60, 'Madre ','50 a 60 años', NOW(), NOW()),
(60, 'Hermano 1 ','50 a 60 años', NOW(), NOW()),
(60, 'Hermano 2 ','50 a 60 años', NOW(), NOW()),
(60, 'Hermano 3 ','50 a 60 años', NOW(), NOW()),
(60, 'Hermano 4 ','50 a 60 años', NOW(), NOW()),
(60, 'Padre','mas de 60 años', NOW(), NOW()),
(60, 'Madre ','mas de 60 años', NOW(), NOW()),
(60, 'Hermano 1 ','mas de 60 años', NOW(), NOW()),
(60, 'Hermano 2 ','mas de 60 años', NOW(), NOW()),
(60, 'Hermano 3 ','mas de 60 años', NOW(), NOW()),
(60, 'Hermano 4 ','mas de 60 años', NOW(), NOW()),



(62, 'Casa de tus padres (propia)','', NOW(), NOW()),
(62, 'Casa de tus padres (rentada)','', NOW(), NOW()),
(62, 'Casa de tus padres (prestada)','', NOW(), NOW()),
(62, 'Otro:','', NOW(), NOW()),
(64, 'Drenaje','', NOW(), NOW()),
(64, 'Agua Potable','', NOW(), NOW()),
(64, 'Luz','', NOW(), NOW()),
(64, 'Estuda de gas','', NOW(), NOW()),
(64, 'Telefono fijo','', NOW(), NOW()),
(64, 'Auto propio de la familia','', NOW(), NOW()),
(64, 'Television por cable','', NOW(), NOW()),
(64, 'Internet','', NOW(), NOW()),

(65, 'Autobus','', NOW(), NOW()),
(65, 'Taxi particular','', NOW(), NOW()),
(65, 'Taxi colectivo','', NOW(), NOW()),
(65, 'Motocicleta','', NOW(), NOW()),
(65, 'Auto propio','', NOW(), NOW()),
(65, 'Auto de la familia','', NOW(), NOW()),
(65, 'Ninguno','', NOW(), NOW()),

(66, 'Menos de 30 minutos','', NOW(), NOW()),
(66, 'De 30 a 60 minutos','', NOW(), NOW()),
(66, 'De 60 a 90 minutos','', NOW(), NOW()),
(66, 'De 90 a 120 minutos','', NOW(), NOW()),
(66, 'Mas de 120 minutos','', NOW(), NOW()),

(67, 'Actualmente vive con usted','Si', NOW(), NOW()),
(67, 'Recibe apoyo economico','Si', NOW(), NOW()),
(67, 'Fallecio','Si', NOW(), NOW()),
(67, 'Actualmente mantengo una buena relacion con mi padre','Si', NOW(), NOW()),
(67, 'Actualmente vive con usted','No', NOW(), NOW()),
(67, 'Recibe apoyo economico','No', NOW(), NOW()),
(67, 'Fallecio','No', NOW(), NOW()),
(67, 'Actualmente mantengo una buena relacion con mi padre','No', NOW(), NOW()),

(68, 'Actualmente vive con usted','Si', NOW(), NOW()),
(68, 'Recibe apoyo economico','Si', NOW(), NOW()),
(68, 'Fallecio','Si', NOW(), NOW()),
(68, 'Actualmente mantengo una buena relacion con mi padre','Si', NOW(), NOW()),
(68, 'Actualmente vive con usted','No', NOW(), NOW()),
(68, 'Recibe apoyo economico','No', NOW(), NOW()),
(68, 'Fallecio','No', NOW(), NOW()),
(68, 'Actualmente mantengo una buena relacion con mi padre','No', NOW(), NOW()),

(69, 'Si','', NOW(), NOW()),
(69, 'No','', NOW(), NOW()),
(70, 'Si','', NOW(), NOW()),
(70, 'No','', NOW(), NOW()),
(71, 'Si','', NOW(), NOW()),
(71, 'No','', NOW(), NOW()),

(72, 'Usas lentes','', NOW(), NOW()),
(72, 'Tienes alguna deficiencia auditiva','', NOW(), NOW()),
(72, 'Problemas de movilidad motriz','', NOW(), NOW()),
(72, 'Ninguna de las anteriores','', NOW(), NOW()),
(72, 'Otro:','', NOW(), NOW()),

(73, 'Si','', NOW(), NOW()),
(73, 'No','', NOW(), NOW()),
(75, 'Si','', NOW(), NOW()),
(75, 'No','', NOW(), NOW()),
(77, 'Si','', NOW(), NOW()),
(77, 'No','', NOW(), NOW()),
(79, 'Si','', NOW(), NOW()),
(79, 'No','', NOW(), NOW()),
(81, 'Si','', NOW(), NOW()),
(81, 'No','', NOW(), NOW()),

(83, 'Tipo de Institucion','Publica', NOW(), NOW()),
(83, 'Tipo de Institucion','Privada', NOW(), NOW()),

(84, 'Modalidad','Escolarizada', NOW(), NOW()),
(84, 'Modalidad','Abierta', NOW(), NOW()),

(89, 'Sin estudio','Completa', NOW(), NOW()),
(89, 'Primaria','Completa', NOW(), NOW()),
(89, 'Secundaria','Completa', NOW(), NOW()),
(89, 'Bachillerato','Completa', NOW(), NOW()),
(89, 'Estudios tecnicos','Completa', NOW(), NOW()),
(89, 'Licenciatura','Completa', NOW(), NOW()),
(89, 'Postgrado','Completa', NOW(), NOW()),
(89, 'Sin estudio','Incompleta', NOW(), NOW()),
(89, 'Primaria','Incompleta', NOW(), NOW()),
(89, 'Secundaria','Incompleta', NOW(), NOW()),
(89, 'Bachillerato','Incompleta', NOW(), NOW()),
(89, 'Estudios tecnicos','Incompleta', NOW(), NOW()),
(89, 'Licenciatura','Incompleta', NOW(), NOW()),
(89, 'Postgrado','Incompleta', NOW(), NOW()),
(89, 'Sin estudio','No aplica', NOW(), NOW()),
(89, 'Primaria','No aplica', NOW(), NOW()),
(89, 'Secundaria','No aplica', NOW(), NOW()),
(89, 'Bachillerato','No aplica', NOW(), NOW()),
(89, 'Estudios tecnicos','No aplica', NOW(), NOW()),
(89, 'Licenciatura','No aplica', NOW(), NOW()),
(89, 'Postgrado','No aplica', NOW(), NOW()),

(90, 'Sin estudio','Completa', NOW(), NOW()),
(90, 'Primaria','Completa', NOW(), NOW()),
(90, 'Secundaria','Completa', NOW(), NOW()),
(90, 'Bachillerato','Completa', NOW(), NOW()),
(90, 'Estudios tecnicos','Completa', NOW(), NOW()),
(90, 'Licenciatura','Completa', NOW(), NOW()),
(90, 'Postgrado','Completa', NOW(), NOW()),
(90, 'Sin estudio','Incompleta', NOW(), NOW()),
(90, 'Primaria','Incompleta', NOW(), NOW()),
(90, 'Secundaria','Incompleta', NOW(), NOW()),
(90, 'Bachillerato','Incompleta', NOW(), NOW()),
(90, 'Estudios tecnicos','Incompleta', NOW(), NOW()),
(90, 'Licenciatura','Incompleta', NOW(), NOW()),
(90, 'Postgrado','Incompleta', NOW(), NOW()),
(90, 'Sin estudio','No aplica', NOW(), NOW()),
(90, 'Primaria','No aplica', NOW(), NOW()),
(90, 'Secundaria','No aplica', NOW(), NOW()),
(90, 'Bachillerato','No aplica', NOW(), NOW()),
(90, 'Estudios tecnicos','No aplica', NOW(), NOW()),
(90, 'Licenciatura','No aplica', NOW(), NOW()),
(90, 'Postgrado','No aplica', NOW(), NOW()),

(91, 'Si','', NOW(), NOW()),
(91, 'No','', NOW(), NOW()),

(92, 'Orientacion vocacional en Bachillerato','', NOW(), NOW()),
(92, 'Conversacion con amigos','', NOW(), NOW()),
(92, 'Conversacion con mis padres o tutores','', NOW(), NOW()),
(92, 'Conversacion con otros familiares','', NOW(), NOW()),
(92, 'Conversacion con mis maestros de bachillerato','', NOW(), NOW()),
(92, 'Informacion del Programa obtenido por la Institucion','', NOW(), NOW()),
(92, 'Oportunidades de empleo futuro','', NOW(), NOW()),
(92, 'Por vocacion','', NOW(), NOW()),
(92, 'Por gusto personal','', NOW(), NOW()),

(93, 'Si','', NOW(), NOW()),
(93, 'No','', NOW(), NOW()),

(96, 'Si','', NOW(), NOW()),
(96, 'No','', NOW(), NOW()),

(97, 'Si','', NOW(), NOW()),
(97, 'No','', NOW(), NOW()),


(99, 'Desde un principio lo planee','', NOW(), NOW()),
(99, 'Me di cuenta que no correspondia con mi vocacion','', NOW(), NOW()),
(99, 'No me gusto el ambiente','', NOW(), NOW()),
(99, 'Por mejores perspectivas futuras de empleo futuro','', NOW(), NOW()),
(99, 'Por mal desempeno','', NOW(), NOW()),
(99, 'Otro:','', NOW(), NOW()),



(100, 'La bibliografia del programa','Nunca', NOW(), NOW()),
(100, 'Bibliografia que busco por mi cuenta','Nunca', NOW(), NOW()),
(100, 'Revistas especializadas','Nunca', NOW(), NOW()),
(100, 'Enciclopedias','Nunca', NOW(), NOW()),
(100, 'Diccionarios','Nunca', NOW(), NOW()),
(100, 'Libros de Texto','Nunca', NOW(), NOW()),
(100, 'Paginas Web','Nunca', NOW(), NOW()),
(100, 'La bibliografia del programa','Casi nunca', NOW(), NOW()),
(100, 'Bibliografia que busco por mi cuenta','Casi nunca', NOW(), NOW()),
(100, 'Revistas especializadas','Casi nunca', NOW(), NOW()),
(100, 'Enciclopedias','Casi nunca', NOW(), NOW()),
(100, 'Diccionarios','Casi nunca', NOW(), NOW()),
(100, 'Libros de Texto','Casi nunca', NOW(), NOW()),
(100, 'Paginas Web','Casi nunca', NOW(), NOW()),
(100, 'La bibliografia del programa','Casi siempre', NOW(), NOW()),
(100, 'Bibliografia que busco por mi cuenta','Casi siempre', NOW(), NOW()),
(100, 'Revistas especializadas','Casi siempre', NOW(), NOW()),
(100, 'Enciclopedias','Casi siempre', NOW(), NOW()),
(100, 'Diccionarios','Casi siempre', NOW(), NOW()),
(100, 'Libros de Texto','Casi siempre', NOW(), NOW()),
(100, 'Paginas Web','Casi siempre', NOW(), NOW()),
(100, 'La bibliografia del programa','Siempre', NOW(), NOW()),
(100, 'Bibliografia que busco por mi cuenta','Siemprea', NOW(), NOW()),
(100, 'Revistas especializadas','Siempre', NOW(), NOW()),
(100, 'Enciclopedias','Siempre', NOW(), NOW()),
(100, 'Diccionarios','Siempre', NOW(), NOW()),
(100, 'Libros de Texto','Siempre', NOW(), NOW()),
(100, 'Paginas Web','Siempre', NOW(), NOW()),

(101, 'Menos de 1 hora','', NOW(), NOW()),
(101, 'De 1 a 5 hrs','', NOW(), NOW()),
(101, 'De 6 a 10 hrs','', NOW(), NOW()),
(101, 'De 11 a 15 hrs','', NOW(), NOW()),
(101, 'De 16 a 20 hrs','', NOW(), NOW()),
(101, 'Mas de 20 hrs','', NOW(), NOW()),

(102, 'Solo','Nunca', NOW(), NOW()),
(102, 'En grupo','Nunca', NOW(), NOW()),
(102, 'Solo','Casi nunca', NOW(), NOW()),
(102, 'En grupo','Casi nunca', NOW(), NOW()),
(102, 'Solo','Casi siempre', NOW(), NOW()),
(102, 'En grupo','Casi siempre', NOW(), NOW()),
(102, 'Solo','Siempre', NOW(), NOW()),
(102, 'En grupo','Siemprea', NOW(), NOW()),



(103, 'En una institucion educativa','', NOW(), NOW()),
(103, 'En el sector publico','', NOW(), NOW()),
(103, 'En una empresa privada','', NOW(), NOW()),
(103, 'En el negocio de mi familia','', NOW(), NOW()),
(103, 'Poner in negocio propio','', NOW(), NOW()),
(103, 'Ejercicio libre de la profesion','', NOW(), NOW()),

(104, 'Altas','', NOW(), NOW()),
(104, 'Medias','', NOW(), NOW()),
(104, 'Bajas','', NOW(), NOW()),
(104, 'Nulas','', NOW(), NOW()),

(105, 'Considerablemente mejor','En terminos economicos', NOW(), NOW()),
(105, 'Mejor','En terminos economicos', NOW(), NOW()),
(105, 'Similar','En terminos economicos', NOW(), NOW()),
(105, 'Inferior','En terminos economicos', NOW(), NOW()),
(105, 'Considerablemente mejor','En cuanto al prestigio social', NOW(), NOW()),
(105, 'Mejor','En cuanto al prestigio social', NOW(), NOW()),
(105, 'Similar','En cuanto al prestigio social', NOW(), NOW()),
(105, 'Inferior','En cuanto al prestigio social', NOW(), NOW()),
(106, 'Si','', NOW(), NOW()),
(106, 'No','', NOW(), NOW());



/*///////////////////////////////////MULTISELECT(VARIAS FILAS Y COLUMNAS)********************************************************/

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
