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
            
            // Общее количество рекламаций для установления количества страниц в постраничном выводе
            $sql = "select count(r.id) from reclamation r inner join calculation c on r.calculation_id = c.id";
            $fetcher = new Fetcher($sql);
            
            if($row = $fetcher->Fetch()) {
                $pager_total_count = $row[0];
            }
            ?>
            <div class="d-flex justify-content-between mb-auto">
                <div class="p-0">
                    <h1>Рекламации</h1>
                </div>
                <div class="pt-1">
                    <a href="select_calculation_id.php" class="btn btn-dark"><i class="fas fa-plus"></i>&nbsp;Новая рекламация</a>
                </div>
            </div>
            <table class="table table-hover" id="content_table">
                <thead>
                    <tr>
                        <th>Дата</th>
                        <th>№ заказа</th>
                        <th>Заказ</th>
                        <th>Дефекты</th>
                        <th>На печати</th>
                        <th>На ламинации</th>
                        <th>На резке</th>
                        <th>Комментарий</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "select r.id, r.date, r.calculation_id, c.name calculation, r.in_print, r.in_lamination, r.in_cut, r.comment, "
                            . "c.name reclamation, c.customer_id, "
                            . "(select count(id) from calculation where customer_id = c.customer_id and id <= c.id) as num_for_customer, "
                            . "(select group_concat(concat(defect_type, '-', quantity, '-', unit, '-', ifnull(percent, '')) separator '*') from reclamation_defect where reclamation_id = r.id group by reclamation_id) as defects "
                            . "from reclamation r "
                            . "inner join calculation c on r.calculation_id = c.id "
                            . "order by r.id desc limit $pager_skip, $pager_take";
                    $fetcher = new Fetcher($sql);
                    while ($row = $fetcher->Fetch()):
                        $rowcounter++;
                    ?>
                    <tr>
                        <td class="text-nowrap"><?=DateTime::createFromFormat('Y-m-d H:i:s', $row['date'])->format('d.m.Y H:i') ?></td>
                        <td><?=$row['customer_id'].'-'.$row['num_for_customer'] ?></td>
                        <td><?=$row['calculation'] ?></td>
                        <td>
                            <?php
                            $defects = explode('*', $row['defects'] ?? '');
                            $new_rows = array();
                            foreach($defects as $defect) {
                                $substrings = explode('-', $defect);
                                array_push($new_rows, (empty($substrings[0]) ? '' : DEFECT_TYPE_NAMES[$substrings[0]].' '.$substrings[1].' '.UNIT_NAMES[$substrings[2]]).(empty($substrings[3]) ? '' : ' ('.$substrings[3].'%)'));
                            }
                            
                            echo implode('<br />', $new_rows);
                            ?>
                        </td>
                        <td><?= $row['in_print'] == 1 ? "На печати" : "" ?></td>
                        <td><?= $row['in_lamination'] == 1 ? "На ламинации" : "" ?></td>
                        <td><?= $row['in_cut'] == 1 ? "На резке" : "" ?></td>
                        <td><?= htmlentities($row['comment']) ?></td>
                        <td><a href="details.php<?= BuildQuery("id", $row['id']) ?>"><img src="<?=APPLICATION ?>/images/icons/vertical-dots.svg" /></a></td>
                    </tr>
                    <?php endwhile; ?>
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