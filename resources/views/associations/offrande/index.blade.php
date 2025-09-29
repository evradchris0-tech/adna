@extends('layout')
@section('title', 'Offrandes')
@section('css')
    @vite('resources/css/paroisse.scss')
    @vite('resources/css/associations.scss')
@endsection
@section('body')
    @livewire('OffrandeTable', ['id' => $id])
@endsection
