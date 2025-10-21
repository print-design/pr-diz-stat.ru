<?php
include '../include/topscripts.php';

// Авторизация
if(!IsInRole(array(ROLE_NAMES[ROLE_TECHNOLOGIST], ROLE_NAMES[ROLE_MANAGER], ROLE_NAMES[ROLE_MANAGER_SENIOR]))) {
    header('Location: '.APPLICATION.'/unauthorized.php');
}

// Формирование ссылки для сортировки по столбцу
function OrderLink($param) {
    if(array_key_exists('order', $_REQUEST) && $_REQUEST['order'] == $param) {
        echo "<strong><i class='fas fa-arrow-down' style='color: black; font-size: small;'></i></strong>";
    }
    else {
        echo "<a class='gray' href='".BuildQueryAddRemove("order", $param, "page")."' style='font-size: x-small;'><i class='fas fa-arrow-down'></i></a>";
    }
}

// Слово "тиражи" в разных падеждах в завиисимости от количества тиражей
function GetPrintingsWithCases($number) {
    $result = "тиражи";
    
    switch($number) {
        case 1:
            $result = "тираж";
            break;
        
        case 2:
            $result = "тиража";
            break;
        
        case 3:
            $result = "тиража";
            break;
        
        case 4:
            $result = "тиража";
            break;
        
        default :
            $result = "тиражей";
            break;;
    }
    
    return $result;
}

// Отображение статуса
function ShowOrderStatus($status_id, $length_cut, $weight_cut, $quantity_sum, $quantity, $unit, $raport, $length, $gap_raport, $status_comment) {
    include '../include/order_status_index.php';
}

