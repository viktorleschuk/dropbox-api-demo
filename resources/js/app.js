/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',

    data: {
        files: {},
        file: {},

        previewLink: null,

        loading: false,
        previewLoading: false,

        availableFormats: ['ai', 'avi', 'css', 'csv', 'dbf', 'doc', 'dwg', 'exe', 'fla', 'html', 'iso', 'jpeg', 'jpg', 'js', 'json', 'mp3', 'mp4', 'pdf', 'png', 'ppt', 'psd', 'rtf', 'svg', 'txt', 'xls', 'sml', 'zip']
    },

    methods: {


        fetchFiles() {
            this.loading = true;
            axios.get('api/files').then(result => {
                this.loading = false;
                this.files = result.data.data;
            }).catch(error => {
                console.log(error.response);
                $.toast({
                    title: 'Error',
                    content: error.response.data.message,
                    type: 'error',
                    delay: 3000
                });
                this.loading = false;
            });

        },

        getIcon(file) {

            return this.availableFormats.indexOf(file.extension) > -1 ? file.extension : 'default';
        },

        openPreviewModal(file) {

            this.file = file;
            this.previewLoading = true;
            axios.get('api/files/preview', {params: {path: file.path}}).then(result => {
                this.previewLoading = false;
                this.previewLink = result.data.path;
            }).catch(error => {
                console.log(error.response);
                $.toast({
                    title: 'Error',
                    content: error.response.data.message,
                    type: 'error',
                    delay: 3000
                });
                this.previewLoading = false;
            });
            $('#fileModal')
                .modal('show')
                .on('hidden.bs.modal', (e) => {

                    this.previewLink = null;
                });
        },

        openFileSelector() {

            $('.file-input')[0].click();
        },

        addFile() {

            this.attachment = this.$refs.file.files[0];
            this.submitForm();
        },

        submitForm() {

            this.formData = new FormData();
            this.formData.append('file', this.attachment);
            this.loading = true;

            axios.post('api/files', this.formData, {headers: {'Content-Type': 'multipart/form-data'}})
                .then(response => {
                    this.loading = false;
                    $.toast({
                        title: 'Notice!',
                        content: 'File successfully added.',
                        type: 'info',
                        delay: 3000
                    });

                    this.files.push(response.data.data);
                })
                .catch(error => {

                    this.loading = false;
                    console.log(error.response);
                    $.toast({
                        title: 'Error',
                        content: error.response.data.message,
                        type: 'error',
                        delay: 3000
                    });
                });
        },

        deleteFile(file) {

            axios.delete('api/files', {params: {path: file.path}})
                .then(response => {
                    $.toast({
                        title: 'Notice!',
                        content: 'File successfully deleted.',
                        type: 'info',
                        delay: 3000
                    });
                    $('#fileModal').modal('hide');
                    this.files.splice(this.files.indexOf(file), 1);
                })
                .catch(error => {

                    $.toast({
                        title: 'Error',
                        content: error.response.data.message,
                        type: 'error',
                        delay: 3000
                    });
                    console.log(error.response);
                });
        },

        isVideo() {
            const videoExtensions = ['mov', 'mp4'];
            return videoExtensions.indexOf(this.file.extension) > -1;
        },

        isPhoto() {
            const photoExtensions = ['jpeg', 'jpg', 'png'];
            return photoExtensions.indexOf(this.file.extension) > -1;
        },

        isElse() {

            return ! isVideo && ! isPhoto;
        }
    },
    mounted() {
        // console.log('mounted');
        this.fetchFiles();
    }
});
