<?php
// 新しい給与クラス情報
function get_salary_data(){
    $assoc_array = array();
    $assoc_array['position'] = get_post('position');
    $assoc_array['salaryclass'] = get_post('salaryclass');
    $assoc_array['basic_salary'] = get_post('basic_salary');
    $assoc_array['overtime_pay'] = get_post('overtime_pay');
    return $assoc_array;
}
// basic.php 入力値バリデーション
function is_valid_salary_data($assoc_array){
    if(empty($assoc_array)){
        set_error('給与クラス追加に失敗しました。');
        return false;
    }
    if(is_valid_numtext($assoc_array['position']) === false){
        set_error('役職は100文字以内で入力してください。');
        return false;
    }
    if(is_valid_numtext($assoc_array['salaryclass']) === false){
        set_error('給与クラスは100文字以内で入力してください。');
        return false;
    }
    if(is_valid_positive_integer($assoc_array['basic_salary']) === false
    || is_valid_positive_integer($assoc_array['overtime_pay']) === false){
        set_error('月給・残業手当は正の整数を入力してください。');
        return false;
    }
    return true;
}
// 給与クラス追加
function insert_salaryclass($db, $salary){
    $sql = "
        INSERT INTO sf_salaryclass
        (salaryclass, position, basic_salary, overtime_pay)
        VALUES (?, ?, ?, ?)
    ";
    return execute_query($db, $sql, 
                         array($salary['salaryclass'], 
                               $salary['position'],
                               $salary['basic_salary'],
                               $salary['overtime_pay']));
}
// 給与クラス一覧読込
function get_salary_record($db){
    $sql ="
        SELECT
            salaryclass_id, salaryclass, position, basic_salary, overtime_pay
        FROM
            sf_salaryclass
    ";
    return fetch_all_query($db, $sql);
}
?>