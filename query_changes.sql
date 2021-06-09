ALTER TABLE `product_master` ADD `updated_at` DATETIME NULL AFTER `created_date`, ADD `deleted_at` DATETIME NULL AFTER `updated_at`, ADD `is_deleted` INT NULL AFTER `deleted_at`;

ALTER TABLE `product_master` ADD `populairty` INT NULL AFTER `is_deleted`;

ALTER TABLE `category_master` ADD `is_deleted` INT NULL DEFAULT NULL AFTER `is_active`, ADD `deleted_at` DATETIME NULL DEFAULT NULL AFTER `is_deleted`;
