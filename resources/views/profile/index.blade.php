@extends('layout.app')

@push('css')
@endpush

@section('body')
    <x-page-heading title="My Profile" subtitle="Manage My Profile" />
    <section class="section">
        <form action="{{ route('profile.update', auth()->user()->uuid) }}" method="post" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <div class="d-flex align-items-center">
                            <div class="avatar">
                                <img src="{{ auth()->user()->profile_image }}" style="height:200px; width:200px;">
                            </div>
                            <div class="ms-3 name">
                                <h5 class="font-bold mb-3">{{ auth()->user()->name }}</h5>
                                <h6 class="text-muted mb-2"><i
                                        class="bi bi-people"></i>&nbsp;&nbsp;{{ auth()->user()->roles['role'] }}</h6>
                                <h6 class="text-muted mb-2"><i
                                        class="bi bi-envelope"></i>&nbsp;&nbsp;{{ auth()->user()->email }}</h6>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="basicInput">Name</label>
                        <input type="text" class="form-control" id="name" placeholder="Enter Name" name="name"
                            value="{{ auth()->user()->name }}">
                    </div>

                    <div class="form-group">
                        <label for="biography" class="form-label mb-2">Biography</label>
                        <textarea name="biography" class="form-control" id="biography" placeholder="Enter Biography" cols="30"
                            rows="3">
@if (auth()->user()->biography !== null)
{{ auth()->user()->biography }}
@endif
</textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-3">
                        <button class="btn btn-primary" type="submit" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Update Profile">Update Profile
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection

@push('javascript')
@endpush
