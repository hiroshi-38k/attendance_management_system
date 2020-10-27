<?php
// カレンダー保存期間を基本情報テーブルに登録
function regist_basic_year($db, $start_year, $end_year){
    // SQL文作成
    $sql_start = "
        UPDATE
            sample2.sf_basic
        SET
            value=?
        WHERE
            name='start_year'
    ";
    $sql_end = "
        UPDATE
            sample2.sf_basic
        SET
            value=?
        WHERE
            name='end_year'
    ";
    // クエリ実行
    if(execute_query($db, $sql_start, array($start_year))
    && execute_query($db, $sql_end, array($end_year))){
        return true;
    }
    return false;
}
// 基本出勤時刻を取得
function get_basic_worktime($db){
    // SQL文作成
    $sql = "
        SELECT
            value
        FROM
            sf_basic
        WHERE
            name = 'basic_worktime'
    ";
    return fetch_query($db, $sql);
}
// 基本退勤時刻を取得
function get_basic_leavetime($db){
    // SQL文作成
    $sql = "
        SELECT
            value
        FROM
            sf_basic
        WHERE
            name = 'basic_leavetime'
    ";
    return fetch_query($db, $sql);
}
// 昼休み開始時刻を取得
function get_start_lunchtime($db){
    // SQL文作成
    $sql = "
        SELECT
            value
        FROM
            sf_basic
        WHERE
            name = 'start_lunchtime'
    ";
    return fetch_query($db, $sql);
}
// 昼休み終了時刻を取得
function get_end_lunchtime($db){
    // SQL文作成
    $sql = "
        SELECT
            value
        FROM
            sf_basic
        WHERE
            name = 'end_lunchtime'
    ";
    return fetch_query($db, $sql);
}
// basicテーブルから基本情報読込
function get_basic_data($db){
    $assoc_array = array();
    $arrays = array();
    // basicテーブルの配列取得
    $arrays = get_basic_array($db);
    // name: 配列キー, value: 値とした連想配列に変換
    $assoc_array = convert_assoc_array_name_and_value($arrays);
    return $assoc_array;
}
// basicテーブル出力処理
function get_basic_array($db){
    // SQL文
    $sql ="
        SELECT
            name, value
        FROM
            sf_basic
    ";
    return fetch_all_query($db, $sql);
}
// 【name: 配列キー】, 【value: 値】で構成されている配列を、連想配列に変換
function convert_assoc_array_name_and_value($arrays){
    $assoc_array = array();
    foreach($arrays as $keys => $values){
        $assoc_array[$values['name']] = $values['value'];
    }
    return $assoc_array;
}

function get_basic_regist_data(){
    $array = ['basic_worktime',
              'basic_leavetime',
              'start_lunchtime',
              'end_lunchtime',
              'paidholiday_number',
              'paidholiday_target_days',
              'overtime_limit'];
    foreach($array as $value){
        $data = get_post($value);
        if($data !== ''){
            $assoc_array['name'] = $value;
            $assoc_array['value'] = $data;
            return $assoc_array;
        }
    }
    return array();
}
// basicテーブル更新
function update_basic_table($db, $array){
    $sql = "
        UPDATE
            sf_basic
        SET
            value = ?
        WHERE
            name = ?
    ";
    return execute_query($db, $sql, array($array['value'], $array['name']));
}

// basicテーブル更新情報のバリデーション
function is_valid_basic_regist_data($db, $array){
    if($array['name'] === 'basic_worktime'
    || $array['name'] === 'basic_leavetime'
    || $array['name'] === 'start_lunchtime'
    || $array['name'] === 'end_lunchtime'
    || $array['name'] === 'overtime_limit'){
        if(is_valid_time($array['value']) === false){
            set_error('時間の項目は、H:時 i:分 s:秒の、HH:ii:ss形式で入力してください。');
            return false;
        }
    }else if($array['name'] === 'paidholiday_number'){
        if(is_valid_positive_integer($array['value']) === false){
            set_error('年休付与日数は、正の整数で入力してください。');
            return false;
        }
    }else if($array['name'] === 'paidholiday_target_days'){
        $basic = get_basic_data($db);
        if(is_valid_positive_integer($array['value']) === false
        || $array['value'] > $basic['paidholiday_number']){
            set_error('年休取得目標は、正の整数かつ、年休付与日数以下で入力してください。');
            return false;
        }
    }
    return true;
}
?>