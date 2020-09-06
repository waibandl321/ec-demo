<?php
session_start();
require_once('../config/dbconnect.php');

$errors = [];

// 商品情報の取得
$item_name = $_POST['item_name'];
$item_price = $_POST['item_price'];
$item_description = $_POST['item_description'];
$item_stock = $_POST['item_stock'];

// 画像の取得
$item_thumbnail = $_FILES['item_thumbnail']['name'];
echo $item_thumbnail;

//画像の登録処理
if(!empty($_FILES['item_thumbnail']['name'])) {
 $upload_dir = '../items/images/';
 $uploaded_file = $upload_dir . basename($_FILES['item_thumbnail']['name']);
 move_uploaded_file($_FILES['item_thumbnail']['tmp_name'], $uploaded_file);
};

//商品情報のデータベースへのinsert処理
if(!empty($_POST)) {
    $stmt = $dbh->prepare("INSERT INTO items(item_name, item_price, item_description, item_stock, item_thumbnail) VALUES (?, ?, ?, ?, ?)");
    $data = [];
    $data[] = $item_name;
    $data[] = $item_price;
    $data[] = $item_description;
    $data[] = $item_stock;
    $data[] = $item_thumbnail;
    $stmt->execute($data);
    $message = "商品情報の登録に成功しました！";
} else {
    $message = "商品情報を入力してください";
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品登録画面</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include("../component/header.php"); ?>
    <div class="container">
        <div class="item-registration">
            <h2>商品登録ページ</h2>
            <p class="text-primary"><?php echo $db_success_message; ?></p>
            <form method="POST" action="../items/index.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="item-name">商品名</label>
                    <input type="text" name="item_name" class="form-control" id="itemName" placeholder="商品名を入力してください" required>
                </div>
                <div class="form-group">
                    <label for="item-price">商品価格</label>
                    <input type="number" name="item_price" class="form-control" id="itemPrice" placeholder="商品価格を入力してください" required>
                </div>
                <div class="form-group">
                    <label for="item-description">商品説明文</label>
                    <textarea class="form-control" name="item_description" id="itemDescription" placeholder="商品説明文を入力してください" required></textarea>
                </div>
                <div class="form-group">
                    <label for="item-stock">在庫数</label>
                    <input type="number" name="item_stock" class="form-control" id="itemStock" placeholder="在庫数を入力してください" required>
                </div>
                <div class="form-group">
                    <label for="item-thumbnail"">商品画像1(ファイルを選択 or ドラッグ&ドロップ)</label>
                    <input type="file" name="item_thumbnail" class="form-control form-control-file" id="itemThumbnail">
                </div>
                <button type="submit" class="btn btn-primary">保存する</button>
                </form>
                <p class="text-danger"><?php echo $message; ?></p>
                <a href="../items/item_list.php">商品一覧ページへ</a>
        </div>
        <?php include("../component/footer.php"); ?>
    <script src="../assets/js/index.js"></script>
</body>
</html>


<!-- <h2 class="item-image-registration">商品商品登録</h2>
<div>
    <div class="form-group">
        <label for="name"">商品画像1(ファイルを選択 or ドラッグ&ドロップ)</label>
        <input type="file" name="itemImage1" class="form-control form-control-file" id="itemImage1" required>
    </div>
    <div class="form-group">
        <label for="name"">商品画像2(ファイルを選択 or ドラッグ&ドロップ)</label>
        <input type="file" name="itemImage2" class="form-control form-control-file" id="itemImage2" required>
    </div>
    <div class="form-group">
        <label for="name"">商品画像3(ファイルを選択 or ドラッグ&ドロップ)</label>
        <input type="file" name="itemImage3" class="form-control form-control-file" id="itemImage3" required>
    </div>
</div> -->