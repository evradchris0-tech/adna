@extends('layout')
@section('title', 'ERREUR 401')
@section('css')
@vite('resources/css/errors.scss')
@endsection

@section('body')
    <div class="error">
        <h1>Erreur : 401</h1>
        <p>Non autorisé.</p>
        <a href="/" class="btn">revenir au dashboard</a>
    </div>
@endsection

