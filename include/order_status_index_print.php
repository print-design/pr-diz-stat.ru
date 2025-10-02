<?php
if(!empty($status_id)) {
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
    
    echo ORDER_STATUS_NAMES[$status_id];
    
    if(in_array($status_id, ORDER_STATUSES_WITH_METERS)) {
        echo "<div style='font-size: smaller;'><span class='text-nowrap'>".rtrim(rtrim(DisplayNumber(floatval($result_cut), 2), '0'), ',')."</span> из <span class='text-nowrap'>".DisplayNumber(floatval(empty($quantity_sum) ? $quantity : $quantity_sum), 0)."</span> ".($unit == KG ? "кг" : "шт")."</div>";
    }
    elseif($status_id == ORDER_STATUS_CUT_REMOVED) {
        echo "<div style='font-size: smaller;'>".$this->edition['status_comment']."</div>";
    }
}
?>