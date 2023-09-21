<?php
//啟動 session ，這樣才能夠取用 $_SESSION['link'] 的連線，做為資料庫的連線用
@session_start();

/**
 * 檢查資料庫有無該使用者名稱，登入的時候用的
 */
function verify_user($community, $username, $password)
{
  //宣告要回傳的結果
  $result = null;
  //先把密碼用md5加密
  //$password = md5($password);
  //將查詢語法當成字串，記錄在$sql變數中
  $sql = "SELECT * FROM `pe` WHERE `username` = '{$username}' AND `password` = '{$password}' AND `god` = '{$community}'";
  //用 mysqli_query 方法取執行請求（也就是sql語法），請求後的結果存在 $query 變數中
  $query = mysqli_query($_SESSION['link'], $sql);
  //如果請求成功
  if ($query) {
    //使用 mysqli_num_rows 回傳 $query 請求的結果數量有幾筆，為一筆代表找到會員且密碼正確。
    if (mysqli_num_rows($query) == 1) {
      //取得使用者資料
      $user = mysqli_fetch_assoc($query);
      //在session李設定 is_login 並給 true 值，代表已經登入
      $_SESSION['is_login_pe'] = TRUE;
      //紀錄登入者的id，之後若要隨時取得使用者資料時，可以透過 $_SESSION['login_user_id'] 取用
      $_SESSION['login_pe_id'] = $user['id'];
      //回傳的 $result 就給 true 代表驗證成功
      $result = true;
    }
  } else {
    echo "{$sql} 語法執行失敗，錯誤訊息：" . mysqli_error($_SESSION['link']);
  }
  //回傳結果
  return $result;
}


/**
 * 檢查資料庫有沒有這個社區，有才可以註冊社區住戶，註冊的時候用的
 */
function verify_god($username, $password)
{
  //宣告要回傳的結果
  $result = null;
  //先把密碼用md5加密
  //$password = md5($password);
  //將查詢語法當成字串，記錄在$sql變數中
  $sql = "SELECT * FROM `god` WHERE `god_name` = '{$username}' AND `password` = '{$password}'";
  //用 mysqli_query 方法取執行請求（也就是sql語法），請求後的結果存在 $query 變數中
  $query = mysqli_query($_SESSION['link'], $sql);
  //如果請求成功
  if ($query) {
    //使用 mysqli_num_rows 回傳 $query 請求的結果數量有幾筆，為一筆代表找到會員且密碼正確。
    if (mysqli_num_rows($query) == 1) {
      //取得使用者資料
      $user = mysqli_fetch_assoc($query);
      //在session李設定 is_login 並給 true 值，代表已經登入
      $_SESSION['is_login_pe_god'] = TRUE;
      //紀錄登入者的id，之後若要隨時取得使用者資料時，可以透過 $_SESSION['login_user_id'] 取用
      $_SESSION['login_pe_god_id'] = $user['god_name'];
      //回傳的 $result 就給 true 代表驗證成功
      $result = true;
    }
  } else {
    echo "{$sql} 語法執行失敗，錯誤訊息：" . mysqli_error($_SESSION['link']);
  }
  //回傳結果
  return $result;
}


/**
 * 檢查資料庫此住戶名稱有無已經先被註冊了，創建的時候用的
 */
function check_user_name($username)
{
  //宣告要回傳的結果
  $result = null;
  $god = $_SESSION['login_pe_god_id'];
  //將查詢語法當成字串，記錄在$sql變數中
  $sql = "SELECT * FROM `pe` WHERE `username` = '{$username}' AND `god` = '{$god}';";
  //用 mysqli_query 方法取執行請求（也就是sql語法），請求後的結果存在 $query 變數中
  $query = mysqli_query($_SESSION['link'], $sql);
  //如果請求成功
  if ($query) {
    //使用 mysqli_num_rows 方法，判別執行的語法，其取得的資料量，是否有一筆資料
    if (mysqli_num_rows($query) >= 1) {
      //取得的量大於0代表有資料
      //回傳的 $result 就給 true 代表有該帳號，不可以被新增
      $result = true;
    }
    //釋放資料庫查詢到的記憶體
    mysqli_free_result($query);
  } else {
    echo "{$sql} 語法執行失敗，錯誤訊息：" . mysqli_error($_SESSION['link']);
  }
  //回傳結果
  return $result;
}

/**
 * 新增住戶，創建的時候用的
 */
