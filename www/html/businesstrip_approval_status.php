<?php
// 設定ファイル読込
require_once '../conf/const.php';
// 関数ファイル読込
require_once MODEL_PATH . '/db.php';
require_once MODEL_PATH . '/function.php';
require_once MODEL_PATH . '/employee.php';
require_once MODEL_PATH . '/businesstrip.php';
require_once MODEL_PATH . '/attendance.php';
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
    $businesstrip_id = get_post('businesstrip_id');
    $approval_status = get_post('approval_status');
    $depature_date = get_post('depature_date');
    $return_date = get_post('return_date');
    // POST値バリデーション
    if(is_valid_businesstrip_approval(
        $approval_status, $depature_date, $return_date) === true){
        // トランザクション処理開始
        $db->beginTransaction();
        $flag = false;
        $flag = update_businesstrip_approval_status($db, $businesstrip_id, $approval_status);
        $flag = update_businesstrip_attendance($db, $businesstrip_id, $depature_date, $return_date);
        if($flag === true){
            $db->commit();
        }else{
            $db->rollback();
        }
    }else{
        set_error('出張承認に失敗しました。再度お試しください。');
    }
    // 出張承認画面にリダイレクト
    redirect_to(BUSINESSTRIP_APPROVAL_URL);
}
?>