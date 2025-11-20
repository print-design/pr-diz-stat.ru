<?php
include '../include/topscripts.php';

// Авторизация
if(!IsInRole(array(ROLE_NAMES[ROLE_TECHNOLOGIST], ROLE_NAMES[ROLE_MANAGER], ROLE_NAMES[ROLE_MANAGER_SENIOR]))) {
    header('Location: '.APPLICATION.'/unauthorized.php');
}
?>
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
                <h1>Статистика по печатнику</h1>
            </div>
            <div class="row">
                <div class="col-4">
                    <h2>Рекламации</h2>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Печатник</th>
                                <th>Рекламаций всего</th>
                                <th class="text-right">В процентах</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "";
                            ?>
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
                            <h2><?=$defect['defect_type'] == DEFECT_TYPE_OTHER ? $defect['other_defect_type'] : DEFECT_TYPE_NAMES[$defect['defect_type']] ?></h2>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Печатник</th>
                                        <th>Дефектов всего</th>
                                        <th class="text-right">В процентах</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "";
                                    ?>
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