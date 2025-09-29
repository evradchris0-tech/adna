@extends('layout')
@section('title', 'GESTIONNAIRE')
@section('css')
    @vite('resources/css/paroisse.scss')
@endsection
@section('body')
    @livewire('GestionnaireTable')
@endsection
