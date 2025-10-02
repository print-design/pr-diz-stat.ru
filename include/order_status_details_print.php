<div id="status">
    <i class="<?=ORDER_STATUS_ICONS[$calculation->status_id] ?>"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=ORDER_STATUS_NAMES[$calculation->status_id] ?>
    <?php
    if(in_array($calculation->status_id, ORDER_STATUSES_WITH_METERS)) {
        $result_cut = 0;
        
        if($calculation->unit == KG) {
            $weight_cut = 0;
            
            $sql = "select sum(weight) from calculation_take_stream where calculation_take_id in (select id from calculation_take where calculation_id = $id)";
            $fetcher = new Fetcher($sql);
            if($row = $fetcher->Fetch()) {
                $weight_cut = $row[0];
            }
            
            $sql = "select sum(weight) from calculation_not_take_stream where calculation_stream_id in (select id from calculation_stream where calculation_id = $id)";
            $fetcher = new Fetcher($sql);
            if($row = $fetcher->Fetch()) {
                $weight_cut += $row[0];
            }
            
            $result_cut = rtrim(rtrim(DisplayNumber(floatval($weight_cut), 2), '0'), ',');
        }
        else {
            $length_cut = 0;
            
            $sql = "select sum(length) from calculation_take_stream where calculation_take_id in (select id from calculation_take where calculation_id = $id)";
            $fetcher = new Fetcher($sql);
            if($row = $fetcher->Fetch()) {
                $length_cut = $row[0];
            }
            
            $sql = "select sum(length) from calculation_not_take_stream where calculation_stream_id in (select id from calculation_stream where calculation_id = $id)";
            $fetcher = new Fetcher($sql);
            if($row = $fetcher->Fetch()) {
                $length_cut += $row[0];
            }
            
            $result_cut = DisplayNumber(floor($length_cut * $calculation->number_in_meter), 0);
        }

        echo " $result_cut из ".DisplayNumber(intval($calculation->quantity), 0)." ".($calculation->unit == KG ? "кг" : "шт");
    }
                                
    if($calculation->status_id == ORDER_STATUS_CUT_REMOVED) {
        echo ": ".$calculation->status_comment;
    }
    ?>
</div>