@extends('layout.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/menu.css') }}" />
@endsection

@section('body')
    <x-page-heading title="Menu Config" subtitle="Manage CMS Menu" />
    <section class="section">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span class="h4">Menu List</span>
                <a href="{{ route('menu.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i>&nbsp;&nbsp;&nbsp;Add Menu
                </a>
                <input type="hidden" value="menu" id="type_data">
            </div>
            <div class="card-body d-flex flex-column gap-2">
                <button class="btn btn-success col-2 d-none" id="button-save-order"><i class="bi bi-save"></i>
                    &nbsp;&nbsp;Save</button>
                    @include('components.sortable.sortable', ['list' => $menus, 'type' => 'menu'])
            </div>
        </div>
    </section>
@endsection

@section('javascript')
    @vite('resources/js/components/handleSortable.js');
@endsection
