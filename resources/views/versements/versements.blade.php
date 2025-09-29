@extends('layout')
@section('title', 'VERSEMENTS')
@section('css')
    @vite('resources/css/paroisse.scss')
    @vite('resources/css/associations.scss')
@endsection
@section('body')
    <livewire:VersementTable>
@endsection

