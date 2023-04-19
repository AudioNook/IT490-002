<?php
namespace Database;
require_once(__DIR__ . "/db.php");
use Database\db;

class Marketplace extends db
{

    public function __construct()
    {
        parent::__construct();
    }

    public function list_item($uid, $cid, $condition, $description, $price)
    {
        $uid = (int) $uid;
        $cid = (int) $cid;
        $condition;
        $selectQuery = "SELECT id FROM User_Collected_Items WHERE user_id = :user_id AND collection_item_id = :collection_item_id";
        $selectParams = [':user_id' => (int) $uid, ':collection_item_id' => (int) $cid];
        $result = $this->exec_query($selectQuery, $selectParams);
        if ($result) {
            $userCollectedItemId = $result[0]['id'];
        } else {
            return [
                'code'=>400,
                'message'=> 'Unable to find user collected item',
                'success' => false
            ];
        }

        $insertQuery = "INSERT INTO Marketplace_Items (user_collected_item_id, item_condition, item_description, price) VALUES (:user_collected_item_id, :condition, :description, :price)";
        $insertParams = [
            ':user_collected_item_id' => $userCollectedItemId,
            ':condition' => $condition,
            ':description' => $description,
            ':price' => $price
        ];
        $r = $this->exec_query($insertQuery, $insertParams);
        if ($r) {
            return [
                'code'=>200,
                'message'=> 'Successfully added item to marketplace',
                'success' => true
            ];
        }
        else
        {
            return [
                'code'=>400,
                'message'=> 'Unable to list item in marketplace',
                'success' => false
            ];
        }
        
    }

    function get_marketplace()
    {
        $query = "SELECT mi.id, mi.item_condition, mi.item_description, mi.price, mi.created, mi.modified,
        ci.title, ci.cover_image, ci.format,
        u.email, u.username,
        GROUP_CONCAT(g.name SEPARATOR ', ') AS genres
        FROM Marketplace_Items AS mi
        INNER JOIN User_Collected_Items AS uci ON mi.user_collected_item_id = uci.id
        INNER JOIN Users AS u ON uci.user_id = u.id
        INNER JOIN Collection_Items AS ci ON uci.collection_item_id = ci.id
        INNER JOIN Genres_Collection AS gc ON ci.id = gc.collection_item_id
        INNER JOIN Genres AS g ON gc.genre_id = g.id
        GROUP BY mi.id";
        $result = $this->exec_query($query);
        if ($result !== false && !empty($result)){
            return [
                'code'=>200,
                'message'=> 'Sending marketplace data',
                'marketplace_items' => $result
            ];
        }
        else{
            return [
                'code'=>400,
                'message'=> 'Unable to retrieve marketplace data',
            ];
        }
    }
    public function get_item_details($id)
    {
        $id = (int) $id;
        $query = "SELECT mi.id, mi.item_condition, mi.item_description, mi.price, mi.created, mi.modified,
        ci.title, ci.cover_image, ci.format,
        u.email, u.username,
        GROUP_CONCAT(g.name SEPARATOR ', ') AS genres
        FROM Marketplace_Items AS mi
        INNER JOIN User_Collected_Items AS uci ON mi.user_collected_item_id = uci.id
        INNER JOIN Users AS u ON uci.user_id = u.id
        INNER JOIN Collection_Items AS ci ON uci.collection_item_id = ci.id
        INNER JOIN Genres_Collection AS gc ON ci.id = gc.collection_item_id
        INNER JOIN Genres AS g ON gc.genre_id = g.id
        WHERE mi.id = :id
        GROUP BY mi.id";
        $params = [':id' => $id];
        $result = $this->exec_query($query, $params);
        if ($result !== false && !empty($result)){
            return [
                'code'=>200,
                'message'=> 'Sending marketplace data',
                'marketplace_item' => $result[0]
            ];
        }
        else{
            return [
                'code'=>400,
                'message'=> 'Unable to retrieve marketplace data',
            ];
        }
    }
}