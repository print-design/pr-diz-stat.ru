<?php
include '../include/topscripts.php';

// Отображение статуса заказа
function ShowOrderStatus($status_id, $length_cut, $weight_cut, $quantity_sum, $quantity, $unit, $raport, $length, $gap_raport, $status_comment) {
    include '../include/order_status_index.php';
}

$calculation_id = filter_input(INPUT_GET, 'id');

$str_name = '';
$str_customer = '';
$str_nn = '';
$str_date = '';
$status_id = 0;
$status_comment = '';

$length_cut = 0;
$weight_cut = 0;
$quantity_sum = 0;
$quantity = 0;
$unit = '';
$raport = 0;
$length = 0;
$gap_raport = 0;

$sql = "select c.name, c.date, c.customer_id, c.quantity, c.unit, c.raport, c.length, cus.name customer, "
        . "(select count(id) from calculation where customer_id = c.customer_id and id <= c.id) num_for_customer, "
        . "(select status_id from calculation_status_history where calculation_id = c.id order by date desc limit 1) status_id, "
        . "(select comment from calculation_status_history where calculation_id = c.id order by date desc limit 1) status_comment, "
        . "ifnull((select sum(length) from calculation_take_stream where calculation_take_id in (select id from calculation_take where calculation_id = c.id)), 0) "
        . "+ ifnull((select sum(length) from calculation_not_take_stream where calculation_stream_id in (select id from calculation_stream where calculation_id = c.id)), 0) length_cut, "
        . "ifnull((select sum(weight) from calculation_take_stream where calculation_take_id in (select id from calculation_take where calculation_id = c.id)), 0) "
        . "+ ifnull((select sum(weight) from calculation_not_take_stream where calculation_stream_id in (select id from calculation_stream where calculation_id = c.id)), 0) weight_cut, "
        . "(select sum(quantity) from calculation_quantity where calculation_id = c.id) quantity_sum, "
        . "(select gap_raport from norm_gap where date <= c.date order by id desc limit 1) as gap_raport "
        . "from calculation c "
        . "inner join customer cus on c.customer_id = cus.id "
        . "where c.id = $calculation_id";
$fetcher = new Fetcher($sql);
if($row = $fetcher->Fetch()) {
    $str_name = $row['name'];
    $str_customer = $row['customer'];
    $str_nn = $row['customer_id'].'_'.$row['num_for_customer'];
    $str_date = DateTime::createFromFormat('Y-m-d H:i:s', $row['date'])->format('d.m.Y');
    $status_id = $row['status_id'];
    $status_comment = $row['status_comment'];
    
    $length_cut = $row['length_cut'];
    $weight_cut = $row['weight_cut'];
    $quantity_sum = $row['quantity_sum'];
    $quantity = $row['quantity'];
    $unit = $row['unit'];
    $raport = $row['raport'];
    $length = $row['length'];
    $gap_raport = $row['gap_raport'];
}
?>
<div style="font-size: large; font-weight: bold; margin-bottom: 3px;"><?=$str_name ?></div>
<div style="font-weight: bold; margin-bottom: 3px;"><?=$str_customer ?></div>
<div style="margin-bottom: 5px;">№<?=$str_nn ?> от <?=$str_date ?></div>
<div style="margin-bottom: 20px;"><?= ShowOrderStatus($status_id, $length_cut, $weight_cut, $quantity_sum, $quantity, $unit, $raport, $length, $gap_raport, $status_comment) ?></div>

<?php
$sql = "select status_id, date from calculation_status_history where calculation_id = $calculation_id order by date asc";
$fetcher = new Fetcher($sql);
while($row = $fetcher->Fetch()):
?>
<div>
    <div style="display: inline-block; text-align: center; width: 25px; line-height: 10px; vertical-align: text-top;">
        <i class="fas fa-circle" style="color: <?= ORDER_STATUS_COLORS[$row['status_id']] ?>;"></i><br />
        <div style="display: inline-block; border: solid 1px #AAAAAA; height: 25px; width: 2px;"></div>
    </div>
    <div style="display: inline-block; vertical-align: top;">
        <?= $row['status_id'] == $status_id ? '<strong>'.ORDER_STATUS_NAMES[$row['status_id']].'</strong>' : ORDER_STATUS_NAMES[$row['status_id']] ?>
        <div style="font-size: smaller;"><?=DateTime::createFromFormat('Y-m-d H:i:s', $row['date'])->format('d.m.Y, H:i') ?></div>
    </div>
</div>
<?php
if($status_id == ORDER_STATUS_SHIPPED && $row['status_id'] == ORDER_STATUS_SHIPPED):
?>
<div style="color: #AAAAAA;">
    <div style="display: inline-block; text-align: center; width: 25px; line-height: 10px; vertical-align: top;">
        <i class="far fa-circle"></i><br />
    </div>
</div>
<?php
endif;
endwhile;

$order_statuses = array_merge(ORDER_STATUSES_BEGIN, ORDER_STATUSES_NOT_IN_WORK, ORDER_STATUSES_IN_WORK, ORDER_STATUSES_IN_PRODUCTION, ORDER_STATUSES_END);
$order_statuses_dictionary = array();
$i = 0;

foreach($order_statuses as $order_status) {
    $order_statuses_dictionary[$order_status] = ++$i;
}

foreach($order_statuses as $order_status):
    if($order_statuses_dictionary[$order_status] > $order_statuses_dictionary[$status_id] 
            && !in_array($order_status, array(ORDER_STATUS_CUT_REMOVED, ORDER_STATUS_REJECTED, ORDER_STATUS_TRASH))):
?>
<div style="color: #AAAAAA;">
    <div style="display: inline-block; text-align: center; width: 25px; line-height: 10px; vertical-align: top;">
        <i class="far fa-circle"></i><br />
        <?php if($order_status != ORDER_STATUS_SHIPPED): ?>
        <div style="display: inline-block; border: solid 1px #AAAAAA; height: 10px; width: 2px;"></div>
        <?php endif; ?>
    </div>
    <div style="display: inline-block; vertical-align: top; line-height: 15px;"><?= ORDER_STATUS_NAMES[$order_status] ?></div>
</div>
<?php
endif;
endforeach;
?>
