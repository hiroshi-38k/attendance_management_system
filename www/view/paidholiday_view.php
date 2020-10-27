<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include VIEW_PATH . '/templates/head.php'; ?>
    <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . '/paidholiday.css'); ?>">
    <title>年次有給休暇申請ページ</title>
</head>
<body>
    <?php include VIEW_PATH . '/templates/header_logined.php' ?>
    <main>
        <h1>年次有給休暇申請画面</h1>
        <?php include VIEW_PATH . '/templates/messages.php'; ?>
        <div>
            <h2>申請内容詳細</h2>
            <form method="post" action="paidholiday_application.php">
                <input type="hidden" name="token" value="<?php print $token; ?>">
                <ul>
                    <li>
                        取得日時：2020年
                        <input class="short_textbox" type="text" name="paidholiday_month">月
                        <input class="short_textbox" type="text" name="paidholiday_day">日
                    </li>
                    <li>
                        取得形態：
                        <select name="paidholiday_type">
                            <option value="">選択してください</option>
                            <option value="1">全休</option>
                            <option value="2">午前休</option>
                            <option value="3">午後休</option>
                        </select>
                    </li>
                </ul>
                <p>
                    <input class="submit" type="submit" value="年休申請">
                </p>
            </form>
        </div>
    </main>
</body>
</html>