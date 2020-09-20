<?php
session_start();
require_once('../config/dbconnect.php');

//セッションで保持したuserデータ
$user = $_SESSION["user"];
$user_id = $_SESSION["user"]["id"];

$quantity = $_POST["quantity"];
$item_id = $_POST["item_id"];

//悪意のあるスクリプトを入力されたときにXSSを防ぐための方法にhtmlspecialchars関数を使用
//よく使用するため関数化
function h($str){
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

//パラメーターに付与された商品ID(code)を取得 =>商品ID(code)に紐つく商品データを取得し、商品詳細情報のブロックに表示させる
if(isset($_GET['code'])) {
    //パラメータの値を取得
    $code = $_GET['code'];
    //紐付く商品データの取得
    $sql = "SELECT * FROM items WHERE item_id = :code";
    $stmt = $dbh->prepare($sql);
    //指定された変数名にパラメータをバインドする
    $stmt->bindParam(':code', $code, PDO::PARAM_INT);
    $stmt->execute();
    $item_info = $stmt->fetchAll();

    //カートから商品IDに紐づく商品の数量を取得
    foreach($item_info as $item) {
        $id = $item["item_id"];
        //商品idとユーザーidを条件に指定して、cartテーブルから数量(quantity)を取得
        $sql = "SELECT * FROM cart WHERE item_id = :item_id AND user_id = :user_id";
        $stmt = $dbh->prepare($sql);
        //指定された変数名にパラメータをバインドする
        $stmt->bindParam(':item_id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        //数量の取得
        $cart_quantity = $stmt->fetch()["quantity"];
    }
    //商品IDに紐づく追加で登録された複数の商品画像を取得する処理
    foreach($item_info as $item) {
        $id = $item["item_id"];
        $sql = "SELECT * FROM item_images WHERE item_id = :item_id";
        $stmt = $dbh->prepare($sql);
        //指定された変数名にパラメータをバインドする
        $stmt->bindParam(':item_id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $item_images = $stmt->fetchAll();
    }
}

//同じ商品がカート内に存在する場合の処理
if(isset($cart_quantity)) {
    $same_item_message = "同じアイテムがカート内に存在しています";
    if($_POST["count_updated_method"]){
        $add_quantity = $_POST["update_quantity"];
        $sum_quantity = $cart_quantity + $add_quantity;
        $sql = "UPDATE cart SET quantity = :sum_quantity WHERE item_id = :item_id AND user_id = :user_id";
        $stmt = $dbh->prepare($sql);
        //指定された変数名にパラメータをバインドする
        $stmt->bindParam(':sum_quantity', $sum_quantity, PDO::PARAM_INT);
        $stmt->bindParam(':item_id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
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
    <link rel="stylesheet" href="../assets/css/drift/drift-basic.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include("../component/header.php"); ?>
    <div class="container">
        <div class="item-page">
            <h2>商品ページ</h2>
            <p class="text-primary font-weight-bold"><?php echo h($message); ?></p>
            <p class="text-primary font-weight-bold"><?php echo h($same_item_message); ?></p>
            <?php foreach($item_info as $item) : ?>
            <div class="item-detail__wrap">
                <div class="item-detail__images">
                    <div class="item-detail__images__main zoom__lens__container">
                        <img src="../items/images/<?php echo h($item["item_thumbnail"]); ?>" alt="商品のサムネイル画像" class="zoom__item js_main_image">
                        <div class="zoom_lens"></div>
                    </div>
                    <ul class="other-image__items">
                    <!-- その他の画像の取得 -->
                    <?php for($i = 0; $i < count($item_images); $i++) : ?>
                        <li class="other-image__item">
                            <img src="../items/images/<?php echo h($item_images[$i]["image_name"]); ?>" alt="他の商品画像が入ります" class="thumbnail">
                        </li>
                    <?php endfor; ?>
                    </ul>
                    <div class="zoom__area">
                        <img src="">
                    </div>
                </div>
                <div class="item-detail__block">
                    <!-- 商品詳細 -->
                    <p class="item-detail__block__id">
                        <span>商品id</span> : <?php echo h($item["item_id"]); ?>
                    </p>
                    <p class="item-detail__block__name">
                        <span>商品名</span> : <?php echo h($item["item_name"]); ?>
                    </p>
                    <p class="item-detail__block__price">
                        <span>価格</span> : <?php echo h($item["item_price"]); ?>
                    </p>
                    <p class="item-detail__block__description">
                        <span>商品説明</span> : <?php echo h($item["item_description"]); ?>
                    </p>
                    <p class="item-detail__block__stock">
                        <span>在庫数</span> : <?php echo h($item["item_stock"]); ?>
                    </p>
                    <p class="item-detail__block__created-at">
                        <span>出品日時</span> : <?php echo h($item["created_at"]); ?>
                    </p>
                    <form action="" method="POST">
                        <div class="select_quantity">
                            <span>個数を選択 : </span>
                            <!-- 　商品詳細ページで表示しているアイテムがカートに存在 or 存在しない場合にselectボックスの表示を切り替える -->
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
                            <!--　　条件分岐ここまで 　-->
                        </div>
                        <div class="cart-in__wrap">
                            <div>
                            <!-- 　「カートに追加」の表示ボタンを切り替えるための条件分岐 　-->
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
                            <!--　　条件分岐ここまで 　-->
                            </div>
                            <div class="back-to-item-list">
                                <a href="../items/item_list.php" class="back-to-item-list___link">商品一覧へ戻る</a>
                            </div>
                        </div>
                    </form>
                    <!-- <p class="text-primary"><?php echo h($cart_in_message); ?></p> -->
                    <p class="text-primary"><?php echo h($finish_insert_message); ?></p>
                    <p class="text-primary"><?php echo h($select_message); ?></p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php include("../component/footer.php"); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.matchHeight/0.7.2/jquery.matchHeight-min.js" integrity="sha512-/bOVV1DV1AQXcypckRwsR9ThoCj7FqTV2/0Bm79bL3YSyLkVideFLE3MIZkq1u5t28ke1c0n31WYCOrO01dsUg==" crossorigin="anonymous"></script>
    <!-- <script src="../assets/js/elevatezoom-master/jquery.elevatezoom.js"></script> -->
    <!-- <script src="../assets/js/drift/Drift.min.js"></script> -->
    <script src="../assets/js/index.js"></script>
</body>
</html>