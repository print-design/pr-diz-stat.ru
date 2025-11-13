<?php
include '../include/topscripts.php';

// Авторизация
if(!LoggedIn()) {
    header('Location: '.APPLICATION.'/unauthorized.php');
}
        
// Валидация формы
$form_valid = true;
$error_message = '';
        
$username_valid = '';
$last_name_valid = '';
$first_name_valid = '';
$email_valid = '';
$phone_valid = '';
        
// Обработка отправки формы
if(null !== filter_input(INPUT_POST, 'user_edit_submit')) {
    $username = filter_input(INPUT_POST, 'username');
    if(empty($username)) {
        $username_valid = ISINVALID;
        $form_valid = false;
    }
    
    $last_name = filter_input(INPUT_POST, 'last_name');
    if(empty($last_name)) {
        $last_name_valid = ISINVALID;
        $form_valid = false;
    }
    
    $first_name = filter_input(INPUT_POST, 'first_name');
    if(empty($first_name)) {
        $first_name_valid = ISINVALID;
        $form_valid = false;
    }
    
    $email = filter_input(INPUT_POST, 'email');
    if(!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_valid = ISINVALID;
        $form_valid = false;
    }
    
    $phone = filter_input(INPUT_POST, 'phone');
    if(empty($phone)) {
        $phone_valid = ISINVALID;
        $form_valid = false;
    }
    
    if($form_valid) {
        $username = addslashes($username ?? '');
        $last_name = addslashes($last_name ?? '');
        $first_name = addslashes($first_name ?? '');
        $email = addslashes($email ?? '');
        $phone = addslashes($phone ?? '');
        $error_message = (new Executer("update user set username='$username', last_name='$last_name', first_name='$first_name', email='$email', phone='$phone' where id=".GetUserId()))->error;
        
        if(empty($error_message)) {
            header('Location: '.APPLICATION.'/personal/');
        }
    }
}
       
// Получение личных данных
$row = (new Fetcher("select username, last_name, first_name, email, phone from user where id=".GetUserId()))->Fetch();

$username = filter_input(INPUT_POST, 'username');
if(empty($username)) {
    $username = htmlentities($row['username'] ?? '');
}

$last_name = filter_input(INPUT_POST, 'last_name');
if(empty($last_name)) {
    $last_name = htmlentities($row['last_name'] ?? '');
}

$first_name = filter_input(INPUT_POST, 'first_name');
if(empty($first_name)) {
    $first_name = htmlentities($row['first_name'] ?? '');
}

$email = filter_input(INPUT_POST, 'email');
if(empty($email)) {
    $email = htmlentities($row['email'] ?? '');
}

$phone = filter_input(INPUT_POST, 'phone');
if(empty($phone)) {
    $phone = htmlentities($row['phone'] ?? '');
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
                    <h1>Редактирование личных данных</h1>
                    <form method="post">
                        <div class="row">
                            <div class="col-6 form-group">
                                <label for="first_name">Имя</label>
                                <input type="text" id="first_name" name="first_name" class="form-control<?=$first_name_valid ?>" value="<?=$first_name ?>" required="required"/>
                                <div class="invalid-feedback">Имя обязательно</div>
                            </div>
                            <div class="col-6 form-group">
                                <label for="last_name">Фамилия</label>
                                <input type="text" id="last_name" name="last_name" class="form-control<?=$last_name_valid ?>" value="<?=$last_name ?>" required="required"/>
                                <div class="invalid-feedback">Фамилия обязательно</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 form-group">
                                <label for="email">E-Mail</label>
                                <input type="email" id="email" name="email" class="form-control<?=$email_valid ?>" value="<?=$email ?>"/>
                                <div class="invalid-feedback">Неправильный формат E-Mail</div>
                            </div>
                            <div class="col-6 form-group">
                                <label for="phone">Телефон</label>
                                <input type="tel" id="phone" name="phone" class="form-control<?=$phone_valid ?>" value="<?=$phone ?>" required="required"/>
                                <div class="invalid-feedback">Телефон обязательно</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 form-group">
                                <label for="username">Логин</label>
                                <input type="text" id="username" name="username" class="form-control<?=$username_valid ?>" value="<?=$username ?>" required="required"/>
                                <div class="invalid-feedback">Логин обязательно</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-dark" id="user_edit_submit" name="user_edit_submit">Сохранить</button>
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