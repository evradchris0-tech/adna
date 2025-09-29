@extends('layout')
@section('title', 'PAROISSIENS')
@section('css')
@vite('resources/css/paroisse.scss')
@endsection

@section('body')
@livewire('ParoissienTable')
@endsection
