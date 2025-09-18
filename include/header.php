<?php
include 'left_bar.php';
?>
<div class="container-fluid header">
    <nav class="navbar navbar-expand-sm justify-content-end">
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