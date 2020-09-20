<?php
session_start();
require_once('../config/dbconnect.php');

//SESSIONで保持したデータ
$user = $_SESSION["user"];
$id = $_SESSION["user"]["id"];

$item_data = $_SESSION["data"];
$item_name = $_SESSION['item_name'];
$item_price = $_SESSION['item_price'];
$item_description = $_SESSION['item_description'];
$item_stock = $_SESSION['item_stock'];

$errors = [];

//悪意のあるスクリプトを入力されたときにXSSを防ぐための方法にhtmlspecialchars関数を使用
//よく使用するため関数化
function h($str){
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

//item_idをselectする処理(全ての条件に合致したデータのみを取得)
$sql = "SELECT item_id FROM items WHERE item_name = ? AND item_price = ? AND item_description = ? AND item_stock = ?";
$data = [];
$data[] = $item_name;
$data[] = $item_price;
$data[] = $item_description;
$data[] = $item_stock;
$stmt = $dbh->prepare($sql);
$stmt->execute($data);
$items = $stmt->fetch();
$item_id = $items["item_id"];


// ファイルがあれば処理実行
if(isset($_FILES["upload_image"])){
// アップロードされたファイル件を処理
for($i = 0; $i < count($_FILES["upload_image"]["name"]); $i++){
    //ファイルが存在しない場合はreturn falseで処理を終了 : 画像登録処理では1つ目のアイテム以外は必須ではないためこの処理が必要
    if(!file_exists($_FILES["upload_image"]["tmp_name"][$i])) {
        return FALSE;
    }
    // is_uploaded_fileでPOSTでアップロードされたファイルかどうかを調べる
    if(is_uploaded_file($_FILES["upload_image"]["tmp_name"][$i])){
        // move_uploaded_fileでアップロードしたファイルを新しい場所に移動する 今回はローカルのimagesフォルダに格納する
        move_uploaded_file($_FILES["upload_image"]["tmp_name"][$i], "../items/images/" . $_FILES["upload_image"]["name"][$i]);
    }
    //画像ファイルのinsert処理
    $stmt = $dbh->prepare("INSERT INTO item_images(item_id, image_name) VALUES (?, ?)");
    $data = [];
    $data[] = $item_id;
    $data[] = $_FILES["upload_image"]["name"][$i];
    $stmt->execute($data);
    $message = "商品情報の登録に成功しました！";
    header('Location: ../items/index.php');
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
        <div class="item-detail__links">
            <div class="login-user">現在ログイン中のユーザー : <?php echo h($id); ?></div>
            <?php if($user): ?>
            <a href="../items/index.php" class="to__register-page">商品登録へ</a>
            <?php endif; ?>
            <a href="../users/cart.php" class="to__cart-page">カートへ</a>
            <a href="../items/item_list.php" class="back-to__item-list">商品一覧へ</a>
        </div>
        <h2>追加で商品画像を登録する</h2>
        <p class="text-primary"><?php echo h($db_success_message); ?></p>
        <div class="register-images">
            <p class="text-danger">商品画像を登録する</p>
            <form action="../items/images_uploaded.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="images">画像1</label>
                <input type="file" class="form-control form-control-file" name="upload_image[]" multiple required>
            </div>
            <div class="form-group">
                <label for="images">画像2</label>
                <input type="file" class="form-control form-control-file" name="upload_image[]" multiple>
            </div>
            <div class="form-group">
                <label for="images">画像3</label>
                <input type="file" class="form-control form-control-file" name="upload_image[]" multiple>
            </div>
            <div class="form-group">
                <label for="images">画像4</label>
                <input type="file" class="form-control form-control-file" name="upload_image[]" multiple>
            </div>
            <div class="form-group">
                <label for="images">画像5</label>
                <input type="file" class="form-control form-control-file" name="upload_image[]" multiple>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <p class="text-primary"><?php echo h($message); ?></p>
            </form>
        </div>
    </main>
    <?php include("../component/footer.php"); ?>

    <script src="../assets/js/index.js"></script>
</body>
</html>