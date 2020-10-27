<?php
// 出張申請内容取得
function get_businesstrip_data(){
    $array['employee_id'] = get_session('employee_id');
    $array['depature_month'] = get_post('depature_month');
    $array['depature_day'] = get_post('depature_day');
    $array['depature_hour'] = get_post('depature_hour');
    $array['depature_min'] = get_post('depature_min');
    $array['depature_time'] = get_date_time($array['depature_month'], $array['depature_day'], $array['depature_hour'], $array['depature_min']);
    $array['return_month'] = get_post('return_month');
    $array['return_day'] = get_post('return_day');
    $array['return_hour'] = get_post('return_hour');
    $array['return_min'] = get_post('return_min');
    $array['return_time'] = get_date_time($array['return_month'], $array['return_day'], $array['return_hour'], $array['return_min']);
    $array['place'] = get_post('place');
    $array['purpose'] = get_post('purpose');
    $array['lodging_status'] = get_post('lodging_status');
    $array['lodging_expense'] = get_post('lodging_expense');
    $array['transportation_expense'] = get_post('transportation_expense');
    return $array;
}
function get_date_time($month, $day, $hour, $min){
    $year = date('Y');
    return $year
            . '-' . str_pad((int)$month, 2, 0, STR_PAD_LEFT)
            . '-' . str_pad((int)$day, 2, 0, STR_PAD_LEFT)
            . ' ' . str_pad((int)$hour, 2, 0, STR_PAD_LEFT)
            . ':' . str_pad((int)$min, 2, 0, STR_PAD_LEFT)
            . ':00';
}
// 出張申請内容を出張情報テーブルに追加
function insert_businesstrip_application($db, $array){
    $sql = "
        INSERT INTO sf_businesstrips
        (employee_id, depature_time, return_time,
         place, purpose, transportation_expense,
         lodging_status, lodging_expense, approval_status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)
    ";
    return execute_query($db, $sql,
           array($array['employee_id'],
                 $array['depature_time'],
                 $array['return_time'],
                 $array['place'],
                 $array['purpose'],
                 (int)$array['transportation_expense'],
                 $array['lodging_status'],
                 (int)$array['lodging_expense'])
                );
}
// 出張申請一覧取得
function get_businesstrip_applications($db, $employee){
    $applications = get_businesstrip_record($db, $employee);
    if($applications === false){
        return array();
    }
    foreach ($applications as $keys => $application){
        // 合計費用
        $applications[$keys]['total_expense']
        = (int)$application['lodging_expense'] + (int)$application['transportation_expense'];
        // 出発日
        $applications[$keys]['depature_date']
        = substr($applications[$keys]['depature_time'], 0, 10);
        // 帰着日
        $applications[$keys]['return_date']
        = substr($applications[$keys]['return_time'], 0, 10);
    }
    return entity_arrays($applications);
}
function get_businesstrip_record($db, $employee){
    $sql = "
        SELECT
            businesstrip_id,
            employee_name,
            depature_time,
            return_time,
            place,
            purpose,
            lodging_expense,
            transportation_expense
        FROM
            sf_businesstrips
        INNER JOIN
            sf_employees ON sf_employees.employee_id = sf_businesstrips.employee_id
        WHERE
            approval_status = 0 AND (department_id = ? OR team_id = ?)
    ";
    return fetch_all_query($db, $sql, array($employee['department_id'], $employee['team_id']));
}
// 出張承認ステータスを更新
function update_businesstrip_approval_status($db, $businesstrip_id, $approval_status){
    $sql = "
        UPDATE
            sf_businesstrips
        SET
            approval_status = ?
        WHERE
            businesstrip_id = ?
    ";
    return execute_query($db, $sql, array($approval_status, $businesstrip_id));
}
// 出張承認内容を勤怠管理テーブルへ反映
function update_businesstrip_attendance($db, $businesstrip_id, $depature_date, $return_date){
    $flag = false;
    // 単日の出張
    if($depature_date === $return_date){
        $flag = update_attendance_oneday_businesstrip($db, $businesstrip_id);
    // 連日の出張
    }else{
        $flag = update_attendance_daily_businesstrip($db, $businesstrip_id, $depature_date, $return_date);
    }
    return $flag;
}
// 単日の出張
function update_attendance_oneday_businesstrip($db, $businesstrip_id){
    // 出勤・退勤日時取得
    $attendance = get_businesstrip_period($db, $businesstrip_id);
    $calendar_date = substr($attendance['depature_time'], 0, 10);
    // 勤怠情報テーブルに出勤・退勤時刻を反映
    return update_attendance_businesstrip($db, $attendance['depature_time'], $attendance['return_time'], $calendar_date, $attendance['employee_id']);
}
// 連日の出張
function update_attendance_daily_businesstrip($db, $businesstrip_id, $depature_date, $return_date){
    $flag = false;
    // 出張情報テーブルから出発・帰着日時を取得
    $attendance = get_businesstrip_period($db, $businesstrip_id);
    // 基本情報テーブルから基本出勤・退勤時刻を取得
    $basic_worktime = get_basic_worktime($db);
    $basic_leavetime = get_basic_leavetime($db);
    // 勤怠情報テーブルに出勤・退勤時刻を反映
    for($calendar_date = $depature_date;
        $calendar_date <= $return_date;
        $calendar_date = date('Y-m-d', strtotime($calendar_date . '+1 day'))
    ){
        $work_time = get_businesstrip_worktime($calendar_date, $depature_date, $basic_worktime, $attendance);
        $leave_time = get_businesstrip_leavetime($calendar_date, $return_date, $basic_leavetime, $attendance); 
        $flag = update_attendance_businesstrip($db, $work_time, $leave_time, $calendar_date, $attendance['employee_id']);
    }
    return $flag;
}
// 出張情報テーブルから出発・帰着日時を取得
function get_businesstrip_period($db, $businesstrip_id){
    // 出勤・退勤日時取得
    $sql = "
    SELECT
        depature_time,
        employee_id,
        return_time
    FROM
        sf_businesstrips
    WHERE
        businesstrip_id = ?
    ";
    return fetch_query($db, $sql, array($businesstrip_id));
}
// 出張中の出勤日時取得
function get_businesstrip_worktime($calendar_date, $depature_date, $basic_worktime, $attendance){
    if($calendar_date === $depature_date){
        $work_time = $attendance['depature_time'];
    }else{
        $work_time = $calendar_date . ' ' . $basic_worktime['value'];
    }
    return $work_time;
}
// 出張中の退勤日時取得
function get_businesstrip_leavetime($calendar_date, $return_date, $basic_leavetime, $attendance){
    if($calendar_date === $return_date){
        $leave_time = $attendance['return_time'];
    }else{
        $leave_time = $calendar_date . ' ' . $basic_leavetime['value'];
    }
    return $leave_time;
}
// 勤怠反映
function update_attendance_businesstrip($db, $work_time, $leave_time, $calendar_date, $employee_id){
    $sql = "
        UPDATE
            sf_attendances
        SET
            work_time = ?, leave_time = ?, approval_status = 1, attendance_type = 6
        WHERE
            calendar_date = ? AND employee_id = ?
    ";
    return execute_query($db, $sql, array($work_time, $leave_time, $calendar_date, $employee_id));
}
// businesstrip.php 入力値バリデーション
function is_valid_businesstrip_data($array){
    if(is_valid_two_digits_number($array['depature_month']) === false
    || is_valid_two_digits_number($array['return_month']) === false
    || is_valid_month((int)$array['depature_month']) === false
    || is_valid_month((int)$array['return_month']) === false){
        set_error('月：2桁の整数かつ、00～12の範囲で入力してください');
        return false;
    }
    if(is_valid_two_digits_number($array['depature_day']) === false
    || is_valid_two_digits_number($array['return_day']) === false
    || is_valid_day(date('Y'), (int)$array['depature_month'], (int)$array['depature_day']) === false
    || is_valid_day(date('Y'), (int)$array['return_month'], (int)$array['return_day']) === false){
        set_error('日：2桁の整数かつ、00～月の最終日の範囲で入力してください');
        return false;
    }
    if(is_valid_two_digits_number($array['depature_hour']) === false
    || is_valid_two_digits_number($array['depature_min']) === false
    || is_valid_hour($array['depature_hour']) === false
    || is_valid_minute($array['depature_min']) === false
    || is_valid_two_digits_number($array['return_hour']) === false
    || is_valid_two_digits_number($array['return_min']) === false
    || is_valid_hour($array['return_hour']) === false
    || is_valid_minute($array['return_min']) === false){
        set_error('時刻：2桁の整数かつ、時：00～23, 分：00～59の範囲で入力してください');
        return false;
    }
    if(is_valid_numtext($array['place']) === false
    || is_valid_numtext($array['purpose']) === false){
        set_error('出張場所・目的は100文字以内で入力してください');
        return false;
    }
    if(is_valid_positive_integer($array['transportation_expense']) === false){
        set_error('費用は0以上の整数を入力してください');
        return false;
    }
    if(is_valid_status($array['lodging_status']) === false){
        set_error('申請に失敗しました。再度お試しください。');
        return false;
    }
    if($array['lodging_status'] === '1'){
        var_dump(is_valid_positive_integer($array['lodging_expense']));
        if(is_valid_positive_integer($array['lodging_expense']) === false){
            set_error('出張場所・目的は100文字以内で入力してください');
            return false;
        }
    }
    return true;
}
// businesstrip_approval.php 入力値バリデーション
function is_valid_businesstrip_approval($approval_status, $depature_date, $return_date){
    if(is_valid_approval_status($approval_status) === false
    || is_valid_calendar_date($depature_date) === false
    || is_valid_calendar_date($return_date) === false){
        set_error('出張承認に失敗しました。再度お試しください。');
        return false;
    }
    return true;
}
?>