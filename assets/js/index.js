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
const zoomArea = document.querySelector('.zoom__area');
const zoomImage = zoomArea.querySelector('img');
var size = 172;
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
        zoomImage.style.marginTop = -(top * scale) + 'px';
    });
})


// jQuery
$(function(){
    $('.js-item-detail').matchHeight();
});

}