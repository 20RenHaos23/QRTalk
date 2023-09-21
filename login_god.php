<?php
//載入 db.php 檔案，讓我們可以透過它連接資料庫，因為此檔案放在 admin 裡，要找到 db.php 就要回上一層 ../php 裡面才能找到
require_once 'db.php';

//如過有 $_SESSION['is_login'] 這個值，以及 $_SESSION['is_login'] 為 true 都代表已登入
if (isset($_SESSION['is_login_god']) && $_SESSION['is_login_god']) {
    //直接轉跳到 index.php 後端首頁
    header("Location: ../admin_god/index_god.php");
}
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <title>QR Talk社區管理員登入</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <!-- XX -->
    <!-- 給行動裝置或平板顯示用，根據裝置寬度而定，初始放大比例 1 -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
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
                    <h1 class="text-center">QR Talk管理員登入</h1>
                    <ul class="nav nav-pills">
                        <li role="presentation" class="active"><a href="login_index.php">登入首頁</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- 網站內容 -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <form class="form-horizontal" id="login_form">
                        <div class="form-group">
                            <label for="community" class="col-sm-2 control-label">社區名稱</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="community" name="community" placeholder="請輸入社區名稱" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-sm-2 control-label">社區密碼</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="password" name="password" placeholder="請輸入密碼" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-default">登入</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- 在表單送出前，檢查確認密碼是否輸入一樣 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        //當文件準備好時，
        $(document).ready(function() {
            //當表單 sumbit 出去的時候
            $("form#login_form").on("submit", function() {
                //使用 ajax 送出 帳密給 verify_user.php
                $.ajax({
                    type: "POST",
                    url: "php_god/verify_god.php", //因為此 login.php 是放在 admin 資料夾內，若要前往 php，就要回上一層 ../ 找到 php 才能進入 verify_user.php
                    data: {
                        un: $("#community").val(), //社區名稱
                        pw: $("#password").val() //社區密碼
                    },
                    dataType: 'html' //設定該網頁回應的會是 html 格式
                }).done(function(data) {
                    //成功的時候
                    console.log(data);
                    if (data == "yes") {
                        
                        //註冊新增成功，轉跳到登入頁面。
                        window.location.href = "admin_god/index_god.php"; //因為目前的 login.php 跟後端的 index.php 首頁在同一資料夾，所以直接叫他就好
                    } else {
                        alert("登入失敗，請確認帳號密碼");
                        //$("#dfs input").attr('value', "123");
                    }
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    //失敗的時候
                    alert("有錯誤產生，請看 console log");
                    console.log(jqXHR.responseText);
                });
                //回傳 false 為了要阻止 from 繼續送出去。由上方ajax處理即可
                return false;
            });
        });
    </script>
    <!-- 頁底 -->
    <?php
    include_once 'footer.php';
    ?>
</body>

</html>