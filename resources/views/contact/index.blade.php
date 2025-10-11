@extends('layouts.app')

@section('title', 'Contact')

@section('content')
<div class="py-5" style="background-image: url('{{ asset('images/fond-contact.jpg') }}'); background-size: cover; background-position: center; min-height: 100vh;">
    <div class="container">
        <div class="p-5 rounded-4 mx-auto shadow-lg" style="max-width: 700px; backdrop-filter: blur(12px); background-color: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.3); color: white;">
            <h1 class="text-center mb-4 text-white fw-bold">📬 Contactez-nous</h1>

            @if(session('success'))
                <div class="alert alert-success bg-success bg-opacity-75 text-white border-0">{{ session('success') }}</div>
            @endif

            <form action="{{ route('contact.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="nom" class="form-label text-white">Nom</label>
                    <input type="text" name="nom" id="nom" class="form-control bg-transparent text-white border-white" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label text-white">Adresse email</label>
                    <input type="email" name="email" id="email" class="form-control bg-transparent text-white border-white" required>
                </div>

                <div class="mb-3">
                    <label for="message" class="form-label text-white">Message</label>
                    <textarea name="message" id="message" rows="5" class="form-control bg-transparent text-white border-white" required></textarea>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-light fw-semibold">📨 Envoyer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
