<?php
$customer_class = '';
$employee_print_class = '';

if($file == "customer.php") {
    $customer_class = ' active';
}
else if($file = "employee_print_class") {
    $employee_print_class = ' active';
}
?>
<div class="text-nowrap nav2">
    <a href="customer.php" class="mr-4<?=$customer_class ?>">По заказчику</a>
    <a href="employee_print.php" class="mr-4<?=$employee_print_class ?>">По печатнику</a>
</div>
<hr />