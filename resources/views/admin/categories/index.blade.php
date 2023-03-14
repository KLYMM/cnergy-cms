@extends('layout.app')

@section('css')
    <style>
        .accordion-body:hover {
            background: transparent !important;
        }

        /*.accordion *:hover { 
            background: transparent !important
        }*/
    </style>
@endsection

@section('body')
    <x-page-heading title="Category Config" subtitle="Manage category" />
    <section class="section">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span class="h4">Category List</span>
                <a href="{{ route('category.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i>&nbsp;&nbsp;&nbsp;Add Category
                </a>
                <input type="hidden" value="category" id="type_data">
            </div>
            <div class="card-body d-flex flex-column gap-2">
                <button class="btn btn-success col-2 d-none" id="button-save-order"><i class="bi bi-save"></i>
                    &nbsp;&nbsp;Save</button>
                @include('components.sortable.sortable', ['list' => $categories, 'type' => 'category'])
            </div>
        </div>

    </section>
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="liveToast" class="toast fade hide" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-success">
                <strong class="me-auto text-white">Message</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Hello, world! This is a toast message.
            </div>
        </div>
    </div>
@endsection

@section('javascript')
        @vite('resources/js/pages/categoryPage.js');
@endsection
