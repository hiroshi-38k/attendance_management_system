<!DOCTYPE html>
<html lang="ja">
    <head>
        <?php include VIEW_PATH . '/templates/head.php'; ?>
        <link rel="stylesheet" href="<?php print (STYLESHEET_PATH . '/paidholiday_approval.css'); ?>">
        <title>年休承認ページ</title>
    </head>
    <body>
        <?php include VIEW_PATH . '/templates/header_logined.php';?>
        <main>
            <h1>年休承認画面</h1>
            <?php include VIEW_PATH . '/templates/messages.php'; ?>
            <div>
                <h2>年休申請一覧</h2>
                <?php if(empty($applications)){ ?>
                <p class="message">現在、未承認の年休申請はありません</p>
                <?php } ?>
                <table>
                    <thead>
                        <tr>
                            <th>従業員名</th>
                            <th>申請日時</th>
                            <th>取得日時</th>
                            <th>取得形態</th>
                            <th>年休承認</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($applications)){?>
                    <?php foreach($applications as $application){?>
                        <tr>
                            <td><?php print $application['employee_name']; ?></td>
                            <td><?php print $application['createdate']; ?></td>
                            <td><?php print $application['paidholiday_date']; ?></td>
                            <td><?php print $application['paidholiday_type']; ?></td>
                            <td>
                                <form method="post" action="paidholiday_approval_status.php">
                                    <input type="hidden" name="token" value="<?php print $token ?>">
                                    <input type="hidden" name="approval_status" value=1>
                                    <input type="hidden" name="paidholiday_id" value="<?php print $application['paidholiday_id'] ?>">
                                    <input type="submit" value="年休承認">
                                </form>
                                <form method="post" action="paidholiday_approval_status.php">
                                    <input type="hidden" name="token" value="<?php print $token ?>">
                                    <input type="hidden" name="approval_status" value=2>
                                    <input type="hidden" name="paidholiday_id" value="<?php print $application['paidholiday_id'] ?>">
                                    <input type="submit" value="年休取下">
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </main>
    </body>
</html>