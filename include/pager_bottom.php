<?php
const LINKS_VISIBLE = 6;

$pager_pages_count = ceil($pager_total_count / $pager_take);
$pager_get_params = filter_input_array(INPUT_GET);

if($pager_pages_count > 1) {
?>
<nav aria-label="Page navigation example">
    <ul class="pagination">
        <?php
        if($pager_page > 1) {
            $pager_get_params[PAGE] = $pager_page - 1;
        ?>
        <li class="page-item">
            <a class="page-link" href="?<?= http_build_query($pager_get_params) ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
                <span class="sr-only">Previous</span>
            </a>
        </li>
        <?php
        }
        $max_head_page = LINKS_VISIBLE + 1;
        $min_tail_page = $pager_pages_count - LINKS_VISIBLE;
        
        if($pager_pages_count <= LINKS_VISIBLE + 2) {
            for($i = 1; $i <= $pager_pages_count; $i++) {
                $pager_get_params[PAGE] = $i;
                $disabled = "";
                if($i == $pager_page) $disabled = " disabled";
                echo "<li class='page-item".$disabled."'><a class='page-link' href='?".http_build_query($pager_get_params)."'>".$i."</a></li>";
            }
        }
        elseif ($pager_page <= $max_head_page - 2) {    
            for($i = 1; $i <= $max_head_page; $i++) {
                $pager_get_params[PAGE] = $i;
                $disabled = "";
                if($i == $pager_page) $disabled = " disabled";
                echo "<li class='page-item".$disabled."'><a class='page-link' href='?".http_build_query($pager_get_params)."'>".$i."</a></li>";
            }
            
            echo "<li class='page-item disabled'><a class='page-link' href='javascript: void(0);'>...</a></li>";
            $pager_get_params[PAGE] = $pager_pages_count;
            echo "<li class='page-item'><a class='page-link' href='?". http_build_query($pager_get_params)."'>".$pager_pages_count."</a>";
        }
        elseif ($pager_page >= $min_tail_page + 2) {
            $pager_get_params[PAGE] = 1;
            echo "<li class='page-item'><a class='page-link' href='?". http_build_query($pager_get_params)."'>1</a>";
            echo "<li class='page-item disabled'><a class='page-link' href='javascript: void(0);'>...</a></li>";
            for($i = $min_tail_page; $i <= $pager_pages_count; $i++) {
                $pager_get_params[PAGE] = $i;
                $disabled = "";
                if($i == $pager_page) $disabled = " disabled";
                echo "<li class='page-item".$disabled."'><a class='page-link' href='?".http_build_query($pager_get_params)."'>".$i."</a></li>";
            }
        }
        else {
            $pager_get_params[PAGE] = 1;
            echo "<li class='page-item'><a class='page-link' href='?". http_build_query($pager_get_params)."'>1</a>";
            echo "<li class='page-item disabled'><a class='page-link' href='javascript: void(0);'>...</a></li>";
            
            $floor = floor(LINKS_VISIBLE / 2);
            $min_page = $pager_page - $floor;
            $max_page = $pager_page + $floor;
            for($i = $min_page; $i <= $max_page; $i++) {
                $pager_get_params[PAGE] = $i;
                $disabled = "";
                if($i == $pager_page) $disabled = " disabled";
                echo "<li class='page-item".$disabled."'><a class='page-link' href='?".http_build_query($pager_get_params)."'>".$i."</a></li>";
            }
            
            echo "<li class='page-item disabled'><a class='page-link' href='javascript: void(0);'>...</a></li>";
            $pager_get_params[PAGE] = $pager_pages_count;
            echo "<li class='page-item'><a class='page-link' href='?". http_build_query($pager_get_params)."'>".$pager_pages_count."</a>";
        }
        if($pager_page < $pager_pages_count) {
            $pager_get_params[PAGE] = $pager_page + 1;
        ?>
        <li class="page-item">
            <a class="page-link" href="?<?= http_build_query($pager_get_params) ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
                <span class="sr-only">Next</span>
            </a>
        </li>
        <?php
        }
        ?>
    </ul>
</nav>
<?php
}
?>