<?php
/**
 * @param $query must have a column called "total"
 * @param array $params
 * @param int $per_page
 */
function paginate($total, $per_page = 10)
{
    global $page; //will be available after function is called
    try {
        $page = (int)htmlspecialchars($_GET['page'] ?? 1, ENT_QUOTES, 'UTF-8', false);
    } catch (Exception $e) {
        //safety for if page is received as not a number
        $page = 1;
    }
    $total_pages = ceil($total / $per_page);
    $offset = ($page - 1) * $per_page;
    return compact('total_pages', 'offset');
}