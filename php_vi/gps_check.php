<?php
//載入資料庫與處理的方法
require_once '../db.php';
require_once 'functions_vi.php';

if ($_POST['n'] < 0.05) {
    mark_distance();
    //若為true 代表有使用者增加的家人連結名稱重複
    echo 'yes';
} else {
    dismark_distance();
    //若為 null 或者 false 代表沒有重複，可以新增
    echo 'no';
}
