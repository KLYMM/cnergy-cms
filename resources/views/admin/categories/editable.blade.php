@extends('layout.app')

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
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
    </style>
@endpush

@section('body')
    <x-page-heading title="Table Category" subtitle="View and Manage Category Data" />

    <section class="section">

        <div class="card col-md-7">

            <div class="card-header">
                <span class="h4 text-capitalize">{{ $method !== 'edit' ? 'Create' : 'Edit' }} Category</span>
            </div>

            <div class="card-body d-flex flex-column gap-2">
                @if ($method === 'edit')
                    <form action="{{ route('category.update', $post->id) }}" method="post">
                        @method('PUT')
                    @else
                        <form action="{{ route('category.store') }}" method="post" id="basicform" data-parsley-validate="">
                @endif
                @csrf
                <div class="col-md-12">
                    @if ($method !== 'edit')
                        @if ($parent)
                            <input type="hidden" name="parent_id" value="{{ $parent->id }}">
                            <label for="basicInput" class="mb-2 fw-bold">Parent Category : {{ $parent->category }}</label>
                        @endif
                    @endif
                    <div class="form-group">
                        <label for="basicInput">Category Name</label>
                        <input type="text" class="form-control" id="category" placeholder="Category" name="category"
                            @if ($method === 'edit') value="{{ $post->category }}" @endif>
                    </div>

                    <div class="form-group">
                        <label for="basicInput">Meta Title</label>
                        <input type="text" class="form-control" id="metaTitle" placeholder="Enter Meta Title"
                            name="meta_title" @if ($method === 'edit') value="{{ $post->meta_title }}" @endif>
                    </div>

                    <div class="form-group">
                        <label for="basicInput">Meta Keywords</label>
                        <input type="text" class="form-control" id="metaKeyword" placeholder="Enter Meta Keyword"
                            name="meta_keywords" data-role="tagsinput"
                            @if ($method === 'edit') value="{{ $post->meta_keywords }}" @endif>
                    </div>

                    <div class="form-group">
                        <label for="basicInput">Meta Description</label>
                        <textarea cols="30" rows="3" class="form-control" id="metaDescription" placeholder="Enter Meta Description"
                            name="meta_description">
                            @if ($method === 'edit'){{ $post->meta_description }}@endif
                        </textarea>
                    </div>

                    <div class="form-group">

                        <label for="basicInput">Type Category</label>

                        <div class="form-group">
                            <li class="d-inline-block me-2 mb-1">
                                <div class="form-check">

                                    <div class="checkbox">
                                        <input type="checkbox" name="types[]" value="news" class="form-check-input"
                                            @if ($method === 'edit') @if (in_array('news', $post->types)) checked @endif
                                            @endif>
                                        <label for="checkbox1">News</label>
                                    </div>

                                    <div class="checkbox">
                                        <input type="checkbox" name="types[]" value="photonews" class="form-check-input"
                                            @if ($method === 'edit') @if (in_array('photonews', $post->types)) checked @endif
                                            @endif>
                                        <label for="checkbox1">Photo News</label>
                                    </div>

                                    <div class="checkbox">
                                        <input type="checkbox" name="types[]" value="video" class="form-check-input"
                                            @if ($method === 'edit') @if (in_array('video', $post->types)) checked @endif
                                            @endif>
                                        <label for="checkbox1">Video</label>
                                    </div>

                                </div>
                            </li>
                        </div>

                    </div>

                    @if ($method === 'edit')
                        <input type="hidden" name="parent_id" value="{{ $post->parent_id }}">

                        <div class="form-group">

                            <label for="basicInput">Status</label>

                            <div class="form-group">

                                <input class="form-check-input" type="radio" name="is_active"
                                    @if ($post->is_active == 1) checked @endif value="1">
                                <label class="form-check-label">On</label>

                                <input class="form-check-input" type="radio" name="is_active"
                                    @if ($post->is_active == 0) checked @endif value="0">
                                <label class="form-check-label">Off</label>

                            </div>

                        </div>
                    @endif

                    <div class="d-flex justify-content-end gap-3 mt-3">

                        <a href="{{ route('category.index') }}" class="btn btn-light" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Back to Table Category">Back</a>

                        <button class="btn btn-primary" type="submit" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Create Category">Save</button>

                    </div>

                </div>
                </form>
            </div>
        </div>
    </section>
@endsection
@push('javascript')
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
@endpush
