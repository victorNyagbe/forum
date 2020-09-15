@extends('layouts.app')

@section('extra-js')
    {!! NoCaptcha::renderJs() !!}
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3>Creer topic</h3>
                <hr>

                <form action="{{ route('topics.store') }}" method="post">
                    @csrf

                    <div class="form-group">
                        <label for="title">Titre</label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror">
                        @error('title')
                            <div class="invalid-feedback">{{ $errors->first('title') }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="content">Contenu</label>
                        <textarea name="content" id="content" rows="10" class="form-control @error('content') is-invalid @enderror"></textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $errors->first('content') }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        {!! NoCaptcha::display() !!}
                        @if ($errors->has('g-recaptcha-response'))
                            <span class="help-block red-text">
                                <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success">enregistrer le topic</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection