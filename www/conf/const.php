<?php
//define('DB_HOST', 'mysql');
define('DB_HOST', 'localhost');
define('DB_NAME', 'sample2');
define('DB_USER', '****');
define('DB_PASS', '****');
define('DB_CHARSET', 'utf8');

define('MODEL_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../model');
define('VIEW_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../view');
define('STYLESHEET_PATH', './css');
define('IMAGE_PATH', './img');
define('CSV_PATH', './csv');
define('PATHINFO_CSV', 'csv');

define('JENUARY', 1);
define('DECEMBER', 12);
define('BUSINESSDAY', 'businessday');
define('HOLIDAY', 'holiday');
define('NON_CHECK', '-');
define('APPROVAL_YES', '○');
define('APPROVAL_NO', '×');
define('NON_CHECK_STATUS', 0);
define('APPROVAL_YES_STATUS', 1);
define('APPROVAL_NO_STATUS', 2);
define('ATTENDANCE_TYPE_BUSINESSTRIP', 6);

// 入力値検証用
define('REGEXP_POSITIVE_INTEGER', '/\A([1-9][0-9]*|0)\z/');
define('DATETIME', '/^[0-9]{4}-[0-9]{2}-[0-9]{2}\S[0-9]{2}:[0-9]{2}:00$/');
define('CALENDAR_DATE', '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/');
define('TIME', '/^[0-9]{2}:[0-9]{2}:00$/');
define('TWO_DIGITS_NUMBER', '/^[0-9]{2}$/');
define('FOUR_DIGITS_NUMBER', '/^[0-9]{4}$/');
define('REGEX_APPROVAL_STATUS', '/^[12]$/');
define('REGEX_STATUS', '/^[01]$/');
define('REGEX_PAIDHOLIDAY_TYPE', '/^[1-3]$/');
define('MAX_HOUR', 23);
define('MAX_MINUTE', 59);
define('MAX_MONTH', 12);
define('MIN_MONTH', 1);
define('MAX_TEXT', 100);
define('MIN_TEXT', 1);
// 一般従業員向けページ
define('LOGIN_URL', 'login.php' );
define('LOGOUT_URL', 'logout.php' );
define('HOME_URL', 'index.php');
define('BUSINESSTRIP_URL', 'businesstrip.php');
define('HOLIDAY_URL', 'paidholiday.php');

// 上司向けページ
define('ATTENDANCE_APPROVAL_URL', 'attendances_approval.php');
define('BUSINESSTRIP_APPROVAL_URL', 'businesstrip_approval.php');
define('HOLIDAY_APPROVAL_URL', 'paidholiday_approval.php');

// 管理者向けページ
define('BASIC_URL', 'basic.php');
define('EMPLOYEE_URL','employee.php');
define('CALENDAR_URL', 'calendar.php');
?>