function add_user($username, $password, $Number, $Floor, $Room)
{
  //宣告要回傳的結果
  $result = null;
  //先把密碼用md5加密
  //$password = md5($password);
  $create_date = date("Y-m-d H:i:s");
  $create_god_id = $_SESSION['login_pe_god_id'];
  /*
  if ($Room == '0') {
    $sql = "INSERT INTO `pe` (`username`, `password`, `number`, `floor`, `create_date`, `god`) VALUE ('{$username}', '{$password}', {$Number}, {$Floor}, '{$create_date}', '{$create_god_id}');";
  } elseif ($Room != '') {
    $sql = "INSERT INTO `pe` (`username`, `password`, `number`, `floor`, `room`, `create_date`, `god`) VALUE ('{$username}', '{$password}', {$Number}, {$Floor}, {$Room}, '{$create_date}', '{$create_god_id}');";
  } else {
    $sql = "INSERT INTO `pe` (`username`, `password`, `number`, `floor`, `create_date`, `god`) VALUE ('{$username}', '{$password}', {$Number}, {$Floor}, '{$create_date}', '{$create_god_id}');";
  }
  */

  $sql = "INSERT INTO `pe` (`username`, `password`, `number`, `floor`, `room`, `create_date`, `god`) VALUE ('{$username}', '{$password}', {$Number}, {$Floor}, {$Room}, '{$create_date}', '{$create_god_id}');";
  //將查詢語法當成字串，記錄在$sql變數中
  //用 mysqli_query 方法取執行請求（也就是sql語法），請求後的結果存在 $query 變數中
  $query = mysqli_query($_SESSION['link'], $sql);
  //如果請求成功
  if ($query) {
    //使用 mysqli_affected_rows 判別異動的資料有幾筆，基本上只有新增一筆，所以判別是否 == 1
    if (mysqli_affected_rows($_SESSION['link']) == 1) {
      //取得的量大於0代表有資料
      //回傳的 $result 就給 true 代表有該帳號，不可以被新增
      $result = true;
    }
  } else {
    echo "{$sql} 語法執行失敗，錯誤訊息：" . mysqli_error($_SESSION['link']);
  }
  //回傳結果
  return $result;
}

/**
 * 讀取社區住戶的資料
 */
function get_datas($id)
{
  //宣告要回傳的結果
  $result = null;
  //將查詢語法當成字串，記錄在$sql變數中
  $sql = "SELECT * FROM `pe` WHERE `id` = {$id};";
  //用 mysqli_query 方法取執行請求（也就是sql語法），請求後的結果存在 $query 變數中
  $query = mysqli_query($_SESSION['link'], $sql);
  //如果請求成功
  if ($query) {
    //使用 mysqli_num_rows 方法，判別執行的語法，其取得的資料量，是否有一筆資料
    if (mysqli_num_rows($query) == 1) {
      //取得的量大於0代表有資料
      //while迴圈會根據查詢筆數，決定跑的次數
      //mysqli_fetch_assoc 方法取得 一筆值
      $result = mysqli_fetch_assoc($query);
    }
    //釋放資料庫查詢到的記憶體
    mysqli_free_result($query);
  } else {
    echo "{$sql} 語法執行失敗，錯誤訊息：" . mysqli_error($_SESSION['link']);
  }

  //回傳結果
  return $result;
}


/**
 * 取得住戶裡面的聯絡人資料，顯示在住戶首頁
 * function get_family($id)
 */

function get_id_family($id)
{
  //宣告空的陣列
  $datas = array();
  //將查詢語法當成字串，記錄在$sql變數中
  $sql = "SELECT * FROM `pe_family` WHERE `create_user_id` = {$id};";
  //用 mysqli_query 方法取執行請求（也就是sql語法），請求後的結果存在 $query 變數中
  $query = mysqli_query($_SESSION['link'], $sql);
  //如果請求成功
  if ($query) {
    //使用 mysqli_num_rows 方法，判別執行的語法，其取得的資料量，是否大於0
    if (mysqli_num_rows($query) > 0) {
      //取得的量大於0代表有資料
      //while迴圈會根據查詢筆數，決定跑的次數
      //mysqli_fetch_assoc 方法取得 一筆值
      while ($row = mysqli_fetch_assoc($query)) {
        $datas[] = $row;
      }
    }
    //釋放資料庫查詢到的記憶體
    mysqli_free_result($query);
  } else {
    echo "{$sql} 語法執行失敗，錯誤訊息：" . mysqli_error($_SESSION['link']);
  }
  //回傳結果
  return $datas;
}

