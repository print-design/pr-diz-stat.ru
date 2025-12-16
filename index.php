<?php
include 'include/topscripts.php';
?>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <?php
        include 'include/head.php';
        ?>
        <style>
            #topmost {
                height: 85px;
            }
        </style>
    </head>
    <body>
        <?php
        include 'include/header.php';
        ?>
        <div class="container-fluid">
            <?php
            if(!empty($error_message)) {
               echo "<div class='alert alert-danger mt-3'>$error_message</div>";
            }
            ?>
            <h1>Принт-Дизайн</h1>
            <h2>Статистика брака</h2>
            <?php if(!LoggedIn()): ?>
            <a href="mobile.php" title="Мобильная версия" class="btn btn-dark d-none" style="height: 12rem; padding: 2rem; font-size: 8rem;"><i class="fas fa-mobile-alt"></i></a>
            <?php endif; ?>
        </div>
        <?php
        include 'include/footer.php';
        ?>
    </body>
</html>