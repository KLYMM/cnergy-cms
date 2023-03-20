@extends('layout.app')

@push('css')
    <style>
        .theme-dark #a-accordion-custom {
            color: white;
        }

        .accordion-active {
            background-color: #a7acb0;
            border-radius: 0.5rem;
            padding: 0.7rem 0rem;
            color: black;
        }
    </style>
@endpush

@section('body')
    <x-page-heading title="Table Role" subtitle="View and Manage Role Data" />
    @if ($method === 'edit')
        <form action="{{ route('role.update', $role->id) }}" method="post">
        @else
            <form action="{{ route('role.store') }}" method="post">
    @endif
    <section id="basic-vertical-layouts">
        @csrf
        <div class="row match-height">
            <div class="col-6 ">
                <div class="card">

                    <div class="card-header">
                        <span class="h4 text-capitalize">{{ $method }} Role</span>
                    </div>

                    <div class="card-body d-flex flex-column gap-2">
                        @if ($method === 'edit')
                            @method('PUT')
                            @csrf
                        @endif
                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="basicInput" class="mb-2">Role Name</label>
                                <input type="text" class="form-control" id="basicInput" name="role"
                                    placeholder="Enter Role Name "
                                    @if ($method === 'edit') value="{{ $role->role }}" @endif />
                            </div>

                            <div class="d-flex justify-content-end gap-3 mt-3">

                                <a href="{{ route('role.index') }}" class="btn btn-light" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Back to Table Rome">Back
                                </a>

                                <button class="btn btn-primary" type="submit" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Create Role">Save
                                </button>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="card">

                    <div class="card-header">
                        <span class="h4">Assign</span>
                    </div>

                    <div class="card-body d-flex flex-column gap-2 p-0">
                        <ul class="list-unstyled mb-0">
                            <li class=" me-2 mb-0">
                                <div class="form-check">
                                    <x-checkbox-menu :accordion="$menus" />
                                </div>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </section>
    </form>
@endsection

@push('javascript')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
@endpush
