<?php
include '../include/topscripts.php';

// Если не задано значение calculation_id, перенаправляем на список
$id = filter_input(INPUT_GET, 'calculation_id');
if(empty($id)) {
    header('Location: '.APPLICATION.'/reclamation/');
}

// Авторизация
if(!IsInRole(array(ROLE_NAMES[ROLE_TECHNOLOGIST], ROLE_NAMES[ROLE_MANAGER], ROLE_NAMES[ROLE_MANAGER_SENIOR]))) {
    header('Location: '.APPLICATION.'/unauthorized.php');
}

// Валидация формы
$form_valid = true;
$error_message = '';

$calculation_id_valid = '';

const DEFECT = "defect";
const QUANTITY = "quantity";
const UNIT = "unit";
const PERCENT = "percent";

$defects = array();
$quantities = array();
$units = array();
$percents = array();

$i = 0;

foreach($_POST as $key => $value) {
    $substrings = explode('_', $key);
    
    if(count($substrings) == 2) {
        switch($substrings[0]) {
            case DEFECT:
                $defects[++$i] = $value;
                break;
            case QUANTITY:
                $quantities[$i] = $value;
                break;
            case UNIT:
                $units[$i] = $value;
                break;
            case PERCENT:
                $percents[$i] = $value;
                break;
        }
    }
}

$i = 0;
while (key_exists(++$i, $defects)) { }
$defect = filter_input(INPUT_POST, DEFECT);
if(null !== $defect) {
    $defects[$i] = $defect;
    $quantities[$i] = filter_input(INPUT_POST, QUANTITY);
    $units[$i] = filter_input(INPUT_POST, UNIT);
    $percents[$i] = filter_input(INPUT_POST, PERCENT);
}

$in_print = 0;
$in_lamination = 0;
$in_cut = 0;
$comment = '';

// Удаление дефекта из списка
$remove_defect = filter_input(INPUT_POST, 'remove_defect');

if(null !== $remove_defect) {
    $defects = array();
    $quantities = array();
    $units = array();
    $percents = array();
    
    $i = 0;
    
    foreach($_POST as $key => $value) {
        $substrings = explode('_', $key);
        
        if(count($substrings) == 2 && $substrings[1] != $remove_defect) {
            switch ($substrings[0]) {
                case DEFECT:
                    $defects[++$i] = $value;
                    break;
                case QUANTITY:
                    $quantities[$i] = $value;
                    break;
                case UNIT:
                    $units[$i] = $value;
                    break;
                case PERCENT:
                    $percents[$i] = $value;
                    break;
            }
        }
    }
}

// Обработка отправки формы
if(null !== filter_input(INPUT_POST, 'reclamation_create_submit')) {
    $calculation_id = filter_input(INPUT_POST, 'calculation_id');
    if(empty($calculation_id)) {
        $calculation_id_valid = ISINVALID;
        $form_valid = false;
    }
    
    if(count($defects) == 0) {
        $error_message = "Укажите хотя бы один дефект";
        $form_valid = false;
    }
    
    if($form_valid) {
        if(filter_input(INPUT_POST, 'in_print') == 'on') $in_print = 1;
        if(filter_input(INPUT_POST, 'in_lamination') == 'on') $in_lamination = 1;
        if(filter_input(INPUT_POST, 'in_cut') == 'on') $in_cut = 1;
        
        if($in_print == 0 && $in_lamination == 0 && $in_cut == 0) {
            $error_message = "Укажите хотя бы одну локализацию";
            $form_valid = false;
        }
    }
    
    $comment = filter_input(INPUT_POST, 'comment');
    
    if($form_valid) {
        $comment = addslashes($comment);
        
        $sql = "insert into reclamation (calculation_id, in_print, in_lamination, in_cut, comment) values ($calculation_id, $in_print, $in_lamination, $in_cut, '$comment')";
        $executer = new Executer($sql);
        $error_message = $executer->error;
        $insert_id = $executer->insert_id;
        
        if(empty($error_message) && !empty($insert_id)) {
            foreach ($defects as $key => $value) {
                $defect = $value;
                $quantity = key_exists($key, $quantities) ? $quantities[$key] : 0;
                $unit = key_exists($key, $units) ? $units[$key] : '';
                $percent = key_exists($key, $percents) ? $percents[$key] : 'NULL';
                if(empty($percent)) { $percent = "NULL"; }
                $sql = "insert into reclamation_defect (reclamation_id, defect_type, quantity, unit, percent) values ($insert_id, $defect, $quantity, '$unit', $percent)";
                $executer = new Executer($sql);
                $error_message = $executer->error;
            }
        }
        
        if(empty($error_message)) {
            header('Location: details.php?id='.$insert_id);
        }
    }
}

