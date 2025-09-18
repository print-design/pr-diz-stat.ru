<?php
// Роли
const ROLE_COLORIST = 1;
const ROLE_LAM_HEAD = 2;
const ROLE_TECHNOLOGIST = 3;
const ROLE_MANAGER = 4;
const ROLE_FLEXOPRINT_HEAD = 5;
const ROLE_STOREKEEPER = 6;
const ROLE_ELECTROCARIST = 7;
const ROLE_CUTTER = 8;
const ROLE_MARKER = 9;
const ROLE_AUDITOR = 10;
const ROLE_MANAGER_SENIOR = 11;
const ROLE_SCHEDULER = 12;
const ROLE_PACKER = 13;

const ROLES = array(ROLE_ELECTROCARIST, ROLE_STOREKEEPER, ROLE_COLORIST, ROLE_MARKER, ROLE_MANAGER, ROLE_LAM_HEAD, ROLE_FLEXOPRINT_HEAD, ROLE_SCHEDULER, ROLE_AUDITOR, ROLE_CUTTER, ROLE_MANAGER_SENIOR, ROLE_TECHNOLOGIST, ROLE_PACKER);
const ROLE_NAMES = array(ROLE_COLORIST => "colorist", ROLE_LAM_HEAD => "lam_head", ROLE_FLEXOPRINT_HEAD => "flexoprint_head", ROLE_TECHNOLOGIST => "technologist", ROLE_MANAGER => "manager", ROLE_STOREKEEPER => "storekeeper", ROLE_ELECTROCARIST => "electrocarist", ROLE_CUTTER => "cutter", ROLE_MARKER => "marker", ROLE_AUDITOR => "auditor", ROLE_MANAGER_SENIOR => "manager-senior", ROLE_SCHEDULER => "scheduler", ROLE_PACKER => "packer");
const ROLE_LOCAL_NAMES = array(ROLE_COLORIST => "Колорист", ROLE_LAM_HEAD => "Начальник участка ламинации и резки", ROLE_FLEXOPRINT_HEAD => "Начальник участка флексопечати", ROLE_TECHNOLOGIST => "Технолог", ROLE_MANAGER => "Менеджер", ROLE_STOREKEEPER => "Кладовщик", ROLE_ELECTROCARIST => "Карщик", ROLE_CUTTER => "Резчик раскрой", ROLE_MARKER => "Маркировщик", ROLE_AUDITOR => "Ревизор", ROLE_MANAGER_SENIOR => "Старший менеджер", ROLE_SCHEDULER => "Планировщик", ROLE_PACKER => "Упаковщица");
const ROLE_TWOFACTOR = array(ROLE_COLORIST => 0, ROLE_LAM_HEAD => 0, ROLE_FLEXOPRINT_HEAD => 0, ROLE_TECHNOLOGIST => 0, ROLE_MANAGER => 0, ROLE_STOREKEEPER => 0, ROLE_ELECTROCARIST => 0, ROLE_CUTTER => 0, ROLE_MARKER => 0, ROLE_AUDITOR => 0, ROLE_MANAGER_SENIOR => 0, ROLE_SCHEDULER => 0, ROLE_PACKER => 0);
//const ROLE_TWOFACTOR = array(ROLE_COLORIST => 0, ROLE_LAM_HEAD => 1, ROLE_FLEXOPRINT_HEAD => 1, ROLE_TECHNOLOGIST => 1, ROLE_MANAGER => 1, ROLE_STOREKEEPER => 1, ROLE_ELECTROCARIST => 0, ROLE_CUTTER => 0, ROLE_MARKER => 0, ROLE_AUDITOR => 0, ROLE_MANAGER_SENIOR => 1, ROLE_SCHEDULER => 0, ROLE_PACKER => 0);

// Единицы размера тиража
const KG = 'kg';
const PIECES = 'pieces';

// Валюты
const CURRENCY_RUB = "rub";
const CURRENCY_USD = "usd";
const CURRENCY_EURO = "euro";

// Лыжи
const SKI_NO = 1;
const SKI_STANDARD = 2;
const SKI_NONSTANDARD = 3;
    
// Краски
const INK_CMYK = "cmyk";
const INK_PANTON = "panton";
const INK_WHITE = "white";
const INK_LACQUER = "lacquer";
    
