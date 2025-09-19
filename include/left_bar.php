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

$reclamation_class = '';

if($folder == "reclamation") {
    $reclamation_class = " active";
}

?>
<div id="left_bar">
    <a href="<?=APPLICATION ?>/" class="left_bar_item logo ui_tooltip right" title="На главную"><img src="<?=APPLICATION ?>/images/logo.svg" /></a>
    <?php
    // Рекламации
    if(IsInRole(array(ROLE_NAMES[ROLE_TECHNOLOGIST], ROLE_NAMES[ROLE_MANAGER]))):
    ?>
    <a href="<?= APPLICATION ?>/reclamation/" class="left_bar_item ui_tooltip right<?=$reclamation_class ?>" title="Рекламации"><i class="fas fa-envelope-open-text" style="font-size: x-large;"></i></a>
    <?php endif; ?>
</div>