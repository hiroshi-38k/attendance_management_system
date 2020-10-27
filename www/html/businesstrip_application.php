<?php
// 設定ファイル読込
require_once '../conf/const.php';
// 関数ファイル読込
require_once MODEL_PATH . '/db.php';
require_once MODEL_PATH . '/function.php';
require_once MODEL_PATH . '/employee.php';
require_once MODEL_PATH . '/calendar.php';
require_once MODEL_PATH . '/businesstrip.php';
// iframe読込禁止
header('X-FRAME-OPTIONS: DENY');
// セッション開始
session_start();
// ログインしていない場合、ログイン画面にリダイレクト
if(is_logined() === false){
    redirect_to(LOGIN_URL);
}
// DB接続
$db = get_db_connect();
// トークンバリデーション
if(is_valid_token() === true){
    // DB接続
    $db = get_db_connect();
    // 従業員情報取得
    $employee_id = get_session('employee_id');
    $employee = get_login_employee($db, $employee_id);
    // POST値
    $array = get_businesstrip_data();
    // バリデーション
    if(is_valid_businesstrip_data($array) === true){;
        // 申請内容を出張申請情報テーブルに反映
        insert_businesstrip_application($db, $array);
    }
    // 勤怠管理画面にリダイレクト
    redirect_to(BUSINESSTRIP_URL);
}
?>