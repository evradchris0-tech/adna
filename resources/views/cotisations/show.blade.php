@extends('layout')
@section('title', 'COTISATION')
@section('css')
    @vite('resources/css/paroisse.scss')
    @vite('resources/css/associations.scss')
@endsection
@section('body')
    <livewire:CotisationShowTable id="{{$id}}">
@endsection

