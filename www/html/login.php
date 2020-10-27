<?php
// 設定ファイル読込
require_once '../conf/const.php';
// 関数ファイル読込
require_once MODEL_PATH . '/db.php';
require_once MODEL_PATH . '/function.php';
require_once MODEL_PATH . '/employee.php';
// セッション開始
session_start();
// トークン生成
$token = get_token();
// ログインしている場合は、勤怠管理画面へリダイレクト
if(is_logined()){
    redirect_to(HOME_URL);
}
// DB接続
$db = get_db_connect();
// 本ページ訪問者が試せる従業員リスト
$employee = get_employee_test_list($db);
if(!empty($employee)){
    $employee = entity_arrays($employee);
}
// viewファイル出力
include_once VIEW_PATH . '/login_view.php';
?>