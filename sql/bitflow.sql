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
    `fecha_registro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_fecha` (`fecha_registro`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
