<?php
session_start();
require_once('dbconnect.php');

$_SESSION = array();

if(isset($_COOKIE["PHPSESSION"])) {
    setcookie("PHPSESSION", '', time() - 1800, '/');
}
session_destroy();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>マイページ</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mypage">
        <h2>ログアウトページ</h2>
        <p>ログアウトしました</p>
        <ul>
            <li><a href="./index.php">登録画面へ</a></li>
            <li><a href="./login.php">ログイン</a></li>
        </ul>
    </div>
    <script src="./index.js"></script>
</body>
</html>