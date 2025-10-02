<?php
if(!empty($status_id)):
    $result_cut = 0;

if($unit == KG) {
    $result_cut = $weight_cut;
}
elseif(empty ($quantity_sum)) {
    $number_in_meter = 0;
    if($length > 0) {
        $number_in_meter = 1 / $length * 1000;
    }
    $result_cut = floor($length_cut * $number_in_meter);
}
else {
    $number_in_meter = 0;
    if($length > 0 && $raport > 0) {
        $number_in_meter = floor($raport / ($length + $gap_raport)) / $raport * 1000;
    }
    $result_cut = floor($length_cut * $number_in_meter);
}
?>
<i class="fas fa-circle" style="color: <?=ORDER_STATUS_COLORS[$status_id] ?>;"></i>&nbsp;&nbsp;<?=ORDER_STATUS_NAMES[$status_id] ?>
<?php
if(in_array($status_id, ORDER_STATUSES_WITH_METERS)) {
    echo "<div style='font-size: smaller;' class='text-nowrap'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".rtrim(rtrim(DisplayNumber(floatval($result_cut), 2), '0'), ',')." из ".DisplayNumber(floatval(empty($quantity_sum) ? $quantity : $quantity_sum), 0).' '.($unit == KG ? "кг" : "шт")."</div>";
}
elseif($status_id == ORDER_STATUS_CUT_REMOVED) {
    echo "<div style='font-size: smaller;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$status_comment."</div>";
}
endif;
?>