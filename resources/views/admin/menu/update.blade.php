@extends('layout.app')

@push('css')
@endpush

@section('body')
    <x-page-heading title="Menu Config" subtitle="Manage CMS Menu" />
    <section class="section">
        <div class="card col-md-7">

            <div class="card-header">
                <span class="h4">Edit Menu</span>
            </div>

            <div class="card-body d-flex flex-column gap-2">

                <form action="{{ route('menu.update', $menu->id) }}" method="post">
                    @method('PUT')
                    @csrf
                    <div class="col-md-12">

                        <label for="basicInput" class="mb-2 fw-bold">Parent Menu : -</label>

                        <div class="form-group">
                            <label for="basicInput" class="mb-2">Menu Name</label>
                            <input type="text" class="form-control @error('menu_name') is-invalid @enderror"
                                id="basicInput" name="menu_name" placeholder="Enter menu name"
                                value="{{ $menu->menu_name }}" />
                            @error('menu_name')
                                <div class="invalid-feedback">
                                    <i class="bx bx-radio-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{--
                        <div class="form-group">
                            <label for="basicInput" class="mb-2">Menu URL</label>
                            <input type="text" class="form-control" id="basicInput" name="url" placeholder="Enter menu url" />
                        </div>

                        <div class="form-group">
                            <label for="basicInput" class="mb-2">Menu Icon</label>
                            <input type="text" class="form-control" id="basicInput" name="icon" placeholder="Enter menu icon" />
                        </div>
                    --}}

                        <div class="d-flex justify-content-end gap-3 mt-3">

                            <a href="{{ route('menu.index') }}" class="btn btn-light" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Back to Table Menu">Back
                            </a>

                            <button class="btn btn-primary" type="submit" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Update Menu Data">Update
                            </button>

                        </div>

                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('javascript')
@endpush
