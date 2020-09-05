'use strict'
{
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
})



}