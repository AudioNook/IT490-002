/* A table for all the genres on our site based on collections*/
CREATE IF NOT EXISTS TABLE Genres (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL
);