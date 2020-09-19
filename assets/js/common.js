'use strict'
{
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

}
