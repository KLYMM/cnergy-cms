<!DOCTYPE html>

@extends('layout.app')

@section('css')
@endsection

@vite([
    'resources/sass/components/image-uploader.scss',
    'resources/sass/components/tags-input.scss',
    'resources/sass/pages/photonews.scss',
    'resources/js/pages/photoNewsUploader.js'
]);

@push('head')
@endpush

@section('body')
    @if ($method === 'edit')
        <form action="{{ route('video.update', $news->id) }}" method="post" enctype="multipart/form-data">
        @else
            <form action="{{ route('video.store') }}" method="post" enctype="multipart/form-data">
    @endif
    <section id="basic-vertical-layouts">
        @csrf
        <div class="d-flex justify-content-between gap-2">
            <div class="col-8 ">
                <div class="card">
                    <div class="card-header">
                        <span class="h4 text-capitalize">{{ $method }} News</span>
                    </div>

                    <div class="card-body d-flex flex-column gap-2">
                        @if ($method === 'edit')
                            @method('PUT')
                        @endif
                        <div class="form-group">
                            <label for="title" class="mb-2">Title</label>
                            <input type="text" class="form-control" id="title" name="title"
                                placeholder="Enter Title" required
                                @if ($method === 'edit') value="{{ $news->title }}" @endif />
                        </div>

                        <div class="form-group">
                            <label for="synopsis" class="form-label mb-2">Synopsis</label>
                            <textarea name="synopsis" placeholder="Enter Synopsis" class="form-control" id="synopsis" cols="30" rows="3"
                                required>
@if ($method === 'edit')
{{ $news->synopsis }}
@endif
</textarea>
                        </div>

                        <div class="form-group">
                            <label for="content" class="form-label">Content</label>
                            <textarea name="content" class="my-editor form-control" id="content" cols="30" rows="20">
@if ($method === 'edit')
{{ $news->content }}
@endif
</textarea>
                        </div>

                        <div class="form-group">
                            <label for="video" class="mb-2">Video Embed Code (HTML)</label>
                            @if ($method === 'edit')
                                <input type="hidden" name="video_id" value="{{ $news->news_videos[0]['id'] }}">
                            @endif
                            <textarea name="video" id="video" placeholder="Paste Embed Code Here" class="form-control" cols="30"
                                rows="3" required>
@if ($method === 'edit')
{{ $news->news_videos[0]['video'] }}
@endif
</textarea>
                        </div>
                    </div>
                </div>
            </div>
            @include('components.other-settings-news', [
                'type' => 'video',
            ])
        </div>
    </section>
    </form>
@endsection

@section('javascript')
    <script src="{{ asset('assets/extensions/toastify-js/src/toastify.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        {{-- Get Tags Checked --}}
        $(document).ready(function() {
            $('#tags').select2({
                width: '100%',
                multiple: true,
                tags: true,
                tokenSeparators: [',', '\n'],
                // maximumSelectionSize: 12,
                // minimumInputLength: 2,
                placeholder: "Select Tags",
                allowClear: true,

                ajax: {
                    url: "{{ route('tagging.search') }}",
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                    global: false,
                    cache: true,
                    data: function(params) {
                        return {
                            _token: '{{ csrf_token() }}',
                            search: params.term
                        };
                    },
                    processResults: function(response) {
                        return {
                            results: response
                        };

                    },
                    success: function(response) {
                        console.log('response', response)
                    },
                    error: function(error) {
                        console.log(error, 'error get tags');
                    },
                }
            });
        });

        $(document).ready(function() {
            {{--  Auto Save Tags --}}
            $('#tags').change(function() {
                var tags = $('#tags').val();
                $.ajax({
                    type: "POST",
                    url: "{{ route('tagging.edit') }}",
                    @if ($method === 'edit')
                        data: {
                            id: {{ $news->id }},
                            tags: tags,
                            _token: @json(csrf_token())
                        },
                    @else
                        data: {
                            tags: tags,
                            _token: @json(csrf_token())
                        },
                    @endif
                    success: function(data) {
                        console.log(data);
                    },
                    error: function(error) {
                        console.log(error, 'error auto save inline');
                    },
                });
            });
        });


        $(document).ready(function() {
            $('.close-modals-button').on('click', function() {
                $('#image-bank').removeClass("show").css("display", "none")
            });
            $("#keyword").select2({
                tags: true,
                placeholder: 'Select Keywords',
                allowClear: true,
                tokenSeparators: [',', '\n'],
                ajax: {
                    url: "{{ route('keyword.index') }}",
                    dataType: 'json',
                    delay: 250,
                    processResults: function({
                        data
                    }) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    id: item.id,
                                    text: item.keywords
                                }
                            })
                        }
                    }
                }
            });
        });
    </script>

    <script src="https://cdn.tiny.cloud/1/vadmwvgg5mg6fgloc7tol190sn52g6mrsnk0dguphazk7y41/tinymce/5/tinymce.min.js"
        referrerpolicy="origin"></script>

    <script>
        var editor_config = {
            path_absolute: "/",
            selector: "textarea.my-editor",
            plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table contextmenu directionality",
                "emoticons template paste textcolor colorpicker textpattern"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | imagebank | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link media",
            relative_urls: false,
            file_browser_callback: function(field_name, url, type, win) {
                var x = window.innerWidth || document.documentElement.clientWidth || document
                    .getElementsByTagName(
                        'body')[0].clientWidth;
                var y = window.innerHeight || document.documentElement.clientHeight || document
                    .getElementsByTagName('body')[0].clientHeight;

                var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
                if (type == 'image') {
                    cmsURL = cmsURL + "&type=Images";
                } else {
                    cmsURL = cmsURL + "&type=Files";
                }

                tinyMCE.activeEditor.windowManager.open({
                    file: cmsURL,
                    title: 'Filemanager',
                    width: x * 0.8,
                    height: y * 0.8,
                    resizable: "yes",
                    close_previous: "no"
                });
            },
            setup: (editor) => {
                editor.ui.registry.addButton('imagebank', {
                    text: 'Image Bank',
                    icon: 'image',
                    onAction: () => {
                        const {
                            id
                        } = editor;
                        const img_uploader_modal = document.getElementById("image-bank");
                        img_uploader_modal.classList.add("show");
                        img_uploader_modal.style.display = "block";
                        img_uploader_modal.setAttribute("tinymce-image-bank", true);
                        img_uploader_modal.setAttribute("target-mce", id);
                    }
                });
            },
        };
        tinymce.init(editor_config);
    </script>

    <script src="{{ asset('assets/extensions/choices.js/public/assets/scripts/choices.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-element-select.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>

    <script>
        $(function() {
            $('input')
                .on('change', function(event) {
                    var $element = $(event.target);
                    var $container = $element.closest('.example');

                    if (!$element.data('tagsinput')) return;

                    var val = $element.val();
                    if (val === null) val = 'null';
                    var items = $element.tagsinput('items');

                    $('code', $('pre.val', $container)).html(
                        $.isArray(val) ?
                        JSON.stringify(val) :
                        '"' + val.replace('"', '\\"') + '"'
                    );
                    $('code', $('pre.items', $container)).html(
                        JSON.stringify($element.tagsinput('items'))
                    );
                })
                .trigger('change');
        });
    </script>
    {{--
    <script src="/path/to/cdn/jquery.slim.min.js"></script>
    <script src="/path/to/js/jquery.dateandtime.js"></script>

    <script>
        $(function() {
            $('.example').dateAndTime();
        });
    </script> --}}
@endsection
