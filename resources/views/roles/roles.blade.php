@extends('layout')
@section('title', 'ROLES')
@section('css')
    @vite('resources/css/paroisse.scss')
    @vite('resources/css/associations.scss')
    @vite('resources/css/roles.scss')
@endsection
@section('body')
    <livewire:RolesTable>
@endsection
