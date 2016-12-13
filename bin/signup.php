<?php
// password_hash()はphp5.5.0以降の関数のため、バージョンが古くて使えない場合に使用
require 'password.php';
require_once('../DbManager.php');
// セッション開始
session_start();

$db = getDb();
//userテーブルに格納されているnameを使ってnameが一意に識別できるようにする
$user_name = "select name from user;";
$user_name_result = $db->query($user_name);

$error_js = $_POST['error'];

// エラーメッセージ、登録完了メッセージの初期化
$errorMessage = "";
$SignUpMessage = "";

// ログインボタンが押された場合
if (isset($_POST["signUp"])) {
    // ユーザIDの入力チェック
    if (empty($_POST["username"])) {  // 値が空のとき
        $errorMessage = 'ユーザーIDが未入力です。';
    } else if (empty($_POST["password"])) {
        $errorMessage = 'パスワードが未入力です。';
    } else if (empty($_POST["password2"])) {
        $errorMessage = 'パスワードが未入力です。';
    }

    foreach ($user_name_result as $key => $value) {
      if($_POST['username'] == $value['name']){
        $errorMessage = 'すでに登録されているユーザーです。';
      }
    }

    if (!empty($_POST["username"]) && !empty($_POST["password"]) && !empty($_POST["password2"]) && $_POST["password"] == $_POST["password2"] && empty($errorMessage)) {
        // 入力したユーザIDとパスワードを格納
        $username = $_POST["username"];
        $password = $_POST["password"];


        //エラー処理
        try {
            $db = getDb();

            $stmt = $db->prepare("INSERT INTO user(name, password) VALUES (?, ?)");

            $stmt->execute(array($username, password_hash($password, PASSWORD_DEFAULT)));
            $userid = $db->lastinsertid();  // 登録した(DB側でauto_incrementした)IDを$useridに入れる

            $SignUpMessage = '登録が完了しました。あなたの登録IDは '. $username. ' です。パスワードは '. $password. ' です。';  // ログイン時に使用するIDとパスワード
        } catch (PDOException $e) {
            $errorMessage = 'データベースエラー';
            // $e->getMessage() でエラー内容を参照可能（デバック時のみ表示）
            // echo $e->getMessage();
        }
    } else if($_POST["password"] != $_POST["password2"]) {
        $errorMessage = 'パスワードに誤りがあります。';
    }
}
?>

<!doctype html>
<html>
<head>
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="../css/logform.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<title>新規登録</title>
</head>
<body>
<div class="bgimg">
  
  <div class="box">
    <div class="box-1">かごんまのよかとこいっかすっで</div>
    <div class="box-2">
      鹿児島のいいところ教えます。<br>新規登録が済んだ人は
      <a href="login.php"><button type="button">ログイン画面へ</button></a>
    </div>
    <form id="loginForm" name="loginForm" action="" method="POST">
            <div id="error"><font color="#ff0000"><?php echo $errorMessage ?></font></div>
            <input type="text" hidden name="error" value="<?php echo $errorMessage;?>">
            <div id="sign"><font color="#ffffff"><?php echo $SignUpMessage ?></font></div>
            <div id="new_form">
              <p>新規登録フォーム</p>
              <p><label for="username">ユーザー名</label><input type="text" id="username" name="username" placeholder="ユーザー名を入力" value="<?php if (!empty($_POST["username"])) {echo htmlspecialchars($_POST["username"], ENT_QUOTES);} ?>"></p>
              <p><label for="password">パスワード</label><input type="password" id="password" name="password" value="" placeholder="パスワードを入力"></p>
              <p><label for="password2">パスワード(確認用)</label><input type="password" id="password2" name="password2" value="" placeholder="再度パスワードを入力"></p>
              <div class="login-btn">
                  <input type="submit" id="signUp" name="signUp" value="登録">
              </div>
            </div>
        </form>
  </div>
</div>
<script>
  $(function(){
    //errorメッセージが出なければ新規登録フォームをフェードアウトする
    if($('#error').text() == "" && $('#sign').text() != ""){
      $('#new_form').fadeOut();
    }
  });
</script>
</body
</html>