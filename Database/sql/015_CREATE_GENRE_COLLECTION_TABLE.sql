/* Table to map the genre to the collection item */
CREATE TABLE IF NOT EXISTS Genres_Collection (
  collection_item_id INT NOT NULL,
  genre_id INT NOT NULL,
  PRIMARY KEY (collection_item_id, genre_id),
  FOREIGN KEY (collection_item_id) REFERENCES Collection_Items(id),
  FOREIGN KEY (genre_id) REFERENCES Genres(id)
);