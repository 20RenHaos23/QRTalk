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

$datas = get_datas($_SESSION['login_vi_id']); //讀取使用者的資料
//print_r($datas);
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
                    <form id="user">
                        <input type="hidden" id="id" value="<?php echo $datas['id'] ?>">
                        <div class="form-group">
                            <label for="InputName" class="col-sm-2 control-label">email帳號</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" id="InputName" name="username" placeholder="請輸入email帳號" value="<?php echo $datas['username']; ?>" required>
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
                                <div class="input-group">
                                    <input type="text" class="form-control" id="InputAddress" placeholder="台中市南區興大路145號" value="<?php echo $datas['address']; ?>" required>
                                    <span class="input-group-btn">
                                        <button onclick="getaddr()" class="btn btn-default" type="button">轉換成經緯度</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="lat" class="col-sm-2 control-label">緯度</label>
                            <div class="col-sm-10">
                                <input type="number" step="0.000000000000001" class="form-control" id="lat" name="lat" placeholder="住家地址的緯度(請按'轉換成經緯度'按鈕，將會幫您自動轉換)" value="<?php echo $datas['latitude']; ?>" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="lng" class="col-sm-2 control-label">經度</label>
                            <div class="col-sm-10">
                                <input type="number" step="0.000000000000001" class="form-control" id="lng" name="lng" placeholder="住家地址的經度(請按'轉換成經緯度'按鈕，將會幫您自動轉換)" value="<?php echo $datas['longitude']; ?>" disabled>
                            </div>
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
            //var lat1 = Number(document.getElementById("lat").value);
            //console.log(Number(lat1));
            var position = {
                lat: Number(document.getElementById("lat").value),
                lng: Number(document.getElementById("lng").value)
            };
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 18,
                center: position
            });
            var marker = new google.maps.Marker({
                position: position,
                map: map,
                animation: google.maps.Animation.BOUNCE,
                draggable: true
            });
            google.maps.event.addListener(marker, 'dragend', function(event) {
                placeMarker(map, event.latLng);
            });

            function placeMarker(map, location) {
                addlatlng(location.lat(), location.lng());
            };
        }

        function getaddr() {
            var name = document.getElementById("InputAddress").value;
            console.log(name);
            if (name != '') {
                //console.log(name);
                Map(name);
            } else {
                alert("沒有輸入地址");
                //console.log("沒有輸入地址");
            }
        }

        function addlatlng(lat, lng) {
            document.getElementById("lat").value = Number(lat);
            document.getElementById("lng").value = Number(lng);
        }

        function Map(address) {
            var lat, lng;
            geocoder = new google.maps.Geocoder();
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 17
            });
            geocoder.geocode({
                address: address
            }, function(results, status) {
                if (status == "OK") {
                    map.setCenter(results[0].geometry.location);
                    var marker = new google.maps.Marker({
                        //zoom: 15,
                        map: map,
                        position: results[0].geometry.location,
                        animation: google.maps.Animation.BOUNCE,
                        draggable: true
                    });
                    //console.log(status);
                    //console.log(address);
                    lat = results[0].geometry.location.lat(); //緯度
                    lng = results[0].geometry.location.lng(); //經度
                    //console.log(lat); //緯度
                    //console.log(lng); //經度
                    addlatlng(lat, lng);
                    google.maps.event.addListener(marker, 'dragend', function(event) {
                        placeMarker(map, event.latLng);
                    });

                    function placeMarker(map, location) {
                        addlatlng(location.lat(), location.lng());
                    };

                } else {
                    console.log(status);
                }
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
                        url: "../php_vi/check_username.php", //目標給哪個檔案 同 form 的 action 屬性
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
                        url: "../php_vi/update_user.php", //因為此 login.php 是放在 admin 資料夾內，若要前往 php，就要回上一層 ../ 找到 php 才能進入 verify_user.php
                        data: {
                            id: $("#id").val(), //id
                            na: $("#InputName").val(), //使用者帳號
                            p: $("#InputPassword").val(), //使用者密碼
                            addr: $("#InputAddress").val(), //使用者地址
                            lat: $("#lat").val(), //使用者緯度
                            lng: $("#lng").val() //使用者經度
                        },
                        dataType: 'html' //設定該網頁回應的會是 html 格式
                    }).done(function(data) {
                        //成功的時候
                        console.log(data);
                        if (data == "yes") {
                            //註冊新增成功，轉跳到登入頁面。
                            alert("更新成功!");
                            window.location.href = "index_vi.php"; //因為目前的 login.php 跟後端的 index.php 首頁在同一資料夾，所以直接叫他就好
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