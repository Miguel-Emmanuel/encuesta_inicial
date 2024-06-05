/*!40101 SET NAMES utf8 */;
/*!40101 SET SQL_MODE=''*/;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `encuesta_02` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE=utf8mb4_general_ci */;
USE `encuesta_02`;

-- Creación de la tabla `secciones`
CREATE TABLE `secciones` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NOT NULL,
  `descripcion` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Creación de la tabla `preguntas`
CREATE TABLE `preguntas` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pregunta` TEXT NOT NULL,
  `tipo` VARCHAR(255) NOT NULL,
  `seccion_id` BIGINT(20) UNSIGNED NOT NULL,
  `activo` TINYINT(1) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `preguntas_seccion_id_foreign` (`seccion_id`),
  CONSTRAINT `preguntas_seccion_id_foreign` FOREIGN KEY (`seccion_id`) REFERENCES `secciones` (`id`) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Creación de la tabla `opciones_respuesta`
CREATE TABLE `opciones_respuesta` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pregunta_id` BIGINT(20) UNSIGNED NOT NULL,
  `opcion1` TEXT NOT NULL,
  `opcion2` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `opciones_respuesta_pregunta_id_foreign` (`pregunta_id`),
  CONSTRAINT `opciones_respuesta_pregunta_id_foreign` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Creación de la tabla `programa_edu`
CREATE TABLE `programa_edu` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Creación de la tabla `respuestas`
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

-- Creación de la tabla `roles`
CREATE TABLE `roles` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NOT NULL,
  `descripcion` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insertando datos en la tabla `roles`
INSERT INTO `roles` (`id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES 
(1, 'Director de Carrera', 'Hola', NULL, NULL),
(2, 'PTC/Tutor', 'Hola', NULL, NULL),
(3, 'Estudiante', 'Hola', NULL, NULL),
(4, 'Psicologia', 'Hola', NULL, NULL);

-- Creación de la tabla `usuarios`
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

-- Creación de la tabla `usuario_respuesta`
CREATE TABLE `usuario_respuesta` (
  `id` BIGINT(200) UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id` BIGINT(200) UNSIGNED NOT NULL,
  `pregunta_id` BIGINT(200) UNSIGNED NOT NULL,
  `opcion_id` BIGINT(200) UNSIGNED DEFAULT NULL,
  `seccion_id` BIGINT(20) UNSIGNED NOT NULL,
  `respuesta_texto` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_respuesta_usuario_id_foreign` (`usuario_id`),
  KEY `usuario_respuesta_pregunta_id_foreign` (`pregunta_id`),
  KEY `usuario_respuesta_opcion_id_foreign` (`opcion_id`),
  KEY `usuario_respuesta_seccion_id_foreign` (`seccion_id`),
  CONSTRAINT `usuario_respuesta_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `usuario_respuesta_pregunta_id_foreign` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`),
  CONSTRAINT `usuario_respuesta_opcion_id_foreign` FOREIGN KEY (`opcion_id`) REFERENCES `opciones_respuesta` (`id`),
  CONSTRAINT `usuario_respuesta_seccion_id_foreign` FOREIGN KEY (`seccion_id`) REFERENCES `secciones` (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
