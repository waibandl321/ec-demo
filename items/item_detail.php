<?php
session_start();
require_once('../config/dbconnect.php');

//セッションで保持したuserデータ
$user = $_SESSION["user"];
$user_id = $_SESSION["user"]["id"];

$quantity = $_POST["quantity"];
$item_id = $_POST["item_id"];

//パラメーターに付与された商品ID(code)を取得して、紐つく商品データを取得
if(isset($_GET['code'])) {
    $code = $_GET['code'];
    $sql = "SELECT * FROM items WHERE item_id = $code";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $item_info = $stmt->fetchAll();
}

//cartテーブルへのinsert
if(isset($_POST['cart_in'])) {
    $stmt = $dbh->prepare("insert into cart(user_id, item_id, quantity) values (?, ?, ?)");
    $data = [];
    $data[] = $user_id;
    $data[] = $item_id;
    $data[] = $quantity;
    $stmt->execute($data);
    $cart_in_message = 'カートへの追加に成功しました!';
} else {
    $cart_in_message = 'カートへの追加に失敗しました!';
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品詳細</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include("../component/header.php"); ?>
    <div class="container">
        <div class="item-page">
            <h2>商品ページ</h2>
            <a href="../items/item_list.php" class="back-to__item-list">商品一覧へ戻る</a>
            <p class="text-primary font-weight-bold"><?php echo $db_success_message; ?></p>
            <?php foreach($item_info as $item) : ?>
            <div class="item-detail__wrap">
                <div class="item-detail__images">
                    <div class="item-detail__images__main">
                        <img src="../items/images/<?php echo $item["item_thumbnail"]; ?>" alt="商品のサムネイル画像">
                    </div>
                    <div class="item-detail__images__sub">

                    </div>
                </div>
                <div class="item-detail__block">
                    <!-- 商品詳細 -->
                    <p class="item-detail__block__id">
                        商品id : <?php echo $item["item_id"]; ?>
                    </p>
                    <p class="item-detail__block__name">
                        商品名 : <?php echo $item["item_name"]; ?>
                    </p>
                    <p class="item-detail__block__price">
                        価格 : <?php echo $item["item_price"]; ?>
                    </p>
                    <p class="item-detail__block__description">
                        商品説明 : <?php echo $item["item_description"]; ?>
                    </p>
                    <p class="item-detail__block__stock">
                        在庫数 : <?php echo $item["item_stock"]; ?>
                    </p>
                    <p class="item-detail__block__created-at">
                        出品日時 : <?php echo $item["created_at"]; ?>
                    </p>
                    <form action="" method="POST">
                        <div>
                            個数を選択
                            <select name="quantity">
                            <?php for($i=1; $i<=20; $i++){
                                echo "<option value=".$i.">".$i."</option>";
                            }?>
                            </select>
                        </div>
                        <div class="cart-in__wrap">
                            <div>
                                <input type="submit" name="cart_in" value="カートに入れる" class="cart-in__bottom">
                                <input type="hidden" value="<?=$item["item_id"]?>" name="item_id">
                            </div>
                            <div class="back-to-item-list">
                                <a href="../items/item_list.php" class="back-to-item-list___link">戻る</a>
                            </div>
                        </div>
                    </form>
                    <p class="text-primary"><?php echo $cart_in_message; ?></p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php var_dump($item_info); ?>
    </div>
    <?php include("../component/footer.php"); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.matchHeight/0.7.2/jquery.matchHeight-min.js" integrity="sha512-/bOVV1DV1AQXcypckRwsR9ThoCj7FqTV2/0Bm79bL3YSyLkVideFLE3MIZkq1u5t28ke1c0n31WYCOrO01dsUg==" crossorigin="anonymous"></script>
    <script src="../assets/js/index.js"></script>
</body>
</html>