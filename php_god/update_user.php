<?php
//載入資料庫與處理的方法
require_once '../db.php';
require_once 'functions_god.php';

//執行新增使用者的方法，直接把整個 $_POST個別的照順序變數丟給方法。
//$update_result = update_user($_POST['id'], $_POST['name'], $_POST['pass'], $_POST['addr']);
$update_result = update_user($_POST['id'], $_POST['na'], $_POST['p'], $_POST['ad'], $_POST['s_number'], $_POST['e_number'], $_POST['lat'], $_POST['lng']);
//echo "{$update_result}  123";
if ($update_result) {
	//若為true 代表新增成功，印出yes
	echo 'yes';
} else {
	//若為 null 或者 false 代表失敗
	echo 'no';
}
