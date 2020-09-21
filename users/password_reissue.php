<?php
session_start();
require_once('../config/dbconnect.php');

//悪意のあるスクリプトを入力されたときにXSSを防ぐための方法にhtmlspecialchars関数を使用
//よく使用するため関数化
function h($str){
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}


/*=========================================
■パスワードの再発行のためにユーザにメールを送信する
========================================= */
//1. 入力されたメールアドレスがデータベースに登録されているかをチェック
$email = $_POST["email"];
$submit = $_POST["submit"];
if(isset($submit)) {
    $sql = "SELECT * FROM users WHERE email = ?";
    $data = [];
    $data[] = $email;
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $data = $stmt->fetch();
    $send_email = $data["email"];
    $user_id = $data["id"];
    //2. メールアドレスが登録されている場合
    if(!empty($data)) {
        $err_msg = "データの取得に成功";
        //セキュリティ性の高いランダムな文字列を生成
        $passResetToken = sha1(uniqid(rand(), true));
        //パスワードリセットトークンをデータベースに保存
        if(isset($passResetToken)) {
            $sql = "UPDATE users SET pass_reset_token = ? WHERE id = ?";
            $token_data = [];
            $token_data[] = $passResetToken;
            $token_data[] = $user_id;
            $stmt = $dbh->prepare($sql);
            $stmt->execute($token_data);
        }
        //生成した文字列をURLのパラメーターとして設定
        $passResetUrl = "http://localhost:8000/users/password_reissue.php?passResetCode=$passResetToken";
        //生成したurlを記載したメールをユーザーに送信する処理
        //言語設定
        mb_language("Japanese");
        //文字コード設定
        mb_internal_encoding("UTF-8");
        //送信内容の設定
        $email = "waibandl321@gmail.com";
        //件名
        $subject = "パスワード再発行";
        // 本文
        $body = $passResetUrl;
        //送信先アドレス
        $to = $send_email;
        $header = "From: $email\nReply-To: $email\n";
        //mb_send_mail()関数を使用してでメール送信
        mb_send_mail($to, $subject, $body, $header);
        $mail_success_msg = "メールの送信が完了しました";
    } else {
        $err_msg = "データの取得に失敗しました";
    }
}

/*===========================================================
■メールに記載されたURLをクリックした際に、パラメーターを取得して再設定を行う
=========================================================== */
$getPassResetToken = $_GET['passResetCode'];
//このパラメータを持つユーザー情報を取得
if(isset($getPassResetToken)) {
    $sql = "SELECT * FROM users WHERE pass_reset_token = ?";
    $reset_data = [];
    $reset_data[] = $getPassResetToken;
    $stmt = $dbh->prepare($sql);
    $stmt->execute($reset_data);
    $reset_user_data = $stmt->fetch();
}
//リセットユーザーの情報が存在する場合にパスワードのアップデート処理
$update_password = $_POST["update_password"];
if(isset($_POST["update"])){
    $sql = "UPDATE users SET password = ? WHERE pass_reset_token = ?";
    $update_data = [];
    //暗号化の強度を高める
    $update_data[] = password_hash($update_password, PASSWORD_DEFAULT);
    $update_data[] = $getPassResetToken;
    $stmt = $dbh->prepare($sql);
    $stmt->execute($update_data);
    $update_password_success_msg = "パスワードの再設定が完了しました";
}
//パスワードが上書き保存されたらログインページへ遷移
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>トップページ</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
    <link rel="stylesheet" href="../assets/css/style.css">
    
</head>
<body>
<?php include("../component/header.php"); ?>
  <main class="sign_out">
    <div class="container">
        <div class="sign_out__wrap">
            <h4>パスワード再発行</h4>
            <p class="text-danger"><?php echo h($err_msg); ?></p>
            <p class="text-dark"><?php echo h($send_email); ?></p>
            <p class="text-primary"><?php echo h($mail_success_msg); ?></p>
            <p class="text-primary"><?php echo h($pupdate_password_success_msg); ?></p>
            <?php echo h($passResetToken); ?>
            <?php if(isset($reset_user_data)) : ?>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="email">新しいパスワードを入力してください。</label><br>
                    <input type="password" name="update_password">
                </div>
                <div class="form-group">
                    <input type="submit" name="update">
                </div>
            </form>
            <?php else : ?>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="email">メールアドレスを入力してください。</label><br>
                    <input type="email" name="email">
                </div>
                <div class="form-group">
                    <input type="submit" name="submit">
                </div>
            </form>
            <?php endif; ?>
        </div>
     </div>
    </main>
    <?php include("../component/footer.php"); ?>
    <script src="../assets/js/index.js"></script>
</body>
</html>