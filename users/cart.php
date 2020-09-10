<?php
session_start();
require_once('../config/dbconnect.php');

$user = $_SESSION["user"];
$user_id = $_SESSION["user"]["id"];

// ログインしていない場合は、ログインリンクを表示する
if(!$user) {
    header('Location: ../items/item_list.php');
    exit;
} else {
    //ログインユーザーに紐づく商品情報をCartテーブルから取得
    $message = "ログアウト";
    $sql = "SELECT * FROM cart WHERE user_id = $user_id";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $cart_items = $stmt->fetchAll();
}
for($i = 0; $i < count($cart_items); $i++) {
    $data = $cart_items[$i]["item_id"];
    $sql = "SELECT * FROM items WHERE item_id = $data";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $item = $stmt->fetchAll();
    var_dump($item);
    var_dump($item["item_name"]);
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>カート</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include("../component/header.php"); ?>
    <div class="container">
        <div class="about-user">
            <p class="about-user__item">現在ログイン中のユーザー : <?php echo $user_id; ?></p>
            <p class="about-user__item"><a href="../users/cart.php"><?php echo $to_cart; ?></a></p>
            <p class="about-user__item"><a href="../users/logout.php"><?php echo $message; ?></a></p>
        </div>
        <div class="cart__wrap">
            <h2>カート詳細</h2>
            <div class="cart__items__wrap">

            </div>
        </div>
    </div>
    <?php include("../component/footer.php"); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.matchHeight/0.7.2/jquery.matchHeight-min.js" integrity="sha512-/bOVV1DV1AQXcypckRwsR9ThoCj7FqTV2/0Bm79bL3YSyLkVideFLE3MIZkq1u5t28ke1c0n31WYCOrO01dsUg==" crossorigin="anonymous"></script>
    <script src="../assets/js/index.js"></script>
</body>
</html>