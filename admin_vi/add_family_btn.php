<?php
//載入 db.php 檔案，讓我們可以透過它連接資料庫，另外後台都會用 session 判別暫存資料，所以要請求 db.php 因為該檔案最上方有啟動session_start()。
require_once '../db.php';
require_once '../php_vi/functions_vi.php';
//print_r($_SESSION); //查看目前session內容

//如過沒有 $_SESSION['is_login'] 這個值，或者 $_SESSION['is_login'] 為 false 都代表沒登入
if (!isset($_SESSION['is_login_vi']) || !$_SESSION['is_login_vi']) {
    //直接轉跳到 login.php
    header("Location: ../login_index.php");
}

//$datas = get_family();
//print_r($datas);

?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <title>新增聯絡人</title>
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
    <?php include_once 'menu.php'; ?>

    <!-- 網站內容 -->
    <div class="content">
        <div class="container">
            <!-- 建立第一個 row 空間，裡面準備放格線系統 -->

            <div class="row">
                <!-- 在 xs 尺寸，佔12格，可參考 http://getbootstrap.com/css/#grid 說明-->
                <div class="col-xs-12">
                    <form id="familylink">
                        <div class="form-group">
                            <label for="InputName">名稱</label>
                            <input type="text" class="form-control" id="InputName" placeholder="請輸入聯絡人名稱or暱稱" required>
                        </div>
                        <div class="form-group">
                            <label for="category">分類</label>
                            <select id="category" class="form-control">
                                <option value="手機" selected>手機</option>
                                <option value="市話">市話</option>
                                <option value="網頁">網頁</option>
                            </select>
                        </div>
                        <div class="form-group" id="InputLink_form">
                            <input type="tel" class="form-control" id="InputLink" name="InputLink" placeholder="0912345678" pattern="[0-9]{4}[0-9]{3}[0-9]{3}" required>
                        </div>
                        <!-- <div class="form-group">
              <label for="InputLink">連結</label>
              <input type="text" class="form-control" id="InputLink" placeholder="請輸入手機or市話or網路連結">
            </div>-->
                        <button type="submit" class="btn btn-default">確認</button>
                        <button type="button" class="btn btn-default" onclick="history.back()">取消</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 頁底 -->
    <?php include_once '../footer.php'; ?>

    <!-- 在表單送出前，檢查確認 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.getElementById("category").onchange = function() {
            document.getElementById("InputLink").value = "";
            if (document.getElementById("category").value == "手機") {
                $('#InputLink_form input').attr('type', 'tel');
                $('#InputLink_form input').attr('pattern', '[0-9]{4}[0-9]{3}[0-9]{3}');
                $('#InputLink_form input').attr('placeholder', '0912345678');
                //console.log($("#InputLink").val());
                //console.log($("#category").val());
            } else if (document.getElementById("category").value == "市話") {
                $('#InputLink_form input').attr('type', 'tel');
                $('#InputLink_form input').attr('pattern', '[0-9]{2}[0-9]{3,4}[0-9]{4}');
                $('#InputLink_form input').attr('placeholder', '04123(4)5678');
                //console.log($("#InputLink").val());
                //console.log($("#category").val());
            } else {
                $('#InputLink_form input').attr('type', 'url');
                $('#InputLink_form input').removeAttr('pattern');
                $('#InputLink_form input').attr('placeholder', 'http://www.ee.nchu.edu.tw/');
                //console.log($("#InputLink").val());
                //console.log($("#category").val());
            }
        };
    </script>
    <script>
        //當文件準備好時，
        $(document).ready(function() {
            //檢查帳號有無重複
            //當帳號的input keyup的時候，透過ajax檢查
            $("#InputName").on("keyup", function() {
                //取得輸入的值
                var keyin_value = $(this).val();
                if (keyin_value != '') {
                    //$.ajax 是 jQuery 的方法，裡面使用的是物件。
                    $.ajax({
                        type: "POST", //表單傳送的方式 同 form 的 method 屬性
                        url: "../php_vi/check_linkname.php", //目標給哪個檔案 同 form 的 action 屬性
                        data: { //為要傳過去的資料，使用物件方式呈現，因為變數key值為英文的關係，所以用物件方式送。ex: {name : "輸入的名字", password : "輸入的密碼"}
                            n: $(this).val() //代表要傳一個 n 變數值為，username 文字方塊裡的值
                        },
                        dataType: 'html' //設定該網頁回應的會是 html 格式
                    }).done(function(data) {
                        //成功的時候
                        //console.log(data); //透過 console 看回傳的結果
                        if (data == "yes") {
                            //如果為 yes username 文字方塊的復元素先移除 has-error 類別，再加入 has-success 類別
                            $("#InputName").parent().removeClass("has-error").addClass("has-success");
                            //把註冊按鈕 disabled 類別移除，讓他可以按註冊
                            $("form#familylink button[type='submit']").removeClass('disabled');
                            $("form#familylink button[type='submit']").attr('disabled', false);

                        } else {
                            alert("帳號有重複，不可以註冊");
                            $("#InputName").parent().removeClass("has-success").addClass("has-error");
                            //把註冊按鈕加上 disabled 不能按，在bootstrap裡 disabled 類別可以讓該元素無法操作
                            $("form#familylink button[type='submit']").addClass('disabled');
                            $("form#familylink button[type='submit']").attr('disabled', true);
                        }

                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        //失敗的時候
                        alert("有錯誤產生，請看 console log");
                        console.log(jqXHR.responseText);
                    });
                } else {
                    //若為空字串，就移除 has-error 跟 has-success 類別
                    $("#InputName").parent().removeClass("has-success").removeClass("has-error");
                    $("form#family button[type='submit']").removeClass('disabled');
                    $("form#family button[type='submit']").attr('disabled', false);
                }

            });

            //當表單 sumbit 出去的時候
            $("form#familylink").on("submit", function() {
                if ($("#InputName").val() == '' || $("#InputLink").val() == '') {
                    alert("請填妥名稱或連結");
                } else {
                    //使用 ajax 送出 帳密給 verify_user.php
                    $.ajax({
                        type: "POST",
                        url: "../php_vi/add_family.php", //因為此 login.php 是放在 admin 資料夾內，若要前往 php，就要回上一層 ../ 找到 php 才能進入 verify_user.php
                        data: {
                            name: $("#InputName").val(), //使用者帳號
                            cate: $("#category").val(),
                            link: $("#InputLink").val() //使用者密碼
                        },
                        dataType: 'html' //設定該網頁回應的會是 html 格式
                    }).done(function(data) {
                        //成功的時候
                        console.log(data);
                        if (data == "yes") {
                            //註冊新增成功，轉跳到登入頁面。
                            alert("新增成功!");
                            window.location.href = "index_vi.php"; //因為目前的 login.php 跟後端的 index.php 首頁在同一資料夾，所以直接叫他就好
                        } else {
                            alert("新增失敗" + data);
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