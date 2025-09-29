@extends('layout')
@section('title', 'AJOUT-VERSEMENT')
@section('css')
@vite('resources/css/paroisse.scss')
@vite('resources/css/engagement.scss')
@endsection
@section('body')
    <livewire:AddVersement :id="$id">
@endsection
