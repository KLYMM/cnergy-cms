@extends('layout.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/image-uploader.css') }}" />
@endpush

@section('body')
    <x-page-heading title="Dashboard" subtitle="" />
    <div class="page-content">
        <section class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body" style="background-color: #4e4df7; border-radius: 10px;">
                                <h6 class="font-extrabold mb-0" style="color:white;">News</h6>
                                </br>
                                <h6 class="font-extrabold mb-0" style="color:white;" align="center">{{ $news }}</h6>
                                </br>
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-center ">
                                    <a href="{{ route('news.index') }}">
                                        <div class="stats-icon"
                                            style="height: 2rem; width: 5rem; background-color:#2523b8;">
                                            <span style="color:white; font-size: 12px;">View All</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body" style="background-color: #f74d9f; border-radius: 10px;">
                                <h6 class="font-extrabold mb-0" style="color:white;">Photo News</h6>
                                </br>
                                <h6 class="font-extrabold mb-0" style="color:white;" align="center">{{ $photo }}</h6>
                                </br>
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-center ">
                                    <a href="{{ route('photo.index') }}">
                                        <div class="stats-icon"
                                            style="height: 2rem; width: 5rem; background-color:#a6165b;">
                                            <span style="color:white; font-size: 12px;">View All</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body" style="background-color: #d18221; border-radius: 10px;">
                                <h6 class="font-extrabold mb-0" style="color:white;">Video News</h6>
                                </br>
                                <h6 class="font-extrabold mb-0" style="color:white;" align="center">{{ $video }}</h6>
                                </br>
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-center ">
                                    <a href="{{ route('video.index') }}">
                                        <div class="stats-icon"
                                            style="height: 2rem; width: 5rem; background-color:#d95514;">
                                            <span style="color:white; font-size: 12px;">View All</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body" style="background-color: #f76116; border-radius: 10px;">
                                <h6 class="font-extrabold mb-0" style="color:white;">Tags</h6>
                                </br>
                                <h6 class="font-extrabold mb-0" style="color:white;" align="center">{{ $tags }}</h6>
                                </br>
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-center ">
                                    <a href="{{ route('tag-management.index') }}">
                                        <div class="stats-icon"
                                            style="height: 2rem; width: 5rem; background-color:#b34d1b;">
                                            <span style="color:white; font-size: 12px;">View All</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
@endsection

@push('javascript')
    {{-- <script src="{{ asset('assets/js/pages/image-uploader.js') }}"></script> --}}
@endpush
