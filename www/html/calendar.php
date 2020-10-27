<?php
// 設定ファイル読込
require_once '../conf/const.php';
// 関数ファイル読込
require_once MODEL_PATH . '/db.php';
require_once MODEL_PATH . '/function.php';
require_once MODEL_PATH . '/employee.php';
require_once MODEL_PATH . '/calendar.php';
// セッション開始
session_start();
// ログインしていない場合はログイン画面にリダイレクト
if(is_logined() === false){
    redirect_to(LOGIN_URL);
}
// トークン生成
$token = get_token();
// カレンダー年月
$year = get_year();
$months = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
$last_year = $year-1;
$next_year = $year+1;
// db接続
$db = get_db_connect();
// 従業員情報取得
$employee_id = get_session('employee_id');
$employee = get_login_employee($db, $employee_id);
if($employee['employee_type'] !== '1' && $employee['employee_type'] !== '0'){
    redirect_to(HOME_URL);
}
// viewファイル出力
include_once VIEW_PATH . '/calendar_view.php';
?>