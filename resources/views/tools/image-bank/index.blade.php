@extends('layout.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/menu.css') }}">
    <style>
        .image-card {
            width: 200px;
            height: fit-content;
            margin: 7px;
            margin-top: 2.5px;
            float: left;
        }

        .button-action {
            background-color: var(--bs-btn-bg);
            border: var(--bs-btn-border-width) solid var(--bs-btn-border-color);
            border-radius: 0;
            color: white;
            cursor: pointer;
            display: inline-block;
            font-family: var(--bs-btn-font-family);
            font-size: var(--bs-btn-font-size);
            font-weight: var(--bs-btn-font-weight);
            line-height: var(--bs-btn-line-height);
            padding: 0.375rem 0.75rem;
            text-align: center;
            text-decoration: none;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out,
                border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
            vertical-align: middle;
        }
    </style>
@endsection

@section('body')
    <x-page-heading title="Image Bank" subtitle="Image bank for public" />

    <section class="section">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between"><span class="h4">Images List</span>
                <a href="{{ route('image-bank.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle-fill"
                        data-bs-toggle="tooltip" data-bs-placement="top" title="Add Tag"></i>&nbsp;&nbsp;&nbsp;Add
                    Image</a>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-5 justify-content-center align-items-center" id="masonry">
                    @foreach ($image_bank as $item)
                        <div class="image-card border p-0 mt-5">
                            @php
                                $welcome = $item->slug;
                                $arr = explode('/', $welcome);
                                $currImage = end($arr);
                                $image = '200xauto-' . $currImage;
                                $arr[count($arr) - 1] = $image;
                                $realPath = implode('/', $arr);
                            @endphp
                            <img src="{{ Storage::url($realPath) }}"
                                alt="" class="w-100" style="height: 100px; object-fit: cover;">
                            <div class="d-flex flex-column gap-2 p-2">
                                <p class="m-0 font-14">{{ $item->title }}</p>
                                <div class="d-flex">
                                    <a href="{{ route('image-bank.edit', $item->id) }}"
                                        class="btn-info font-14 w-50 button-action">
                                        <i class="bi bi-pencil-square"></i> Meta
                                    </a>
                                    <button class="btn btn-danger font-14 w-50 button-action" type="button"
                                        data-bs-toggle="modal" data-bs-target="#delete{{ $item->id }}"
                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Image"><i
                                            class="bi bi-trash"></i>&nbsp;&nbsp;Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade text-left" id="delete{{ $item->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="myModalLabel1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="myModalLabel1">Delete Image</h5>
                                        <button type="button" class="close rounded-pill" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="" style="font-size: 15px"> Are you sure you want to delete
                                            <strong class="text-danger">{{ $item->title }}</strong> image?
                                        </p>
                                    </div>
                                    <div class="modal-footer">
                                        <form action="{{ route('image-bank.destroy', $item->id) }}" method="post">
                                            {{ method_field('delete') }}
                                            @csrf
                                            <button type="button" class="btn btn-outline-secondary"
                                                data-bs-dismiss="modal">
                                                <i class="bx bx-x d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">No</span>
                                            </button>
                                            <button type="submit" class="btn btn-primary ml-1" data-bs-dismiss="modal">
                                                <i class="bx bx-check d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Yes</span>
                                            </button>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                    {{-- @for ($i = 0; $i < 10; $i++)
                        <div class="image-card border p-0">
                            <img @if ($i % 2 == 0) src="{{ asset('assets/images/example_image.jpg') }}"
                            @else
                            src="{{ asset('assets/images/example_image_1.jpg') }}" @endif
                                alt="" class="w-100">
                            <div class="d-flex flex-column gap-2 p-2">
                                <p class="m-0 font-14">Lorem ipsum dolor sit amet consectetur adipisicing elit</p>
                                <div class="d-flex">
                                    <button class="btn-warning font-14 w-50 button-action"><i
                                            class="bi bi-pencil-square"></i>&nbsp;&nbsp;Edit</button>
                                    <button class="btn-danger font-14 w-50 button-action"><i
                                            class="bi bi-trash"></i>&nbsp;&nbsp;Delete</button>
                                </div>
                            </div>
                        </div>
                    @endfor --}}
                </div>
            </div>
        </div>
    </section>
@endsection

@section('javascript')
    <script src="https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js"
        integrity="sha384-GNFwBvfVxBkLMJpYMOABq3c+d3KnQxudP/mGPkzpZSTYykLBNsZEnG2D9G/X/+7D" crossorigin="anonymous" async>
    </script>
    <script>
        window.addEventListener('load', (event) => {
            var elem = document.querySelector('#masonry');
            var msnry = new Masonry(elem, {
                // options
                itemSelector: '.image-card',
                columnWidth: 215,

            });
        });
    </script>
@endsection
