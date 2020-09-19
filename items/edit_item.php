<?php
session_start();
require_once('../config/dbconnect.php');

$user = $_SESSION["user"];
$user_id = $_SESSION["user"]["id"];

//POSTされた編集データを取得
$edit_name = $_POST["edit_name"];
$edit_price = $_POST["edit_price"];
$edit_description = $_POST["edit_description"];
$edit_stock = $_POST["edit_stock"];


if(!$user) {
    header('Location: ../items/item_list.php');
    exit;
}

//パラメータの値を取得
$item_id = $_GET['code'];

if(isset($_GET['code'])) {
    //item_idに紐付く商品データの取得
    $sql = "SELECT * FROM items WHERE item_id = $item_id AND seller_id = $user_id";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $edit_item = $stmt->fetch();
}

//商品情報を編集してsubmitされたらデータベースを更新


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
    <div class="container">
        <div class="item-registration__outer">
            <div class="item-registration">
                <div class="login-user">現在ログイン中のユーザー : <?php echo $user_id; ?></div>
                <h2>商品編集ページ</h2>
                <p class="text-primary"><?php echo $db_success_message; ?></p>
                <p class="text-danger"><?php echo $success_message; ?></p>
                <form method="POST" action="../items/edit_item.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="item-name">商品名</label>
                        <input type="text" name="edit_name" class="form-control" id="itemName" value="<?php echo $edit_item["item_name"]; ?>" placeholder="商品名を入力してください" required>
                    </div>
                    <div class="form-group">
                        <label for="item-price">商品価格</label>
                        <input type="number" name="edit_price" class="form-control" id="itemPrice" value="<?php echo $edit_item["item_price"]; ?>" placeholder="商品価格を入力してください" required>
                    </div>
                    <div class="form-group">
                        <label for="item-description">商品説明文</label>
                        <textarea class="form-control" name="edit_description" id="itemDescription" placeholder="商品説明文を入力してください" required><?php echo $edit_item["item_description"]; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="item-stock">在庫数</label>
                        <input type="number" name="edit_stock" class="form-control" id="itemStock" value="<?php echo $edit_item["item_stock"]; ?>" placeholder="在庫数を入力してください" required>
                    </div>
                    <input type="submit" class="btn btn-primary" name="edit" value="編集する">
                    </form>
                    <a href="../items/item_list.php">商品一覧ページへ</a>
            </div>
            <!-- 商品画像追加 -->
            <?php if(isset($_POST["edit"])) : ?>
            <a href="../items/images_uploaded.php?code="<?php echo $item["item_id"]; ?>">追加で画像を登録する</a>
            <?php endif; ?>
        </div>
    </div>
        
<?php include("../component/footer.php"); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.matchHeight/0.7.2/jquery.matchHeight-min.js" integrity="sha512-/bOVV1DV1AQXcypckRwsR9ThoCj7FqTV2/0Bm79bL3YSyLkVideFLE3MIZkq1u5t28ke1c0n31WYCOrO01dsUg==" crossorigin="anonymous"></script>
<!-- <script src="../assets/js/elevatezoom-master/jquery.elevatezoom.js"></script> -->
<!-- <script src="../assets/js/drift/Drift.min.js"></script> -->
<script src="../assets/js/index.js"></script>
</body>
</html>