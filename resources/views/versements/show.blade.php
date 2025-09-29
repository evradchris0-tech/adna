@extends('layout')
@section('title', 'DASHBOARD')
@section('css')
    @vite('resources/css/paroisse.scss')
    @vite('resources/css/associations.scss')
@endsection
@section('body')
    <livewire:VersementShowTable id="{{$id}}">
@endsection

