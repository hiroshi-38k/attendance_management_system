<?php
// 設定ファイル読込
require_once '../conf/const.php';
// 関数ファイル読込
require_once MODEL_PATH . '/db.php';
require_once MODEL_PATH . '/function.php';
require_once MODEL_PATH . '/employee.php';
require_once MODEL_PATH . '/calendar.php';
// セッション開始
session_start();
$employee_id = get_session('employee_id');
$start_year = 2020;
$end_year = $start_year + 60;
$year = $start_year;
$db = get_db_connect();
$attendances = array();
while($year <= $end_year){
    $calendars = get_calendars($db, $year);
    foreach($calendars as $value){
        $calendar_date  = $value['calendar_date'];
        $calendar_status = $value['calendar_status'];
        if($calendar_status === 0){
            $attendance_type = 4;
        } else if($calendar_status === 1){
            $attendance_type = 0;
        }
        $attendances[] = [
            "employee_id" => $employee_id,
            "calendar_date" => $calendar_date,
            "attendance_type" => $attendance_type
        ];
    }
    $year++;
}
$sql_before = "
    INSERT INTO sf_attendances (employee_id, calendar_date, attendance_type)
    VALUES ";
$sql_after = "";
foreach($attendances as $value){
    $sql_after .= '(' . $value['employee_id'] . ','
                . "'" . $value['calendar_date'] ."'" . ','
                      . $value['attendance_type'] . ')';
    if($value !== end($attendances)){
        $sql_after .= ',';
    }
}
$sql = $sql_before . $sql_after;

?>