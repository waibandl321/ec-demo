<?php
session_start();
require_once('dbconnect.php');


if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $sql = 'SELECT * FROM user_image';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function findUserByEmail($dbh, $image_id) {
    $sql = 'SELECT * FROM user_images WHERE image_id = ?';
    $stmt = $dbh->prepare($sql);
    $data[] = $image_id;
    $stmt->execute($data);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
if (!empty($_POST)) {
    $image = findImageById($dbh, $_POST["email"]);
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
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>画像表示テスト</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container user-registration">
        <h2>画像表示テスト</h2>
        <p class="text-primary font-weight-bold"><?php echo $db_success_message; ?></p> 
        <div class="images">
                <div class="image">
                <?php echo $images; ?>
                </div>
        </div>
        </form>
    </div>
</body>
</html>