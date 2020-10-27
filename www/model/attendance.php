<?php
// 勤怠承認ステータス取得
function get_attendance_approval_status($approval_status){
    if($approval_status === 1){
        return APPROVAL_YES;
    }else if($approval_status === 2){
        return APPROVAL_NO;
    }else{
        return NON_CHECK;
    }
}
// 出勤時刻を更新
function update_attendance_worktime($db, $employee_id, $calendar_date, $worktime_hour, $worktime_min, $date){
    $sql = "
        UPDATE
            sf_attendances
        SET
            work_time = ?, attendance_type = 5, updatedate = ?, approval_status = 0
        WHERE
            employee_id = ? AND calendar_date = ?
    ";
    $work_time = $calendar_date . ' ' . str_pad($worktime_hour, 2, 0, STR_PAD_LEFT) . ':' . str_pad($worktime_min, 2, 0, STR_PAD_LEFT) . ':00';
    return execute_query($db, $sql, array($work_time, $date, $employee_id, $calendar_date));
}
// 退勤時刻を更新
function update_attendance_leavetime($db, $employee_id, $calendar_date, $leavetime_hour, $leavetime_min, $date){
    $sql = "
        UPDATE
            sf_attendances
        SET
            leave_time = ?, attendance_type = 5, updatedate = ?, approval_status = 0
        WHERE
            employee_id = ? AND calendar_date = ?
    ";
    $leave_time = $calendar_date . ' ' . str_pad($leavetime_hour, 2, 0, STR_PAD_LEFT) . ':' . str_pad($leavetime_min, 2, 0, STR_PAD_LEFT) . ':00';
    return execute_query($db, $sql, array($leave_time, $date, $employee_id, $calendar_date));
}
// 勤怠承認ステータスを更新
function update_attendance_approval_status($db, $attendance_id, $approval_status){
    $sql = "
        UPDATE
            sf_attendances
        SET
            approval_status = ?
        WHERE
            attendance_id = ?
    ";
    return execute_query($db, $sql, array($approval_status, $attendance_id));
}
// 出勤形態ステータスを更新
function update_attendance_type($db, $attendance_type){
    $sql = "
        UPDATE
            sf_attendances
        SET
            approval_type = ?
        WHERE

    ";
}
// 勤怠管理情報テーブル
function get_attendances($db, $employee_id, $calendar_date){
    $sql = "
        SELECT
            work_time,
            leave_time,
            approval_status,
            attendance_type
        FROM
            sf_attendances
        WHERE
            employee_id = ? AND calendar_date = ?
    ";
    return fetch_query($db, $sql, array($employee_id, $calendar_date));
}
function get_attendance_applications($db, $employee){
    $applications = get_attendance_record($db, $employee);
    foreach($applications as $keys => $values){
        $applications[$keys]['attendance_type'] = conversion_application_type($values['attendance_type']);
        $applications[$keys]['work_time'] = conversion_working_time($values['work_time']);
        $applications[$keys]['leave_time'] = conversion_working_time($values['leave_time']);
    }
    return $applications;
}
function get_attendance_record($db, $employee){
    $sql = "
        SELECT
            attendance_id,
            employee_name,
            calendar_date,
            attendance_type,
            work_time,
            leave_time,
            approval_status
        FROM
            sf_attendances
        INNER JOIN
            sf_employees ON sf_employees.employee_id = sf_attendances.employee_id
        WHERE
            work_time IS NOT NULL AND leave_time IS NOT NULL AND approval_status = 0
            AND (department_id = ? OR team_id = ?)
    ";
    return fetch_all_query($db, $sql, array($employee['department_id'], $employee['team_id']));
}
// application_type => 出勤形態の表示名
function conversion_application_type($value){
    if($value === 0){
        $value = '';
    }else if($value === 1){
        $value = '全休';
    }else if($value === 2){
        $value = '午前半休';
    }else if($value === 3){
        $value = '午後半休';
    }else if($value === 4){
        $value = '休日';
    }else if($value === 5){
        $value = '通常出勤';
    }else if($value === 6){
        $value = '出張';
    }
    return $value;
}
// work_time・leave_time => 出勤・退勤時分
function conversion_working_time($value){
    return substr($value, 11, 5);
}
// index.php 入力値バリデーション
function is_valid_input_from_index($hour, $minute){
    $calendar_date = get_post('calendar_date');
    var_dump(is_valid_calendar_date($calendar_date));
    if(is_valid_calendar_date($calendar_date) === false){
        set_error('勤怠登録に失敗しました。再度お試しください。');
        return false;
    }
    $year = get_post('year');
    $month = get_post('month');
    $day = get_post('day');
    if(is_valid_two_digits_number($hour) === false
    || is_valid_two_digits_number($minute) === false
    || is_valid_hour($hour) === false
    || is_valid_minute($minute) === false){
        set_error('時：00～23, 分：00～59の範囲内で入力してください');
        return false;
    }
    var_dump(is_valid_four_digits_number($year));
    var_dump(is_valid_month($month));
    var_dump(is_valid_day($year, $month, $day));
    if(is_valid_four_digits_number($year) === false
    || is_valid_month($month) === false
    || is_valid_day($year, $month, $day) === false){
        set_error('勤怠登録に失敗しました。再度お試しください。');
        return false;
    }
    return true;
}
?>