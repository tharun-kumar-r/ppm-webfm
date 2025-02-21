SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- Table: company_info
CREATE TABLE `company_info` (
  `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `maps_link` VARCHAR(300) DEFAULT NULL,
  `address` VARCHAR(350) NOT NULL,
  `email` VARCHAR(30) NOT NULL,
  `email_optional` VARCHAR(30) DEFAULT NULL,
  `phone` VARCHAR(15) NOT NULL,
  `phone_optional` VARCHAR(15) DEFAULT NULL,
  `workhours` VARCHAR(250) DEFAULT NULL,
  `small_description` VARCHAR(800) DEFAULT NULL,
  `social` TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: metadata
CREATE TABLE `metadata` (
  `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `page_url` VARCHAR(255) NOT NULL UNIQUE,
  `meta_description` TEXT DEFAULT NULL,
  `meta_keywords` TEXT DEFAULT NULL,
  `og_image` VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: users
CREATE TABLE `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `uid` VARCHAR(20) NOT NULL UNIQUE,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `profile` VARCHAR(255) DEFAULT NULL,
  `type` ENUM('admin', 'user', 'worker') DEFAULT 'user',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: token
CREATE TABLE `token` (
  `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `uid` VARCHAR(20) NOT NULL,
  `token_id` VARCHAR(255) NOT NULL UNIQUE,
  `valid_till` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE PROCEDURE `GetCompanyAndMetadata` (IN `company_id` INT, IN `page_url_param` VARCHAR(255))
BEGIN
    SELECT 
        c.*,
        m.page_url, 
        m.meta_description, 
        m.meta_keywords, 
        m.og_image, 
        IF(m.page_url IS NOT NULL, 1, 0) AS metadata_exists
    FROM company_info c
    LEFT JOIN metadata m ON m.page_url = page_url_param
    WHERE c.id = company_id
    LIMIT 1;
END

