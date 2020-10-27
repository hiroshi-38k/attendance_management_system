<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include VIEW_PATH . '/templates/head.php'; ?>
    <link rel="stylesheet" href="<?php print STYLESHEET_PATH . '/attendances_approval.css'; ?>">
    <title>勤怠承認ページ</title>
</head>
<body>
    <?php include VIEW_PATH . '/templates/header_logined.php'; ?>
    <main>
        <h1>勤怠承認画面</h1>
        <?php include VIEW_PATH . '/templates/messages.php'; ?>
        <div>
            <h2>勤怠申請一覧</h2>
            <?php if(empty($applications)){ ?>
                <p class="message">現在、未承認の勤怠申請はありません</p>
            <?php } ?>
            <table>
                <thead>
                    <tr>
                        <th>従業員名</th>
                        <th>出勤日時</th>
                        <th>出勤形態</th>
                        <th>出勤時刻</th>
                        <th>退勤時刻</th>
                        <th>勤怠承認</th>
                    </tr>
                </thead>
                <?php if(!empty($applications)){ ?>
                <tbody>
                    <?php foreach($applications as $application) { ?>
                    <tr>
                        <td><?php print $application['employee_name']; ?></td>
                        <td><?php print $application['calendar_date']; ?></td>
                        <td><?php print $application['attendance_type']; ?></td>
                        <td><?php print $application['work_time']; ?></td>
                        <td><?php print $application['leave_time']; ?></td>
                        <td>
                            <form method="post" action="attendance_approval_status.php">
                            <input type="hidden" name="token" value="<?php print $token; ?>">
                                <input type="hidden" name="attendance_id" value="<?php print $application['attendance_id']; ?>">
                                <input type="hidden" name="approval_status" value=1>
                                <input type="submit" value="承認">
                            </form>
                            <form method="post" action="attendance_approval_status.php">
                                <input type="hidden" name="token" value="<?php print $token; ?>">
                                <input type="hidden" name="attendance_id" value="<?php print $application['attendance_id']; ?>">
                                <input type="hidden" name="approval_status" value=2>
                                <input type="submit" value="取下">
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
                <?php } ?>
            </table>
        </div>
    </main>
</body>
</html>
