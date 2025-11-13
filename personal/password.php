<?php
include '../include/topscripts.php';

// Авторизация
if(!LoggedIn()) {
    header('Location: '.APPLICATION.'/unauthorized.php');
}
        
// Валидация формы
$form_valid = true;
$error_message = '';
        
$old_password_valid = '';
$new_password_valid = '';
$confirm_valid = '';
        
// Обработка отправки формы
if(null !== filter_input(INPUT_POST, 'password_change_submit')) {
    $old_password = filter_input(INPUT_POST, 'old_password');
    if(empty($old_password)) {
        $old_password_valid = ISINVALID;
        $form_valid = false;
    }
    
    $new_password = filter_input(INPUT_POST, 'new_password');
    if(empty($new_password)) {
        $new_password_valid = ISINVALID;
        $form_valid = false;
    }
            
    $confirm = filter_input(INPUT_POST, 'confirm');
    if($new_password != $confirm) {
        $confirm_valid = ISINVALID;
        $form_valid = false;
    }
                        
    if($form_valid) {
        $row = (new Fetcher("select count(*) count from user where id=".GetUserId()." and password=password('$old_password')"))->Fetch();
        if($row['count'] == 0){
            $error_message = "Неправильный текущий пароль";
        }
        else {
            $error_message = (new Executer("update user set password=password('$new_password') where id=".GetUserId()))->error;
            
            if(empty($error_message)) {
                header('Location: '.APPLICATION.'/personal/index.php?password=true');
            }
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
            <div class="row">
                <div class="col-12 col-md-6">
                    <a class="btn btn-light backlink" href="<?=APPLICATION ?>/personal/">Назад</a>
                    <h1>Смена пароля</h1>
                    <form method="post">
                        <div class="form-group">
                            <label for="old_password">Текущий пароль</label>
                            <input type="password" id="old_password" name="old_password" class="form-control<?=$old_password_valid ?>" required="required"/>
                            <div class="invalid-feedback">Текущий пароль обязательно</div>
                        </div>
                        <div class="form-group">
                            <label for="new_password">Новый пароль</label>
                            <input type="password" id="new_password" name="new_password" class="form-control<?=$new_password_valid ?>" required="required"/>
                            <div class="invalid-feedback">Новый пароль обязательно</div>
                        </div>
                        <div class="form-group">
                            <label for="confirm">Введите новый пароль повторно</label>
                            <input type="password" id="confirm" name="confirm" class="form-control<?=$confirm_valid ?>" required="required"/>
                            <div class="invalid-feedback">Новый пароль и его подтверждение не совпадают</div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-dark" id="password_change_submit" name="password_change_submit">Сменить</button>
                        </div>
                    </form>
                </div>
            </div>
            <?php
            include '../include/footer.php';
            ?>
        </div>
    </body>
</html>