/*
CREATE TABLE IF NOT EXISTS `Ratings`(
    `id` int AUTO_INCREMENT PRIMARY KEY,
    `product_id` int,
    `user_id` int,
    `rating` int,
    `comment` text,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `modified` TIMESTAMP DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(`id`),
    FOREIGN KEY (`product_id`) REFERENCES Products(`id`),
    UNIQUE KEY (`user_id`, `product_id`)
)
*/