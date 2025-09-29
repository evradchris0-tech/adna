@extends('layout')
@section('title', 'ERREUR 500')
@section('css')
@vite('resources/css/errors.scss')
@endsection

@section('body')
    <div class="error">
        <h1>Erreur : 500</h1>
        <p>Server Error</p>
        <a href="/" class="btn">revenir au dashboard</a>
    </div>
@endsection

