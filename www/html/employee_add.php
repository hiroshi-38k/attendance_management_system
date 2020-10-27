<?php
// 設定ファイル読込
require_once '../conf/const.php';
// 関数ファイル読込
require_once MODEL_PATH . '/db.php';
require_once MODEL_PATH . '/function.php';
require_once MODEL_PATH . '/employee.php';
require_once MODEL_PATH . '/calendar.php';
// iframe読込禁止
header('X-FRAME-OPTIONS: DENY');
// セッション開始
session_start();
if(is_valid_token() === true){
    // DB接続
    $db = get_db_connect();
    // ログインした従業員情報取得
    $employee_id = get_session('employee_id');
    $employee = get_login_employee($db, $employee_id);
    if($employee['employee_type'] !== '0'){
        redirect_to(HOME_URL);
    }
    // FILE値
    $file = get_file('new_employee_data');
    if(regist_employee($db, $file)){
        // 正常メッセージ
        set_messages('従業員を登録しました。');
    }else{
        // 異常メッセージ
        set_error('従業員の登録に失敗しました。');
    }
}
// employee.phpにリダイレクト
redirect_to(EMPLOYEE_URL);
?>