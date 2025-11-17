<?php
$customer_class = '';

if($file == "customer.php") {
    $customer_class = ' active';
}
?>
<div class="text-nowrap nav2">
    <a href="customer.php" class="mr-4<?=$customer_class ?>">По заказчику</a>
</div>
<hr />