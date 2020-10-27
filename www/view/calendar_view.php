<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include VIEW_PATH . '/templates/head.php'; ?>
    <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . '/calendar.css') ?>">
    <title>休日設定ページ</title>
</head>
<body>
    <?php include VIEW_PATH . '/templates/header_logined.php'; ?>
    <main>
        <h1>休日設定画面</h1>
        <h2><?php print $year; ?>年カレンダー</h2>
        <p>
            <a href='calendar_setting.php?year=<?php print $last_year; ?>'>前年</a>
            <a href='calendar_setting.php?year=<?php print $next_year; ?>'>翌年</a>
        </p>
        <div class="container">
            <p>
                <button class="sample_button businessday">平日</button>
                <button class="sample_button holiday">休日</button>
            </p>
            <div class="calendars">
                <?php foreach($months as $month){ ?>
                <div class="calendar">
                    <?php show_calendar($db, $year, $month, $token); ?>
                </div>
                <?php } ?>
            </div>
        </div>
        <div class="container">
            <h3>カレンダー初期化</h3>
            <form method="post" action="calendar_initialize.php">
                <input type="hidden" name="token" value="<?php print $token; ?>">
                <p>
                    カレンダー設定の保存期間：
                    <input type="text" name="start_year" value="">年
                    ～
                    <input type="text" name="end_year" value="">年
                </p>
                <p>
                    <input type="submit" value="初期化">
                </p>
            </form>
        </div>
    </main>
</body>
</html>