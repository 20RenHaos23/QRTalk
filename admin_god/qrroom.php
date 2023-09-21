<?php
require_once '../db.php';
require_once '../php_god/functions_god.php';

//$result = get_rooms($_GET['number'], $_GET['floor'], $_GET['god_name']);

$result = get_id_pe_room(base64_decode($_GET['number']), base64_decode($_GET['floor']), $_GET['god_name']);

if (!isset($_SESSION['inside_dis_god_roo']) || !$_SESSION['inside_dis_god_roo']) {
    //直接轉跳到 login.php
    header("Location: ../login_index.php");
}
//print_r($result);
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <title>QR Talk客戶端</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <!-- XX -->
    <!-- 給行動裝置或平板顯示用，根據裝置寬度而定，初始放大比例 1 -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css" />

    <link href="../css/favicon.ico" rel="shortcut icon" />
	<link href="../css/favicon.ico" rel="bookmark" />
</head>

<body>
    <!-- 頁首 -->
    <div class="jumbotron">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h1 class="text-center"><?php echo $_GET['god_name'] ?>社區<?php echo base64_decode($_GET['number']) ?>號<?php echo base64_decode($_GET['floor']) ?>樓</h1>
                </div>
            </div>
        </div>
    </div>
    <!-- 網站內容 -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <?php if ($result) : ?>
                        <?php foreach ($result as &$room) : ?>
                            <a class="btn btn-default btn-lg btn-block" href='https://cs.ee.nchu.edu.tw<?php echo dirname($_SERVER['PHP_SELF']); ?>/qrcoddde.php?id=<?php echo base64_encode($room['id']) ?>' role="button">之<?php echo $room['room'] ?></a>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <h1>
                            <p class="text-center">
                                無資料
                            </p>
                        </h1>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- 頁底 -->
    <?php
    include_once '../footer.php';
    ?>

</body>


</html>
<?php
unset($_SESSION['inside_dis_god_roo']);
//print_r($_SESSION); //查看目前session內容
mark_distance_cod();
?>