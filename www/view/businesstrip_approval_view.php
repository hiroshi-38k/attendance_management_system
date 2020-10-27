<!DOCTYPE html>
<html lang="ja">
    <head>
        <?php include VIEW_PATH . '/templates/head.php'; ?>
        <link rel="stylesheet" href="<?php print (STYLESHEET_PATH . '/businesstrip_approval.css'); ?>">
        <title>出張承認ページ</title>
    </head>
    <body>
        <?php include VIEW_PATH . '/templates/header_logined.php';?>
        <main>
            <h1>出張承認画面</h1>
            <?php include VIEW_PATH . '/templates/messages.php'; ?>
            <div>
                <h2>出張申請一覧</h2>
                <?php if(empty($applications)){ ?>
                    <p class="message">現在、未承認の出張申請はありません</p>
                <?php } ?>
                <table>
                    <thead>
                        <tr>
                            <th>従業員名</th>
                            <th>出発日時</th>
                            <th>帰着日時</th>
                            <th>出張場所</th>
                            <th>出張目的</th>
                            <th>費用</th>
                            <th>承認</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($applications)){ ?>
                        <?php foreach($applications as $application){ ?>
                        <tr>
                            <td><?php print $application['employee_name']; ?></td>
                            <td><?php print $application['depature_time']; ?></td>
                            <td><?php print $application['return_time']; ?></td>
                            <td><?php print $application['place']; ?></td>
                            <td><?php print $application['purpose']; ?></td>
                            <td><?php print $application['total_expense']; ?></td>
                            <td>
                                <form method="post" action="businesstrip_approval_status.php">
                                    <input type="hidden" name="token" value="<?php print $token; ?>">
                                    <input type="hidden" name="depature_date" value="<?php print $application['depature_date']; ?>">
                                    <input type="hidden" name="return_date" value="<?php print $application['return_date']; ?>">
                                    <input type="hidden" name="businesstrip_id" value="<?php print $application['businesstrip_id']; ?>">
                                    <input type="hidden" name="approval_status" value=1>
                                    <input type="submit" value="出張承認">
                                </form>
                                <form method="post" action="businesstrip_approval_status.php">
                                    <input type="hidden" name="token" value="<?php print $token; ?>">
                                    <input type="hidden" name="depature_date" value="<?php print $application['depature_date']; ?>">
                                    <input type="hidden" name="return_date" value="<?php print $application['return_date']; ?>">
                                    <input type="hidden" name="businesstrip_id" value="<?php print $application['businesstrip_id']; ?>">
                                    <input type="hidden" name="approval_status" value=2>
                                    <input type="submit" value="出張取下">
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