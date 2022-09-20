@extends('layout.app')

@section('css')

<link rel="stylesheet" href="{{ asset('assets/css/pages/menu.css')}}">
@endsection

@section('body')
<x-page-heading title="Table Tag" subtitle="View and Manage Tag Data" />

<section class="section">
    <div class="card ">
        <div class="card-header d-flex align-items-center justify-content-between"><span class="h4">Tag Search</span>
        </div>
        <div class="card-body">
            <form class="row g-3" method="GET">
                <div class="col-md-4">
                    <label for="inputTag" class="form-label">Tag</label>
                    <input name="inputTags" value="{{ old('inputTags') }}" id="tag" placeholder="Tag" type="text" class="form-control">
                </div>
                <div class="col-md-4">
                    <label for="inputSlug" class="form-label">Slug</label>
                    <input name="inputSlug" value="{{ old('inputSlug') }}" id="slug" placeholder="Slug" type="text" class="form-control">
                </div>
                <div class="col-md-2">
                    <label for="inputState" class="form-label">Status</label>
                    <select name="status" id="inputState" class="form-select">
                        <option value="" selected>All</option>
                        <option value="1">Active</option>
                        <option value="2">Inactive</option>
                    </select>
                </div>
                <div class="col-4">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i>&nbsp;&nbsp;&nbsp;Search</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Basic Tables start -->
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between"><span class="h4">Tags List</span>
            <a href="{{ route('tags.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle-fill"></i>&nbsp;&nbsp;&nbsp;Add
                Tag</a>
        </div>
        <div class="card-body">
            <table class="table" id="table1">
                <thead>
                    <tr>
                        <th>Tags</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tags as $t)
                    <tr>
                        <td>{{ $t->tags }}</td>
                        <td>{{ $t->slug }}</td>
                        @if ($t->is_active == 1)
                        <td> <span class="badge bg-success">Active</span> </td>
                        @else
                        <td> <span class="badge bg-danger">Inactive</span> </td>
                        @endif
                        <td>
                            <a href="{{ route('tags.edit', $t->id) }}" class="btn icon btn-warning"><i class="bi bi-pencil-square"></i></a>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#delete{{ $t->id }}" class="btn icon btn-danger"><i class="bi bi-trash"></i></button>
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
                                    Delete Tag '{{ $t->tags }}' ?
                                </div>
                                <div class="modal-footer">
                                    <form action="{{ route('tags.destroy', $t->id) }}" method="post">
                                        {{ method_field('delete') }}
                                        @csrf
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                        </button>
                                        <button type="submit" class="btn btn-primary">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>

            <div  class="d-flex">
                {{ $tags->links() }}
            </div>
        </div>
    </div>
</section>
@endsection