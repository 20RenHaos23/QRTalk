<?php
//載入 db.php 檔案，讓我們可以透過它連接資料庫，另外後台都會用 session 判別暫存資料，所以要請求 db.php 因為該檔案最上方有啟動session_start()。
require_once '../db.php';
require_once '../php_pe/functions_pe.php';
//print_r($_SESSION); //查看目前session內容

//如過沒有 $_SESSION['is_login'] 這個值，或者 $_SESSION['is_login'] 為 false 都代表沒登入
if (!isset($_SESSION['is_login_pe']) || !$_SESSION['is_login_pe']) {
    //直接轉跳到 login.php
    header("Location: ../login_index.php");
}

$datas = get_datas($_SESSION['login_pe_id']); //讀取社區住戶的資料
//print_r($datas);

$com_datas = get_com_datas($datas['god']); //讀取屬於社區的資料
//print_r($com_datas);

?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <title>編輯</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <!-- XX -->
    <!-- 給行動裝置或平板顯示用，根據裝置寬度而定，初始放大比例 1 -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/style.css" />

    <link href="../css/favicon.ico" rel="shortcut icon" />
	<link href="../css/favicon.ico" rel="bookmark" />
</head>

<body>
    <!-- 頁首 -->
    <?php include_once 'menu.php'; ?>

    <!-- 網站內容 -->
    <div class="content">
        <div class="container">
            <!-- 建立第一個 row 空間，裡面準備放格線系統 -->
            <div class="row">
                <!-- 在 xs 尺寸，佔12格，可參考 http://getbootstrap.com/css/#grid 說明-->
                <div class="col-xs-12">
                    <form class="form-horizontal" id="user">
                        <input type="hidden" id="id" value="<?php echo $datas['id'] ?>">
                        <div class="form-group">
                            <label for="InputName" class="col-sm-2 control-label">email帳號</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" id="InputName" name="InputName" value="<?php echo $datas['username']; ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="InputPassword" class="col-sm-2 control-label">密碼</label>
                            <div class="col-sm-10">
                                <!-- <input type="text" class="form-control" id="InputPassword" placeholder="如果不想要更改密碼則不用填寫，將沿用上次密碼">-->
                                <div class="input-group" id="show_hide_password">
                                    <input type="password" class="form-control" id="InputPassword" name="InputPassword" placeholder="如果不想要更改密碼則不用填寫，將沿用上次密碼">
                                    <div class="input-group-addon" id="eye">
                                        <a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="InputAddress" class="col-sm-2 control-label">住家地址</label>
                            <div class="col-sm-10">
                                <p class="form-control-static"><?php echo $com_datas['address']; ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">幾號幾樓之幾</label>
                            <div class="col-sm-2">
                                <input type="number" class="form-control" id="Number" name="Number" value="<?php echo $datas['number']; ?>" min="<?php echo $com_datas['start_number']; ?>" max="<?php echo $com_datas['end_number']; ?>" required>
                            </div>
                            <label for="Number" class="col-sm-1 control-label">號</label>
                            <div class="col-sm-2">
                                <input type="number" class="form-control" id="Floor" name="Floor" value="<?php echo $datas['floor']; ?>" min="-10" max="127" required>
                            </div>
                            <label for="Floor" class="col-sm-1 control-label">樓</label>
                            <label class="col-sm-1 control-label">之</label>
                            <div class="col-sm-2">
                                <input type="number" class="form-control" id="Room" name="Room" placeholder="可不填" value="<?php echo $datas['room']; ?>" min="0" max="255">
                            </div>
                            <label for="Room" class="col-sm-1 control-label">室</label>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-default">確認</button>
                                <button type="button" class="btn btn-default" onclick="history.back()">取消</button>
                            </div>
                        </div>
                    </form>
                    <div id="map" style="min-width: 300px; min-height: 300px;"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- 頁底 -->
    <?php include_once '../footer.php'; ?>

    <!-- 在表單送出前，檢查確認密碼是否輸入一樣 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=秘密&callback=initMap" async defer></script>

    <script>
        var map, geocoder;

        function initMap() {
            var position = {
                lat: Number(<?php echo $com_datas['latitude']; ?>),
                lng: Number(<?php echo $com_datas['longitude']; ?>)
            };
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 17,
                center: position
            });
            var marker = new google.maps.Marker({
                position: position,
                map: map,
                animation: google.maps.Animation.BOUNCE
            });
        }
    </script>
    <script>
        $("#eye").on('click', function(event) {
            event.preventDefault();
            if ($('#show_hide_password input').attr("type") == "text") {
                $('#show_hide_password input').attr('type', 'password');
                $('#show_hide_password i').addClass("fa-eye-slash");
                $('#show_hide_password i').removeClass("fa-eye");
            } else if ($('#show_hide_password input').attr("type") == "password") {
                $('#show_hide_password input').attr('type', 'text');
                $('#show_hide_password i').removeClass("fa-eye-slash");
                $('#show_hide_password i').addClass("fa-eye");
            }
        });
        //當文件準備好時，
        $(document).ready(function() {
            //當帳號的input keyup的時候，透過ajax檢查
            $("#InputName").on("keyup", function() {
                //取得輸入的值
                var keyin_value = $(this).val();
                //當keyup的時候，裡面的值不是空字串的話，就檢查。
                if (keyin_value != '' && keyin_value != '<?php echo $datas['username']; ?>') {
                    //$.ajax 是 jQuery 的方法，裡面使用的是物件。
                    $.ajax({
                        type: "POST", //表單傳送的方式 同 form 的 method 屬性
                        url: "../php_pe/check_username.php", //目標給哪個檔案 同 form 的 action 屬性
                        data: { //為要傳過去的資料，使用物件方式呈現，因為變數key值為英文的關係，所以用物件方式送。ex: {name : "輸入的名字", password : "輸入的密碼"}
                            n: $(this).val() //代表要傳一個 n 變數值為，username 文字方塊裡的值
                        },
                        dataType: 'html' //設定該網頁回應的會是 html 格式
                    }).done(function(data) {
                        //成功的時候
                        //console.log(data); //透過 console 看回傳的結果
                        if (data == "yes") {
                            //如果為 yes username 文字方塊的復元素先移除 has-error 類別，再加入 has-success 類別
                            $("#InputName").parent().parent().removeClass("has-error").addClass("has-success");
                            //把註冊按鈕 disabled 類別移除，讓他可以按註冊
                            $("form#user button[type='submit']").removeClass('disabled');
                            $("form#user button[type='submit']").attr('disabled', false);
                        } else {
                            alert("帳號有重複，不可以註冊");
                            $("#InputName").parent().parent().removeClass("has-success").addClass("has-error");
                            //把註冊按鈕加上 disabled 不能按，在bootstrap裡 disabled 類別可以讓該元素無法操作
                            $("form#user button[type='submit']").addClass('disabled');
                            $("form#user button[type='submit']").attr('disabled', true);
                        }
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        //失敗的時候
                        alert("有錯誤產生，請看 console log");
                        console.log(jqXHR.responseText);
                    });
                } else {
                    //若為空字串，就移除 has-error 跟 has-success 類別
                    $("#InputName").parent().parent().removeClass("has-success").removeClass("has-error");
                    $("form#user button[type='submit']").removeClass('disabled');
                    $("form#user button[type='submit']").attr('disabled', false);
                }
            });
            //當表單 sumbit 出去的時候
            $("form#user").on("submit", function() {
                if ($("#InputName").val() == '') {
                    alert("請填妥email帳號");
                } else {
                    //使用 ajax 送出 帳密給 verify_user.php
                    $.ajax({
                        type: "POST",
                        url: "../php_pe/update_user.php", //因為此 login.php 是放在 admin 資料夾內，若要前往 php，就要回上一層 ../ 找到 php 才能進入 verify_user.php
                        data: {
                            id: $("#id").val(), //id
                            na: $("#InputName").val(), //使用者帳號
                            p: $("#InputPassword").val(), //使用者密碼
                            nb: $("#Number").val(), //號
                            fl: $("#Floor").val(), //樓
                            ro: $("#Room").val() //之幾室
                        },
                        dataType: 'html' //設定該網頁回應的會是 html 格式
                    }).done(function(data) {
                        //成功的時候
                        console.log(data);
                        if (data == "yes") {
                            //註冊新增成功，轉跳到登入頁面。
                            alert("更新成功!");
                            window.location.href = "index_pe.php"; //因為目前的 login.php 跟後端的 index.php 首頁在同一資料夾，所以直接叫他就好
                        } else {
                            alert("更新失敗" + data);
                        }
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        //失敗的時候
                        alert("有錯誤產生，請看 console log");
                        console.log(jqXHR.responseText);
                    });
                }
                //回傳 false 為了要阻止 from 繼續送出去。由上方ajax處理即可
                return false;
            });
        });
    </script>
</body>

</html>