<?php
include '../include/topscripts.php';

// Авторизация
if(!IsInRole(array(ROLE_NAMES[ROLE_TECHNOLOGIST], ROLE_NAMES[ROLE_MANAGER], ROLE_NAMES[ROLE_MANAGER_SENIOR]))) {
    header('Location: '.APPLICATION.'/unauthorized.php');
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
        include '../include/pager_top.php';
        $rowcounter = 0;
        ?>
        <div class="container-fluid">
            <?php
            if(!empty($error_message)) {
                echo "<div class='alert alert-danger'>$error_message</div>";
            }
            ?>
            <div class="d-flex justify-content-between mb-auto">
                <div class="p-0">
                    <h1>Рекламации</h1>
                </div>
                <div class="pt-1">
                    <a href="create.php" class="btn btn-dark"><i class="fas fa-plus"></i>&nbsp;Новая рекламация</a>
                </div>
            </div>
            <table class="table table-hover" id="content_table">
                <thead>
                    <tr style="border-top: 1px solid #dee2e6; border-left: 1px solid #dee2e6; border-right: 1px solid #dee2e6;">
                        <th style="padding-left: 5px; padding-right: 5px;">Дата</th>
                        <th style="padding-left: 5px; padding-right: 5px;">№ заказа</th>
                        <th style="padding-left: 5px; padding-right: 5px;">Заказ</th>
                        <th style="padding-left: 5px; padding-right: 5px;">Количество</th>
                        <th style="padding-left: 5px; padding-right: 5px;">В процентах</th>
                        <th style="padding-left: 5px; padding-right: 5px;">Этапы</th>
                        <th style="padding-left: 5px; padding-right: 5px;">Комментарий</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "";
                    ?>
                </tbody>
            </table>
            <?php
            include '../include/pager_bottom.php';
            ?>
        </div>
        <?php
        include '../include/footer.php';
        ?>
    </body>
</html>