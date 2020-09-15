@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3>{{ $topic->title }}</h3>
                <hr>

                <form action="{{ route('topics.update', $topic->id) }}" method="post">

                    @csrf
                    @method('PATCH')

                    <div class="form-group">
                        <label for="title">Titre</label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ $topic->title }}">
                        @error('title')
                            <div class="invalid-feedback">{{ $errors->first('title') }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="content">Contenu</label>
                        <textarea name="content" id="content" rows="10" class="form-control @error('content') is-invalid @enderror">{{ $topic->content }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $errors->first('content') }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Modifier le topic</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection