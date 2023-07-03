CREATE DATABASE IF NOT EXISTS `demo_app`;
USE `demo_app`;

CREATE TABLE IF NOT EXISTS `users` (
    `id`            INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name`          CHAR(64) NOT NULL,
    `email`         CHAR(255) NOT NULL,
    `password`      CHAR(128) NOT NULL,
    `token`         CHAR(128) NOT NULL,
    `informations`  INT NOT NULL,
    `created_at`    DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at`    DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `del`           BOOLEAN DEFAULT FALSE
);

CREATE TABLE IF NOT EXISTS `informations` (
    `id`            INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `height`        FLOAT DEFAULT NULL COMMENT '身長',
    `weight`        FLOAT DEFAULT NULL COMMENT '体重',
    `created_at`    DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at`    DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `del`           BOOLEAN DEFAULT FALSE
);
