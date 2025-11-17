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
                <div class="col-4">
                    <h2>Рекламации</h2>
                    <table class="table table-hover">
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
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-8">
                    <div class="row">
                        <?php
                        $sql = "select distinct rd.defect_type, rd.other_defect_type from reclamation_defect rd";
                        $grabber = new Grabber($sql);
                        $defects = $grabber->result;
                        
                        foreach($defects as $defect):
                        ?>
                        <div class="col-6">
                            <h2><?= $defect['defect_type'] == DEFECT_TYPE_OTHER ? $defect['other_defect_type'] : DEFECT_TYPE_NAMES[$defect['defect_type']] ?></h2>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Заказчик</th>
                                        <th>Дефектов всего</th>
                                        <th class="text-right">В процентах</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "select cus.name customer, count(rd.id) total, "
                                            . "count(rd.id) / (select count(id) from reclamation_defect where defect_type = ".$defect['defect_type']." and other_defect_type = '".$defect['other_defect_type']."') * 100 percent "
                                            . "from customer cus "
                                            . "inner join calculation c on c.customer_id = cus.id "
                                            . "inner join reclamation r on r.calculation_id = c.id "
                                            . "inner join reclamation_defect rd on rd.reclamation_id = r.id "
                                            . "where rd.defect_type = ".$defect['defect_type']." and rd.other_defect_type = '".$defect['other_defect_type']."' "
                                            . "group by cus.id order by total desc";
                                    $fetcher = new Fetcher($sql);
                                    
                                    while ($row = $fetcher->Fetch()):
                                    ?>
                                    <tr>
                                        <td><?=$row['customer'] ?></td>
                                        <td><?=$row['total'] ?></td>
                                        <td class="text-right"><?= DisplayNumber(floatval($row['percent']), 2) ?>%</td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
        include '../include/footer.php';
        ?>
    </body>
</html>