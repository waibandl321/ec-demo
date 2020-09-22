<?php
session_start();
require_once('../config/dbconnect.php');
require_once('../config/functions.php');

$user_id = $_GET["user_id"];
//ユーザーとユーザーが登録している商品の削除処理
if(isset($user_id)) {
    $sql = "DELETE users, items FROM users LEFT JOIN items ON users.id = items.seller_id WHERE users.id = :id";
    // 削除するレコードのIDは空のまま、SQL実行の準備をする
    $stmt = $dbh->prepare($sql);
    // 削除するレコードのIDを配列に格納する
    $params = array(':id' => $user_id);
    // 削除するレコードのIDが入った変数をexecuteにセットしてSQLを実行
    $stmt->execute($params);
    $message = "退会しました。5秒後にページを移動します。";
}
session_destroy();
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
<?php include("../component/header.php"); ?>
  <main>
    <div class="container sign_out">
     <h2>退会ページ</h2>
     <p><?php echo h($message); ?></p>
     </div>
    </main>
    <?php include("../component/footer.php"); ?>
    <script src="../assets/js/index.js"></script>
    <script>
        setTimeout(() => {
            window.location.href = "../items/item_list.php";
        }, 3000);
    </script>
</body>
</html>