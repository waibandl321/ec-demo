<?php
session_start();
require_once('../config/dbconnect.php');
require_once('../config/functions.php');

// 入力項目の値
$status = 1;
$userName = $_POST["username"];
$email = $_POST["email"];
$password = $_POST["password"];
$address = $_POST["address"];

if(isset($_POST["register"])) {
    if (!empty($_FILES['image']['name'])) {
        $image_name = $_FILES['image']['name'];
        //ファイルを判定し、指定した拡張子であれば登録処理
        if ($_FILES['image']['type'] === 'image/jpg' ||
            $_FILES['image']['type'] === 'image/jpeg' ||
            $_FILES['image']['type'] === 'image/png' ||
            $_FILES['image']['type'] === 'image/svg' ||
            $_FILES['image']['type'] === 'image/webp' ||
            $_FILES['image']['type'] === 'image/gif') {
                //ディレクトリの設定
                $upload_dir = '../users/images/';
                //パスの作成 basename関数でファイル名だけ取得する + [name]に格納する （一次領域）
                $uploaded_file = $upload_dir . basename($_FILES['image']['name']);
                //move_uploaded_file関数で指定ディレクトリにファイルをアップロード
                move_uploaded_file($_FILES['image']['tmp_name'], $uploaded_file);
            }
    } else {
        // もし画像が選択されなかった場合はno image画像を追加する
        $image_name = "no_image.png";
        $stmt = $dbh->prepare("insert into users(name, email, password, address, user_image, status) values (?, ?, ?, ?, ?, ?)");
        $data = [];
        $data[] = $userName;
        $data[] = $email;
        //暗号化されたパスワードの中にsolt　solt = 暗号化の強度を高める
        $data[] = password_hash($password, PASSWORD_DEFAULT);
        $data[] = $address;
        $data[] = $image_name;
        $data[] = $status;
        $stmt->execute($data);
        // 登録に成功したら、アラートの後にページ遷移させる
        $alert = "
        <script>
            alert('ユーザー登録に成功しました');
            window.location.href = '../users/login.php';
        </script>";
        echo $alert;
    }
};







?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include("../component/header.php"); ?>
    <main>
    <div class="container">
        <div class="user-registration">
            <h2 class="page__title">ユーザー登録</h2>
            <p class="text-primary"><?php echo h($message); ?></p>
            <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                    <!-- 必須項目が入力されていない or 形式が異なる場合にエラーメッセージを表示 -->
                    <label for="name"">名前<span>(必須)</span></label>
                    <input type="text" name="username" class="form-control" id="name"" placeholder="名前" required>
                </div>
                <div class="form-group">
                    <!-- 必須項目が入力されていない or 形式が異なる場合にエラーメッセージを表示 -->
                    <label for="email">メールアドレス<span>(必須)</span></label>
                    <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="メールアドレス" required>
                    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                </div>
                <div class="form-group">
                    <!-- 必須項目が入力されていない or 形式が異なる場合にエラーメッセージを表示 -->
                    <label for="password">パスワード<span>(必須)</span></label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="パスワード" required>
                </div>
                <div class="form-group">
                    <!-- 必須項目が入力されていない or 形式が異なる場合にエラーメッセージを表示 -->
                    <label for="address">住所<span>(必須)</span></label>
                    <input type="text"" name="address" class="form-control" id="address" placeholder="住所" required>
                </div>
                <div class="form-group">
                    <label for="image">画像</label>
                    <input type="file"" name="image" class="form-control" id="user-image">
                    <p class="text-danger"><?php echo $file_error_message; ?></p>
                </div>
                <input type="submit" name="register" id="saveBtn" class="btn btn-lg btn-block mt-4" value="登録する">
            </form>
            <?php var_dump($same_email_user); ?>
        <div class="page-links">
            <p><a href="../users/login.php" class="font-weight-bold">すでに登録済みの方はこちら</a></p>
        </div>
        <p><a href="../users/login.php"><?php echo h($login_link); ?></a></p>
        <p><?php echo h($session_id); ?></p>
     </div>
    </div>
    </main>
    <?php include("../component/footer.php"); ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script src="../assets/js/index.js"></script>
    <script>
    </script>
</body>
</html>