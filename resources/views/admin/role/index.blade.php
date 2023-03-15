@extends('layout.app')

@section('body')
<x-page-heading title="Table Role" subtitle="View and Manage Role Data" />
<section class="section">
    <div class="card">

        <div class="card-header d-flex align-items-center justify-content-between">
            <span class="h4">Role Search</span>
        </div>
        
        <div class="card-body">

            <form class="row g-3" method="GET">

                <div class="col-md-4">
                    <label for="inputTag" class="form-label">Role</label>
                    <input name="inputRole" id="role" placeholder="Role" type="text" class="form-control">
                </div>

                <div class="d-flex justify-content-end gap-3 mt-3">

                    <button type="submit" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Role Search">
                        <i class="bi bi-search"></i>&nbsp;&nbsp;&nbsp;Search
                    </button>

                    <a href="{{route('role.index')}}" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Show All Role Data">
                        <i class="bi bi-card-list"></i>&nbsp;&nbsp;&nbsp;Show All
                    </a>

                </div>

            </form>

        </div>

    </div>

    <div class="card">
        
        <div class="card-header d-flex align-items-center justify-content-between"><span class="h4">Role List</span>
            <a href="{{ route('role.create') }}" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Add Role">
                <i class="bi bi-plus-circle"></i>&nbsp;&nbsp;&nbsp;Add Role
            </a>
        </div>
        
        <div class="card-body">
            <table class="table" id="table1">
                <thead>
                    <tr>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                    <tr>
                        <td>{{ $role->role }}</td>
                        <td>
                            <div class="d-flex gap-2">

                                <a href="{{ route('role.edit', $role->id) }}" class="btn icon btn-warning">
                                    <i class="bi bi-pencil-square" data-bs-toggle="tooltip" data-bs-placement="top" title="Update Role Data"></i>
                                </a>

                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#delete{{ $role->id }}" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Delete Role Data">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                                
                            </div>
                        </td>
                    </tr>
                    <!-- Delete Modal -->
                    <div class="modal fade text-left" id="delete{{ $role->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="myModalLabel1">Delete Role</h5>
                                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal"
                                        aria-label="Close">
                                        <i data-feather="x"></i>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Delete Role "{{ $role->role }}"?
                                </div>
                                <div class="modal-footer">
                                    <form action="{{ route('role.destroy', $role->id) }}" method="post">
                                        {{ method_field('delete') }}
                                        @csrf
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="bx bx-x d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Cancel</span>
                                        </button>
                                        <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">

                                            <span class="d-none d-sm-block">Delete</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
    
    <div class="d-flex">
        {{ $roles->links() }}
    </div>
    
</section>

@endsection