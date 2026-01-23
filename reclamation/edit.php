<?php
include '../include/topscripts.php';

// Если не задано значение id, перенаправляем на список
$id = filter_input(INPUT_GET, 'id');
if(empty($id)) {
    header('Location: '.APPLICATION.'/reclamation/');
}

// Валидация формы
$form_valid = true;
$error_message = '';

$comment_valid = '';

const DEFECT = "defect";
const OTHER = "other";
const QUANTITY = "quantity";
const UNIT = "unit";
const PERCENT = "percent";

// Редактирование рекламация
if(null !== filter_input(INPUT_POST, 'edit-submit')) {
    $id = filter_input(INPUT_POST, 'id');
    
    $in_print = 0; if(filter_input(INPUT_POST, 'in_print') == 'on') $in_print = 1;
    $in_lamination = 0; if(filter_input(INPUT_POST, 'in_lamination') == 'on') $in_lamination = 1;
    $in_cut = 0; if(filter_input(INPUT_POST, 'in_cut') == 'on') $in_cut = 1;
    $comment = filter_input(INPUT_POST, 'comment');
    $comment = addslashes($comment);
    
    $sql = "update reclamation set in_print = $in_print, in_lamination = $in_lamination, in_cut = $in_cut, comment = '$comment' where id = $id";
    $executer = new Executer($sql);
    $error_message = $executer->error;
    
    if(empty($error_message)) {
        header('Location: details.php'.BuildQuery('id', $id));
    }
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
        <div id="add_defect" class="modal fade show">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post">
                        <input type="hidden" name="scroll" />
                        <div class="modal-header">
                            <span class="font-weight-bold" style="font-size: x-large;">Добавить дефект</span>
                            <button type="button" class="close create_film_variation_dismiss" data-dismiss="modal"><i class="fas fa-times" style="color: #EC3A7A;"></i></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="<?= DEFECT ?>">Тип рекламации</label>
                                <select id="<?= DEFECT ?>" name="<?= DEFECT ?>" class="form-control" required="required" onchange="javascript: if($(this).val() === '<?= DEFECT_TYPE_OTHER ?>') { $('#other_defect_type_group').removeClass('d-none'); $('#other_defect_type').attr('required', 'required'); $('#other_defect_type').focus(); } else { $('#other_defect_type_group').addClass('d-none'); $('#other_defect_type').removeAttr('required'); }">
                                    <option value="" hidden="hidden">...</option>
                                    <?php foreach(DEFECT_TYPES as $item): ?>
                                    <option value="<?=$item ?>"><?= DEFECT_TYPE_NAMES[$item] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group d-none" id="other_defect_type_group">
                                <label for="<?= OTHER ?>">Другой тип рекламации</label>
                                <input type="text" name="<?= OTHER ?>" id="other_defect_type" class="form-control" />
                            </div>
                            <div class="row">
                                <div class="form-group col-6">
                                    <label for="quantity">Количество</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control int-only" id="<?= QUANTITY ?>" name="<?= QUANTITY ?>" placeholder="Количество" required="required" autocomplete="off" />
                                        <div class="input-group-append">
                                            <select id="<?= UNIT ?>" name="<?= UNIT ?>" required="required">
                                                <option value="" hidden="hidden">...</option>
                                                <option value="<?= UNIT_M ?>"><?= UNIT_NAMES[UNIT_M] ?></option>
                                                <option value="<?= UNIT_KG ?>"><?= UNIT_NAMES[UNIT_KG] ?></option>
                                                <option value="<?= UNIT_PC ?>"><?= UNIT_NAMES[UNIT_PC] ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-6">
                                    <label for="percent">Количество, %</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control int-only" id="<?= PERCENT ?>" name="<?= PERCENT ?>" placeholder="Количество, %" autocomplete="off" />
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-dark" id="add_defect_submit" name="add_defect_submit">Добавить</button>
                            <button type="button" class="btn btn-light create_film_variation_dismiss" data-dismiss="modal">Отменить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
                    <button type="button" class="btn btn-dark" id="add_defect" data-toggle="modal" data-target="#add_defect">Добавить дефект</button>
                    <hr />
                    <h2>Локализация</h2>
                    <form method="post">
                        <input type="hidden" name="id" value="<?=$id ?>" />
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