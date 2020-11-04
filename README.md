# PHPを使用したECサイト制作  
フレームワークの使用無  

## 実装した機能  
・ユーザー登録(画像登録有)  
・ログイン  
・ログイアウト  
・パスワード再設定  
・商品登録(画像登録有)  
・商品情報編集  
・カート機能(合計金額計算、在庫計算)  
・検索機能  
・決済画面表示(Stripe)  

## ディレクトリ説明
assets: css,jsフォルダ  
component : ヘッダー、フッター、サイドナビのパーツ  
config : dbconnect.php(データベースとの接続情報), functions.php(共通変数をfunctions.phpに定義)  
images : 画像を保存  
items : 商品登録(index),商品一覧(item_list),商品詳細ページ(item_detail),商品削除(item_destroy),商品画像登録(images_uploaded)  
users : 会員登録(index), ログイン(login), ログアウト(logout), サインイン(signin), パスワード再発行(password_reissue), マイページ(mypage), カート機能(cart)  
index.php : トップページ  
 
## テーブル設計
![image](https://user-images.githubusercontent.com/60844799/98106648-d9c2e900-1edc-11eb-95e8-c4f9680f394d.png)  

## ユーザーの使用ケースを想定
![image](https://user-images.githubusercontent.com/60844799/98106922-21e20b80-1edd-11eb-9ae5-c7643050bb29.png)  
