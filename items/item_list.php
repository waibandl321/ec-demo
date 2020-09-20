<?php
session_start();
require_once('../config/dbconnect.php');

$sql = "SELECT * FROM items";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$items = $stmt->fetchAll();

$user = $_SESSION["user"];
$id = $_SESSION["user"]["id"];

// ログインしていない場合は、ログインリンクを表示する
if(!$_SESSION["login"]) {
    $message = "ログインする";
} else {
    $message = "ログアウトする";
}

//悪意のあるスクリプトを入力されたときにXSSを防ぐための方法にhtmlspecialchars関数を使用
//よく使用するため関数化
function h($str){
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧ページ</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include("../component/header.php"); ?>
    <div class="container">
        <div class="item-list__wrap">
        <!-- <div class="about-user">
            <p class="about-user__item">現在ログイン中のユーザー : <?php echo h($id); ?></p>
        </div> -->
        <h2>商品一覧</h2>
            <ul class="item-list">
                <?php for($i = 0; $i < count($items); $i++) : ?>
                <li class="item-detail js-item-detail">
                    <div class="trimming"><img src="../items/images/<?php echo h($items[$i]["item_thumbnail"]); ?>" alt="商品画像" class="item-detail__image"></div>
                    <!-- <p class="item-detail__id">商品ID : <?php echo h($items[$i]["item_id"]); ?></p> -->
                    <p class="item-detail__name">商品名 : <?php echo h($items[$i]["item_name"]); ?></p>
                    <p class="item-detail__description">商品説明文 : <?php echo h($items[$i]["item_description"]); ?></p>
                    <p class="item-detail__price">価格 : <span class="item-detail__price__number font-weight-bold"><?php echo h($items[$i]["item_price"]); ?></span>円（税別）</p>
                    <p class="item-detail__stock">在庫数 : <?php echo h($items[$i]["item_stock"]); ?></p>
                    <a href="../items/item_detail.php?code=<?php echo h($items[$i]["item_id"]); ?>">詳細を見る</a>
                </li>
                <?php endfor; ?>
            </ul>
        </div>
    </div>
    <?php include("../component/footer.php"); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.matchHeight/0.7.2/jquery.matchHeight-min.js" integrity="sha512-/bOVV1DV1AQXcypckRwsR9ThoCj7FqTV2/0Bm79bL3YSyLkVideFLE3MIZkq1u5t28ke1c0n31WYCOrO01dsUg==" crossorigin="anonymous"></script>
    <script src="../assets/js/index.js"></script>
</body>
</html>