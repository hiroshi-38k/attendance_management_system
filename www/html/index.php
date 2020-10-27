<?php
// 設定ファイル読込
require_once '../conf/const.php';
// 関数ファイル読込
require_once MODEL_PATH . '/db.php';
require_once MODEL_PATH . '/function.php';
require_once MODEL_PATH . '/employee.php';
require_once MODEL_PATH . '/attendance.php';
require_once MODEL_PATH . '/calendar.php';

$weekdays = array('日','月','火','水','木','金','土');

// セッション開始
session_start();
// ログインしていない場合、ログイン画面にリダイレクト
if(is_logined() === false){
    redirect_to(LOGIN_URL);
}
// トークン生成
$token = get_token();
// DB接続
$db = get_db_connect();
// 従業員情報取得
$employee_id = get_session('employee_id');
$employee = get_login_employee($db, $employee_id);
// カレンダー情報取得
$year = get_year();
$month = get_month();
$day = get_day();
$weekday = get_first_weekday($year, $month);
$lastDate = get_lastDate($year, $month);
$calendar_date = get_calendar_date($year, $month, $day);
// 出勤・退勤情報取得
$attendances = get_attendances($db, $employee_id, $calendar_date);
$attendance_type = conversion_application_type($attendances['attendance_type']);
if(isset($attendances['work_time']) === true){
    $worktime_hour = substr($attendances['work_time'],11,2);
    $worktime_min  = substr($attendances['work_time'],14,2);
}
if(isset($attendances['leave_time']) === true){
    $leavetime_hour = substr($attendances['leave_time'],11,2);
    $leavetime_min  = substr($attendances['leave_time'],14,2);
}
$approval_status = get_attendance_approval_status($attendances['approval_status']);
// viewファイル出力
include_once VIEW_PATH . '/index_view.php';
?>