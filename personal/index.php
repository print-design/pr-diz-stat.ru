<?php
include '../include/topscripts.php';

// Авторизация
if(!LoggedIn()) {
    header('Location: '.APPLICATION.'/unauthorized.php');
}

// Получение личных данных
$username = '';
$last_name = '';
$first_name = '';
$email = '';
$phone = '';

$sql = "select username, last_name, first_name, email, phone from user where id=".GetUserId();
$fetcher = new Fetcher($sql);
if($row = $fetcher->Fetch()) {
    $username = $row['username'];
    $last_name = $row['last_name'];
    $first_name = $row['first_name'];
    $email = $row['email'];
    $phone = $row['phone'];
}

if(IsInRole(CUTTER_USERS)) {
    $current_time = new DateTime();
    $current_hour = intval($current_time->format('G'));
    $current_shift = 'day';
    $working_current_time = clone $current_time;
    
    if($current_hour > 19 && $current_hour < 24) {
        $current_shift = 'night';
    }
    elseif ($current_hour >= 0 && $current_hour < 8) {
        $current_shift = 'night';
        $working_current_time->modify("-1 day");
    }
    
    $sql = "select pe.last_name, pe.first_name, pe.phone "
            . "from plan_workshift1 pw inner join plan_employee pe on pw.employee1_id = pe.id "
            . "where date_format(pw.date, '%d-%m-%Y') = '".$working_current_time->format('d-m-Y')."' and pw.shift = '$current_shift' and pw.work_id = ".WORK_CUTTING.' and pw.machine_id = '. GetUserId();
    $fetcher = new Fetcher($sql);
    if($row = $fetcher->Fetch()) {
        $last_name = $row['last_name'];
        $first_name = $row['first_name'];
        $phone = $row['phone'];
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
            
            if(filter_input(INPUT_GET, 'password') == 'true') {
                echo "<div class='alert alert-info'>Пароль успешно изменён</div>";
            }
            ?>
            <div class="row">
                <div class="col-12 col-md-6">
                    <h1>Мои настройки</h1>
                    <table class="table table-bordered">
                        <tr>
                            <th>Имя</th>
                            <td><?=$first_name ?></td>
                        </tr>
                        <tr>
                            <th>Фамилия</th>
                            <td><?=$last_name ?></td>
                        </tr>
                        <tr>
                            <th>E-Mail</th>
                            <td><?=$email ?></td>
                        </tr>
                        <tr>
                            <th>Телефон</th>
                            <td><?=$phone ?></td>
                        </tr>
                        <tr>
                            <th>Логин</th>
                            <td><?=$username ?></td>
                        </tr>
                        <?php if(IsInRole(CUTTER_USERS)): ?>
                        <tr>
                            <th>Текущее время</th>
                            <td><?=$current_time->format('d-m-Y H:i:s') ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
        <?php
        include '../include/footer.php';
        include '../include/footer_cut.php';
        ?>
    </body>
</html>