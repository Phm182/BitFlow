-- Migración del panel administrativo para una instalación BitFlow existente.
-- Hacer backup y ejecutar una sola vez desde phpMyAdmin sobre la base `bitflow`.

USE `bitflow`;

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

DELIMITER //
DROP PROCEDURE IF EXISTS `bitflow_add_column_if_missing`//
CREATE PROCEDURE `bitflow_add_column_if_missing`(
    IN p_table VARCHAR(64),
    IN p_column VARCHAR(64),
    IN p_definition TEXT
)
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = p_table
          AND COLUMN_NAME = p_column
    ) THEN
        SET @sql = CONCAT('ALTER TABLE `', p_table, '` ADD COLUMN `', p_column, '` ', p_definition);
        PREPARE statement_to_run FROM @sql;
        EXECUTE statement_to_run;
        DEALLOCATE PREPARE statement_to_run;
    END IF;
END//
DELIMITER ;

CALL `bitflow_add_column_if_missing`('contacto', 'estado', 'VARCHAR(20) NOT NULL DEFAULT ''nuevo'' AFTER `metodo`');
CALL `bitflow_add_column_if_missing`('contacto', 'notas_admin', 'TEXT NULL AFTER `estado`');
CALL `bitflow_add_column_if_missing`('contacto', 'archivado', 'TINYINT(1) NOT NULL DEFAULT 0 AFTER `notas_admin`');
CALL `bitflow_add_column_if_missing`('contacto', 'actualizado_at', 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `fecha_registro`');
CALL `bitflow_add_column_if_missing`('contacto', 'actualizado_por', 'INT UNSIGNED NULL AFTER `actualizado_at`');

DROP PROCEDURE IF EXISTS `bitflow_add_column_if_missing`;

SET @index_exists = (
    SELECT COUNT(*)
    FROM information_schema.STATISTICS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'contacto'
      AND INDEX_NAME = 'idx_contacto_estado'
);
SET @index_sql = IF(
    @index_exists = 0,
    'ALTER TABLE `contacto` ADD INDEX `idx_contacto_estado` (`estado`, `archivado`)',
    'SELECT 1'
);
PREPARE index_statement FROM @index_sql;
EXECUTE index_statement;
DEALLOCATE PREPARE index_statement;

