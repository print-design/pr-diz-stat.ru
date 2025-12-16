<?php
include '../include/topscripts.php';

// Авторизация
if(LoggedIn()) {
    if(!IsInRole(array(ROLE_NAMES[ROLE_MANAGER_SENIOR]))) {
        header('Location: create.php');
    }
}
else {
    header('Location: '.APPLICATION.'/unauthorized.php');
}
?>
<!DOCTYPE html>
<html>
    <body>
        <h1>Предложения по улучшению</h1>
    </body>
</html>