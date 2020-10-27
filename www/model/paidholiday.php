<?php
// 年休申請内容取得
function get_paidholiday_data(){
    $array['employee_id'] = get_session('employee_id');
    $array['paidholiday_month'] = get_post('paidholiday_month');
    $array['paidholiday_day'] = get_post('paidholiday_day');
    $array['paidholiday_type'] = get_post('paidholiday_type');
    return $array;
}
// 年休申請内容を年休情報テーブルに追加
function insert_paidholiday_application($db, $array){
    $sql = "
        INSERT INTO sf_paidholidays
        (employee_id, paidholiday_date, paidholiday_type)
        VALUES (?, ?, ?)
    ";
    $paidholiday = date('Y')
                    . '-' . str_pad((int)$array['paidholiday_month'], 2, 0, STR_PAD_LEFT)
                    . '-' . str_pad((int)$array['paidholiday_day'], 2, 0, STR_PAD_LEFT);
    return execute_query($db, $sql,
                         array($array['employee_id'],
                               $paidholiday,
                               $array['paidholiday_type'])
                        );
}
// 年休申請一覧取得
function get_paidholiday_applications($db, $employee){
    $applications = get_paidholiday_record($db, $employee);
    if($applications === false){
        return array();    
    }
    foreach ($applications as $keys => $values){
        // 申請日時
        $applications[$keys]['createdate'] = substr($values['createdate'], 0, 10);
        // 取得形態
        $applications[$keys]['paidholiday_type'] = conversion_paidholiday_type($values['paidholiday_type']);
    }
    return entity_arrays($applications);
}
function get_paidholiday_record($db, $employee){
    $sql = "
        SELECT
            paidholiday_id,
            employee_name,
            paidholiday_date,
            paidholiday_type,
            sf_paidholidays.createdate
        FROM
            sf_paidholidays
        INNER JOIN
            sf_employees
        ON
            sf_employees.employee_id = sf_paidholidays.employee_id
        WHERE
            approval_status = 0 AND (department_id = ? OR team_id = ?)
    ";
    return fetch_all_query($db, $sql, array($employee['department_id'], $employee['team_id']));
}
// paidholiday_type -> 年休取得形態名
function conversion_paidholiday_type($paidholiday_type){
    if($paidholiday_type === 1){
        $paidholiday_type = '全休';
    }else if($paidholiday_type === 2){
        $paidholiday_type = '午前休';
    }else if($paidholiday_type === 3){
        $paidholiday_type = '午後休';
    }
    return $paidholiday_type;
}
// 年休承認ステータスを更新
function update_paidholiday_approval_status($db, $paidholiday_id, $approval_status){
    $sql = "
        UPDATE
            sf_paidholidays
        SET
            approval_status = ?
        WHERE
            paidholiday_id = ?
    ";
    return execute_query($db, $sql, array($approval_status, $paidholiday_id));
}
// 年休承認内容を勤怠管理テーブルへ反映
function update_attendance_paidholiday($db, $paidholiday_id){
    // 年休情報テーブルから年休予定日を取得
    $attendance = get_paidholiday_period($db, $paidholiday_id);
    // 年休情報を勤怠情報テーブルに反映
    return execute_update_attendance_paidholiday( $db, $attendance);
}
// 年休情報テーブルから年休予定日を取得
function get_paidholiday_period($db, $paidholiday_id){
    $start_lunchtime = get_start_lunchtime($db);
    $end_lunchtime = get_end_lunchtime($db);
    $attendance = get_paidholiday_date($db, $paidholiday_id);
    if($attendance['paidholiday_type'] === '2'){
        $attendance['work_time'] = $attendance['paidholiday_date'] . ' ' . $end_lunchtime['value'];
    }else if($attendance['paidholiday_type'] === '3'){
        $attendance['leave_time'] = $attendance['paidholiday_date'] . ' ' . $start_lunchtime['value'];
    }
    return $attendance;
}
function get_paidholiday_date($db, $paidholiday_id){
    $sql = "
    SELECT
        paidholiday_date,
        paidholiday_type,
        employee_id
    FROM
        sf_paidholidays
    WHERE
        paidholiday_id = ?
    ";
    return fetch_query($db, $sql, array($paidholiday_id));
}
// 年休情報を勤怠情報テーブルに反映させるSQL文作成
function execute_update_attendance_paidholiday($db, $attendance){
    if($attendance['paidholiday_type'] === 1){
        $sql = "
            UPDATE
                sf_attendances
            SET
                approval_status = 1, attendance_type = 1
            WHERE
                calendar_date = ? AND employee_id = ?
        ";
        $array = array($attendance['paidholiday_date'], $attendance['employee_id']);
    }else if($attendance['paidholiday_type'] === 2){
        $sql = "
            UPDATE
                sf_attendances
            SET
                work_time = ?, attendance_type = 2
            WHERE
                calendar_date = ? AND employee_id = ?
        ";
        $array = array($attendance['work_time'], $attendance['paidholiday_date'], $attendance['employee_id']);
    }else if($attendance['paidholiday_type'] === 3){
        $sql = "
            UPDATE
                sf_attendances
            SET
                leave_time = ?, attendance_type = 3
            WHERE
                calendar_date = ? AND employee_id = ?
        ";
        $array = array($attendance['leave_time'], $attendance['paidholiday_date'], $attendance['employee_id']);
    }
    var_dump($array);
    return execute_query($db, $sql, $array);
}
// paidholiday.php 入力値バリデーション
function is_valid_paidholiday_data($array){
    var_dump(is_valid_two_digits_number($array['paidholiday_month']));
    var_dump(is_valid_month((int)$array['paidholiday_month']));
    if(is_valid_two_digits_number($array['paidholiday_month']) === false
    || is_valid_month((int)$array['paidholiday_month']) === false){
        set_error('月：2桁の整数かつ、00～12の範囲で入力してください');
        return false;
    }
    var_dump(is_valid_two_digits_number($array['paidholiday_day']));
    var_dump(is_valid_day(date('Y'), (int)$array['paidholiday_month'], (int)$array['paidholiday_day']));
    if(is_valid_two_digits_number($array['paidholiday_day']) === false
    || is_valid_day(date('Y'), (int)$array['paidholiday_month'], (int)$array['paidholiday_day']) === false){
        set_error('日：2桁の整数かつ、00～月の最終日の範囲で入力してください');
        return false;
    }
    var_dump(is_valid_paidholiday_type($array['paidholiday_type']));
    if(is_valid_paidholiday_type($array['paidholiday_type']) === false){
        set_error('取得形態を選択してください。');
        return false;
    }
    return true;
}
?>