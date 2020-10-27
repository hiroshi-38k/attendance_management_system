<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include VIEW_PATH . '/templates/head.php'; ?>
    <link rel="stylesheet" href="<?php print (STYLESHEET_PATH . '/businesstrip.css'); ?>">
    <title>出張申請ページ</title>
</head>
<body>
    <?php include VIEW_PATH . '/templates/header_logined.php' ?>
    <main>
        <h1>出張申請画面</h1>
        <?php include VIEW_PATH . '/templates/messages.php'; ?>
        <div>
            <h2>出張申請内容</h2>
            <form method="post" action="businesstrip_application.php">
                <input type="hidden" name="token" value="<?php print $token; ?>">
                <ul>
                    <li>
                        出発日時：<?php print date('Y'); ?>年
                        <input class="short_textbox" type="text" name="depature_month">月
                        <input class="short_textbox" type="text" name="depature_day">日
                        <input class="short_textbox" type="text" name="depature_hour">時
                        <input class="short_textbox" type="text" name="depature_min">分
                    </li>
                    <li>
                        帰着日時：<?php print date('Y'); ?>年
                        <input class="short_textbox" type="text" name="return_month">月
                        <input class="short_textbox" type="text" name="return_day">日
                        <input class="short_textbox" type="text" name="return_hour">時
                        <input class="short_textbox" type="text" name="return_min">分
                    </li>
                    <li>
                        出張場所：
                        <input class="long_textbox" type="text" name="place">
                    </li>
                    <li>
                        出張目的：
                        <input class="long_textbox" type="text" name="purpose">
                    </li>
                    <li>
                        移動費用：
                        <input class="middle_textbox" type="text" name="transportation_expense">円
                    </li>
                    <li>
                        出張宿泊：
                        <select name="lodging_status">
                            <option value=0>無し</option>
                            <option value=1>有り</option>
                        </select>
                    </li>
                    <li>
                        宿泊費用：
                        <input  class="middle_textbox" type="text" name="lodging_expense">円
                    </li>
                </ul>
                <input class="submit" type="submit" value="出張申請">
            </form>
        </div>
    </main>
</body>
</html>