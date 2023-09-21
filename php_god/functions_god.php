<?php
//啟動 session ，這樣才能夠取用 $_SESSION['link'] 的連線，做為資料庫的連線用
@session_start();


/**
 * 檢查資料庫有無該社區名稱，登入帳號的時候用的
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
      $_SESSION['is_login_god'] = TRUE;
      //紀錄登入者的id，之後若要隨時取得使用者資料時，可以透過 $_SESSION['login_user_id'] 取用
      $_SESSION['login_god_id'] = $user['id'];
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
 * 檢查資料庫是否已經有人先註冊過這個社區名稱，創建社區用的
 */
function check_god_name($god_name)
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
 * 新增社區，創建帳號用的
 */
function add_god($god_name, $password, $address, $start_number, $end_number, $latitude, $longitude)
{
  //宣告要回傳的結果
  $result = null;
  //先把密碼用md5加密
  //$password = md5($password);
  $create_date = date("Y-m-d H:i:s");
  //將查詢語法當成字串，記錄在$sql變數中
  $sql = "INSERT INTO `god` (`god_name`, `password`, `create_date`, `address`, `start_number`, `end_number`, `longitude`, `latitude`) 
                    VALUE ('{$god_name}', '{$password}', '{$create_date}', '{$address}', {$start_number}, {$end_number}, {$longitude}, {$latitude});";
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
 * 讀取已經創建的社區的資料
 */
function get_datas($id)
{
  //宣告要回傳的結果
  $result = null;
  //將查詢語法當成字串，記錄在$sql變數中
  $sql = "SELECT * FROM `god` WHERE `id` = {$id};";
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
 * 更新社區資料
 */
function update_user($id, $username, $password, $address, $start_number, $end_number, $latitude, $longitude)
{
  //宣告要回傳的結果
  $result = null;
  //將查詢語法當成字串，記錄在$sql變數中
  $modify_date = date("Y-m-d H:i:s");
  $password_sql = '';
  if ($password != '') {
    //$password = md5($password);
    $password_sql = "`password` = '{$password}',";
  }
  //echo "{$sql}";
  $sql = "UPDATE `god` SET {$password_sql} `address` = '{$address}',
                                          `start_number` = {$start_number},
                                          `end_number` = {$end_number},
                                          `latitude` = {$latitude},
                                          `longitude` = {$longitude},
                                          `god_name` = '{$username}',  
                                          `modify_date` = '{$modify_date}' WHERE `id` = {$id};";
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
 * 檢查註冊時的地址有無填寫錯誤
 */
function check_addr1($address)
{
  //如果回傳true 則會不給註冊
  if (preg_match("/[A-Za-z0-9]/", $address)) {
    $result = true;
  } elseif (strpos($address, "號")) {
    $result = true;
  } else {
    $result = null;
  }
  //回傳結果
  return $result;
}


/**
 * 檢查註冊時的地址有無重複
 */
function check_addr($address, $start_number, $end_number)
{
  //宣告要回傳的結果
  $result = null;
  //將查詢語法當成字串，記錄在$sql變數中
  $sql = "SELECT * FROM `god` WHERE `address` = '{$address}' AND `start_number` = {$start_number} AND `end_number` = {$end_number};";
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

  $result1 = check_addr1($address);
  //回傳結果
  return ($result || $result1);
}


/**
 * 取得住戶裡面的聯絡人資料，顯示在住戶首頁
 * function get_family($id)
 */

function get_id_pe($god_name)
{
  //宣告空的陣列
  $datas = array();
  //將查詢語法當成字串，記錄在$sql變數中
  //$sql = "SELECT * FROM `pe` WHERE `god` = '{$god_name}';";

  $sql = "SELECT * FROM `pe` WHERE `god` = '{$god_name}' ORDER BY `number`,`floor`,`room` ASC;";
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
 * 刪除住戶
 */
function del_pe($id)
{
  //宣告要回傳的結果
  $result = null;
  //將查詢語法當成字串，記錄在$sql變數中
  //$sql = "DELETE `family` SET `name` = '{$name}', `link` = '{$link}',  `modify_date` = '{$modify_date}' WHERE `id` = {$id};";
  $sql = "DELETE FROM `pe` WHERE `id` = {$id};";
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
 * 搜尋屬於某個社區的住戶的'號'
 */
function get_id_pe_number($god_name)
{
  //宣告空的陣列
  $datas = array();
  //將查詢語法當成字串，記錄在$sql變數中
  //$sql = "SELECT * FROM `pe` WHERE `god` = '{$god_name}';";

  $sql = "SELECT `number` FROM `pe` WHERE `god` = '{$god_name}' ORDER BY `number` ASC;";
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
        $datas[] = $row['number'];
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
 * 製造出屬於這個社區的住戶有登記資料的'號'
 */
function get_numbers($datas)
{
  //$datas = get_datas(base64_decode($id)); //先用這個社區的id查這個社區的資料
  $pe_datas = get_id_pe_number($datas['god_name']); //用這個社區的名稱茶有哪些住戶是屬於這個社區的，然後只取出屬於這個社區的住戶的'號'
  $floors = range($datas['start_number'], $datas['end_number'], 1); //做一個陣列，從起始號到結束號的連續陣列
  $result = array_intersect($floors, $pe_datas); //將重複的號只取出一個，用取交集來執行
  return $result;
}





/**
 * 搜尋屬於某個社區的住戶的'號'的"樓"
 */
function get_id_pe_floor($number, $god_name)
{
  //宣告空的陣列
  $datas = array();
  //將查詢語法當成字串，記錄在$sql變數中
  //$sql = "SELECT * FROM `pe` WHERE `god` = '{$god_name}';";

  $sql = "SELECT `floor` FROM `pe` WHERE `god` = '{$god_name}' AND `number` = {$number} ORDER BY `floor` ASC;";
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
        $datas[] = $row['floor'];
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
 * 製造出屬於這個社區的住戶有登記資料的'號'的"樓"
 */
function get_floors($number, $god_name)
{
  $pe_datas = get_id_pe_floor(base64_decode($number), $god_name); //先去資料庫撈特定社區特定'號'的住戶的'樓'
  $numbers = range(1, 127, 1); //做一個陣列，從1到127樓的連續陣列
  $result = array_intersect($numbers, $pe_datas); //將重複的樓只取出一個，用取交集來執行
  return $result;
}


/**
 * 搜尋屬於某個社區的住戶的'號'的"樓"的"室"
 */
function get_id_pe_room($number, $floor, $god_name)
{
  //宣告空的陣列
  $datas = array();

  //將查詢語法當成字串，記錄在$sql變數中
  //$sql = "SELECT * FROM `pe` WHERE `god` = '{$god_name}';";

  $sql = "SELECT `id`,`room` FROM `pe` WHERE `god` = '{$god_name}' AND `number` = {$number} AND `floor` = {$floor} ORDER BY `room` ASC;";
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
        //$datas[] = $row['room'];
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

function mark_distance()
{
  $_SESSION['inside_dis_god_num'] = TRUE;
}

function dismark_distance()
{
  $_SESSION['inside_dis_god_num'] = FALSE;
}

function mark_distance_num()
{
  $_SESSION['inside_dis_god_flo'] = TRUE;
}

function mark_distance_roo()
{
  $_SESSION['inside_dis_god_roo'] = TRUE;
}
function mark_distance_cod()
{
  $_SESSION['inside_dis_god_cod'] = TRUE;
}