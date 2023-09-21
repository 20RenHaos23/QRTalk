<?php
//載入資料庫與處理的方法
require_once '../db.php';
require_once 'functions_pe.php';

//執行刪除使用者家人連結的方法。
$del_result = del_family($_POST['id']);

if ($del_result) {
    //若為true 代表刪除成功，印出yes
    echo 'yes';
} else {
    //若為 null 或者 false 代表刪除失敗
    echo 'no';
}
