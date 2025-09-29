<th wire:click="setOrderField('{{$name}}')">
    {{$label}}
    @if ($visible)
        @if ($direction == 'ASC')
            <i class="fa-solid fa-sort-up"></i>
        @else
            <i class="fa-solid fa-sort-down"></i>
        @endif
    @endif
</th>
