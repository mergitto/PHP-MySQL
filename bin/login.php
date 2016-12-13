<?php
//ini_set("display_errors",1); //以下2行はエラーメッセージを出す　デバッグ用
//error_reporting(E_ALL);
require 'password.php';   // password_verfy()はphp 5.5.0以降の関数のため、バージョンが古くて使えない場合に使用
require_once('../DbManager.php');
// セッション開始
session_start();


// エラーメッセージの初期化
$errorMessage = "";

// ログインボタンが押された場合
if (isset($_POST["login"])) {
    // ユーザIDの入力チェック
    if (empty($_POST["userid"])) {  // emptyは値が空のとき
        $errorMessage = 'ユーザーIDが未入力です。';
    } else if (empty($_POST["password"])) {
        $errorMessage = 'パスワードが未入力です。';
    }

    if (!empty($_POST["userid"]) && !empty($_POST["password"])) {
        // 入力したユーザIDを格納
        $userid = $_POST["userid"];

        // エラー処理
        try {
            $db = getDb();

            $hash_agri = $db->prepare('select password from user where name = :name;');
            $hash_agri->bindParam(':name', $userid);
            $hash_agri->execute();

            $password = $_POST['password'];//ユーザーが入力したパスワード

            if($row = $hash_agri->fetch(PDO::FETCH_ASSOC)){ 
                //$row['password']==データベースから取り出したユーザーID
                if (password_verify($password, $row['password'])) {
                    //echo 'Password is valid!';
                    $_SESSION['USERID'] = $userid;
                    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/top.php");
                    //echo $_SESSION['USERID'];
                    //exit();
                    
                }else{
                    // 認証失敗
                    $errorMessage = 'ユーザーIDあるいはパスワードに誤りがあります。';
                }
            }else{
                // 認証成功なら、セッションIDを新規に発行する
                // 該当データなし
                $errorMessage = 'ユーザーIDあるいはパスワードに誤りがあります。';
            }
        }catch(PDOException $e) {
            $errorMessage = 'データベースエラー';
            //$errorMessage = $sql;
            // $e->getMessage() でエラー内容を参照可能（デバック時のみ表示）
            //echo $e->getMessage();
        }
    }
}

//ゲストでログインが押されたとき
if(isset($_POST['guestLogin'])){
    $_SESSION['USERID'] = 'ゲスト';
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/top.php");
}
?>

<!doctype html>
<html>
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../css/logform.css">
    <title>ログイン</title>
    </head>
    <body>
    <div class="container">
        <div class="login-head-btn">
            <a href="signup.php"><button type="button">新規登録</button></a>
        </div>
        <div class="title">
            <h1>ログイン画面</h1>
        </div>

        <div class="form">
            <form id="loginForm" name="loginForm" action="" method="POST">
            <p>ログインフォーム</p>
            <div><font color="#ff0000"><?php echo $errorMessage ?></font></div>
            <p><label for="userid">ユーザーID</label><input type="text" id="userid" name="userid" placeholder="ユーザーIDを入力" value="<?php if (!empty($_POST["userid"])) {echo htmlspecialchars($_POST["userid"], ENT_QUOTES);} ?>"></p>
            <p><label for="password">パスワード</label><input type="password" id="password" name="password" value="" placeholder="パスワードを入力"></p>
            <div class="login-btn">
                <input type="submit" id="login" name="login" value="ログイン">
                <input type="submit" id="guestLogin" name="guestLogin" value="ゲストでログイン"> 
            </div>
        </form>
        </div>
    </div>
    </body>
</html>