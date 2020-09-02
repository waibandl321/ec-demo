<?php
session_start();
require_once('dbconnect.php');

function findUserByEmail($dbh, $email) {
    $sql = 'SELECT * FROM users WHERE email = ?';
    $stmt = $dbh->prepare($sql);
    $data[] = $email;
    $stmt->execute($data);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
if (!empty($_POST)) {
    $user = findUserByEmail($dbh, $_POST["email"]);
    if (password_verify($_POST["password"], $user["password"])) {
        $_SESSION["login"] = true;
        $_SESSION["user"]  = $user;
        $id = $_SESSION["user"]["id"];
        header('Location: ./mypage.php');
        exit;
    } else {
        $errors[] = 'メールアドレスまたはパスワードに誤りがあります。';
}
}
$errors = [];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログインページ</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>ログイン</h2>
        <!-- エラーチェック -->
        <?php if(!empty($errors)): ?>
        <ul class="error-box">
        <?php foreach($errors as $error): ?>
        <li><?php echo $error; ?></li>
        <?php endforeach ?>
        </ul>
        <?php endif ?>
        <!-- エラーチェック -->

        <!-- ログインフォーム -->
        <div class="login">
        <form action="" method="POST">
        <div class="form-group">
                <label for="email">メールアドレス</label>
                <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="メールアドレス" required>
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="パスワード" required>
            </div>
            <button type="submit" name="login" id="loginBtn" class="btn btn-warning btn-lg btn-block mt-4">ログインする</button>
        </form>
        </div>
        <!-- ログインフォーム -->
    </div>
</body>
</html>