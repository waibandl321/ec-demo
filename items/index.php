<?php
session_start();
require_once('../config/dbconnect.php');

if(!$_SESSION["login"]) {
    header('Location: ../users/login.php');
    exit;
}
//SESSIONで保持したuser情報
$user = $_SESSION["user"];
$id = $_SESSION["user"]["id"];
$submit = $_POST["submit"];

$errors = [];

// 新規登録の商品情報の取得
$item_name = $_POST['item_name'];
$item_price = $_POST['item_price'];
$item_description = $_POST['item_description'];
$item_stock = $_POST['item_stock'];
//セッションに情報保持
$_SESSION["item_name"] = $item_name;
$_SESSION["item_price"] = $item_price;
$_SESSION["item_description"] = $item_description;
$_SESSION["item_stock"] = $item_stock;

// 画像の取得
$item_thumbnail = $_FILES['item_thumbnail']['name'];

$item_id = $_GET['code'];
//パラメーターに値が存在する場合は更新処理
if(isset($_GET['code'])) {
    //item_idに紐付く商品データの取得
    $sql = "SELECT * FROM items WHERE item_id = $item_id"; 
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $edit_item = $stmt->fetch();
}

//更新情報の取得
$edit_name = $_POST["edit_name"];
$edit_price = $_POST["edit_price"];
$edit_description = $_POST["edit_description"];
$edit_stock = $_POST["edit_stock"];


//もし商品情報がすでに存在する場合にはupdate処理
if(isset($_GET['code'])) {
    //更新ボタンが押されたときの処理
    if(isset($_POST["edit"])) {
        $sql = "UPDATE items SET item_name = ?, item_price = ?, item_description = ?, item_stock = ? WHERE item_id = $item_id";
        $data = [];
        $data[] = $edit_name;
        $data[] = $edit_price;
        $data[] = $edit_description;
        $data[] = $edit_stock;
        $stmt = $dbh->prepare($sql);
        $stmt->execute($data);
        $message = "商品情報の更新に成功しました";
        header('Location: ../users/mypage.php');
    }
} else {
    //商品情報が存在しない場合には新規insert
    if(!empty($_POST)) {
        $stmt = $dbh->prepare("INSERT INTO items(seller_id, item_name, item_price, item_description, item_stock, item_thumbnail) VALUES (?, ?, ?, ?, ?, ?)");
        $data = [];
        $data[] = $id;
        $data[] = $item_name;
        $data[] = $item_price;
        $data[] = $item_description;
        $data[] = $item_stock;
        $data[] = $item_thumbnail;
        $_SESSION["data"] = $data;
        $stmt->execute($data);
        $message = "商品情報の登録に成功しました！";
        header('Location: ../items/index.php');
    } else {
        $message = "商品を登録してください！";
    }
}

//画像の登録処理
if(!empty($_FILES['item_thumbnail']['name'])) {
 $upload_dir = '../items/images/';
 $uploaded_file = $upload_dir . basename($_FILES['item_thumbnail']['name']);
 move_uploaded_file($_FILES['item_thumbnail']['tmp_name'], $uploaded_file);
};



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
        <div class="item-registration__outer">
        <div class="item-registration">
            <div class="login-user">現在ログイン中のユーザー : <?php echo $id; ?></div>
            <h2>商品登録ページ</h2>
            <p class="text-primary"><?php echo $db_success_message; ?></p>
            <p class="text-danger"><?php echo $message; ?></p>
            <!-- 新規登録フォーム -->
            <?php if(!isset($_GET["code"])) : ?>
            <form method="POST" action="" enctype="multipart/form-data">
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
                <input type="submit" class="btn btn-primary" name="submit" value="保存する">
                </form>
                <?php else : ?>
                <!-- 更新フォーム -->
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="item-name">商品名</label>
                        <input type="text" name="edit_name" class="form-control" id="itemName" value="<?php echo $edit_item["item_name"]; ?>" placeholder="商品名を入力してください" required>
                    </div>
                    <div class="form-group">
                        <label for="item-price">商品価格</label>
                        <input type="number" name="edit_price" class="form-control" id="itemPrice" value="<?php echo $edit_item["item_price"]; ?>" placeholder="商品価格を入力してください" required>
                    </div>
                    <div class="form-group">
                        <label for="item-description">商品説明文</label>
                        <textarea class="form-control" name="edit_description" id="itemDescription" placeholder="商品説明文を入力してください" required><?php echo $edit_item["item_description"]; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="item-stock">在庫数</label>
                        <input type="number" name="edit_stock" class="form-control" id="itemStock" value="<?php echo $edit_item["item_stock"]; ?>" placeholder="在庫数を入力してください" required>
                    </div>
                    <input type="submit" class="btn btn-primary" name="edit" value="更新する">
                </form>
                <?php endif; ?>
                <a href="../items/item_list.php">商品一覧ページへ</a>
        </div>
        <!-- 商品画像追加 -->
        <?php if(isset($submit)) : ?>
        <a href="../items/images_uploaded.php">追加で画像を登録する</a>
        <?php endif; ?>
    </div>
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