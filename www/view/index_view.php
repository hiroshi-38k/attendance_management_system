<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include VIEW_PATH . '/templates/head.php'; ?>
    <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . '/index.css'); ?>">
    <title>勤怠管理ページ</title>
</head>
<body>
    <?php include VIEW_PATH . '/templates/header_logined.php'; ?>
    <main>
    <h1>勤怠管理画面</h1>
    <?php include VIEW_PATH . '/templates/messages.php'; ?>
    <div class="main_container">
        <div class="sub_container">
            <nav class="calendar">
                <?php include VIEW_PATH . '/templates/calendar.php'; ?>
            </nav>
        </div>
        <div class="sub_container">
            <nav>
                <h2>従業員情報</h2>
                <ul>
                    <li>従業員ID：<?php print str_pad($employee['employee_id'], 6,0, STR_PAD_LEFT); ?></li>
                    <li>従業員名：<?php print $employee['employee_name']; ?></li>
                </ul>
                <ul>
                    <li>所属部門：<?php print $employee['department_name']; ?></li>
                    <li>所属チーム：<?php print $employee['team_name']; ?></li>
                </ul>
            </nav>
            <nav class="working_info">
                <h2>勤怠情報</h2>
                <p>通勤形態：<?php print $attendance_type; ?></p>
                <p>
                    <form method="post" action="index_attendance_application.php">
                        <input type="hidden" name="token" value="<?php print $token; ?>">
                        <input type="hidden" name="calendar_date" value="<?php print $calendar_date; ?>">
                        <input type="hidden" name="year" value="<?php print $year; ?>">
                        <input type="hidden" name="month" value="<?php print $month; ?>">
                        <input type="hidden" name="day" value="<?php print $day; ?>">
                        <input type="hidden" name="process_type" value="worktime">
                        出勤時刻：
                        <input class="textbox" type="text" name="worktime_hour" value="<?php if(isset($worktime_hour)){ print $worktime_hour;} ?>">時
                        <input class="textbox" type="text" name="worktime_min" value="<?php if(isset($worktime_min)){ print $worktime_min;} ?>">分
                        <input type="submit" value="出勤登録">
                    </form>
                </p>
                <p>
                    <form method="post" action="index_attendance_application.php">
                        <input type="hidden" name="token" value="<?php print $token; ?>">
                        <input type="hidden" name="calendar_date" value="<?php print $calendar_date; ?>">
                        <input type="hidden" name="year" value="<?php print $year; ?>">
                        <input type="hidden" name="month" value="<?php print $month; ?>">
                        <input type="hidden" name="day" value="<?php print $day; ?>">
                        <input type="hidden" name="process_type" value="leavetime">
                        退勤時刻：
                        <input class="textbox" type="text" name="leavetime_hour" value="<?php if(isset($leavetime_hour)){ print $leavetime_hour;} ?>">時
                        <input class="textbox" type="text" name="leavetime_min" value="<?php if(isset($leavetime_min)){ print $leavetime_min;} ?>">分
                        <input type="submit" value="退勤登録">
                    </form>
                </p>
                <p>上司承認：【<?php print $approval_status; ?>】</p>
            </nav>
        </div>
    </div>
    </main>
</body>
</html>