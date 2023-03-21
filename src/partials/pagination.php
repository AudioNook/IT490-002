<?php
if (!isset($total_pages)) {
    error_log("Note to Dev: The total_pages variable is undefined: danger");
    $total_pages = 0;
}
if (!isset($page)) {
    error_log("Note to Dev: The page variable is undefined: danger");
    $page = 1;
}
//$total_pages = ceil($total / $per_page);
//updates or inserts page into query string while persisting anything already present
function persistQueryString($page)
{
    //set the query param for easily building
    $_GET["page"] = $page;
    return http_build_query($_GET);
}
function check_apply_disabled_prev($page)
{
    echo $page < 1 ? "disabled" : "";
}
function check_apply_active($page, $i)
{
    echo ($page - 1) == $i ? "active" : "";
}
function check_apply_disabled_next($page)
{
    global $total_pages;
    echo ($page) >= $total_pages ? "disabled" : "";
}
?>

<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
        <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
            <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>" tabindex="-1">Previous</a>
        </li>
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>
        <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
            <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>">Next</a>
        </li>
    </ul>
</nav>