<?php
// 設定ファイル読込
require_once '../conf/const.php';
// 関数ファイル読込
require_once MODEL_PATH . '/db.php';
require_once MODEL_PATH . '/function.php';
require_once MODEL_PATH . '/employee.php';
require_once MODEL_PATH . '/attendance.php';
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
    if($employee['employee_type'] !== '1' && $employee['employee_type'] !== '2'){
        redirect_to(HOME_URL);
    }
    // POST値
    $approval_status = get_post('approval_status');
    $attendance_id = get_post('attendance_id');
    if(is_valid_approval_status($approval_status) === false){
        set_error('勤怠承認に失敗しました。再度お試しください。');
        redirect_to(ATTENDANCE_APPROVAL_URL);
    }
    update_attendance_approval_status($db, $attendance_id, $approval_status);
    // 勤怠承認画面にリダイレクト
    redirect_to(ATTENDANCE_APPROVAL_URL);
}
?>