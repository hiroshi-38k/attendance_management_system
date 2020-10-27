<?php
// 設定ファイル読込
require_once '../conf/const.php';
// 関数ファイル読込
require_once MODEL_PATH . '/db.php';
require_once MODEL_PATH . '/function.php';
require_once MODEL_PATH . '/employee.php';
require_once MODEL_PATH . '/basic.php';
require_once MODEL_PATH . '/salary.php';
// セッション開始
session_start();
// トークン生成
$token = get_token();
// ログインしていない場合、ログイン画面にリダイレクト
if(is_logined() === false){
    redirect_to(LOGIN_URL);
}
// DB接続
$db = get_db_connect();
// 従業員情報取得
$employee_id = get_session('employee_id');
$employee = get_login_employee($db, $employee_id);
if($employee['employee_type'] !== '0'){
    redirect_to(HOME_URL);
}
// basicテーブルから基本情報読込
$basic = get_basic_data($db);
// 給与クラステーブルから給与クラス一覧読込
$salary = get_salary_record($db);
// viewファイル出力
include_once VIEW_PATH . '/basic_view.php';
?>