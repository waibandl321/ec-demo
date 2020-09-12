<?php
session_start();
require_once('../config/dbconnect.php');

//セッションで保持したuserデータ
$user = $_SESSION["user"];
$user_id = $_SESSION["user"]["id"];

//個数
$quantity = $_POST["quantity"];
$item_id = $_POST["item_id"];

//パラメーターに付与された商品ID(code)を取得して、紐つく商品データを取得
if(isset($_GET['code'])) {
    $code = $_GET['code'];
    $sql = "SELECT * FROM items WHERE item_id = $code";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $item_info = $stmt->fetchAll();

    //カートから商品IDに紐づく商品の数量を取得
    foreach($item_info as $item) {
        $id = $item["item_id"];
        $sql = "SELECT quantity FROM cart WHERE item_id = $id";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $cart_quantity = intval($stmt->fetch());
    }
}

//cartテーブルへのinsert
if(isset($_POST['cart_in'])) {
    //もし同じ商品がカート内に存在する場合の処理
    if($cart_quantity){
        //cartテーブルの更新
        $same_item_message = "同じアイテムがカート内に存在しています";
        //合計の数量を算出
        $sum_quantity = [];
        $sum_quantity[] = intval($quantity);
        $sum_quantity[] = $cart_quantity;
        $fix_sum_quantity = array_sum($sum_quantity);
        //データベースの更新
        $cart_sql = "UPDATE cart SET quantity = $fix_sum_quantity WHERE item_id = $id";
        $cart_stmt = $dbh->prepare($sql);
        $cart_stmt->execute();
    } else {
        //同じ商品がカートに存在しない場合の処理
        $stmt = $dbh->prepare("INSERT INTO cart(user_id, item_id, quantity) VALUES (?, ?, ?)");
        $data = [];
        $data[] = $user_id;
        $data[] = $item_id;
        $data[] = $quantity;
        $stmt->execute($data);
    }

} else {
    $cart_in_message = '商品をカートに追加してください';
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
            <div class="item-detail__links">
                <a href="../users/cart.php" class="to__cart-page">カートへ</a>
                <a href="../items/item_list.php" class="back-to__item-list">商品一覧へ戻る</a>
            </div>
            <p class="text-primary font-weight-bold"><?php echo $db_success_message; ?></p>
            <p class="text-primary font-weight-bold"><?php echo $message; ?></p>
            <p class="text-primary font-weight-bold"><?php echo $same_item_message; ?></p>
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
                                <!-- count -->
                                <input type="hidden" name="count_updated_method" value="add">
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
        <?php var_dump($fix_sum_quantity); ?>
    </div>
    <?php include("../component/footer.php"); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.matchHeight/0.7.2/jquery.matchHeight-min.js" integrity="sha512-/bOVV1DV1AQXcypckRwsR9ThoCj7FqTV2/0Bm79bL3YSyLkVideFLE3MIZkq1u5t28ke1c0n31WYCOrO01dsUg==" crossorigin="anonymous"></script>
    <script src="../assets/js/index.js"></script>
</body>
</html>