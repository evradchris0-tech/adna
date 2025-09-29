@extends('layout')
@section('title', 'ERREUR 404')
@section('css')
@vite('resources/css/errors.scss')
@endsection

@section('body')
    <div class="error">
        <h1>Erreur : 404</h1>
        <p>Ressource innexistante.</p>
        <a href="/" class="btn">revenir au dashboard</a>
    </div>
@endsection

