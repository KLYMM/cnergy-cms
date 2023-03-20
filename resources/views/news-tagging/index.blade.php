@extends('layout.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/menu.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
@endpush

@section('body')
    <x-page-heading title="News Tagging" subtitle="View and Manage News Tagging Data" />
    <div class="card ">
        <div class="card-header d-flex align-items-center justify-content-between"><span class="h4">News Search</span>
        </div>
        <div class="card-body">
            <form class="row g-3" method="GET">
                <div class="col-md-4">
                    <label for="inputState" class="form-label">Schedule</label>
                    <div class="input-group">
                        <input type="date" class="form-control" name="startDate">
                        &nbsp;&nbsp;&nbsp;<p>To</p>&nbsp;&nbsp;&nbsp;
                        <input type="date" class="form-control" name="endDate">
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="inputTitle" class="form-label">Title</label>
                    <input name="inputTitle" id="title" placeholder="Title" type="text" class="form-control">
                </div>
                <div class="col-md-4">
                    <label for="inputCategory" class="form-label">Category</label>
                    <input name="inputCategory" id="category" placeholder="Category" type="text" class="form-control">
                </div>
                <div class="col-md-4">
                    <label for="inputHeadline" class="form-label">Reporter</label>
                    <select name="reporter" id="inputHeadline" class="form-select">
                        <option value="" selected>All</option>
                        @foreach ($reporters as $r)
                            <option value="{{ $r->uuid }}">{{ $r->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="inputHeadline" class="form-label">Editor</label>
                    <select name="editor" id="inputHeadline" class="form-select">
                        <option value="" selected>All</option>
                        @foreach ($editors as $e)
                            <option value="{{ $e->uuid }}">{{ $e->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="inputTag" class="form-label">Tag</label>
                    <input name="inputTag" id="tag" placeholder="Tag" type="text" class="form-control">
                </div>
                <div class="col-md-4">
                    <label for="inputHeadline" class="form-label">Type</label>
                    <select name="newsTypes" id="inputHeadline" class="form-select">
                        <option value="" selected>All</option>
                        @foreach ($newsTypes as $t)
                            <option value="{{ $t }}">{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="inputPublished" class="form-label">Status</label>
                    <select name="published" id="inputPublished" class="form-select">
                        <option value="" selected>All</option>
                        <option value="1">Published</option>
                        <option value="0">Not Published</option>
                    </select>
                </div>
                <div class="d-flex justify-content-end gap-3 mt-3">
                    <button type="submit" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="Category Search"><i class="bi bi-search"></i>&nbsp;&nbsp;&nbsp;Search
                    </button>
                    <a href="" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="Show All Category Data"><i class="bi bi-card-list"></i>&nbsp;&nbsp;&nbsp;Show All</a>
                </div>
            </form>
        </div>
    </div>
    <section class="section">

        <form action="{{ route('tagging.multi') }}" class="row" method="POST">
            @csrf
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between"><span class="h4">Page
                        List</span>
                </div>
                <div class="card-body">
                    <table class="table" id="table1">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="checkedTag"
                                        class=" form-check-input-custom form-check-success ">
                                </th>
                                <th>No</th>
                                <th>Title</th>
                                <th style="width: 20%">Category</th>
                                <th>Type</th>
                                <th>Schedule</th>
                                <th style="width:20%">Tag</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($news as $n)
                                <tr>
                                    <td>
                                        <input type="checkbox"
                                            class="checkedTag{{ $n->id }} form-check-input-custom form-check-success "
                                            name="checkedTag[]" value="{{ $n->id }}">
                                    </td>
                                    <td>{{ $news->firstItem() + $loop->index }}</td>
                                    <td>{{ $n->title }}</td>
                                    <td>{{ $n->categories->category }}</td>
                                    <td>{{ $n->types }}</td>
                                    <td>{{ $n->published_at }}</td>

                                    <td>
                                        <div class="form-group">
                                            <select id='tags{{ $n->id }}' name="tags[]" multiple>
                                                @foreach ($n->tags as $value)
                                                    <option value="{{ $value->id }}" selected> {{ $value->tags }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Get Tags All Checked --}}
                                        <script>
                                            $("#checkedTag").click(
                                                function() {
                                                    let getParent = document.getElementById("checkedTag")
                                                    console.log(getParent);
                                                    let getChild = document.querySelector(".checkedTag{{ $n->id }}")
                                                    if (getParent.checked === true) {
                                                        getChild.checked = true
                                                    } else {
                                                        getChild.checked = false
                                                    }
                                                }
                                            )
                                        </script>

                                        {{-- Get Tags Checked --}}
                                        <script>
                                            $('#tags{{ $n->id }}').select2({
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
                                        </script>

                                        {{--  Auto Save Inline Table --}}
                                        <script>
                                            $('#tags{{ $n->id }}').change(function() {
                                                var tags = $('#tags{{ $n->id }}').val();
                                                $.ajax({
                                                    type: "POST",
                                                    url: "{{ route('tagging.edit') }}",
                                                    data: {
                                                        id: {{ $n->id }},
                                                        tags: tags,
                                                        _token: @json(csrf_token())
                                                    },
                                                    success: function(data) {
                                                        console.log(data);
                                                    },
                                                    error: function(error) {
                                                        console.log(error, 'error auto save inline');
                                                    },
                                                });
                                            });
                                        </script>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between"><span class="h4">Mass Tag
                        Checked News</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <select id='massTag' name="massTag[]" multiple required></select>
                        </div>
                        <div class="col">
                            <button type="submit" class="btn btn-primary" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Mass Tag"><i class="bi bi-bookmark-fill"></i> Mass Tag
                            </button>
                        </div>
                    </div>
                </div>
                {{--   Get Tags In Mass Tag   --}}
                <script>
                    $('#massTag').select2({
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
                            cache: true,
                            success: function(response) {
                                console.log('response', response)
                            },
                            error: function(error) {
                                console.log(error, 'error get tags');
                            },
                        }
                    });
                </script>


            </div>
            <div class="d-flex">
                {{ $news->links() }}
            </div>
        </form>
    </section>
@endsection
@push('javascript')
    <script src="https://cdn.datatables.net/v/bs5/dt-1.12.1/datatables.min.js"></script>
    <script src="assets/js/bootstrap.js"></script>
@endpush
