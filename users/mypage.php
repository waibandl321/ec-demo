<?php
session_start();
require_once('../config/dbconnect.php');

if(!$_SESSION["login"]) {
    header('Location: ../users/login.php');
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include("../component/header.php"); ?>
    <div class="container mypage">
        <div class="login-user">現在ログイン中のユーザーid : <?php echo $id; ?></div>
        <h2>マイページ</h2>
        <a class="logout" href="../users/logout.php">ログアウトする</a>
        <ul class="user-information">
           <li><?php echo $id; ?></li>
           <li><?php echo $user["name"]; ?></li>
           <li><?php echo $user["email"]; ?></li>
           <li><?php echo $user["address"]; ?></li>
           <li><img src="./images/<?php echo $image; ?>"></li>
        </ul>
        <a href="../items/index.php">商品登録する</a>
        <a href="../items/item_list.php">商品一覧ページへ</a>
    </div>
<?php include("../component/footer.php"); ?>
<script src="../assets/js/index.js"></script>
</body>
</html>