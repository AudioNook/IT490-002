/* Album Reviews*/
CREATE TABLE IF NOT EXISTS `Album_Reviews` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `collection_item_id` INT NOT NULL,
  `rating` INT NOT NULL,
  `review` TEXT,
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `Users`(`id`),
  FOREIGN KEY (`collection_item_id`) REFERENCES `Collection_Items`(`id`)
);
