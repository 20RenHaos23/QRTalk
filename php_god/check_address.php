<?php
//載入資料庫與處理的方法
require_once '../db.php';
require_once 'functions_god.php';

//執行檢查有無使用者的方法。
$check = check_addr($_POST['adr'], $_POST['numS'], $_POST['numE']);

if ($check) {
	//若為true 代表地址填寫違反規定，不給註冊
	echo 'no';
} else {
	//若為 null 或者 false 代表沒有違反規定，可以註冊
	echo 'yes';
}
