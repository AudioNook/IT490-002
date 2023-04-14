<?php

namespace Database;

require_once(__DIR__ . "/db.php");

use Database\db;
/**
 * Class Products: Database class for Products
 * @package Database
 */

///////////////////////////////
//Products Class
///////////////////////////////

class Products extends db
{
    /**
     * Forums constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Get all products in Products table
     * @return array
     */
    public function get_products()
    {
        $table = 'Products';
        $products = $this->exec_query("SELECT id, name, category, stock, cost, image FROM $table");
        if ($products !== false && !empty($products)){
            return [
                'code'=>200,
                'message'=> 'Sending product data',
                'products' => $products
            ];
        }
        else{
            return [
                'code'=>400,
                'message'=> 'Unable to retrieve product data',
            ];
        }
    }
}



