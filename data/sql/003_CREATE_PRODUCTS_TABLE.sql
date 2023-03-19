
/*
CREATE TABLE IF NOT EXISTS `Products` (
    `id` int AUTO_INCREMENT,
    `name` varchar(30), 
    `description` text,
    `category` text,
    `stock` int,
    `cost` int,
    `image` text,
    `visibility` BOOLEAN,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `modified` TIMESTAMP DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
    PRIMARY  KEY (`id`),
    check (`cost` >= 0), -- don't allow negative costs
    check (`stock` >= 0) -- don't allow negative stock
)
*/