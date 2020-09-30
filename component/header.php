<?php
$signin_link = 'サインイン';
$signout_message = 'サインアウト';

$user = $_SESSION["user"];
$id = $_SESSION["user"]["id"];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
    <link rel="stylesheet" href="http://waibandl321.xsrv.jp/ec/assets/css/style.css">
</head>
<body>
<header>
    <div class="header-top">
        <div class="header-top__menu-icon">
        <a href="http://waibandl321.xsrv.jp/ec/index.php">ECサイト</a>
        <!-- <i class="fas fa-bars"></i> -->
        </div>
        <!-- <div class="header-top__logo">
            <a href="../users/index.php"><img src="../images/logo.jpg" alt="ロゴ画像"></a>
        </div> -->
        <!-- <div class="header-top__search-bar">
            <form action="" method="POST">
                <div class="form-group header-top__form-group">
                    <input type="text" name="search" class="form-control" id="itemName" placeholder="検索キーワードを入力してください" required>
                    <input type="submit" value="探す">
                </div>
            </form>
        </div> -->
        <div class="header-top__links">
            <div class="header-top__item_list header-top__link">
                <a href="http://waibandl321.xsrv.jp/ec/items/item_list.php">商品一覧</a>
            </div>
            <div class="header-top__account-information header-top__link">
                <a href="http://waibandl321.xsrv.jp/ec/users/mypage.php">マイページ</a>
            </div>
            <?php if(!$_SESSION["login"]) : ?>
            <div class="header-top__account-information header-top__link">
                <a href="http://waibandl321.xsrv.jp/ec/users/login.php">ログイン</a>
            </div>
            <div class="header-top__account-information header-top__link">
                <a href="http://waibandl321.xsrv.jp/ec/users/index.php">ユーザー新規登録</a>
            </div>
            <?php else : ?>
            <div class="header-top__account-information header-top__link">
                <a href="http://waibandl321.xsrv.jp/ec/users/logout.php">ログアウト</a>
            </div>
            <?php endif; ?>
            <a class="header-top__cart-link header-top__link" href="http://waibandl321.xsrv.jp/ec/users/cart.php">
                <i class="fas fa-cart-plus"></i>カート
            </a>
        </div>
    </div>
    <!-- <div class="header-bottom">
        <div class="header-bottom__delivery-location">
            <div class="delivery-location__icon">
            <i class="fas fa-location-arrow"></i>
            </div>
            <div class="delivery-location__text">
                配達先<br>
                東京 : 品川区
            </div>
        </div>
        <ul class="header-bottom__service-links">
            <li class="header-bottom__service-link"><a href="">本日のお買い得情報</a></li>
            <li class="header-bottom__service-link"><a href="">カスタマーサービス</a></li>
            <li class="header-bottom__service-link"><a href="">ギフトカード</a></li>
        </ul>
        <div class="header-bottom__notice">
            <a href="">COVIDー19に対する弊社の対応</a>
        </div>
    </div> -->
</header>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
<script src="../assets/js/index.js"></script>
</body>
</html>
