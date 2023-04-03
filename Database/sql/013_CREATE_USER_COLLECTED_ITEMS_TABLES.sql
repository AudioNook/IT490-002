/* A table that maps the user to the collection item */
CREATE IF NOT EXISTS TABLE User_Collected_Items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  collection_item_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES Users(id),
  FOREIGN KEY (collection_item_id) REFERENCES Collection_Items(id),
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);