// CMYK
const CMYK_CYAN = "cyan";
const CMYK_MAGENDA = "magenta";
const CMYK_YELLOW = "yellow";
const CMYK_KONTUR = "kontur";
    
// Лак
const LACQUER_GLOSSY = "glossy";
const LACQUER_MATTE = "matte";
    
// Формы
const CLICHE_OLD = "old";
const CLICHE_FLINT = "flint";
const CLICHE_KODAK = "kodak";
const CLICHE_REPEAT = "repeat";

// Печатные машины
const PRINTER_ZBS_1 = 1;
const PRINTER_ZBS_2 = 2;
const PRINTER_ZBS_3 = 3;
const PRINTER_COMIFLEX = 4;
const PRINTER_ATLAS = 5;
const PRINTER_SOMA_OPTIMA = 6;

const PRINTERS = array(PRINTER_COMIFLEX, PRINTER_SOMA_OPTIMA, PRINTER_ZBS_1, PRINTER_ZBS_2, PRINTER_ZBS_3, PRINTER_ATLAS);
const PRINTER_NAMES = array(PRINTER_COMIFLEX => 'Comiflex', PRINTER_ZBS_1 => 'ZBS-1', PRINTER_ZBS_2 => 'ZBS-2', PRINTER_ZBS_3 => 'ZBS-3', PRINTER_ATLAS => 'Atlas', PRINTER_SOMA_OPTIMA => 'Soma Optima');
const PRINTER_SHORTNAMES = array(PRINTER_COMIFLEX => 'comiflex', PRINTER_ZBS_1 => 'zbs1', PRINTER_ZBS_2 => 'zbs2', PRINTER_ZBS_3 => 'zbs3', PRINTER_ATLAS => 'atlas', PRINTER_SOMA_OPTIMA => 'somaoptima');
const PRINTER_COLORFULLNESSES = array(PRINTER_COMIFLEX => 8, PRINTER_ZBS_1 => 6, PRINTER_ZBS_2 => 6, PRINTER_ZBS_3 => 8, PRINTER_ATLAS => 6, PRINTER_SOMA_OPTIMA => 8);
const PRINTER_MACHINE_COEFFICIENTS = array(PRINTER_COMIFLEX => 1.14, PRINTER_ZBS_1 => 1.7, PRINTER_ZBS_2 => 1.7, PRINTER_ZBS_3 => 1.7, PRINTER_ATLAS => 1.7, PRINTER_SOMA_OPTIMA => 1.14);

// Ламинаторы
const LAMINATOR_SOLVENT = 1;
const LAMINATOR_SOLVENTLESS = 2;

const LAMINATORS = array(LAMINATOR_SOLVENT, LAMINATOR_SOLVENTLESS);
const LAMINATOR_NAMES = array(LAMINATOR_SOLVENT => 'Ламинатор сольвент', LAMINATOR_SOLVENTLESS => 'Ламинатор бессольвент');

// Резки
const CUTTER_1 = 1;
const CUTTER_3 = 3;
const CUTTER_4 = 4;
const CUTTER_ATLAS = 101;
const CUTTER_SOMA = 102;
const CUTTER_ZTM_1 = 5;
const CUTTER_ZTM_2 = 2;

const CUTTERS = array(CUTTER_1, CUTTER_3, CUTTER_4, CUTTER_SOMA, CUTTER_ATLAS, CUTTER_ZTM_1, CUTTER_ZTM_2);
const CUTTER_NAMES = array(CUTTER_1 => "Резка 1", CUTTER_3 => "Резка 3", CUTTER_4 => "Резка 4", CUTTER_ATLAS => "Резка &laquo;Атлас&raquo;", CUTTER_SOMA => "Резка &laquo;Сома&raquo;", CUTTER_ZTM_1 => "ZTM 1", CUTTER_ZTM_2 => "ZTM 2");

// Резчики (пользователи) !!! Пользователя резки Атлас пока удалили !!!
const CUTTER_USER_1 = "cut1";
const CUTTER_USER_3 = "cut3";
const CUTTER_USER_SOMA = "somacut";
const CUTTER_USER_ZTM_1 = "ztm1";
const CUTTER_USER_ZTM_2 = "ztm2";

