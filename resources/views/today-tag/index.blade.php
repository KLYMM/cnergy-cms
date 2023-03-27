@extends('layout.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/menu.css') }}">
@endpush

@section('body')
    <x-page-heading title="Today Tag" subtitle="Set today's tag" />
    <section class="section">
        <div class="card ">

            <div class="card-body">

                <form class="row g-3" method="GET">

                    <div class="col-md-5">
                        <label for="inputTitle" class="form-label">Title</label>
                        <input name="inputTitle" id="title" placeholder="Title" type="text" class="form-control">
                    </div>

                    <div class="col-md-5">
                        <label for="inputCategory" class="form-label">Category</label>
                        {{-- <select name="inputCategory" id="category" class="form-select">
                            <option value="" selected>All</option>
                            <option value="1">Category 1</option>
                            <option value="2">Category 2</option>
                            <option value="3">Category 3</option>
                        </select> --}}
                        <select class="form-select" name="inputCategory" id="category">
                            <option value="" selected>All</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->category }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="inputId" class="form-label">ID</label>
                        <input name="inputId" id="Id" placeholder="ID" type="text" class="form-control">
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-3">

                        <button type="submit" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Tag Search">
                            <i class="bi bi-search"></i>&nbsp;&nbsp;&nbsp;Search
                        </button>

                        <a href="{{ route('today-tag.index') }}" class="btn btn-light" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Show All Today Tag">
                            <i class="bi bi-card-list"></i>&nbsp;&nbsp;&nbsp;Show All
                        </a>

                    </div>

                </form>
            </div>
        </div>

        <div class="card">

            <div class="card-header d-flex align-items-center justify-content-between">

                <span class="h4">Today Tags</span>

                <a href="{{ route('today-tag.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="Create Today Tag"></i>&nbsp;&nbsp;&nbsp;Create Today Tag
                </a>

            </div>

            <div class="card-body">
                <table class="table" id="table1">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Tag/Link</th>
                            <th>Type</th>
                            <th>Category</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($today_tag as $t)
                            <tr>
                                <td>{{ $t->order_by_no }}</td>
                                <td>{{ $t->id }}</td>
                                <td>{{ $t->title }}</td>
                                {{-- <td>{{ implode(" " , json_decode($t->tag, true)) }}</td> --}}
                                @if ($t->types === 'news_tag')
                                    <td>{{ $t->tagId->tags ?? 'empty' }}</td>
                                @endif
                                @if ($t->types === 'sponsorship_tag')
                                    <td>{{ $t->tagId->tags ?? 'empty' }}</td>
                                @endif
                                @if ($t->types === 'external_link')
                                    <td>{{ $t->url }}</td>
                                @endif
                                <td>{{ $t->types }}</td>
                                <td>{{ $t->categoryId->category ?? 'empty' }}</td>
                                <td>
                                    <a href="{{ route('today-tag.edit', $t->id) }}" class="btn icon btn-warning"
                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Update Today Tag Data">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button type="button" data-bs-toggle="modal"
                                        data-bs-target="#delete{{ $t->id }}" class="btn icon btn-danger"
                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Today Tag Data">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <div class="modal fade" id="delete{{ $t->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="myModalLabel1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel">Delete Tag</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Delete Tag '{{ $t->title }}' ?
                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{ route('today-tag.destroy', $t->id) }}" method="post">
                                                {{ method_field('delete') }}
                                                @csrf
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex">
                    {{ $today_tag->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
