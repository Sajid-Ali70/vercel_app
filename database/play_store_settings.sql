-- Play Store Settings Table
CREATE TABLE IF NOT EXISTS `app_settings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  -- App Identity & Badges
  `app_name` varchar(255) NOT NULL,
  `developer` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL, -- Contains Ads / In-app Purchases Tag
  `app_icon` varchar(255) DEFAULT NULL,

  -- Ratings & Metrics
  `rating_score` decimal(2,1) DEFAULT '4.8',
  `reviews_count` varchar(50) DEFAULT '1M reviews',
  `downloads_count` varchar(50) DEFAULT '500M+',
  `content_rating` varchar(100) DEFAULT 'Rated for 3+',

  -- About This App & Release Notes
  `updated_date` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `release_notes` text DEFAULT NULL,

  -- App Screenshots (JSON array of URLs)
  `screenshots` text DEFAULT NULL,

  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default settings matching the image
INSERT INTO `app_settings` (`id`, `app_name`, `developer`, `category`, `tags`, `app_icon`, `rating_score`, `reviews_count`, `downloads_count`, `content_rating`, `updated_date`, `description`, `release_notes`, `screenshots`, `created_at`, `updated_at`)
VALUES (1,
        'imo video calls and chat',
        'imo.im',
        'Communication',
        'Contains ads · In-app purchases',
        '/imo_files/imo.50ad88b6.png',
        4.8,
        '1M reviews',
        '500M+',
        'Rated for 3+',
        'Aug 14, 2023',
        'imo is a free, simple, and faster video calling & instant messaging app. Send text or voice messages or video call with your friends and family easily and quickly, even with a poor network signal.',
        '• Improved connection stability for HD video calls\n• Bug fixes and overall performance improvements\n• Enhanced instant message translation accuracy',
        '[]',
        NOW(),
        NOW())
ON DUPLICATE KEY UPDATE `app_name`=VALUES(`app_name`);
