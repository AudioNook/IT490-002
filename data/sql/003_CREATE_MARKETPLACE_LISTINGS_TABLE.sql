
CREATE TABLE IF NOT EXISTS `Marketplace_Listings` (
   id INT AUTO_INCREMENT PRIMARY KEY,
  user_collected_item_id INT NOT NULL,
  user_id INT NOT NULL,
  price DECIMAL(10, 2) NOT NULL,
  status ENUM('listed', 'sold', 'removed') NOT NULL DEFAULT 'listed',
  condition ENUM('P', 'F','G','VG','VG+','E','NM','M') NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_collected_item_id) REFERENCES User_Collected_Items(id),
  FOREIGN KEY (user_id) REFERENCES Users(id)
);