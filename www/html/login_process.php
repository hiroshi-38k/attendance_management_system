<?php
// 設定ファイル読込
require_once '../conf/const.php';
// 関数ファイル読込
require_once MODEL_PATH . '/db.php';
require_once MODEL_PATH . '/function.php';
require_once MODEL_PATH . '/employee.php';
// iframe読込禁止
header('X-FRAME-OPTIONS: DENY');
// セッション開始
session_start();
// ログインしていない場合ログイン画面にリダイレクト
if(is_logined() === true){
    redirect_to(HOME_URL);
}
// トークンバリデーション
if(is_valid_token() === true){
    // POST値取得
    $employee_id = get_post('employee_id');
    $password    = get_post('password');
    // db接続
    $db = get_db_connect();
    // ログイン処理
    $employee = login_as($db, $employee_id, $password);
    if($employee === false){
        // 異常メッセージ
        set_messages('ログインに失敗しました。');
        // ログイン画面にリダイレクト
        redirect_to(LOGIN_URL);
    }
    // 正常メッセージ
    set_messages('ログインしました');
    // 勤怠管理画面にリダイレクト
    redirect_to(HOME_URL);
}
?>