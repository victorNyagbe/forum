@extends('layouts.app')

@section('extra-js')
    <script>
        function toggleReplyComment(id)
        {
            let element = document.getElementById('replyComment-' + id);
            element.classList.toggle('d-none');
        }
    </script>
@endsection


@section('content')
    <div class="container">
        <div class="row mt-3">
            <div class="col-12">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        {{ $message }}
                        <button type="button" class="close" aria-label="close" data-dismiss="alert">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $topic->title }}</h5>
                        <p class="card-text">{{ $topic->content }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small>Posté le {{ $topic->created_at->format('d/m/Y à H:m') }}</small>
                            <span class="badge badge-primary">{{ $topic->user->name }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            @can('update', $topic)
                                <a href="{{ route('topics.edit', $topic->id) }}" class="btn btn-warning">Editer ce topic</a>
                            @endcan
                            
                            @can('delete', $topic)
                                <form action="{{ route('topics.destroy', $topic->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Supprimer</button>
                                </form>
                            @endcan
                            
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">               
                <hr>
                <h5 class="h5-responsive">Commentaires</h5>
                @forelse ($topic->comments as $comment)
                    <div class="card mb-3 @if($topic->solution == $comment->id) border border-success @endif">
                        <div class="card-body d-flex justify-content-between">
                            <div>
                                {{ $comment->content }}
                                <div class="d-flex justify-content-between align-items-center">
                                    <small>Posté le {{ $comment->created_at->format('d/m/Y') }}</small>
                                    <span class="badge badge-primary">{{ $comment->user->name }}</span>
                                </div>
                            </div>
                            <div>
                                @guest
                                    @if ($topic->solution == $comment->id)
                                        <h4><span class="badge badge-success">Marqué comme solution</span></h4>
                                    @endif
                                @endguest
                                @auth
                                    @if (!$topic->solution && auth()->user()->id == $topic->user_id)
                                        <solution-button topic-id="{{ $topic->id }}" comment-id="{{ $comment->id }}"></solution-button>
                                    @else
                                        @if ($topic->solution == $comment->id)
                                            <h4><span class="badge badge-success">Marqué comme solution</span></h4>
                                        @endif
                                    @endif  
                                @endauth
                                
                            </div>
                        </div>
                    </div>
                    @foreach ($comment->comments as $replyComment)
                        <div class="card mb-3 ml-5">
                            <div class="card-body">
                                {{ $replyComment->content }}
                                <div class="d-flex justify-content-between align-items-center">
                                    <small>Posté le {{ $replyComment->created_at->format('d/m/Y') }}</small>
                                    <span class="badge badge-primary">{{ $replyComment->user->name }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @auth
                        <button class="btn btn-info mb-3" onclick="toggleReplyComment({{ $comment->id }})">Répondre</button>
                        <form action="{{ route('comments.storeReply', $comment) }}" method="post" class="ml-5 mb-3 d-none" id="replyComment-{{ $comment->id }}">
                            @csrf
                            <div class="form-group">
                                <label for="replyComment">Ma réponse</label>
                                <textarea name="replyComment" id="replyComment" rows="5" class="form-control @error('replyComment') is-invalid @enderror"></textarea>
                                @error('replyComment')
                                    <div class="invalid-feedback">{{ $errors->first('replyComment') }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Répondre à ce commentaire</button>
                        </form>
                    @endauth
                @empty
                    <div class="alert alert-info">Aucun commentaire pour ce topic.</div>
                @endforelse
                <form action="{{ route('comments.store', $topic) }}" method="post" class="mt-3">
                    @csrf
                    <div class="form-group">
                        <label for="content">Votre commentaire</label>
                        <textarea name="content" id="content" rows="3" class="form-control @error('content') is-invalid @enderror"></textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $errors->first('content') }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Soumettre mon commentaire</button>
                </form>
            </div>
        </div>
    </div>
@endsection