<?php
session_start();
require_once('../config/dbconnect.php');

if(isset($_GET['code'])) {
    $code = $_GET['code'];
    $sql = "SELECT * FROM items WHERE item_id = $code";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $item_info = $stmt->fetchAll();
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
                        <?php echo $item["item_id"]; ?>
                    </p>
                    <p class="item-detail__block__name">
                        <?php echo $item["item_name"]; ?>
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