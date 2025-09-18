<?php
include 'define.php';
include 'constants.php';

global $weekdays;

$weekdays = array();
$weekdays[0] = 'Вс';
$weekdays[1] = 'Пн';
$weekdays[2] = 'Вт';
$weekdays[3] = 'Ср';
$weekdays[4] = 'Чт';
$weekdays[5] = 'Пт';
$weekdays[6] = 'Сб';

global $weekday_names;

$weekday_names = array();
$weekday_names[0] = 'Воскресенье';
$weekday_names[1] = 'Понедельник';
$weekday_names[2] = 'Вторник';
$weekday_names[3] = 'Среда';
$weekday_names[4] = 'Четверг';
$weekday_names[5] = 'Пятница';
$weekday_names[6] = 'Суббота';

global $months_genitive;

$months_genitive = array();

$months_genitive[1] = "января";
$months_genitive[2] = "февраля";
$months_genitive[3] = "марта";
$months_genitive[4] = "апреля";
$months_genitive[5] = "мая";
$months_genitive[6] = "июня";
$months_genitive[7] = "июля";
$months_genitive[8] = "августа";
$months_genitive[9] = "сентабря";
$months_genitive[10] = "октября";
$months_genitive[11] = "ноября";
$months_genitive[12] = "декабря";

// Функции
function LoggedIn() {
    return !empty(filter_input(INPUT_COOKIE, USERNAME));
}

function GetUserId() {
    return filter_input(INPUT_COOKIE, USER_ID);
}

function IsInRole($role) {
    // Роль 'manager-senior' имеет все права роли 'manager'
    if(is_array($role) && in_array(ROLE_NAMES[ROLE_MANAGER], $role)) {
        array_push($role, ROLE_NAMES[ROLE_MANAGER_SENIOR]);
    }
    elseif($role == ROLE_NAMES[ROLE_MANAGER]) {
        $role = array(ROLE_NAMES[ROLE_MANAGER], ROLE_NAMES[ROLE_MANAGER_SENIOR]);
    }
    
    $cookie = filter_input(INPUT_COOKIE, ROLE);
    
    if(is_array($role)) {
        return in_array($cookie, $role);
    }
    else {
        return $cookie == $role;
    }
    
    return false;
}

function DisplayNumber($value, $decimals) {
    if(is_float($value) || is_double($value) || is_int($value)) {
        return number_format($value ?? 0, $decimals ?? 0, ",", " ");
    }
    elseif(is_string($value)) {
        return str_replace(".", ",", $value);
    }
    else {
        return $value;
    }
}

function Initials() {
    $last_name = filter_input(INPUT_COOKIE, LAST_NAME);
    $first_name = filter_input(INPUT_COOKIE, FIRST_NAME);
    $result = '';
    
    if(mb_strlen($last_name) > 1) {
        $result .= mb_substr($last_name, 0, 1);
    }
    else {
        $result .= $last_name;
    }
    
    if(mb_strlen($first_name) > 1) {
        $result .= mb_substr($first_name, 0, 1);
    }
    else {
        $result .= $first_name;
    }
    
    return $result;
}

function BuildQuery($key, $value) {
    $result = '';
    $get_params = $_GET;
    $get_params[$key] = $value;
    $result = http_build_query($get_params);
    
    if(!empty($result)) {
        $result = "?$result";
    }
    
    return $result;
}

function BuildQueryRemove($key) {
    $result = '';
    $get_params = $_GET;
    unset($get_params[$key]);
    $result = http_build_query($get_params);

    if(!empty($result)) {
        $result = "?$result";
    }
    
    return $result;
}

function BuildQueryRemoveArray($array) {
    $result = '';
    $get_params = $_GET;
    foreach($array as $key) {
        unset($get_params[$key]);
    }
    $result = http_build_query($get_params);

    if(!empty($result)) {
        $result = "?$result";
    }
    
    return $result;
}

function BuildQueryAddRemove($key, $value, $remove) {
    $result = '';
    $get_params = $_GET;
    $get_params[$key] = $value;
    unset($get_params[$remove]);
    $result = http_build_query($get_params);
    
    if(!empty($result)) {
        $result = "?$result";
    }
    
    return $result;
}

function BuildQueryAddRemoveArray($key, $value, $array) {
    $result = '';
    $get_params = $_GET;
    $get_params[$key] = $value;
    foreach($array as $item_key) {
        unset($get_params[$item_key]);
    }
    $result = http_build_query($get_params);
    
    if(!empty($result)) {
        $result = "?$result";
    }
    
    return $result;
}

