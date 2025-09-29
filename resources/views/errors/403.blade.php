@extends('layout')
@section('title', 'ERREUR 403')
@section('css')
@vite('resources/css/errors.scss')
@endsection

@section('body')
    <div class="error">
        <h1>Erreur : 403</h1>
        <p>{{__($exception->getMessage() ?: 'Forbidden')}}</p>
    </div>
@endsection
