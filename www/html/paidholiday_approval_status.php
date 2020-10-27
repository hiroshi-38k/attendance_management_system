<?php
// 設定ファイル読込
require_once '../conf/const.php';
// 関数ファイル読込
require_once MODEL_PATH . '/db.php';
require_once MODEL_PATH . '/function.php';
require_once MODEL_PATH . '/employee.php';
require_once MODEL_PATH . '/paidholiday.php';
require_once MODEL_PATH . '/basic.php';
// iframe読込禁止
header('X-FRAME-OPTIONS: DENY');
// セッション開始
session_start();
// ログインしていない場合、ログイン画面にリダイレクト
if(is_logined() === false){
    redicrect_to(LOGIN_URL);
}
// トークンバリデーション
if(is_valid_token() === true){
    // DB接続
    $db = get_db_connect();
    // 従業員情報取得
    $employee_id = get_session('employee_id');
    $employee = get_login_employee($db, $employee_id);
    if($employee['employee_type'] !== '1' && $employee['employee_type'] !== '2'){
        redirect_to(HOME_URL);
    }
    // POST値
    $paidholiday_id = get_post('paidholiday_id');
    $approval_status = get_post('approval_status');
    if(is_valid_approval_status($approval_status) === true){
        var_dump(is_valid_approval_status($approval_status));
        // トランザクション処理開始
        $db->beginTransaction();
        $flag = false;
        $flag = update_paidholiday_approval_status($db, $paidholiday_id, $approval_status);
        $flag = update_attendance_paidholiday($db, $paidholiday_id);
        if($flag === true){
            $db->commit();
        }else{
            $db->rollback();
        }
    }else{
        set_error('年休承認に失敗しました。再度お試しください。');
    }
    // 年休承認画面にリダイレクト
    redirect_to(HOLIDAY_APPROVAL_URL);
}
?>