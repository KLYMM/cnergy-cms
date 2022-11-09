@extends('layout.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/codemirror.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/dracula.css') }}">
    <style>
        .CodeMirror {
            border: 1px solid #eee;
            height: 375px;
        }
    </style>
@endsection

@push('head')
@endpush

@section('body')
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                @foreach (array_keys($inventory_config) as $item)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link nav-link-inventory @if ($loop->first) active @endif text-capitalize"
                            id="{{ $item }}-tab" data-bs-toggle="tab" href="#{{ $item }}" role="tab"
                            aria-controls="{{ $item }}" aria-selected="true">{{ $item }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="card-body">
            <form action="{{ route('inventory-management.store') }}" method="post">
                @csrf
                <div class="tab-content" id="myTabContent">
                    @php
                        $inventory = '';
                        $type = '';
                    @endphp
                    @foreach ($inventory_config as $types => $value)
                        @php
                            $type = $types;
                        @endphp
                        <div class="tab-pane @if ($loop->first) show active @endif"
                            id="{{ $types }}" role="tabpanel" aria-labelledby="{{ $types }}-tab">
                            @foreach ($value as $index_value)
                                @php
                                    $index_arr = $loop->index;
                                @endphp
                                <div class="row @if (!$loop->first) mt-5 @endif">
                                    <div class="col-md-3">
                                        @foreach ($index_value as $keys_name => $input_value)
                                            @if ($loop->first)
                                                @php
                                                    $inventory = $index_value['slot_name'];
                                                @endphp
                                                <p class="fw-bold">{{ $input_value }}</p>
                                            @endif
                                            @if (!$loop->first)
                                                @if ($keys_name !== 'slot_name')
                                                    @if ($keys_name !== 'inventory')
                                                        <div class="form-group">
                                                            <label for="{{ $keys_name }}"
                                                                class="mb-2 text-capitalize">{{ implode(' ', explode('_', $keys_name)) }}</label>
                                                            <input type="text" class="form-control"
                                                                id="{{ $keys_name }}"
                                                                name="{{ $type }}[{{ $index_arr }}][{{ $keys_name }}]"
                                                                required value="{{ $inventory_management[$type][$index_arr][$keys_name] ?? null }}" />
                                                        </div>
                                                    @else
                                                        <input type="hidden"
                                                            name="{{ $type }}[{{ $index_arr }}][{{ $keys_name }}]"
                                                            value="{{ $input_value }}">
                                                    @endif
                                                @else
                                                    <input type="hidden"
                                                        name="{{ $type }}[{{ $index_arr }}][id]"
                                                        value="{{ $inventory_management[$type][$index_arr]['id'] }}">
                                                    <input type="hidden"
                                                        name="{{ $type }}[{{ $index_arr }}][{{ $keys_name }}]"
                                                        value="{{ $input_value }}">
                                                @endif
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="col-md-9">
                                        <textarea name="{{ $type }}[{{ $index_arr }}][code]" id="code" cols="30" rows="20"
                                            class="h-100 CodeMirror code-inventory">{{ $inventory_management[$type][$index_arr]['code'] ?? null }}</textarea>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
                <button type="submit" class="btn btn-success">Submit</button>
            </form>
        </div>
        <div class="card-footer">
        </div>
    </div>
@endsection


@section('javascript')
    <script src="{{ asset('assets/js/codemirror.js') }}"></script>
    <script src="{{ asset('assets/js/extensions/javascript.js') }}"></script>
    <script src="{{ asset('assets/js/pages/inventory.js') }}"></script>
@endsection
