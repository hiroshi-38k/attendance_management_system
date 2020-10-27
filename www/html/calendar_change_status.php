<?php
// 設定ファイル読込
require_once '../conf/const.php';
// 関数ファイル読込
require_once MODEL_PATH . '/db.php';
require_once MODEL_PATH . '/function.php';
require_once MODEL_PATH . '/employee.php';
require_once MODEL_PATH . '/calendar.php';
require_once MODEL_PATH . '/basic.php';
// iframe読込禁止
header('X-FRAME-OPTIONS: DENY');
// セッション開始
session_start();
// ログインしていない場合はログイン画面にリダイレクト
if(is_logined() === false){
    redirect_to(LOGIN_URL);
}
// トークンバリデーション
if(is_valid_token() === true){
    // DB接続
    $db = get_db_connect();
    // 従業員情報取得
    $employee_id = get_session('employee_id');
    $employee = get_login_employee($db, $employee_id);
    if($employee['employee_type'] !== '0'){
        redirect_to(HOME_URL);
    }
    // POST値取得
    $processtype = get_post('process_type');
    $calendar_date = get_post('calendar_date');
    $calendar_status = get_post('calendar_status');
    // 休日ステータス変更
    change_calendar_status($db, $calendar_date, $calendar_status);
    // 休日設定画面にリダイレクト
    redirect_to(CALENDAR_URL);
}
?>