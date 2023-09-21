<?php
//取得目前檔案的名稱。透過$_SERVER['PHP_SELF']先取得路徑
$current_file = $_SERVER['PHP_SELF'];
//echo $current_file; //查看目前取得的檔案完整
//然後透過 basename 取得檔案名稱，加上第二個參數".php"，主要是將取得的檔案去掉 .php 這副檔名稱
$current_file = basename($current_file, ".php");
//echo $current_file; //查看目前取得後的檔名

switch ($current_file) {
    case 'setting':
        //為作品列表或完整作品頁
        $index = 1;
        break;
    default:
        //預設索引為 0
        $index = 0;
        break;
}

require_once '../php_god/functions_god.php';
$datas = get_datas($_SESSION['login_god_id']); //讀取已經創建的社區的資料
?>
<div class="jumbotron">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h1 class="text-center"><?php echo $datas['god_name']; ?>管理員首頁</h1>
                <ul class="nav nav-pills nav-justified">
                    <li role="presentation">
                        <a>
                            歡迎 <?php echo $datas['god_name']; ?>
                        </a>
                    </li>
                    <li role="presentation">
                        <a>
                            帳號創建時間為 :
                            <br>
                            <?php echo $datas['create_date'] ?>
                            <br>
                            帳號上次修改時間為 :
                            <br>
                            <?php if ($datas['modify_date'] != '') {
                                echo $datas['modify_date'];
                            } else {
                                echo '無';
                            } ?>
                        </a>
                    </li>
                    <?php if ($index == 0) : ?>
                        <li role="presentation"><a href="setting.php">設定</a></li>
                    <?php endif; ?>


                    <li role="presentation" class="active"><a href="../php_god/logout.php">登出</a></li>
                </ul>

            </div>
        </div>
    </div>
</div>