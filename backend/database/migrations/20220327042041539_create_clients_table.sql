CREATE TABLE `clients` (
    `id` VARCHAR (32) NOT NULL DEFAULT '' PRIMARY KEY,
    `secret` VARCHAR (64) NOT NULL DEFAULT '' UNIQUE KEY,
    `app_name` VARCHAR (20) NOT NULL DEFAULT '',
    `email` VARCHAR (256) NOT NULL DEFAULT '',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);