function GetDateFromDateTo($getDateFrom, $getDateTo, &$dateFrom, &$dateTo) {
    $dateFrom = null;
    $dateTo = null;
    
    $diff7Days = new DateInterval('P7D');
    $diff14Days = new DateInterval('P14D');
    $diff30Days = new DateInterval('P30D');
    $diff1Day = new DateInterval('P1D');
    $diff3Months = new DateInterval('P3M');
    
    if($getDateFrom !== null && $getDateFrom !== '') {
        $dateFrom = DateTime::createFromFormat("Y-m-d", $getDateFrom);
    }
    
    if($getDateTo !== null && $getDateTo !== '') {
        $dateTo = DateTime::createFromFormat("Y-m-d", $getDateTo);
    }
    
    if($dateFrom !== null && $dateTo == null) {
        $dateTo = clone $dateFrom;
        $dateTo->add($diff3Months);
    }
    
    if($dateFrom == null && $dateTo !== null) {
        $dateFrom = clone $dateTo;
        $dateFrom->sub($diff3Months);
    }
    
    if($dateFrom !== null && $dateTo !== null && $dateFrom >= $dateTo) {
        $dateTo = clone $dateFrom;
    }
    
    if($dateFrom == null && $dateTo == null) {
        $dateFrom = new DateTime();
        $dateTo = clone $dateFrom;
        $dateTo->add($diff3Months);
    }
}

// Принудительная выгрузка (вместо открытия)
function DownloadSendHeaders($filename) {
    // disable caching
    $now = gmdate("D, d M Y H:i:s");
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
    header("Last-Modified: {$now} GMT");

    // force download
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");

    // disposition / encoding on response body
    header("Content-Disposition: attachment;filename={$filename}");
    header("Content-Transfer-Encoding: binary");
}

// Выгрузка картинки
if(null !== filter_input(INPUT_POST, 'download_image_submit')) {
    $object = filter_input(INPUT_POST, 'object');
    $id = filter_input(INPUT_POST, 'id');
    $image = filter_input(INPUT_POST, 'image');
    
    if(!empty($object) && !empty($id) && !empty($image)) {
        $sql = "";
        
        if($object == PRINTING) {
            $sql = "select concat(c.name, cq.id) name, cq.image$image, cq.pdf$image from calculation_quantity cq inner join calculation c on cq.calculation_id = c.id where cq.id = $id";
        }
        elseif ($object == STREAM) {
            $sql = "select name, image$image, pdf$image from calculation_stream where id = $id";
        }
        
        if(!empty($sql)) {
            $targetname = "image";
            $fetcher = new Fetcher($sql);
            
            if($row = $fetcher->Fetch()) {
                if(!empty($row['name'])) {
                    $targetname = $row['name'].'_'.$image;
                    $targetname = str_replace('.', '', $targetname);
                    $targetname = str_replace(',', '', $targetname);
                    $targetname = htmlspecialchars($targetname);
                }
                
                $filename = $row["image$image"];
                $filepath = "../content/$object/$filename";
                $extension = "";
                
                if(!empty($row["pdf$image"])) {
                    $filename = $row["pdf$image"];
                    $filepath = "../content/$object/pdf/$filename";
                    $extension = "pdf";
                }
                else {
                    $substrings = explode('.', $filename);
                    if(count($substrings) > 1) {
                        $extension = $substrings[count($substrings) - 1];
                    }
                }
                
                $targetname = $targetname.'.'.$extension;
                
                DownloadSendHeaders($targetname);
                readfile($filepath);
                exit();
            }
        }
    }
}

function Array2Csv(array &$array, $titles) {
    if (count($array) == 0) {
            return null;
    }
    ob_start();
    $df = fopen("php://output", 'w');
    fputs($df, chr(0xEF) . chr(0xBB) . chr(0xBF)); // Это для правильной кодировки
    fputcsv($df, $titles, ';');
    foreach ($array as $row) {
        fputcsv($df, $row, ';');
    }
    fclose($df);
    return ob_get_clean();
}

// Классы
class Executer {
    public $error = '';
    public $insert_id = 0;
            
    function __construct($sql) {
        $conn = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);

        if($conn->connect_error) {
            $this->error = 'Ошибка соединения: '.$conn->connect_error;
            return;
        }
        
