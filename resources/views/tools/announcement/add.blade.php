@extends('layout.app');

@section('body')
<x-page-heading title="Anouncement" subtitle="Add Anouncement"/>
    <div class="card">
        <div class="card-header">
          <h4>Manage Anouncement</h4>
        </div>
        <div class="card-body">
          <form action="{{ route('anouncement.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="formGroupExampleInput" class="form-label">Headline</label>
                <input type="text" class="form-control" id="formGroupExampleInput" name="headline" placeholder="Headline Anouncement">
            </div>
            <div class="mb-3">
                <label for="formGroupExampleInput" class="form-label">Message</label>
                <input type="text" class="form-control" id="formGroupExampleInput" name="message" placeholder="Message Anouncement">
            </div>
            <div class="mb-3">
                <label for="formGroupExampleInput" class="form-label">Target Role</label>
                @foreach ($targetRole as $tr)
                    <div class="form-check me-3">
                        <input class="form-check-input" type="checkbox" name="targetRole[]" value="{{ $tr->id }}" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
                        {{ $tr->role }}
                        </label>
                    </div>
                @endforeach
            </div>
            <div class="mb-3">
              <button type="submit" class="btn btn-primary">Create</button>
            </div>
          </form>
        </div>
      </div>
@endsection