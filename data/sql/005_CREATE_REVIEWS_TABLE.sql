
CREATE TABLE IF NOT EXISTS `Reviews`(
    `id` int AUTO_INCREMENT PRIMARY KEY,
    `product_id` int,
    `productname` varchar(30), 
    `user_name` VARCHAR(30) NOT NULL UNIQUE,
    `user_id` int,
    `rating` int,
    `comment` text,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `modified` TIMESTAMP DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
    FOREIGN KEY (user_name) REFERENCES Users(`username`),
    FOREIGN KEY (user_id) REFERENCES Users(`id`),
    FOREIGN KEY (`product_id`) REFERENCES Products(`id`),
    FOREIGN KEY (`productname`) REFERENCES Products(`name`),
    UNIQUE KEY (`user_name`, `product_id`)
)
