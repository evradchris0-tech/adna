@extends('layout')
@section('title', 'AJOUT-COTISATION')
@section('css')
@vite('resources/css/paroisse.scss')
@vite('resources/css/engagement.scss')
@endsection
@section('body')
    <livewire:AddCotisation :id="$id">
@endsection
