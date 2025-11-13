<?php
require_once '../include/topscripts.php';

$current_time = new DateTime();
$current_hour = intval($current_time->format('G'));
$current_shift = 'day';
$working_current_time = clone $current_time;

if($current_hour > 19 && $current_hour < 24) {
    $current_shift = 'night';
}
elseif ($current_hour >= 0 && $current_hour < 8) {
    $current_shift = 'night';
    $working_current_time->modify("-1 day");
}

$sql = "select pe.last_name, pe.first_name "
        . "from plan_workshift1 pw inner join plan_employee pe on pw.employee1_id = pe.id "
        . "where date_format(pw.date, '%d-%m-%Y') = '".$working_current_time->format('d-m-Y')."' and pw.shift = '$current_shift' and pw.work_id = ".WORK_CUTTING.' and pw.machine_id = '. GetUserId();

$result = '';

$fetcher = new Fetcher($sql);

while($row = $fetcher->Fetch()) {
    $result .= $row['last_name'].' '.$row['first_name'];
}

if(empty($result)) {
    $result = "ВЫХОДНОЙ ДЕНЬ";
}

echo $result;
?>