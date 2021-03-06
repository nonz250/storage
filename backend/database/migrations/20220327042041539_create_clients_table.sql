CREATE TABLE `clients` (
    `id` VARCHAR (32) NOT NULL PRIMARY KEY,
    `secret` VARCHAR (64) NOT NULL UNIQUE KEY,
    `app_name` VARCHAR (20) NOT NULL,
    `email` VARCHAR (255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
