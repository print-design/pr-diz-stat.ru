<?php
include '../include/topscripts.php';

// Валидация формы
$form_valid = true;
$error_message = '';

$title_valid = '';
$body_valid = '';

// Обработка отправки формы
if(null !== filter_input(INPUT_POST, 'improvement_create_submit')) {
    $user_id = GetUserId();
    
    $title = filter_input(INPUT_POST, 'title');
    if(empty($title)) {
        $title_valid = ISINVALID;
        $form_valid = false;
    }
    
    $body = filter_input(INPUT_POST, 'body');
    if(empty($body)) {
        $body_valid = ISINVALID;
        $form_valid = false;
    }
    
    $effect = filter_input(INPUT_POST, 'effect');
    
    if($form_valid) {
        $title = addslashes($title);
        $body = addslashes($body);
        $effect = addslashes($effect);
        
        $sql = "insert into improvement (id, user_id, role_id, title, body, effect) values ($user_id, (select role_id from user where id = $user_id), '$title', '$body', '$effect')";
        $executer = new Executer($sql);
        $error_message = $executer->error;
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <?php
        include '../include/head.php';
        ?>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php
        include '../include/style_mobile.php';
        ?>
        <link href="<?=APPLICATION ?>/css/select2.min.css" rel="stylesheet"/>
    </head>
    <body>
        <div class="container-fluid header">
            <nav class="navbar navbar-expand-sm justify-content-end">
                <?php if(!empty(filter_input(INPUT_COOKIE, USERNAME))): ?>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown no-dropdown-arrow-after">
                        <a class="nav-link mr-0" href="<?=APPLICATION ?>/user_mobile.php?link=<?= urlencode($_SERVER['REQUEST_URI']) ?>"><i class="fa fa-cog" aria-hidden="true"></i></a>
                    </li>
                </ul>
                <?php endif; ?>
            </nav>
        </div>
        <div id="topmost"></div>
        <div class="container-fluid">
            <?php
            if(!empty($error_message)) {
                echo "<div class='alert alert-danger'>$error_message</div>";
            }
            
            if(null !== filter_input(INPUT_POST, 'improvement_create_submit') && empty($error_message)):
            ?>
            <h1>Ваше предложение отправлено</h1>
            <a href="create.php" class="btn btn-dark" title="OK">OK</a>
            <?php else: ?>
            <h1>Предложение по улучшению</h1>
            <form method="post">
                <div class="form-group">
                    <label for="employee_id">Сотрудник</label>
                    <select class="form-control" id="employee_id" name="employee_id" multiple="multiple" required="required">
                        <option value="" hidden="hidden">...</option>
                        <?php
                        $sql = "select last_name, first_name "
                                . "from plan_employee "
                                . "union "
                                . "select last_name, first_name "
                                . "from user "
                                . "order by last_name, first_name";
                        $fetcher = new Fetcher($sql);
                        while($row = $fetcher->Fetch()):
                        ?>
                        <option><?=$row['last_name'].' '.$row['first_name'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="title">Заголовок</label>
                    <input type="text" class="form-control" name="title" required="required" />
                    <div class="invalid-feedback">Заголовок обязательно</div>
                </div>
                <div class="form-group">
                    <label for="body">Текст предложения</label>
                    <textarea class="form-control" name="body" rows="4" required="required"></textarea>
                    <div class="invalid-feedback">Текст предложения обязательно</div>
                </div>
                <div class="form-group">
                    <label for="effect">Что изменится в результате улучшения</label>
                    <textarea class="form-control" name="effect" rows="4"></textarea>
                </div>
                <div class="form-group">
                    <label for="reason"></label>
                </div>
                <button type="submit" class="btn btn-dark" id="improvement_create_submit" name="improvement_create_submit">Подать</button>
            </form>
            <?php endif; ?>
        </div>
        <?php
        include '../include/footer.php';
        include '../include/footer_mobile.php';
        ?>
        <script src="<?=APPLICATION ?>/js/select2.min.js"></script>
        <script src="<?=APPLICATION ?>/js/i18n/ru.js"></script>
        <script>
            $('#employee_id').select2({
                placeholder: "Фамилия, имя...",
                maximumSelectionLength: 1,
                language: "ru",
                width: "100%"
            });
        </script>
    </body>
</html>