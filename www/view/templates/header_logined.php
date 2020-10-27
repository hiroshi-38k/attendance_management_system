<header>
    <div>
        <img src="<?php print (IMAGE_PATH . '/logo.jpg'); ?>">
        <p>勤怠管理システム</p>
    </div>
    <nav class="common_menu">
        <ul>
            <li>一般メニュー</li>
        </ul>
        <button onclick="location.href='<?php print(HOME_URL); ?>'">
            勤怠管理
        </button>
        <button onclick="location.href='<?php print(BUSINESSTRIP_URL); ?>'">
            出張申請
        </button>
        <button onclick="location.href='<?php print(HOLIDAY_URL); ?>'">
            年休申請
        </button>
        <button onclick="location.href='<?php print(LOGOUT_URL); ?>'">
            ログアウト
        </button>
    </nav>
    <?php if($employee['employee_type'] === '1' || $employee['employee_type'] === '2'){ ?>
    <nav class="boss_menu">
        <ul>
            <li>上司メニュー</li>
        </ul>
        <button onclick="location.href='<?php print(ATTENDANCE_APPROVAL_URL); ?>'">
            勤怠承認
        </button>
        <button onclick="location.href='<?php print(BUSINESSTRIP_APPROVAL_URL); ?>'">
            出張承認
        </button>
        <button onclick="location.href='<?php print(HOLIDAY_APPROVAL_URL); ?>'">
            年休承認
        </button>
    </nav>
    <?php } ?>
    <?php if($employee['employee_type'] === '0'){ ?>
    <nav class="manager_menu">
        <ul>
            <li>管理メニュー</li>
        </ul>
        <button onclick="location.href='<?php print(BASIC_URL); ?>'">
            基本情報
        </button>
        <!--<button onclick="location.href='<?php print(EMPLOYEE_URL); ?>'">-->
        <!--    従業員情報-->
        <!--</button>-->
        <button onclick="location.href='<?php print(CALENDAR_URL); ?>'">
            休日設定
        </button>
    </nav>
    <?php } ?>
</header>