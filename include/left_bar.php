<?php
$php_self = $_SERVER['PHP_SELF'];
$substrings = mb_split("/", $php_self);
$count = count($substrings);
$folder = '';
$file = '';

if($count > 1) {
    $folder = $substrings[$count - 2];
    $file = $substrings[$count - 1];
}

$zakaz_class = '';
$sklad_class = '';
$plan_class = '';
$cut_class = '';
$pack_class = '';
$admin_class = '';
$reclamation_class = '';

if($folder == "calculation" || $folder == "techmap" || $folder == "schedule") {
    $zakaz_class = " active";
}
elseif($folder == "pallet" || $folder == "roll" || $folder == "cut_source" || $folder == "utilized") {
    $sklad_class = " active";
}
elseif($folder == "plan") {
    $plan_class = " active";
}
elseif ($folder == "cut") {
    $cut_class = " active";
}
elseif($folder == "pack") {
    $pack_class = " active";
}
elseif($folder == "user" || $folder == "supplier" || $folder == 'admin') {
    $admin_class = " active";
}

?>
<div id="left_bar">
    <a href="<?=APPLICATION ?>/" class="left_bar_item logo ui_tooltip right" title="На главную"><img src="<?=APPLICATION ?>/images/logo.svg" /></a>
    <?php
    // Расчёты
    if(IsInRole(array(ROLE_NAMES[ROLE_TECHNOLOGIST], ROLE_NAMES[ROLE_MANAGER]))):
    ?>
    <a href="<?=APPLICATION ?>/calculation/<?= BuildQueryAddRemoveArray("status", ORDER_STATUS_SHIPPED, array("page", "order")) ?>" class="left_bar_item ui_tooltip right<?=$zakaz_class ?>" title="Заказы"><img src="<?=APPLICATION ?>/images/nav_clock.svg" /></a>
    <?php
    endif;
    // Склад
    if(IsInRole(array(ROLE_NAMES[ROLE_TECHNOLOGIST], ROLE_NAMES[ROLE_STOREKEEPER], ROLE_NAMES[ROLE_MANAGER]))):
    ?>
    <a href="<?=APPLICATION ?>/roll/" class="left_bar_item ui_tooltip right<?=$sklad_class ?>" title="Склад"><img src="<?=APPLICATION ?>/images/nav_sklad.svg" /></a>
    <?php
    endif;
    // План
    if(IsInRole(array(ROLE_NAMES[ROLE_TECHNOLOGIST], ROLE_NAMES[ROLE_MANAGER], ROLE_NAMES[ROLE_SCHEDULER], ROLE_NAMES[ROLE_LAM_HEAD], ROLE_NAMES[ROLE_FLEXOPRINT_HEAD], ROLE_NAMES[ROLE_STOREKEEPER], ROLE_NAMES[ROLE_PACKER], ROLE_NAMES[ROLE_COLORIST]))):
    ?>
    <a href="<?=APPLICATION ?>/plan/" class="left_bar_item ui_tooltip right<?=$plan_class ?>" title="План"><img src="<?=APPLICATION ?>/images/nav_grafik.svg" /></a>
    <?php
    endif;
    
    /*-----------------*/
    /*ВРЕМЕННО*/
    if(GetUserId() == CUTTER_SOMA):
    ?>
    <a href="<?=APPLICATION ?>/plan/<?= BuildQueryAddRemoveArray("work_id", WORK_CUTTING, array("page", "order")) ?>" class="left_bar_item ui_tooltip right<?=$plan_class ?>" title="План"><img src="<?=APPLICATION ?>/images/nav_grafik.svg" /></a>
    <?php
    endif;
    /*ВРЕМЕННО*/
    
    // Резка
    if(IsInRole(CUTTER_USERS) || IsInRole(array(ROLE_NAMES[ROLE_TECHNOLOGIST], ROLE_NAMES[ROLE_LAM_HEAD]))):
    ?>
    <a href="<?=APPLICATION ?>/cut/<?= IsInRole(CUTTER_USERS) ? "" : "?machine_id=".CUTTER_1 ?>" class="left_bar_item ui_tooltip right<?=$cut_class ?>" title="Резка"><img src="<?=APPLICATION ?>/images/icons/factory.svg" /></a>
    <?php
    endif;
    // Упаковка
    if(IsInRole(array(ROLE_NAMES[ROLE_TECHNOLOGIST], ROLE_NAMES[ROLE_PACKER]))):
    ?>
    <a href="<?=APPLICATION ?>/pack/" class="left_bar_item ui_tooltip right<?=$pack_class ?>" title="Упаковка"><img src="<?=APPLICATION ?>/images/icons/loader_machine.svg" /></a>
    <?php
    endif;
    // Админка
    if(IsInRole(ROLE_NAMES[ROLE_TECHNOLOGIST])):
    ?>
    <a href="<?=APPLICATION ?>/user/" class="left_bar_item ui_tooltip right<?=$admin_class ?>" title="Админка"><img src="<?=APPLICATION ?>/images/nav_admin.svg" /></a>
    <?php
    elseif(IsInRole(array(ROLE_NAMES[ROLE_SCHEDULER], ROLE_NAMES[ROLE_LAM_HEAD]))):
    ?>
    <a href="<?=APPLICATION ?>/admin/plan_employees.php" class="left_bar_item ui_tooltip right<?=$admin_class ?>" title="Админка"><img src="<?=APPLICATION ?>/images/nav_admin.svg" /></a>
    <?php
    endif;
    ?>
    
    <!-- Старший менеджер может редактировать константы -->
    <?php if(IsInRole(ROLE_NAMES[ROLE_MANAGER_SENIOR])): ?>
    <a href="<?=APPLICATION ?>/supplier/film.php" class="left_bar_item ui_tooltip right<?=$admin_class ?>" title="Админка"><img src="<?=APPLICATION ?>/images/nav_admin.svg" /></a>
    <?php endif; ?>
</div>