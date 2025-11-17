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
        include '../include/header_stat.php';
        ?>
        <div class="container-fluid">
            <?php
            include '../include/subheader_stat.php';
            
            if(!empty($error_message)) {
                echo "<div class='alert alert-danger'>$error_message</div>";
            }
            ?>
            <div class="d-flex justify-content-start">
                <h1>Статистика по заказчику</h1>
            </div>
            <div class="row">
                <div class="col-6">
                    <h2>Рекламации</h2>
            <table class="table table-hover" id="content_table">
                <thead>
                    <tr>
                        <th>Заказчик</th>
                        <th>Рекламаций всего</th>
                        <th class="text-right">В процентах</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "select cus.name customer, count(r.id) total, count(r.id) / (select count(id) from reclamation) * 100 percent "
                            . "from customer cus "
                            . "inner join calculation c on c.customer_id = cus.id "
                            . "inner join reclamation r on r.calculation_id = c.id "
                            . "group by cus.id "
                            . "order by total desc";
                    $fetcher = new Fetcher($sql);
                    
                    while($row = $fetcher->Fetch()):
                    ?>
                    <tr>
                        <td><?=$row['customer'] ?></td>
                        <td><?=$row['total'] ?></td>
                        <td class="text-right"><?= DisplayNumber(floatval($row['percent']), 2) ?>%</td>
                    </tr>
                    <?php
                    endwhile;
                    ?>
                </tbody>
            </table>
                    </div>
                </div>
        </div>
        <?php
        include '../include/footer.php';
        ?>
    </body>
</html>