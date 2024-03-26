CREATE TABLE `goscieportalu` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `datetime` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `page` VARCHAR(255),
    `username` varchar(255) DEFAULT '-',
    `ip` VARCHAR(255),
    `userdevice` VARCHAR(255),
    `localization` VARCHAR(255),
    `coord` VARCHAR(255),
    `browser` VARCHAR(255),
    `display` VARCHAR(255),
    `viewport` VARCHAR(255),
    `colors` VARCHAR(3),
    `cookies` BOOLEAN,
    `java` BOOLEAN,
    `lang` VARCHAR(255)
);

CREATE TABLE `users` (
    `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `username` varchar(255) NOT NULL,
    `password` varchar(999) NOT NULL,
    `userGroup` varchar(255) NOT NULL DEFAULT 'user',
    `avatar` varchar(255) NOT NULL DEFAULT '_default_avatar.svg',
    `banner` varchar(255) DEFAULT '_default_banner.png',
    `color` varchar(7) DEFAULT '#FF9000',
    `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE break_ins (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `datetime` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `username` VARCHAR(255),
    `ip` VARCHAR(45) NOT NULL
);

CREATE VIEW visits AS
SELECT
    ip AS visitor_ip,
    COUNT(*) AS views
FROM
    goscieportalu
GROUP BY
    ip;

CREATE TABLE `blacklist` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(255) NOT NULL,
    `datetime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `reason` VARCHAR(10000) NOT NULL,
    `topic` INT NOT NULL
);