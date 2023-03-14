@extends('layout.app')

@section('body')
    <div class="container">
        <div class="">
            <h3 class="mb-3">Notification</h3>
        </div>
        <div class="d-flex flex-column">
            @php
                $is_read=[];
            @endphp
            @foreach ($anouncement as $an)
                @php
                    $data = Str::of($an->targetRole)->explode(',');
                @endphp
                @foreach ($data as $d)
                    @if ($d == Auth::user()->role_id)
                        @php
                            $is_read[$an->id] = null;
                            if($markAsRead != null) {
                                foreach ($markAsRead as $mar) {
                                    if ($mar->anouncement_id == $an->id) {
                                        $is_read[$an->id] = 1;
                                        break;
                                    }else {
                                        $is_read[$an->id] = null;
                                        // break;
                                    }
                                }
                            }
                            
                        @endphp

                        {{-- {{ dd($is_read) }} --}}
                        <div class="{{ $is_read[$an->id] == 1 ? 'bg-transparant' : 'bg-white' }} p-3 mb-3">
                            <a class="nav-link collapsed" data-bs-target="#target{{ $an->id }}" data-bs-toggle="collapse" href="#">
                                <strong>Headline : {{ $an->headline }}</strong><i class="bi bi-chevron-down ms-3"></i><br>
                                <small>Created By : {{ $an->user->name }}</small>
                            </a>
                            <div id="target{{ $an->id }}" class="nav-content collapse mt-3" data-bs-parent="#sidebar-nav">
                                <p style="text-decoration: underline">Message : </p>
                                <p>{{ $an->message }}</p>
                                <small class="me-0">Created at : {{ $an->created_at->isoFormat('ddd, D MMMM Y') }}</small>
                                @if ($is_read[$an->id] == null)
                                <form action="{{ route('notification.asread') }}" method="POST">
                                    @csrf
                                    <input type="hidden" value="{{ $an->id }}" name="id">
                                    <input type="hidden" value="1" name="is_read">
                                    <button type="submit">Mark as read</button>
                                </form>
                                @else  
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
            @endforeach
        </div>
    </div>
@endsection