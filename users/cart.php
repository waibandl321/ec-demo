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
//カートに追加された個数を取得
foreach($cart_items as $cart_item) {

}

//カートに表示されるアイテムを個別に取得
$items = [];
for($i = 0; $i < count($cart_items); $i++) {
    $data = $cart_items[$i]["item_id"];
    $sql = "SELECT * FROM items WHERE item_id = $data";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $items[] = $stmt->fetch(); //対象の値(item_id)が単数形の場合はfetch() 複数形の場合はfetchAll() 単数形の処理の時にfetchAll()を使うと正常に取得ができない
}

//合計金額の計算
foreach($items as $item) {
    $a = floor($item["item_price"] * 1.10); //floorで小数点切り捨て
    $sum += $a;
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
            <p class="about-user__item"><a href="../items/item_list.php">商品一覧へ戻る</a></p>
            <p class="about-user__item"><a href="../users/logout.php"><?php echo $message; ?></a></p>
        </div>
        <div class="cart">
         <div class="cart-items">
            <h2>SHOPPING CART</h2>
            <div class="cart-items__inner">
                <?php foreach($items as $item) : ?>
                    <div class="cart-item">
                        <div class="cart-item__image">
                            <img src="../items/images/<?php echo $item["item_thumbnail"]; ?>" alt="商品画像">
                        </div>
                        <div class="cart-item__infomation">
                            <p>商品ID : <?php echo $item["item_id"]; ?></p>
                            <p><a href="../items/item_detail.php?code=<?php echo $item["item_id"]; ?>"><?php echo $item["item_name"]; ?></a></p>
                            <p>価格 : <?php echo floor($item["item_price"] * 1.10); ?></p>
                            <p>数量 : 
                            </p>
                            <p>商品説明 : <?php echo $item["item_description"]; ?></p>
                        </div>
                    </div>
                <?php endforeach;?>
                <div class="cart-item__sum">合計金額 : <?php echo $sum; ?>円(税込)</div>
            </div>
          </div>
          <aside class="checkout-link">
            <div class="total__price">
             <p class="cart-item__sum__aside">合計金額 : <?php echo $sum; ?>円(税込)</p>
            </div>
            <div class="to-checkout-page">
                <a href="">決済画面へ</a>
            </div>
          </aside>
        </div>
    </div>
    <?php include("../component/footer.php"); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.matchHeight/0.7.2/jquery.matchHeight-min.js" integrity="sha512-/bOVV1DV1AQXcypckRwsR9ThoCj7FqTV2/0Bm79bL3YSyLkVideFLE3MIZkq1u5t28ke1c0n31WYCOrO01dsUg==" crossorigin="anonymous"></script>
    <script src="../assets/js/index.js"></script>
</body>
</html>