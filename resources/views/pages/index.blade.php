@extends('layouts.app')

@section('container')

    <div class="file-library pt-4 mb-4">
        <div class="spinner-grow text-primary" v-if="loading"></div>
        <div class="row mr-3 ml-3">
            <div class="col-lg-2 col-md-3 col-sm-4 mb-4" v-for="file in files" @click="openPreviewModal(file)">
                <div class="h-100">
                    <a href="#"><img class="card-img-top" :src="'/images/icons/' + getIcon(file) + '.png'" alt=""></a>
                    <a href="#" style="word-break: break-word;">@{{ file.filename }}</a>
                    <a href="#">(@{{ file.extension }})</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="fileModal" tabindex="-1" role="dialog" aria-labelledby="file" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@{{ file.filename }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="spinner-grow text-primary" v-if="previewLoading"></div>
                    <video v-if="isVideo() && previewLink" width="100%" controls autoplay>
                        <source :src="previewLink" type="video/mp4">
                    </video>

                    <img width="100%" v-if="isPhoto && previewLink" :src="previewLink" :alt="file.filename">

                    <div class="row" v-if="previewLink">
                        <a class="btn btn-primary btn-lg btn-block mr-3 ml-3" :href="previewLink" role="button">Download File</a>

                        {{--<a :href="previewLink" type="button" class="btn btn-primary btn-lg btn-block">Download File</a>--}}
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" @click="deleteFile(file)">&times;</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    {{--<button type="button" class="btn btn-primary">Save changes</button>--}}
                </div>
            </div>
        </div>
    </div>

@endsection