<?php
session_start();
require_once('../config/dbconnect.php');

$errors = [];

$userName = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$address = $_POST['address'];
$image_name = $_FILES['image']['name'];
//アップ画像がfilesに入る
if (!empty($_FILES['image']['name'])) {
  $upload_dir = '../users/images/'; //ディレクトリの設定
  $uploaded_file = $upload_dir . basename($_FILES['image']['name']); //パスの作成 basename関数でファイル名だけ取得する + [name]に格納する （一次領域）
  move_uploaded_file($_FILES['image']['tmp_name'], $uploaded_file); //move_uploaded_file関数でアップしたいディレクトリに移動
}


if(!empty($_POST)) {
    $stmt = $dbh->prepare("insert into users(name, email, password, address, user_image) values (?, ?, ?, ?, ?)");
    $data = [];
    $data[] = $userName;
    $data[] = $email;
    $data[] = password_hash($password, PASSWORD_DEFAULT);//暗号化されたパスワードの中にsolt　solt = 暗号化の強度を高める    
    $data[] = $address;
    $data[] = $image_name;
    $stmt->execute($data);
    $login_link = '<a href="../users/login.php">ログインページへ</a>';
    echo $data;
    $message = '登録に成功しました!';
} else {
    $message = 'ユーザー情報を入力してください';
}



?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <p><?php echo $data; ?></p>
    <div class="container">
        <div class="user-registration">
        <h2 class="page__title">ユーザー登録</h2>
        <p class="text-primary font-weight-bold"><?php echo $db_success_message; ?></p>
        <p class="text-danger font-weight-bold"><?php echo $message; ?></p>
        <form method="POST" action="./index.php" enctype="multipart/form-data">
        <div class="form-group">
                <label for="name"">名前</label>
                <input type="text" name="username" class="form-control" id="name"" placeholder="名前" required>
            </div>
            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="メールアドレス" required>
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="パスワード" required>
            </div>
            <div class="form-group">
                <label for="address">住所</label>
                <input type="text"" name="address" class="form-control" id="address" placeholder="住所" required>
            </div>
            <div class="form-group">
                <label for="image">画像</label>
                <input type="file"" name="image" class="form-control" id="user-image" required>
            </div>
            <input type="submit" name="register" id="saveBtn" class="btn btn-warning btn-lg btn-block mt-4" value="登録する">
        </form>
        <div class="page-links alert alert-secondary">
            <p><a href="../users/login.php" class="font-weight-bold">すでに登録済みの方はこちら</a></p>
            <p><a href="../items/index.php" class="font-weight-bold">商品を登録する</a></p>
        </div>
        <p><?php echo $login_link; ?></p>
        <p><?php echo $session_id; ?></p>
     </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>