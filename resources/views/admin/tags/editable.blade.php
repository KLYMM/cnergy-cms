@extends('layout.app')

@push('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" rel="stylesheet" />
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
    <x-page-heading title="Table Tag" subtitle="View and Manage Tag Data" />
    <section class="section">
        <div class="card col-md-7">

            <div class="card-header">
                <span class="h4 text-capitalize">{{ $method }} Tag</span>
            </div>

            <div class="card-body d-flex flex-column gap-2">
                @if ($method === 'edit')
                    <form action="{{ route('tag-management.update', $tag->id) }}" method="post">
                        @method('PUT')
                    @else
                        <form action="{{ route('tag-management.store') }}" method="POST">
                @endif
                @csrf
                <div class="col-md-12">

                    <div class="form-group">
                        <label for="basicInput">Tag Name</label>
                        <input type="text" class="form-control" id="tags" placeholder="Enter Tag Name"
                            name="tag" @if ($method === 'edit') value="{{ $tag->tags }}" @endif>
                    </div>

                    <div class="form-group">
                        <label for="basicInput">Meta Title</label>
                        <input type="text" class="form-control" id="title" placeholder="Enter Meta Title"
                            name="title" @if ($method === 'edit') value="{{ $tag->meta_title }}" @endif>
                    </div>

                    <div class="form-group">
                        <label for="basicInput">Meta Description</label>
                        <textarea name="description" class="form-control" id="description" cols="30" rows="3"
                            placeholder="Enter Meta Description">
                            @if ($method === 'edit')
{{ $tag->meta_description }}
@endif
                        </textarea>
                    </div>

                    <div class="form-group">
                        <label for="basicInput">Meta Keywords</label>
                        <input name="keywords" id="keywords" type="text"
                            @if ($method === 'create') placeholder="Enter Meta Keyword" @endif class="form-control"
                            data-role="tagsinput"
                            @if ($method === 'edit') value="{{ $tag->meta_keywords }}" @endif />
                    </div>

                    @if ($method === 'edit')
                        <div class="form-group">
                            <label for="basicInput">Status</label>
                            <div class="form-group">

                                <input class="form-check-input" type="radio" name="is_active"
                                    @if ($tag->is_active == 1) checked @endif value="1">
                                <label class="form-check-label">On</label>

                                <input class="form-check-input" type="radio" name="is_active"
                                    @if ($tag->is_active == 0) checked @endif value="0">
                                <label class="form-check-label">Off</label>

                            </div>
                        </div>
                    @endif

                    <div class="d-flex justify-content-end gap-3 mt-3">

                        <a href="{{ route('tag-management.index') }}" class="btn btn-light" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Back to Table Tag">Back
                        </a>

                        <button class="btn btn-primary" type="submit" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Create Tag Data">Save
                        </button>

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
