<?php
include '../include/topscripts.php';

$id = filter_input(INPUT_GET, 'id');

if(!empty($id)):
$sql = "select c.date, c.name, c.person, c.phone, c.extension, c.email, u.id user_id, u.last_name, u.first_name "
        . "from customer c "
        . "inner join user u on c.manager_id = u.id "
        . "where c.id = $id";
$fetcher = new Fetcher($sql);
if($row = $fetcher->Fetch()):
?>
<h2><?=$row['name'] ?></h2>
<table class="w-100 mt-3">
    <tr>
        <td class="pr-3 pb-3">Дата регистрации:</td>
        <td><?=DateTime::createFromFormat('Y-m-d H:i:s', $row['date'])->format('d.m.Y') ?></td>
    </tr>
    <tr>
        <td class="pr-3 pb-3">Имя представителя:</td>
        <td class="pb-3" id="customer_card_person_td">
            <div id="customer_card_person" class="d-flex justify-content-between">
                <div id="customer_card_person_value"><?=$row['person'] ?></div>
                <div><a href="javascript: void(0);" onclick="EditCustomerPerson();"><img src="../images/icons/edit1.svg" title="Редактировать" /></a></div>
            </div>
            <div id="customer_card_person_edit" class="d-none justify-content-between">
                <div><input type="text" class="form-control" id="customer_card_person_input" value="<?= htmlentities($row['person'] ?? '') ?>" /></div>
                <div>
                    <a class="btn btn-outline-dark d-none" onclick="CancelCustomerPerson();" href="javascript: void(0);"><i class="fas fa-undo"></i></a>
                    <a class="btn btn-dark" onclick="OKCustomerPerson(<?=$id ?>);" href="javascript: void(0);">OK</a>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td class="pr-3 pb-3">Номер телефона:</td>
        <td class="pb-3" id="customer_card_phone_td">
            <div id="customer_card_phone" class="d-flex justify-content-between">
                <div id="customer_card_phone_value" data-phone="<?=$row['phone'] ?>" data-extension="<?=$row['extension'] ?>"><?=$row['phone'].(empty($row['extension']) ? "" : " (доп. ".$row['extension'].")") ?></div>
                <div><a href="javascript: void(0);" onclick="EditCustomerPhone();"><img src="../images/icons/edit1.svg" title="Редактировать" /></a></div>
            </div>
            <div id="customer_card_phone_edit" class="d-none justify-content-between">
                <div>
                    <input type="tel" class="form-control" id="customer_card_phone_input" value="<?=$row['phone'] ?>" />
                    <input type="text" class="form-control int-only" id="customer_card_extension_input" value="<?=$row['extension'] ?>" placeholder="Добавочный" />
                </div>
                <div>
                    <a class="btn btn-outline-dark d-none" onclick="CancelCustomerPhone();" href="javascript: void(0);"><i class="fas fa-undo"></i></a>
                    <a class="btn btn-dark" onclick="OKCustomerPhone(<?=$id ?>);" href="javascript: void(0);">OK</a>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td class="pr-3 pb-3">E-mail:</td>
        <td class="pb-3" id="customer_card_email_td">
            <div id="customer_card_email" class="d-flex justify-content-between">
                <div id="customer_card_email_value"><?=$row['email'] ?></div>
                <div><a href="javascript: void(0);" onclick="EditCustomerEmail();"><img src="../images/icons/edit1.svg" title="Редактировать" /></a></div>
            </div>
            <div id="customer_card_email_edit" class="d-none justify-content-between">
                <div><input type="email" class="form-control" id="customer_card_email_input" value="<?=$row['email'] ?>" /></div>
                <div>
                    <a class="btn btn-outline-dark d-none" onclick="CancelCustomerEmail();" href="javascript: void(0);"><i class="fas fa-undo"></i></a>
                    <a class="btn btn-dark" onclick="OKCustomerEmail(<?=$id ?>);" href="javascript: void(0);">OK</a>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td class="pr-3 pb-3">Менеджер:</td>
        <td class="pb-3" id="customer_card_manager_td">
            <div id="customer_card_manager" class="d-flex justify-content-between">
                <div id="customer_card_manager_value" data-id='<?=$row['user_id'] ?>'><?=$row['last_name'].' '.$row['first_name'] ?></div>
                <div><a href="javascript: void(0);" class="d-none" onclick="EditCustomerManager();"><img src="../images/icons/edit1.svg" title="Редактировать" /></a></div>
            </div>
            <div id="customer_card_manager_edit" class="d-none justify-content-between">
                <div>
                    <select class="form-control" id="customer_card_manager_select">
                        <?php
                        $u_sql = "select id, last_name, first_name from user where role_id = ".ROLE_MANAGER
                                . " union "
                                . "select id, last_name, first_name from user where id = ".$row['user_id']
                                . " order by last_name, first_name";
                        $u_fetcher = new Fetcher($u_sql);
                        while ($u_row = $u_fetcher->Fetch()):
                            $manager_selected = $row['user_id'] == $u_row['id'] ? " selected='selected'" : "";
                        ?>
                        <option value="<?=$u_row['id'] ?>"<?=$manager_selected ?>><?=$u_row['last_name'].' '.$u_row['first_name'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div>
                    <a class="btn btn-outline-dark d-none" onclick="CancelCustomerManager();" href="javascript: void(0);"><i class="fas fa-undo"></i></a>
                    <a class="btn btn-dark" onclick="OKCustomerManager(<?=$id ?>);" href="javascript: void(0);">OK</a>
                </div>
            </div>
        </td>
    </tr>
</table>
<?php
endif;
endif;
?>
<button type="button" class="close" data-dismiss='modal' style="position: absolute; right: 34px; top: 34px; z-index: 2000;"><img src="../images/icons/close_modal_red.svg" /></button>
<script>
    $.mask.definitions['~'] = "[+-]";
    $("#customer_card_phone_number_input").mask("+7 (999) 999-99-99");
    
    // Фильтрация ввода
    $('#customer_card_phone_extension_input').keypress(function(e) {
        if(/\D/.test(e.key)) {
            return false;
        }
    });
    
    $('#customer_card_phone_extension_input').keyup(function() {
        var val = $(this).val();
        val = val.replaceAll(/\D/g, '');
        
        if(val === '') {
            $(this).val('');
        }
        else {
            val = parseInt(val);
            
            if($(this).hasClass('int-format')) {
                val = Intl.NumberFormat('ru-RU').format(val);
            }
            
            $(this).val(val);
        }
    });
    
    $('#customer_card_phone_extension_input').change(function(e) {
        var val = $(this).val();
        val = val.replace(/[^\d]/g, '');
        
        if(val === '') {
            $(this).val('');
        }
        else {
            val = parseInt(val);
            
            if($(this).hasClass('int-format')) {
                val = Intl.NumberFormat('ru-RU').format(val);
            }
            
            $(this).val(val);
        }
    });
    
    $("#customer_card_phone_number_input").click(function() {
        var maskposition = $(this).val().indexOf("_");
        if(Number.isInteger(maskposition)) {
            $(this).prop("selectionStart", maskposition);
            $(this).prop("selectionEnd", maskposition);
        }
    });
    
    function EditCustomerPerson() {
        $('#customer_card_person').removeClass('d-flex');
        $('#customer_card_person').addClass('d-none');
        $('#customer_card_person_edit').removeClass('d-none');
        $('#customer_card_person_edit').addClass('d-flex');
    }
    
    function CancelCustomerPerson() {
        $('#customer_card_person_input').val($('#customer_card_person_value').text());
        $('#customer_card_person_edit').removeClass('d-flex');
        $('#customer_card_person_edit').addClass('d-none');
        $('#customer_card_person').removeClass('d-none');
        $('#customer_card_person').addClass('d-flex');
    }
    
    function OKCustomerPerson(id) {
        $.ajax({ url: "_customer_edit.php?id=" + id + "&person=" + encodeURIComponent($('#customer_card_person_input').val()) })
                .done(function(data) {
                    $('#customer_card_person_value').text(data);
                    CancelCustomerPerson();
        })
                .fail(function() {
                    alert('Ошибка при редактировании имени предствителя');
        });
    }
    
    function EditCustomerPhone() {
        $('#customer_card_phone').removeClass('d-flex');
        $('#customer_card_phone').addClass('d-none');
        $('#customer_card_phone_edit').removeClass('d-none');
        $('#customer_card_phone_edit').addClass('d-flex');
    }
    
    function CancelCustomerPhone() {
        $('#customer_card_phone_input').val($('#customer_card_phone_value').attr('data-phone'));
        $('#customer_card_extension_input').val($('#customer_card_phone_value').attr('data-extension'));
        $('#customer_card_phone_edit').removeClass('d-flex');
        $('#customer_card_phone_edit').addClass('d-none');
        $('#customer_card_phone').removeClass('d-none');
        $('#customer_card_phone').addClass('d-flex');
    }
    
    function OKCustomerPhone(id) {
        $.ajax({ dataType: 'JSON', url: "_customer_edit.php?id=" + id + "&phone=" + encodeURIComponent($('#customer_card_phone_input').val()) + "&extension=" + $('#customer_card_extension_input').val() })
                .done(function(data) {
                    $('#customer_card_phone_value').text(data.phone + (data.extension.length === 0 ? "" : " (доп. " + data.extension + ")"));
                    $('#customer_card_phone_value').attr('data-phone', data.phone);
                    $('#customer_card_phone_value').attr('data-extension', data.extension);
                    CancelCustomerPhone();
        })
                .fail(function() {
                    alert('Ошибка при редактировании телефона');
        });
    }
    
    function EditCustomerEmail() {
        $('#customer_card_email').removeClass('d-flex');
        $('#customer_card_email').addClass('d-none');
        $('#customer_card_email_edit').removeClass('d-none');
        $('#customer_card_email_edit').addClass('d-flex');
    }
    
    function CancelCustomerEmail() {
        $('#customer_card_email_input').val($('#customer_card_email_value').text());
        $('#customer_card_email_edit').removeClass('d-flex');
        $('#customer_card_email_edit').addClass('d-none');
        $('#customer_card_email').removeClass('d-none');
        $('#customer_card_email').addClass('d-flex');
    }
    
    function OKCustomerEmail(id) {
        $.ajax({ url: "_customer_edit.php?id=" + id + "&email=" + encodeURIComponent($('#customer_card_email_input').val()) })
                .done(function(data) {
                    $('#customer_card_email_value').text(data);
                    CancelCustomerEmail();
        })
                .fail(function() {
                    alert('Ошибка при редактировании email');
        });
    }
    
    function EditCustomerManager() {
        $('#customer_card_manager').removeClass('d-flex');
        $('#customer_card_manager').addClass('d-none');
        $('#customer_card_manager_edit').removeClass('d-none');
        $('#customer_card_manager_edit').addClass('d-flex');
    }
    
    function CancelCustomerManager() {
        $('#customer_card_manager_select').val($('#customer_card_manager_value').attr('data-id'));
        $('#customer_card_manager_edit').removeClass('d-flex');
        $('#customer_card_manager_edit').addClass('d-none');
        $('#customer_card_manager').removeClass('d-none');
        $('#customer_card_manager').addClass('d-flex');
    }
    
    function OKCustomerManager(id) {
        $.ajax({ dataType: 'JSON', url: "_customer_edit.php?id=" + id + "&manager_id=" + $('#customer_card_manager_select').val() })
                .done(function(data) {
                    $('#customer_card_manager_value').text(data.last_name + ' ' + data.first_name);
                    $('#customer_card_manager_value').attr('data-id', data.id);
                    CancelCustomerManager();
        })
                .fail(function() {
                    alert('Ошибка при редактировании менеджера');
        });
    }
</script>