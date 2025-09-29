@extends('layout')
@section('title', 'AJOUT-ENGAGEMENT')
@section('css')
@vite('resources/css/paroisse.scss')
@vite('resources/css/engagement.scss')
@endsection
@section('body')
    <livewire:AddEngagement :id="$id">
@endsection
