-- =====================================================
-- Mammoth Self Store - Admin Panel Database Setup
-- =====================================================
-- Run this SQL in your mammoths_checkout_db database

-- Step 1: Add new columns to existing tbl_unit_specials table
-- (Only add if they don't exist)
ALTER TABLE `tbl_unit_specials` 
ADD COLUMN IF NOT EXISTS `free_months` INT(11) DEFAULT 0 COMMENT 'Number of free months (Get Y)',
ADD COLUMN IF NOT EXISTS `months_to_pay` INT(11) DEFAULT 0 COMMENT 'Number of months to pay (Buy X)',
ADD COLUMN IF NOT EXISTS `is_active` TINYINT(1) DEFAULT 1 COMMENT 'Active/Inactive status';

-- Step 2: Create mapping table for specials to locations
CREATE TABLE IF NOT EXISTS `tbl_unit_specials_locations` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `special_id` INT(11) NOT NULL,
  `location_id` INT(11) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_special_id` (`special_id`),
  KEY `idx_location_id` (`location_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Step 3: Create mapping table for specials to sizes
CREATE TABLE IF NOT EXISTS `tbl_unit_specials_sizes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `special_id` INT(11) NOT NULL,
  `size_key` VARCHAR(50) NOT NULL COMMENT 'e.g. 10x10, 6x3, parking',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_special_id` (`special_id`),
  KEY `idx_size_key` (`size_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Step 4: Create unit types table
CREATE TABLE IF NOT EXISTS `tbl_unit_types` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL,
  `sort_order` INT(11) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default unit types
INSERT INTO `tbl_unit_types` (`name`, `slug`, `sort_order`) VALUES
('Ground Floor', 'ground_floor', 1),
('Upper Level', 'upper_level', 2),
('Drive Up', 'drive_up', 3),
('Parking', 'parking', 4)
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- Step 5: Create mapping table for specials to types
CREATE TABLE IF NOT EXISTS `tbl_unit_specials_types` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `special_id` INT(11) NOT NULL,
  `type_id` INT(11) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_special_id` (`special_id`),
  KEY `idx_type_id` (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Step 6: Create unit fit filters table
CREATE TABLE IF NOT EXISTS `tbl_unit_fit_filters` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL,
  `sort_order` INT(11) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default fit filters
INSERT INTO `tbl_unit_fit_filters` (`name`, `slug`, `sort_order`) VALUES
('Small', 'small', 1),
('Medium', 'medium', 2),
('Large', 'large', 3),
('Extra Large', 'extra_large', 4),
('1 Bedroom', '1_bedroom', 5),
('2 Bedroom', '2_bedroom', 6),
('3 Bedroom', '3_bedroom', 7),
('4 Bedroom', '4_bedroom', 8),
('Large 4 Bedroom', 'large_4_bed', 9),
('Studio', 'studio', 10),
('Vehicle', 'vehicle', 11)
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- Step 7: Create unit size descriptions table
CREATE TABLE IF NOT EXISTS `tbl_unit_size_descriptions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `size_key` VARCHAR(50) NOT NULL COMMENT 'e.g. 10x10, 6x3',
  `description` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_size_key` (`size_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Step 8: Create mapping table for size descriptions to fit filters
CREATE TABLE IF NOT EXISTS `tbl_unit_size_fit_filters` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `description_id` INT(11) NOT NULL,
  `filter_id` INT(11) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_description_id` (`description_id`),
  KEY `idx_filter_id` (`filter_id`),
  UNIQUE KEY `unique_desc_filter` (`description_id`, `filter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Migration Complete
-- =====================================================