// Получение данных
$calculation_id = filter_input(INPUT_GET, 'calculation_id');
$calculation = '';
$date = null;
$customer_id = 0;
$customer = '';
$num_for_customer = 0;
$sql = "select c.name, c.date, c.customer_id, cus.name customer, "
        . "(select count(id) from calculation where customer_id = c.customer_id and id <= c.id) as num_for_customer "
        . "from calculation c "
        . "inner join customer cus on c.customer_id = cus.id "
        . "where c.id = $calculation_id";
$fetcher = new Fetcher($sql);
if($row = $fetcher->Fetch()) {
    $calculation = htmlentities($row['name']);
    $date = $row['date'];
    $customer_id = $row['customer_id'];
    $customer = htmlentities($row['customer']);
    $num_for_customer = $row['num_for_customer'];
}

$defect_type = filter_input(INPUT_POST, "defect_type");
$quantity = filter_input(INPUT_POST, 'quantity');
$unit = filter_input(INPUT_POST, 'unit');
$percent = filter_input(INPUT_POST, 'percent');
if(filter_input(INPUT_POST, 'in_print') == 'on') $in_print = 1;
if(filter_input(INPUT_POST, 'in_lamination') == 'on') $in_lamination = 1;
if(filter_input(INPUT_POST, 'in_cut') == 'on') $in_cut = 1;
$comment = htmlentities(filter_input(INPUT_POST, 'comment') ?? '');
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
        include '../include/header.php';
        ?>
        <div id="add_defect" class="modal fade show">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post">
                        <input type="hidden" name="scroll" />
                        <?php 
                        $i = 0;
                        while(key_exists(++$i, $defects)):
                        ?>
                        <input type="hidden" name="<?= DEFECT.'_'.$i ?>" value="<?=$defects[$i] ?>" />
                        <input type="hidden" name="<?= QUANTITY.'_'.$i ?>" value="<?= key_exists($i, $quantities) ? $quantities[$i] : '' ?>" />
                        <input type="hidden" name="<?= UNIT.'_'.$i ?>" value="<?= key_exists($i, $units) ? $units[$i] : '' ?>" />
                        <input type="hidden" name="<?= PERCENT.'_'.$i ?>" value="<?= key_exists($i, $percents) ? $percents[$i] : '' ?>" />
                        <?php endwhile; ?>
                        <input type="hidden" name="in_print" id="in_print_modal" value="<?=$in_print == 1 ? 'on' : '' ?>" />
                        <input type="hidden" name="in_lamination" id="in_lamination_modal" value="<?=$in_lamination == 1 ? 'on' : '' ?>" />
                        <input type="hidden" name="in_cut" id="in_cut_modal" value="<?=$in_cut == 1 ? 'on' : '' ?>" />
                        <input type="hidden" name="comment" id="comment_modal" value="<?= htmlentities($comment) ?>" />
                        <div class="modal-header">
                            <span class="font-weight-bold" style="font-size: x-large;">Добавить дефект</span>
                            <button type="button" class="close create_film_variation_dismiss" data-dismiss="modal"><i class="fas fa-times" style="color: #EC3A7A;"></i></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="<?= DEFECT ?>">Тип рекламации</label>
                                <select id="<?= DEFECT ?>" name="<?= DEFECT ?>" class="form-control" required="required">
                                    <option value="" hidden="hidden">...</option>
                                    <?php foreach(DEFECT_TYPES as $item): ?>
                                    <option value="<?=$item ?>"><?= DEFECT_TYPE_NAMES[$item] ?></option>
                                    <?php endforeach; ?>
                                    <option disabled="disabled"> </option>
                                    <option value="">Другое</option>
                                </select>
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
            <a class="btn btn-outline-dark backlink" href="<?= APPLICATION."/reclamation/" ?>">К списку</a>
            <h1>Новая рекламация</h1>
            <div class="row">
                <div class="col-12 col-lg-4">
                    <form method="post">
                        <input type="hidden" name="scroll" />
                        <input type="hidden" name="calculation_id" value="<?=$calculation_id ?>" />
                        <div class="name">Заказчик: <?=$customer ?></div>
                        <div class="name">Наименование: <?=$calculation ?></div>
                        <div class="subtitle">№ заказа: <?=$customer_id.'-'.$num_for_customer ?> от <?= DateTime::createFromFormat('Y-m-d H:i:s', $date)->format('d.m.Y') ?></div>
                        <hr />
                        <h2>Дефекты</h2>
                        <table class="table">
                            <tr>
                                <th>Тип</th>
                                <th>м/шт</th>
                                <th>%</th>
                                <th></th>
                            </tr>
                            <?php
                            $i = 0;
                            while(key_exists(++$i, $defects)):
                            ?>
                            <tr>
                                <td>
                                    <input type="hidden" name="<?= DEFECT.'_'.$i ?>" value="<?=$defects[$i] ?>" />
                                    <?= key_exists($defects[$i], DEFECT_TYPE_NAMES) ? DEFECT_TYPE_NAMES[$defects[$i]] : "Другое" ?>
                                </td>
                                <td>
                                    <input type="hidden" name="<?= QUANTITY.'_'.$i ?>" value="<?= key_exists($i, $quantities) ? $quantities[$i] : '' ?>" />
                                    <input type="hidden" name="<?= UNIT.'_'.$i ?>" value="<?= key_exists($i, $units) ? $units[$i] : '' ?>" />
                                    <?= (key_exists($i, $quantities) ? $quantities[$i] : '').' '.(key_exists($i, $units) ? UNIT_NAMES[$units[$i]] : '') ?>
                                </td>
                                <td>
                                    <input type="hidden" name="<?= PERCENT.'_'.$i ?>" value="<?= key_exists($i, $percents) ? $percents[$i] : '' ?>" />
                                    <?= (key_exists($i, $percents) && !empty($percents[$i])) ? $percents[$i].' %' : '' ?>
                                </td>
                                <td>
                                    <button type="submit" class="btn btn-sm btn-link" style="font-size: xx-large;" name="remove_defect" value="<?=$i ?>">×</button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </table>
                        <button type="button" class="btn btn-dark" id="add_defect" data-toggle="modal" data-target="#add_defect">Добавить дефект</button>
                        <hr />
                        <h2>Локализации</h2>
                        <div class="d-flex justify-content-lg-start">
                            <div class="form-check mr-4">
                                <label class="form-check-label" style="line-height: 25px;">
                                    <input type="checkbox" class="form-check-input" id="in_print" name="in_print" value="on" onchange="javascript: $('#in_print_modal').val($(this).is(':checked') ? 'on' : '');" <?=$in_print == 1 ? " checked='checked'" : "" ?> />
                                    На печати
                                </label>
                            </div>
                            <div class="form-check mr-4">
                                <label class="form-check-label" style="line-height: 25px;">
                                    <input type="checkbox" class="form-check-input" id="in_lamination" name="in_lamination" onchange="javascript: $('#in_lamination_modal').val($(this).is(':checked') ? 'on' : '');" value="on"<?=$in_lamination == 1 ? " checked='checked'" : "" ?> />
                                    На ламинации
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label" style="line-height: 25px;">
                                    <input type="checkbox" class="form-check-input" id="in_cut" name="in_cut" onchange="javascript: $('#in_cut_modal').val($(this).is(':checked') ? 'on' : '');" value="on"<?=$in_cut == 1 ? " checked='checked'" : "" ?> />
                                    На резке
                                </label>
                            </div>
                        </div>
                        <hr />
                        <div class="form-group">
                            <label for="comment">Комментарий</label>
                            <textarea class="form-control" rows="5" id="comment" name="comment" onkeyup="javascript: $('#comment_modal').val($(this).val());"><?= htmlentities($comment) ?></textarea>
                        </div>
                        <div class="form-group" style="padding-top: 24px;">
                            <button type="submit" class="btn btn-dark" id="reclamation_create_submit" name="reclamation_create_submit">Создать</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
        include '../include/footer.php';
        ?>
    </body>
</html>