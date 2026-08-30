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
  `developer_website` varchar(255) DEFAULT NULL,
  `developer_email` varchar(255) DEFAULT NULL,
  `developer_address` text DEFAULT NULL,
  `active_theme` varchar(50) DEFAULT 'playstore',
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert refined data
INSERT INTO `app_settings` (`id`, `app_name`, `developer`, `category`, `tags`, `app_icon`, `rating_score`, `reviews_count`, `downloads_count`, `content_rating`, `updated_date`, `description`, `release_notes`, `screenshots`, `apk_url`, `developer_website`, `developer_email`, `developer_address`, `active_theme`, `updated_at`)
VALUES (1,
        'Alfa Mobiles',
        'Alfa Mobiles Mart Karachi',
        'Shopping',
        'Contains ads · In-app purchases',
        '/asset/image/01_app_icon.png',
        '4.3',
        '1.9K reviews',
        '3K+',
        'Rated for 3+',
        'Aug 14, 2026',
        'Alfa Mobiles is Pakistan\'s trusted online mobile shopping app. Buy 100% original smartphones on easy monthly installments. No advance payment, 0% markup, no hidden charges. Enjoy a safe, simple & reliable shopping experience with Alfa Mobiles.\r\n\r\n• 100% Original Box Pack Mobiles\r\n• Easy Monthly Installments (12 to 60 Months)\r\n• No Advance Payment\r\n• 0% Markup - No Hidden Charges\r\n• Official Warranty\r\n• Nationwide Delivery (All Over Pakistan)\r\n• Dedicated Customer Support',
        '• New mobile booking system with easy installment plan\r\n• Improved app performance and faster browsing\r\n• Bug fixes and overall user experience improvements\r\n• Enhanced security for safe shopping',
        '["/asset/image/02_shop_front.png", "/asset/image/03_shop_interior.png", "/asset/image/04_shop_counter.png", "/asset/image/05_book_your_order_screen.png", "/asset/image/06_select_mobile_screen.png"]',
        '/apk/app-release.apk',
        'https://alfamobiles.pk',
        'alfamobilesmart.online@gmail.com',
        'Hafees Centre, Bangalore Town, Block A, Karachi, Pakistan 37350',
        'playstore',
        NOW())
ON DUPLICATE KEY UPDATE
    `app_name` = VALUES(`app_name`),
    `developer` = VALUES(`developer`),
    `active_theme` = VALUES(`active_theme`),
    `updated_at` = NOW();

-- Create Reviews Table
CREATE TABLE IF NOT EXISTS `app_reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reviewer_name` varchar(255) DEFAULT NULL,
  `rating` int(1) DEFAULT 5,
  `review_date` varchar(50) DEFAULT NULL,
  `review_text` text DEFAULT NULL,
  `avatar_letter` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert reviews
INSERT INTO `app_reviews` (`reviewer_name`, `rating`, `review_date`, `review_text`, `avatar_letter`) VALUES
('Abdul Rehman', 5, 'August 12, 2026', 'Alfa Mobiles se mobile book karna bohat asaan hai. Easy installment plan aur without advance payment best experience diya. Highly recommended!', 'A'),
('Muhammad Usman', 5, 'August 8, 2026', 'Original phones, official warranty aur 0% markup ke saath monthly installments. Alfa Mobiles trust aur service dono mein best hai.', 'M'),
('Sana Khan', 5, 'August 1, 2026', 'Maine iPhone 15 Pro booking ki. Delivery time par mili aur condition excellent thi. Alfa Mobiles ki customer support bohat achi hai.', 'S');
