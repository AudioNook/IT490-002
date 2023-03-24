
CREATE TABLE IF NOT EXISTS Marketplace_Items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_collected_item_id INT NOT NULL,
  item_condition VARCHAR(50),
  item_description TEXT,
  price DECIMAL(10, 2) NOT NULL,
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_collected_item_id) REFERENCES User_Collected_Items(id)
);