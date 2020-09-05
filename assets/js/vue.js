new Vue({
    el: '#app',
    data: {
        isEnter: false,
        files: []
    },
    methods: {
        dragEnter() {
            this.isEnter = true;
        },
        dragleave() {
            this.isEnter = false;
        },
        dropFile() {
            console.log(event.dataTransfer.files);
            this.files = [...event.dataTransfer.files]
            this.isEnter = false;
        }
    },
})