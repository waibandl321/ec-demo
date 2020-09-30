<?php
session_start();
require_once('../config/dbconnect.php');
require_once('../config/functions.php');

//セッションで保持したuserデータ
$user = $_SESSION["user"];
$user_id = $_SESSION["user"]["id"];

$quantity = $_POST["quantity"];
$item_id = $_POST["item_id"];

//パラメータの値を取得
$code = $_GET['code'];
//パラメーターに付与された商品ID(code)を取得 =>商品ID(code)に紐つく商品データを取得し、商品詳細情報のブロックに表示させる
if(isset($_GET['code'])) {
    //紐付く商品データの取得
    $sql = "SELECT * FROM items WHERE item_id = :code";
    $stmt = $dbh->prepare($sql);
    //指定された変数名にパラメータをバインドする
    $stmt->bindParam(':code', $code, PDO::PARAM_INT);
    $stmt->execute();
    $item_info = $stmt->fetchAll();

    //カートから商品IDに紐づく商品の数量を取得
    foreach((array)$item_info as $item) {
        $id = $item["item_id"];
        //商品idとユーザーidを条件に指定して、cartテーブルから数量(quantity)を取得
        $sql = "SELECT * FROM cart WHERE item_id = :item_id AND user_id = :user_id";
        $stmt = $dbh->prepare($sql);
        //指定された変数名にパラメータをバインドする
        $stmt->bindParam(':item_id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        //数量の取得
        $cart_quantity = $stmt->fetch()["quantity"];
    }
    //商品IDに紐づく追加で登録された複数の商品画像を取得する処理
    foreach((array)$item_info as $item) {
        $id = $item["item_id"];
        $sql = "SELECT * FROM item_images WHERE item_id = :item_id";
        $stmt = $dbh->prepare($sql);
        //指定された変数名にパラメータをバインドする
        $stmt->bindParam(':item_id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $item_images = $stmt->fetchAll();
    }
}

//同じ商品がカート内に存在する場合の処理
if(isset($cart_quantity)) {
    $same_item_message = "同じアイテムがカート内に存在しています";
    if($_POST["count_updated_method"]){
        $add_quantity = $_POST["update_quantity"];
        $sum_quantity = $cart_quantity + $add_quantity;
        $sql = "UPDATE cart SET quantity = :sum_quantity WHERE item_id = :item_id AND user_id = :user_id";
        $stmt = $dbh->prepare($sql);
        //指定された変数名にパラメータをバインドする
        $stmt->bindParam(':sum_quantity', $sum_quantity, PDO::PARAM_INT);
        $stmt->bindParam(':item_id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
    }
} elseif(isset($_POST["cart_in"])) {
    //ログインユーザーが存在しない場合はログインページにリダイレクト
    if(!isset($_SESSION["user"])) {
        header('Location: ../users/login.php');
    }
    //insertされたことを確認するための<input type="hidden" name="finish_insert">からPOSTされたデータを取得
    $finish_insert = $_POST["finish_insert"];
     //同じ商品がカートに存在しない場合の処理 cartテーブルへのinsert
     $stmt = $dbh->prepare("INSERT INTO cart(user_id, item_id, quantity) VALUES (?, ?, ?)");
     $data = [];
     $data[] = $user_id;
     $data[] = $item_id;
     $data[] = $quantity;
     $stmt->execute($data);
     //insertが完了したら処理を終了するためにheader関数で同じページに飛ばして、cartテーブルから商品情報の取得が完了した状態にする
     header("Location: ../items/item_detail.php?code={$item_id}");
} else {
    //cartに紐付く商品がなく、cartテーブルにinsertもされていない状態の処理
    $cart_in_message = '商品をカートに追加してください';
}

// 商品にレビューが投稿された際の処理
$post_content = $_POST["post_content"];
$post_name = "名無さん";
$post_item_id = $_POST["post_item_id"];
//投稿データのinsert処理
if(isset($_POST["post_submit"])) {
    $stmt = $dbh->prepare("INSERT INTO posts(item_id, post_name, post_content) VALUES (?, ?, ?)");
    $post_data = [];
    $post_data[] = $post_item_id;
    $post_data[] = $post_name;
    $post_data[] = $post_content;
    $stmt->execute($post_data);
    header('Location: ../items/item_detail.php?code=' . $post_item_id);
} 

// 商品レビューの取得
if(isset($_GET['code'])) {
    $sql = "SELECT * FROM posts WHERE item_id = ?";
    $stmt = $dbh->prepare($sql);
    $data = [];
    $data[] = $code;
    $stmt->execute($data);
    $post_data = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品詳細</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/drift/drift-basic.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include("../component/header.php"); ?>
    <div class="container">
        <div class="item-page">
            <h2>商品ページ</h2>
            <p class="text-primary font-weight-bold"><?php echo h($message); ?></p>
            <p class="text-primary font-weight-bold"><?php echo h($same_item_message); ?></p>
            <?php foreach((array)$item_info as $item) : ?>
                <div class="item-detail__wrap">
                    <div class="item-detail__images">
                        <div class="item-detail__images__main zoom__lens__container">
                            <img src="../items/images/<?php echo h($item["item_thumbnail"]); ?>" alt="商品のサムネイル画像" class="zoom__item js_main_image">
                            <div class="zoom_lens"></div>
                        </div>
                        <ul class="other-image__items">
                        <!-- その他の画像の取得 -->
                        <?php for($i = 0; $i < count($item_images); $i++) : ?>
                            <li class="other-image__item">
                                <img src="../items/images/<?php echo h($item_images[$i]["image_name"]); ?>" alt="他の商品画像が入ります" class="thumbnail">
                            </li>
                        <?php endfor; ?>
                        </ul>
                        <div class="zoom__area">
                            <img src="">
                        </div>
                    </div>
                    <div class="item-detail__block">
                        <!-- 商品詳細 -->
                        <p class="item-detail__block__id">
                            <span>商品id</span> : <?php echo h($item["item_id"]); ?>
                        </p>
                        <p class="item-detail__block__name">
                            <span>商品名</span> : <?php echo h($item["item_name"]); ?>
                        </p>
                        <p class="item-detail__block__price">
                            <span>価格</span> : <?php echo h($item["item_price"]); ?>
                        </p>
                        <p class="item-detail__block__description">
                            <span>商品説明</span> : <?php echo h($item["item_description"]); ?>
                        </p>
                        <p class="item-detail__block__stock">
                            <span>在庫数</span> : <?php echo h($item["item_stock"]); ?>
                        </p>
                        <p class="item-detail__block__created-at">
                            <span>出品日時</span> : <?php echo h($item["created_at"]); ?>
                        </p>
                        <form action="" method="POST">
                            <div class="select_quantity">
                                <span>個数を選択 : </span>
                                <!-- 　商品詳細ページで表示しているアイテムがカートに存在 or 存在しない場合にselectボックスの表示を切り替える -->
                                <?php if(!$cart_quantity) : ?>
                                <!-- もしカート内に同じアイテムが存在しない場合に表示するselectボックス-->
                                <select name="quantity">
                                    <?php for($i=1; $i<=20; $i++){
                                        echo "<option value=".$i.">".$i."</option>";
                                    }?>
                                </select>
                                <?php else : ?>
                                <!-- もしカート内に同じアイテムが存在する場合に表示するselectボックス-->
                                <!-- name="update_quantity"で値の上書きのためのデータを取得 -->
                                <select name="update_quantity">
                                    <?php for($i=1; $i<=20; $i++){
                                        echo "<option value=".$i.">".$i."</option>";
                                    }?>
                                </select>
                                <?php endif; ?>
                                <!--　　条件分岐ここまで 　-->
                            </div>
                            <div class="cart-in__wrap">
                                <div>
                                <!-- 　「カートに追加」の表示ボタンを切り替えるための条件分岐 　-->
                                <?php if(!$cart_quantity) : ?>
                                    <!-- もしカート内に商品がない場合に表示させるボタン -->
                                    <input type="submit" name="cart_in" value="カートに入れる" class="cart-in__bottom">
                                    <!-- 商品のinsertが終わったことを表現する<input type="hidden"> -->
                                    <input type="hidden" name="finish_insert">
                                    <!-- insertされる商品のitem_idをpostする -->
                                    <input type="hidden" value="<?=$item["item_id"]?>" name="item_id">
                                <?php else : ?>
                                    <!-- もしカート内に商品がある場合に表示させるボタン（値の上書き用） -->
                                    <input type="submit" name="count_updated_method" value="カートに入れる" class="cart-in__bottom">
                                <?php endif; ?>
                                <!--　　条件分岐ここまで 　-->
                                </div>
                                <div class="back-to-item-list">
                                    <a href="../items/item_list.php" class="back-to-item-list___link">商品一覧へ戻る</a>
                                </div>
                            </div>
                        </form>
                        <p class="text-primary"><?php echo h($finish_insert_message); ?></p>
                        <p class="text-primary"><?php echo h($select_message); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <hr>
        <div class="comments_and_review__wrap">
            <div class="comments_and_review__inner">
                <div class="post_reviews_link" id="post"><p>+レビューを投稿する</p></div>
                <div class="view_reviews_link" id="review"><p id="post_link">レビュー一覧を見る</p></div>
            </div>
            <div class="post_reviews" id="postBody">
                <form action="../items/item_detail.php" method="post" class="review_form">
                    <div class="form-group">
                        <label for="post_content">投稿内容(500文字以内でご記入ください)</label><br>
                        <textarea type="text" name="post_content" class="form-control" required></textarea>
                    </div>
                    <div class="form-group submit">
                        <!-- insertされるpostに紐づくitem_idを格納する -->
                        <input type="hidden" value="<?=$item["item_id"]?>" name="post_item_id">
                        <input type="submit" value="投稿する" name="post_submit">
                    </div>
                </form>
            </div>
            <div class="view_reviews" id="reviewBody">
                <div class="view_reviews__list" id="reviewList">
                  <?php foreach($post_data as $post) : ?>
                  <div class="view_reviews__item">
                    <h5 class="post_name">投稿者 : <?php echo h($post["post_name"]); ?></h5>
                    <div class="post_item">
                        <p class="post_item_desc" id="postText"><?php echo h($post["post_content"]); ?></p>
                    </div>
                  </div>
                  <hr>
                  <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php include("../component/footer.php"); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.matchHeight/0.7.2/jquery.matchHeight-min.js" integrity="sha512-/bOVV1DV1AQXcypckRwsR9ThoCj7FqTV2/0Bm79bL3YSyLkVideFLE3MIZkq1u5t28ke1c0n31WYCOrO01dsUg==" crossorigin="anonymous"></script>
    <!-- <script src="../assets/js/elevatezoom-master/jquery.elevatezoom.js"></script> -->
    <!-- <script src="../assets/js/drift/Drift.min.js"></script> -->
    <script src="../assets/js/index.js"></script>
    <script>
        /*========================================
        ルーペ拡大部分の表示領域設定
        ========================================*/
        const zoomArea = document.querySelector('.zoom__area');
        const zoomImage = zoomArea.querySelector('img');
        var size = 200;
        var scale = 400 / size;

        Array.prototype.forEach.call(document.querySelectorAll('.zoom__lens__container'), (container) => {
            console.log(container);
            const lens = container.querySelector('.zoom_lens');
            const img = container.querySelector('img');
            //要素にmouseenterした時のzoomAreaの処理
            container.addEventListener('mouseenter', () => {
                const image = container.querySelector('img');
                // Zoom要素を表示させる
                zoomArea.classList.add('active');
                lens.classList.add('active');
                //表示領域の画像のsrcに取得したimageのsrcを付与
                zoomImage.setAttribute('src', image.src);
                //offsetWidthで要素のレイアウト幅を整数として返し、scale値を乗算してzoom比率を算出
                zoomImage.style.width = (image.offsetWidth * scale) + 'px';
            });
            // マウスが離れた時にzoomエリアを非表示にする
            container.addEventListener('mouseleave', () => {
                zoomArea.classList.remove('active');
                lens.classList.remove('active');
            });

            let xmax, ymax;
            // hover要素内の画像が読み込まれた時にhover範囲のheightとwidthを取得する
            img.addEventListener('load', () => {
                xmax = img.offsetWidth - size;
                ymax = img.offsetHeight - size;
            });
            //container(hover対象要素)でマウスが動いたときの処理
            container.addEventListener('mousemove', (e) => {
                // getBoundingClientRect()で要素に関連付くCSSボーダーボックスを取得
                const rect = container.getBoundingClientRect();
                // pageXでイベント発生場所の水平位置を取得
                const mouseX = e.pageX;
                // pageYでイベント発生場所の垂直位置を取得
                const mouseY = e.pageY;
                //対象要素のtop leftからの座標点を取得
                const positionX = rect.left + window.pageXOffset;
                const positionY = rect.top + window.pageYOffset;
                //コンテナの左上からの相対座標
                const offsetX = mouseX - positionX;
                const offsetY = mouseY - positionY;

                let left = offsetX - (size / 2);
                let top = offsetY - (size / 2);

                if(left > xmax){
                    left = xmax;
                }
                if(top > ymax){
                    top = ymax;
                }
                if(left < 0){
                    left = 0;
                }
                if(top < 0){
                    top = 0;
                }
                lens.style.top = top + 'px';
                lens.style.left = left + 'px';
                zoomImage.style.marginLeft = -(left * scale) + 'px';
                // zoomImage.style.marginTop = -(top * scale) + 'px';
            });
        });

        /*==========================================================
        商品詳細ページのサムネイル画像にhoverした時にメインイメージを切り替える
        ==========================================================*/
        let thumbnails = [];
        thumbnails = document.querySelectorAll('.thumbnail');
        const mainImage = document.querySelector('.js_main_image');
        const mainImageSrc = mainImage.getAttribute('src');
        thumbnails.forEach((thumbnail) => {
            thumbnail.addEventListener('mouseover', () => {
                mainImage.setAttribute('src', thumbnail.src);
            });
            thumbnail.addEventListener('mouseout', () => {
                mainImage.setAttribute('src', mainImageSrc);
            });
        });

        /*==========================================================================
        商品詳細ページの投稿用のフォーム、投稿一覧の表示切り替え + レビュー一覧のスクロール判定処理
        ==========================================================================*/
        $(function(){
            // レビューが存在する場合にのみリンクを表示させる
            if($('#postText').off().text()) {
                $('#post_link').addClass('active');
            }
            // レビューの投稿フォームとレビュー一覧を表示切り替えをslideToggleで実装
            $('#post').off().on('click', () => {
                $('#postBody').slideToggle('slow');
            });
            $('#post_link').off().on('click', () => {
                $('#reviewBody').slideToggle('slow');
                // 商品詳細ページのレビュー蘭の高さが300pxを超えたときにスクロール処理 => scrollクラスを付与
                if($('#reviewList').outerHeight() > 250) {
                    $('.view_reviews__list').addClass('scroll');
                };
            });
        });
    </script>
</body>
</html>