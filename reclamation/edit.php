<?php
include '../include/topscripts.php';

// Если не задано значение id, перенаправляем на список
$id = filter_input(INPUT_GET, 'id');
if(empty($id)) {
    header('Location: '.APPLICATION.'/reclamation/');
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
    
    if(filter_input(INPUT_POST, 'in_print') === null) {
        $in_print = $row['in_print'];
    }
    elseif(filter_input(INPUT_POST, 'in_print') == 'on') {
        $in_print = 1;
    }
    else {
        $in_print = 0;
    }
    
    if(filter_input(INPUT_POST, 'in_lamination') === null) {
        $in_lamination = $row['in_lamination'];
    }
    elseif(filter_input(INPUT_POST, 'in_lamination') == 'on') {
        $in_lamination = 1;
    }
    else {
        $in_lamination = 0;
    }
    
    if(filter_input(INPUT_POST, 'in_cut') === null) {
        $in_cut = $row['in_cut'];
    }
    elseif(filter_input(INPUT_POST, 'in_cut') == 'on') {
        $in_cut = 1;
    }
    else {
        $in_cut = 0;
    }
    
    $comment = filter_input(INPUT_POST, 'comment');
    if($comment === null && isset($row['comment'])) {
        $comment = $row['comment'];
    }
    $comment = htmlentities($comment);
    
    
    
    
    
    $lamination1_customers_material = 0; if(filter_input(INPUT_POST, 'lamination1_customers_material') == 'on') $lamination1_customers_material = 1;
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
            <a class="btn btn-light backlink" href="details.php<?= BuildQuery('id', $id) ?>">Назад</a>
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
                    <form method="post">
                        <div class="d-flex justify-content-lg-start">
                            <div class="form-check mr-4">
                                <label class="form-check-label text-nowrap mt-1 mb-4" style="line-height: 25px;">
                                    <?php $checked = $in_print == 1 ? " checked='checked'" : ""; ?>
                                    <input type="checkbox" class="form-check-input" id="in_print" name="in_print" value="on"<?=$checked ?> />на печати
                                </label>
                            </div>
                            <div class="form-check mr-4">
                                <label class="form-check-label text-nowrap mt-1 mb-4" style="line-height: 25px;">
                                    <?php $checked = $in_lamination == 1 ? " checked='checked'" : "" ?>
                                    <input type="checkbox" class="form-check-input" id="in_lamination" name="in_lamination" value="on"<?=$checked ?> />на ламинации
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label text-nowrap mt-1 mb-4" style="line-height: 25px;">
                                    <?php $checked = $in_cut == 1 ? " checked='checked'" : "" ?>
                                    <input type="checkbox" class="form-check-input" id="in_cut" name="in_cut" value="on"<?=$checked ?> />на резке
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="comment">Комментарий</label>
                            <textarea name="comment" class="form-control" style="height: 120px;"><?= htmlentities($comment) ?></textarea>
                        </div>
                        <button type="submit" id="edit-submit" name="edit-submit" class="btn btn-dark" style="width: 175px;">OK</button>
                    </form>
                </div>
            </div>
        </div>
        <?php
        include '../include/footer.php';
        ?>
    </body>
</html>