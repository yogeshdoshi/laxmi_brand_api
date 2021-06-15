ALTER TABLE `product_master` ADD `updated_at` DATETIME NULL AFTER `created_date`, ADD `deleted_at` DATETIME NULL AFTER `updated_at`, ADD `is_deleted` INT NULL AFTER `deleted_at`;

ALTER TABLE `product_master` ADD `populairty` INT NULL AFTER `is_deleted`;

ALTER TABLE `category_master` ADD `is_deleted` INT NULL DEFAULT NULL AFTER `is_active`, ADD `deleted_at` DATETIME NULL DEFAULT NULL AFTER `is_deleted`;

CREATE TABLE `faq_master` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `faq_title` int(11) DEFAULT NULL,
 `faq_answer` int(11) DEFAULT NULL,
 `is_active` int(11) DEFAULT 0,
 `craeted_at` datetime DEFAULT NULL,
 `updated_at` datetime DEFAULT NULL,
 `deleted_at` datetime DEFAULT NULL,
 `is_deleted` int(11) DEFAULT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
