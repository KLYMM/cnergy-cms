@extends('layout.app')

@section('css')
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" rel="stylesheet" />
    <style type="text/css">
        .bootstrap-tagsinput {
            width: 100%;
        }

        .bootstrap-tagsinput .tag {
            margin-right: 2px;
            color: white !important;
            background-color: #38E54D;
            padding: .2em .6em .3em;
            font-size: 100%;
            font-weight: 700;
            vertical-align: baseline;
            border-radius: .25em;
        }

        .search_select_box select {
            width: 100%;
            ;
        }

        a[aria-expanded=true] .bi-chevron-down {
            display: none;
        }


        a[aria-expanded=false] .bi-chevron-up {
            display: none;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
@endsection

@push('head')
@endpush

@section('body')
    @if ($method === 'edit')
        <form action="{{ route('static-page.update', $static->id) }}" method="post" enctype="multipart/form-data">
        @else
            <form action="{{ route('static-page.store') }}" method="post" enctype="multipart/form-data">
    @endif
    <x-page-heading title="Create New Page" subtitle=" " />
    <section id="basic-vertical-layouts">
        @csrf
        <div class="d-flex justify-content-between gap-2">
            <div class="col-12 ">
                <div class="card">
                    <div class="card-body d-flex flex-column gap-2">
                        @if ($method === 'edit')
                            @method('PUT')
                        @endif

                        <div class="form-group">
                            <label for="title" class="mb-2">Page Title</label>
                            <input type="text" class="form-control" id="title" name="title"
                                placeholder="Enter Title " required
                                @if ($method === 'edit') value="{{ $static->title }}" @endif />
                        </div>
                        <div class="form-group">

                            <label for="slug" class="form-label mb-2">Slug</label>
                            <textarea name="slug" class="form-control" id="slug" cols="30" rows="3" required
                                placeholder="Enter Slug">
@if ($method === 'edit')
{{ $static->slug }}
@endif
</textarea>
                        </div>
                        <div class="form-group">
                            <label for="content" class="form-label">Content</label>
                            <textarea name="content" class="my-editor form-control" id="content" cols="30" rows="10" required>
                                                @if ($method === 'edit')
{{ $static->content }}
@endif
                                             </textarea>
                        </div>
                        <div class="form-group">
                            <input name="isActive" class="form-check-input" type="checkbox" id="isActive" 
                                @if ($method === 'edit' and $static->is_active == '1') checked @endif />
                            <label class="form-check-label" for="isActive">On</label>
                        </div>
                        <div class="d-flex justify-content-end gap-3 mt-3">
                            <a href="{{ route('static-page.index') }}" class="btn btn-light" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Back to Table Rome">Back</a>

                            <button class="btn btn-primary" name="save" type="submit" data-bs-toggle="tooltip"
                                value="publish" data-bs-placement="top" title="Create Role">Save
                            </button>
                        </div>

                    </div>
                </div>
            </div>


        </div>

        </div>
    </section>
    </form>
@endsection


@section('javascript')
    <script src="https://cdn.tiny.cloud/1/vadmwvgg5mg6fgloc7tol190sn52g6mrsnk0dguphazk7y41/tinymce/4/tinymce.min.js"
        referrerpolicy="origin"></script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

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
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
            relative_urls: false,
            file_browser_callback: function(field_name, url, type, win) {
                var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName(
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
            }
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
@endsection
