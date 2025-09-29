@extends('layout')
@section('title', 'ENGAGEMENTS')
@section('css')
@vite('resources/css/paroisse.scss')
@vite('resources/css/engagement.scss')
@endsection
@section('body')
    @livewire('EngagementTable')
@endsection
