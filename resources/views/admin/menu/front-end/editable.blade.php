@extends('layout.app')

@section('body')
    <x-page-heading title="FrontEnd Menu Config" subtitle="Manage frontend menu for user" />
    <section class="section">
        <div class="card col-md-7">

            <div class="card-header">
                <span class="h4 text-capitalize">{{ $method }} Front End Menu</span>
            </div>

            <div class="card-body d-flex flex-column gap-2">
                <input type="hidden" id="methodType" value="{{ $method }}">
                @if ($method === 'edit')
                    <form action="{{ route('front-end-menu.update', $fe_menu->id) }}" method="post">
                        @method('PUT')
                    @else
                        <form action="{{ route('front-end-menu.store') }}" method="post">
                @endif
                @csrf
                <div class="col-md-12">
                    @if ($method !== 'edit')
                        @if ($parent)
                            <input type="hidden" name="parent_id" value="{{ $parent->id }}">
                            <label for="basicInput" class="mb-2 fw-bold">Parent Menu : {{ $parent->title }}</label>
                        @endif
                    @endif

                    <div class="form-group">
                        <label for="basicInput" class="mb-2">Menu Name</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="basicInput"
                            name="title" placeholder="Enter menu name"
                            @if ($method === 'edit') value="{{ $fe_menu->title }}" @endif />
                        @error('title')
                            <div class="invalid-feedback">
                                <i class="bx bx-radio-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- <div class="form-group">
                        <label for="basicInput" class="mb-2">Position</label>
                        <select class="form-select" multiple aria-label="multiple select example" name="position[]">
                            <option value="header"
                                @if ($method === 'edit') @if (in_array('header', json_decode($fe_menu->position, true)))
                                selected @endif
                                @endif>Header</option>
                            <option
                                @if ($method === 'edit') @if (in_array('footer', json_decode($fe_menu->position, true)))
                                selected @endif
                                @endif
                                value="footer">Footer</option>
                        </select>
                    </div> --}}

                    {{-- Position --}}
                    <div class="form-group">
                        <label for="basicInput" class="mb-2">Position</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input name="position[]" class="form-check-input" type="checkbox" id="inlineCheckbox1"
                                    value="header"
                                    @if ($method === 'edit') @if (in_array('header', json_decode($fe_menu->position, true)))
                                checked @endif
                                    @endif>
                                <label class="form-check-label" for="inlineCheckboxHeader">Header</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input name="position[]" class="form-check-input" type="checkbox" id="inlineCheckbox2"
                                    value="footer"
                                    @if ($method === 'edit') @if (in_array('footer', json_decode($fe_menu->position, true)))
                                checked @endif
                                    @endif>
                                <label class="form-check-label" for="inlineCheckboxFooter">Footer</label>
                            </div>
                        </div>
                    </div>

                    {{-- Type --}}
                    <div class="form-group">
                        <label for="basicInput" class="mb-2">Type</label>
                        <select name="type" class="form-select" aria-label="Default select example"
                            onchange="getType(this)">
                            <option value="link"
                                @if ($method === 'edit') @if ($fe_menu->type == 'link')
                                selected @endif
                                @endif>Link</option>
                            <option value="label"
                                @if ($method === 'edit') @if ($fe_menu->type == 'label')
                                selected @endif
                                @endif>Label</option>
                            <option value="anchor"
                                @if ($method === 'edit') @if ($fe_menu->type == 'anchor')
                                selected @endif
                                @endif>Anchor</option>
                        </select>
                    </div>

                    {{-- URL --}}
                    <div class="form-group" id="form-group-url">
                        <label for="basicInput" class="mb-2">URL</label>
                        <input name="slug" id="" type="text" class="form-control" placeholder="URL"
                            @if ($method === 'edit') value="{{ $fe_menu->slug }}" @endif>
                    </div>

                    {{-- Target --}}
                    <div class="form-group" id="form-group-target">
                        <label for="basicInput" class="mb-2">Target</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input name="target" class="form-check-input" type="radio" id="inlineRadio1"
                                    value="Same Tab"
                                    @if ($method === 'edit') @if ($fe_menu->target == 'Same Tab') checked @endif
                                    @endif>
                                <label class="form-check-label" for="inlineRadioSameTab">Same Tab</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input name="target" class="form-check-input" type="radio" id="inlineRadio2"
                                    value="New Window"
                                    @if ($method === 'edit') @if ($fe_menu->target == 'New Window') checked @endif
                                    @endif>
                                <label class="form-check-label" for="inlineRadioNewWindow">New Window</label>
                            </div>
                        </div>
                    </div>


                    <div class="d-flex justify-content-end gap-3 mt-3">

                        <a href="{{ route('front-end-menu.index') }}" class="btn btn-light" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Back to Table Menu">Back
                        </a>

                        <button class="btn btn-primary" type="submit" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Create Menu">Save
                        </button>

                    </div>
                </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@section('javascript')
    <script>
        var target = document.getElementById("form-group-target");
        var url = document.getElementById("form-group-url");

        function getType(selectObject) {
            var value = selectObject.value;

            if (value == 'anchor') {
                target.style.display = 'none';
                url.style.display = 'block';
            }
            if (value == 'label') {
                target.style.display = 'none';
                url.style.display = 'none';
            }
            if (value == 'link') {
                target.style.display = 'block';
                url.style.display = 'block';
            }
        }
        var method = document.getElementById("methodType").value;
        if (method === 'edit') {
            var value = "{{ $method == 'edit' ? $fe_menu->type:'' }}";
            console.log(value);
            if (value == 'anchor') {
                target.style.display = 'none';
                url.style.display = 'block';
            }
            if (value == 'label') {
                target.style.display = 'none';
                url.style.display = 'none';
            }
            if (value == 'link') {
                target.style.display = 'block';
                url.style.display = 'block';
            }
        }
    </script>
@endsection
