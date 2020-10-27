<?php
// 設定ファイル読込
require_once '../conf/const.php';
// 関数ファイル読込
require_once MODEL_PATH . '/db.php';
require_once MODEL_PATH . '/function.php';
require_once MODEL_PATH . '/employee.php';
require_once MODEL_PATH . '/salary.php';
// iframe読込禁止
header('X-FRAME-OPTIONS: DENY');
// セッション開始
session_start();
// ログインしていない場合、ログイン画面にリダイレクト
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
    $salary = get_salary_data();
    // POST値バリデーション
    if(is_valid_salary_data($salary)){
        // 給与クラステーブルへ追加
        if(insert_salaryclass($db, $salary)){
            set_messages('給与クラスを追加しました。');
        }else{
            set_error('給与クラス追加に失敗しました。');
        }
    }
}
// 基本情報画面にリダイレクト
redirect_to(BASIC_URL);
?>