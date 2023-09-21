<?php
//載入 db.php 檔案，讓我們可以透過它連接資料庫
//require_once 'php/db.php';
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
	<title>QR Talk註冊</title>
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
	<div class="jumbotron">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<h1 class="text-center">QR Talk註冊(搜尋社區大樓名稱)</h1>
					<ul class="nav nav-pills">
						<li role="presentation" class="active"><a href="../login_index.php">登入首頁</a></li>
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
					<form class="form-horizontal" id="register_form">
						<div class="form-group">
							<label for="community" class="col-sm-2 control-label">社區名稱</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="community" name="community" placeholder="請輸入社區名稱" required>
							</div>
						</div>
						<div class="form-group">
							<label for="password" class="col-sm-2 control-label">社區密碼</label>
							<div class="col-sm-10">
								<!-- <input type="password" class="form-control" id="password" name="password" placeholder="請輸入密碼" required> -->
								<div class="input-group" id="show_hide_password">
									<input type="password" class="form-control" id="password" name="password" placeholder="請輸入密碼" required>
									<div class="input-group-addon" id="eye">
										<a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button type="submit" class="btn btn-default">搜尋</button>
								<a class="btn btn-default" href="../login_index.php" role="button">取消</a>
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
		//$("#show_hide_password a").on('click', function(event) {
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
			//檢查帳號有無重複
			//當表單 sumbit 出去的時候
			$("form#register_form").on("submit", function() {
				//若當密碼正確無誤，就使用 ajax 送出
				$.ajax({
					type: "POST",
					url: "../php_pe/verify_god.php",
					data: {
						un: $("#community").val(), //社區名稱
						pw: $("#password").val() //社區密碼						
					},
					dataType: 'html' //設定該網頁回應的會是 html 格式
				}).done(function(data) {
					//成功的時候
					console.log(data);
					if (data == "yes") {
						alert("有此社區!將自動前往社區用戶註冊頁。");
						//註冊新增成功，轉跳到登入頁面。
						window.location.href = "register_pe.php";
					} else {
						alert("無此社區或密碼錯誤，請確認社區名稱或密碼");
					}
				}).fail(function(jqXHR, textStatus, errorThrown) {
					//失敗的時候
					alert("有錯誤產生，請看 console log");
					console.log(jqXHR.responseText);
				});
				//一樣要回傳 false 阻止 from 繼續把資料送出去。因為會交由上方的 ajax 非同步處理註冊的動作
				return false;
			});
		});
	</script>
	<!-- 頁底 -->
	<?php
	include_once '../footer.php';
	?>
</body>

</html>