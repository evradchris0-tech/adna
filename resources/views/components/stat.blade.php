<div class="stats {{$style ?? ''}}">
    @foreach ($items as $it)
        <div class="stat {{$it['class']}}">
            <span class="icon">
                {{$it['icon']}}
            </span>
            <span class="text">
                <h4>{{$it['title']}}</h4>
                <h2>{{ $it['value'] }}</h2>
            </span>
        </div>
    @endforeach
</div>
