<?php
if(LoggedIn()):
$find_class = " d-none";
$group_class = "";
$append_class = "";
$string_class = " d-none";
$placeholder = "Поиск по расчётам";
if(filter_input(INPUT_GET, "find") != '') {
    $find_class = " w-100";
    $group_class = " w-100";
    $append_class = " d-none";
    $string_class = "";
    $placeholder = "";
}
?>
<button type="button" class="btn btn-link mr-2 ml-auto<?=$append_class ?>" id="find-append" style="color: #EC3A7A; height: 35px; line-height: 0;"><i class="fas fa-search"></i></button>
<form class="form-inline ml-auto mr-3<?=$find_class ?>" method="get" id="find-form" action="<?=APPLICATION.'/calculation/' ?>">
    <div class="input-group input-group-sm<?=$group_class ?>" id="find-group">
        <input type="text" class="form-control" id="find" name="find" placeholder="<?=$placeholder ?>" />
        <?php if(filter_input(INPUT_GET, 'status') !== null): ?>
        <input type="hidden" name="status" value="<?= filter_input(INPUT_GET, 'status') ?>" />
        <?php endif; ?>
        <div class="input-group-append">
            <button type="submit" class="btn btn-outline-dark form-control" id="find-submit" style="border-top-right-radius: 5px; border-bottom-right-radius: 5px; height: 35px; width: 70px;">Найти</button>
        </div>
        <div class="position-absolute px-2 align-text-bottom <?=$string_class ?>" style="top: 3px; left: 5px; bottom: 3px; background-color: gray; color: white; border-radius: 4px; padding-top: .4rem;">
        <?= filter_input(INPUT_GET, "find") ?>
            &nbsp;&nbsp;
            <a href="<?=APPLICATION.'/calculation/'.(filter_input(INPUT_GET, 'status') === null ? "" : "?status=". filter_input(INPUT_GET, 'status')) ?>"><i class="fas fa-times" style="color: white;"></i></a>
        </div>
    </div>
</form>
<?php
else:
    echo "<div class='ml-auto'></div>";
endif;
?>