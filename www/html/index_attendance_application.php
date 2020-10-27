<?php
// 設定ファイル読込
require_once '../conf/const.php';
// 関数ファイル読込
require_once MODEL_PATH . '/db.php';
require_once MODEL_PATH . '/function.php';
require_once MODEL_PATH . '/employee.php';
require_once MODEL_PATH . '/attendance.php';
require_once MODEL_PATH . '/calendar.php';
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
    // POST値
    $process_type = get_post('process_type');
    $calendar_date = get_post('calendar_date');
    $year = get_post('year');
    $month = get_post('month');
    $day = get_post('day');
    // 時刻
    $date = date('Y-m-d H:i:s');
    // 出勤時刻登録
    if($process_type === 'worktime'){
        // POST値
        $worktime_hour = get_post('worktime_hour');
        $worktime_min = get_post('worktime_min');
        // 入力値バリデーション
        if(is_valid_input_from_index($worktime_hour, $worktime_min) === false){
            redirect_to(HOME_URL . '?year=' . $year . '&month=' . $month . '&day=' . (int)$day);
        }
        // 勤怠情報テーブル更新
        update_attendance_worktime($db, $employee_id, $calendar_date, $worktime_hour, $worktime_min, $date);
    }
    // 退勤時刻登録
    if($process_type === 'leavetime'){
        // POST値
        $leavetime_hour = get_post('leavetime_hour');
        $leavetime_min = get_post('leavetime_min');
        // 入力値バリデーション
        if(is_valid_input_from_index($leavetime_hour, $leavetime_min) === false){
            redirect_to(HOME_URL . '?year=' . $year . '&month=' . $month . '&day=' . (int)$day); 
        }
        // 勤怠情報テーブル更新
        update_attendance_leavetime($db, $employee_id, $calendar_date, $leavetime_hour, $leavetime_min, $date);
    }
    // 勤怠管理画面にリダイレクト
    redirect_to(HOME_URL . '?year=' . $year . '&month=' . $month . '&day=' . (int)$day);
}
?>