        $conn->query('set names utf8');
        $conn->query($sql);
        $this->error = $conn->error;
        $this->insert_id = $conn->insert_id;
        
        $conn->close();
    }
}

class Grabber {
    public  $error = '';
    public $result = array();
            
    function __construct($sql) {
        $conn = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
        
        if($conn->connect_error) {
            $this->error = 'Ошибка соединения: '.$conn->connect_error;
            return;
        }
        
        $conn->query('set names utf8');
        $result = $conn->query($sql);
        
        if(is_bool($result)) {
            $this->error = $conn->error;
        }
        else {
            $this->result = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
        
        $conn->close();
    }
}

class Fetcher {
    public $error = '';
    private $result;
            
    function __construct($sql) {
        $conn = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
        
        if($conn->connect_error) {
            $this->error = 'Ошибка соединения: '.$conn->connect_error;
            return;
        }
        
        $conn->query('set names utf8');
        $this->result = $conn->query($sql);
        
        if(is_bool($this->result)) {
            $this->error = $conn->error;
        }
        
        $conn->close();
    }
    
    function Fetch() {
        return mysqli_fetch_array($this->result);
    }
}

// Валидация формы логина
$login_form_valid = true;

$login_username_valid = '';
$login_password_valid = '';

// Обработка отправки формы логина
if(null !== filter_input(INPUT_POST, 'login_submit')) {
    $login_username = filter_input(INPUT_POST, 'login_username');
    if(empty($login_username)) {
        $login_username_valid = ISINVALID;
        $login_form_valid = false;
    }
    
    $login_password = filter_input(INPUT_POST, 'login_password');
    if(empty($login_password)) {
        $login_password_valid = ISINVALID;
        $login_form_valid = false;
    }
    
    if($login_form_valid) {
        //*****************************************************
        // Сначала пробуем залогниться, как пользователь резки.
        $lower_username = strtolower($login_username);
        $lower_password = strtolower($login_password);
        
        if(in_array($lower_username, CUTTER_USERS) && $lower_username == $lower_password) {
            setcookie(USER_ID, CUTTER_USER_IDS[$lower_username], time() + 60 * 60 * 24 * 100000, "/");
            setcookie(USERNAME, $lower_username, time() + 60 * 60 * 24 * 100000, "/");
            setcookie(PASSWORD5, $lower_password, time() + 60 * 60 * 24 * 100000, "/");
            setcookie(LAST_NAME, '', time() + 60 * 60 * 24 * 100000, "/");
            setcookie(FIRST_NAME, '', time() + 60 * 60 * 24 * 100000, "/");
            setcookie(ROLE, $lower_username, time() + 60 * 60 * 24 * 100000, "/");
            setcookie(ROLE_LOCAL, CUTTER_USER_NAMES[$lower_username], time() + 60 * 60 * 24 * 100000, "/");
            setcookie(LOGIN_TIME, (new DateTime())->getTimestamp(), time() + 60 * 60 * 24 * 100000, "/");
            header('Refresh:0');
            exit();
        }
        //******************************************************
        
        // Если это не пользователь резки, то логинимся, как обычный пользователь.
        $user_id = '';
        $username = '';
        $password = '';
        $password5 = ''; // Первые 5 символов зашифрованного пароля
        $last_name = '';
        $first_name = '';
        $role = '';
        $role_local = '';
        $twofactor = 0;
        
        $sql = "select id, username, password, last_name, first_name, email, role_id "
                . "from user where username='$login_username' and password=password('$login_password') and active=true";
        
        $users_result = (new Grabber($sql))->result;
        
        foreach ($users_result as $row) {
            $user_id = $row['id'];
            $username = $row['username'];
            $password = $row['password'];
            if(strlen($password) > 5) {
                // Сохраняем первые 5 символов зашифрованного пароля (чтобы не хранить в куках весь пароль)
                $password5 = substr($password, 1, 5);
            }
            $last_name = $row['last_name'];
            $first_name = $row['first_name'];
            $role = ROLE_NAMES[$row['role_id']];
            $role_local = ROLE_LOCAL_NAMES[$row['role_id']];
            $email = $row['email'];
            $twofactor = ROLE_TWOFACTOR[$row['role_id']];
        }
        
        if(empty($user_id) || empty($username)) {
            $error_message = "Неправильный логин или пароль";
        }
        else {
            //*******************************
            // Двухфакторная аутентификация
            if($twofactor == 1) {
                $code_valid = '';
                include 'twofactor_email.php';
            }
            // ****************************
            
            setcookie(USER_ID, $user_id, time() + 60 * 60 * 24 * 100000, "/");
            setcookie(USERNAME, $username, time() + 60 * 60 * 24 * 100000, "/");
            setcookie(PASSWORD5, $password5, time() + 60 * 60 * 24 * 100000, "/");
            setcookie(LAST_NAME, $last_name, time() + 60 * 60 * 24 * 100000, "/");
            setcookie(FIRST_NAME, $first_name, time() + 60 * 60 * 24 * 100000, "/");
            setcookie(ROLE, $role, time() + 60 * 60 * 24 * 100000, "/");
            setcookie(ROLE_LOCAL, $role_local, time() + 60 * 60 * 24 * 100000, "/");
            setcookie(LOGIN_TIME, (new DateTime())->getTimestamp(), time() + 60 * 60 * 24 * 100000, "/");
            header("Refresh:0");
        }
    }
}

// Обработка формы отправки кода безопасности
if(null !== filter_input(INPUT_POST, 'security_code_submit')) {
    $id = filter_input(INPUT_POST, 'id');
    $sql = "select id, username, password, last_name, first_name, email, code, role_id "
            . "from user where id = $id";
    $result = (new Grabber($sql))->result;
    
    foreach ($result as $row) {
        $user_id = $row['id'];
        $username = $row['username'];
        $password = $row['password'];
        if(strlen($password) > 5) {
            $password5 = substr($password, 1, 5);
        }
        $last_name = $row['last_name'];
        $first_name = $row['first_name'];
        $role = ROLE_NAMES[$row['role_id']];
        $role_local = ROLE_LOCAL_NAMES[$row['role_id']];
        $email = $row['email'];
        $code = $row['code'];
        
        if(filter_input(INPUT_POST, 'code') == $code) {
            $error_message = (new Executer("update user set code=NULL where id=$user_id"))->error;
            
            if($error_message == '') {
                setcookie(USER_ID, $user_id, time() + 60 * 60 * 24 * 100000, "/");
                setcookie(USERNAME, $username, time() + 60 * 60 * 24 * 100000, "/");
                setcookie(PASSWORD5, $password5, time() + 60 * 60 * 24 * 100000, "/");
                setcookie(LAST_NAME, $last_name, time() + 60 * 60 * 24 * 100000, "/");
                setcookie(FIRST_NAME, $first_name, time() + 60 * 60 * 24 * 100000, "/");
                setcookie(ROLE, $role, time() + 60 * 60 * 24 * 100000, '/');
                setcookie(ROLE_LOCAL, $role_local, time() + 60 * 60 * 24 * 100000, '/');
                setcookie(LOGIN_TIME, (new DateTime())->getTimestamp(), time() + 60 * 60 * 24 * 100000, "/");
                header("Refresh:0");
            }
        }
        else {
            $code_valid = ISINVALID;
            include 'twofactor_email.php';
        }
    }
}

function Logout() {
    setcookie(USER_ID, '', time() + 60 * 60 * 24 * 100000, "/");
    setcookie(USERNAME, '', time() + 60 * 60 * 24 * 100000, "/");
    setcookie(PASSWORD5, '', time() + 60 * 60 * 24 * 100000, "/");
    setcookie(LAST_NAME, '', time() + 60 * 60 * 24 * 100000, "/");
    setcookie(FIRST_NAME, '', time() + 60 * 60 * 24 * 100000, "/");
    setcookie(LOGIN_TIME, '', time() + 60 * 60 * 24 * 100000, "/");
    setcookie(ROLE, '', time() + 60 * 60 * 24 * 100000, "/");
    setcookie(ROLE_LOCAL, '', time() + 60 * 60 * 24 * 100000, "/");
    header("Refresh:0");
    header('Location: '.APPLICATION.'/');
}

// Обработка кнопки выхода из системы
if(null !== filter_input(INPUT_POST, 'logout_submit')) {
    Logout();
}

// Выход из системы, если удалили пользователя или сменили пароль
if(LoggedIn() && !IsInRole(CUTTER_USERS)) {
    $username = filter_input(INPUT_COOKIE, USERNAME);
    $password5 = filter_input(INPUT_COOKIE, PASSWORD5);
    $sql = "select count(id) from user where username = '$username' and substring(password, 2, 5) = '$password5' and active=true";
    $row = (new Fetcher($sql))->Fetch();
    
    if($row[0] == 0) {
        Logout();
    }
}
?>