@extends('layout.app')

@section('css')
    <style>
        .image-preview {
            width: 300px;
            height: 200px;
            object-fit: cover;
        }
    </style>
@endsection

@section('body')
    <x-page-heading title="Menu Settings Config" subtitle="Manage frontend settings" />
    <section class="section">
       
        <form action="{{ route('front-end-setting.update', 1) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="d-flex justify-content-between gap-2">
                <div class="card col-md-5">
                    <div class="card-header"><span class="h5">General Settings</span></div>
                    <div class="card-body">
                        @csrf
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="basicInput" class="mb-2">Site Title</label>
                                <input type="text" class="form-control @error('site_title') is-invalid @enderror"
                                    id="basicInput" name="site_title" placeholder="Enter site title"
                                    value="@if ($menu_settings ?? null) {{ $menu_settings->site_title }} @endif" />
                                @error('site_title')
                                    <div class="invalid-feedback">
                                        <i class="bx bx-radio-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="basicInput" class="mb-2">Site Description</label>
                                <textarea type="text" class="form-control @error('site_description') is-invalid @enderror" id="basicInput"
                                    name="site_description" placeholder="Enter site description">
@if ($menu_settings ?? null)
{{ $menu_settings->site_description }}
@endif
</textarea>
                                @error('site_description')
                                    <div class="invalid-feedback">
                                        <i class="bx bx-radio-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="basicInput" class="mb-2">Adresss</label>
                                <textarea type="text" class="form-control @error('address') is-invalid @enderror" id="basicInput" name="address"
                                    placeholder="Enter address">
@if ($menu_settings ?? null)
{{ $menu_settings->address }}
@endif
</textarea>
                                @error('address')
                                    <div class="invalid-feedback">
                                        <i class="bx bx-radio-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="basicInput" class="mb-2">Social Media</label>
                                <div class="form-group position-relative has-icon-left">
                                    <input type="text" class="form-control" placeholder="Facebook" name="facebook"
                                        value="@if ($menu_settings ?? null) {{ $menu_settings->facebook }} @endif" />
                                    <div class="form-control-icon">
                                        <i class="bi bi-facebook"></i>
                                    </div>
                                </div>
                                <div class="form-group position-relative has-icon-left">
                                    <input type="text" class="form-control" placeholder="Instagram" name="instagram"
                                        value="@if ($menu_settings ?? null) {{ $menu_settings->instagram }} @endif" />
                                    <div class="form-control-icon">
                                        <i class="bi bi-instagram"></i>
                                    </div>
                                </div>
                                <div class="form-group position-relative has-icon-left">
                                    <input type="text" class="form-control" placeholder="Twitter" name="twitter"
                                        value="@if ($menu_settings ?? null) {{ $menu_settings->facebook }} @endif" />
                                    <div class="form-control-icon">
                                        <i class="bi bi-twitter"></i>
                                    </div>
                                </div>
                                <div class="form-group position-relative has-icon-left">
                                    <input type="text" class="form-control" placeholder="Youtube" name="youtube"
                                        value="@if ($menu_settings ?? null) {{ $menu_settings->youtube }} @endif" />
                                    <div class="form-control-icon">
                                        <i class="bi bi-youtube"></i>
                                    </div>
                                </div>
                                @error('social_media')
                                    <div class="invalid-feedback">
                                        <i class="bx bx-radio-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="basicInput" class="mb-2">Facebook App ID</label>
                                <input type="text" class="form-control @error('facebook_app_id') is-invalid @enderror"
                                    id="basicInput" name="facebook_app_id" placeholder="Enter facebook id"
                                    value="@if ($menu_settings ?? null) {{ $menu_settings->facebook_app_id }} @endif" />
                                @error('facebook_app_id')
                                    <div class="invalid-feedback">
                                        <i class="bx bx-radio-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="basicInput" class="mb-2">Twitter Username</label>
                                <input type="text" class="form-control @error('twitter_username') is-invalid @enderror"
                                    id="basicInput" name="twitter_username" placeholder="Enter twitter username"
                                    value="@if ($menu_settings ?? null) {{ $menu_settings->twitter_username }} @endif" />
                                @error('twitter_username')
                                    <div class="invalid-feedback">
                                        <i class="bx bx-radio-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card col-md-7">
                    <div class="card-header"><span class="h5">Visual Settings</span></div>
                    <div class="card-body d-flex flex-column gap-2">
                        @csrf
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="site_logo" class="mb-2">Site Logo (.png)</label>
                                <div class="flex flex-column">
                                    <img src="@if ($menu_settings ?? null) {{ Storage::url($menu_settings->site_logo) }} @else {{ asset('assets/images/site_logo.png') }} @endif"
                                        class="mb-3 image-preview" alt="Your Image" id="site_logo_preview">
                                    <input type="file" class="form-control" name="site_logo" id="site_logo_input"
                                        accept="image/png"
                                        value="@if ($menu_settings ?? null) {{ $menu_settings->site_logo }} @endif" />
                                    @error('site_logo')
                                        <div class="invalid-feedback">
                                            <i class="bx bx-radio-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="favicon" class="mb-2">Favicon (.ico)</label>
                                <div class="flex flex-column">
                                    <img src="@if ($menu_settings ?? null) {{ Storage::url($menu_settings->favicon) }} @else {{ asset('assets/images/site_logo.png') }} @endif"
                                        class="mb-3 image-preview" alt="Your Image" id="favicon_preview">
                                    <input type="file" class="form-control" name="favicon" id="favicon_input"
                                        accept="image/x-icon"
                                        value="@if ($menu_settings ?? null) {{ $menu_settings->favicon }} @endif" />
                                    @error('favicon')
                                        <div class="invalid-feedback">
                                            <i class="bx bx-radio-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="accent_color" class="mb-2">Color
                                </label>
                                <input type="hidden" id="accent_color_str"
                                    value="@if ($menu_settings ?? null) {{ $menu_settings->accent_color }} @endif">
                                <input type="color" name="accent_color" class="form-control" id="accent_color_input"
                                    value="#ffffff" />
                                @error('accent_color')
                                    <div class="invalid-feedback">
                                        <i class="bx bx-radio-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="d-flex justify-content-end gap-3 mt-3">
                                <button class="btn btn-primary" type="submit" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Save Setting Data">Save Settings</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="card col-md-12">
            <div class="card-header"><span class="h5">Generate Token</span></div>
            <div class="card-body d-flex flex-column gap-2">
                <form action="{{ route('generate.token') }}" method="post">
                    @csrf
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="basicInput" class="mb-2">Token Name</label>
                            <input type="text" class="form-control @error('token_name') is-invalid @enderror"
                                id="basicInput" name="token_name" placeholder="Enter token name" value="" />
                            @error('token_name')
                                <div class="invalid-feedback">
                                    <i class="bx bx-radio-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-end gap-3 mt-3">
                            <a href="{{ route('menu.index') }}" class="btn btn-light" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Back to Table Menu">Back</a>
                            <button class="btn btn-primary" type="submit" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Create Menu">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@section('javascript')
    <script src="{{ asset('assets/js/pages/menu_settings.js') }}"></script>
@endsection