const CUTTER_USERS = array(CUTTER_USER_1, CUTTER_USER_3, CUTTER_USER_SOMA, CUTTER_USER_ZTM_1, CUTTER_USER_ZTM_2);
const CUTTER_USER_IDS = array(CUTTER_USER_1 => CUTTER_1, CUTTER_USER_3 => CUTTER_3, CUTTER_USER_SOMA => CUTTER_SOMA, CUTTER_USER_ZTM_1 => CUTTER_ZTM_1, CUTTER_USER_ZTM_2 => CUTTER_ZTM_2);
const CUTTER_USER_NAMES = array(CUTTER_USER_1 => "Резка 1", CUTTER_USER_3 => "Резка 3", CUTTER_USER_SOMA => "Резка Сома", CUTTER_USER_ZTM_1 => "ZTM 1", CUTTER_USER_ZTM_2 => "ZTM 2");

// Типы работы
const WORK_TYPE_NOPRINT = 1;
const WORK_TYPE_PRINT = 2;
const WORK_TYPE_SELF_ADHESIVE = 3;

const WORK_TYPES = array(WORK_TYPE_NOPRINT, WORK_TYPE_PRINT, WORK_TYPE_SELF_ADHESIVE);
const WORK_TYPE_NAMES = array(WORK_TYPE_NOPRINT => "Пленка без печати", WORK_TYPE_PRINT => "Пленка с печатью", WORK_TYPE_SELF_ADHESIVE => "Самоклеящиеся мат-лы");
const WORK_TYPE_PRINTERS = array(WORK_TYPE_PRINT => array(PRINTER_COMIFLEX, PRINTER_SOMA_OPTIMA, PRINTER_ZBS_1, PRINTER_ZBS_2, PRINTER_ZBS_3), WORK_TYPE_SELF_ADHESIVE => array(PRINTER_ATLAS));

// Статусы заказа
const ORDER_STATUS_DRAFT = 1; // Черновик
const ORDER_STATUS_CALCULATION = 2; // Сделан расчёт
const ORDER_STATUS_WAITING = 3; // Ждём подтверждения
const ORDER_STATUS_CONFIRMED = 4; // Ждём постановки в план
const ORDER_STATUS_REJECTED = 5; // Отклонено
const ORDER_STATUS_TECHMAP = 6; // Составлена технологическая карта
const ORDER_STATUS_TRASH = 7; // В корзине
const ORDER_STATUS_PLAN_PRINT = 8; // В плане печати
const ORDER_STATUS_PLAN_LAMINATE = 9; // В плане ламинации
const ORDER_STATUS_PLAN_CUT = 10; // В плане резки
const ORDER_STATUS_CUT_PRILADKA = 11; // Приладка на резке
const ORDER_STATUS_CUTTING = 12; // Режется
const ORDER_STATUS_PACK_READY = 14; // Готово к упаковке
const ORDER_STATUS_SHIP_READY = 15; // Ждёт отгрузки
const ORDER_STATUS_SHIPPED = 16; // Отгружено
const ORDER_STATUS_CUT_REMOVED = 17; // Сняли с резки

const ORDER_STATUS_NOT_IN_WORK = 100; // Сделан расчёт или составлена тех. карта
const ORDER_STATUS_IN_PRODUCTION = 101; // Приладка на резке, режется, готово к упаковке, сняли с резки
const ORDER_STATUS_IN_WORK = 200; // Всё остальное

const ORDER_STATUSES_NOT_IN_WORK = array(ORDER_STATUS_CALCULATION, ORDER_STATUS_TECHMAP);
const ORDER_STATUSES_IN_PRODUCTION = array(ORDER_STATUS_CUT_PRILADKA, ORDER_STATUS_CUTTING, ORDER_STATUS_PACK_READY, ORDER_STATUS_CUT_REMOVED);
const ORDER_STATUSES_IN_WORK = array(ORDER_STATUS_WAITING, ORDER_STATUS_CONFIRMED, ORDER_STATUS_REJECTED, ORDER_STATUS_PLAN_PRINT, ORDER_STATUS_PLAN_LAMINATE, ORDER_STATUS_PLAN_CUT);

