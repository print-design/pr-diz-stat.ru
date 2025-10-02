<?php
include '../include/topscripts.php';

// Если не задано значение id, перенаправляем на список
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

// Обработка отправки формы
if(null !== filter_input(INPUT_POST, 'reclamation_create_submit')) {
    $calculation_id = filter_input(INPUT_POST, 'calculation_id');
    if(empty($calculation_id)) {
        $calculation_id_valid = ISINVALID;
        $form_valid = false;
    }
    
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
    $comment = filter_input(INPUT_POST, 'comment');
    
    if($form_valid) {
        $comment = addslashes($comment);
        
        $sql = "";
        $insert_id = 0;
        
        if(empty($error_message)) {
            header('Location: details.php?id='.$insert_id);
        }
    }
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
            <a class="btn btn-outline-dark backlink" href="<?= APPLICATION."/reclamation/" ?>">К списку</a>
            <h1>Новая рекламация</h1>
        </div>
        <?php
        include '../include/footer.php';
        ?>
    </body>
</html>