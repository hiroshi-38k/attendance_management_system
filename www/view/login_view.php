<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include VIEW_PATH . '/templates/head.php'; ?>
    <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . '/login.css'); ?>">
    <title>ログイン画面</title>
</head>
<body>
    <?php include VIEW_PATH . '/templates/header.php'; ?>
    <main>
    <div class="container">
        <h1>ログイン</h1>
        <form method="post" action="login_process.php">
            <input type="hidden" name="token" value="<?php print $token; ?>">
            <p>
                <label for="employee_id">従業員番号：</label>
                <input type="text" name="employee_id" id="employee_id">
            </p>
            <p>
                <label for="password">パスワード：</label>
                <input type="text" name="password" id="password">
            </p>
            <p>
                <input type="submit" value="勤怠管理画面へ移動">
            </p>
        </form>
    </div>
    <div class="exposition">
        <p>(1) 本ページは架空の会社の勤怠管理システムです。</p>
        <p>(2) 下記リストの従業員ID、パスワードを用いてログインしてください。</p>
        <table>
            <thead>
                <tr>
                    <th>従業員名</th>
                    <th>従業員ID</th>
                    <th>パスワード</th>
                    <th>役職</th>
                </tr>
            </thead>
            <?php if(!empty($employee)){?>
            <?php foreach($employee as $value){ ?>
            <tbody>
                <tr>
                    <td><?php print $value['employee_name']; ?></td>
                    <td><?php print $value['employee_id']; ?></td>
                    <td><?php print $value['password']; ?></td>
                    <td><?php print $value['position']; ?></td>
                </tr>
            </tbody>
            <?php } ?>
            <?php } ?>
        </table>
    </div>
    </main>
</body>
</html>