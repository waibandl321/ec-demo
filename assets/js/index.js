'use strict'
{
//JS
/*========================================
ヘッダーとフッターの高さを取得して、メインコンテンツの上部の余白を指定する
========================================*/
let header = document.querySelector('header');
let footer = document.querySelector('.footer');
let headerHeight = header.clientHeight + 50;
let footerHeight = footer.clientHeight + 50;

let jsContainer = document.querySelector('.container');
jsContainer.style.marginTop = headerHeight + "px";
jsContainer.style.paddingBottom = footerHeight + "px";

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



}

