@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Modifier la demande</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('demandes.update', $demande) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $demande->description) }}</textarea>
            @error('description') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary"> Enregistrer</button>
        <a href="{{ route('demandes.index') }}" class="btn btn-secondary">⬅ Retour</a>
    </form>
</div>
@endsection
