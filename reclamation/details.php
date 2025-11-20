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
$r_date = "";
$c_date = "";
$calculation_id = 0;
$calculation = "";
$customer_id = 0;
$customer = "";
$num_for_customer = 0;
$in_print = 0;
$in_lamination = 0;
$in_cut = 0;
$comment = "";

$sql = "select r.date r_date, r.calculation_id, c.date c_date, c.name calculation, c.customer_id, "
        . "(select count(id) from calculation where customer_id = c.customer_id and id <= c.id) as num_for_customer, "
        . "cus.name customer, r.in_print, r.in_lamination, r.in_cut, r.comment "
        . "from reclamation r "
        . "inner join calculation c on r.calculation_id = c.id "
        . "inner join customer cus on c.customer_id = cus.id "
        . "where r.id = $id";
$fetcher = new Fetcher($sql);
if($row = $fetcher->Fetch()) {
    $r_date = DateTime::createFromFormat('Y-m-d H:i:s', $row['r_date'])->format('d.m.Y');
    $c_date = DateTime::createFromFormat('Y-m-d H:i:s', $row['c_date'])->format('d.m.Y');
    $calculation_id = $row['calculation_id'];
    $calculation = $row['calculation'];
    $customer_id = $row['customer_id'];
    $customer = $row['customer'];
    $num_for_customer = $row['num_for_customer'];
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
        <style>
            .name {
                font-size: 26px;
                font-weight: bold;
                line-height: 45px;
            }
            
            .subtitle {
                font-weight: bold;
                font-size: 20px;
                line-height: 40px
            }
            
            .modal-content {
                border-radius: 20px;
            }
        </style>
    </head>
    <body>
        <?php
        include '../include/header_stat.php';
        ?>
        <div class="container-fluid">
            <?php
            if(!empty($error_message)) {
               echo "<div class='alert alert-danger'>$error_message</div>";
            }
            ?>
            <a class="btn btn-light backlink" href="<?=APPLICATION ?>/reclamation/<?= BuildQueryRemove('id') ?>">К списку</a>
            <div class="name"><?=$r_date ?></div>
            <h1><?= $calculation ?></h1>
            <div class="row">
                <div class="col-12 col-lg-4">
                    <div class="subtitle">№ заказа: <?=$customer_id.'-'.$num_for_customer ?> от <?=$c_date ?></div>
                    <div class="subtitle">Заказчик: <?=$customer ?></div>
                    <h2>Дефекты</h2>
                    <table class="table">
                        <tr>
                            <th>Тип</th>
                            <th>м/шт</th>
                            <th>%</th>
                        </tr>
                        <?php
                        $sql = "select defect_type, other_defect_type, quantity, unit, percent from reclamation_defect where reclamation_id = $id";
                        $fetcher = new Fetcher($sql);
                        while($row = $fetcher->Fetch()):
                        ?>
                        <tr>
                            <td><?= $row['defect_type'] == DEFECT_TYPE_OTHER ? $row['other_defect_type'] : (key_exists($row['defect_type'], DEFECT_TYPE_NAMES) ? DEFECT_TYPE_NAMES[$row['defect_type']] : $row['defect_type']) ?></td>
                            <td><?=$row['quantity'].' '. UNIT_NAMES[$row['unit']] ?></td>
                            <td><?= empty($row['percent']) ? '' : $row['percent'].'%' ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </table>
                    <h2>Локализация</h2>
                    <div class="d-flex justify-content-lg-start">
                        <div class="mr-4"><i class="far <?=$in_print ? "fa-check-square" : "fa-square" ?> mr-2"></i>на печати</div>
                        <div class="mr-4"><i class="far <?=$in_lamination ? "fa-check-square" : "fa-square" ?> mr-2"></i>на ламинации</div>
                        <div><i class="far <?=$in_cut ? "fa-check-square" : "fa-square" ?> mr-2"></i>на резке</div>
                    </div>
                    <br />
                    <p><?=$comment ?></p>
                </div>
                <div class="col-12 col-lg-4">
                    <?php
                    $sql = "select ped.date, ped.shift, ped.work_id, ped.machine_id, pe.last_name, pe.first_name "
                            . "from plan_edition ped, "
                            . "plan_workshift1 pw1 "
                            . "inner join plan_employee pe on pw1.employee1_id = pe.id "
                            . "where pw1.date = ped.date and pw1.shift = ped.shift and pw1.work_id = ped.work_id and pw1.machine_id = ped.machine_id "
                            . "and ped.calculation_id = $calculation_id";
                    $grabber = new Grabber($sql);
                    $editions = $grabber->result;
                    ?>
                    <h2>Печать</h2>
                    <table class="table">
                        <?php
                        foreach ($editions as $edition):
                        if($edition['work_id'] == WORK_PRINTING):
                        ?>
                        <tr>
                            <td><?=DateTime::createFromFormat('Y-m-d', $edition['date'])->format('d.m.Y') ?></td>
                            <td><?= SHIFT_NAMES[$edition['shift']] ?></td>
                            <td><?= PRINTER_NAMES[$edition['machine_id']] ?></td>
                            <td><?=$edition['last_name'].' '.$edition['first_name'] ?></td>
                        </tr>
                        <?php
                        endif;
                        endforeach;
                        ?>
                    </table>
                    <h2>Ламинация</h2>
                    <table class="table">
                        <?php
                        foreach ($editions as $edition):
                        if($edition['work_id'] == WORK_LAMINATION):
                        ?>
                        <tr>
                            <td><?=DateTime::createFromFormat('Y-m-d', $edition['date'])->format('d.m.Y') ?></td>
                            <td><?= SHIFT_NAMES[$edition['shift']] ?></td>
                            <td><?= LAMINATOR_NAMES[$edition['machine_id']] ?></td>
                            <td><?=$edition['last_name'].' '.$edition['first_name'] ?></td>
                        </tr>
                        <?php
                        endif;
                        endforeach;
                        ?>
                    </table>
                    <h2>Резка</h2>
                    <table class="table">
                        <?php
                        foreach($editions as $edition):
                        if($edition['work_id'] == WORK_CUTTING):
                        ?>
                        <tr>
                            <td><?=DateTime::createFromFormat('Y-m-d', $edition['date'])->format('d.m.Y') ?></td>
                            <td><?= SHIFT_NAMES[$edition['shift']] ?></td>
                            <td><?= CUTTER_NAMES[$edition['machine_id']] ?></td>
                            <td><?=$edition['last_name'].' '.$edition['first_name'] ?></td>
                        </tr>
                        <?php
                        endif;
                        endforeach;
                        ?>
                    </table>
                </div>
            </div>
        </div>
        <?php
        include '../include/footer.php';
        ?>
    </body>
</html>