<header class='mb-3'>
    <nav class="navbar navbar-expand navbar-light navbar-top">
        <div class="container-fluid">
            <a href="#" class="burger-btn d-block">
                <i class="bi bi-justify fs-3"></i>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-lg-0">
                        <li class="nav-item dropdown me-3 dropdown-hover">
                            <a class="nav-link active dropdown-toggle text-gray-600 font-bold" href="#"
                                data-bs-toggle="dropdown"><i class='bi bi-plus fs-4'></i> New
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end notification-dropdown"
                                aria-labelledby="dropdownMenuButton">
                                <li class="dropdown-item notification-item">
                                    <a class="d-flex align-items-center font-bold"
                                        href="{{ route('news.create') }}">News</a>
                                </li>
                                <li class="dropdown-item notification-item">
                                    <a class="d-flex align-items-center font-bold"
                                        href="{{ route('photo.create') }}">Photo News</a>
                                </li>
                                <li class="dropdown-item notification-item">
                                    <a class="d-flex align-items-center font-bold"
                                        href="{{ route('video.create') }}">Video News</a>
                                </li>
                                <li class="dropdown-item notification-item">
                                    <a class="d-flex align-items-center font-bold"
                                        href="{{ route('category.create') }}">Category</a>
                                </li>
                                <li class="dropdown-item notification-item">
                                    <a class="d-flex align-items-center font-bold"
                                        href="{{ route('tag-management.create') }}">Tag</a>
                                </li>
                                <li class="dropdown-item notification-item">
                                    <a class="d-flex align-items-center font-bold"
                                        href="{{ route('static-page.create') }}">Static Page</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    {{--  --}}
                    <ul class="navbar-nav ms-auto mb-lg-0">
                        <li class="nav-item dropdown me-3">
                            <a class="nav-link active dropdown-toggle text-gray-600" href="#"
                                data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                <i class='bi bi-bell bi-sub fs-4'></i>
                                {{-- menampilakan jumlah notif  --}}
                                <span class="position-absolute top-2 start-80 translate-middle badge rounded-pill bg-danger" style="font-size: 10px">
                                    <small>{{ $count }}</small>
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end notification-dropdown"
                                aria-labelledby="dropdownMenuButton">
                                <li class="dropdown-header">
                                    <h6>Notifications</h6>
                                </li>
                                {{-- menampilakan list notif  --}}
                                @foreach ($anouncement as $an)
                                @php
                                    $data = Str::of($an->targetRole)->explode(',');
                                @endphp
                                @foreach ($data as $d)
                                @if ($d == Auth::user()->role_id)
                                    <li class="dropdown-item nitification-item">
                                        <a href="{{ route('notification') }}">{{ $an->headline }}</a>
                                    </li>
                                @endif
                                @endforeach
                                @endforeach
                                <li>
                                    <p class="text-center py-2 mb-0"><a href="{{ route('notification') }}">See all
                                            notification</a></p>
                                </li>
                            </ul>
                        </li>
                    </ul>
                <div class="dropdown">
                    <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-menu d-flex">
                            <div class="user-name text-end me-3">
                                <h6 class="mb-0 text-gray-600">{{auth()->user()->name}}</h6>
                                <p class="mb-0 text-sm text-gray-600">{{auth()->user()->roles['role']}}</p>
                            </div>
                            <div class="user-img d-flex align-items-center">
                                <div class="avatar avatar-md">
                                    <img src="{{auth()->user()->profile_image}}">
                                </div>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton"
                        style="min-width: 11rem;">
                        <li>
                            <h6 class="dropdown-header">Hello, {{auth()->user()->name}}!</h6>
                        </li>
                        <li><a class="dropdown-item" href="{{route('profile.index')}}"><i
                                    class="icon-mid bi bi-person me-2"></i> My
                                Profile</a></li>
                        <li><a class="dropdown-item" href="{{route('logout')}}"><i
                                    class="icon-mid bi bi-box-arrow-left me-2"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>
