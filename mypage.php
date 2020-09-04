<?php
session_start();
require_once('dbconnect.php');

if(!$_SESSION["login"]) {
    header('Location: login.php');
    exit;
}
$user = $_SESSION["user"];
$id = $_SESSION["user"]["id"];
$image = $_SESSION["user"]["user_image"];


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
        <h2>マイページ</h2>
        <a class="logout" href="./logout.php">ログアウトする</a>
        <ul class="user-information">
           <li><?php echo $id; ?></li>
           <li><?php echo $user["name"]; ?></li>
           <li><?php echo $user["email"]; ?></li>
           <li><?php echo $user["address"]; ?></li>
           <li><img src="./"><?php echo $image; ?></li>
        </ul>
    </div>
</body>
</html>