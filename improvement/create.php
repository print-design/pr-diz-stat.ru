<?php
include '../include/topscripts.php';

// Авторизация
if(!IsInRole(array(ROLE_NAMES[ROLE_TECHNOLOGIST], ROLE_NAMES[ROLE_CUTTER]))) {
    header('Location: '.APPLICATION.'/unauthorized.php');
}
?>
<html>
    <head>
        <?php
        include '../include/head.php';
        ?>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php
        include '../include/style_mobile.php';
        ?>
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
            <h1>Предложение по улучшению</h1>
            <form method="post">
                <div class="form-group">
                    <label for="title">Заголовок</label>
                    <input type="text" class="form-control" name="title" required="required" />
                </div>
                <div class="form-group">
                    <label for="body">Текст предложения</label>
                    <textarea class="form-control" name="body" rows="4" required="required"></textarea>
                </div>
                <div class="form-group">
                    <label for="body">Что изменится в результате улучшения</label>
                    <textarea class="form-control" name="effect" rows="4"></textarea>
                </div>
                <button type="submit" class="btn btn-dark">Подать</button>
            </form>
        </div>
        <?php
        include '../include/footer.php';
        include '../include/footer_mobile.php';
        ?>
    </body>
</html>