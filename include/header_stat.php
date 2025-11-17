<?php
include 'left_bar.php';

$php_self = $_SERVER['PHP_SELF'];
$substrings = mb_split("/", $php_self);
$count = count($substrings);
$folder = '';
$file = '';

if($count > 1) {
    $folder = $substrings[$count - 2];
    $file = $substrings[$count - 1];
}

$list_status = '';
$stat_status = '';

if($file == 'index.php') {
    $list_status = ' disabled';
}
elseif ($folder == 'stat') {
    $stat_status = ' disabled';
}
?>
<div class="container-fluid header">
    <nav class="navbar navbar-expand-sm justify-content-end">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link<?=$list_status ?>" href="<?= APPLICATION ?>/reclamation/">Список</a>
            </li>
            <li class="nav-item">
                <a class="nav-link<?=$stat_status ?>" href="<?= APPLICATION ?>/stat/customer.php">Статистика</a>
            </li>
        </ul>
        <?php
        if(file_exists('find.php')) {
            include 'find.php';
        }
        else {
            echo "<div class='ml-auto'></div>";
        }
        
        include 'header_right.php';
        ?>
    </nav>
</div>
<div id="topmost"></div>