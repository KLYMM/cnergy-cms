@extends('layout.app')

@push('css')
@endpush

@section('body')
    <x-page-heading title="Menu Config" subtitle="Manage backend menu for user" />
    <section class="section">
        <div class="card col-md-7">
            <div class="card-header"><span class="h4 text-capitalize">{{ $method !== 'edit' ? 'Create' : 'Edit' }} Menu</span>
            </div>
            <div class="card-body d-flex flex-column gap-2">
                @if ($method === 'edit')
                    <form action="{{ route('menu.update', $menu->id) }}" method="post">
                        @method('PUT')
                    @else
                        <form action="{{ route('menu.store') }}" method="post">
                @endif
                @csrf
                <div class="col-md-12">
                    @if ($method !== 'edit' && $menu)
                        <input type="hidden" name="parent_id" value="{{ $menu->id }}">
                        <input type="hidden" name="parent_slug" value="{{ $menu->slug }}">
                        <label for="basicInput" class="mb-2 fw-bold">Parent Menu : {{ $menu->menu_name }}</label>
                    @endif
                    <div class="form-group">
                        <label for="basicInput" class="mb-2">Menu Name</label>
                        <input type="text" class="form-control @error('menu_name') is-invalid @enderror" id="basicInput"
                            name="menu_name" placeholder="Enter menu name"
                            @if ($method === 'edit') value="{{ $menu->menu_name }}" @endif />
                        @error('menu_name')
                            <div class="invalid-feedback">
                                <i class="bx bx-radio-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-end gap-3 mt-3">

                        <a href="{{ route('menu.index') }}" class="btn btn-light" data-bs-toggle="tooltip"
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

@push('javascript')
@endpush
