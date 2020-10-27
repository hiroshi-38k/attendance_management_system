<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include VIEW_PATH . '/templates/head.php'; ?>
    <link rel="stylesheet" href="<?php print (STYLESHEET_PATH . '/employee.css'); ?>">
    <title>従業員情報管理ページ</title>
</head>
<body>
    <?php include VIEW_PATH . '/templates/header_logined.php'; ?>
    <main>
        <h1>従業員情報管理画面</h1>
        <?php include VIEW_PATH . '/templates/messages.php'; ?>
        <div>
            <h2>従業員情報追加（csvファイルアップロード）</h2>
            <form method="post" enctype="multipart/form-data" action="employee_add.php">
                <input type="hidden" name="token" value="<?php print $token; ?>">
                <p>
                    csvファイル選択：
                    <input type="file" name="new_employee_data">
                    <input type="submit" value="アップロード">
                </p>
            </form>
        </div>
        <div>
            <h2>従業員情報一覧</h2>
            <table>
                <thead>
                    <tr>
                        <th>従業員名</th>
                        <th>所属部門</th>
                        <th>所属チーム</th>
                        <th>役職</th>
                        <th>給与クラス</th>
                        <th>在職状況</th>
                    </tr>
                </thead>
                <?php if(!empty($employees)){ ?>
                <tbody>
                    <?php foreach($employees as $employee){?>
                    <tr>
                        <td>   
                            <?php print $employee['employee_name']; ?>
                        </td>
                        <td>
                            <form method="post" action="employee_change.php">
                                <input type="hidden" name="process_type" value="department_name">
                                <input type="hidden" name="employee_id" value="<?php print $employee['employee_id']; ?>">
                                <input type="text" name="department_name" value="<?php print $employee['department_name']; ?>">
                            </form>
                        </td>
                        <td>
                            <form method="post" action="employee_change.php">
                                <input type="hidden" name="process_type" value="team_name">
                                <input type="hidden" name="employee_id" value="<?php print $employee['employee_id']; ?>">
                                <input type="text" name="team_name" value="<?php print $employee['team_name']; ?>">
                            </form>
                        </td>
                        <td>
                            <form method="post" action="employee_change.php">
                                <input type="hidden" name="process_type" value="position">
                                <input type="hidden" name="employee_id" value="<?php print $employee['employee_id']; ?>">
                                <input type="text" name="position" value="<?php print $employee['position']; ?>">
                            </form>
                        </td>
                        <td>
                            <form method="post" action="employee_change.php">
                                <input type="hidden" name="process_type" value="salaryclass">
                                <input type="hidden" name="employee_id" value="<?php print $employee['employee_id']; ?>">
                                <input type="text" name="salaryclass" value="<?php print $employee['salaryclass']; ?>">
                            </form>
                        </td>
                        <td>
                            <?php if((int)$employee_status === 1){ print '在職中'; } ?>
                            <?php if((int)$employee_status === 0){ print '離職済'; } ?>
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