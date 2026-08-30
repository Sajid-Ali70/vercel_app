-- Create Reviews Table
CREATE TABLE IF NOT EXISTS `app_reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reviewer_name` varchar(255) DEFAULT NULL,
  `rating` int(1) DEFAULT 5,
  `review_date` varchar(50) DEFAULT NULL,
  `review_text` text DEFAULT NULL,
  `avatar_letter` varchar(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert initial reviews from images
INSERT INTO `app_reviews` (`reviewer_name`, `rating`, `review_date`, `review_text`, `avatar_letter`, `created_at`, `updated_at`) VALUES
('Abdul Rehman', 5, 'August 12, 2026', 'Alfa Mobiles se mobile book karna bohat asaan hai. Easy installment plan aur without advance payment best experience diya. Highly recommended!', 'A', NOW(), NOW()),
('Muhammad Usman', 5, 'August 8, 2026', 'Original phones, official warranty aur 0% markup ke saath monthly installments. Alfa Mobiles trust aur service dono mein best hai.', 'M', NOW(), NOW()),
('Sana Khan', 5, 'August 1, 2026', 'Maine iPhone 15 Pro booking ki. Delivery time par mili aur condition excellent thi. Alfa Mobiles ki customer support bohat achi hai.', 'S', NOW(), NOW());
