<?php
session_start();
require_once('../config/dbconnect.php');

$errors = [];

// ファイルがあれば処理実行
if(isset($_FILES["upload_image"])){
// アップロードされたファイル件を処理
for($i = 0; $i < count($_FILES["upload_image"]["name"]); $i++ ){
    // アップロードされたファイルか検査
    if(is_uploaded_file($_FILES["upload_image"]["tmp_name"][$i])){
        // ファイルをお好みの場所に移動
        move_uploaded_file($_FILES["upload_image"]["tmp_name"][$i], "../test/images/" . $_FILES["upload_image"]["name"][$i]);
    }
    $stmt = $dbh->prepare("INSERT INTO item_images(item_image) VALUES (?)");
    $data = [];
    $data[] = $_FILES["upload_image"]["name"][$i];
    $stmt->execute($data);
    $message = "商品情報の登録に成功しました！";
    }
} else {
    $message = "画像を選択してください";
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>画像アップロードテスト</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include("../component/header.php"); ?>
    <main class="container">
        <h2>画像アップロードテスト</h2>
        <p class="text-primary"><?php echo $db_success_message; ?></p>
        <div class="register-images">
            <p class="text-danger">商品画像を登録する</p>
            <form action="../test/images_uploaded.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="images">画像1</label>
                <input type="file" class="form-control form-control-file" name="upload_image[]" multiple required>
            </div>
            <div class="form-group">
                <label for="images">画像2</label>
                <input type="file" class="form-control form-control-file" name="upload_image[]" multiple>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <p class="text-primary"><?php echo $message; ?></p>
            </form>
        </div>
    </main>
    <?php include("../component/footer.php"); ?>

    <script src="../assets/js/index.js"></script>
</body>
</html>