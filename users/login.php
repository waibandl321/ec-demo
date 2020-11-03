<?php
session_start();
require_once('../config/dbconnect.php');
require_once('../config/functions.php');

function findUserByEmail($dbh, $email) {
    $sql = 'SELECT * FROM users WHERE email = ?';
    $stmt = $dbh->prepare($sql);
    $data[] = $email;
    $stmt->execute($data);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
//dataの存在チェック
if (!empty($_POST)) { 
    //findUserByEmail関数でemailを検索情報にしてusersテーブルから情報を取得
    $user = findUserByEmail($dbh, $_POST["email"]);
    //ユーザーのステータスがfalseの場合はリダイレクト処理 + エラーメッセージを表示
    if($user["status"] == 0) {
        header('Location: ../users/login.php?info=not_exist_user');
        exit;
    }
    //password_verify関数でpasswordの検証 暗号化されたパスワードとuserが入力されたpassを比較
    if (password_verify($_POST["password"], $user["password"])) {
        //passwordの比較結果がtrue
        $_SESSION["login"] = true;
        //user情報
        $_SESSION["user"]  = $user;
        $id = $_SESSION["user"]["id"];
        header('Location: ../users/mypage.php');
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include("../component/header.php"); ?>
<main>
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

        <!-- 退会済みユーザーがアクセスしてきた場合の表示切り替え パラメーターに-not_exist_userが含まれている場合->
        <?php if(isset($_GET["info"])) : ?>
            <!-- 退会済みユーザーの場合に適切なエラーメッセージを表示 -->
            <div class="not_exist_user">
                <p class="text-danger not_exist_user_msg">ユーザーが存在しません。<br>
                入力されたログイン情報が間違っているか、既に退会済みの可能性があります。</p>
                <div class="register_link">
                    <a href="../users/index.php">登録がまだの方はこちら</a>
                    <a href="../users/login.php">もう一度ログインする</a>
                </div>
        </div>
        <?php else : ?>
        <!-- ログインフォーム -->
        <div class="login">
            <form action="" method="POST">
            <div class="form-group">
                    <label for="email">メールアドレス<span class="text-danger">(必須)</span></label>
                    <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="メールアドレス" required>
                    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                </div>
                <div class="form-group">
                    <label for="password">パスワード<span class="text-danger">(必須)</span></label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="パスワード" required>
                </div>
                <button type="submit" name="login" id="loginBtn" class="btn btn-lg btn-block mt-4">ログインする</button>
            </form>
        </div>
        <!-- ログインフォーム -->
        <!-- パスワード再発行　パスワードを忘れた方用のページへ遷移 -->
        <div class="register_link">
            <a href="../users/password_reissue.php">パスワードをお忘れの方はこちら</a>
        </div>
        <!--　登録がまだの場合 -->
        <div class="register_link">
            <a href="../users/index.php">登録がまだの方はこちら</a>
        </div>
        <!--　登録がまだの場合 -->
        <?php endif; ?>
    </div>
    </main>
    <?php include("../component/footer.php"); ?>
    <script src="../assets/js/index.js"></script>
    <script>
    // ユーザー登録に成功した場合のアラート
    const params = (new URL(document.location)).searchParams;
    const registerParams = params.get('register');
    if(registerParams === 'success') {
        alert('ユーザー登録に成功しました。ログイン情報を入力してログインしてください。');
    }
    </script>
</body>
</html>