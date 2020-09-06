'use strict'
{
//JS
/*========================================
ドラッグアンドドロップイベントの処理
========================================*/
const someFileArea = document.querySelectorAll('.form-control-file');

someFileArea.forEach((fileArea) => {
    fileArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        console.log("dropped");
        fileArea.classList.add('enter');
    });
    fileArea.addEventListener('dragleave', (e) => {
        e.preventDefault();
        console.log("leaved");
        fileArea.classList.remove('enter');
    });
    fileArea.addEventListener('drop', (e) => {
        console.log(e.dataTransfer.files);
        fileArea.classList.remove('enter');
    });
});
/*========================================
ヘッダーの高さを取得して、メインコンテンツの上部の余白を指定する
========================================*/
const header = document.querySelector('header');
const footer = document.querySelector('footer');
const headerHeight = header.offsetHeight + 50;
const footerHeight = footer.offsetHeight + 50;

const jsContainer = document.querySelector('.container');
jsContainer.style.marginTop = headerHeight + "px";
jsContainer.style.paddingBottom = footerHeight + "px";

/*========================================
メニューアイコンのクリックイベントでサイドメニューを開く
========================================*/


/*========================================
ルーペ拡大部分の表示領域設定
========================================*/


// jQuery
$(function(){
    $('.js-item-detail').matchHeight();
});

}