/**
 * 檢查資料庫是否已經有同樣的聯絡人名稱
 */
function check_has_linkname($username)
{
  //宣告要回傳的結果
  $result = null;
  $create_user_id = $_SESSION['login_pe_id'];
  //將查詢語法當成字串，記錄在$sql變數中
  $sql = "SELECT * FROM `pe_family` WHERE `name` = '{$username}' AND `create_user_id` = {$create_user_id};";
  //用 mysqli_query 方法取執行請求（也就是sql語法），請求後的結果存在 $query 變數中
  $query = mysqli_query($_SESSION['link'], $sql);
  //如果請求成功
  if ($query) {
    //使用 mysqli_num_rows 方法，判別執行的語法，其取得的資料量，是否有一筆資料
    if (mysqli_num_rows($query) >= 1) {
      //取得的量大於0代表有資料
      //回傳的 $result 就給 true 代表有該帳號，不可以被新增
      $result = true;
    }
    //釋放資料庫查詢到的記憶體
    mysqli_free_result($query);
  } else {
    echo "{$sql} 語法執行失敗，錯誤訊息：" . mysqli_error($_SESSION['link']);
  }

  //回傳結果
  return $result;
}

/**
 * 增加聯絡人
 */
function add_family($name, $cate, $link)
{
  //宣告要回傳的結果
  $result = null;
  //將查詢語法當成字串，記錄在$sql變數中
  $create_date = date("Y-m-d H:i:s");
  $create_user_id = $_SESSION['login_pe_id'];
  $sql = "INSERT INTO `pe_family` (`name`, `category`, `link`, `create_date`, `create_user_id`) VALUE ('{$name}', '{$cate}', '{$link}', '{$create_date}', {$create_user_id});";
  //用 mysqli_query 方法取執行請求（也就是sql語法），請求後的結果存在 $query 變數中
  $query = mysqli_query($_SESSION['link'], $sql);
  //如果請求成功
  if ($query) {
    //使用 mysqli_affected_rows 判別異動的資料有幾筆，基本上只有新增一筆，所以判別是否 == 1
    if (mysqli_affected_rows($_SESSION['link']) == 1) {
      //取得的量大於0代表有資料
      //回傳的 $result 就給 true 代表有該帳號，不可以被新增
      $result = true;
    }
  } else {
    echo "{$sql} 語法執行失敗，錯誤訊息：" . mysqli_error($_SESSION['link']);
  }
  //回傳結果
  return $result;
}

/**
 * 刪除聯絡人
 */
function del_family($id)
{
  //宣告要回傳的結果
  $result = null;
  //將查詢語法當成字串，記錄在$sql變數中
  //$sql = "DELETE `family` SET `name` = '{$name}', `link` = '{$link}',  `modify_date` = '{$modify_date}' WHERE `id` = {$id};";
  $sql = "DELETE FROM `pe_family` WHERE `id` = {$id};";
  //用 mysqli_query 方法取執行請求（也就是sql語法），請求後的結果存在 $query 變數中
  $query = mysqli_query($_SESSION['link'], $sql);
  //如果請求成功
  if ($query) {
    //使用 mysqli_affected_rows 判別異動的資料有幾筆，基本上只有新增一筆，所以判別是否 == 1
    if (mysqli_affected_rows($_SESSION['link']) == 1) {
      //取得的量大於0代表有資料
      //回傳的 $result 就給 true 代表有該帳號，不可以被新增
      $result = true;
    }
  } else {
    echo "{$sql} 語法執行失敗，錯誤訊息：" . mysqli_error($_SESSION['link']);
  }
  //回傳結果
  return $result;
}

/**
 * 編輯聯絡人
 */
function get_edit_family($id)
{
  //宣告要回傳的結果
  $result = null;
  //將查詢語法當成字串，記錄在$sql變數中
  $sql = "SELECT * FROM `pe_family` WHERE `id` = {$id};";
  //用 mysqli_query 方法取執行請求（也就是sql語法），請求後的結果存在 $query 變數中
  $query = mysqli_query($_SESSION['link'], $sql);
  //如果請求成功
  if ($query) {
    //使用 mysqli_num_rows 方法，判別執行的語法，其取得的資料量，是否有一筆資料
    if (mysqli_num_rows($query) == 1) {
      //取得的量大於0代表有資料
      //while迴圈會根據查詢筆數，決定跑的次數
      //mysqli_fetch_assoc 方法取得 一筆值
      $result = mysqli_fetch_assoc($query);
    }
    //釋放資料庫查詢到的記憶體
    mysqli_free_result($query);
  } else {
    echo "{$sql} 語法執行失敗，錯誤訊息：" . mysqli_error($_SESSION['link']);
  }

  //回傳結果
  return $result;
}

