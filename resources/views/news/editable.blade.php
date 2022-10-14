@extends('layout.app')

@section('css')
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/image-uploader.css') }}" />
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
    <x-page-heading title="Table News" subtitle="View and Manage News Data" />
    @if ($method === 'edit')
        <form action="{{ route('news.update', $news->id) }}" method="post" enctype="multipart/form-data">
        @else
            <form action="{{ route('news.store') }}" method="post" enctype="multipart/form-data">
    @endif
    <section id="basic-vertical-layouts">
        @if ($method === 'edit')
            @method('PUT')
        @endif
        @csrf
        @if ($method === 'edit')
            <input type="hidden" value="{{ json_encode($news->news_paginations) }}" id="news_paginations">
        @endif
        <div class="d-flex justify-content-between gap-2">
            <div class="col-7 ">
                <div class="d-flex justify-content-end gap-3 mt-3">
                    <a href="{{ route('news.index') }}" class="btn btn-light" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="Back to Table Rome">Back</a>

                    <button class="btn btn-primary" name="save" type="submit" data-bs-toggle="tooltip" value="publish"
                        data-bs-placement="top" title="Create Role">Save
                    </button>
                </div>
                <div class="card" id="card_content">
                    <div class="card-header"><span class="h4 text-capitalize card-header-text">{{ $method }}
                            News</span>
                    </div>
                    <div class="card-body d-flex flex-column gap-2">
                        <div class="form-group">
                            <label for="title" class="mb-2">Title</label>
                            <input type="text" class="form-control" id="title" name="title[]"
                                placeholder="Enter Title " required
                                @if ($method === 'edit') value="{{ $news->title }}" @endif />
                        </div>

                        <div class="form-group">
                            <label for="synopsis" class="form-label mb-2">Synopsis</label>
                            <textarea name="synopsis" class="form-control" id="synopsis" cols="30" rows="3" required
                                placeholder="Enter Synopsis">@if ($method === 'edit'){{ $news->synopsis }}@endif</textarea>
                        </div>

                        <div class="form-group" id="content_box">
                            <label for="content" class="form-label">Content</label>
                            <textarea name="content[]" class="my-editor form-control" id="content" cols="30" rows="10" required>
                                    @if ($method === 'edit')
                                        {{ $news->content }}
                                    @endif
                            </textarea>
                        </div>
                    </div>
                </div>
                <div id="other_page"></div>
                <span class="btn btn-outline-secondary my-3 w-100" id="add_page_button"><i
                        class="bi bi-plus-circle"></i>&nbsp;
                    Add New Page</span>

            </div>
            <div class="col-5">
                <div class="card">
                    <div class="card-body">
                        <a data-bs-toggle="collapse" href="#satu">
                            <span class="h6">Status & Visibility</span>
                            <i class="bi bi-chevron-down fs-6" style="float:right"></i>

                        </a>
                        <hr />
                        <div class="collapse" id="satu">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-5">
                                        <label for="basicInput">Publish Status</label>
                                    </div>
                                    <div class="col-md-5">
                                        <input class="form-check-input" type="radio" value="1" name="isPublished"
                                            @if ($method === 'edit' and $news->is_published == '1') checked @endif />
                                        <label class="form-check-label">
                                            On
                                        </label>
                                        <input class="form-check-input" type="radio" value="0" name="isPublished"
                                            @if ($method === 'edit' and $news->is_published == '0') checked @endif />
                                        <label class="form-check-label">
                                            Off
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-5">
                                        <label for="publishedAt" class="mb-2">Schedule</label>
                                    </div>
                                    <div class="col-md-7">
                                        <input type="date" class="form-control" id="publishedAt" name="publishedAt"
                                            placeholder="dd-mm-yyyy"
                                            @if ($method === 'edit') value="{{ date('Y-m-d', strtotime($news->published_at)) }}" @endif />

                                    </div>
                                </div>
                            </div>
                        </div>

                        <a data-bs-toggle="collapse" href="#dua">
                            <i class="bi bi-chevron-down fs-6" style="float:right"></i>
                            <span class="h6">Picture</span>
                        </a>
                        <hr />
                        <div class="collapse" id="dua">
                            <div class="form-group">
                                @if (isset($news))
                                    <x-image-uploader :item="$news" />
                                @else
                                    <x-image-uploader />
                                @endif
                            </div>
                        </div>

                        <a data-bs-toggle="collapse" href="#sembilan">
                            <i class="bi bi-chevron-down fs-6" style="float:right"></i>
                            <span class="h6">Category</span>
                        </a>
                        <hr />
                        <div class="collapse" id="sembilan">
                            <div class="form-group">
                                <div class="row">
                                    <fieldset class="form-group">
                                        <select data-live-search="true" class="w-100 selectpicker" name="category"
                                            id="category">
                                            @if ($method === 'create')
                                                <option value="" disabled selected>Select Category</option>
                                            @endif
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    @if ($method === 'edit' and $category->id === $news->category_id) selected @endif>
                                                    {{ $category->category }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <br><a href="{{ route('category.create') }}" class="mb-2">Add Category</a>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="form-group">

                            </div>
                        </div>


                        <a data-bs-toggle="collapse" href="#tiga">
                            <i class="bi bi-chevron-down fs-6" style="float:right"></i>
                            <span class="h6">Tags</span>
                        </a>
                        <hr />
                        <div class="collapse" id="tiga">
                            <div class="form-group">
                                <div class="row">
                                    <select name="tags[]" class="choices form-select multiple-remove"
                                        multiple="multiple" id="tags" required>
                                        <optgroup label="Tags">
                                            @foreach ($tags as $id => $tag)
                                                <option id="{{ $id }}" value="{{ $tag->id }}"
                                                    @if ($method === 'edit' and $tag->news()->find($news->id)) selected @endif>{{ $tag->tags }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <a data-bs-toggle="collapse" href="#tujuh">
                            <i class="bi bi-chevron-down fs-6" style="float:right"></i>
                            <span class="h6">Description</span>
                        </a>
                        <hr />
                        <div class="collapse" id="tujuh">
                            <div class="form-group">
                                <textarea name="description" class="form-control" id="description" cols="30" rows="3" required
                                    placeholder="Enter description">
@if ($method === 'edit')
{{ $news->description }}
@endif
</textarea>

                            </div>
                        </div>

                        <!--<a data-bs-toggle="collapse"  href="#delapan">
                                                                <i class="bi bi-chevron-down fs-6" style="float:right"></i>
                                                                <span class="h6">Author</span>
                                                            </a>
                                                            <hr />
                                                            <div class="collapse" id="delapan">
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <select name="author[]" class="choices form-select multiple-remove"
                                                                        multiple="multiple"
                                                                        id="author">
                                                                            <optgroup label="Author">


                                                                            </optgroup>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>-->

                        <a data-bs-toggle="collapse" href="#empat">
                            <i class="bi bi-chevron-down fs-6" style="float:right"></i>
                            <span class="h6">Other Settings</span>
                        </a>
                        <hr />
                        <div class="collapse" id="empat">
                            <div class="form-group">
                                <label class="mb-2">Keyword</label><br>
                                <input name="keywords" id="keywords" type="text" required
                                    placeholder="Enter Keyword" class="w-100 form-control" data-role="tagsinput"
                                    @if ($method === 'edit') value="{{ $news->keywords }}" @endif />
                            </div>
                            <!--<div class="form-group">
                                                                        <label class="mb-2">Photographer</label><br>
                                                                        <select name="photographer[]" class="choices form-select multiple-remove"
                                                                        multiple="multiple"
                                                                        id="photographer">
                                                                            <optgroup label="photographer">

                                                                            </optgroup>
                                                                        </select>
                                                            </div>
                                                            <div class="form-group">
                                                                        <label class="mb-2">Contributor</label><br>
                                                                        <select name="contributor[]" class="choices form-select multiple-remove"
                                                                        multiple="multiple"
                                                                        id="contributor">
                                                                            <optgroup label="contributor">

                                                                            </optgroup>
                                                                        </select>

                                                            </div>-->
                                                            <div class="form-group">
                                                                <label class="mb-2">Photographer</label><br>
                                                                <select name="photographers[]"
                                                                        class="choices form-select multiple-remove"
                                                                        multiple="multiple"
                                                                        id="photographer">
                                                                    <optgroup label="photographer">
                                                                        @foreach($users as $user)
                                                                            @if($user->roles->role === 'Photographer')
                                                                                <option
                                                                                    @if ($method === 'edit' and is_null(json_decode($news->photographers))==false)
                                                                                    @if(in_array($user->uuid,json_decode($news->photographers)))
                                                                                    selected
                                                                                    @endif
                                                                                    @endif
                                                                                    value="{{$user->uuid}}">{{$user->name}}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    </optgroup>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="mb-2">Contributor</label><br>
                                                                <select name="contributor" class="choices form-select multiple-remove"
                                                                        multiple="multiple"
                                                                        id="contributor" disabled>
                                                                    <optgroup label="contributor">
                                                                        @foreach($contributors as $contributor)
                                                                            <option value="{{$contributor->name}}"
                                                                                    selected>{{$contributor->name}}</option>
                                                                        @endforeach
                                                                    </optgroup>
                                                                </select>
                                                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label for="publishedAt" class="mb-2">Type</label>
                                    <fieldset class="form-group">
                                        <select name="types" class="form-select" id="type">
                                            @if ($method === 'create')
                                                <option value="" disabled selected>Select Type</option>
                                            @endif
                                            @foreach ($types as $type)
                                                <option value="{{ $type }}"
                                                    @if ($method === 'edit' and $type === $news->types) selected @endif>{{ ucwords($type) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                        <a data-bs-toggle="collapse" href="#lima">
                            <i class="bi bi-chevron-down fs-6" style="float:right"></i>
                            <span class="h6">Content Type</span>
                        </a>
                        <hr />
                        <div class="collapse" id="lima">
                            <div class="form-group">

                                <input name="isCurated" class="form-check-input" type="checkbox" id="isCurated"
                                    @if ($method === 'edit' and $news->is_curated == '1') checked @endif />
                                <label class="form-check-label" for="isCurated">Curated/Feed</label>
                                <br>
                                <input name="isAdultContent" class="form-check-input" type="checkbox"
                                    id="isAdultContent" @if ($method === 'edit' and $news->is_adult_content == '1') checked @endif />
                                <label class="form-check-label" for="isAdultContent">Adult Content(18+)</label>
                                <br>
                                <input name="isVerifyAge" class="form-check-input" type="checkbox" id="isVerifyAge"
                                    @if ($method === 'edit' and $news->is_verify_age == '1') checked @endif />
                                <label class="form-check-label" for="isVerifyAge">Verify Age(18+)</label>
                                <br>
                                <input name="isHomeHeadline" class="form-check-input" type="checkbox"
                                    id="isHomeHeadline" @if ($method === 'edit' and $news->is_home_headline == '1') checked @endif />
                                <label class="form-check-label" for="isHomeHeadline">Home Headline</label>
                                <br>
                                <input name="isCategoryHeadline" class="form-check-input" type="checkbox"
                                    id="isCategoryHeadline" @if ($method === 'edit' and $news->is_category_headline == '1') checked @endif />
                                <label class="form-check-label" for="isCategoryHeadline">Category
                                    Headline</label>
                                <br>
                                <input name="isAdvertorial" class="form-check-input" type="checkbox" id="isAdvertorial"
                                    @if ($method === 'edit' and $news->is_advertorial == '1') checked @endif />
                                <label class="form-check-label" for="isAdvertorial">Advertorial</label>
                                <br>
                                <input name="isSeo" class="form-check-input" type="checkbox" id="isSeo"
                                    @if ($method === 'edit' and $news->is_seo == '1') checked @endif />
                                <label class="form-check-label" for="isSeo">SEO</label>
                                <br>
                                <input name="isDisableInteractions" class="form-check-input" type="checkbox"
                                    id="isDisableInteractions" @if ($method === 'edit' and $news->is_disable_interactions == '1') checked @endif />
                                <label class="form-check-label" for="isDisableInteractions">Disable
                                    Interactions</label>
                                <br>
                                <input name="isBrandedContent" class="form-check-input" type="checkbox"
                                    id="isBrandedContent" @if ($method === 'edit' and $news->is_branded_content == '1') checked @endif />
                                <label class="form-check-label" for="isBrandedContent">Branded Content</label>
                                <br>
                                <input name="isHeadline" class="form-check-input" type="checkbox" id="isHeadline"
                                    @if ($method === 'edit' and $news->is_headline == '1') checked @endif />
                                <label class="form-check-label" for="isHeadline">Headline</label>
                                <br>
                                <input name="editorPick" class="form-check-input" type="checkbox" id="editorPick"
                                    @if ($method === 'edit' and $news->editor_pick == '1') checked @endif />
                                <label class="form-check-label" for="editorPick">Editor Pick</label>
                            </div>
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

    <script src="{{ asset('assets/js/pages/image-uploader.js') }}"></script>
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
