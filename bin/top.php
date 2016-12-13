<?php
require_once('../DbManager.php');
require_once('getdata.php');
session_start();

//$_SESSION['USERID']に値が無ければ（ログインしていなければ）login.phpに返す
if(!isset($_SESSION['USERID'])){
  header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/login.php");
}

$genre = $_GET['genre'];
//$genreが空白でなくて、IDを登録してログインしていれば条件を指定
if(!is_null($genre) && !($_SESSION['USERID'] == 'ゲスト') ){
  $sql = "select * from kagoshima where ジャンル = '$genre';";
}else{
  $sql = "select * from kagoshima";
}
$sql_result = $db->query($sql); //$sqlを実行
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript" src="../script/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="../css/style.css">
<title>鹿児島の観光情報</title>
</head>
<body>
<!--=================ナビバー==================-->
<nav class="navbar navbar-inverse navbar-fixed-top" style="z-index: 10;" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <?php echo "<li style='list-style:none; float:left;' class='navbar-brand'>ようこそ！".$_SESSION['USERID'].'さん</li>';?>
        </div>
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav navbar-right sp_gnav">
                <li><a href="login.php">ログイン</a></li>
                <li><a href="logout.php" id="logOut">ログアウト</a></li>
            </ul>
        </div>
    </div>
</nav>
<!--=================end ナビバー==================-->
<div class="contents" id="menu">
<?php if($_SESSION['USERID']=='ゲスト'):?><p>ゲストでログインしているときは一部の情報と機能のみの公開となっています。<br>ID登録してログインすると観光地名よりホームページへとぶことができます</p>
<?php else: ?>
  <h4>画像を押すとホームページに飛ぶことができます。</h4>
<?php endif ?>

  <div class="contents-head middle-text">
    鹿児島の観光情報
  </div>
  <!--ジャンル分けによって検索を行う-->
  <?php if(!($_SESSION['USERID'] == 'ゲスト')): ?>
  <div class="search">
    <form action="top.php" method="get" accept-charset="utf-8">
      <select name="genre" id="genre">
        <?php foreach($genre_result as $key => $value): ?>
          <option value="<?php echo $value['ジャンル']; ?>"><?php echo $value['ジャンル']; ?></option>
        <?php endforeach ?>
        <input type="submit" name="" value="検索">
      </select>
    </form>
    <a href="top.php">リセットする</a>
  </div>
  <?php endif ?>

  <div class="center-contents">
  <?php foreach ($sql_result as $key => $value) : ?>
    <?php if($_SESSION['USERID'] == 'ゲスト'):?>
      <?php if($key == 10){break;}?>
    <?php endif ?>
      <div class="select-contents">
        <?php $imgOrder = $key+1; //画像の名前に合わせるための変数 ?>
        <span><a href=<?php if($_SESSION['USERID']!=='ゲスト'){echo '"'.$value['ホームページ'].'"'.'target="_brank"';} ?> ><img class="contents-image middle-text" src="../img/s_<?php if($imgOrder<10){ echo '00'.$imgOrder;}elseif($imgOrder<100){echo '0'.$imgOrder;}else{ echo $imgOrder;} ?>.jpg"></a></span>
        <?php if($_SESSION['USERID'] !== 'ゲスト'): ?><p class="arrow_box"><?php echo $value['説明文'];?></p><?php endif ?>
        <div class="contents-details">
          <ul>
            <li><h3>
            <?php if($_SESSION['USERID'] == 'ゲスト'): //ゲストならリンクを外す ?>
              <?php echo $value['名称']; ?>
            <?php else: ?>
              <a href=<?php echo '"'.$value['ホームページ'].'"'.'target="_brank"'; ?> >
            <?php echo $value['名称']; ?>
              </a>
            <?php endif ?>
            </h3></li>
            <li><?php echo $value['ジャンル']; ?></li>
            <li><?php echo 'TEL:'.$value['電話番号']; //ゲストならリンクを外す ?></li>
            <li>
            <?php if($_SESSION['USERID'] == 'ゲスト'): ?>
              <?php echo '住所:'.$value['都道府県名'].$value['市区町村名'].$value['場所']; ?>
            <?php else: ?>
              <a href=<?php if($_SESSION['USERID']!=='ゲスト'){echo '"'.'http://maps.google.co.jp/maps?q='.$value['都道府県名'].$value['市区町村名'].$value['場所'].'"'.'target="_brank"'; }?> ><?php echo '住所:'.$value['都道府県名'].$value['市区町村名'].$value['場所']; ?></a>
            <?php endif ?>
            </li>
          </ul>
        </div>
      </div>
  <?php endforeach ?>
  </div>
</div>
<script type="text/javascript">
$(function(){
  $('#logOut').click(function(){
    if(!confirm('本当にログアウトしますか。')){
        /* キャンセルの時の処理 */
        return false;
    }else{
        /*　OKの時の処理 */
        location.href = 'logout.php';
    }
  });
});
</script>
</body>
</html>