@extends('layout')
@section('title', 'AJOUT-PAROISSIEN')
@section('css')
    @vite('resources/css/paroisse.scss')
    @vite('resources/css/engagement.scss')
    @vite('resources/css/ajout_paroisse.scss')
@endsection
@section('body')
    <div class="body_header d_flex jc_between ai_center">
        <h1 class="body_header_title">Ajouter un paroissien</h1>
    </div>
    <div class="card">
        <div class="card_content">
            <livewire:AddParoissien :id="$id">
        </div>
    </div>
@endsection
