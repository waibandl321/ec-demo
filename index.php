<?php
session_start();
require_once('./config/dbconnect.php');
require_once('./config/functions.php');

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(empty($_POST["check_data"])) {
        $err_msg = "エラー発生";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>トップページ</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
    <link rel="stylesheet" href="../assets/css/style.css">
    
</head>
<body>
<?php include("./component/header.php"); ?>
  <main>
    <div class="container">
     <h1>トップページです</h1>
        <ul>
            <li><a href="../users/index.php">ユーザー登録</a></li>
            <li><a href="../users/login.php">ログイン</a></li>
            <li><a href="../items/index.php">商品登録</a></li>
            <li><a href="../items/item_list.php">商品一覧</a></li>
        </ul>
        <!-- フォームバリデーションのチェック -->
        <form method="POST" name="check_form">
            送信データ<br />
            <p><?php echo h($err_msg); ?></p>
            <input type="text" name="check_data" value="" /><br /><br />
            <input type="submit" value="送信" name="submit" />
        </form>
    </div>
    </main>
    <?php include("./component/footer.php"); ?>
    <script src="../assets/js/index.js"></script>
</body>
</html>