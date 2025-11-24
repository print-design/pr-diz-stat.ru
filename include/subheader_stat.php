<?php
$customer_class = '';
$employee_print_class = '';
$employee_lamination_class = '';
$employee_cut_class = '';

if($file == "customer.php") {
    $customer_class = ' active';
}
else if($file == "employee_print.php") {
    $employee_print_class = ' active';
}
else if($file == "employee_lamination.php") {
    $employee_lamination_class = ' active';
}
else if($file == "employee_cut.php") {
    $employee_cut_class = ' active';
}
?>
<div class="text-nowrap nav2">
    <a href="customer.php" class="mr-4<?=$customer_class ?>">По заказчику</a>
    <a href="employee_print.php" class="mr-4<?=$employee_print_class ?>">По печатнику</a>
    <a href="employee_lamination.php" class="mr-4<?=$employee_lamination_class ?>">По ламинаторщику</a>
    <a href="employee_cut.php" class="mr-4<?=$employee_cut_class ?>">По резчику</a>
</div>
<hr />