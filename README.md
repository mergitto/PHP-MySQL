# prototype
PHPを用いた試作品です。故郷の鹿児島の観光をテーマに作成してみました。
##開発環境
php 5.6.29   
MySQL 5.1.73
###signup.php
新規ユーザー登録を行います。ユーザー名とパスワードを入力して、パスワードは
```php
password_hash($password, PASSWORD_DEFAULT)
```
password_hash()を用いてパスワードハッシュを作成してDBのuserテーブルに挿入してます。
###login.php,logout.php
**login.php**   
signup.phpで作成したパスワードハッシュとユーザーが入力したパスワードをpassword_verify()を用いて認証します。
```php
password_verify($password, $row['password'])　//$passwordはユーザーの入力、$row['password']はDBより取り出したハッシュパスワードです。
```
認証された場合は入力したユーザー名をsession変数に格納します。   
```
$_SESSION['USERID'] = $userid;
```
**logout.php**   
login.phpで発行したsession変数を破棄します
```
session_destroy();
```

###作品掲載
http://210-140-96-142.jp-east.compute.idcfcloud.com/php/bin/login.php
