<?php
require_once 'db.php';
require_once 'php_vi/functions_vi.php';

//$datas = get_datas(base64_decode($_GET['id'])); //取得使用者裡面的聯絡人資料，顯示在使用者首頁
$datas = get_datas($_GET['id']); //取得使用者裡面的聯絡人資料，顯示在使用者首頁
//print_r($datas);

?>
<!DOCTYPE html>
<html lang="zh-TW">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    if (navigator.geolocation) {
        // 支援GPS地理定位
        navigator.geolocation.getCurrentPosition(geoYes, geoNo);
    } else {
        alert("目前GPS無法使用");
    }

    function distanceX(lat1, lon1, lat2, lon2) {
        R = 6371; // km (change this constant to get miles)
        dLat = (lat2 - lat1) * Math.PI / 180;
        dLon = (lon2 - lon1) * Math.PI / 180;
        a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * Math.sin(dLon / 2) * Math.sin(dLon / 2);
        c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        d = R * c;

        //if (d > 1) return d + " km";
        //else if (d <= 1) return d * 1000 + "m";
        return d;
    }

    function distance(lat1, lon1, lat2, lon2, unit) {
        if ((lat1 == lat2) && (lon1 == lon2)) {
            return 0;
        } else {
            var radlat1 = Math.PI * lat1 / 180;
            var radlat2 = Math.PI * lat2 / 180;
            var theta = lon1 - lon2;
            var radtheta = Math.PI * theta / 180;
            var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
            if (dist > 1) {
                dist = 1;
            }
            dist = Math.acos(dist);
            dist = dist * 180 / Math.PI;
            dist = dist * 60 * 1.1515;
            if (unit == "K") {
                dist = dist * 1.609344
            }
            if (unit == "N") {
                dist = dist * 0.8684
            }
            return dist;
        }
    }

    function tevt(diss) {
        if ($('#distance').value != 0) {
            //console.log(diss);

            $.ajax({
                type: "POST", //表單傳送的方式 同 form 的 method 屬性
                url: "1122.php", //目標給哪個檔案 同 form 的 action 屬性
                data: { //為要傳過去的資料，使用物件方式呈現，因為變數key值為英文的關係，所以用物件方式送。ex: {name : "輸入的名字", password : "輸入的密碼"}
                    na: diss //代表要傳一個 n 變數值為，username 文字方塊裡的值
                },
                dataType: 'html' //設定該網頁回應的會是 html 格式
            }).done(function(data) {
                if (data == "yes") {
                    //console.log(data);
                    window.location.href = "login_index.php";
                } else {
                    //console.log(data);
                    alert("你離大門口太遠了！");
                    window.location.href = "login_index.php";
                }

            }).fail(function(jqXHR, textStatus, errorThrown) {
                //失敗的時候
                alert("有錯誤產生，請看 console log");
                console.log(jqXHR.responseText);
            });
        }
    }

    function geoYes(evt) {
        latitude = evt.coords.latitude;
        longitude = evt.coords.longitude;
        accuracy = evt.coords.accuracy;

        longitude1 = 120.67495328318307; //經度
        latitude1 = 24.12401936280665; //緯度
        longitude2 = <?php echo $datas['longitude'] ?>; //經度
        latitude2 = <?php echo $datas['latitude'] ?>; //緯度

        diss = distance(latitude, longitude, latitude2, longitude2, "K");
        document.getElementById("distance").value = diss;

        //dissX = distanceX(latitude1, longitude1, latitude2, longitude2);

        tevt(diss);
    }

    function geoNo(evt) {
        console.log(evt);
        alert("GPS取得失敗,將導回到首頁");
        window.location.href = "login_index.php";
    }
</script>


<head>
    <title>QR Talk GPS</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <!-- 人豪 -->
    <!-- 給行動裝置或平板顯示用，根據裝置寬度而定，初始放大比例 1 -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css" />
</head>

<body>
    <!-- 頁首 -->
    <div class="jumbotron">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h1 class="text-center">QR Talk GPS</h1>
                </div>
            </div>
        </div>
    </div>
    <!-- 網站內容 -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <form>
                        <input type="hidden" id="distance">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
    include_once 'footer.php';
    ?>

</body>

</html>