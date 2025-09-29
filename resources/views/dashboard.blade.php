@extends('layout')
@section('title', 'DASHBOARD')
@section('css')
    @vite('resources/css/index.scss')
    @vite('resources/css/chart.scss')
@endsection
@section('body')
    <div class="body_header d_flex jc_between ai_center">
        <h1 class="body_header_title">Dashboard</h1>
    </div>
    <div class="stats stats_01">
        <div class="stat red">
            <span class="icon">
                <i class="fa fa-users"></i>
            </span>
            <span class="text">
                <h5>Effectif paroissiens</h5>
                <h2>{{ formatNumber($nbParoissien) }}</h2>
            </span>
        </div>
        <div class="stat">
            <span class="icon">
                <i class="fa fa-user"></i>
            </span>
            <span class="text">
                <h5>Effectif Hommes</h5>
                <h2>{{ formatNumber($nbMen) }}</h2>
            </span>
        </div>
        <div class="stat red">
            <span class="icon">
                <i class="fa fa-users"></i>
            </span>
            <span class="text">
                <h5>Effectif Femmes</h5>
                <h2>{{ formatNumber($nbWife) }}</h2>
            </span>
        </div>
        <div class="stat red">
            <span class="icon">
                <i class="fa fa-users"></i>
            </span>
            <span class="text">
                <h5>Anciens d'Eglise</h5>
                <h2>{{ formatNumber($nbAncien) }}</h2>
            </span>
        </div>
        <div class="stat">
            <span class="icon">
                <i class="fa fa-user"></i>
            </span>
            <span class="text">
                <h5>Diacres</h5>
                <h2>{{ formatNumber($nbDiacre) }}</h2>
            </span>
        </div>
        <div class="stat red">
            <span class="icon">
                <i class="fa fa-users"></i>
            </span>
            <span class="text">
                <h5>Reste des fideles</h5>
                <h2>{{ formatNumber($reste) }}</h2>
            </span>
        </div>
    </div>
    <div class="stats">
        <div class="card stat-2 max">
            <div class="header">
                <h5>Taille des associations</h5>
                <h5>Effectif total : {{ formatNumber($nbParoissien) }} membres</h5>
            </div>
            <h4>{{ $statAssociation->count() }}</h4>
            <div class="stat_block">
                @foreach ($statAssociation as $asso)
                    <div class="asso-stat">
                        <div class="label">{{ $asso->sigle }}</div>
                        <div class="percent-bar-wrapper">
                            <div class="percent-value"
                                style="--stat-width: {{ ($asso->paroissiens->count() / ($nbParoissien == 0 ? 1 : $nbParoissien)) * 100 }}%">
                            </div>
                            <div class="percent-value-text"
                                style="--negative-stat-width: -{{ ($asso->paroissiens->count() / ($nbParoissien == 0 ? 1 : $nbParoissien)) * 100 }}%">
                                {{ formatNumber($asso->paroissiens->count()) }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="card stat-2">
            <div class="header">
                <h5>Taux global d'achèvement des engagements(Dime)</h5>
            </div>
            <div class="item html">
                <h2>{{ $assoDimeStat['percent'] }}%</h2>
                <svg width="160" height="160" xmlns="http://www.w3.org/2000/svg">
                    <g>
                        <title>Layer 1</title>
                        <circle class="circle_animation" style="--radius:{{ 440 - $assoDimeStat['percent'] * 4.4 }}"
                            r="69.85699" cy="81" cx="81" stroke-width="12" stroke="#6fdb6f" fill="none" />
                    </g>
                </svg>
            </div>
        </div>
    </div>
    <div class="stats">
        <livewire:DashboardStat>
            <div class="card stat-2">
                <div class="header">
                    <h5>Taux global d'achèvement des engagements(Construction) </h5>
                </div>
                <div class="item css">
                    <h2>{{ $assoConsStat['percent'] }}%</h2>
                    <svg width="160" height="160" xmlns="http://www.w3.org/2000/svg">
                        <g>
                            <title>Layer 1</title>
                            <circle class="circle_animation" style="--radius:{{ 440 - $assoConsStat['percent'] * 4.4 }}"
                                r="69.85699" cy="81" cx="81" stroke-width="12" stroke="#6fdb6f"
                                fill="none" />
                        </g>
                    </svg>
                </div>
            </div>
    </div>
@endsection
