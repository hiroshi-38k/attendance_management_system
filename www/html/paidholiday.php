<?php
// 設定ファイル読込
require_once '../conf/const.php';
// 関数ファイル読込
require_once MODEL_PATH . '/db.php';
require_once MODEL_PATH . '/function.php';
require_once MODEL_PATH . '/employee.php';
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
// viewファイル出力
include_once VIEW_PATH . '/paidholiday_view.php';
?>