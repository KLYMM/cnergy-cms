@extends('layout.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/menu.css') }}" />
@endpush

@section('body')
    <x-page-heading title="FrontEnd Menu Config" subtitle="Manage frontend menu for user" />
    <section class="section">
        <div class="card">

            <div class="card-header d-flex align-items-center justify-content-between">

                <span class="h4">Menu List</span>

                <a href="{{ route('front-end-menu.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle-fill"></i>&nbsp;&nbsp;&nbsp;Add Menu Front End
                </a>

            </div>

            <div class="card-body d-flex flex-column gap-2">
                {{-- <div class="spinner-border text-light" role="status">
                    <span class="visually-hidden">Loading...</span>
                  </div> --}}
                <input type="hidden" value="front-end-menu" id="type_data">
                <button class="btn btn-success col-2 d-none" id="button-save-order"><i class="bi bi-save"></i>
                    &nbsp;&nbsp;Save</button>
                @include('components.sortable.sortable', ['list' => $fe_menus, 'type' => 'front-end-menu'])
            </div>

        </div>

        <div class="toast-container position-fixed bottom-0 end-0 p-3">
            <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <strong class="me-auto">Message</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body bg-success text-light">
                    <p class="text-light">Hello, world! This is a toast message.</p>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('javascript')
    <script src="{{ asset('assets/extensions/toastify-js/src/toastify.js') }}"></script>
    <script src="{{ asset('assets/js/sortable.js') }}"></script>
    <script src="{{ asset('assets/js/pages/sortable-item.js') }}"></script>
@endpush
