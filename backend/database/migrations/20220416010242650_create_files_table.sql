CREATE TABLE `files` (
    `id` VARCHAR (32) NOT NULL PRIMARY KEY,
    `client_id` VARCHAR (32) NOT NULL,
    `name` VARCHAR (255) NOT NULL,
    `origin_mimetype` VARCHAR (255) NOT NULL,
    `thumbnail_mimetype` VARCHAR (255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_client_id`
        FOREIGN KEY (`client_id`)
        REFERENCES `clients` (`id`)
);
