-- Table structure for `app_settings`
CREATE TABLE IF NOT EXISTS `app_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_name` varchar(255) DEFAULT NULL,
  `developer` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `app_icon` text DEFAULT NULL,
  `rating_score` varchar(50) DEFAULT NULL,
  `reviews_count` varchar(50) DEFAULT NULL,
  `downloads_count` varchar(50) DEFAULT NULL,
  `content_rating` varchar(50) DEFAULT NULL,
  `updated_date` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `release_notes` text DEFAULT NULL,
  `screenshots` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `apk_url` varchar(255) DEFAULT '/apk/app-release.apk',
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for `admins`
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default admin record (password is 'admin123')
INSERT INTO `admins` (`username`, `password`, `updated_at`)
SELECT 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW()
WHERE NOT EXISTS (SELECT 1 FROM `admins` WHERE `username` = 'admin');
