-- Base de datos BitFlow
-- Ejecutar en phpMyAdmin o: mysql -u root < sql/bitflow.sql

CREATE DATABASE IF NOT EXISTS `bitflow`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `bitflow`;

CREATE TABLE IF NOT EXISTS `contacto` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nombre` VARCHAR(100) NOT NULL,
    `celular` VARCHAR(30) NOT NULL,
    `email` VARCHAR(150) DEFAULT NULL,
    `consulta` TEXT,
    `metodo` VARCHAR(50) NOT NULL,
    `estado` VARCHAR(20) NOT NULL DEFAULT 'nuevo',
    `notas_admin` TEXT DEFAULT NULL,
    `archivado` TINYINT(1) NOT NULL DEFAULT 0,
    `fecha_registro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `actualizado_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `actualizado_por` INT UNSIGNED DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_fecha` (`fecha_registro`),
    INDEX `idx_contacto_estado` (`estado`, `archivado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `admin_usuarios` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `usuario` VARCHAR(60) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `activo` TINYINT(1) NOT NULL DEFAULT 1,
    `ultimo_acceso` DATETIME DEFAULT NULL,
    `creado_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `actualizado_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_admin_usuario` (`usuario`),
    UNIQUE KEY `uq_admin_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `admin_auditoria` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `admin_id` INT UNSIGNED DEFAULT NULL,
    `accion` VARCHAR(80) NOT NULL,
    `entidad` VARCHAR(80) DEFAULT NULL,
    `entidad_id` INT UNSIGNED DEFAULT NULL,
    `detalle` TEXT DEFAULT NULL,
    `ip` VARCHAR(45) DEFAULT NULL,
    `user_agent` VARCHAR(255) DEFAULT NULL,
    `creado_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_auditoria_fecha` (`creado_at`),
    KEY `idx_auditoria_admin` (`admin_id`),
    CONSTRAINT `fk_auditoria_admin` FOREIGN KEY (`admin_id`) REFERENCES `admin_usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `admin_login_intentos` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `identificador` VARCHAR(190) NOT NULL,
    `ip` VARCHAR(45) NOT NULL,
    `intentos` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    `ultimo_intento` DATETIME NOT NULL,
    `bloqueado_hasta` DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_login_identificador_ip` (`identificador`, `ip`),
    KEY `idx_login_bloqueado` (`bloqueado_hasta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
