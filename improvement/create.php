<?php
include '../include/topscripts.php';
include './improvement_goals.php';

const DBT_PLAN_EMPLOYEE = "plan_employee";
const DBT_USER = "user";

// Валидация формы
$form_valid = true;
$error_message = '';

$user_name_valid = '';
$title_valid = '';
$body_valid = '';
$improvement_goal_valid = '';

// Обработка отправки формы
if(null !== filter_input(INPUT_POST, 'improvement_create_submit')) {
    $user_name = filter_input(INPUT_POST, 'user_name');
    if(empty($user_name)) {
        $user_name_valid = ISINVALID;
        $form_valid = false;
    }
    
    $substrings = explode('_', $user_name);
    $last_name = '';
    $first_name = '';
    $role = '';
    
    if(count($substrings) < 3) {
        $user_name_valid = ISINVALID;
        $form_valid = false;
    }
    else {
        $last_name = $substrings[0];
        $first_name = $substrings[1];
        $role = mb_substr($user_name, mb_strlen($last_name) + mb_strlen($first_name) + 2);
        
        if(empty($last_name) || empty($first_name) || empty($role)) {
            $user_name_valid = ISINVALID;
            $form_valid = false;
        }
    }
    
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
    
    $improvement_goal = filter_input(INPUT_POST, 'improvement_goal');
    if(empty($improvement_goal)) {
        $improvement_goal = ISINVALID;
        $form_valid = false;
    }
    
    if($form_valid) {
        /*$title = addslashes($title);
        $body = addslashes($body);
        $effect = addslashes($effect);
        
        $sql = "insert into improvement (last_name, first_name, role, title, body, effect, improvement_goal) values ('$last_name', '$first_name', '$title', '$body', '$effect')";
        $executer = new Executer($sql);
        $error_message = $executer->error;*/
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
                    <label for="user_name">Сотрудник</label>
                    <select class="form-control" id="user_name" name="user_name" multiple="multiple" required="required">
                        <option value="" hidden="hidden">...</option>
                        <?php
                        $sql = "select id, trim(last_name) last_name, trim(first_name) first_name, role_id, '". DBT_PLAN_EMPLOYEE."' as dbt "
                                . "from plan_employee "
                                . "where active = 1 "
                                . "union "
                                . "select id, trim(last_name) last_name, trim(first_name) first_name, role_id, '". DBT_USER."' as dbt "
                                . "from user "
                                . "where active = 1 "
                                . "order by last_name, first_name";
                        $fetcher = new Fetcher($sql);
                        while($row = $fetcher->Fetch()):
                        ?>
                        <option value="<?=$row['last_name'].'_'.$row['first_name'].'_'.($row['dbt'] == DBT_USER ? ROLE_LOCAL_NAMES[$row['role_id']] : PLAN_ROLE_NAMES[$row['role_id']]) ?>"><?=$row['last_name'].' '.$row['first_name'] ?></option>
                        <?php endwhile; ?>
                    </select>
                    <div class="invalid-feedbacki">Фамилия и имя обязательно</div>
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
                    <label for="improvement_goal">На что направлено улучшение</label>
                    <select class="form-control" id="improvement_goal" name="improvement_goal" required="required">
                        <option value="" hidden="hidden">...</option>
                        <?php foreach(IMPROVEMENT_GOALS as $item): ?>
                        <option value="<?=$item ?>"><?= IMPROVEMENT_GOALS_NAMES[$item] ?></option>
                        <?php endforeach; ?>
                    </select>
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
            $('#user_name').select2({
                placeholder: "Фамилия, имя...",
                maximumSelectionLength: 1,
                language: "ru",
                width: "100%"
            });
        </script>
    </body>
</html>