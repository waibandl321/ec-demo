<?php
session_start();
require_once('../config/dbconnect.php');
require_once('../config/functions.php');

$user = $_SESSION["user"];
$user_id = $_SESSION["user"]["id"];

//パラメーターから商品IDを取得
$item_id = $_GET['item_id'];


//商品データを削除する処理
if(isset($_GET['item_id'])) {
    $sql = "DELETE FROM items WHERE item_id = :item_id";
    // 削除するレコードのIDは空のまま、SQL実行の準備をする
    $stmt = $dbh->prepare($sql);
    // 削除するレコードのIDを配列に格納する
    $params = array(':item_id' => $item_id);
    // 削除するレコードのIDが入った変数をexecuteにセットしてSQLを実行
    $stmt->execute($params);
    $delete_success_msg = "商品データを削除しました。";
} else {
    //削除に失敗した場合
    $err_msg = "商品の削除に失敗しました。もう一度やり直してください。";
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
<?php include("../component/header.php"); ?>
  <main>
    <div class="container item_destroy">
    <?php if(isset($_GET['item_id'])) : ?>
     <p><?php echo h($delete_success_msg); ?></p>
     <a href="../users/mypage.php">マイページへ</a>
     <?php else : ?>
     <p><?php echo h($err_msg); ?></p>
     <a href="../users/mypage.php">マイページへ</a>
     <?php endif; ?>
     </div>
    </main>
    <?php include("../component/footer.php"); ?>
    <script src="../assets/js/index.js"></script>
    <script>
    /*================================================
    商品を削除した後に指定秒数後にページマイページへ遷移する処理
    ==================================================*/
    // setTimeout(() => {
    //     window.location.href = '../users/mypage.php';
    // }, 5000);
    
    </script>
</body>
</html>