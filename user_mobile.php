<?php
include 'include/topscripts.php';

// Авторизация
if(!IsInRole(array('technologist', 'dev', 'electrocarist', 'cutter', 'marker', 'auditor'))) {
    header('Location: '.APPLICATION.'/unauthorized.php');
}
?>
<!DOCTYPE html>
<html>
    <head>
        <?php
        include 'include/head.php';
        ?>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php
        include 'include/style_mobile.php';
        ?>
    </head>
    <body>
        <div class="container-fluid header">
            <nav class="navbar navbar-expand-sm justify-content-start">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= urldecode(filter_input(INPUT_GET, 'link')) ?>"><i class="fas fa-chevron-left"></i>&nbsp;Назад</a>
                    </li>
                </ul>
            </nav>
        </div>
        <div id="topmost"></div>
        <div class="container-fluid">
            <?php
            if(!empty($error_message)) {
               echo "<div class='alert alert-danger'>$error_message</div>";
            }
            
            $position = "Работник";
            if(IsInRole('electrocarist')) {
                $position = "Водитель погрузчика";
            }
            elseif(IsInRole('cutter')) {
                $position = "Резчик раскрой";
            }
            elseif(IsInRole('auditor')) {
                $position = "Ревизор";
            }
            ?>
            <p class="mt-4" style="font-size: 18px; line-height: 24px; font-weight: 600;"><?=$position ?>:</p>
            <?php
            $sql = "select last_name, first_name from user where id=". GetUserId();
            $fetcher = new Fetcher($sql);
            if($row = $fetcher->Fetch()):
            $last_name = $row['last_name'];
            $first_name = $row['first_name'];
            ?>
            <p class="mt-2 mb-5" style="font-size: 24px; line-height: 32px; font-weight: 600;"><?=$last_name.' '.$first_name ?></p>
            <?php
            endif;
            ?>
            <form method="post">
                <button type="submit" class="btn btn-outline-danger form-control" id="logout_submit" name="logout_submit">Выйти</button>
            </form>
        </div>
        <?php
        include 'include/footer.php';
        ?>
    </body>
</html>