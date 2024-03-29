/* A table for all collected items on our site */
CREATE TABLE Collection_Items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  release_id INT NOT NULL,
  title VARCHAR(255) NOT NULL,
  cover_image VARCHAR(255),
  format VARCHAR(255),
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);