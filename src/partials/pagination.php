<?php
if (!isset($total_pages)) {
    error_log("Note to Dev: The total_pages variable is undefined: danger");
    $total_pages = 0;
}
if (!isset($page)) {
    error_log("Note to Dev: The page variable is undefined: danger");
    $page = 1;
}
$total_pages = ceil($total_items / $per_page);
$visible_pages = 3; // Number of visible pages before and after the current page
$start_page = $page - $visible_pages;
$end_page = $page + $visible_pages;

if ($start_page < 1) {
    $start_page = 1;
}

if ($end_page > $total_pages) {
    $end_page = $total_pages;
}
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
    echo ($page) == $i ? "active" : "";
}
function check_apply_disabled_next($page)
{
    global $total_pages;
    echo ($page) >= $total_pages ? "disabled" : "";
}
?>

<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
        <li class="page-item <?php echo check_apply_disabled_prev($page); ?>">
            <a class="page-link" href="?<?php echo persistQueryString($page - 1); ?>" tabindex="-1">Previous</a>
        </li>
        <?php if ($start_page > 1) : ?>
            <li class="page-item">
                <a class="page-link" href="?<?php echo persistQueryString(1); ?>">1</a>
            </li>
            <li class="page-item disabled">
                <span class="page-link">...</span>
            </li>
        <?php endif; ?>

        <?php for ($i = $start_page; $i <= $end_page; $i++) : ?>
            <li class="page-item <?php echo check_apply_active($page, $i); ?>">
                <a class="page-link" href="?<?php echo persistQueryString($i); ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>

        <?php if ($end_page < $total_pages) : ?>
            <li class="page-item disabled">
                <span class="page-link">...</span>
            </li>
            <li class="page-item">
                <a class="page-link" href="?<?php echo persistQueryString($total_pages); ?>"><?php echo $total_pages; ?></a>
            </li>
        <?php endif; ?>
        <li class="page-item <?php echo check_apply_disabled_next($page); ?>">
            <a class="page-link" href="?<?php echo persistQueryString($page + 1); ?>">Next</a>
        </li>
    </ul>
</nav>