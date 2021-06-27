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

ALTER TABLE `image_master` ADD `image_name` VARCHAR(250) NULL DEFAULT NULL AFTER `image_path`;
ALTER TABLE `order_master` ADD `updated_at` DATETIME NULL AFTER `order_created_date`;

ALTER TABLE `product_master` ADD `quantinty` INT NULL AFTER `populairty`;

ALTER TABLE `admin_users` CHANGE `password` `password` VARCHAR(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL;
ALTER TABLE `admin_users` ADD PRIMARY KEY(`userid`);
ALTER TABLE `admin_users` CHANGE `userid` `userid` INT NOT NULL AUTO_INCREMENT;
ALTER TABLE `product_master` CHANGE `created_date` `created_date` DATETIME NOT NULL;
ALTER TABLE `product_master` CHANGE `pdt_name` `pdt_name` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL, CHANGE `category_id` `category_id` INT(6) NULL, CHANGE `pdt_discount_display` `pdt_discount_display` INT(3) NULL, CHANGE `pdt_about` `pdt_about` VARCHAR(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL, CHANGE `pdt_storage_uses` `pdt_storage_uses` VARCHAR(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL, CHANGE `pdt_other_info` `pdt_other_info` VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL, CHANGE `is_active` `is_active` TINYINT(4) NULL, CHANGE `prdt_images` `prdt_images` VARCHAR(2500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL, CHANGE `created_date` `created_date` DATETIME NULL;