// !!!!!! Удаляем все двойные или тройные пробелы в названиях расчётов (иначе будут проблемы в поиске по названию).
$sql = "update calculation set name = replace(name, '  ', ' ') where name like('%  %')";
$executer = new Executer($sql);
?>
<!DOCTYPE html>
<html>
    <head>
        <?php
        include '../include/head.php';
        ?>
        <link href="<?=APPLICATION ?>/css/select2.min.css" rel="stylesheet"/>
    </head>
    <body>
        <?php
        include '../include/header.php';
        include '../include/status_track.php';
        include '../include/pager_top.php';
        $rowcounter = 0;
        ?>
        <div class="container-fluid">
            <?php
            if(!empty($error_message)) {
                echo "<div class='alert alert-danger'>$error_message</div>";
            }
            ?>
            <a class="btn btn-outline-dark backlink" href="<?= APPLICATION."/reclamation/" ?>">Назад</a>
            <div class="d-flex justify-content-between mb-auto">
                <div class="p-1 text-nowrap">
                    <h1 style="font-size: 32px; font-weight: 600;">Выберите заказ, по которому рекламация</h1>
                    <?php
                    $where = " where (select status_id from calculation_status_history where calculation_id = c.id order by date desc limit 1) = ". ORDER_STATUS_SHIPPED;
                    
                    $unit = filter_input(INPUT_GET, 'unit');
                    if(!empty($unit)) {
                        $where .= " and c.unit = '$unit'";
                    }
                    
                    $work_type = filter_input(INPUT_GET, 'work_type');
                    if(!empty($work_type)) {
                        $where .= " and c.work_type = $work_type";
                    }
                    
                    $manager = filter_input(INPUT_GET, 'manager');
                    if(empty($manager) && !IsInRole(array(ROLE_NAMES[ROLE_TECHNOLOGIST], ROLE_NAMES[ROLE_MANAGER_SENIOR]))) {
                        $manager = GetUserId();
                    }
                    if(!empty($manager)) {
                        $where .= " and c.manager_id = $manager";
                    }
                    
                    $customer = filter_input(INPUT_GET, 'customer');
                    if(!empty($customer)) {
                        $where .= " and c.customer_id = $customer";
                    }
                    
                    $name = filter_input(INPUT_GET, 'name');
                    if(!empty($name)) {
                        $where .= " and (c.name) = '$name'";
                    }
                    
                    $find = trim(filter_input(INPUT_GET, 'find') ?? '');
                    if(!empty($find)) {
                        $find_substrings = explode('-', $find);
                        if(count($find_substrings) != 2 || intval($find_substrings[0]) == 0 || intval($find_substrings[1]) == 0) {
                            $where .= " and false";
                        }
                        else {
                            $where .= " and c.customer_id = ". intval($find_substrings[0])." and (select count(id) from calculation where customer_id = c.customer_id and id <= c.id) = ". intval($find_substrings[1]);
                        }
                    }
                    
                    // Общее количество расчётов для установления количества страниц в постраничном выводе
                    $sql = "select count(c.id) from calculation c left join customer cus on c.customer_id = cus.id$where";
                    $fetcher = new Fetcher($sql);
                    
                    if($row = $fetcher->Fetch()) {
                        $pager_total_count = $row[0];
                    }
                    ?>
                </div>
                <div class="p-1 text-nowrap">
                    <?php $order = filter_input(INPUT_GET, 'order'); ?>
                    <form class="form-inline d-inline" method="get">
                        <input type="hidden" name="status" value="<?= filter_input(INPUT_GET, 'status') ?>" />
                        <?php if(null !== $order): ?>
                        <input type="hidden" name="order" value="<?=$order ?>" />
                        <?php endif; ?>
                        <select id="unit" name="unit" class="form-control" multiple="multiple" onchange="javascript: this.form.submit();">
                            <option value="">Шт/кг...</option>
                            <option value="pieces"<?= filter_input(INPUT_GET, 'unit') == 'pieces' ? " selected='selected'" : "" ?>>Шт</option>
                            <option value="kg"<?= filter_input(INPUT_GET, 'unit') == 'kg' ? " selected='selected'" : "" ?>>Кг</option>
                        </select>
                        <select id="work_type" name="work_type" class="form-control" multiple="multiple" onchange="javascript: this.form.submit();">
                            <option value="">Тип работы...</option>
                            <?php foreach(WORK_TYPES as $item): ?>
                            <option value="<?=$item ?>"<?=($item == filter_input(INPUT_GET, 'work_type') ? " selected='selected'" : "") ?>><?=WORK_TYPE_NAMES[$item] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if(IsInRole(array(ROLE_NAMES[ROLE_TECHNOLOGIST], ROLE_NAMES[ROLE_MANAGER_SENIOR]))): ?>
                        <select id="manager" name="manager" class="form-control" multiple="multiple" onchange="javascript: this.form.submit();">
                            <option value="">Менеджер...</option>
                            <?php
                            $sql = "select distinct u.id, u.last_name, u.first_name from calculation c inner join user u on c.manager_id = u.id order by u.last_name";
                            $fetcher = new Fetcher($sql);
                            
                            while ($row = $fetcher->Fetch()):
                            ?>
                            <option value="<?=$row['id'] ?>"<?=($row['id'] == filter_input(INPUT_GET, 'manager') ? " selected='selected'" : "") ?>><?=(mb_strlen($row['first_name']) == 0 ? '' : mb_substr($row['first_name'], 0, 1).'. ').$row['last_name'] ?></option>
                            <?php
                            endwhile;
                            ?>
                        </select>
                        <?php endif; ?>
                        <select id="customer" name="customer" class="form-control" multiple="multiple" onchange="javascript: this.form.submit();">
                            <option value="">Заказчик...</option>
                            <?php
                            $customer_where = "";
                            $customer_manager = filter_input(INPUT_GET, 'manager');
                            if(!IsInRole(array(ROLE_NAMES[ROLE_TECHNOLOGIST], ROLE_NAMES[ROLE_MANAGER_SENIOR]))) {
                                $customer_manager = GetUserId();
                            }
                            if(!empty($customer_manager)) {
                                $customer_where .= "where c.manager_id = $customer_manager";
                            }
                            $sql = "select distinct cus.id, cus.name from calculation c inner join customer cus on c.customer_id = cus.id $customer_where order by cus.name";
                            $fetcher = new Fetcher($sql);
                            
                            while ($row = $fetcher->Fetch()):
                            ?>
                            <option value="<?=$row['id'] ?>"<?=($row['id'] == filter_input(INPUT_GET, 'customer') ? " selected='selected'" : "") ?>><?=$row['name'] ?></option>
                            <?php
                            endwhile;
                            ?>
                        </select>
                        <select id="name" name="name" class="form-control" multiple="multiple" onchange="javascript: this.form.submit();">
                            <option value="">Наименование...</option>
                            <?php
                            if($status_id == ORDER_STATUS_NOT_IN_WORK) {
                                $name_where = "where (select status_id from calculation_status_history where calculation_id = c.id order by date desc limit 1) in (". implode(', ', ORDER_STATUSES_NOT_IN_WORK).")";
                            }
                            elseif($status_id == ORDER_STATUS_IN_PRODUCTION) {
                                $name_where = "where (select status_id from calculation_status_history where calculation_id = c.id order by date desc limit 1) in (". implode(', ', ORDER_STATUSES_IN_PRODUCTION).")";
                            }
                            elseif(!empty ($status_id)) {
                                $name_where = "where (select status_id from calculation_status_history where calculation_id = c.id order by date desc limit 1) = $status_id";
                            }
                            else {
                                $name_where = "where (select status_id from calculation_status_history where calculation_id = c.id order by date desc limit 1) in (". implode(', ', ORDER_STATUSES_IN_WORK).")";
                            }
                            
                            $customer = filter_input(INPUT_GET, 'customer');
                            if(!empty($customer)) {
                                $name_where .= " and c.customer_id = $customer";
                                
                                $customer_manager = filter_input(INPUT_GET, 'manager');
                                if(!IsInRole(array(ROLE_NAMES[ROLE_TECHNOLOGIST], ROLE_NAMES[ROLE_MANAGER_SENIOR]))) {
                                    $customer_manager = GetUserId();
                                }
                                if(!empty($customer_manager)) {
                                    $name_where .= " and c.manager_id = $customer_manager";
                                }
                            }
                            
                            $sql = "select distinct trim(c.name) name from calculation c $name_where order by trim(c.name)";
                            $fetcher = new Fetcher($sql);
                            
                            while ($row = $fetcher->Fetch()):
                            ?>
                            <option<?=($row['name'] == filter_input(INPUT_GET, 'name') ? " selected='selected'" : "") ?>><?=$row['name'] ?></option>
                            <?php
                            endwhile;
                            ?>
                        </select>
                    </form>
                </div>
            </div>
            <table class="table table-hover" id="content_table">
                <thead>
                    <tr>
                        <th>ID&nbsp;&nbsp;<?= OrderLink('id') ?></th>
                        <th>Дата расчета&nbsp;&nbsp;<?= OrderLink('date') ?></th>
                        <th>Заказчик&nbsp;&nbsp;<?= OrderLink('customer') ?></th>
                        <th>Имя заказа&nbsp;&nbsp;<?= OrderLink('name') ?></th>
                        <th class="text-center">Объем&nbsp;&nbsp;<?= OrderLink('quantity') ?></th>
                        <th>Тип работы&nbsp;&nbsp;<?= OrderLink('work_type') ?></th>
                        <th>Менеджер&nbsp;&nbsp;<?= OrderLink('manager') ?></th>
                        <th>Статус&nbsp;&nbsp;</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Сортировка
                    $orderby = "order by status_date desc, c.id desc";
                    
                    if(array_key_exists('order', $_REQUEST)) {
                        switch ($_REQUEST['order']) {
                            case 'id':
                                $orderby = "order by c.customer_id desc, c.id desc";
                                break;
                            
                            case 'date':
                                $orderby = "order by c.id desc";
                                break;
                            
                            case 'customer':
                                $orderby = "order by cus.name asc";
                                break;
                            
                            case 'name':
                                $orderby = "order by c.name asc";
                                break;
                            
                            case 'quantity':
                                $orderby = "order by c.quantity desc";
                                break;
                            
                            case 'work_type':
                                $orderby = "order by c.work_type_id";
                                break;
                            
                            case 'manager':
                                $orderby = "order by u.last_name asc, u.first_name asc";
                                break;
                            
                            case 'status':
                                $orderby = "order by status_id";
                                break;
                        }
                    }
                    
                    $sql = "select c.id, c.date, c.customer_id, cus.name customer, trim(c.name) name, c.quantity, "
                            . "c.unit, c.work_type_id, u.last_name, u.first_name, c.raport, c.length, "
                            . "(select count(quantity) from calculation_quantity where calculation_id = c.id) quantities, "
                            . "(select sum(quantity) from calculation_quantity where calculation_id = c.id) quantity_sum, "
                            . "(select gap_raport from norm_gap where date <= c.date order by id desc limit 1) as gap_raport, "
                            . "ifnull((select sum(length) from calculation_take_stream where calculation_take_id in (select id from calculation_take where calculation_id = c.id)), 0) "
                            . "+ ifnull((select sum(length) from calculation_not_take_stream where calculation_stream_id in (select id from calculation_stream where calculation_id = c.id)), 0) length_cut, "
                            . "ifnull((select sum(weight) from calculation_take_stream where calculation_take_id in (select id from calculation_take where calculation_id = c.id)), 0) "
                            . "+ ifnull((select sum(weight) from calculation_not_take_stream where calculation_stream_id in (select id from calculation_stream where calculation_id = c.id)), 0) weight_cut, "
                            . "(select status_id from calculation_status_history where calculation_id = c.id order by date desc limit 1) status_id, "
                            . "(select comment from calculation_status_history where calculation_id = c.id order by date desc limit 1) status_comment, "
                            . "(select date from calculation_status_history where calculation_id = c.id order by date desc limit 1) status_date, "
                            . "(select count(id) from calculation where customer_id = c.customer_id and id <= c.id) num_for_customer "
                            . "from calculation c "
                            . "left join customer cus on c.customer_id = cus.id "
                            . "left join user u on c.manager_id = u.id$where "
                            . "$orderby limit $pager_skip, $pager_take";
                    $fetcher = new Fetcher($sql);
                    
                    while($row = $fetcher->Fetch()):
                        
                    $rowcounter++;
                    $quantity = empty($row['quantities']) ? number_format($row['quantity'] ?? 0, 0, ",", " ")." ".($row['unit'] == KG ? "кг" : "шт") : $row['quantities']." ".GetPrintingsWithCases($row['quantities']);
                    ?>
                    <tr>
                        <td class="text-nowrap"><?=$row['customer_id'].'-'.$row['num_for_customer'] ?></td>
                        <td class="text-nowrap"><?= DateTime::createFromFormat('Y-m-d H:i:s', $row['date'])->format('d.m.Y') ?></td>
                        <td><a href="javascript: void(0);" class="customer" data-toggle="modal" data-target="#customerModal" data-customer-id="<?=$row['customer_id'] ?>"><?=$row['customer'] ?></a></td>
                        <td><?= htmlentities($row['name'] ?? '') ?></td>
                        <td class="text-right"><?=$quantity ?></td>
                        <td><?= WORK_TYPE_NAMES[$row['work_type_id']] ?></td>
                        <td class="text-nowrap"><?=(mb_strlen($row['first_name']) == 0 ? '' : mb_substr($row['first_name'], 0, 1).'. ').$row['last_name'] ?></td>
                        <td class="text-nowrap" data-toggle="modal" data-target="#status_track" style="cursor: pointer;" onclick="javascript: StatusTrack(<?=$row['id'] ?>);"><?= ShowOrderStatus($row['status_id'], $row['length_cut'], $row['weight_cut'], $row['quantity_sum'], $row['quantity'], $row['unit'], $row['raport'], $row['length'], $row['gap_raport'], $row['status_comment']) ?></td>
                        <td><a class="btn btn-light" href="<?= APPLICATION ?>/reclamation/create.php?calculation_id=<?=$row['id'] ?>">Выбрать</a></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php
            if($rowcounter == 0) {
                echo '<p>Ничего не найдено.</p>';
            }
            
            include '../include/pager_bottom.php';
            ?>
        </div>
        <!-- Информация о заказчике -->
        <div class="modal fixed-left fade" id="customerModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-aside" role="document">
                <div class="modal-content" style="padding-left: 32px; padding-right: 32px; padding-bottom: 32px; padding-top: 84px; width: 521px; overflow-y: auto;"></div>
            </div>
        </div>
        <?php
        include '../include/footer.php';
        ?>
        <script src="<?=APPLICATION ?>/js/select2.min.js"></script>
        <script src="<?=APPLICATION ?>/js/i18n/ru.js"></script>
        <script>
            // Список с  поиском
            $('#unit').select2({
                placeholder: "Шт/кг...",
                maximumStatusLength: 1,
                language: "ru",
                width: '4rem'
            })
            
            $('#status').select2({
                placeholder: "Статус...",
                maximumSelectionLength: 1,
                language: "ru"
            });
            
            $('#work_type').select2({
                placeholder: "Тип работы...",
                maximumSelectionLength: 1,
                language: "ru",
                width: '8rem'
            });
            
            $('#manager').select2({
                placeholder: "Менеджер...",
                maximumSelectionLength: 1,
                language: "ru",
                width: '8rem'
            });
            
            $('#customer').select2({
                placeholder: "Заказчик...",
                maximumSelectionLength: 1,
                language: "ru",
                width: '15rem'
            });
            
            $('#name').select2({
                placeholder: "Наименование...",
                maximumSelectionLength: 1,
                language: "ru",
                width: '15rem'
            });
            
            // Заполнение информации о заказчике
            $('a.customer').click(function(e) {
                var customer_id = $(e.target).attr('data-customer-id');
                if(customer_id !== null) {
                    $.ajax({ url: "_customer.php?id=" + customer_id })
                            .done(function(data) {
                                $('#customerModal .modal-dialog .modal-content').html(data);
                    });
                }
            });
        </script>
    </body>
</html>