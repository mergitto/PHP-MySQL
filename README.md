# prototype
PHPを用いた試作品です。故郷の鹿児島の観光をテーマに作成してみました。<br>
作品は一番下の作品掲載のurlよりアクセスしてください。       
##開発環境
php 5.6.29   
MySQL 5.1.73
###signup.php
新規ユーザー登録を行います。ユーザー名とパスワードを入力して、パスワードはpassword_hash()を用いてパスワードハッシュを作成してDBのuserテーブルに挿入してます。
```php
password_hash($password, PASSWORD_DEFAULT)
```
###login.php, logout.php, password.php
**login.php**<br>
signup.phpで作成したパスワードハッシュとユーザーが入力したパスワードをpassword_verify()を用いて認証します。
```php
password_verify($password, $row['password'])　//$passwordはユーザーの入力、$row['password']はDBより取り出したハッシュパスワードです。
```
認証された場合は入力したユーザー名をsession変数に格納します。   
```
$_SESSION['USERID'] = $userid;
```
**logout.php**<br>
login.phpで発行したsession変数を破棄します
```
session_destroy();
```
**password.php**<br>
password_hash()とpassword_verify()はphp5.5.0以降の関数なのでphp5.5.0以前の環境においても使用できるようにした[password_compatライブラリ](https://github.com/ircmaxell/password_compat)のpassword.phpを使用しています。    
###DbManager.php
PDOを用いてMySQLと接続しています。
```
//dbName, userName, passwordを自分の環境に合わせて変更してください
$dsn = 'mysql:dbname=dbName; host=localhost; charset=utf8';
$usr = 'userName';
$passwd = 'password';
```    
###top.php
メインの画面になります。session変数を用いてゲストでログインしたときとID登録をしてログインしたときでは使える機能に差ができるようにしています。<br>
###
| ログインレベル   | 表示件数          | 機能                 | その他    |
| --------------- |:---------------:| -------------------- | ---------:|
| 個別ID          | 全て             | ジャンル検索          | リンク付き |
| ゲスト          | 10件             | 無し                 | 無し       |
### 
ユーザーがゲスト、もしくは登録IDからログインせずに、urlを直打ちしたときにはログイン画面に飛ぶようにしている。
```   
//$_SESSION['USERID']に値が無ければ（ログインしていなければ）login.phpに返す
if(!isset($_SESSION['USERID'])){
  header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/login.php");
}
```   
###getcsv.php, createtable.php, insert.php
**getcsv.php**<br>
[鹿児島県の観光情報のオープンデータ（csv形式）](https://www.city.kagoshima.lg.jp/jousys/documents/5-1_kankou.csv)を読み込み、phpで連想配列として取り込む処理を書いてます。   
**createtable.php**<br>
新規ユーザー登録時のテーブルやオープンデータを挿入するためのテーブルを生成するためのqueryを記述しています。<br>
開発環境で試してみるときには、ファイル内に「create文を実行するときのみ外す」という部分のコメントアウトタグを外し、直接このファイルをurlに打ち込みページを開くとDBにテーブルを作成してくれます。     
**insert.php**<br>
get.csvで連想配列にしたデータを用いて、作成したテーブルにデータを挿入します。createtable.php同様、データの挿入時にはコメントアウトタグを外してurlに直打ちするとデータをDBに取り込みます。    
###getdata.php     
DBからデータを取り出すqueryを書くだけのファイル
###作品掲載
http://210-140-96-142.jp-east.compute.idcfcloud.com/php/bin/login.php
