<?php
// カレンダー初期化
function calendar_initialize($db, $start_year, $end_year){
    // トランザクション開始
    $db->beginTransaction();
    // １：現行カレンダー削除
    // ２：カレンダー保存期間を基本情報テーブルに登録
    // ３：初期カレンダーを生成し、カレンダーテーブルに登録
    if(delete_calendar_table($db)
    && regist_basic_year($db, $start_year, $end_year)
    && generate_initialcalendar($db, $start_year, $end_year)){
        $db->commit();
        return true;
    }
    // 失敗：ロールバック
    $db->rollback();
    return false;
}
// 現行カレンダー削除
function delete_calendar_table($db){
    // SQL文作成
    $sql = "TRUNCATE TABLE sample2.sf_calendars";
    // クエリ実行
    return execute_query($db, $sql);
}
// 初期カレンダーを生成し、カレンダーテーブルに登録
function generate_initialcalendar($db, $start_year, $end_year){
    $year = (int)$start_year;
    $flag = true;
    while($year <= (int)$end_year){
        $month = JENUARY;
        while($month <= DECEMBER){
            $lastDate = (int)date('d', strtotime('last day of'. $year . '-' . str_pad($month, 2,0, STR_PAD_LEFT)));
            $counter = 1;
            while($counter <= $lastDate){
                $calendar_date = $year . '-' . str_pad($month, 2, 0, STR_PAD_LEFT) . '-' . str_pad($counter, 2, 0, STR_PAD_LEFT);
                $weekday = date('w', strtotime($year. '-' . $month. '-' . $counter));
                if($weekday === '0' || $weekday === '6'){
                    $calendar_status = 0;
                }else{
                    $calendar_status = 1;
                }
                $sql = "
                    INSERT INTO
                        sf_calendars(
                            calendar_date,
                            calendar_status
                        )
                    VALUES(?, ?);
                ";
                $flag = execute_query($db, $sql, array($calendar_date, $calendar_status));
                if($flag === false){
                    return false;
                }
                $counter++;
            }
            $month++;
        }
        $year++;
    }
    return true;
}
// 年度取得
function get_year(){
    if(isset($_GET['year']) === true){
        $year = $_GET['year'];
    }else{
        $year = date('Y');
    }
    return $year;
}
// month取得
function get_month(){
    if(isset($_GET['month'])===true){
        $month = $_GET['month'];
    }else{
        $month = date('m');
    }
    return $month;
}
// day取得
function get_day(){
    if(isset($_GET['day'])===true){
        $day = $_GET['day'];
    }else{
        $day = date('d');
    }
    return $day;
}
// 月初めの曜日取得
function get_first_weekday($year, $month){
    return date('w', strtotime($year. '-' . $month. '-' . '01')); //date('w', strtotime($year.$month.'01'));
}
// 月最後の日付取得
function get_lastdate($year, $month){
    return ((int)date('d', strtotime('last day of' . $year . '-' . $month)));
}
// 日付取得
function get_calendar_date($year, $month, $day){
    return $year . '-' . str_pad((int)$month, 2, 0, STR_PAD_LEFT) . '-' . str_pad((int)$day, 2, 0, STR_PAD_LEFT);
}
// カレンダー表示
function show_calendar($db, $year, $month, $token){
    $weekdays = array('日','月','火','水','木','金','土');
    $weekday = date('w', strtotime($year. '-' . $month. '-' . '01'));
    $lastDate = (int)date('d', strtotime('last day of'.$year.'-'.$month));
    $counter = -$weekday+1;
    $calendars = get_calendar($db, $year, $month);
    echo '<h4>' . $month . '月</h4>';
    echo '<table border="1">';
    echo '<thead>';
    echo '<tr>';
        foreach($weekdays as $w) {
            echo '<th>' . $w . '</th>';
        }
    echo '</tr>'; 
    echo '</thead>';
    echo '<tbody>';
    for($i=0;$i<6;$i++){
        echo '<tr>';
        for($j=0;$j<7;$j++){
            echo '<td>';
            if($counter>0 && $counter <= $lastDate){
                $calendar_date = $year . '-' . str_pad($month, 2, 0, STR_PAD_LEFT) . '-' . str_pad($counter, 2, 0, STR_PAD_LEFT);
                $calendar_status = get_calendar_status($calendar_date, $calendars);
                $calendar_button_class = get_calendar_button_class($calendar_status);
                echo '<form method="post" action="calendar_change_status.php">';
                echo '<input type="hidden" name="token" value="' . $token . '">';
                echo '<input type="hidden" name="calendar_date" value="' . $calendar_date . '">';
                echo '<input type="hidden" name="calendar_status" value="' . $calendar_status . '">';
                echo '<button class="'. $calendar_button_class .'" type="submit">';
                echo $counter;
                echo '</botton>';
                echo '</form>';
            }
           echo '</td>';
           $counter++;
       }
       echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
}
// カレンダーテーブル（年別）取得
function get_calendars($db, $year){
    // SQL文作成
    $sql = "
        SELECT
            calendar_date,
            calendar_status
        FROM
            sf_calendars
        WHERE
            calendar_date like '" . $year . "%'";
    return fetch_all_query($db, $sql);
}
// カレンダーテーブル（月別）取得
function get_calendar($db, $year, $month){
    // SQL文作成
    $sql = "
        SELECT
            calendar_date,
            calendar_status
        FROM
            sf_calendars
        WHERE
            calendar_date like '" . $year. '-' . str_pad($month, 2, 0, STR_PAD_LEFT) . "%'";
    return fetch_all_query($db, $sql);
}
// 休日ステータス取得
function get_calendar_status($calendar_date, $calendars){
    $keys = array_search( $calendar_date, array_column( $calendars, 'calendar_date'));
    return $calendars[$keys]['calendar_status'];
}
// ボタン設定
function get_calendar_button_class($calendar_status){
    if($calendar_status === 0){
        return HOLIDAY;
    }
    return BUSINESSDAY;
}
// 休日ステータス変更
function change_calendar_status($db, $calendar_date, $calendar_status){
    var_dump($calendar_status);
    $calendar_status = change_status($calendar_status);
    update_calendar_status($db, $calendar_date, $calendar_status);
}
// ステータス変更
function change_status($status){
    if($status === '0'){
        $status = 1;
    }else if($status === '1'){
        $status = 0;
    }
    var_dump($status);
    return $status;
}
// 休日ステータス変更をカレンダーテーブルに反映
function update_calendar_status($db, $calendar_date, $calendar_status){
    $sql = "
        UPDATE
            sf_calendars
        SET
            calendar_status = ?
        WHERE
            calendar_date = ?
    ";
    return execute_query($db, $sql, array($calendar_status, $calendar_date));
}
?>