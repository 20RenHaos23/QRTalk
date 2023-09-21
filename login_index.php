<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <title>QR Talk登入首頁</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <!-- XX -->
    <!-- 給行動裝置或平板顯示用，根據裝置寬度而定，初始放大比例 1 -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css" />

    <link href="css/favicon.ico" rel="shortcut icon" />
	<link href="css/favicon.ico" rel="bookmark" />
</head>

<body>
    <!-- 頁首 -->
    <div class="jumbotron">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h1 class="text-center">QR Talk登入首頁</h1>
                </div>
            </div>
        </div>
    </div>
    <!-- 網站內容 -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-4">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <p class="text-center panel-title">
                                社區住戶
                            </p>
                            <!-- <h3 class="panel-title">有管理員的社區大樓</h3>-->
                        </div>
                        <div class="panel-body">
                            <p class="text-center">
                                住在社區且有大樓管理員的住戶請在此註冊或登入
                            </p>
                            <div class="btn-group btn-group-justified" role="group" aria-label="...">
                                <!-- <button type="button" class="btn btn-primary btn-lg btn-block">註冊</button>-->
                                <a href="login_pe.php" class="btn btn-info btn-lg btn-block" role="button">登入</a>
                                <a href="register/search_god.php" class="btn btn-default btn-lg btn-block" role="button">註冊</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-4">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <p class="text-center panel-title">
                                社區大樓管理員
                            </p>
                            <!-- <h3 class="panel-title">有管理員的社區大樓</h3>-->
                        </div>
                        <div class="panel-body">
                            <p class="text-center">
                                為社區的大樓管理員請在此註冊或登入
                            </p>
                            <div class="btn-group btn-group-justified" role="group" aria-label="...">
                                <!-- <button type="button" class="btn btn-primary btn-lg btn-block">註冊</button>-->
                                <a href="login_god.php" class="btn btn-success btn-lg btn-block" role="button">登入</a>
                                <a href="register/register_god.php" class="btn btn-default btn-lg btn-block" role="button">註冊</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-4">
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <p class="text-center panel-title">
                                透天or無管理員住戶
                            </p>
                            <!-- <h3 class="panel-title">透天or沒有管理員的社區大樓</h3>-->
                        </div>
                        <div class="panel-body">
                            <p class="text-center">
                                為透天或者沒有社區管理員的住戶請在此註冊或登入
                            </p>
                            <div class="btn-group btn-group-justified" role="group" aria-label="...">
                                <!-- <button type="button" class="btn btn-primary btn-lg btn-block">註冊</button>-->
                                <a href="login_vi.php" class="btn btn-warning btn-lg btn-block" role="button">登入</a>
                                <a href="register/register_vi.php" class="btn btn-default btn-lg btn-block" role="button">註冊</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    include_once 'footer.php';
    ?>
</body>

</html>