<?php
session_start();
require_once('../config/dbconnect.php');

//セッションで保持したuserデータ
$user = $_SESSION["user"];
$user_id = $_SESSION["user"]["id"];

$quantity = $_POST["quantity"];
$item_id = $_POST["item_id"];

//パラメーターに付与された商品ID(code)を取得して、紐つく商品データを取得して商品詳細情報のブロックに表示させる
if(isset($_GET['code'])) {
    //パラメータの値を取得
    $code = $_GET['code'];
    //紐付く商品データの取得
    $sql = "SELECT * FROM items WHERE item_id = $code";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $item_info = $stmt->fetchAll();

    //カートから商品IDに紐づく商品の数量を取得
    foreach($item_info as $item) {
        $id = $item["item_id"];
        //商品idとユーザーidを条件に指定して、cartテーブルから数量(quantity)を取得
        $sql = "SELECT quantity FROM cart WHERE item_id = $id AND user_id = $user_id";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        //数量の取得
        $cart_quantity = $stmt->fetch()["quantity"];
    }
}

//同じ商品がカート内に存在する場合の処理
if(isset($cart_quantity)) {
    $same_item_message = "同じアイテムがカート内に存在しています";
    if($_POST["count_updated_method"]){
        $add_quantity = $_POST["update_quantity"];
        $sum_quantity = $cart_quantity + $add_quantity;
        $sql = "UPDATE cart SET quantity = $sum_quantity WHERE item_id = $id AND user_id = $user_id";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
    }
} elseif(isset($_POST["cart_in"])) {
    //insertされたことを確認するための<input type="hidden" name="finish_insert">からPOSTされたデータを取得
    $finish_insert = $_POST["finish_insert"];
     //同じ商品がカートに存在しない場合の処理 cartテーブルへのinsert
     $stmt = $dbh->prepare("INSERT INTO cart(user_id, item_id, quantity) VALUES (?, ?, ?)");
     $data = [];
     $data[] = $user_id;
     $data[] = $item_id;
     $data[] = $quantity;
     $stmt->execute($data);
     //insertが完了したら処理を終了するためにheader関数で同じページに飛ばして、cartテーブルから商品情報の取得が完了した状態にする
     header("Location: ../items/item_detail.php?code={$item_id}");
} else {
    //cartに紐付く商品がなく、cartテーブルにinsertもされていない状態の処理
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
                            <!-- ▼▼▼▼▼▼　商品詳細ページで表示しているアイテムがカートに存在 or 存在しない場合にselectボックスの表示を切り替える ▼▼▼▼▼▼-->
                            <?php if(!$cart_quantity) : ?>
                            <!-- もしカート内に同じアイテムが存在しない場合に表示するselectボックス-->
                            <select name="quantity">
                                <?php for($i=1; $i<=20; $i++){
                                    echo "<option value=".$i.">".$i."</option>";
                                }?>
                            </select>
                            <?php else : ?>
                            <!-- もしカート内に同じアイテムが存在する場合に表示するselectボックス-->
                            <!-- name="update_quantity"で値の上書きのためのデータを取得 -->
                            <select name="update_quantity">
                                <?php for($i=1; $i<=20; $i++){
                                    echo "<option value=".$i.">".$i."</option>";
                                }?>
                            </select>
                            <?php endif; ?>
                            <!--　▲▲▲▲▲▲　条件分岐ここまで ▲▲▲▲▲▲　-->
                        </div>
                        <div class="cart-in__wrap">
                            <div>
                            <!-- ▼▼▼▼▼▼　「カートに追加」の表示ボタンを切り替え流ための条件分岐 ▼▼▼▼▼▼　-->
                            <?php if(!$cart_quantity) : ?>
                                <!-- もしカート内に商品がない場合に表示させるボタン -->
                                <input type="submit" name="cart_in" value="カートに入れる" class="cart-in__bottom">
                                <!-- 商品のinsertが終わったことを表現する<input type="hidden"> -->
                                <input type="hidden" name="finish_insert">
                                <!-- insertされる商品のitem_idをpostする -->
                                <input type="hidden" value="<?=$item["item_id"]?>" name="item_id">
                            <?php else : ?>
                                <!-- もしカート内に商品がある場合に表示させるボタン（値の上書き用） -->
                                <input type="submit" name="count_updated_method" value="カートに入れる" class="cart-in__bottom">
                            <?php endif; ?>
                            <!--　▲▲▲▲▲▲　条件分岐ここまで ▲▲▲▲▲▲　-->
                            </div>
                            <div class="back-to-item-list">
                                <a href="../items/item_list.php" class="back-to-item-list___link">戻る</a>
                            </div>
                        </div>
                    </form>
                    <p class="text-primary"><?php echo $cart_in_message; ?></p>
                    <p class="text-primary"><?php echo $finish_insert_message; ?></p>
                    <p class="text-primary"><?php echo $select_message; ?></p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php var_dump($cart_quantity); ?>
        <?php var_dump($add_quantity); ?>
        <?php var_dump($sum_quantity); ?>
    </div>
    <?php include("../component/footer.php"); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.matchHeight/0.7.2/jquery.matchHeight-min.js" integrity="sha512-/bOVV1DV1AQXcypckRwsR9ThoCj7FqTV2/0Bm79bL3YSyLkVideFLE3MIZkq1u5t28ke1c0n31WYCOrO01dsUg==" crossorigin="anonymous"></script>
    <script src="../assets/js/index.js"></script>
</body>
</html>