<?php
namespace Database;
require_once(__DIR__ . "/db.php");
use Database\db;

/**
 * Class Collection: Database class for collection
 * @package Database
 */
class Collection extends db{

    /**
     * Collection constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all items in a user's collection
     * @param $user_id
     * @return array
     */
    public function add_to_collection($user_id, $items) {
        $user_id = (int)htmlspecialchars($user_id);
        $collectionTable = 'Collection_Items';
        $userCollectionTable = 'User_Collected_Items';

        $collectionInsertQuery = "INSERT INTO $collectionTable (release_id, title, cover_image, format) VALUES(:rid, :title, :img, :format)";
        $userCollectionInsertQuery = "INSERT INTO $userCollectionTable (user_id, collection_item_id) VALUES (:uid, :cid)";

        foreach ($items as $item) {
            // Check if item is already in collection
            $collectionItemQuery = "SELECT id FROM $collectionTable WHERE release_id = :release_id";
            $collectionItemParams = [':release_id' => (int)$item['release_id']];
            $result = $this->exec_query($collectionItemQuery, $collectionItemParams);

            $collected_item_id = $result ? $result[0]['id'] : false;

            if (!$collected_item_id) {
                $params = [
                    ':rid' => (int)$item['release_id'],
                    ':title' => htmlspecialchars($item['title']),
                    ':img' => htmlspecialchars($item['cover_image'], ENT_QUOTES, 'UTF-8'),
                    ':format' => htmlspecialchars($item['format'])
                ];
                $this->exec_query($collectionInsertQuery, $params);
                $collected_item_id = $this->last_insert_id();
            }

            $userCollectionParams = [
                ':uid' => $user_id,
                ':cid' => $collected_item_id,
            ];
            $result = $this->exec_query($userCollectionInsertQuery, $userCollectionParams);
            if (!$result) {
                return false;
            }

            $genre_arr = strpos($item['genre'], ', ') !== false ? explode(",", $item['genre']) : [$item['genre']];
            foreach ($genre_arr as $genreName) {
                $genreQuery = "SELECT id FROM Genres WHERE name = :genre_name";
                $genreParams = [':genre_name' => $genreName];
                $result = $this->exec_query($genreQuery, $genreParams);

                $genreId = $result ? $result[0]['id'] : false;

                if (!$genreId) {
                    $insertGenreQuery = "INSERT INTO Genres (name) VALUES(:genre_name)";
                    $insertGenreParams = [':genre_name' => $genreName];
                    $this->exec_query($insertGenreQuery, $insertGenreParams);
                    $genreId = $this->last_insert_id();
                }

                $genreCollectionInsertQuery = "INSERT INTO Genres_Collection (collection_item_id, genre_id) VALUES (:cid, :gid)";
                $genreCollectionParams = [
                    ':cid' => $collected_item_id,
                    ':gid' => $genreId,
                ];
                $result = $this->exec_query($genreCollectionInsertQuery, $genreCollectionParams);
                if (!$result) {
                    return false;
                }
            }
        }

        return true;
    }
    /**
     * Get all items in a user's collection
     * @param $user_id
     * @return array
     */
    public function get_user_collection($user_id)
     {
        $user_id = (int)htmlspecialchars($user_id);
        $query = "SELECT Collection_Items.id, Collection_Items.title, Collection_Items.cover_image, Collection_Items.format
        FROM User_Collected_Items
        INNER JOIN Collection_Items ON User_Collected_Items.collection_item_id = Collection_Items.id
        WHERE User_Collected_Items.user_id = :uid;";
        $params = [':uid' => "$user_id"];
        return $this->exec_query($query, $params);
    }

    /**
     * Get a single item in a user's collection
     * @param $user_id
     * @param $collection_item_id
     * @return array
     */
    public function get_collection_item($user_id, $collection_item_id) 
    {
        $user_id = (int)$user_id;
        $collection_item_id = (int)$collection_item_id;
    
        $query = "SELECT
                    Collection_Items.id AS collection_item_id,
                    Collection_Items.title,
                    Collection_Items.cover_image,
                    Collection_Items.format,
                    GROUP_CONCAT(Genres.name SEPARATOR ', ') AS genres
                  FROM User_Collected_Items
                  JOIN Collection_Items ON User_Collected_Items.collection_item_id = Collection_Items.id
                  JOIN Genres_Collection ON Genres_Collection.collection_item_id = Collection_Items.id
                  JOIN Genres ON Genres_Collection.genre_id = Genres.id
                  WHERE User_Collected_Items.user_id = :uid
                    AND Collection_Items.id = :cid
                  GROUP BY Collection_Items.id
                  LIMIT 1;";
    
        $params = [
            ':uid' => $user_id,
            ':cid' => $collection_item_id,
        ];
    
        return $this->exec_query($query, $params);
    }

}