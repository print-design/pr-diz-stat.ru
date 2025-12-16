<?php
require '../vendor/autoload.php';
use chillerlan\QRCode\QRCode;
?>
<!DOCTYPE html>
<html>
    <body>
        <?php
        $data = "https://pr-diz.ru/improvement/";
        $qrcode = (new QRCode)->render($data);
        ?>
        <img src="<?=$qrcode ?>" alt="QR Code" />
    </body>
</html>