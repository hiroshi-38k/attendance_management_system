<?php
// ログイン処理
function login_as($db, $employee_id, $password){
  // 従業員ID照会：パスワード取得
  $employee = get_employee_by_id($db, $employee_id);
  // ユーザ情報が存在しない、又はパスワードが違う場合もfalseを返す
  if($employee === false || $password !== $employee['password'] ){
    return false;
  }
  // セッション格納
  set_session('employee_id', $employee['employee_id']);
  return $employee;
}
// 従業員ID照会：パスワード取得
function get_employee_by_id($db, $employee_id){
  $sql = "
    SELECT
      employee_id,
      password
    FROM
      sf_employees
    WHERE
      employee_id = ?
    ";
  return fetch_query($db, $sql, array($employee_id));
}
// ログインした従業員の情報取得
function get_login_employee($db, $employee_id){
  $employee = get_employee($db, $employee_id);
  return entity_array($employee);
}
// 従業員情報取得
function get_employee($db, $employee_id){
  $sql = "
    SELECT
      employee_id,
      employee_name,
      sf_employees.department_id,
      department_name,
      sf_employees.team_id,
      team_name,
      employee_type
    FROM
      sf_employees
    INNER JOIN sf_departments ON sf_employees.department_id = sf_departments.department_id
    INNER JOIN sf_teams ON sf_employees.team_id = sf_teams.team_id
    WHERE
      employee_id = ?
  ";
  return fetch_query($db, $sql, array($employee_id));
}
function get_employee_test_list($db){
  $sql ="
    SELECT
      employee_name, employee_id, password, position
    FROM
      sf_employees
    INNER JOIN sf_salaryclass
    ON sf_employees.salaryclass_id = sf_salaryclass.salaryclass_id
  ";
  return fetch_all_query($db, $sql);
}
// 新しい従業員情報の登録
function regist_employee($db, $file){
  // アップロード時のファイル名設定
  $filename = get_upload_file($file);
  if($filename === ''){
    return false;
  }
  // 従業員登録処理成功でtrue, 失敗でfalseを返す
  return execute_regist_employee($db, $file, $filename);
}
// 従業員登録処理
function execute_regist_employee($db, $file, $filename){
  // 従業員登録処理と画像保存が成功：コミット
  if(save_file($file, $filename)){
    // 新規従業員データ取得
    $new_employee_data = get_new_employee_data($filename);
    if(!empty($new_employee_data)){
      // 新しい従業員データを追加
      if(insert_new_employee_transaction($db, $new_employee_data)){
        return true;    
      }
    }
  }
  return false;
}
// 新規従業員データをcsvファイルから取得
function get_new_employee_data($filename){
  // csvファイルを配列として取得
  $csv = get_csv_array($filename);
  // 一行目を配列キー、残りの行をデータとした連想配列取得
  $employee = assoc_array_first_row_is_key($csv);
  // YYYY/mm/dd -> YYYY-mm-dd変換
  foreach($employee as $keys => $values){
    $array = explode('/', $values['hiredate']);
    $year = $array[0];
    $month = str_pad($array[1], 2, 0, STR_PAD_LEFT);
    $day = str_pad($array[2], 2, 0, STR_PAD_LEFT);
    $employee[$keys]['hiredate'] = $year . '-' . $month . '-' . $day;
  }
  return $employee;
}
// 新しい従業員データを追加
function insert_new_employee_transaction($db, $assoc_arrays){
  $db->beginTransaction();
  foreach($assoc_arrays as $values){    
    if(insert_new_employee($db, $values) === false
    || insert_new_employee_attendance($db, $values) === false){
      $db->rollback();
      return false;
    }
  }
  $db->commit();
  return true;
}
// 従業員データ追加処理：従業員情報
function insert_new_employee($db, $values){
  $department_id = getid($db, $values['department_name'], 'department_name', 'department_id', 'sf_departments');
  $team_id = getid($db, $values['team_name'], 'team_name', 'team_id', 'sf_teams');
  $salaryclass_id = getid($db, $values['salaryclass'], 'salaryclass', 'salaryclass_id', 'sf_salaryclass');
  $employee_type = get_employee_type($salaryclass_id);
  if($department_id === false || $team_id === false
  || $salaryclass_id === false || $employee_type === false){
    return false;
  }
  $sql = "
    INSERT INTO sf_employees
    (department_id, team_id, salaryclass_id, employee_name, password, employee_type, hiredate)
    VALUES (?, ?, ?, ?, 'password', ?, ?)
  ";
  return execute_query($db, $sql, array($department_id, $team_id, $salaryclass_id,
                                  $values['employee_name'], $employee_type, $values['hiredate']));
}
// 従業員データ追加処理：勤怠情報
function insert_new_employee_attendance($db, $employee){
  // データ追加用配列作成
  $start_year = substr($employee['hiredate'], 0, 4);
  $end_year = $start_year + 60;
  $employee_id = getid($db, $employee['employee_name'], 'employee_name', 'employee_id', 'sf_employees');
  $year = $start_year;
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
  // SQL文作成
  $sql = get_sql_for_new_attendance($attendances);
  return execute_query($db, $sql);
}
// 勤怠データ追加用SQL文作成
function get_sql_for_new_attendance($attendances){
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
  return $sql;
}
// 特定のカラム値からID取得
function getid($db, $value, $name, $id, $table){
  $sql = "
    SELECT
      {$id}
    FROM
      {$table}
    WHERE
      {$name} = ?
  ";
  $row = fetch_query($db, $sql, array($value));
  if($row === false){
    return false;
  }
  return $row[$id];
}
// 給与クラスIDからemployee_type取得
function get_employee_type($salaryclass_id){
  if($salaryclass_id === 1
  || $salaryclass_id === 2){
    return 3;
  }else if($salaryclass_id === 3){
    return 0;
  }else if($salaryclass_id === 4){
    return 2;
  }else if($salaryclass_id === 5){
    return 1;
  }
  return false;
}
?>