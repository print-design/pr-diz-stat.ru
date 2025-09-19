<?php
define('PAGE', 'page');

$pager_total_count = 0;
$pager_page = 1;

if(null !== filter_input(INPUT_GET, PAGE) && is_numeric(filter_input(INPUT_GET, PAGE))) {
    $pager_page = filter_input(INPUT_GET, PAGE);
}

if($pager_page < 1) {
    $pager_page = 1;
}

$pager_take = 50;
$pager_skip = $pager_take * ($pager_page - 1);
?>