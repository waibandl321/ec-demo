<?php
session_start();
require_once('dbconnect.php');

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    // 画像を取得
    $message = '画像を登録してください';
} else {
    // 画像を保存
    if (!empty($_FILES['image']['name'])) {
        $_SESSION["image"]  = $img;
        $image_name = $_FILES['image']['name'];
        $image_type = $_FILES['image']['type'];
        $image_content = file_get_contents($_FILES['image']['tmp_name']);
        $image_size = $_FILES['image']['size'];
        $stmt = $dbh->prepare("insert into user_image(image_name, image_type, image_content, image_size) values (?, ?, ?, ?)");
        $data = [];
        $data[] = $image_name;
        $data[] = $image_type;
        $data[] = $image_content;
        $data[] = $image_size;
        $stmt->execute($data);
        $message = '登録に成功しました!';
    }
    unset($pdo);
    exit();
}


?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>画像アップロードテスト</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container user-registration">
        <h2>画像アップロードテスト</h2>
        <p class="text-primary font-weight-bold"><?php echo $db_success_message; ?></p>
        <form action="./image_view.php" class="form" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="image">画像</label>
                <input type="file"" name="image" class="form-control" id="user-image" required>
            </div>
            <button type="submit" class="btn btn-primary btn-lg btn-block mt-4">送信</button>
        </form>
    </div>
</body>
</html>