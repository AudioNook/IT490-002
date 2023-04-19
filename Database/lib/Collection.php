<?php

namespace Database;

require_once(__DIR__ . "/db.php");

use Database\db;

/**
 * Class Collection: Database class for collection
 * @package Database
 */
class Collection extends db
{

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
    public function add_to_collection($user_id, $items)
    {
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
                return [
                    'code' => 400,
                    'message' => 'Unable to add itme to collection',
                    'success' => false
                ];
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
                    return [
                        'code' => 400,
                        'message' => 'Unable to add genre to collection table',
                        'success' => false
                    ];
                }
            }
        }

        return [
            'code' => 200,
            'message' => 'Successfully added item to collection',
            'success' => true
        ];
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
        $result = $this->exec_query($query, $params);
        if ($result !== false && !empty($result)) {
            return [
                'code' => 200,
                'message' => 'Sending user collection',
                'collection' => $result
            ];
        } else {
            return [
                'code' => 400,
                'message' => 'Unable to retrieve user collection',
            ];
        }
    }


    /**
     * Get a single item in a user's collection
     * @param $user_id
     * @param $collection_item_id
     * @return array
     */
    public function get_collection_item($user_id, $collection_item_id)
    {
        //$user_id = (int)$user_id;
        //$collection_item_id = (int)$collection_item_id;
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
            ':uid' => (int)$user_id,
            ':cid' => (int)$collection_item_id,
        ];
        var_dump($params);
        $result = $this->exec_query($query, $params);
        if ($result !== false && !empty($result)) {
            return [
                'code' => 200,
                'message' => 'Sending collection item data',
                'item' => $result
            ];
        } else {
            return [
                'code' => 400,
                'message' => 'Unable to retrieve collection item data',
            ];
        }
    }
    public function review_album($user_id, $collection_item_id, $review, $rating)
    {
        // Check if the user has already reviewed the album
        $query = "SELECT id FROM Album_Reviews WHERE user_id = :uid AND collection_item_id = :cid";
        $params = [
            ':uid' => (int)$user_id,
            ':cid' => (int)$collection_item_id
        ];
        $result = $this->exec_query($query, $params);
        if ($result !== false && !empty($result)) {
            // User has already reviewed the album
            return [
                'code' => 400,
                'message' => 'User has already reviewed this album',
                'success' => false
            ];
        }

        // Insert the new review
        $query = "INSERT INTO Album_Reviews (user_id, collection_item_id, review, rating) VALUES (:uid, :cid, :review, :rating)";
        $params = [
            ':uid' => (int)$user_id,
            ':cid' => (int)$collection_item_id,
            ':review' => htmlspecialchars($review),
            ':rating' => (int)$rating
        ];
        $result = $this->exec_query($query, $params);
        if ($result !== false && !empty($result)) {
            return [
                'code' => 200,
                'message' => 'Successfully added review',
                'success' => true
            ];
        } else {
            return [
                'code' => 400,
                'message' => 'Unable to add review',
                'success' => false
            ];
        }
    }

    public function get_album_reviews($collection_item_id)
    {
        $query = "SELECT
                    Album_Reviews.id,
                    Album_Reviews.review,
                    Album_Reviews.rating,
                    Album_Reviews.created,
                    Users.username
                  FROM Album_Reviews
                  JOIN Users ON Album_Reviews.user_id = Users.id
                  WHERE Album_Reviews.collection_item_id = :cid";
        $params = [
            ':cid' => (int)$collection_item_id
        ];
        $result = $this->exec_query($query, $params);
        if ($result !== false && !empty($result)) {
            return [
                'code' => 200,
                'message' => 'Sending album reviews',
                'reviews' => $result
            ];
        } else {
            return [
                'code' => 400,
                'message' => 'Unable to retrieve album reviews',
            ];
        }
    }
    public function get_avg_rating($collection_item_id) {
        $query = "SELECT AVG(rating) AS average_rating FROM Album_Reviews WHERE collection_item_id = :cid";
        $params = [
            ':cid' => (int)$collection_item_id
        ];
        $result = $this->exec_query($query, $params);
        if ($result !== false && !empty($result)) {
            $average_rating = round($result[0]['average_rating'], 1);
            return [
                'code' => 200,
                'message' => 'Average rating retrieved successfully',
                'average_rating' => $average_rating
            ];
        } else {
            return [
                'code' => 400,
                'message' => 'Unable to retrieve average rating',
                'average_rating' => null
            ];
        }
    }
    
}
