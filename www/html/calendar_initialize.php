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
    // POST値取得
    $start_year = get_post('start_year');
    $end_year = get_post('end_year');
    // db接続
    $db = get_db_connect();
    // 従業員情報取得
    $employee_id = get_session('employee_id');
    $employee = get_login_employee($db, $employee_id);
    if($employee['employee_type'] !== '0'){
        redirect_to(HOME_URL);
    }
    // カレンダー初期化
    calendar_initialize($db, $start_year, $end_year);
    // 休日設定画面にリダイレクト
    redirect_to(CALENDAR_URL);
}
?>