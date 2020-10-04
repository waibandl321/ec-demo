<?php
session_start();
require_once('../config/dbconnect.php');
require_once('../config/functions.php');

$user = $_SESSION["user"];
$id = $_SESSION["user"]["id"];
$image = $_SESSION["user"]["user_image"];


//ログインユーザー以外はログインページにリダイレクト
if(!$_SESSION["login"]) {
    header('Location: ../users/login.php');
    exit;
} else {
    //ユーザーが登録した商品を取得する処理 seller_id(ユーザーid)に紐付くデータを取得
    $sql = "SELECT * FROM items WHERE seller_id = :seller_id";
    $stmt = $dbh->prepare($sql);
    //指定された変数名にパラメータをバインドする
    $stmt->bindParam(':seller_id', $id, PDO::PARAM_INT);
    $stmt->execute();
    //数量の取得
    $items = $stmt->fetchAll();
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>マイページ</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include("../component/header.php"); ?>
<main>
    <div class="container mypage">
        <div>
            <!-- <div class="login-user">現在ログイン中のユーザーid : <?php echo h($id); ?></div> -->
            <h2>ユーザー情報</h2>
            <div class="user-information__wrap">
                <div class="user-information__image">
                    <img src="./images/<?php echo h($image); ?>">
                </div>
                <ul class="user-information__list">
                    <li>ユーザーID : <?php echo h($id); ?></li>
                    <li>ユーザー名 : <?php echo h($user["name"]); ?></li>
                    <li>メールアドレス : <?php echo h($user["email"]); ?></li>
                    <li>登録住所 : <?php echo h($user["address"]); ?></li>
                    <li><a href="../items/index.php" class="registered-item__link">商品登録する</a></li>
                    <li id="signOutBtn"><a href="../users/signout.php?user_id=<?php echo h($id); ?>" class="signout_link btn btn-block btn-large">退会する</a></li>
                </ul>
            </div>
            
        </div>
        <hr class="hr upper__registered-items">
        <div class="registered-items__wrap">
            <h3>出品した商品一覧</h3>
            <ul class="registered-items">
                <?php for($i = 0; $i < count($items); $i++) : ?>
                <li class="registered-item js-registered-item">
                    <div class="trimming">
                        <img src="../items/images/<?php echo h($items[$i]["item_thumbnail"]); ?>" alt="商品画像" class="item-detail__image">
                    </div>
                    <p class="registered-item__id">商品ID : <?php echo h($items[$i]["item_id"]); ?></p>
                    <p class="registered-item__name">商品名 : <?php echo h($items[$i]["item_name"]); ?></p>
                    <p class="registered-item__description">商品説明文 : <?php echo h($items[$i]["item_description"]); ?></p>
                    <p class="registered-item__price">価格 : <span class="price__number text-danger"><?php echo h($items[$i]["item_price"]); ?></span></p>
                    <p class="registered-item__stock">在庫数 : <?php echo h($items[$i]["item_stock"]); ?></p>
                    <a href="../items/index.php?code=<?php echo h($items[$i]["item_id"]); ?>" class="registered-item__edit">商品情報を編集する</a>
                </li>
                <?php endfor; ?>
            </ul>
        </div>
    </div>
</main>
<?php include("../component/footer.php"); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.matchHeight/0.7.2/jquery.matchHeight-min.js" integrity="sha512-/bOVV1DV1AQXcypckRwsR9ThoCj7FqTV2/0Bm79bL3YSyLkVideFLE3MIZkq1u5t28ke1c0n31WYCOrO01dsUg==" crossorigin="anonymous"></script>
<!-- <script src="../assets/js/elevatezoom-master/jquery.elevatezoom.js"></script> -->
<!-- <script src="../assets/js/drift/Drift.min.js"></script> -->
<script src="../assets/js/index.js"></script>
<script>
    // 退会ボタンを押した時のアラート表示
    const signOutBtn = document.getElementById('signOutBtn');
    signOutBtn.addEventListener('click', () => {
        alert('本当に退会しますか？？');
    })
    // jQuery
    $(function(){
        $('.js-registered-item').matchHeight();
    
    });
</script>
</body>
</html>