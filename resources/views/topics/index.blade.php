@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="list-group">
                    @foreach ($topics as $topic)
                        <div class="list-group-item">
                            <h5><a href="{{ route('topics.show', $topic->id) }}">{{ $topic->title }}</a></h5>
                            <p>{{ $topic->content }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small>Posté le {{ $topic->created_at->format('d/m/Y à H:m') }}</small>
                                <span class="badge badge-primary">{{ $topic->user->name }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="row mt-3 justify-content-center">
            {{ $topics->links() }}
        </div>
    </div>
@endsection