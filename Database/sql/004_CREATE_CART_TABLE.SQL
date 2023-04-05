/*
CREATE TABLE IF NOT EXISTS `Cart`(
    `id` int AUTO_INCREMENT,
    `quantity` int,
    `product_id` int,
    `unit_price` int,
    `user_id` int,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `modified` TIMESTAMP DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
    check (`quantity`> 0),
    check (`unit_price` >= 0), 
    PRIMARY  KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES Users(`id`),
    FOREIGN KEY (`product_id`) REFERENCES Products(`id`),
    UNIQUE KEY (`user_id`, `product_id`)
)
*/