@extends('layout')
@section('title', 'PERFORMANCE')
@section('css')
    @vite('resources/css/paroisse.scss')
@endsection
@section('body')
    <livewire:PerformanceDetailTable id="{{$id}}">
@endsection
