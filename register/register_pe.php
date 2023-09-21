<?php
//載入 db.php 檔案，讓我們可以透過它連接資料庫，另外後台都會用 session 判別暫存資料，所以要請求 db.php 因為該檔案最上方有啟動session_start()。
require_once '../db.php';
require_once '../php_pe/functions_pe.php';

//如過沒有 $_SESSION['is_login'] 這個值，或者 $_SESSION['is_login'] 為 false 都代表沒登入
if (!isset($_SESSION['is_login_pe_god']) || !$_SESSION['is_login_pe_god']) {
	//直接轉跳到 login.php
	header("Location: ../login/login_index.php");
}

$com_datas = get_com_datas($_SESSION['login_pe_god_id']);
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
					<h1 class="text-center"><?php echo $com_datas['god_name']; ?>社區住戶註冊頁面</h1>
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
							<label for="username" class="col-sm-2 control-label">email帳號</label>
							<div class="col-sm-10">
								<input type="email" class="form-control" id="username" name="username" placeholder="請輸入email帳號" required>
							</div>
						</div>
						<div class="form-group">
							<label for="password" class="col-sm-2 control-label">密碼</label>
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
							<label for="confirm_password" class="col-sm-2 control-label">確認密碼</label>
							<div class="col-sm-10">
								<input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="請確認密碼" required>
							</div>
						</div>
						<div class="form-group">
							<label for="community" class="col-sm-2 control-label">住家地址</label>
							<div class="col-sm-10">
								<p class="form-control-static"><?php echo $com_datas['address']; ?></p>
							</div>
						</div>
						<div class="alert alert-info col-sm-10 col-sm-offset-2" role="alert">如果您的住家地址的<strong><a href="#Number" class="alert-link">x號y樓</a></strong>
							只有一戶，沒有再分<strong>'x號y樓之一' 'x號y樓之二'</strong>等，則
							<strong><a href="#Room" class="alert-link">'室'</a></strong>則填寫<strong>0</strong>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">幾號幾樓之幾</label>
							<div class="col-sm-2">
								<input type="number" class="form-control" id="Number" name="Number" placeholder="幾" min="<?php echo $com_datas['start_number']; ?>" max="<?php echo $com_datas['end_number']; ?>" required>
							</div>
							<label for="Number" class="col-sm-1 control-label">號</label>
							<div class="col-sm-2">
								<input type="number" class="form-control" id="Floor" name="Floor" placeholder="幾" min="1" max="127" required>
							</div>
							<label for="Floor" class="col-sm-1 control-label">樓 之</label>

							<div class="col-sm-2">
								<input type="number" class="form-control" id="Room" name="Room" placeholder="可不填" min="0" max="255" required>
							</div>
							<label for="Room" class="col-sm-1 control-label">室</label>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button type="submit" class="btn btn-default">註冊</button>
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
			//當帳號的input keyup的時候，透過ajax檢查
			$("#username").on("keyup", function() {
				//取得輸入的值
				var keyin_value = $(this).val();
				//當keyup的時候，裡面的值不是空字串的話，就檢查。
				if (keyin_value != '') {
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
							$("#username").parent().parent().removeClass("has-error").addClass("has-success");
							//把註冊按鈕 disabled 類別移除，讓他可以按註冊
							$("form#register_form button[type='submit']").removeClass('disabled');
							$("form#register_form button[type='submit']").attr('disabled', false);
						} else {
							alert("帳號名稱有重複，不可以註冊");
							$("#username").parent().parent().removeClass("has-success").addClass("has-error");
							//把註冊按鈕加上 disabled 不能按，在bootstrap裡 disabled 類別可以讓該元素無法操作
							$("form#register_form button[type='submit']").addClass('disabled');
							$("form#register_form button[type='submit']").attr('disabled', true);
						}
					}).fail(function(jqXHR, textStatus, errorThrown) {
						//失敗的時候
						alert("有錯誤產生，請看 console log");
						console.log(jqXHR.responseText);
					});
				} else {
					//若為空字串，就移除 has-error 跟 has-success 類別
					$("#username").parent().parent().removeClass("has-success").removeClass("has-error");
					$("form#register_form button[type='submit']").removeClass('disabled');
					$("form#register_form button[type='submit']").attr('disabled', false);
				}
			});
			//############################################################################################################


			


			//############################################################################################################
			//當表單 sumbit 出去的時候
			$("form#register_form").on("submit", function() {
				//如果密碼與驗證密碼不一樣
				if ($("#password").val() != $("#confirm_password").val()) {
					//把 input 的父標籤 加入 has-error，讓人知道哪個地方有錯誤，作為提醒
					//為何要在父類別加has-error，請看 http://getbootstrap.com/css/#forms-control-validation
					$("#password").parent().parent().addClass("has-error");
					$("#confirm_password").parent().parent().addClass("has-error");
					//若密碼都不一樣就警告。
					alert("兩次密碼輸入不一樣，請確認");
				} else {
					//若當密碼正確無誤，就使用 ajax 送出
					$.ajax({
						type: "POST",
						url: "../php_pe/add_user.php",
						data: {
							un: $("#username").val(), //使用者帳號
							pw: $("#password").val(), //使用者密碼
							nb: $("#Number").val(), //號
							fl: $("#Floor").val(), //樓
							ro: $("#Room").val() //之幾室
						},
						dataType: 'html' //設定該網頁回應的會是 html 格式
					}).done(function(data) {
						//成功的時候
						console.log(data);
						if (data == "yes") {
							alert("註冊成功，將自動前往登入頁。");
							//註冊新增成功，轉跳到登入頁面。
							window.location.href = "../login_pe.php";
						} else {
							alert("註冊失敗，請與系統人員聯繫");
						}
					}).fail(function(jqXHR, textStatus, errorThrown) {
						//失敗的時候
						alert("有錯誤產生，請看 console log");
						console.log(jqXHR.responseText);
					});
				}
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

<?php
unset($_SESSION['is_login_pe_god']);
//print_r($_SESSION); //查看目前session內容
?>