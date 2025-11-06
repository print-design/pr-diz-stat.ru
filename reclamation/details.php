<?php
include '../include/topscripts.php';

// Если не задано значение id, перенаправляем на список
$id = filter_input(INPUT_GET, 'id');
if(empty($id)) {
    header('Location: '.APPLICATION.'/reclamation/');
}

// Авторизация
if(!IsInRole(array(ROLE_NAMES[ROLE_TECHNOLOGIST], ROLE_NAMES[ROLE_MANAGER], ROLE_NAMES[ROLE_MANAGER_SENIOR]))) {
    header('Location: '.APPLICATION.'/unauthorized.php');
}

// Получение объекта
$date = "";
$calculation_id = 0;
$calculation = "";
$defect_type = 0;
$quantity = 0;
$unit = "";
$percent = 0;
$in_print = 0;
$in_lamination = 0;
$in_cut = 0;
$comment = "";

$sql = "select r.date, r.calculation_id, c.name calculation, r.in_print, r.in_lamination, r.in_cut, r.comment "
        . "from reclamation r inner join calculation c on r.calculation_id = c.id "
        . "where r.id = $id";
$fetcher = new Fetcher($sql);
if($row = $fetcher->Fetch()) {
    $date = DateTime::createFromFormat('Y-m-d H:i:s', $row['date'])->format('d.m.Y H:i');
    $calculation_id = $row['calculation_id'];
    $calculation = $row['calculation'];
    $in_print = $row['in_print'];
    $in_lamination = $row['in_lamination'];
    $in_cut = $row['in_cut'];
    $comment = htmlentities($row['comment']);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <?php
        include '../include/head.php';
        ?>
    </head>
    <body>
        <?php
        include '../include/header.php';
        ?>
        <div class="container-fluid">
            <?php
            if(!empty($error_message)) {
               echo "<div class='alert alert-danger'>$error_message</div>";
            }
            ?>
            <a class="btn btn-light backlink" href="<?=APPLICATION ?>/reclamation/<?= BuildQueryRemove('id') ?>">К списку</a>
            <div><?=$date ?></div>
            <h1><?= $calculation ?></h1>
            <div><i class="far <?=$in_print ? "fa-check-square" : "fa-square" ?> mr-2"></i>на печати</div>
            <div><i class="far <?=$in_lamination ? "fa-check-square" : "fa-square" ?> mr-2"></i>на ламинации</div>
            <div><i class="far <?=$in_cut ? "fa-check-square" : "fa-square" ?> mr-2"></i>на резке</div>
            <br />
            <p><?=$comment ?></p>
        </div>
        <?php
        include '../include/footer.php';
        ?>
    </body>
</html>