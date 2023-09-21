<?php
//載入 db.php 檔案，讓我們可以透過它連接資料庫，另外後台都會用 session 判別暫存資料，所以要請求 db.php 因為該檔案最上方有啟動session_start()。
require_once '../db.php';
require_once '../php_god/functions_god.php';
//print_r($_SESSION); //查看目前session內容
//如過沒有 $_SESSION['is_login'] 這個值，或者 $_SESSION['is_login'] 為 false 都代表沒登入
if (!isset($_SESSION['is_login_god']) || !$_SESSION['is_login_god']) {
  //直接轉跳到 login.php
  header("Location: ../login_index.php");
}
$datas = get_datas($_SESSION['login_god_id']); //讀取社區管理員的資料
$pe_datas = get_id_pe($datas['god_name']); //取得哪些住戶是這個社區的，抓出資料，顯示在首頁

//然後透過 basename 取得檔案名稱，加上第二個參數".php"，主要是將取得的檔案去掉 .php 這副檔名稱
//$current_file = dirname(dirname($_SERVER['PHP_SELF']));
//echo $current_file; //查看目前取得後的檔名
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
  <title>index_god</title>
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
          <table class="table table-hover">
            <tr>
              <th>號</th>
              <th>樓</th>
              <th>室</th>
              <th>帳號</th>
              <th>創建日期</th>
              <th>修改日期</th>
              <th>管理動作</th>
            </tr>
            <?php if ($pe_datas) : ?>
              <?php foreach ($pe_datas as $a_data) : ?>
                <tr>
                  <td><?php echo $a_data['number']; ?></td>
                  <td><?php echo $a_data['floor']; ?></td>
                  <td><?php echo $a_data['room']; ?></td>
                  <td><?php echo $a_data['username']; ?></td>
                  <td><?php echo $a_data['create_date']; ?></td>
                  <td><?php echo $a_data['modify_date']; ?></td>
                  <td>
                    <a href='javascript:void(0);' class='btn btn-danger del_pe' data-id="<?php echo $a_data['id']; ?>">刪除</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else : ?>
              <tr>
                <td colspan="7">無資料</td>
              </tr>
            <?php endif; ?>
          </table>
          <div class="text-center">
            <a href='https://cs.ee.nchu.edu.tw<?php echo dirname(dirname($_SERVER['PHP_SELF'])); ?>/admin_god/gps.php?id=<?php echo base64_encode($datas['id']); ?>' target="_blank">
              <div id="qrcode"></div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- 頁底 -->
  <?php include_once '../footer.php'; ?>
  <!-- 在表單送出前，檢查確認密碼是否輸入一樣 -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="../qr1code/jquery-qrcode-0.18.0.min.js"></script>
  <script>
    $('#qrcode').qrcode({
      text: 'https://cs.ee.nchu.edu.tw<?php echo dirname(dirname($_SERVER['PHP_SELF'])); ?>/admin_god/gps.php?id=<?php echo base64_encode($datas['id']); ?>'
    });
  </script>
  <script>
    //當文件準備好時，
    $(document).ready(function() {

      $("a.del_pe").on("click", function() {
        var c = confirm("你確定要刪除此住戶資料嗎?"),
          this_tr = $(this).parent().parent();
        //console.log($(this).attr("data-id"));


        if (c) {
          $.ajax({
            type: "POST",
            url: "../php_god/del_pe.php",
            data: {
              id: $(this).attr("data-id")
            },
            dataType: 'html' //設定該網頁回應的會是 html 格式
          }).done(function(data) {
            //成功的時候
            console.log(data);
            if (data == "yes") {
              alert("刪除成功");
              //註冊新增成功，轉跳到登入頁面。
              this_tr.fadeOut();
            } else {
              alert("刪除失敗，請與系統人員聯繫" + data);
            }

          }).fail(function(jqXHR, textStatus, errorThrown) {
            //失敗的時候
            alert("有錯誤產生，請看 console log");
            console.log(jqXHR.responseText);
          });
        }

      });

    });
  </script>
</body>

</html>