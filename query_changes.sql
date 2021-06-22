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


ALTER TABLE `faq_master` CHANGE `faq_title` `faq_title` VARCHAR(200) NULL DEFAULT NULL;

ALTER TABLE `faq_master` CHANGE `faq_answer` `faq_answer` VARCHAR(250) NULL DEFAULT NULL;

ALTER TABLE `faq_master` CHANGE `craeted_at` `created_at` DATETIME NULL DEFAULT NULL;

CREATE TABLE `offer_master` (
 `offerid` int(11) NOT NULL AUTO_INCREMENT,
 `offer_description` text DEFAULT NULL,
 `offer_product_id` int(11) DEFAULT NULL,
 `discount_amount` float DEFAULT NULL,
 `offer_status` int(11) DEFAULT NULL,
 `created_at` datetime DEFAULT NULL,
 `updated_at` datetime DEFAULT NULL,
 `deleted_at` datetime DEFAULT NULL,
 `is_deleted` int(11) DEFAULT NULL,
 PRIMARY KEY (`offerid`)
);

CREATE TABLE `image_master` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `image_for` tinyint(4) DEFAULT NULL COMMENT '1=>product, 2=>category',
 `image_path` varchar(250) DEFAULT NULL,
 `image_type` varchar(50) DEFAULT NULL,
 `created_at` datetime DEFAULT NULL,
 `updated_at` datetime DEFAULT NULL,
 `deleted_at` datetime DEFAULT NULL,
 `is_deleted` tinyint(4) DEFAULT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4

ALTER TABLE `image_master` ADD `reference_id` INT NULL AFTER `image_for`;