const ORDER_STATUS_TITLES = array(ORDER_STATUS_SHIPPED => "Отгружено", ORDER_STATUS_SHIP_READY => "Ждёт отгрузки", ORDER_STATUS_IN_PRODUCTION => "Производят", ORDER_STATUS_IN_WORK => "В работе", ORDER_STATUS_NOT_IN_WORK => "Расчёты", ORDER_STATUS_DRAFT => "Черновики", ORDER_STATUS_TRASH => "Корзина");
const ORDER_STATUSES_IN_CUT = array(ORDER_STATUS_CUT_PRILADKA, ORDER_STATUS_CUTTING, ORDER_STATUS_PACK_READY, ORDER_STATUS_SHIP_READY, ORDER_STATUS_SHIPPED);
const ORDER_STATUSES_WITH_METERS = array(ORDER_STATUS_CUTTING, ORDER_STATUS_PACK_READY, ORDER_STATUS_SHIP_READY, ORDER_STATUS_SHIPPED);

const ORDER_STATUS_NAMES = array(ORDER_STATUS_DRAFT => "Черновик", ORDER_STATUS_CALCULATION => "Сделан расчёт", ORDER_STATUS_WAITING => "Ждём подтверждения", ORDER_STATUS_CONFIRMED => "Ждём постановки в план", ORDER_STATUS_REJECTED => "Отклонено", ORDER_STATUS_TECHMAP => "Составлена тех. карта", ORDER_STATUS_TRASH => "В корзине", ORDER_STATUS_PLAN_PRINT => "В плане печати", ORDER_STATUS_PLAN_LAMINATE => "В плане ламинации", ORDER_STATUS_PLAN_CUT => "В плане резки", ORDER_STATUS_CUT_PRILADKA => "Приладка на резке", ORDER_STATUS_CUTTING => "Режется", ORDER_STATUS_PACK_READY => "Готово к упаковке", ORDER_STATUS_SHIP_READY => "Ждёт отгрузки", ORDER_STATUS_SHIPPED => "Отгружено", ORDER_STATUS_CUT_REMOVED => "Сняли с резки");
const ORDER_STATUS_COLORS = array(ORDER_STATUS_DRAFT => "gray", ORDER_STATUS_CALCULATION => "steelblue", ORDER_STATUS_WAITING => "goldenrod", ORDER_STATUS_CONFIRMED => "mediumseagreen", ORDER_STATUS_REJECTED => "crimson", ORDER_STATUS_TECHMAP => "saddlebrown", ORDER_STATUS_TRASH => "black", ORDER_STATUS_PLAN_PRINT => "#9933ff", ORDER_STATUS_PLAN_LAMINATE => "#4d009a", ORDER_STATUS_PLAN_CUT => "#27004e", ORDER_STATUS_CUT_PRILADKA => "chocolate", ORDER_STATUS_CUTTING => "brown", ORDER_STATUS_PACK_READY => "paleturquoise", ORDER_STATUS_SHIP_READY => "RosyBrown", ORDER_STATUS_SHIPPED => "chocolate", ORDER_STATUS_CUT_REMOVED => "crimson");
const ORDER_STATUS_ICONS = array(ORDER_STATUS_DRAFT => "fas fa-edit", ORDER_STATUS_CALCULATION => "fas fa-check", ORDER_STATUS_WAITING => "fas fa-clock", ORDER_STATUS_CONFIRMED => "fas fa-check-double", ORDER_STATUS_REJECTED => "fas fa-times-circle", ORDER_STATUS_TECHMAP => "fas fa-file", ORDER_STATUS_TRASH => "fas fa-trash-alt", ORDER_STATUS_PLAN_PRINT => "fas fa-print", ORDER_STATUS_PLAN_LAMINATE => "fas fa-layer-group", ORDER_STATUS_PLAN_CUT => "fas fa-cut", ORDER_STATUS_CUT_PRILADKA => "fas fa-sliders-h", ORDER_STATUS_CUTTING => "fas fa-cut", ORDER_STATUS_PACK_READY => "fas fa-box", ORDER_STATUS_SHIP_READY => "fas fa-box", ORDER_STATUS_SHIPPED => "fas fa-box", ORDER_STATUS_CUT_REMOVED => "fas fa-times-circle");

// Разделы плана
const WORK_PRINTING = 1;
const WORK_LAMINATION = 2;
const WORK_CUTTING = 3;

