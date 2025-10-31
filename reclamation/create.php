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
$quantity_valid = '';
$unit_valid = '';

$defect_type = null;
$quantity = null;
$unit = null;
$percent = null;
$in_print = 0;
$in_lamination = 0;
$in_cut = 0;
$comment = '';

// Обработка отправки формы
if(null !== filter_input(INPUT_POST, 'reclamation_create_submit')) {
    $calculation_id = filter_input(INPUT_POST, 'calculation_id');
    if(empty($calculation_id)) {
        $calculation_id_valid = ISINVALID;
        $form_valid = false;
    }
    
    $defect_type = filter_input(INPUT_POST, 'defect_type');
    
    $quantity = filter_input(INPUT_POST, 'quantity');
    if(empty($quantity)) {
        $quantity_valid = ISINVALID;
        $form_valid = false;
    }
    
    $unit = filter_input(INPUT_POST, 'unit');
    if(empty($unit)) {
        $unit_valid = ISINVALID;
        $form_valid = false;
    }
    
    $percent = filter_input(INPUT_POST, 'percent');
    if(filter_input(INPUT_POST, 'in_print') == 'on') $in_print = 1;
    if(filter_input(INPUT_POST, 'in_lamination') == 'on') $in_lamination = 1;
    if(filter_input(INPUT_POST, 'in_cut') == 'on') $in_cut = 1;
    $comment = filter_input(INPUT_POST, 'comment');
    
    if($form_valid) {
        $comment = addslashes($comment);
        
        $sql = "insert into reclamation (calculation_id, defect_type, quantity, unit, percent, in_print, in_lamination, in_cut, comment) values ($calculation_id, $defect_type, $quantity, '$unit', $percent, $in_print, $in_lamination, $in_cut, '$comment')";
        $executer = new Executer($sql);
        $error_message = $executer->error;
        $insert_id = $executer->insert_id;
        
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
        </style>
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
            <a class="btn btn-outline-dark backlink" href="<?= APPLICATION."/reclamation/" ?>">К списку</a>
            <h1>Новая рекламация</h1>
            <div class="row">
                <div class="col-12 col-lg-4">
                    <form method="post">
                        <input type="hidden" name="calculation_id" value="<?=$calculation_id ?>" />
                        <div class="form-group">
                            <label for="defect_type">Тип рекламации</label>
                            <select id="defect_type" name="defect_type" class="form-control" required="required">
                                <option value="" hidden="hidden">...</option>
                                <?php
                                foreach (DEFECT_TYPES as $item):
                                    $selected = '';
                                if($defect_type == $item) {
                                    $selected = " selected='selected'";
                                }
                                ?>
                                <option value="<?=$item ?>"<?=$selected ?>><?= DEFECT_TYPE_NAMES[$item] ?></option>
                                <?php endforeach; ?>
                                <option disabled="disabled"> </option>
                                <option value=""<?= (empty($defect_type) && null !== filter_input(INPUT_POST, 'reclamation_create_submit')) ? " selected='selected'" : "" ?>>Другое</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="form-group col-12 col-lg-6">
                                <label for="quantity">Количество</label>
                                <div class="input-group">
                                    <input type="text" class="form-control int-only<?=$quantity_valid ?>" id="quantity" name="quantity" placeholder="Количество" value="<?= $quantity ?>" required="required" autocomplete="off" />
                                    <div class="input-group-append">
                                        <select id="unit" name="unit" required="required">
                                            <option value="" hidden="hidden">...</option>
                                            <option value="m"<?=$unit == UNIT_M ? " selected='selected'" : "" ?>>м</option>
                                            <option value="pc"<?=$unit == UNIT_PC ? " selected='selected'" : "" ?>>шт</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label for="percent">Количество, %</label>
                                <div class="input-group">
                                    <input type="text" class="form-control int-only" id="percent" name="percent" placeholder="Количество, %" value="<?=$percent ?>" autocomplete="off" />
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="name">Заказчик: <?=$customer ?></div>
                        <div class="name">Наименование: <?=$calculation ?></div>
                        <div class="subtitle">№ расчёта: <?=$customer_id.'-'.$num_for_customer ?> от <?= DateTime::createFromFormat('Y-m-d H:i:s', $date)->format('d.m.Y') ?></div>
                        <hr />
                        <h2>Дефекты</h2>
                        <button type="button" class="btn btn-dark" id="add_defect">Добавить дефект</button>
                        <hr />
                        <div class="d-flex justify-content-lg-start">
                            <div class="form-check mr-4">
                                <label class="form-check-label" style="line-height: 25px;">
                                    <input type="checkbox" class="form-check-input" id="in_print" name="in_print" value="on" <?=$in_print == 1 ? " checked='checked'" : "" ?> />
                                    На печати
                                </label>
                            </div>
                            <div class="form-check mr-4">
                                <label class="form-check-label" style="line-height: 25px;">
                                    <input type="checkbox" class="form-check-input" id="in_lamination" name="in_lamination" value="on"<?=$in_lamination == 1 ? " checked='checked'" : "" ?> />
                                    На ламинации
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label" style="line-height: 25px;">
                                    <input type="checkbox" class="form-check-input" id="in_cut" name="in_cut" value="on"<?=$in_cut == 1 ? " checked='checked'" : "" ?> />
                                    На резке
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="comment">Комментарий</label>
                            <textarea class="form-control" rows="5" id="comment" name="comment"><?= htmlentities($comment) ?></textarea>
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