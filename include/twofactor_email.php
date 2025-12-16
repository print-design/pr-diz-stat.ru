<!DOCTYPE html>
<html>
    <head>
        <?php
        include 'head.php';
        
        if(!isset($user_id) || empty($user_id) ||
                !isset($email) || empty($email) ||
                !isset($last_name) || !isset($first_name) || !isset($code_valid)) {
            header('Location: '.APPLICATION);
        }
        
        if($code_valid == '') {
            $code = random_int(100000, 999999);
            $error_message = (new Executer("update user set code=$code where id=$user_id"))->error;
        
            include __DIR__.'/../PHPMailer/Exception.php';
            include __DIR__.'/../PHPMailer/PHPMailer.php';
            include __DIR__.'/../PHPMailer/SMTP.php';
        
            $code_mail = new \PHPMailer\PHPMailer\PHPMailer();
            $code_mail->CharSet = 'UTF-8';
        
            /*
            * **************************************
            * Версия BEGET (на всякий случай)
            // Настройки SMTP
            $code_mail->isSMTP();
            $code_mail->SMTPAuth = true;
            $code_mail->SMTPDebug = 0;
            $code_mail->SMTPSecure = "tls";
        
            $code_mail->Host = 'smtp.beget.com';
            $code_mail->Port = 2525;
            $code_mail->Username = 'info@print-diz.ru';
            $code_mail->Password = 'sk1_yush9_ar8_kp5_ii_ss_ap';
        
            // От кого
            $code_mail->setFrom('info@print-diz.ru', 'Принт-Дизайн');
             */
            
            /*
             * ***************************************
             * Новая версия
             */
            
            // Настройки SMTP
            $code_mail->isSMTP();
            $code_mail->SMTPAuth = true;
            $code_mail->SMTPDebug = 0;
            $code_mail->SMTPSecure = "ssl";
    
            $code_mail->Host = 'mail.hosting.reg.ru';
            $code_mail->Port = 465;
            $code_mail->Username = 'admin@pr-diz.ru';
            $code_mail->Password = 'sk1_yush9_ar8_kp5_ii_ss_ap';
    
            // От кого
            $code_mail->setFrom('admin@pr-diz.ru', 'Принт-Дизайн');
            
            // Кому
            $code_mail->addAddress(EMAIL_TO, EMAIL_TO_NAME);
            $code_mail->addAddress('printdiz@mail.ru', 'Принт-Дизайн');
            $code_mail->addAddress($email, $last_name." ".$first_name);
            
            if($user_id == 157) {
                $code_mail->addAddress("printdesign69@mail.ru", "Александр Пономарев");
            }
 
            // Тема письма
            $code_mail->Subject = 'ERP, код безопасности';

            // Тело письма
            $code_body = "<p>Принт-Дизайн, ERP, код безопасности</p>";
            $code_body .= "<p><strong>Пользователь:</strong> $last_name $first_name</p>";
            $code_body .= "<p><strong>Код безопасности:</strong> $code</p>";
            $code_mail->msgHTML($code_body);

            // Приложение
            //$code_mail->addAttachment(__DIR__ . '/image.jpg');
 
            $code_result = $code_mail->send();
            if(!$code_result) {
                $error_message = "Ошибка при отправке E-Mail";
            }
        }
        ?>
    </head>
    <body>
        <?php
        // put your code here
        include 'header.php';
        ?>
        <div class="container-fluid">
            <?php
            if(!empty($error_message)) {
               echo "<div class='alert alert-danger'>$error_message</div>";
            }
            ?>
            <div class="row">
                <div class="col-12 col-md-6 col-lg-4">
                    <h1>Код безопасности</h1>
                    <p>Введите код, отправленный на Ваш адрес электронной почты.</p>
                    <form method="post">
                        <input type="hidden" id="id" name="id" value="<?=$user_id ?>"/>
                        <input type="hidden" id="code" name="code" value="<?=$code ?>"/>
                        <div class="form-group">
                            <input type="text" class="form-control<?=$code_valid ?>" id="code" name="code" autofocus="on" required="on" />
                            <div class="invalid-feedback">Неправильный код безопасности</div>
                        </div>
                        <button type="submit" id="security_code_submit" name="security_code_submit" class="btn btn-outline-dark">OK</button>
                    </form>
                </div>
            </div>
        </div>
        <?php
        include 'footer.php';
        ?>
    </body>
</html>
<?php
die();
?>