const WORKS = array(WORK_PRINTING, WORK_LAMINATION, WORK_CUTTING);
const WORK_NAMES = array(WORK_PRINTING => "Печать", WORK_LAMINATION => "Ламинация", WORK_CUTTING => "Резка");
const WORK_CONTINUATIONS = array(WORK_PRINTING => "Допечатка", WORK_LAMINATION => "Доламинирование", WORK_CUTTING => "Дорезка");

// Роли плана
const PLAN_ROLE_PRINT = 1;
const PLAN_ROLE_ASSISTANT = 2;
const PLAN_ROLE_LAMINATE = 3;
const PLAN_ROLE_CUT = 4;

const PLAN_ROLES = array(PLAN_ROLE_PRINT, PLAN_ROLE_ASSISTANT, PLAN_ROLE_LAMINATE, PLAN_ROLE_CUT);
const PLAN_ROLE_NAMES = array(PLAN_ROLE_PRINT => "Печатник", PLAN_ROLE_ASSISTANT => "Помощник", PLAN_ROLE_LAMINATE => "Ламинаторщик", PLAN_ROLE_CUT => "Резчик");
const PLAN_ROLE_PLURALS = array(PLAN_ROLE_PRINT => "Печатники", PLAN_ROLE_ASSISTANT => "Помощники", PLAN_ROLE_LAMINATE => "Ламинаторщики", PLAN_ROLE_CUT => "Резчики");
const WORK_PLAN_ROLES = array(WORK_PRINTING => PLAN_ROLE_PRINT, WORK_LAMINATION => PLAN_ROLE_LAMINATE, WORK_CUTTING => PLAN_ROLE_CUT);

// Типы объектов плана
const PLAN_TYPE_EDITION = 1;
const PLAN_TYPE_EVENT = 2;
const PLAN_TYPE_CONTINUATION = 3;

// Типы наценки
const ET_NOPRINT = 1;
const ET_PRINT_NO_LAMINATION = 2;
const ET_PRINT_1_LAMINATION = 3;
const ET_PRINT_2_LAMINATIONS = 4;
const ET_SELF_ADHESIVE = 5;

const EXTRACHARGE_TYPES = array(ET_NOPRINT, ET_PRINT_NO_LAMINATION, ET_PRINT_1_LAMINATION, ET_PRINT_2_LAMINATIONS);
const EXTRACHARGE_TYPE_NAMES = array(ET_NOPRINT => "Пленка без печати", ET_PRINT_NO_LAMINATION => "Пленка с печатью без ламинации", ET_PRINT_1_LAMINATION => "Пленка с печатью и ламинацией", ET_PRINT_2_LAMINATIONS => "Пленка с печатью и двумя ламинациями", ET_SELF_ADHESIVE => "Самоклеящиеся материалы");

// Статусы роликов
const ROLL_STATUS_FREE = 1;
const ROLL_STATUS_UTILIZED = 2;
const ROLL_STATUS_CUT = 3;
const ROLL_STATUSES = array(ROLL_STATUS_FREE, ROLL_STATUS_UTILIZED, ROLL_STATUS_CUT);
const ROLL_STATUS_NAMES = array(ROLL_STATUS_FREE => "Свободный", ROLL_STATUS_UTILIZED => "Сработанный", ROLL_STATUS_CUT => "Раскроили");
const ROLL_STATUS_COLOURS = array(ROLL_STATUS_FREE => "forestgreen", ROLL_STATUS_UTILIZED => "red", ROLL_STATUS_CUT => "violet");

// Отходы
const WASTE_PRESS = "В пресс";
const WASTE_KAGAT = "В кагат";
const WASTE_PAPER = "В макулатуру";

const WASTE_PRESS_FILMS = array("CPP cast", "CPP LA", "HGPL прозрачка", "HMIL.M металл", "HOHL жемчуг", "HWHL белая", "LOBA жемчуг", "LOHM.M", "MGS матовая");
const WASTE_PAPER_FILM = "Офсет БДМ-7";

// Цена на другое
const PRICE_ECO_CUSTOMERS_MATERIAL = 1;
const PRICE_ECO_OTHER_MATERIAL = 2;

// Объекты, для которых загружены картинки
const STREAM = "stream";
const PRINTING = "printing";

// Другое
const ISINVALID = ' is-invalid';
?>