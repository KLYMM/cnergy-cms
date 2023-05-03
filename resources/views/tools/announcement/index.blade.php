@extends('layout.app');

@section('body')
<x-page-heading title="Anouncement" subtitle="View & Manage Anouncement"/>
<a href="{{ route('anouncement.create') }}" class="btn btn-primary mb-3">Create Anouncement</a>

{{-- {{ $data }} --}}
<div class="card">
  <div class="card-body">
    <table class="table">
      <thead>
        <tr>
          <th scope="col">No</th>
          <th scope="col">Headline</th>
          <th scope="col">Message</th>
          <th scope="col">Created By</th>
          <th scope="col">Target</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($data as $d) 
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $d->headline }}</td>
          <td>{{ $d->message }}</td>
          <td>{{ $d->user->name }}</td>
          <td>{{ $d->targetRole }}</td>
          <td>
            <a href="{{ route('anouncement.edit', $d->id) }}" class="btn btn-warning mb-2"><i
              class="bi bi-pencil-square"></i></a>
            <form action="{{ route('anouncement.destroy', $d->id) }}" method="POST">
              @method('DELETE')
              @csrf
              <button type="submit" class="btn btn-danger"><i
                class="bi bi-trash"></i></button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="d-flex">
    {{ $data->links() }}
  </div>
</div>
@endsection