<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include VIEW_PATH . '/templates/head.php'; ?>
    <link rel="stylesheet" href="<?php print (STYLESHEET_PATH . '/basic.css'); ?>">
    <title>基本情報設定ページ</title>
</head>
<body>
    <?php include VIEW_PATH . '/templates/header_logined.php'; ?>
    <main>
        <h1>基本情報設定画面</h1>
        <?php include VIEW_PATH . '/templates/messages.php'; ?>
        <div>
            <h2>労働時間 (時:分:秒)</h2>
                <ul>
                    <li>
                        <form method="post" action="basic_registration.php">
                            始業時間　　　　：
                            <input type="hidden" name="token" value="<?php print $token; ?>">
                            <input type="text" name="basic_worktime" value="<?php if(isset($basic['basic_worktime'])){ print $basic['basic_worktime']; } ?>">
                            <input type="submit" value="更新">
                        </form>
                    </li>
                    <li>
                        <form method="post" action="basic_registration.php">
                            終業時間　　　　：
                            <input type="hidden" name="token" value="<?php print $token; ?>">
                            <input type="text" name="basic_leavetime" value="<?php if(isset($basic['basic_leavetime'])){ print $basic['basic_leavetime']; } ?>">
                            <input type="submit" value="更新">
                        </form>
                    </li>
                    <li>
                        <form method="post" action="basic_registration.php">
                            昼休憩開始時間　：
                            <input type="hidden" name="token" value="<?php print $token; ?>">
                            <input type="text" name="start_lunchtime" value="<?php if(isset($basic['start_lunchtime'])){ print $basic['start_lunchtime']; } ?>">
                            <input type="submit" value="更新">
                        </form>
                    </li>
                    <li>
                        <form method="post" action="basic_registration.php">
                            昼休憩終了時間　：
                            <input type="hidden" name="token" value="<?php print $token; ?>">
                            <input type="text" name="end_lunchtime" value="<?php if(isset($basic['end_lunchtime'])){ print $basic['end_lunchtime']; } ?>">
                            <input type="submit" value="更新">
                        </form>
                    </li>
                    <li>
                        <form method="post" action="basic_registration.php">
                            規定年間残業時間：
                            <input type="hidden" name="token" value="<?php print $token; ?>">
                            <input type="text" name="overtime_limit" value="<?php if(isset($basic['overtime_limit'])){ print $basic['overtime_limit']; }?>">
                            <input type="submit" value="更新">
                        </form>
                    </li>
                </ul>
        </div>
        <div>
            <h2>年次有給休暇 (日)</h2>
                <ul>
                    <li>
                        <form method="post" action="basic_registration.php">
                            年間付与日数　　：
                            <input type="hidden" name="token" value="<?php print $token; ?>">
                            <input type="text" name="paidholiday_number" value="<?php if(isset($basic['paidholiday_number'])){ print $basic['paidholiday_number']; } ?>">
                            <input type="submit" value="更新">
                        </form>
                    </li>
                    <li>
                        <form method="post" action="basic_registration.php">
                            年間取得目標　　：
                            <input type="hidden" name="token" value="<?php print $token; ?>">
                            <input type="text" name="paidholiday_target_days" value="<?php if(isset($basic['paidholiday_target_days'])){ print $basic['paidholiday_target_days']; } ?>">
                            <input type="submit" value="更新">
                        </form>
                    </li>
                </ul>
        </div>
        <div>
            <h2>給与クラス追加</h2>
            <form method="post" action="basic_salaryclass.php">
                <input type="hidden" name="token" value="<?php print $token; ?>">
                <ul>
                    <li>
                        役職　　　　　　：
                        <input type="text" name="position" value="<?php if(isset($salary['position'])){ print $salary['position']; }?>">
                    </li>
                    <li>
                        給与クラス　　　：
                        <input type="text" name="salaryclass" value="<?php if(isset($salary['salaryclass'])){ print $salary['salaryclass']; }?>">
                    </li>
                    <li>
                        基本月給　　　　：
                        <input type="text" name="basic_salary" value="<?php if(isset($salary['basic_salary'])){ print $salary['basic_salary']; }?>">
                        円/月
                    </li>
                    <li>
                        残業手当　　　　：
                        <input type="text" name="overtime_pay" value="<?php if(isset($salary['overtime_salary'])){ print $salary['overtime_salary']; }?>">
                        円/時
                    </li>
                </ul>
                <p>
                    <input type="submit" value="追加登録">
                </p>
            </form>
        </div>
        <div>
            <h2>給与クラス一覧</h2>
            <table>
                <thead>
                    <tr>
                        <th>役職</th>
                        <th>給与クラス</th>
                        <th>基本月給[円/月]</th>
                        <th>残業手当[円/時]</th>
                    </tr>
                </thead>
                <?php if(!empty($salary)){ ?>
                <tbody>
                    <?php foreach($salary as $value){ ?>
                    <tr>
                        <td>
                            <form method="post">
                                <input type="hidden" name="token" value="<?php print $token; ?>">
                                <input type="hidden" name="salaryclass_id" value="<?php print $value['salaryclass_id']; ?>">
                                <input type="hidden" name="salaryclass" value="<?php print $value['salaryclass']; ?>">
                                <input type="text" name="position" value="<?php print $value['position']; ?>">
                                <input class="submit" type="submit" value="変更">
                            </form>
                        </td>
                        <td>
                            <form>
                                <input type="hidden" name="token" value="<?php print $token; ?>">
                                <input type="hidden" name="salaryclass_id" value="<?php print $value['salaryclass_id']; ?>">
                                <input type="hidden" name="salaryclass" value="<?php print $value['salaryclass']; ?>">
                                <input type="text" name="salaryclass" value="<?php print $value['salaryclass']; ?>">
                                <input class="submit" type="submit" value="変更">
                            </form>
                        </td>
                        <td>
                            <form>
                                <input type="hidden" name="token" value="<?php print $token; ?>">
                                <input type="hidden" name="salaryclass_id" value="<?php print $value['salaryclass_id']; ?>">
                                <input type="hidden" name="salaryclass" value="<?php print $value['salaryclass']; ?>">
                                <input type="text" name="basic_salary" value="<?php print $value['basic_salary']; ?>">
                                <input class="submit" type="submit" value="変更">
                            </form>
                        </td>
                        <td>
                            <form>
                                <input type="hidden" name="token" value="<?php print $token; ?>">
                                <input type="hidden" name="salaryclass_id" value="<?php print $value['salaryclass_id']; ?>">
                                <input type="hidden" name="salaryclass" value="<?php print $value['salaryclass']; ?>">
                                <input type="text" name="overtime_pay" value="<?php print $value['overtime_pay']; ?>">
                                <input class="submit" type="submit" value="変更">
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