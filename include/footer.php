<div style="height: 50px;"></div>

<script src='<?=APPLICATION ?>/js/jquery-3.5.1.min.js'></script>
<script src='<?=APPLICATION ?>/js/bootstrap.min.js'></script>
<script src="<?=APPLICATION ?>/js/jquery-ui.js"></script>
<script src="<?=APPLICATION ?>/js/popper.min.js"></script>
<script src="<?=APPLICATION ?>/js/jquery.maskedinput.js"></script>
<script src="<?=APPLICATION ?>/js/calculation.js?version=100"></script>

<?php
// Если в этом разделе есть find.php, то (помимо включения его в header.php)
// включаем также сюда footer_find.php
if(file_exists('find.php')) {
    include '../include/footer_find.php';
}
?>

<script>
    // Отправка формы по нажатию Enter
    $('input').keypress(function(e) {
        if(e.which === 10 || e.which === 13) {
            $(e.target).focusout();
         
            submit_btn = $(e.target.form).find("button[type='submit']");
                
            if(submit_btn === null) {
                this.form.submit();
            }
            else {
                submit_btn.click();
            }
        }
    });
    
    // Фильтрация ввода
    $('.int-only').keypress(function(e) {
        if(/\D/.test(e.key)) {
            return false;
        }
    });
    
    $('.int-only').keyup(function() {
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
    
    $('.int-only').change(function(e) {
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
    
    $('.float-only').keypress(function(e) {
        if(!/[\.\,\d]/.test(e.key)) {
            return false;
        }
        
        if(/[\.\,]/.test(e.key) && ($(e.target).val().includes('.') || $(e.target).val().includes(','))) {
            return false;
        }
    });
    
    $('.float-only').change(function(e) {
        var val = $(this).val();
        val = val.replace(',', '.');
        val = val.replace(/[^\.\d]/g, '');
        
        if(val === '' || isNaN(val)) {
            $(this).val('');
        }
        else {
            val = parseFloat(val);
            $(this).val(val);
        }
    });
    
    $('.no-latin').keypress(function(e) {
        if(e.which !== 10 && e.which !== 13) {
            if(/[a-zA-Z]/.test(e.key)) {
                $(this).next('.invalid-feedback').text('Переключите раскладку');
                $(this).next('.invalid-feedback').show();
                return false;
            }
            else {
                $(this).next('.invalid-feedback').hide();
            }
        }
    });
    
    $('.no-latin').change(function() {
        var val = $(this).val();
        val = val.replace('[a-zA-Z]', '');
        $(this).val(val);
    });
    
    $('.no-latin').keyup(function() {
        var val = $(this).val();
        val = val.replace(/[a-zA-Z]/g, '');
        $(this).val(val);
    });
    
    
    // Фильтрация ввода (другой способ)
    function KeyDownIntValue(e) {
        if(e.which !== 8 && e.which !== 9 && e.which !== 46 && e.which !== 37 && e.which !== 39) {
            return /\d/.test(e.key);
        }
    }
    
    function KeyUpIntValue(e) {
        e.target.value = e.target.value.replace(/\D/g,'');
    }
    
    function ChangeIntValue(e) {
        e.target.value = e.target.value.replace(/\D/g,'');
    }
    
    function KeyDownFloatValue(e) {
        if(e.which !== 8 && e.which !== 9 && e.which !== 46 && e.which !== 37 && e.which !== 39) {
            if(!/[\.\,\d]/.test(e.key)) {
                return false;
            }
            
            if(/[\.\,]/.test(e.key) && (e.target.value.includes('.') || e.target.value.includes(','))) {
                return false;
            }
        }
    }
    
    function KeyUpFloatValue(e) {
        val = e.target.value;
        val = val.replace(',', '.');
        val = val.replace(/[^\.\d]/g, '');
        e.target.value = val;
    }
    
    function ChangeFloatValue(e) {
        val = e.target.value;
        val = val.replace(',', '.');
        val = val.replace(/[^\.\d]/g, '');
        val = parseFloat(val);
        if(!isNaN(val)) {
            e.target.value = val;
        }
    }
    
    // Ограничение значений для полей с целочисленными значениями (проценты и т. д.)
    // Обработка изменения нажатия клавиш
    function KeyDownLimitIntValue(textbox, e, max) {
        if(e.which !== 8 && e.which !== 9 && e.which !== 46 && e.which !== 37 && e.which !== 39) {
            if(/\D/.test(e.key)) {
                return false;
            }
            
            var text = textbox.val();
            var selStart = textbox.prop('selectionStart');
            var selEnd = textbox.prop('selectionEnd');
            var textStart = text.substring(0, selStart);
            var textEnd = text.substring(selEnd);
            var newvalue = textStart + e.key + textEnd;
            newvalue = newvalue.replace(/\D/g, ''); // целое число может быть разбито на разряды
            var iNewValue = parseInt(newvalue);
            
            if(iNewValue === null || iNewValue < 1 || iNewValue > max) {
                return false;
            }
        }
        
        return true;
    }
    
    // Ограничение значений для полей с целочисленными значениями (проценты и т. д.) 
    // Обработка отпускания клавиши
    function KeyUpLimitIntValue(textbox, max) {
        val = textbox.val().replace(/[^\d]/g, '');
        
        if(val !== null && val !== '' && !isNaN(val) && parseInt(val) > max) {
            textbox.addClass('is-invalid');
        }
        else {
            textbox.removeClass('is-invalid');
        }
    }
    
    // Ограничение значений для полей с целочисленными значениями (проценты и т. д.)
    // Обработка изменения текста
    function ChangeLimitIntValue(textbox, max) {
        val = textbox.val().replace(/[^\d]/g, '');
        textbox.val(val);
        
        if(isNaN(val)) {
            alert('Только целое значение от 1 до ' + max);
            textbox.val('');
            textbox.focus();
        }
        else if(val === null || val === '') {
            textbox.val('');
            textbox.focus();
        }
        else {
            iVal = parseInt(val);
            
            if(iVal < 1 || iVal > max) {
                alert('Только целое значение от 1 до ' + max);
                textbox.val('');
                textbox.focus();
            }
            else {
                textbox.val(iVal);
            }
        }
    }
    
    // Ограничение значений для полей с числовыми значениями (проценты и т. д.)
    // Обработка изменения нажатия клавиш
    function KeyDownLimitFloatValue(textbox, e, max) {
        if(e.which !== 8 && e.which !== 46 && e.which !== 37 && e.which !== 39) {
            if(!/[\.\,\d]/.test(e.key)) {
                return false;
            }
            
            if(/[\.\,]/.test(e.key) && (textbox.val().includes('.') || textbox.val().includes(',') || parseFloat(textbox.val()) >= max)) {
                return false;
            }
            
            var text = textbox.val();
            var selStart = textbox.prop('selectionStart');
            var selEnd = textbox.prop('selectionEnd');
            var textStart = text.substring(0, selStart);
            var textEnd = text.substring(selEnd);
            var newvalue = textStart + e.key + textEnd;
            var fNewValue = parseFloat(newvalue);
            
            if(fNewValue === null || fNewValue < 1 || fNewValue > max) {
                return false;
            }
        }
        
        return true;
    }
    
    // Ограничение значений для полей с числовыми значениями (проценты и т. д.)
    // Обработка изменения текста
    function ChangeLimitFloatValue(textbox, max) {
        var val = textbox.val();
        val = val.replace(',', '.');
        val = val.replace(/[^\.\d]/g, '');
        textbox.val(val);
        
        if(val === null || val === '' || isNaN(val)) {
            alert('Только целое значение от 0 до ' + max);
            textbox.val('');
            textbox.focus();
        }
        else {
            fVal = parseFloat(val);
            
            if(fVal < 0 || fVal > max) {
                alert('Только числовое значение от 0 до ' + max);
                textbox.val('');
                textbox.focus();
            }
            else {
                textbox.val(fVal);
            }
        }
    }
    
    // Форматирование целочисленного поля для отображения разрядов
    function IntFormat(textbox) {
        oldv = textbox.val();
        replv = oldv.replaceAll(/\D/g, '');
        
        if(replv === '') textbox.val('');
        else {
            val = Intl.NumberFormat('ru-RU').format(replv);
            textbox.val(val);
        }
    }
    
    // Запрет на изменение размеров всех многострочных текстовых полей вручную
    $('textarea').css('resize', 'none');
    
    // Валидация
    $('input').keypress(function(){
        $(this).removeClass('is-invalid');
    });
    
    $('select').change(function(){
        $(this).removeClass('is-invalid');
    });
    
    $.mask.definitions['~'] = "[+-]";
    $("#phone").mask("+7 (999) 999-99-99");
    
    // При щелчке в поле телефона, устанавливаем курсор в самое начало ввода телефонного номера.
    $("#phone").click(function(){
        var maskposition = $(this).val().indexOf("_");
        if(Number.isInteger(maskposition)) {
            $(this).prop("selectionStart", maskposition);
            $(this).prop("selectionEnd", maskposition);
        }
    });
    
    // Подтверждение удаления
    $('button.confirmable').click(function() {
        return confirm("Действительно удалить?");
    });
    
    // Отмена нажатия неактивной кнопки
    $('button.disabled').click(function() {
        return false;
    });
    
    // Всплывающая подсказка
    $(".ui_tooltip.left").tooltip({
        position: {
            my: "right center",
            at: "left-10 center"
        },
        tooltipClass: "left"
    });
    
    $(".ui_tooltip.right").tooltip({
        position: {
            my: "left center",
            at: "right+10 center"
        },
        tooltipClass: "right"
    });
    
    $(".ui_tooltip.top").tooltip({
        position: {
            my: "center bottom",
            at: "center top-10"
        },
        tooltipClass: "top"
    });
    
    $(".ui_tooltip.bottom").tooltip({
        position: {
            my: "center top",
            at: "center bottom+10"
        },
        tooltipClass: "bottom"
    });
    
    // Защита от двойного нажатия
    var submit_clicked = false;
    
    $('button[type=submit]').click(function () {
        if(submit_clicked) {
            submit_clicked = false;
            return false;
        }
        else {
            submit_clicked = true;
        }
    });
    
    $(document).keydown(function () {
        submit_clicked = false;
    });
    
    $('select').change(function () {
        submit_clicked = false;
    });
    
    $('input').keydown(function() {
        submit_clicked = false;
    });
    
    $('input').change(function() {
        submit_clicked = false;
    });
    
    // Увеличение и уменьшение картинки
    function ResizeImage(obj, e) {
        if(obj.hasClass('img-fluid')) {
            width1 = obj.width();
            height1 = obj.height();
            positionX1 = e.pageX - obj.offset().left;
            positionY1 = e.pageY - obj.offset().top;
            
            obj.removeClass('img-fluid');
            obj.css('cursor', 'zoom-out');
            
            width2 = obj.width();
            height2 = obj.height();
            positionX2 = positionX1 * width2 / width1;
            positionY2 = positionY1 * height2 / height1;
            obj.parent().scrollLeft(positionX2 - obj.parent().width() / 2);
            obj.parent().scrollTop(positionY2 - obj.parent().height() / 2);
        } else {
            obj.addClass('img-fluid'); 
            obj.css('cursor', 'zoom-in'); 
        }
    }
    
    // Заполнение трекинга заказов
    function StatusTrack(id) {
        $.ajax({ url: '../calculation/_status_track.php?id=' + id })
                .done(function(data) {
                    $('#status_track_body').html(data);
                })
                .fail(function() {
                    alert('Ошибка при получении трекинга заказов');
                });
    }
        
    // Отображение полностью блока с фиксированной позицией, не умещающегося полностью в окне
    function AdjustFixedBlock(fixed_block) {
        windowHeight = $(window).height();
        blockTop = fixed_block.offset().top;
        blockHeight = fixed_block.outerHeight();
        blockMarginTop = parseInt(fixed_block.css('margin-top').replace('px', ''));
        
        if(blockHeight + blockMarginTop < windowHeight) {
            fixed_block.css('position', 'fixed');
            fixed_block.css('top', 0);
            fixed_block.css('bottom', 'auto');
        }
        else {
            if(blockHeight + blockMarginTop < $(window).scrollTop() + windowHeight) {
                fixed_block.css('position', 'fixed');
                fixed_block.css('bottom', 0);
                fixed_block.css('top', 'auto');
            }
            else {
                fixed_block.css('position', 'absolute');
                fixed_block.css('top', 0);
                fixed_block.css('bottom', 'auto');
            }
        }
    };
        
    // Прокрутка на прежнее место после отправки формы
    $(window).on("scroll", function(){
        $('input[name="scroll"]').val($(window).scrollTop());
    });
    
    <?php if(!empty($_REQUEST['scroll'])): ?>
        window.scrollTo(0, <?php echo intval($_REQUEST['scroll']); ?>);
    <?php endif; ?>
</script>