/**
 * 更新聯絡人
 */
function update_family($id, $name, $cate, $link)
{
  //宣告要回傳的結果
  $result = null;
  //將查詢語法當成字串，記錄在$sql變數中
  $modify_date = date("Y-m-d H:i:s");
  $sql = "UPDATE `pe_family` SET `name` = '{$name}', `category` = '{$cate}', `link` = '{$link}',  `modify_date` = '{$modify_date}' WHERE `id` = {$id};";
  //用 mysqli_query 方法取執行請求（也就是sql語法），請求後的結果存在 $query 變數中
  $query = mysqli_query($_SESSION['link'], $sql);
  //如果請求成功
  if ($query) {
    //使用 mysqli_affected_rows 判別異動的資料有幾筆，基本上只有新增一筆，所以判別是否 == 1
    if (mysqli_affected_rows($_SESSION['link']) == 1) {
      //取得的量大於0代表有資料
      //回傳的 $result 就給 true 代表有該帳號，不可以被新增
      $result = true;
    }
  } else {
    echo "{$sql} 語法執行失敗，錯誤訊息：" . mysqli_error($_SESSION['link']);
  }
  //回傳結果
  return $result;
}

/**
 * 更新社區住戶資料
 */
function update_user($id, $username, $password, $Number, $Floor, $Room)
{
  //宣告要回傳的結果
  $result = null;

  //將查詢語法當成字串，記錄在$sql變數中
  $modify_date = date("Y-m-d H:i:s");
  $password_sql = '';
  $Room_sql = '';
  if ($password != '') {
    //$password = md5($password);
    $password_sql = "`password` = '{$password}',";
  }
  if ($Room == '0') {
    $Room_sql = "`room` = null,";
  } elseif ($Room != '') {
    $Room_sql = "`room` = {$Room},";
  } else {
    $Room_sql = "`room` = null,";
  }
  //echo "{$sql}";
  $sql = "UPDATE `pe` SET {$password_sql} {$Room_sql} `number` = {$Number},`floor` = {$Floor},`username` = '{$username}',  `modify_date` = '{$modify_date}' WHERE `id` = {$id};";
  //$sql = "UPDATE `family` SET `name` = '{$name}', `link` = '{$link}',  `modify_date` = '{$modify_date}' WHERE `id` = {$id};";
  //用 mysqli_query 方法取執行請求（也就是sql語法），請求後的結果存在 $query 變數中
  $query = mysqli_query($_SESSION['link'], $sql);
  //如果請求成功
  if ($query) {
    //使用 mysqli_affected_rows 判別異動的資料有幾筆，基本上只有新增一筆，所以判別是否 == 1
    if (mysqli_affected_rows($_SESSION['link']) == 1) {
      //取得的量大於0代表有資料
      //回傳的 $result 就給 true 代表有該帳號，不可以被新增
      $result = true;
    }
  } else {
    echo "{$sql} 語法執行失敗，錯誤訊息：" . mysqli_error($_SESSION['link']);
  }

  //回傳結果
  return $result;
}

/**
 * 讀取屬於社區的資料
 */
function get_com_datas($god_name)
{
  //宣告要回傳的結果
  $result = null;
  //將查詢語法當成字串，記錄在$sql變數中
  $sql = "SELECT * FROM `god` WHERE `god_name` = '{$god_name}';";
  //用 mysqli_query 方法取執行請求（也就是sql語法），請求後的結果存在 $query 變數中
  $query = mysqli_query($_SESSION['link'], $sql);
  //如果請求成功
  if ($query) {
    //使用 mysqli_num_rows 方法，判別執行的語法，其取得的資料量，是否有一筆資料
    if (mysqli_num_rows($query) == 1) {
      //取得的量大於0代表有資料
      //while迴圈會根據查詢筆數，決定跑的次數
      //mysqli_fetch_assoc 方法取得 一筆值
      $result = mysqli_fetch_assoc($query);
    }
    //釋放資料庫查詢到的記憶體
    mysqli_free_result($query);
  } else {
    echo "{$sql} 語法執行失敗，錯誤訊息：" . mysqli_error($_SESSION['link']);
  }
  //回傳結果
  return $result;
}
