<?php
// リダイレクト
function redirect_to($url){
    header('Location: ' . $url);
    exit;
}
// ポスト値取得
function get_post($name){
    if(isset($_POST[$name]) === true){
        return $_POST[$name];
    };
    return '';
}
// セッションに値を格納
function set_session($name, $value){
    $_SESSION[$name] = $value;
}
// セッションから値を取得
function get_session($name){
    if (isset($_SESSION[$name])){
        return $_SESSION[$name];
    }
    return '';
}
// FILE値取得
function get_file($name){
    // 指定したキーに値が存在するか確認
    if(isset($_FILES[$name]) === true){
        return $_FILES[$name];
    }
    // 値が無ければ空で返す
    return array();
}
// 正常メッセージ
function set_messages($message){
    $_SESSION['__messages'][] = $message;
}
function get_messages(){
    $messages = get_session('__messages');
    if($messages === ''){
        return array();
    }
    set_session('__messages', array());
    return $messages;
}
// 異常メッセージ
function set_error($error){
    $_SESSION['__errors'][] = $error;
}
function get_error(){
    $errors = get_session('__errors');
    if($errors === ''){
        return array();
    }
    set_session('__errors', array());
    return $errors;
}
// ログイン確認
function is_logined(){
    return get_session('employee_id') !== '';
}
// トークン生成
function get_token(){
    // 乱数でトークン生成
    $token = get_random_string();
    // セッションへ格納
    set_session('token', $token);
    return $token;
}
// トークンバリデーション
function is_valid_token(){
    $token = get_post('token');
    var_dump($token);
    if($token === ''){
        return false;
    }
    // セッションに格納したトークンと等しければtrue, 異なればfalse
    return $token === get_session('token');
}
// 乱数文字列生成
function get_random_string($length = 20){
    return substr(base_convert(hash('sha256', uniqid()), 16, 36), 0, $length);
}
// HTMLエンティティ化
function h($str){
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
// HTMLエンティティ化（一次元配列）
function entity_array($array){
    foreach($array as $key => $value){
        $array[$key] = h($value);
    }
    return $array;
}
// HTMLエンティティ化（多次元配列）
function entity_arrays($arrays){
    foreach($arrays as $keys => $values){
        foreach($values as $key => $value){
            $arrays[$keys][$key] = h($value);
        }
    }
    return $arrays;
}
// ファイル名取得
function get_upload_file($file){
    // アップロードファイルのバリデーション
    if(is_valid_uploaded_file($file) === false){
      return '';
    }
    // ファイルの拡張子取得
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    // 乱数 + 拡張子名で返す
    return get_random_string() . '.' . $extension;
  }
// アップロードファイルのバリデーション
function is_valid_uploaded_file($file){
    // 一時ファイルの取得確認
    if(is_uploaded_file($file['tmp_name']) === false){
        // 異常メッセージ
        set_error('ファイル形式が不正です。');
        return false;
    }
    // 一時ファイルの拡張子取得
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    if($extension !== PATHINFO_CSV){
        // 異常メッセージ
        set_error('ファイル形式は' . PATHINFO_CSV . 'のみ利用可能です。');
        return false;
    }
    return true;
}
// ファイル保存
function save_file($file, $filename){
    // 指定のフォルダへファイルを移動し、成功すればtrue、失敗すればfalseを返す
    return move_uploaded_file($file['tmp_name'], $filename);
}
// csvファイルを配列として取得
function get_csv_array($filename){
    setlocale(LC_ALL, 'ja_JP.UTF-8');
  
    $data = file_get_contents($filename);
    $data = mb_convert_encoding($data, 'UTF-8', 'sjis-win');
    $temp = tmpfile();
    $csv = array();
  
    if(($fwrite = fwrite($temp, $data)) !== false
    && ($frewind = rewind($temp)) !== false){
      while(($data = fgetcsv($temp, 0, ",")) !== false){
        $csv[] = $data;
      }
      fclose($temp);
    }
    
    return $csv;
}
// 1行目をキー名とした連想配列を取得
function assoc_array_first_row_is_key($arrays){
    $assoc_arrays = array();
    $assoc_arrays_keys = $arrays[0];
    foreach($arrays as $keys => $values){
      if($keys !== 0){
        foreach($assoc_arrays_keys as $key => $value){
            $assoc_arrays[$keys - 1][$value] = $arrays[$keys][$key];
        }
      }
    }
    return $assoc_arrays;
}
// 入力値検証
function is_valid_approval_status($str){
    return preg_match(REGEX_APPROVAL_STATUS, $str) === 1;
}
function is_valid_status($str){
    return preg_match(REGEX_STATUS, $str) === 1;
}
function is_valid_paidholiday_type($str){
    return preg_match(REGEX_PAIDHOLIDAY_TYPE, $str) === 1;
}
function is_valid_two_digits_number($str){
    return preg_match(TWO_DIGITS_NUMBER, $str) === 1;
}
function is_valid_four_digits_number($str){
    return preg_match(FOUR_DIGITS_NUMBER, $str) === 1;
}
function is_valid_positive_integer($num){
    return preg_match(REGEXP_POSITIVE_INTEGER, $num) === 1;
}
function is_valid_calendar_date($calendar_date){
    return preg_match(CALENDAR_DATE, $calendar_date) === 1;
}
function is_valid_datetime($datetime){
    return preg_match(DATETIME, $datetime) === 1;
}
function is_valid_time($time){
    return preg_match(TIME, $time) === 1;
}
function is_valid_month($month){
    return ($month <= MAX_MONTH && $month >= MIN_MONTH); 
}
function is_valid_day($year, $month, $day){
    $lastDate = get_lastDate($year, $month);
    return ($day <= $lastDate);
}
function is_valid_hour($hour){
    return ($hour <= MAX_HOUR && $hour >= 0); 
}
function is_valid_minute($minute){
    return ($minute <= MAX_MINUTE && $minute >= 0); 
}
function is_valid_numtext($str){
    return (strlen($str) <= MAX_TEXT && strlen($str) >= MIN_TEXT);
}
?>